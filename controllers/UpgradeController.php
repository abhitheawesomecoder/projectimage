<?php

use App\Models\User;
use App\Models\DailyImpression;
use App\Models\Image;
use App\Models\Product;
use App\Models\Payment as Pay;
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\ExecutePayment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\Transaction;

class UpgradeController extends Controller
{

    private $_api_context;

    public function __construct()
    {
        // setup PayPal api context
        $paypal_conf = Config::get('paypal');
        $this->_api_context = new ApiContext(new OAuthTokenCredential($paypal_conf['client_id'], $paypal_conf['secret']));
        $this->_api_context->setConfig($paypal_conf['settings']);
    }

    public function getPayment() {
        $user = Auth::user();

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
            $recentImages = Image::take(3)
                ->orderBy('created_at', 'DESC')
                ->get();

            $impressions = DailyImpression::take(6)
                ->orderBy('day', 'DESC')
                ->get();
        } else {
            $recentImages = Image::where('user_id', $user->id)
                ->orderBy('created_at', 'DESC')
                ->take(3)
                ->get();

            $impressions = DailyImpression::where('user_id', $user->id)
                ->orderBy('day', 'DESC')
                ->take(6)
                ->get();
        }

        return View::make('app.upgrade', [
            'stats' => $stats,
            'impressions' => $impressions
        ]);
    }

    public function account_locked() {
        $user = Auth::user();
        if($user->admin == '1' || $user->premium == '1') {
            return Redirect::route('app.dashboard');
        } else {
            return View::make('app.account_locked');
        }
    }

    public function PayNow($image_id, $product_id)
    {
        $user = Auth::user();

        $products = Product::where('id', $product_id)->get();

        foreach($products as $product)
		{
			$image = Image::where('id', $image_id)->get();
	        if($user->premium == '0' && $user->lock == '0') {
	            $payer = new Payer();
	            $payer->setPaymentMethod('paypal');

	            $item_1 = new Item();
	            $item_1->setName($product->title) // item name
	                ->setCurrency(Config::get('paypal.settings.currency'))
	                ->setQuantity(1)
	                ->setPrice($product->price); // unit price

	            // add item to list
	            $item_list = new ItemList();
	            $item_list->setItems(array($item_1));

	            $amount = new Amount();
	            $amount->setCurrency(Config::get('paypal.settings.currency'))
	                ->setTotal($product->price);

	            $transaction = new Transaction();
	            $transaction->setAmount($amount)
	                ->setItemList($item_list)
	                ->setDescription($product->description);

	            $redirect_urls = new RedirectUrls();
	            $redirect_urls->setReturnUrl(URL::route('payment.status'))
	                ->setCancelUrl(URL::route('payment.status'));

	            $payment = new Payment();
	            $payment->setIntent('Order')
	                ->setPayer($payer)
	                ->setRedirectUrls($redirect_urls)
	                ->setTransactions(array($transaction));

	            try {
	                $payment->create($this->_api_context);
	            } catch (\PayPal\Exception\PPConnectionException $ex) {
	                if (\Config::get('app.debug')) {
	                    echo "Exception: " . $ex->getMessage() . PHP_EOL;
	                    $err_data = json_decode($ex->getData(), true);
	                    exit;
	                } else {
	                    die('Some error occur, sorry for inconvenient');
	                }
	            }

	            foreach($payment->getLinks() as $link) {
	                if($link->getRel() == 'approval_url') {
	                    $redirect_url = $link->getHref();
	                    break;
	                }
	            }

	            // add payment ID to session
	            Session::put('paypal_payment_id', $payment->getId());

	            if(isset($redirect_url)) {
	                // redirect to paypal
	                return Redirect::away($redirect_url);
	            }

	            return Redirect::route('image.tag.paynow')
	                ->with('error', 'Unknown error occurred');
	        } else {
	            if($user->premium == '1') {
	                return Redirect::route('image.tag.paynow')
	                    ->with('success', 'You have already made payment !');
	            }
	            elseif($user->premium == '1') {
	                return Redirect::route('image.tag.paynow')
	                    ->with('error', 'Your account is locked !');
	            }
	        }
	    }
    }

    public function postPayment($premium_id)
    {
        $user = Auth::user();

        if($user->premium == '0' && $user->lock == '0') {
            $payer = new Payer();
            $payer->setPaymentMethod('paypal');

            if($premium_id == '1') {
                $premium_price = Config::get('paypal.settings.price');
            }
            elseif($premium_id == '2') {
                $premium_price = Config::get('paypal.settings.price2');
            }

            $item_1 = new Item();
            $item_1->setName(Config::get('paypal.settings.service')) // item name
                ->setCurrency(Config::get('paypal.settings.currency'))
                ->setQuantity(1)
                ->setPrice($premium_price); // unit price

            // add item to list
            $item_list = new ItemList();
            $item_list->setItems(array($item_1));

            $amount = new Amount();
            $amount->setCurrency(Config::get('paypal.settings.currency'))
                ->setTotal($premium_price);

            $transaction = new Transaction();
            $transaction->setAmount($amount)
                ->setItemList($item_list)
                ->setDescription(Config::get('paypal.settings.description'));

            $redirect_urls = new RedirectUrls();
            $redirect_urls->setReturnUrl(URL::route('payment.status'))
                ->setCancelUrl(URL::route('payment.status'));

            $payment = new Payment();
            $payment->setIntent('sale')
                ->setPayer($payer)
                ->setRedirectUrls($redirect_urls)
                ->setTransactions(array($transaction));

            try {
                $payment->create($this->_api_context);
            } catch (\PayPal\Exception\PPConnectionException $ex) {
                if (\Config::get('app.debug')) {
                    echo "Exception: " . $ex->getMessage() . PHP_EOL;
                    $err_data = json_decode($ex->getData(), true);
                    exit;
                } else {
                    die('Some error occur, sorry for inconvenient');
                }
            }

            foreach($payment->getLinks() as $link) {
                if($link->getRel() == 'approval_url') {
                    $redirect_url = $link->getHref();
                    break;
                }
            }

            // add payment ID to session
            Session::put('paypal_payment_id', $payment->getId());

            if(isset($redirect_url)) {
                // redirect to paypal
                return Redirect::away($redirect_url);
            }

            return Redirect::route('app.dashboard')
                ->with('error', 'Unknown error occurred');
        } else {
            if($user->premium == '1') {
                return Redirect::route('app.dashboard')
                    ->with('success', 'Sie sind bereits Premium-Mitglied!');
            }
            elseif($user->premium == '1') {
                return Redirect::route('app.dashboard')
                    ->with('error', 'Your account is locked !');
            }
        }
    }

    public function getPaymentStatus($premium_id)
    {
        $user = Auth::user();

        if($user->premium == '0' && $user->lock == '0') {
            // Get the payment ID before session clear
            $payment_id = Session::get('paypal_payment_id');

            // clear the session payment ID
            Session::forget('paypal_payment_id');

            if (!trim(Input::get('PayerID')) || !trim(Input::get('token'))) {
                return Redirect::route('app.dashboard')
                    ->with('error', 'Zahlung abgebrochen');
            }

            $payment = Payment::get($payment_id, $this->_api_context);

            // PaymentExecution object includes information necessary 
            // to execute a PayPal account payment. 
            // The payer_id is added to the request query parameters
            // when the user is redirected from paypal back to your site
            $execution = new PaymentExecution();
            $execution->setPayerId(Input::get('PayerID'));

            //Execute the payment
            $result = $payment->execute($execution, $this->_api_context);

            //echo '<pre>';print_r($result);echo '</pre>';exit; // DEBUG RESULT, remove it later

            if ($result->getState() == 'approved') { // payment made
                Pay::create([
                    'user_id' => $user->id,
                    'BuyerName' => $result->payer->payer_info->first_name.' '.$result->payer->payer_info->last_name,
                    'BuyerEmail' => $result->payer->payer_info->email,
                    'PaymentID' => $result->id,
                    'ItemAmount' => $result->transactions[0]->amount->total,
                    'ItemCurrency' => $result->transactions[0]->amount->currency
                ]);

                if($premium_id == '1') {
                    $premium_expire_date = date('Y-m-d', strtotime("+12 months", strtotime($effectiveDate)));
                }
                elseif($premium_id == '2') {
                    $premium_expire_date = date('Y-m-d', strtotime("+12 months", strtotime($effectiveDate)));
                }

                $user_update = User::where('id', $user->id)->first();

                $user_update->premium = $premium_id;
                $user_update->premium_expire_date = $premium_expire_date;
                $user_update->save();

                return Redirect::route('app.dashboard')
                    ->with('success', 'Vielen Dank! Ihr Account wurde geupgradet.');
            }

            $user_update = User::where('id', $user->id)->first();

            $user_update->premium = 0;
            $user_update->lock = 1;
            $user_update->save();

            return Redirect::route('app.dashboard')
                ->with('error', 'Zahlung abgebrochen');
        } else {
            if($user->premium == '1') {
                return Redirect::route('app.dashboard')
                    ->with('success', 'Sie sind bereits Premium-Mitglied!');
            }
            elseif($user->premium == '1') {
                return Redirect::route('app.dashboard')
                    ->with('error', 'Your account is locked !');
            }
        }
    }

}