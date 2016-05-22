<?php

namespace App\Controllers;

use App\Lib\ImgShop;
use App\Lib\SetupService;
use App\Models\DailyImpression;
use App\Models\Image;
use App\Models\Product;
use App\Models\User;
use App\Models\Tag;
use App\Models\TagsIcons;
use Auth;
use DB;
use Hash;
use Input;
use Symfony\Component\HttpFoundation\JsonResponse;
use URL;
use Validator;
use View;
use Request;
use Config;
use Redirect;
use Session;
use Intervention\Image\ImageManagerStatic as ImageUpload;
use Illuminate\Support\MessageBag;
use Mail;

class AppController extends BaseController
{
    public function __construct(SetupService $setupService)
    {
        $this->setupService = $setupService;
    }

    /**
     * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function login()
    {
        if (Request::isMethod('post')) {
            $credentials = Request::only(['username', 'password']);

            $validator = \Validator::make($credentials, [
                'username' => 'required',
                'password' => 'required'
            ]);

            if ($validator->fails()) {
                return Redirect::route('app.login')
                    ->withErrors($validator->errors());
            }

            if (Auth::attempt($credentials, Request::has('remember_me'))) {
                return Redirect::route('app.dashboard');
            }

            return Redirect::route('app.login')
                ->withInput()
                ->withErrors(['Benutzername oder Passwort sind falsch!']);
        }

        return View::make('app.login');
    }

    public function register()
    {
        if (Request::isMethod('post')) {
            $credentials = Request::only(['username', 'password', 'email']);

            $validator = \Validator::make($credentials, [
                'username' => 'required|min:5',
                'password' => 'required',
                'email' => 'required'
            ]);

            if ($validator->fails()) {
                return Redirect::route('app.register')
                    ->withErrors($validator->errors());
            }

            if (Auth::attempt($credentials, Request::has('remember_me'))) {
                return Redirect::route('app.dashboard');
            }

            $Validity_Username = User::where('username', Input::get('username'))->count();
            $Validity_Email = User::where('email', Input::get('email'))->count();

            if($Validity_Username == 0 && $Validity_Email == 0) {
                $code = str_random(11);
                // Create User
                $user = new User();
                $user->username = Input::get('username');
                $user->password = Hash::make(Input::get('password'));
                $user->email = Input::get('email');
                $user->code = $code;
                $user->save();

                // Save installation information
                $this->setupService->setInfo([
                    'version' => ImgShop::VERSION,
                    'created_at' => (new \DateTime())->format('Y-m-d H:i:s'),
                    'updated_at' => (new \DateTime())->format('Y-m-d H:i:s'),
                ]);

                Mail::send('emails.auth.register', array('username' => Input::get('username'), 'code' => $code), function($message)
                {
                    $message->from('info@imagemarker.com', 'ImageMarker');

                    $message->to(Input::get('email'), Input::get('username'))->subject('Ihre Anmeldung auf ImageMarker');
                });

                // Login User
                Auth::login($user);

                // Allow user to see message... durrr!! :)
                sleep(1);
            } else {
                return Redirect::route('app.register')
                    ->withErrors(['Der Username oder die E-Mail existiert bereits']);
            }

            return Redirect::route('app.register')
                ->withInput()
                ->withErrors(['Usernamen und Passwort ist falsch!']);
        }

        return View::make('app.register');
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        Auth::logout();

        //return Redirect::route('app.login');
        return Redirect::to('http://imagemarker.com/');
    }

    public function activate($code)
    {
        $user = User::where('code', '=', $code)->first();

        if($user->code != '') {
            $update = User::find($user->id);
            $update->status = '1';
            $update->save();

            // Login User
            Auth::login($user);
            
            return Redirect::route('app.dashboard');
        }
    }

    public function resend_email()
    {
        $user = Auth::user();

        Mail::send('emails.auth.register', array('username' => $user->username, 'code' => $user->code), function($message)
        {
            $user = Auth::user();
            $message->from('info@imagemarker.com', 'ImageMarker');
            $message->to($user->email, $user->username)->subject('Ihre Anmeldung auf ImageMarker');
        });
            
        return Redirect::route('app.dashboard');
    }

    public function status()
    {
        $user = Auth::user();

        return View::make('app.status', [
            'user' => $user
        ]);
    }

    public function dashboard()
    {
        $user = Auth::user();

        if(date('Y-m-d') == $user->premium_expire_date) {
            if($user->premium == '1' || $user->premium == '2') {

                $user_update = User::where('id', $user->id)->first();

                $user_update->premium = 0;
                $user_update->premium_expire_date = '';
                $user_update->save();
                
            }
        }

        if($user->lock == '1') {
            return Redirect::route('app.locked');
        }

        if($user->status == '0') {
            return Redirect::route('app.status');
        }

        if($user->admin == '1') {
            $value_count = DB::selectOne('SELECT FORMAT(SUM(price),2) as all_price FROM (SELECT price FROM products ORDER BY price DESC) price;')->all_price;
            if($value_count == NULL) {
                $value_count = '0';
            }
            if(array_get(Auth::user(), 'currency') == 'EUR') {
                $currency = '€';
            } else {
                $currency = array_get(Auth::user(), 'currency');
            }
            $stats = [
                'images_count' => DB::selectOne('SELECT COUNT("id") AS count FROM images')->count,
                'products_count' => DB::selectOne('SELECT COUNT("id") AS count FROM products')->count,
                'tags_count' => DB::selectOne('SELECT COUNT("id") AS count FROM tags')->count,
                'value_count' => $value_count.' '.$currency,
            ];
        } else {
            $value_count = DB::selectOne('SELECT FORMAT(SUM(price),2) as all_price FROM (SELECT price FROM products WHERE user_id="'.$user->id.'" ORDER BY price DESC) price;')->all_price;
            if($value_count == NULL) {
                $value_count = '0';
            }
            if(array_get(Auth::user(), 'currency') == 'EUR') {
                $currency = '€';
            } else {
                $currency = array_get(Auth::user(), 'currency');
            }
            $stats = [
                'images_count' => DB::selectOne('SELECT COUNT("id") AS count FROM images WHERE user_id="'.$user->id.'"')->count,
                'products_count' => DB::selectOne('SELECT COUNT("id") AS count FROM products WHERE user_id="'.$user->id.'"')->count,
                'tags_count' => DB::selectOne('SELECT COUNT("id") AS count FROM tags WHERE user_id="'.$user->id.'"')->count,
                'value_count' => $value_count.' '.$currency,
            ];
        }

        if($user->admin == '1') {
            $recentImages = Image::take(12)
                ->orderBy('created_at', 'DESC')
                ->get();

            $impressions = DailyImpression::where('user_id', $user->id)
                ->orderBy('day', 'DESC')
                ->take(6)
                ->get();
        } else {
            $recentImages = Image::where('user_id', $user->id)
                ->orderBy('created_at', 'DESC')
                ->take(12)
                ->get();

            $impressions = DailyImpression::where('user_id', $user->id)
                ->orderBy('day', 'DESC')
                ->take(6)
                ->get();
        }

        return View::make('app.dashboard', [
            'stats' => $stats,
            'recentImages' => $recentImages,
            'impressions' => $impressions
        ]);
    }

    public function images()
    {
        $user = Auth::user();

        if($user->lock == '1') {
            return Redirect::route('app.locked');
        }

        if($user->status == '0') {
            return Redirect::route('app.status');
        }

        if($user->admin == '1') {
            $images = Image::orderBy('id', 'DESC')->paginate(12);
        } else {
            $images = Image::where('user_id', $user->id)->orderBy('id', 'DESC')->paginate(12);
        }

        return View::make('app.images', [
            'images' => $images
        ]);
    }

    /**
     * @return \Illuminate\View\View
     */
    public function listing()
    {
        $user = Auth::user();

        if($user->lock == '1') {
            return Redirect::route('app.locked');
        }

        if($user->status == '0') {
            return Redirect::route('app.status');
        }

        if($user->admin == '1') {
            $products = Product::orderBy('id', 'DESC')->paginate(12);
        } else {
            $products = Product::where('user_id', $user->id)->orderBy('id', 'DESC')->paginate(12);
        }

        return View::make('app.listing', [
            'products' => $products
        ]);
    }
    

    public function deleteList($id)
    {
          $tag = Tag::where("product_id",$id)->delete();

          $product = Product::find($id);
          $product->delete();         


          return Redirect::back();

    }

    /**
     * @return \Illuminate\View\View
     */
    public function listing_edit($id)
    {
        $user = Auth::user();

        if($user->lock == '1') {
            return Redirect::route('app.locked');
        }

        if($user->status == '0') {
            return Redirect::route('app.status');
        }

        if($user->admin == '1') {
            $product = Product::find($id);

            if(!$product->youtube) {

            $rules = array(
                'title'       => 'required',
                'description'      => 'required'
            );

            } else {

            $rules = array(
                'youtube'       => 'required'
            );

            }

            $validator = Validator::make(Input::all(), $rules);

            // process the login
            if (!$validator->fails()) {

                if(Input::get('price') != '') { if(is_numeric(str_replace(',', '', str_replace('.', '', Input::get('price'))))) { $price = Input::get('price'); } else { $price = 0; } } else { $price = 0; }
            
                $product->title        = Input::get('title');
                $product->description  = Input::get('description');
                $product->price        = $price;
                $product->url          = Input::get('url');
                $product->youtube      = Input::get('youtube');
                $product->save();

                return Redirect::to('listing');
            }
        } else{
        //if($user->premium == '1' || $user->premium == '2') {
            $product = Product::where('user_id', $user->id)->where('id', $id)->first();

            $rules = array(
                'title'       => 'required',
                'description'      => 'required'
            );

            $validator = Validator::make(Input::all(), $rules);

            // process the login
            if (!$validator->fails()) {

                if(Input::get('price') != '') { 
                    if(is_numeric(str_replace(',', '', str_replace('.', '', Input::get('price'))))) { 
                        $price = Input::get('price'); 
                    } else { 
                        $price = 0; 
                    } 
                } else { 
                    $price = 0; 
                }
                //parse_str( parse_url( Input::get('youtube'), PHP_URL_QUERY ), $my_array_of_vars );
                preg_match("#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#", Input::get('youtube'), $matches);

                $product->title        = Input::get('title');
                $product->description  = Input::get('description');
                $product->price        = $price;
                $product->url          = Input::get('url');
                if(isset($matches[0]))
                $product->youtube      = $matches[0];
                if(Input::has('button'))
                $product->button = Input::get('button');
                $product->save();

                return Redirect::to('listing');
            }
        } //else { return Redirect::to('listing');    }

        
        if (Request::isMethod('post')) {
            switch (Request::input('_action')) {
                case 'delete_image':
                    DB::beginTransaction();
                    try {
                        $image->tags()->delete();
                        $image->delete();

                        DB::commit();

                        return Redirect::route('app.images')
                            ->with('success', 'Bild und Inhalte erfolgreich gelöscht.');
                    } catch (\Exception $e) {
                        DB::rollBack();

                        return Redirect::refresh()
                            ->withErrors($e->getMessage());
                    }
                    break;
            }
        }



        $tags = Tag::where('product_id', '=', $id)->first();

        if($user->admin == '1') {
            $image = Image::find($tags->image_id);
        } else {
            $image = Image::where('user_id', $user->id)->find($tags->image_id);
        }

        $image->load(['tags', 'tags.product']);

        $fullurl = url();

        $image = Image::find($tags->image_id);
        $image->load('tags', 'tags.product');

        $tags_icons = TagsIcons::get();

        return View::make('app.listing_edit', [
            'image' => $image,
            'tags_icons' => $tags_icons,
            'action' => '',
            'publicUrl' => route('app.image', ['image_id' => $image->id]),
            'product' => $product
        ]);
    }

    /**
     * @return \Illuminate\View\View
     */
    public function users()
    {
        $user = Auth::user();

        if($user->lock == '1') {
            return Redirect::route('app.locked');
        }

        if($user->status == '0') {
            return Redirect::route('app.status');
        }

        if($user->admin == '1') {
            $users = User::orderBy('id', 'DESC')->paginate(12);
        }

        return View::make('app.users', [
            'users' => $users
        ]);
    }

    /**
     * @return \Illuminate\View\View
     */
    public function users_delete($id)
    {
        $user = Auth::user();

        if($user->lock == '1') {
            return Redirect::route('app.locked');
        }

        if($user->status == '0') {
            return Redirect::route('app.status');
        }

        if($user->admin == '1') {
            $user = User::find($id);
            
            $user->delete();

            return Redirect::to('users');
        }

        return View::make('app.listing_edit')->with('product', $product);
    }

    /**
     * @return \Illuminate\View\View
     */
    public function search()
    {
        $user = Auth::user();

        if($user->lock == '1') {
            return Redirect::route('app.locked');
        }

        if($user->status == '0') {
            return Redirect::route('app.status');
        }

        $term = Input::get('term');

        if($user->admin == '1') {
            $products = Product::where('title', 'LIKE', '%'. $term .'%')->where('description', 'LIKE', '%'. $term .'%')->orderBy('id', 'DESC')->paginate(12);
        } else {
            $products = Product::where('user_id', $user->id)->where('title', 'LIKE', '%'. $term .'%')->where('description', 'LIKE', '%'. $term .'%')->orderBy('id', 'DESC')->paginate(12);
        }

        return View::make('app.listing', [
            'products' => $products->appends(Input::except('term'))
        ]);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function upload()
    {
        $user = Auth::user();

        if($user->lock == '1') {
            return Redirect::route('app.locked');
        }

        if($user->status == '0') {
            return Redirect::route('app.status');
        }
        $errorMessages = new MessageBag;
        $errorMessages->add('limit', 'Sie können Bilder bis max 1MB hochladen. Bitte komprimieren Sie Ihr Bild und versuchen Sie es erneut.');               
              
        if(Input::has("imgsize")){
              if(Input::get("imgsize") == "0"){

                 return Redirect::refresh()->withErrors($errorMessages->all()); 
                 
              }
        }
      

        if (Request::isMethod('post') && Request::hasFile('image')) {

            $validator = Validator::make(Request::file(), [
                'image' => 'required|image|mimes:jpeg,jpg,bmp,png'
            ]);

            if ($validator->fails()) {
                return Redirect::refresh()
                    ->withErrors($validator);
            }

            if (Input::hasFile('image'))
            {

                $file       = Input::file('image');

                /*var_dump($file);
                exit();*/

                $name       = $file->getClientOriginalName();

                list($width, $height) = getimagesize($file->getPathname());


                // open an image file
                $img = ImageUpload::make($file->getRealPath());


                $size = $img->filesize();
                if($size > 1048576)
                    return Redirect::refresh()->withErrors($errorMessages->all()); 
                

                if($user->premium == '0' && $user->admin == '0') {
                    // insert a watermark
                    $img->insert('uploads/watermark.png', 'bottom-right', 40, 20, 40, 40);
                }

                // save image in desired format
                $img->save(public_path(Config::get('imgshop.upload_paths.image')) . '/' . time() . '-' . $name, 70);

                $image_create = Image::create([
                    'name' => $name,
                    'path' => time() . '-' . $name,
                    'size' => $file->getClientSize(),
                    'width' => $width,
                    'height' => $height,
                    'user_id' => $user->id
                ]);

                return Redirect::route('app.tagger', ['id' => $image_create->getAttribute('id')]);
            }
        }

        return View::make('app.upload');
    }

    /**
     * @param $id
     * @return \Illuminate\View\View
     */
    public function tagger($id)
    {
        $user = Auth::user();

        if($user->lock == '1') {
            return Redirect::route('app.locked');
        }

        if($user->status == '0') {
            return Redirect::route('app.status');
        }

        if($user->admin == '1') {
            $image = Image::find($id);
        } else {
            $image = Image::where('user_id', $user->id)->find($id);
        }

        $image->load(['tags', 'tags.product']);

        if (Request::isMethod('post')) {
            switch (Request::input('_action')) {
                case 'delete_image':
                    DB::beginTransaction();
                    try {
                        $image->tags()->delete();
                        $image->delete();

                        DB::commit();

                        return Redirect::route('app.images')
                            ->with('success', 'Bild und Inhalte erfolgreich gelöscht.');
                    } catch (\Exception $e) {
                        DB::rollBack();

                        return Redirect::refresh()
                            ->withErrors($e->getMessage());
                    }
                    break;
            }
        }
        $fullurl = url();
        
        $image = Image::find($id);
        $image->load('tags', 'tags.product');


        if(Auth::user()->premium == 0)
        $tags_icons = TagsIcons::where('user_id', '=', Auth::user()->id)->orwhere('user_id', '=', '0')->orderBy('order')->orderBy('id')->take(14)->get();
        else
        $tags_icons = TagsIcons::where('user_id', '=', Auth::user()->id)->orwhere('user_id', '=', '0')->orderBy('order')->orderBy('id')->get();

        return View::make('app.tagger', [
            'image' => $image,
            'tags_icons' => $tags_icons,
            'action' => '',
            'publicUrl' => route('app.image', ['image_id' => $image->id])
        ]);
    }

    /**
     * @param $id
     * @return \Illuminate\View\View
     */
    public function tagger_code($id, $action)
    {
        $user = Auth::user();

        if($user->lock == '1') {
            return Redirect::route('app.locked');
        }

        if($user->status == '0') {
            return Redirect::route('app.status');
        }

        if($user->admin == '1') {
            $image = Image::find($id);
        } else {
            $image = Image::where('user_id', $user->id)->find($id);
        }

        $image->load(['tags', 'tags.product']);

        $tags_icons = TagsIcons::get();

        return View::make('app.tagger', [
            'image' => $image,
            'tags_icons' => $tags_icons,
            'action' => $action,
            'publicUrl' => route('app.image', ['image_id' => $image->id])
        ]);
    }

    /**
     * @return \Illuminate\View\View
     */
    public function setting()
    {
        $user = Auth::user();

        if($user->lock == '1') {
            return Redirect::route('app.locked');
        }

        if($user->status == '0') {
            return Redirect::route('app.status');
        }

        if($user->admin == '1') {
            $coupons = DB::table('coupons')->where('user_id', '=', '')->orderBy('id', 'DESC')->get();
        } else {
            $coupons = '';
        }

        if (Request::isMethod('post')) {
            $validator = Validator::make(Request::input(), [
                'username' => 'required',
                'email' => 'required'
            ]);

            if ($validator->fails()) {
                return Redirect::to(URL::current())
                    ->withErrors($validator);
            }

            $Validity_Username = User::where('username', Request::input('username'))->count();
            $Validity_Email = User::where('email', Request::input('email'))->count();

            if(!$Validity_Username != 1 && $user->username == Request::input('username')) {
                Auth::user()->username = Request::input('username');
            } else {
                return Redirect::refresh()
                    ->with('error', 'Der Username wird bereits verwendet');
            }

            if(!$Validity_Email != 1 && $user->email == Request::input('email')) {
                Auth::user()->email = Request::input('email');
            } else {
                return Redirect::refresh()
                    ->with('error', 'Diese E-Mail wurde bereits verwendet');
            }
            
            //Auth::user()->admin = '1';

            $password = Request::get('password');
            $currency = Request::input('app_currency');

            if (!empty($currency)) {
                Auth::user()->currency = $currency;
            }

            if (!empty($password)) {
                Auth::user()->password = Hash::make($password);
            }

            if($user->admin == '1') {

                $coupon_code = Request::get('coupon_code');
                if (!empty($coupon_code)) {
                    $coupon_validity = DB::table('coupons')->where('coupon_code', $coupon_code)->count();
                    if($coupon_validity == 0) {
                        DB::insert('
                            INSERT INTO coupons
                            (coupon_code, created_at, updated_at)
                            VALUES
                            (\'' . $coupon_code . '\', \'' . date('Y-m-d H:i:s') . '\', \'' . date('Y-m-d H:i:s') . '\')
                        ');
                    }
                }

            }

            $coupon_code_upgrade = Request::get('coupon_code_upgrade');
            if (!empty($coupon_code_upgrade)) {
                $coupon_validity_check = DB::table('coupons')->where('coupon_code', $coupon_code_upgrade)->where('user_id', '')->count();
                if($coupon_validity_check == 1) {
                    DB::update('
                        UPDATE coupons
                        SET user_id = \'' . $user->id . '\'
                        WHERE coupon_code = \'' . $coupon_code_upgrade . '\'
                    ');
                        
                    $user_update = User::where('id', $user->id)->first();
                    $user_update->premium = 1;
                    $user_update->save();
                }
            }

            if (Auth::user()->save()) {
                return Redirect::refresh()
                    ->with('success', 'Einstellungen wurden gespeichert!');
            }
        }

        $my_coupon = DB::table('coupons')->where('user_id', $user->id);

        $info = $this->setupService->getInfo();

        return View::make('app.setting', [
            'info' => $info,
            'coupons' => $coupons,
            'my_coupon' => $my_coupon
        ])->with('user',$user);
    }

    /**
     * Frontend image View
     *
     * @param $imageId
     * @return \Illuminate\View\View
     */
    public function image($imageId)
    {
        $user = Auth::user();

        if(Auth::check()) {

            if($user->lock == '1') {
                return Redirect::route('app.locked');
            }

        }

        $image = Image::find($imageId);
        $image->load('tags', 'tags.product');

        if (!$image) {
            App::abort(404);

            return null;
        }

        
        $tags_icons = TagsIcons::get();

        return View::make('app.image', [
            'image' => $image,
            'tags_icons' => $tags_icons
        ]);
    }

    /**
     * @return JsonResponse
     */
    public function analytics()
    {
        $user = Auth::user();

        if($user->lock == '1') {
            return Redirect::route('app.locked');
        }
        
        $type = Request::get('type');
        if (!in_array($type, ['impression', 'click'])) {
            return new JsonResponse([
                'error' => ['message' => 'Invalid tracker type']
            ], 400);
        }

        try {

            $today = date('Y-m-d');
            $table = 'daily_' . $type . 's';

            // -- Log Clicks && Impressions

            $exists = DB::selectOne('
                SELECT COUNT("day") AS day_exists
                 FROM ' . $table . '
                 WHERE `day` = \'' . $today . '\' AND `user_id` = \'' . $user->id . '\'
            ')->day_exists > 0;

            if ($exists) {
                DB::update('
                    UPDATE ' . $table . '
                    SET count = count + 1
                    WHERE day = \'' . $today . '\' AND `user_id` = \'' . $user->id . '\'
                ');
            } else {
                DB::insert('
                    INSERT INTO ' . $table . '
                    (day, user_id, count)
                    VALUES
                    (\'' . $today . '\', \'' . $user->id . '\', 1 )
                ');
            }

            // -- Log Per User Impression
            if ($type === 'impression') {
                DB::insert('
                    INSERT INTO impressions
                    (ua, ip, image_id, source, user_id, created_at, updated_at)
                    VALUES
                    (\'' . Request::input('ua') . '\', \'' . Request::input('ip') . '\',
                    \'' . Request::input('image_id') . '\', \'' . Request::input('source') . '\', \'' . $user->id . '\',
                    \'' . date('Y-m-d H:i:s') . '\', \'' . date('Y-m-d H:i:s') . '\')
                ');
            }

            return new JsonResponse(['data' => true]);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => ['message' => $e->getMessage()]]);
        }
    }
}
