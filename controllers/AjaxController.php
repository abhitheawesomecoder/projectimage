<?php

namespace App\Controllers;

use App\Lib\ImgShop;
use App\Lib\SetupService;
use Hash;
use App\Models\User;
use App\Models\Image;
use App\Models\Product;
use App\Models\DailyImpression;
use App\Models\Tag;
use App\Models\TagsIcons;
use Auth;
use DB;
use Illuminate\Http\JsonResponse;
use Request;
use Redirect;
use Input;
use Config;
use Symfony\Component\Process\Exception\RuntimeException;
use Mail;

class AjaxController extends BaseController
{
    /**
     * Tag an Image
     *
     * Data
     *  - image_id
     *  - pos_x
     *  - pos_y
     *  - product_id
     *  - prodct_title
     *  - product_description
     *
     * @return \Illuminate\View\View
     */
    public function __construct(SetupService $setupService)
    {
        $this->setupService = $setupService;
    }
    public function test123(){
/*
           $image = Image::find(378);

        echo var_dump( $image->name);

       foreach ($image->tags()->get() as $img) {

            $image_tags = Tag::find($img->id);

            echo var_dump($image_tags);*/

           /* foreach ($image_tags as $key => $value) {
               
                echo var_dump($value->product_id);
            }   */        
            

          /*  $product = Product::where('id' , '=' , $img->product_id )->first();

            echo  var_dump($product);*/

      //  }



    }
    public function postRemind()
    { $type="password";
        //validate email entered here 
        $rules = array(
            'email'     => 'required|email',
        );
 
        $validator = \Validator::make(Input::all(), $rules);
 
        if ($validator->fails()) 
        {
            //return Redirect::to('password/remind')->withErrors($validator)->withInput(Input::except('password'));
            return json_encode(["code" => 0, "type" => $type, "error" => $validator->errors() ]);

        }else
        {
            $response = \Password::remind(Input::only('email'), function($message) {
                // I'm creating an array with user's info but most likely you can use $user->email or pass $user object to closure later
                $user = array(
                    'email' => Input::get('email')
                );

                // the data that will be passed into the mail view blade template
                $data = array('token' => Input::get('email'));

                $message->to($user['email'])->subject('Ihr neues Passwort');
            });

            switch ($response) {
                case \Password::INVALID_USER:
                    //return Redirect::back()->with('error', Lang::get($response));
                return json_encode(["code" => 0, "type" => $type, "error" => [\Lang::get($response)] ]);
                case \Password::REMINDER_SENT:
                    //return Redirect::back()->with('status', Lang::get($response));
                return json_encode(["code" => 0, "type" => $type, "error" => [\Lang::get($response)] ]);
            }
        }
 
    }
    public function login()
    {   $type = "login"; 
        if (Request::isMethod('post')) {
            $credentials = Request::only(['username', 'password']);

            $validator = \Validator::make($credentials, [
                'username' => 'required',
                'password' => 'required'
            ]);

            if ($validator->fails()) {
                //return Redirect::route('app.login')->withErrors($validator->errors());

                 return json_encode(["code" => 0, "type" => $type, "error" => $validator->errors() ]);

            }

            if (Auth::attempt($credentials, Request::has('remember_me'))) {
                //return Redirect::route('app.dashboard');
                return json_encode(["code" => 2 , "type" => $type]);
            }

            //return Redirect::route('app.login')->withInput()->withErrors(['Benutzername oder Passwort sind falsch!']);
            return json_encode(["code" => 0, "type" => $type, "error" => ['Benutzername oder Passwort sind falsch!'] ]);
        }

        //return View::make('app.login');

        return json_encode(["code" => 1 , "type" => $type]);
    }
    public function register()
    {   $type = "register";
        if (Request::isMethod('post')) {
            $credentials = Request::only(['username', 'password', 'email']);

            $validator = \Validator::make($credentials, [
                'username' => 'required|min:5',
                'password' => 'required',
                'email' => 'required'
            ]);

            if ($validator->fails()) {
              //  return Redirect::route('app.register')->withErrors($validator->errors());
                return json_encode(["code" => 0, "type" => $type, "error" => $validator->errors() ]);
            }

            if (Auth::attempt($credentials, Request::has('remember_me'))) {
                //return Redirect::route('app.dashboard');
                return json_encode(["code" => 2 , "type" => $type]);
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
                return json_encode(["code" => 1 , "type" => $type]);
                // Allow user to see message... durrr!! :)

               // sleep(1);
            } else {
                //return Redirect::route('app.register')->withErrors(['Der Username oder die E-Mail existiert bereits']);
                return json_encode(["code" => 0, "type" => $type, "error" => ['Der Username oder die E-Mail existiert bereits'] ]);

            }

            //return Redirect::route('app.register')->withInput()->withErrors(['Usernamen und Passwort ist falsch!']);
            return json_encode(["code" => 0, "type" => $type, "error" => ['Usernamen und Passwort ist falsch!'] ]);

        }

        //return View::make('app.register');
        return json_encode(["code" => 1 , "type" => $type]);
    }
    public function PremiumAccount()
    {
        $user = Auth::user();
        $data = Request::input();

        if($user->premium == '0') {
            if($user->admin == '0') {
                $products_num = Product::where('user_id', $user->id)->count();
            } else {
                $products_num = 'unlimited';
            }
        } else {
            $products_num = 'unlimited';
        }
        $product = [];

        return new JsonResponse([
            'products' => $products_num,
            'free_max_products' => Config::get('imgshop.free_max_products')
        ]);
    }

    public function DeleteCoupon()
    {
        $user = Auth::user();
        $coupon_code = Request::get('coupon_code');

        if($user->admin == '1') {
            $coupons = DB::table('coupons')->where('coupon_code', $coupon_code)->delete();
        }

        return 'true';
    }

    public function UploadPremiumIcon() {
        $user = Auth::user();
        $data = Request::input();

        $product = TagsIcons::create([
            'image' => $data['image'],
            'premium' => '1',
            'user_id' => $user->id
        ]);

       // $tags_icons = TagsIcons::get();

       // $tags_icons = TagsIcons::where('user_id', '=', Auth::user()->id)->orwhere('user_id', '=', '0')->get();

        if(Auth::user()->premium == 0)
        $tags_icons = TagsIcons::where('user_id', '=', Auth::user()->id)->orwhere('user_id', '=', '0')->orderBy('order')->orderBy('id')->take(14)->get();
        else
        $tags_icons = TagsIcons::where('user_id', '=', Auth::user()->id)->orwhere('user_id', '=', '0')->orderBy('order')->orderBy('id')->get();


        $html = '<span style="float: left; padding-right: 10px; padding-bottom: 5px; color: #000;"><b>Wählen</b> Sie ein Icon aus:</span><br><br>';

           /*     <a id="color-blue" class="color-blue" style="margin-left: 5px; margin-top: 5px; display: inline-block;"><img width="30" height="30" src="http://imagemarker.com/apps/icons/imagemarker/blue.png"></a>
                <a id="color-black" class="color-black" style="margin-left: 5px; margin-top: 5px; display: inline-block;"><img width="30" height="30" src="http://imagemarker.com/apps/icons/imagemarker/black.png"></a>
                <a id="color-green" class="color-green" style="margin-left: 5px; margin-top: 5px; display: inline-block;"><img width="30" height="30" src="http://imagemarker.com/apps/icons/imagemarker/green.png"></a>
                <a id="color-yellow" class="color-yellow" style="margin-left: 5px; margin-top: 5px; display: inline-block;"><img width="30" height="30" src="http://imagemarker.com/apps/icons/imagemarker/yellow.png"></a>';         
*/
        foreach($tags_icons as $icon) {

            if(Auth::user()->premium == 2){

            $html .= '<a id="color-'.$icon->id.'" class="color-'.$icon->id.' color-n" style="margin-left: 5px; margin-top: 5px; display: inline-block;"><img width="30" height="30" src="'.$icon->image.'"></a>';
           
            }else{  
                          
                    if($icon->premium == '1' && Auth::user()->premium == '1' || Auth::user()->premium == '2') {
                        $html .= '<a id="color-'.$icon->id.'" class="color-'.$icon->id.' color-n" style="margin-left: 5px; margin-top: 5px; display: inline-block;"><img width="30" height="30" src="'.$icon->image.'"></a>';
                    }

                    if($icon->premium == '0') {
                        $html .= '<a id="color-'.$icon->id.'" class="color-'.$icon->id.' color-n" style="margin-left: 5px; margin-top: 5px; display: inline-block;"><img width="30" height="30" src="'.$icon->image.'"></a>';
                    }

                    if($icon->premium == '1' && Auth::user()->admin == '1' && Auth::user()->premium == '0') {
                        $html .= '<a id="color-'.$icon->id.'" class="color-'.$icon->id.' color-n" style="margin-left: 5px; margin-top: 5px; display: inline-block;"><img width="30" height="30" src="'.$icon->image.'"></a>';
                    }
                }

            $html .= '<style>.tag-image-marker.i'.$icon->id.' {
  background: url("'.$icon->image.'");
  opacity: 1.0;
  width: 30px;
  height: 30px;
  background-size: 30px;
  border-radius: 0%!important;
}</style>';

    $html .= "<script>
            $('#color-".$icon->id."').click(function() {
              $('#color-div .active').removeClass('active');
              $(this).toggleClass('active');
              document.getElementById('color').value = 'i".$icon->id."';
              document.getElementById('color').checked = true;
            });
            </script>";

        }

        return $html;
    }

    public function addImageTag()
    {
        $user = Auth::user();
        $data = Request::input();
        $product = [];

        if($data['product_price'] != '') { if(is_numeric(str_replace('.', '', str_replace(',', '', $data['product_price'])))) { $price = $data['product_price']; } else { $price = 0; } } else { $price = 0; }
            

        $exists = Product::where('type', '=', $data['type'])
                    ->where('title', '=', $data['product_id'])
                    ->where('description', '=', $data['product_description'])
                    ->where('price', '=', str_replace('.', ',', $price))
                    ->where('user_id', '=', $user->id);

        if (!$exists->exists() && $data['type'] == 'image') {

            if($data['product_price'] != '') { if(is_numeric(str_replace('.', '', str_replace(',', '', $data['product_price'])))) { $price = $data['product_price']; } else { $price = 0; } } else { $price = 0; }
            // we create a new product If product id is not passed
                
                $product = Product::create([
                    'type' => $data['type'],
                    'title' => $data['product_title'],
                    'description' => $data['product_description'],
                    'price' => str_replace('.', ',', $price),
                    'currency' => Auth::user()->currency,
                    'url' => $data['product_url'],
                    'youtube' => $data['product_youtube'],
                    'button' => $data['product_button'],
                    'image' => $data['product_image'],
                    'user_id' => $user->id
                ]);

            $data['product_id'] = $product->id;
        }
        elseif (!$exists->exists() && $data['type'] == 'video') {

            if($data['product_price'] != '') { if(is_numeric(str_replace('.', '', str_replace(',', '', $data['product_price'])))) { $price = $data['product_price']; } else { $price = 0; } } else { $price = 0; }
            // we create a new product If product id is not passed
            
                $product = Product::create([
                    'type' => $data['type'],
                    'youtube' => $data['product_youtube'],
                    'price' => str_replace('.', ',', $price),
                    'currency' => Auth::user()->currency,
                    'url' => $data['product_url'],
                    'image' => $data['product_image'],
                    'user_id' => $user->id
                ]);

            $data['product_id'] = $product->id;
        }
        elseif (!$exists->exists() && $data['type'] == 'paynow') {

            if($data['product_price'] != '') { if(is_numeric(str_replace('.', '', str_replace(',', '', $data['product_price'])))) { $price = $data['product_price']; } else { $price = 0; } } else { $price = 0; }
            // we create a new product If product id is not passed
            
                $product = Product::create([
                    'type' => $data['type'],
                    'title' => $data['product_title'],
                    'description' => $data['product_description'],
                    'price' => str_replace('.', ',', $price),
                    'currency' => Auth::user()->currency,
                    'paypal' => $data['product_paypal'],
                    'image' => $data['product_image'],
                    'user_id' => $user->id
                ]);

            $data['product_id'] = $product->id;
        }

        $tag = Tag::create([
            'color' => $data['color'],
            'pos_x' => $data['pos_x'],
            'pos_y' => $data['pos_y'],
            'image_id' => $data['image_id'],
            'product_id' => $data['product_id'],
            'user_id' => $user->id
        ]);

        $product = Product::find($data['product_id']);
        $tag['product'] = $product;

        return new JsonResponse([
            'data' => $tag
        ]);
    }

    public function removeImageTag()
    {
        $user = Auth::user();

        $imageId = Request::get('image_id');
        $tagId = Request::get('tag_id');

        Tag::find($tagId)->product()->delete();

        if (Tag::find($tagId)->delete()) {
            $image = Image::find($imageId);
            $image->load(['tags', 'tags.product']);

            if (!$image) {
                App::abort(404);

                return null;
            }

            return new JsonResponse([
                'tagger_image_tags' => $image->tags,
                'data' => $tagId
            ]);
        }

        throw new RuntimeException();
    }

    /**
     * Search products
     *
     *  - term
     *
     * @return JsonResponse
     */
    public function searchProducts()
    {
        $user = Auth::user();
        if (!Request::has('term') || Request::input('term') === '') {
            return new JsonResponse([
                'data' => []
            ]);
        }

        $query = '%' . filter_var(Request::input('term'), FILTER_SANITIZE_STRING) . '%';
        $products = Product::where('title', 'like', $query)->where('user_id', '=', $user->id)->get();

        return new JsonResponse([
            'data' => $products->toArray()
        ]);
    }

    /**
     * @return JsonResponse
     */
    public function deleteImage()
    {
        if (!Request::has('image_id')) {
            return new JsonResponse([
                'data' => []
            ], 400);
        }       


        DB::beginTransaction();
        try {
            $image = Image::find(Request::input('image_id'));
            $user_id = Auth::user()->id;
            foreach ($image->tags()->get() as $img) {
              //  $image_tags = Tag::find($img->id)->get();

            //    foreach ($image_tags as $tag) {
                    $product = Product::where('id' , '=' , $img->product_id )->delete();
            //    }
            }

            $image->tags()->delete();
            $image->delete();

            DB::commit();

            return new JsonResponse([]);
        } catch (\Exception $e) {
            DB::rollBack();

            return new JsonResponse([
                'message' => $e->getMessage()
            ], 400);
        }
    }
     public function sendMail(){

      if(Request::has('url')){

        return json_encode(["code" => 0 , "msg" => ""]); 

       }

       if(!Request::has('name')){

        return json_encode(["code" => 0 , "msg" => Config::get('contact.errorname')]); 

       }

       if(!Request::has('email')){

        return json_encode(["code" => 0 , "msg" => Config::get('contact.erroremail')]); 
        
       }

       if(!Request::has('message')){

        return json_encode(["code" => 0 , "msg" => Config::get('contact.errormessage')]); 
        
       }
       $name = Request::get('name');

       $email = Request::get('email');

       $mess = Request::get('message');

        Mail::send('emails.contact', array('mess' => $mess), function($message)
        {           
            $message->from( Request::get('email'), Request::get('name'));
            $message->to(Config::get('contact.email'),"ImageMarker" )->subject('Contact Us');
        });

        return json_encode(["code" => 1 , "msg" => Config::get('contact.successmessage')]);

     }



}
