<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

/*
 | ------------------------------------------------------------------------
 | Normal User Section Routes
 | ------------------------------------------------------------------------
 |
 | All the routes are related to normal user
 |
 */
// Route::get('/publish', function() {
// 	Redis::publish('Channel', json_encode(['foo' => 'bar']));
// });

Route::group(['middleware' => ['cachebefore', 'cacheafter']], function () { });

// Route::get('/full-backup', 'CronJobController@fullBackup');

Route::get('/', 'PagesController@index');
Route::get('/kvitto', function () {
	$order = App\Order::find(5);
	return view('email.recite', compact('order'));
});

Route::get('/falgar', 'PagesController@rims');
Route::get('/sommardack', 'PagesController@summerTires');
Route::get('/friktionsdack', 'PagesController@friktionTires');
Route::get('/dubbdack', 'PagesController@studdedTires');

Route::get('/falgar/{productId}', 'PagesController@showSimpleProduct');
Route::get('/sommardack/{productId}', 'PagesController@showSimpleProduct');
Route::get('/friktionsdack/{productId}', 'PagesController@showSimpleProduct');
Route::get('/dubbdack/{productId}', 'PagesController@showSimpleProduct');

Route::get('/falgar/{brand}/{model}/{dimension}/{id}', 'PagesController@showProduct');
Route::get('/falgar/{brand}/{model}/{dimension}/{id}/pcd', 'PagesController@showProductWithPCD');
Route::get('/falgar/{brand}/{dimension}/{id}', 'PagesController@showProductWithPCD2');
Route::get('/sommardack/{brand}/{model}/{dimension}/{id}', 'PagesController@showProduct');
Route::get('/friktionsdack/{brand}/{model}/{dimension}/{id}', 'PagesController@showProduct');
Route::get('/dubbdack/{brand}/{model}/{dimension}/{id}', 'PagesController@showProduct');

Route::get('/dack/{brand}', 'PagesController@showTiresByBrand');
Route::get('/falg/{brand}', 'PagesController@showRimsByBrand');
Route::get('/sitemap.xml', 'PagesController@sitemapGenerator');
Route::get('/product-feed-tires', 'PagesController@productFeedTiresGenerator');
Route::get('/product-feed-rims', 'PagesController@productFeedRimsGenerator');


Route::get('/boka_tid', 'PagesController@bookAppointment');
Route::get('/boka_tid_ja', 'PagesController@bookAppointmentYes');
Route::get('/falg_fix', 'PagesController@rimFix');

Route::get('/tillbehor', 'PagesController@accessories');
// Route::get('/vara_tjanster', 'PagesController@ourServices');
Route::get('/tpms', 'PagesController@showTPMS');
Route::get('/tpms/1', 'PagesController@showTPMS');

Route::get('/muttrar', 'PagesController@showNuts');
Route::get('/muttrar/{id}', 'PagesController@filterNutDimension');

Route::get('/bultar', 'PagesController@showBolts');
Route::get('/bultar/{id}', 'PagesController@filterBoltDimension');

Route::get('/ringar', 'PagesController@showRings');
Route::get('/ringar/{id}', 'PagesController@showRingDetails');
// Route::get('/ringar/{outerID}/{innerID}', 'PagesController@filterRingOuterDimension');
Route::get('/ringar/{outerID}/{innerID}', 'PagesController@filterRingDimension');
// Route::get('/ringar/filterOuterDimension', 'PagesController@filterOuterDimension');

Route::get('/monteringskit', 'PagesController@showMonteringskit');
// Route::get('/monteringskit/{id}', 'PagesController@filterBoltDimension');

Route::get('/lasbultar', 'PagesController@showLockBolts');
Route::get('/lasbultar/{id}', 'PagesController@filterBoltDimension');

Route::get('/spacers', 'PagesController@showSpacers');
Route::get('/spacers/{id}', 'PagesController@filterBoltDimension');
// Route::get('/spacers/{id}', 'PagesController@filterSpacersDimension');

Route::get('/tjanster', 'PagesController@showServices');
Route::get('/tjanster/{id}', 'PagesController@filterBoltDimension');

Route::get('/ovrigt', 'PagesController@showOther');
Route::get('/ovrigt/{id}', 'PagesController@filterBoltDimension');

Route::get('/kontakt', 'PagesController@contact');

Route::get('/productNameSearchSuggestion', 'SearchController@productNameSearchSuggestion');
Route::get('/sok_sortimentet', 'SearchController@searchAllProducts');

// Route::get('/faq', 'PagesController@faq');
// Route::get('/kopvillkor', 'PagesController@paymentTerms');
// Route::get('/samarbetspartners', 'PagesController@partners');
// Route::get('/leverans-returer', 'PagesController@deliveryTerms');
// Route::get('/anvandarvillkor', 'PagesController@terms');
// Route::get('/om-oss', 'PagesController@about');
// Route::get('/inter-sprint-products', 'InterSprint@inter_sprint_products');
// Route::get('/inter-tyre-products', 'InterSprint@inter_tyre_products');
Route::get('/admin/storeInterSprintTires', 'InterSprint@storeInterSprintTires');
Route::get('/admin/storeInterTyreRims', 'InterSprint@storeInterTyreRims');
Route::get('/admin/updateInterSprintTires', 'InterSprint@updateInterSprintTires');
Route::get('/admin/updateInterTyreRims', 'InterSprint@updateInterTyreRims');

//Auth Passwords
Route::post('losenord/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
Route::get('losenord/aterstall', 'Auth\ForgotPasswordController@showLinkRequestForm');
Route::post('losenord/aterstall', 'Auth\ResetPasswordController@reset');
Route::get('losenord/aterstall/{token}', 'Auth\ResetPasswordController@showResetForm');


Route::group(['middleware' => ['auth', 'admin']], function () {
	// Route::get('/testing',function(){
	// 	// return view('testing');
	// return Carbon\Carbon::today()->format('Y-m-d');
	// });
});


Route::get('pushOrder', 'CheckoutController@pushOrder');


Route::get('testing', function () {
	Cart::destroy();
	Cache::flush();
	Session::flush();
	Cookie::queue(Cookie::forget('carSearch'));
	dd();


	// $x = 6; 
	// $y = &$x; 
	// unset($x);
	// echo $y;


	// if(true and false) {
	// 	echo 'true';
	// } else {
	// 	echo 'false';
	// }
	// // 
	// $x = true and false;
	// 

	// $array = [1, 2, 3, 5, 7];
	// $diff = 1; 
	// foreach($array as $key => $value) { 
	// 	if( ($key + $diff) !== $value) { 
	// 		$missingNumbers[] = $key + $diff; 
	// 		$diff++;
	// 	} 
	// }; 
	// return $missingNumbers;



	//echo str_replace(["/", "\\"], ['-', '-'], 'brons\silver');
	// header('Content-Type: text/plain');
	// $curl = curl_init();
	// $curl = curl_init('https://payment.architrade.com/paymentweb/start.action');
	// curl_setopt($curl, CURLOPT_HEADER, 1);  
	// // curl_setopt($curl, CURLOPT_POST, 1);  
	// curl_setopt($curl, CURLOPT_VERBOSE, 1);  
	// // curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);  
	// curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);  
	// curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);  
	// curl_setopt($curl, CURLINFO_HEADER_OUT, 1);
	// curl_setopt($curl, CURLOPT_POSTFIELDS, [
	// 	'accepturl' => url('/storeCardPayment'),
	//        'amount' => 1000,
	//        'billingAddress' => Session::get('orderInfo.billingAddress'),
	//        'billingFirstName' => Session::get('orderInfo.billingFirstName'),
	//        'billingLastName' => Session::get('orderInfo.billingLastName'),
	//        'billingPostalCode' => Session::get('orderInfo.billingPostalCode'),
	//        'billingPostalPlace' => Session::get('orderInfo.billingCity'),
	//        'cancelurl' => url('/kortbetalning'),
	//        'currency' => 'SEK',
	//        'email' => Session::get('orderInfo.email'),
	//        'lang' => 'en',
	//        'merchant' => '90222319',
	//        'orderid' => '15',
	//        'paytype' => 'VISA,MC',
	//        'test' => 1,
	// ]);
	// curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
	// curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
	// // curl_setopt($curl, CURLOPT_POSTREDIR, 3);

	// curl_exec($curl);
	// curl_close($curl); 

	// header('HTTP/1.1 307 Temporary Redirect'); 
	// header('Location: https://payment.architrade.com/paymentweb/start.action');
	// exit;
	// return redirect('https://payment.architrade.com/paymentweb/start.action');
	// return "hej";
	// $url = url('images/product/inter-tyre_rims/1039.png');
	// $url = "http://www.etyre.net/preview/w3/1039-3.png";
	// return exif_imagetype($url);
	// return getimagesize($url);
	// return url_exists($url) ? "Exists" : 'Not Exists';
	// $product = "86T";
	// $loadIndex = substr($product, 0, -1);
	//    $speedIndex = substr($product, -1);
	//    dd($loadIndex , $speedIndex);
	// return Session::getId();
	// $directory = public_path('images/product/media/rims');
	//    $images = File::allFiles($directory);

	//    dd($directory, $images);

	// $value = "Albert von Fontän";
	// $Firstname = strtok($value, " "); 
	// $Lastname = trim($value, $Firstname);

	// echo $Firstname ."<br>". $Lastname;
	// $rate = \Swap::latest('EUR/SEK');
	// return $rate->getValue();
	// 
	// $str = '7-14 dagars leveranstid';
	// $str2 = '5-7 dagars leveranstid';


	// preg_match_all('!\d+!', $str, $matches);
	// $maxNumber = max(max($matches));
	// print_r($maxNumber);

	// return;
});

// Route::get('/testing', function () {
//     // send an email to "batman@batcave.io"
//     $products = \DB::table('products')->select('products.id', 'products.product_category_id', 'products.product_name')
//     	->leftJoin('product_images', function($join) {
//     		$join->on('product_images.product_id', '=', 'products.id');
//     	})
//     	->where('product_images.id', null)
//     	->where('products.product_category_id', 2)
//     	->get();

//     foreach ($products as $product) {
// 	    $createProductImage = new App\ProductImage;
// 	    $createProductImage->product_id = $product->id;
// 	    $createProductImage->name = "noRimImg.jpg";
// 	    $createProductImage->path = "images/product/noRimImg.jpg";
// 	    $createProductImage->thumbnail_path = "images/product/tn-noRimImg.jpg";
// 	    $createProductImage->priority = 1;
// 	    $createProductImage->save();
//     }

//     $products = \DB::table('products')->select('products.id', 'products.product_category_id', 'products.product_name')
//     	->leftJoin('product_images', function($join) {
//     		$join->on('product_images.product_id', '=', 'products.id');
//     	})
//     	->where('product_images.id', null)
//     	->where('products.product_category_id', 1)
//     	->get();

//     foreach ($products as $product) {
// 	    $createProductImage = new App\ProductImage;
// 	    $createProductImage->product_id = $product->id;
// 	    $createProductImage->name = "noTireImg.jpg";
// 	    $createProductImage->path = "images/product/noTireImg.jpg";
// 	    $createProductImage->thumbnail_path = "images/product/tn-noTireImg.jpg";
// 	    $createProductImage->priority = 1;
// 	    $createProductImage->save();
//     }

//     // dd($products);


//     // return $price;
// });

// Route::get('/testing', function () {
//     // send an email to "batman@batcave.io"
// 	$price = str_replace("£", '', "£208.33");
//     return $price;
// });

//use App\Mail\orderConfirmation;

// Route::get('/testing', function () {
//     // send an email to "batman@batcave.io"
//     Mail::to('sibar.alani@gmail.com')->send(new orderConfirmation);

//     return view('/testing');
// });


// Route::get('/testing',function(){
// 		$url = 'http://b2b.wheeltrade.pl/zasoby/import/J/JR2020105X4074MBZ_11539_1.jpg';
//     $file = file_get_contents($url);
//     $fileName = basename($url);
//     $fileName = time().'.'.$fileName;
//     $save = file_put_contents('images/product/' . $fileName, $file);
//     if($save) {

//     	DB::beginTransaction();
//     	try{
//     		// save in DB product_images
//     		DB::commit();
//     	} catch (Exception $e) {
// 	    	File::delete('images/product/' . $imageName);
// 	    	DB::rollback();
//     	}
//     }

// 	return 'success';
// });



/*
 | ------------------------------------------------------------------------
 | Cart Section Routes
 | ------------------------------------------------------------------------
 |
 | All the routes are related to normal user
 |
 */
// Route::get('/varukorg', 'CartController@cart');
Route::resource('varukorg', 'CartController', ['only' => ['index', 'store', 'update', 'destroy']]);
Route::delete('emptyCart', 'CartController@emptyCart');
Route::get('addMount', 'CartController@addMount');
Route::get('addKit', 'CartController@addKit');
Route::get('addTPMS', 'CartController@addTPMS');
Route::get('addCarChange', 'CartController@addCarChange');
Route::get('addLockKit', 'CartController@addLockKit');
Route::patch('updateET', 'CartController@updateET');
Route::get('printCartOrder', 'CartController@printCartOrder');
Route::post('sendToFriend', 'CartController@sendToFriend');
Route::post('campaignCode', 'CartController@campaignCode');


Route::get('processNetsPayement', 'CheckoutController@processNetsPayement');
Route::get('callingSale', 'CheckoutController@callingSale');
Route::get('callingQuery', 'CheckoutController@callingQuery');


/*
 | ------------------------------------------------------------------------
 | Checkout Section Routes
 | ------------------------------------------------------------------------
 |
 | All the routes are related to normal user
 |
 */
// Route::get('/kassa', 'CheckoutController@index');
// Route::post('/kassa', 'CheckoutController@index');
Route::post('saveOrderInfo', 'CheckoutController@saveOrderInfo');
Route::post('saveDeliveryInfo', 'CheckoutController@saveDeliveryInfo');
Route::post('savePaymentInfo', 'CheckoutController@savePaymentInfo');
Route::get('/orderbekraftelse/{token}', 'CheckoutController@confirmOrder');

Route::get('getAddress', 'CheckoutController@getAddress');
// Route::get('getAlternativeAddress', 'CheckoutController@getAlternativeAddress');
// Route::post('saveOrderInfo', 'CheckoutController@saveOrderInfo');




Route::get('/createKlarnaOrder', 'CheckoutController@createKlarnaOrder');
Route::post('/createOrder', 'CheckoutController@createOrder');
// Route::get('/orderbekraftelse', 'CheckoutController@confirmOrder');
Route::get('/cardPaymentCreateOrder', 'CheckoutController@cardPaymentCreateOrder');
Route::post('/kortbetalning', 'CheckoutController@cardPayment');


Route::get('/kortbetalning', 'CheckoutController@cardPayment');
Route::get('/avarda_payment', 'CheckoutController@avardaPaymentInitialize');
Route::get('/avarda_callback', 'CheckoutController@avardaCallback');
Route::get('/avarda_success', 'CheckoutController@getAvardaPaymentStatus');
Route::get('/careditcard_payment', 'CheckoutController@getNetsHTML');
Route::get('/faktura', 'CheckoutController@invoicePayment');
Route::get('/delbetala', 'CheckoutController@partPayment');
Route::get('/betala_butik', 'CheckoutController@inStorePayment');




//klarna routes

Route::get('/klarna_payment', 'CheckoutController@klarnaPayment');
Route::get('/klarna_confirmation', 'CheckoutController@klarnaConfirmation');

Route::get('/klarna_callback', 'CheckoutController@klarnaCallback');
Route::get('/klarna_success', 'CheckoutController@klarnaSuccess');








// Route::post('/showDibsCardPayment', 'CheckoutController@showDibsCardPayment');
Route::get('/storeCardPayment', 'CheckoutController@storeCardPayment');
Route::post('/storeCardPayment', 'CheckoutController@storeCardPayment');
Route::post('/storeInvoicePayment', 'CheckoutController@storeInvoicePayment');
Route::post('/storePartPayment', 'CheckoutController@storePartPayment');
Route::post('/storeInStorePayment', 'CheckoutController@storeInStorePayment');

Route::get('/checkSSN', 'CheckoutController@checkSSN');


Route::get('/shippingCost', 'CheckoutController@getShippingCost');
// Route::get('/VATCosts', 'CheckoutController@VATCosts');

Route::post('/admin/orderbekraftelse', 'CheckoutController@klarnaPush');

/*
 | ------------------------------------------------------------------------
 | Search Section Routes
 | ------------------------------------------------------------------------
 |
 | All the routes are related to normal user
 |
 */
Route::post('/sok/reg/kompletta-hjul/falgar', 'SearchController@showCompleteRims');
Route::get('/sok/reg/kompletta-hjul/falgar', 'SearchController@showCompleteRims');
Route::post('/sok/reg/kompletta-hjul/dack', 'SearchController@searchCompleteTires');
Route::get('/sok/reg/kompletta-hjul/dack', 'SearchController@searchCompleteTires');

Route::post('/sok/reg/falgar', 'SearchController@showRimSearch');
Route::get('/sok/reg/falgar', 'SearchController@showRimSearch');
Route::get('/sok/reg/seachRimsByReg', 'SearchController@showRimSearch');

// Route::post('/sok/reg/sommardack', 'SearchController@searchTiresByReg');
// Route::get('/sok/reg/sommardack', 'SearchController@searchTiresByReg');
Route::post('/sok/reg/sommardack', 'SearchController@showSummerTireSearch');
Route::get('/sok/reg/sommardack', 'SearchController@showSummerTireSearch');
Route::get('/sok/reg/searchSummerTiresByWidth', 'SearchController@showSummerTireSearch');
Route::get('/sok/reg/searchSummerTiresByDimension', 'SearchController@showSummerTireSearch');

// Route::post('/sok/reg/friktionsdack', 'SearchController@searchTiresByReg');
// Route::get('/sok/reg/friktionsdack', 'SearchController@searchTiresByReg');
Route::post('/sok/reg/friktionsdack', 'SearchController@showFriktionTireSearch');
Route::get('/sok/reg/friktionsdack', 'SearchController@showFriktionTireSearch');
Route::get('/sok/reg/searchFriktionTiresByWidth', 'SearchController@showFriktionTireSearch');
Route::get('/sok/reg/searchFriktionTiresByDimension', 'SearchController@showFriktionTireSearch');

// Route::post('/sok/reg/dubbdack', 'SearchController@searchTiresByReg');
// Route::get('/sok/reg/dubbdack', 'SearchController@searchTiresByReg');
Route::post('/sok/reg/dubbdack', 'SearchController@showStuddedTireSearch');
Route::get('/sok/reg/dubbdack', 'SearchController@showStuddedTireSearch');
Route::get('/sok/reg/searchStuddedTiresByWidth', 'SearchController@showStuddedTireSearch');
Route::get('/sok/reg/searchStuddedTiresByDimension', 'SearchController@showStuddedTireSearch');

Route::post('/sok/storlek/falgar', 'SearchController@searchRimsBySize');
Route::get('/sok/storlek/falgar', 'SearchController@searchRimsBySize');

Route::post('/sok/storlek/sommardack', 'SearchController@searchSummerTiresBySize');
Route::get('/sok/storlek/sommardack', 'SearchController@searchSummerTiresBySize');

Route::post('/sok/storlek/friktionsdack', 'SearchController@searchFriktionTiresBySize');
Route::get('/sok/storlek/friktionsdack', 'SearchController@searchFriktionTiresBySize');

Route::post('/sok/storlek/dubbdack', 'SearchController@searchStuddedTiresBySize');
Route::get('/sok/storlek/dubbdack', 'SearchController@searchStuddedTiresBySize');

// Route::get('/sok/storlek/paginationTest/{productTypeID}', 'SearchController@paginationTest');






Route::get('/deleteSearchCookie', 'SearchController@deleteSearchCookie');

Route::get('/searchRegNr', 'SearchController@searchRegNr');

// Route::get('/searchSize', 'SearchController@searchSize');

Route::get('/getCarManuf', 'SearchController@getCarManuf');

Route::get('/getCarModels', 'SearchController@getCarModels');

Route::get('/filterByWidth/{productTypeID}', 'SearchController@filterByWidth');
Route::get('/filterByProfile/{productTypeID}', 'SearchController@filterByProfile');
Route::get('/filterByInch/{productTypeID}', 'SearchController@filterByInch');
Route::get('/filterByBrand/{productTypeID}', 'SearchController@filterByBrand');
Route::get('/filterByModel/{productTypeID}', 'SearchController@filterByModel');

Route::get('/filterRimsByInch', 'SearchController@filterRimsByInch');
Route::get('/filterRimsByWidth', 'SearchController@filterRimsByWidth');
Route::get('/filterRimsByET', 'SearchController@filterRimsByET');
Route::get('/filterRimsByBrand', 'SearchController@filterRimsByBrand');



/*
 | ------------------------------------------------------------------------
 | Authentication & Registration Routes
 | ------------------------------------------------------------------------
 |
 |
 */
// Route::get('login', 'Auth\AuthController@getLogin');
Route::group(['middleware' => 'guest'], function () {
	Route::post('login', 'Auth\LoginController@postLogin');
	Route::get('login', 'Auth\LoginController@getLogin');

	// Route::get('register', 'Auth\AuthController@getRegister');
	Route::post('registrera', 'Auth\RegisterController@postRegister');
	Route::get('registrera', 'Auth\RegisterController@getRegistered');
});

Route::group(['middleware' => 'auth'], function () {
	Route::get('logout', 'Auth\LoginController@logout');
	Route::get('konto', 'UserController@myAccount');
	Route::get('orderlista', 'UserController@orderList');
	Route::get('adresser', 'UserController@myAddress');
	Route::patch('updateAddress', 'UserController@updateAddress');
	Route::get('konto_installningar', 'UserController@accountSettings');
	Route::patch('updateAccountSettings', 'UserController@updateAccountSettings');

	Route::get('order_status/{token}', 'UserController@orderStatus');
});

Route::group(['prefix' => 'admin'], function () {
	Route::group(['middleware' => ['auth', 'admin']], function () {
		// Route::get('panel', 'AdminOrdersController@dashboard');
		Route::get('ordrar', 'AdminOrdersController@orders');
		Route::get('order/{id}', 'AdminOrdersController@showOrder');
		Route::get('printOrder/{id}', 'AdminOrdersController@printOrder');
		Route::get('updateCarData/{id}', 'AdminOrdersController@updateCarData');
		Route::post('order/sendItemNotification', 'AdminOrdersController@sendItemNotification');
		Route::get('showOrderCommentModal', 'AdminOrdersController@showOrderCommentModal');
		Route::post('commentOrder', 'AdminOrdersController@commentOrder');

		// Route::get('showOrderCommentModal', 'AdminOrderCommentsController@showOrderCommentModal');
		// Route::post('commentOrder', 'AdminOrderCommentsController@commentOrder');

		Route::patch('updateQuantity', 'AdminOrdersController@updateQuantity');
		Route::patch('updateStatus', 'AdminOrdersController@updateStatus');

		Route::post('activateKlarna', 'AdminOrdersController@activateKlarna');
		Route::post('cancelKlarna', 'AdminOrdersController@cancelKlarna');

		Route::get('purchaseAvarda/{id}/{purchaseid}', 'CheckoutController@purchaseAvardaPayment');

		Route::get('filterOrders', 'AdminOrdersController@filterOrders');

		Route::get('dack', 'AdminProductsController@tires');
		Route::post('storeTire', 'AdminProductsController@storeTire');
		Route::get('showUpdateTireModal', 'AdminProductsController@showUpdateTireModal');
		Route::post('updateTire', 'AdminProductsController@updateTire');
		Route::get('filterTires', 'AdminProductsController@filterTires');
		Route::get('showUpdateMediaTireModal', 'AdminProductsController@showUpdateMediaTireModal');
		Route::post('updateMediaTire', 'AdminProductsController@updateMediaTire');
		// Route::patch('updateTireAction', 'AdminProductsController@updateTireAction');

		Route::get('falgar', 'AdminProductsController@rims');
		Route::post('storeRim', 'AdminProductsController@storeRim');
		Route::get('showUpdateRimModal', 'AdminProductsController@showUpdateRimModal');
		Route::post('updateRim', 'AdminProductsController@updateRim');
		Route::get('filterRims', 'AdminProductsController@filterRims');
		Route::get('showUpdateMediaRimModal', 'AdminProductsController@showUpdateMediaRimModal');
		Route::post('updateMediaRim', 'AdminProductsController@updateMediaRim');

		Route::delete('images/{id}', 'AdminProductsController@destroyImage');


		Route::get('tillbehor', 'AdminProductsController@accessories');
		Route::post('storeAccessory', 'AdminProductsController@storeAccessory');
		Route::get('showUpdateAccessoryModal', 'AdminProductsController@showUpdateAccessoryModal');
		Route::post('updateAccessory', 'AdminProductsController@updateAccessory');
		Route::get('filterAccessories', 'AdminProductsController@filterAccessories');



		Route::get('priser/sommardack', 'AdminProfitController@summerTires');
		Route::patch('priser/sommardack', 'AdminProfitController@updateSummerTires');

		Route::get('priser/vinterdack', 'AdminProfitController@winterTires');
		Route::patch('priser/vinterdack', 'AdminProfitController@updateWinterTires');

		Route::get('priser/falgar', 'AdminProfitController@rims');
		Route::patch('priser/falgar', 'AdminProfitController@updateRims');

		// Route::get('priser/tillbehor', 'AdminProfitController@accessories');
		// Route::patch('priser/tillbehor', 'AdminProfitController@updateAccessories');


		Route::get('anvandare', 'AdminUsersController@users');
		Route::post('storeUser', 'AdminUsersController@storeUser');
		Route::get('showUpdateUserModal', 'AdminUsersController@showUpdateUserModal');
		Route::post('updateUser', 'AdminUsersController@updateUser');
		Route::patch('updateUserType', 'AdminUsersController@updateUserType');
		Route::get('filterUsers', 'AdminUsersController@filterUsers');

		Route::get('loginUser/{id}', 'AdminUsersController@loginUser');

		Route::get('sokningar', 'AdminCustomerSearchesController@index');
		Route::get('filterCustomerSearches', 'AdminCustomerSearchesController@filterCustomerSearches');
		Route::get('exportCustomerSearches', 'AdminCustomerSearchesController@filterCustomerSearches');


		Route::get('bilder/dack', 'AdminImagesController@tires');
		Route::post('bilder/uploadMediaTires', 'AdminImagesController@uploadTires');
		Route::delete('bilder/dack/{tire}', 'AdminImagesController@deleteTire');

		Route::get('bilder/falgar', 'AdminImagesController@rims');
		Route::post('bilder/uploadMediaRims', 'AdminImagesController@uploadRims');
		Route::delete('bilder/falgar/{rim}', 'AdminImagesController@deleteRim');

		Route::get('cms/menyer', 'AdminCMSController@menuList');
		Route::post('cms/sortMenu', 'AdminCMSController@sortMenu');

		// Route::get('cms', 'AdminCMSController@pageIndex');
		Route::get('cms/sidor', 'AdminCMSController@pageList');
		Route::post('cms/storePage', 'AdminCMSController@storePage');
		Route::get('cms/showUpdatePageModal', 'AdminCMSController@showUpdatePageModal');
		Route::post('cms/updatePage', 'AdminCMSController@updatePage');

		Route::get('cms/inlagg', 'AdminCMSController@postList');
		Route::post('cms/storePost', 'AdminCMSController@storePost');
		Route::get('cms/showUpdatePostModal', 'AdminCMSController@showUpdatePostModal');
		Route::post('cms/updatePost', 'AdminCMSController@updatePost');

		Route::get('cms/slider', 'AdminCMSController@sliderList');
		Route::post('cms/storeSlider', 'AdminCMSController@storeSlider');
		Route::get('cms/showUpdateSliderModal', 'AdminCMSController@showUpdateSliderModal');
		Route::post('cms/updateSlider', 'AdminCMSController@updateSlider');
		Route::delete('cms/sliderImage/{id}', 'AdminCMSController@destroySliderImage');
		Route::post('cms/sortSlider', 'AdminCMSController@sortSlider');

		Route::get('/laravel-filemanager', '\Unisharp\Laravelfilemanager\controllers\LfmController@show');
		Route::post('/laravel-filemanager/upload', '\Unisharp\Laravelfilemanager\controllers\LfmController@upload');

		Route::get('cms/installningar/allmant/{settings_type}', 'AdminCMSController@settingList');
		Route::get('cms/installningar/foretags_info/{settings_type}', 'AdminCMSController@settingList');
		Route::get('cms/installningar/sociala_medier/{settings_type}', 'AdminCMSController@settingList');
		Route::post('cms/updateSettings', 'AdminCMSController@updateSettings');
	});

	Route::group(['middleware' => 'guest'], function () {
		Route::get('77889', 'Auth\LoginController@getAdminLogin');
		Route::post('77889', 'Auth\LoginController@postAdminLogin');
	});
});


/*
 | ------------------------------------------------------------------------
 | CronJobs Section Routes
 | ------------------------------------------------------------------------
 | All the routes are related to admin user
 |
 |
 */
Route::group(['prefix' => 'admin'], function () {
	// Route::group(['middleware' => ['auth', 'admin']], function () {
	// Route::get('CronJobs', 'CronJobController@index');

	Route::get('storeJRAccessories', 'CronJobController@storeJRAccessories');

	Route::get('storeAmringProducts', 'CronJobController@storeAmringProducts');
	Route::get('updateAmringProducts', 'CronJobController@updateAmringProducts');

	Route::get('storeVandenbanTires', 'CronJobController@storeVandenbanTires');
	Route::get('updateVandenbanTires', 'CronJobController@updateVandenbanTires');

	Route::get('storeDelticomTires', 'CronJobController@storeDelticomTires');
	Route::get('updateDelticomTires', 'CronJobController@updateDelticomTires');

	Route::get('storeABSRims', 'CronJobController@storeABSRims');
	Route::get('updateABSRims', 'CronJobController@updateABSRims');
	Route::get('storeABSTires', 'CronJobController@storeABSTires');
	Route::get('updateABSTires', 'CronJobController@updateABSTires');

	Route::get('storeEonTyreTires', 'EontyreApi@list_tyre_products');
	Route::get('storeEonTyreRims', 'EontyreApi@list_rim_products');
	Route::get('updateEonTyrePrice', 'EontyreApi@get_price_changes');
	Route::get('updateEonTyreStock', 'EontyreApi@get_stock_changes');
	Route::post('creatEonTyreOrder', 'EontyreApi@create_order');

	// Route::get('storeImadProducts', 'CronJobController@storeImadProducts');
	// Route::get('storeWZProducts', 'CronJobController@storeWZProducts');
	// });
});

// CMS pages should alwase be the last route
Route::get('/{slug}', 'PagesController@cmsPage');
