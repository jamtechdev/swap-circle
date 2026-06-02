<?php
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UsersController;

use Illuminate\Http\Request;
use App\Helpers\Helper;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/* ----------------------------------- WEB PANEL --------------------------------------------- */
// SIGNIN USERS_CUTOMERS
Route::get('/', [UsersController::class, 'users_customers_login']);
Route::post('/', [UsersController::class, 'users_customers_login_process']);
// SIGNIN USERS_CUTOMERS

// SIGNUP USERS_CUTOMERS
Route::get('/users/signup', [UsersController::class, 'users_customers_signup']);
Route::get('/users/signup_individual', [UsersController::class, 'users_customers_signup_individual']);
Route::get('/users/signup_corporate', [UsersController::class, 'users_customers_signup_corporate']);
Route::post('/users/signup_process', [UsersController::class, 'users_customers_signup_process']);
Route::get('/users/signup_verified', [UsersController::class, 'users_customers_signup_verified']);
// SIGNUP USERS_CUTOMERS

// FORGOT PASSWORD USERS_CUSTOMERS
Route::get('/users/forgot_password', [UsersController::class, 'users_customers_forgot_password']);
// FORGOT PASSWORD USERS_CUSTOMERS

// VERIFICATION CODE USERS_CUSTOMERS
Route::get('/users/verification_code/{id}', [UsersController::class, 'users_customers_verification_code']);
// VERIFICATION CODE USERS_CUSTOMERS

// RESET PASSWORD USERS_CUSTOMERS
Route::get('/users/reset_password/{email}/{otp}', [UsersController::class, 'users_customers_reset_password']);
// RESET PASSWORD USERS_CUSTOMERS

// DASHBOARD USERS_CUSTOMERS
Route::get('/users/dashboard', [UsersController::class, 'users_customers_dashboard']);
Route::get('/users/wallets', [UsersController::class, 'users_customers_wallets']);
// DASHBOARD USERS_CUSTOMERS

// DATA ANALYSIS USERS_CUSTOMERS
Route::get('/users/data_analysis', [UsersController::class, 'users_customers_data_analysis']);
// DATA ANALYSIS USERS_CUSTOMERS

// OFFERS USERS_CUSTOMERS
Route::get('/users/offers', [UsersController::class, 'users_customers_offers']);
Route::get('/users/offer_requests/{offer_id}', [UsersController::class, 'users_customers_offer_requests'])->name('offer_requests');
// OFFERS USERS_CUSTOMERS

// PRODUCTS USERS_CUSTOMERS
Route::get('/users/products', [UsersController::class, 'users_customers_products']);
Route::get('/users/product/view/{product_id}', [UsersController::class, 'product_view']);
Route::get('/users/product/{type}/{id}', [UsersController::class, 'products_buy']);
Route::get('/users/stripe/success', [UsersController::class, 'stripe_payment_success']);
Route::get('/users/stripe/cancel', [UsersController::class, 'stripe_payment_cancel']);
Route::get('/users/download-invoice/{purchase_id}', [UsersController::class, 'download_invoice']);
// PRODUCTS USERS_CUSTOMERS

// CLAIMS USERS_CUSTOMERS
Route::get('/users/claims', [UsersController::class, 'products_claims']);
// CLAIMS USERS_CUSTOMERS

// TRACK USERS_CUSTOMERS
Route::get('/users/track', [UsersController::class, 'users_customers_track']);
// TRACK USERS_CUSTOMERS

// CONNECT USERS_CUSTOMERS
Route::get('/users/connect', [UsersController::class, 'users_customers_connect']);
Route::get('/users/connect/blog/{blog_id}', [UsersController::class, 'users_customers_connect_blog']);
// CONNECT USERS_CUSTOMERS

// PROFILE USERS_CUSTOMERS
Route::get('/users/profile', [UsersController::class, 'users_customers_profile']);
Route::get('/users/profile_edit', [UsersController::class, 'users_customers_profile_edit']);
Route::get('/users/billing_payment', [UsersController::class, 'users_customers_billing_payment']);
Route::get('/users/transactions', [UsersController::class, 'users_customers_transactions']);
Route::get('/users/settings', [UsersController::class, 'users_customers_settings']);
Route::get('/users/faqs', [UsersController::class, 'users_customers_faqs']);
// PROFILE USERS_CUSTOMERS

// MESSAGE USERS_CUSTOMERS
Route::get('/users/message/{user_id?}', [UsersController::class, 'users_customers_message']);
// MESSAGE USERS_CUSTOMERS

// LOGOUT USERS_CUSTOMERS
Route::get('/users/logout', [UsersController::class, 'users_customers_logout']);
// LOGOUT USERS_CUSTOMERS
/* ----------------------------------- WEB PANEL --------------------------------------------- */

/* ----------------------------------- ADMIN PANEL --------------------------------------------- */
// Base Authentication Routes
Route::get('/admin', [AdminController::class, 'index']);
Route::get('/admin/clear_cache', [AdminController::class, 'clear_cache']);

Route::post('/admin/login', [AdminController::class, 'login']);
Route::get('/admin/logout', [AdminController::class, 'logout']);
// Base Authentication Routes

// DASHBOARD
Route::get('/admin/dashboard', [AdminController::class, 'Dashboard']);
// DASHBOARD

// USERS CUSTOMERS
Route::get('/admin/users_customers', [AdminController::class, 'users_customers']);
Route::get('/admin/users_customers_view/{id}', [AdminController::class, 'users_customers_view'])->name('users_customers_view');
Route::get('/admin/users_customers_update/{id}/{status}', [AdminController::class, 'users_customers_update'])->name('users_customers_update');
Route::get('/admin/users_customers_delete/{id}', [AdminController::class, 'users_customers_delete'])->name('users_customers_delete');
// USERS CUSTOMERS

//SUPPORT MANAGEMENT
Route::get('/admin/support', [AdminController::class, 'support']);
//SUPPORT MANAGEMENT

// USERS SYSTEM
Route::get('/admin/users_system', [AdminController::class, 'users_system']);
Route::get('/admin/users_system_update/{id}/{status}', [AdminController::class, 'users_system_update'])->name('users_system_update');
Route::get('/admin/users_system_delete/{id}', [AdminController::class, 'users_system_delete'])->name('users_system_delete');

Route::get('/admin/users_system_add', [AdminController::class, 'users_system_add']);
Route::post('/admin/users_system_add_data', [AdminController::class, 'users_system_add_data'])->name('users_system_add_data');

Route::get('/admin/users_system_edit/{id}', [AdminController::class, 'users_system_edit'])->name('users_system_edit');
Route::post('/admin/users_system_edit_data', [AdminController::class, 'users_system_edit_data'])->name('users_system_edit_data');
// USERS SYSTEM

// USERS SYSTEM
Route::get('/admin/users_system_roles', [AdminController::class, 'users_system_roles']);
Route::get('/admin/users_system_roles_delete/{id}', [AdminController::class, 'users_system_roles_delete'])->name('users_system_roles_delete');

Route::get('/admin/users_system_roles_add', [AdminController::class, 'users_system_roles_add']);
Route::post('/admin/users_system_roles_add_data', [AdminController::class, 'users_system_roles_add_data'])->name('users_system_roles_add_data');

Route::get('/admin/users_system_roles_edit/{id}', [AdminController::class, 'users_system_roles_edit'])->name('users_system_roles_edit');
Route::post('/admin/users_system_roles_edit_data', [AdminController::class, 'users_system_roles_edit_data'])->name('users_system_roles_edit_data');
// USERS SYSTEM

//Start GENERAl Settings
Route::get('/admin/account_settings', [AdminController::class, 'account_settings']);
Route::post('/admin/account_settings_update/{id}', [AdminController::class, 'account_settings_update'])->name('account_settings_update');

Route::get('/admin/system_settings', [AdminController::class, 'system_settings']);
Route::post('/admin/system_settings_edit', [AdminController::class, 'system_settings_edit']);

Route::get('/admin/system_about_us', [AdminController::class, 'system_about_us']);
Route::get('/admin/system_terms', [AdminController::class, 'system_terms']);
Route::get('/admin/system_privacy', [AdminController::class, 'system_privacy']);
//End GENERAl Settings


// USERS CUSTOMERS FAQs
Route::get('/admin/users_customers_faqs', [AdminController::class, 'users_customers_faqs'])->name('users_customers_faqs');
Route::get('/admin/users_customers_faqs_fetch', [AdminController::class, 'users_customers_faqs_fetch'])->name('users_customers_faqs_fetch');
Route::get('/admin/users_customers_view_faqs/{id}', [AdminController::class, 'users_customers_view_faqs'])->name('users_customers_view_faqs');
Route::post('/admin/users_customers_update_faq', [AdminController::class, 'users_customers_update_faq'])->name('users_customers_update_faq');
Route::post('/admin/users_customers_delete_faq', [AdminController::class, 'users_customers_delete_faq'])->name('users_customers_delete_faq');
Route::post('/admin/users_customers_add_faq_data', [AdminController::class, 'users_customers_add_faq_data'])->name('users_customers_add_faq_data');
Route::get('/admin/users_customers_edit_faq/{id}', [AdminController::class, 'users_customers_edit_faq'])->name('users_customers_edit_faq');
Route::post('/admin/users_customers_edit_faq_data', [AdminController::class, 'users_customers_edit_faq_data'])->name('users_customers_edit_faq_data');
// USERS CUSTOMERS FAQs

// SWAP OFFERS
Route::get('/admin/swap_offers', [AdminController::class, 'swap_offers'])->name('swap_offers');
// SWAP OFFERS

// PRODUCTS
Route::get('/admin/manage_products', [AdminController::class, 'manage_products']);
Route::post('/admin/products_add', [AdminController::class, 'products_add']);
Route::post('/admin/products_edit', [AdminController::class, 'products_edit']);
Route::post('/admin/products/information-image', [AdminController::class, 'products_information_image_upload']);
Route::get('/admin/products_update/{status}/{id}', [AdminController::class, 'products_update']);
// PRODUCTS

// USERS CUSTOMERS TRANSACTIONS
Route::get('/admin/users_customers_trxns', [AdminController::class, 'users_customers_trxns'])->name('users_customers_trxns');
Route::get('/admin/transactions', [AdminController::class, 'products_transactions']);
// USERS CUSTOMERS TRANSACTIONS

// PRODUCTS CLAIMS
Route::get('/admin/products_claims', [AdminController::class, 'products_claims']);
// PRODUCTS CLAIMS

// TASKS TYPES
Route::get('/admin/tasks_types', [AdminController::class, 'tasks_types']);
Route::post('/admin/tasks_types_add', [AdminController::class, 'tasks_types_add']);
Route::post('/admin/tasks_types_edit', [AdminController::class, 'tasks_types_edit']);
Route::get('/admin/tasks_types_update/{status}/{id}', [AdminController::class, 'tasks_types_update']);
// TASKS TYPES

// OCCUPATIONS
Route::get('/admin/occupations', [AdminController::class, 'occupations']);
Route::post('/admin/occupations_add', [AdminController::class, 'occupations_add']);
Route::post('/admin/occupations_edit', [AdminController::class, 'occupations_edit']);
Route::get('/admin/occupations_update/{status}/{id}', [AdminController::class, 'occupations_update']);
// OCCUPATIONS

// RELATIONSHIPS
Route::get('/admin/relationships', [AdminController::class, 'relationships']);
Route::post('/admin/relationships_add', [AdminController::class, 'relationships_add']);
Route::post('/admin/relationships_edit', [AdminController::class, 'relationships_edit']);
Route::get('/admin/relationships_update/{status}/{id}', [AdminController::class, 'relationships_update']);
// RELATIONSHIPS

// PAYMENT METHODS
Route::get('/admin/payment_methods', [AdminController::class, 'payment_methods'])->name('payment_methods');
Route::get('/admin/payment_methods_view/{id}', [AdminController::class, 'payment_methods_view'])->name('payment_methods_view');
Route::get('/admin/payment_methods_update/{id}/{status}', [AdminController::class, 'payment_methods_update'])->name('payment_methods_update');
Route::get('/admin/payment_methods_delete/{id}', [AdminController::class, 'payment_methods_delete'])->name('payment_methods_delete');

Route::get('/admin/payment_methods_add', [AdminController::class, 'payment_methods_add'])->name('payment_methods_add');
Route::post('/admin/payment_methods_add_data', [AdminController::class, 'payment_methods_add_data'])->name('payment_methods_add_data');

Route::get('/admin/payment_methods_edit/{id}', [AdminController::class, 'payment_methods_edit'])->name('payment_methods_edit');
Route::post('/admin/payment_methods_edit_data/{id}', [AdminController::class, 'payment_methods_edit_data'])->name('payment_methods_edit_data');
// PAYMENT METHODS

// CONNECT CATEGORIES
Route::get('/admin/connect_categories', [AdminController::class, 'connect_categories'])->name('connect_categories');
Route::get('/admin/connect_categories_fetch', [AdminController::class, 'connect_categories_fetch'])->name('connect_categories_fetch');
Route::post('/admin/connect_category_update', [AdminController::class, 'connect_category_update'])->name('connect_category_update');
Route::post('/admin/connect_category_delete', [AdminController::class, 'connect_category_delete'])->name('connect_category_delete');
Route::post('/admin/connect_category_add_data', [AdminController::class, 'connect_category_add_data'])->name('connect_category_add_data');
Route::get('/admin/connect_category_edit/{id}', [AdminController::class, 'connect_category_edit'])->name('connect_category_edit');
Route::post('/admin/connect_category_edit_data', [AdminController::class, 'connect_category_edit_data'])->name('connect_category_edit_data');
// CONNECT CATEGORIES

// CONNECT RATE API
Route::get('/admin/rate_api', [AdminController::class, 'rate_api'])->name('rate_api');
Route::get('/admin/rate_api_fetch', [AdminController::class, 'rate_api_fetch'])->name('rate_api_fetch');
Route::post('/admin/rate_api_update', [AdminController::class, 'rate_api_update'])->name('rate_api_update');
Route::post('/admin/rate_api_delete', [AdminController::class, 'rate_api_delete'])->name('rate_api_delete');
Route::post('/admin/rate_api_add_data', [AdminController::class, 'rate_api_add_data'])->name('rate_api_add_data');
Route::get('/admin/rate_api_edit/{id}', [AdminController::class, 'rate_api_edit'])->name('rate_api_edit');
Route::post('/admin/rate_api_edit_data', [AdminController::class, 'rate_api_edit_data'])->name('rate_api_edit_data');
// CONNECT RATE API

// ADMIN RATE API
Route::get('/admin/admin_rate', [AdminController::class, 'admin_rate'])->name('admin_rate');
Route::get('/admin/admin_rate_fetch', [AdminController::class, 'admin_rate_fetch'])->name('admin_rate_fetch');
Route::get('/admin/refresh_rate_data', [AdminController::class, 'refresh_rate_data'])->name('refresh_rate_data');
Route::get('/admin/admin_rate_edit/{id}', [AdminController::class, 'admin_rate_edit'])->name('admin_rate_edit');
Route::post('/admin/admin_rate_edit_data', [AdminController::class, 'admin_rate_edit_data'])->name('admin_rate_edit_data');
// ADMIN RATE API

// CONNECT ARTICLES
Route::get('/admin/connect_articles', [AdminController::class, 'connect_articles'])->name('connect_articles');
Route::get('/admin/connect_articles_fetch', [AdminController::class, 'connect_articles_fetch'])->name('connect_articles_fetch');
Route::post('/admin/connect_article_update', [AdminController::class, 'connect_article_update'])->name('connect_article_update');
Route::post('/admin/connect_article_delete', [AdminController::class, 'connect_article_delete'])->name('connect_article_delete');
Route::post('/admin/connect_article_add_data', [AdminController::class, 'connect_article_add_data'])->name('connect_article_add_data');
Route::get('/admin/connect_article_edit/{id}', [AdminController::class, 'connect_article_edit'])->name('connect_article_edit');
Route::post('/admin/connect_article_edit_data', [AdminController::class, 'connect_article_edit_data'])->name('connect_article_edit_data');
// CONNECT ARTICLES

// CURRENCY RATE
Route::get('/admin/currency_rate', [AdminController::class, 'currency_rate'])->name('currency_rate');
// CURRENCY RATE

// FUND WALLET REQUESTS
Route::get('/admin/fund_wallet_requests', [AdminController::class, 'fund_wallet_requests'])->name('fund_wallet_requests');
Route::get('/admin/fund_wallet_requests_fetch', [AdminController::class, 'fund_wallet_requests_fetch'])->name('fund_wallet_requests_fetch');
Route::post('/admin/fund_wallet_requests_update', [AdminController::class, 'fund_wallet_requests_update'])->name('fund_wallet_requests_update');
Route::post('/admin/fund_wallet_requests_delete', [AdminController::class, 'fund_wallet_requests_delete'])->name('fund_wallet_requests_delete');
Route::get('/admin/fund_wallet_requests_edit/{id}', [AdminController::class, 'fund_wallet_requests_edit'])->name('fund_wallet_requests_edit');
// FUND WALLET REQUESTS

// WITHDRAW WALLETS REQUESTS
Route::get('/admin/withdraw_wallets_requests', [AdminController::class, 'withdraw_wallets_requests'])->name('withdraw_wallets_requests');
Route::get('/admin/withdraw_wallets_requests_fetch', [AdminController::class, 'withdraw_wallets_requests_fetch'])->name('withdraw_wallets_requests_fetch');
Route::post('/admin/withdraw_wallets_requests_update', [AdminController::class, 'withdraw_wallets_requests_update'])->name('withdraw_wallets_requests_update');
Route::post('/admin/withdraw_wallets_requests_delete', [AdminController::class, 'withdraw_wallets_requests_delete'])->name('withdraw_wallets_requests_delete');
Route::get('/admin/withdraw_wallets_requests_edit/{id}', [AdminController::class, 'withdraw_wallets_requests_edit'])->name('withdraw_wallets_requests_edit');
// WITHDRAW WALLETS REQUESTS

/* ----------------------------------- ADMIN PANEL --------------------------------------------- */