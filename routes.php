<?php

Route::match(['GET', 'POST'], '/test123', [
    'as' => 'test123',    
    'uses' => 'App\Controllers\AjaxController@test123'
]);

Route::get('mail',array('as'=>'mail','uses'=> 'App\Controllers\AjaxController@sendMail'));

Route::get('locked', array(
    'as' => 'app.locked',
    'uses' => 'UpgradeController@account_locked',
));

Route::get('upgrade', array(
    'as' => 'payment',
    'uses' => 'UpgradeController@getPayment',
));

Route::post('upgrade/{premium_id}', array(
    'as' => 'payment.post',
    'uses' => 'UpgradeController@postPayment',
));

Route::match(['GET', 'POST'], '/images/{image_id}/paynow/{product_id}', [
    'as' => 'image.tag.paynow',
    'uses' => 'UpgradeController@PayNow',
]);

Route::get('upgrade/status/{premium_id}', array(
    'as' => 'payment.status',
    'uses' => 'UpgradeController@getPaymentStatus',
));

Route::match(['GET', 'POST'], '/register', [
    'as' => 'app.register',
    'before' => ['guest'],
    'uses' => 'App\Controllers\AppController@register'
]);

Route::match(['GET', 'POST'], '/register/ajax', [
    'as' => 'app.register.ajax',    
    'uses' => 'App\Controllers\AjaxController@register'
]);

Route::get('password/remind', array(
    'as' => 'remind',
    'uses' => 'RemindersController@getRemind'
));

Route::post('password/remind', array(
    'uses' => 'RemindersController@postRemind'
));

Route::post('password/remind/ajax', array(
    'uses' => 'App\Controllers\AjaxController@postRemind'
));

Route::get('password/reset/{token}', array(
    'as' => 'reset',
    'uses' => 'RemindersController@getReset'
));

Route::post('password/reset/{token}', array(
    'uses' => 'RemindersController@postReset'
));

Route::match(['GET'], '/logout', [
    'as' => 'app.logout',
    'before' => ['auth'],
    'uses' => 'App\Controllers\AppController@logout'
]);

Route::match(['GET', 'POST'], '/loginajax', [
    'as' => 'app.login.ajax',
    'uses' => 'App\Controllers\AjaxController@login'
]);


Route::match(['GET', 'POST'], '/', [
    'as' => 'app.login',
    'before' => ['guest'],
    'uses' => 'App\Controllers\AppController@login'
]);

Route::match(['GET'], '/images/{image_id}', [
    'before' => [],
    'as' => 'app.image',
    'uses' => 'App\Controllers\AppController@image'
]);

Route::match(['GET'], '/dashboard', [
    'as' => 'app.dashboard',
    'before' => ['auth'],
    'uses' => 'App\Controllers\AppController@dashboard'
]);

Route::match(['GET'], '/status', [
    'as' => 'app.status',
    'before' => ['auth'],
    'uses' => 'App\Controllers\AppController@status'
]);

Route::match(['GET'], '/resend_email', [
    'as' => 'app.resend_email',
    'before' => ['auth'],
    'uses' => 'App\Controllers\AppController@resend_email'
]);

Route::match(['GET'], '/listing', [
    'as' => 'app.listing',
    'before' => ['auth'],
    'uses' => 'App\Controllers\AppController@listing'
]);

Route::match(['GET', 'POST'], '/listing/edit/{id}', [
    'as' => 'app.listing_edit',
    'before' => ['auth'],
    'uses' => 'App\Controllers\AppController@listing_edit'
]);

Route::match(['GET'], '/users', [
    'as' => 'app.users',
    'before' => ['auth'],
    'uses' => 'App\Controllers\AppController@users'
]);

Route::match(['GET', 'POST'], '/users/delete/{id}', [
    'as' => 'app.users_delete',
    'before' => ['auth'],
    'uses' => 'App\Controllers\AppController@users_delete'
]);

Route::match(['GET', 'POST'], '/settings', [
    'as' => 'app.setting',
    'before' => ['auth'],
    'uses' => 'App\Controllers\AppController@setting'
]);

Route::match(['GET', 'POST'], '/search', [
    'as' => 'app.tagger',
    'before' => ['auth'],
    'uses' => 'App\Controllers\AppController@search'
]);

Route::match(['GET', 'POST'], '/tagger/{id}', [
    'as' => 'app.tagger',
    'before' => ['auth'],
    'uses' => 'App\Controllers\AppController@tagger'
]);

Route::match(['GET', 'POST'], '/tagger/{id}/{action}', [
    'as' => 'app.tagger.code',
    'before' => ['auth'],
    'uses' => 'App\Controllers\AppController@tagger_code'
]);

Route::match(['GET', 'POST'], '/images', [
    'as' => 'app.images',
    'before' => ['auth'],
    'uses' => 'App\Controllers\AppController@images'
]);

Route::match(['GET', 'POST'], '/upload', [
    'as' => 'app.upload',
    'before' => ['auth'],
    'uses' => 'App\Controllers\AppController@upload'
]);

Route::match(['GET', 'POST'], '/activate/{code}', [
    'as' => 'app.activate',
    'before' => ['auth'],
    'uses' => 'App\Controllers\AppController@activate'
]);

Route::match(['GET'], '/ajax/premium-account', [
    'before' => ['auth'],
    'uses' => 'App\Controllers\AjaxController@PremiumAccount'
]);

Route::match(['GET', 'POST'], '/ajax/upload-premium-icon', [
    'before' => ['auth'],
    'uses' => 'App\Controllers\AjaxController@UploadPremiumIcon'
]);

Route::match(['POST'], '/ajax/tag-image', [
    'before' => ['auth'],
    'uses' => 'App\Controllers\AjaxController@addImageTag'
]);

Route::match(['DELETE'], '/ajax/tag-image', [
    'before' => ['auth'],
    'uses' => 'App\Controllers\AjaxController@removeImageTag'
]);

Route::match(['GET'], '/ajax/search-products', [
    'before' => ['auth'],
    'uses' => 'App\Controllers\AjaxController@searchProducts'
]);

Route::match(['DELETE'], '/ajax/delete-image', [
    'before' => ['auth'],
    'uses' => 'App\Controllers\AjaxController@deleteImage'
]);

Route::match(['DELETE'], '/ajax/delete-coupon', [
    'before' => ['auth'],
    'uses' => 'App\Controllers\AjaxController@DeleteCoupon'
]);

Route::match(['POST'], '/analytics', [
    'uses' => 'App\Controllers\AppController@analytics'
]);
Route::match(['GET'], '/delete-list/{id}', [
    'before' => ['auth'],
    'uses' => 'App\Controllers\AppController@deleteList'
]);