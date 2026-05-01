<?php
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\InsuretechSyncController;
use App\Http\Controllers\UsersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/* ----------------------------------- WEB API PANEL --------------------------------------------- */
Route::get('/clear', function() {
    // $exitCode = Artisan::call('route:list');
    // echo 'Routes cache cleared'; echo "<br>";
    // exit;
    
    //$exitCode = Artisan::call('route:cache');
    //echo 'Routes cache cleared'; echo "<br>";

    $exitCode = Artisan::call('route:clear');
    echo 'Routes cache cleared'; echo "<br>";
     
    $exitCode = Artisan::call('config:cache');
    echo 'Config cache cleared'; echo "<br>";
    
    $exitCode = Artisan::call('cache:clear');
    echo 'Application cache cleared';  echo "<br>";
    
    $exitCode = Artisan::call('view:clear');
    echo 'View cache cleared';  echo "<br>";

    // $Command = Artisan::call('make:middleware Cors');
    Session::flash('message', 'Cache Cleared!'); 
    Session::flash('alert-class', 'alert-danger'); 
    return redirect('/admin/dashboard');
});
Route::get('/payment-success', [ApiController::class, 'paymentSuccess']);
Route::get('/download/{type}/{id}', [ApiController::class, 'download_file']);
Route::get('/test-mail', [ApiController::class, 'sendTestMail']);
//USER AUTHENTICATION
Route::post('/signin', [ApiController::class, 'users_customers_login']);
Route::post('/signup', [ApiController::class, 'users_customers_signup']);
Route::post('/update_profile', [ApiController::class, 'update_profile']);
Route::post('/email_exist', [ApiController::class, 'email_exist']);
Route::post('/forgot_password', [ApiController::class, 'forgot_password']);
Route::post('/modify_password', [ApiController::class, 'modify_password']);

Route::post('/change_password', [ApiController::class, 'change_password']);
Route::post('/delete_account', [ApiController::class, 'delete_account']);

Route::post('/update_activity_interval', [ApiController::class, 'update_activity_interval']);
Route::post('/users_customers_last_activity', [ApiController::class, 'users_customers_last_activity']);
Route::post('/users_customers_activity_interval', [ApiController::class, 'users_customers_activity_interval']);
Route::post('/users_customers_verify_otp', [ApiController::class, 'users_customers_verify_otp']);
//USER AUTHENTICATION

//LIVE CHAT MESSAGES
Route::post('/getAllChat', [ApiController::class, 'getAllChat']);
Route::post('/user_chat', [ApiController::class, 'user_chat']);
Route::post('/unreaded_messages', [ApiController::class, 'unreaded_messages']);
//LIVE CHAT MESSAGES

//GET NOTIFICATIONS
Route::post('/notifications', [ApiController::class, 'notifications']);
Route::post('/notifications_unread', [ApiController::class, 'notifications_unread']);
Route::post('/notification_permission', [ApiController::class, 'notification_permission']);
//GET NOTIFICATIONS

//GET DATA
Route::post('/users_customers_profile', [ApiController::class, 'users_customers_profile']);
Route::get('/system_settings', [ApiController::class, 'system_settings'])->name('system_settings');
Route::post('/all_users', [ApiController::class, 'all_users']);  
Route::post('/all_users_suggested', [ApiController::class, 'all_users_suggested']);  
Route::get('/all_countries', [ApiController::class, 'all_countries']);

Route::get('/all_currencies', [ApiController::class, 'all_currencies']);
Route::post('/get_currencies_by_id', [ApiController::class, 'get_currencies_by_id']);
//GET DATA

//WALLET 
Route::post('/create_wallet', [ApiController::class, 'create_wallet']);
Route::post('/get_wallet', [ApiController::class, 'get_wallet']);
Route::post('/user_wallet_detail', [ApiController::class, 'user_wallet_detail']);
//WALLET
 
// Currency CONVERTER
Route::post('/currency_converter', [ApiController::class, 'currency_converter']);
// Currency CONVERTER

//TRANSFER
Route::post('/transfer_currency', [ApiController::class, 'transfer_currency']);
//TRANSFER

//SWAP
Route::post('/wallet_swap', [ApiController::class, 'wallet_swap']);
Route::post('/swap_offer', [ApiController::class, 'swap_offer']);
Route::post('/swap_offer_request', [ApiController::class, 'swap_offer_request']);
Route::post('/all_swap_offers', [ApiController::class, 'all_swap_offers']);
Route::post('/user_swap_offers_requests', [ApiController::class, 'user_swap_offers_requests']);
Route::post('/user_swap_offers', [ApiController::class, 'user_swap_offers']);
Route::post('/swap_offer_request_approve', [ApiController::class, 'swap_offer_request_approve']);
Route::post('/swap_offer_request_reject', [ApiController::class, 'swap_offer_request_reject']);
//SWAP

// CURRENCY RATE
Route::post('/buy_currency_rate', [ApiController::class, 'buy_currency_rate']);
Route::post('/sell_currency_rate', [ApiController::class, 'sell_currency_rate']);
// CURRENCY RATE

//TRANSACTIONS
Route::post('/all_transactions', [ApiController::class, 'all_transactions']);
//TRANSACTIONS

// USER FEEDBACK
Route::post('/user_feedback', [ApiController::class, 'user_feedback']);
// USER FEEDBACK

// FAQ
Route::post('/add_faq', [ApiController::class, 'add_faq']);
Route::get('/all_faqs', [ApiController::class, 'all_faqs']);
// FAQ

// CONNECT CATEGORIES
Route::get('/connect_categories', [ApiController::class, 'connect_categories']);
// CONNECT CATEGORIES

// CONNECT ARTICLES
Route::post('/connect_articles', [ApiController::class, 'connect_articles']);
Route::post('/connect_articles_by_category', [ApiController::class, 'connect_articles_by_category']);
Route::post('/add_favorite_connect_articles', [ApiController::class, 'add_favorite_connect_articles']);
Route::post('/remove_favorite_connect_articles', [ApiController::class, 'remove_favorite_connect_articles']);
Route::post('/connect_article_view', [ApiController::class, 'connect_article_view']);
Route::post('/popular_connect_articles', [ApiController::class, 'popular_connect_articles']);
Route::post('/popular_connect_articles_by_category', [ApiController::class, 'popular_connect_articles_by_category']);
Route::post('/favorite_connect_articles', [ApiController::class, 'favorite_connect_articles']);
// CONNECT ARTICLES

// FAVORITE SWAP OFFERS
Route::post('/all_favorite_swaps_offers', [ApiController::class, 'all_favorite_swaps_offers']);
Route::post('/add_favorite_swaps_offers', [ApiController::class, 'add_favorite_swaps_offers']);
Route::post('/remove_favorite_swaps_offers', [ApiController::class, 'remove_favorite_swaps_offers']);
// FAVORITE SWAP OFFERS

// ACCOUNT
Route::post('/add_acount', [ApiController::class, 'add_acount']);
Route::post('/all_acounts', [ApiController::class, 'all_acounts']);
// ACCOUNT

// FUND WALLET
Route::post('/fund_wallet_request', [ApiController::class, 'fund_wallet_request']);
// FUND WALLET

// WITHDRAW WALLETS
Route::post('/withdraw_wallets_request', [ApiController::class, 'withdraw_wallets_request']);
// WITHDRAW WALLETS

// PRODUCTS
Route::post('/purchase_product', [ApiController::class, 'purchase_product']);
Route::post('/claim_purchased_product', [ApiController::class, 'claim_purchased_product']);
Route::post('/stripe/initiate-payment', [ApiController::class, 'initiateStripePayment']);
Route::post('/stripe/handle-success', [ApiController::class, 'handleStripeSuccess']);
Route::post('/stripe/handle-cancel', [ApiController::class, 'handleStripeCancel']);
Route::get('/download-invoice/{purchase_id}', [UsersController::class, 'download_invoice']);
// PRODUCTS

// INSURETECH SYNC BRIDGE
Route::post('/insuretech/sync-all', [InsuretechSyncController::class, 'syncAll']);
Route::post('/insuretech/one-click-sale', [InsuretechSyncController::class, 'oneClickSale']);
/* ----------------------------------- WEB API PANEL --------------------------------------------- */