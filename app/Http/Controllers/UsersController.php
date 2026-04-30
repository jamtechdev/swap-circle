<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use App\Models\Event_post;
use App\Models\Tag;
use App\Models\Event_tag;

use App\Http\Controllers\Controller;
use App\User;
use Carbon\Carbon;
use DB;

use Artisan;
use Session;

class UsersController extends Controller{
    public $successStatus = 200;
    public $errorStatus = 401;

    // -------------- CACHE PAGE ------------- //
    public function clear_cache(Request $request){
        $exitCode = Artisan::call('route:clear');
        $exitCode = Artisan::call('config:cache');
        $exitCode = Artisan::call('cache:clear');
        $exitCode = Artisan::call('view:clear');

        Session::flash('success', 'Cache Cleared!'); 
        return redirect('users/dashboard');
    }
    // -------------- CACHE PAGE ------------- //
    
    // -------------- SIGNUP ------------- //
	public function users_customers_signup(){
        if (session()->has('id')) {
            return redirect('users/dashboard');
        } else{
            return view('users.users_customers_signup');
        }
    }
    // -------------- SIGNUP ------------- //
    
    // -------------- SIGNUP INDIVIDUAL ------------- //
    public function users_customers_signup_individual(){
        if (session()->has('id')) {
            return redirect('users/dashboard');
        } else{
            return view('users.users_customers_signup_individual');
        }
    }
    // -------------- SIGNUP INDIVIDUAL ------------- //

    // -------------- SIGNUP CORPORATE ------------- //
    public function users_customers_signup_corporate(){
        if (session()->has('id')) {
            return redirect('users/dashboard');
        } else{
            return view('users.users_customers_signup_corporate');
        }
    }
    // -------------- SIGNUP CORPORATE ------------- //
    
    // -------------- SIGNUP WAIT ------------- //
    public function users_customers_signup_wait(){
        if (session()->has('id')) {
            return redirect('users/dashboard');
        } else{
            return view('users.users_customers_signup_wait');
        }
    }
    // -------------- SIGNUP WAIT ------------- //

    // -------------- SIGNUP AUTHENTICATION PROCESSING ------------- //
    public function users_customers_signup_process(Request $request){
        if (isset($request->users_customers_type) && isset($request->users_customers_id) && isset($request->email) && isset($request->phone)) {
            $request->session()->put([
                'users_customers_type' => $request->users_customers_type,
                'id'                   => $request->users_customers_id,
                'profile_pic'          => $request->profile_pic,
                'first_name'           => $request->first_name,
                'last_name'            => $request->last_name ?? '',
                'company_name'         => $request->company_name ?? '',
                'email'                => $request->email,
                'phone'                => $request->phone,
            ]);
            Session::flash('success', 'Signed up successfully.');
            return true;
        }
        return false;
    }
    // -------------- SIGNUP AUTHENTICATION PROCESSING ------------- //
    
    // -------------- SIGNUP VERIFIED ------------- //
    public function users_customers_signup_verified(){
        return view('users.users_customers_signup_verified');
    }
    // -------------- SIGNUP VERIFIED ------------- //


    // -------------- LOGIN AUTHENTICATION MAIN ------------- //
	public function users_customers_login(){
        if (session()->has('id')) {
            return redirect('users/dashboard');
        } else{
            return view('users.users_customers_login');
        }
    }
    // -------------- LOGIN AUTHENTICATION MAIN ------------- //
    
    // -------------- LOGIN AUTHENTICATION PROCESSING ------------- //
    public function users_customers_login_process(Request $request){
        if (isset($request->users_customers_type) && isset($request->users_customers_id) && isset($request->email) && isset($request->phone)) {
            $request->session()->put([
                'users_customers_type' => $request->users_customers_type,
                'id'                   => $request->users_customers_id,
                'profile_pic'          => $request->profile_pic,
                'first_name'           => $request->first_name,
                'last_name'            => $request->last_name ?? '',
                'company_name'         => $request->company_name ?? '',
                'email'                => $request->email,
                'phone'                => $request->phone,
            ]);
            Session::flash('success', 'Logged in successfully.');
            return true;
        }
        return false;
    }
    // -------------- LOGIN AUTHENTICATION PROCESSING ------------- //

    // -------------- LOGOUT ------------- //
    public function users_customers_logout(Request $request){
        // $request->session()->flush();
        $request->session()->forget('id');
        return redirect('/');
    }
    // -------------- LOGOUT ------------- //
    
    // -------------- FORGOT PASSWORD ------------- //
    public function users_customers_forgot_password(){
        if (session()->has('id')) {
            return redirect('users/dashboard');
        } else{
            return view('users.users_customers_forgot_password');
        }
    }
    // -------------- FORGOT PASSWORD ------------- //

    // -------------- VERIFICATION CODE ------------- //
    public function users_customers_verification_code($id){
        return view('users.users_customers_verification_code', compact('id'));
    }
    // -------------- VERIFICATION CODE ------------- //

    // -------------- RESET PASSWORD ------------- //
    public function users_customers_reset_password($email, $otp){
        if(session()->has('id')) {
            return redirect('/users/dashbaord');
        } else {
            return view('users.users_customers_reset_password', compact('email', 'otp'));
        } 
    }
    // -------------- RESET PASSWORD ------------- //
    
    // -------------- DASHBOARD ------------- //
    // public function users_customers_dashboard(){
    //     if (!session()->has('id')) {
    //         return redirect('/');
    //     } else{
    //         $products_purchases = DB::table('products_purchases')->orderBy('products_purchases_id', 'DESC')->get();
    //         if (count($products_purchases) > 0) {
    //             foreach($products_purchases as $prod_purchase) {
    //                 $prod_purchase->product = DB::table('products')->where('products_id', $prod_purchase->products_id)->first();
    //                 if ($prod_purchase->product->type == 'A' || $prod_purchase->product->type == 'B') {
    //                     $prod_purchase->beneficiary                 = DB::table('products_purchases_beneficiaries')->where('products_purchases_id', $prod_purchase->products_purchases_id)->first();
    //                     $prod_purchase->beneficiary->occupation     = DB::table('occupations')->where('occupations_id', $prod_purchase->beneficiary->occupations_id)->first()->name;
    //                     $prod_purchase->beneficiary->relationship   = DB::table('relationships')->where('relationships_id', $prod_purchase->beneficiary->relationships_id)->first()->name;
    //                 }
    //             }

    //             return view('users.dashboard', compact('products_purchases'));
    //         } else {
    //             return redirect('/users/products');
    //         }
    //     }
    // }
    public function users_customers_dashboard()
    {
        if (!session()->has('id')) {
            return redirect('/');
        }

        try {
            app(\App\services\InsuretechSyncService::class)->pullProductsFromAdmin();
        } catch (\Throwable $exception) {
            \Log::warning('Unable to pull admin products in users_customers_dashboard.', [
                'error' => $exception->getMessage(),
            ]);
        }

        $products = DB::table('products')
            ->where('status', 'Active')
            ->orderBy('products_id', 'ASC')
            ->get();

        $products_purchases = DB::table('products_purchases')
            ->where('users_customers_id', session('id'))
            ->orderBy('products_purchases_id', 'DESC')
            ->get();

        foreach ($products_purchases as $prod_purchase) {

            // Product
            $prod_purchase->product = DB::table('products')
                ->where('products_id', $prod_purchase->products_id)
                ->first();

            // Safety check: product exists
            if (!$prod_purchase->product) {
                continue;
            }

            if (in_array($prod_purchase->product->type, ['A', 'B'])) {

                // Beneficiary
                $prod_purchase->beneficiary = DB::table('products_purchases_beneficiaries')
                    ->where('products_purchases_id', $prod_purchase->products_purchases_id)
                    ->first();

                // 👇 IMPORTANT NULL CHECK
                if (!$prod_purchase->beneficiary) {
                    continue;
                }

                // Occupation (SAFE)
                $prod_purchase->beneficiary->occupation =
                    DB::table('occupations')
                        ->where('occupations_id', $prod_purchase->beneficiary->occupations_id)
                        ->value('name');

                // Relationship (SAFE)
                $prod_purchase->beneficiary->relationship =
                    DB::table('relationships')
                        ->where('relationships_id', $prod_purchase->beneficiary->relationships_id)
                        ->value('name');
            }
        }

        return view('users.dashboard', compact('products_purchases', 'products'));
    }
    // -------------- DASHBOARD ------------- //

    // -------------- WALLETS ------------- //
    public function users_customers_wallets(){
        if (!session()->has('id')) {
            return redirect('/');
        } else{
            return view('users.users_customers_wallets');
        }
    }
    // -------------- WALLETS ------------- //
    
    // -------------- DATA ANALYSIS ------------- //
    public function users_customers_data_analysis(){
    	if (!session()->has('id')) {
            return redirect('/');
        } else{
            return view('users.users_customers_data_analysis');
        }
    }
    // -------------- DATA ANALYSIS ------------- //
    
    // -------------- OFFERS ------------- //
    public function users_customers_offers(){
    	if (!session()->has('id')) {
            return redirect('/');
        } else{
            return view('users.users_customers_offers');
        }
    }
    // -------------- OFFERS ------------- //

    // -------------- OFFER REQUESTS ------------- //
     public function users_customers_offer_requests($offer_id){
        if (!session()->has('id')) {
            return redirect('/');
        } else{
            return view('users.users_customers_offer_requests', compact('offer_id'));
        }
    }
    // -------------- OFFER REQUESTS ------------- //

    // -------------- PRODUCTS ------------- //
    public function users_customers_products(){
    	if (!session()->has('id')) {
            return redirect('/');
        } else{
            try {
                app(\App\services\InsuretechSyncService::class)->pullProductsFromAdmin();
            } catch (\Throwable $exception) {
                \Log::warning('Unable to pull admin products in users_customers_products.', [
                    'error' => $exception->getMessage(),
                ]);
            }
            $products      = DB::table('products')->where('status', 'Active')->orderBy('products_id', 'ASC')->get();
            return view('users.users_customers_products', compact('products'));
        }
    }
    // -------------- PRODUCTS ------------- //

    // -------------- PRODUCTS BUY ------------- //
    public function products_buy($type, $id){
    	if (!session()->has('id')) {
            return redirect('/');
        } else{
            try {
                app(\App\services\InsuretechSyncService::class)->pullProductsFromAdmin();
            } catch (\Throwable $exception) {
                \Log::warning('Unable to pull admin products in products_buy.', [
                    'error' => $exception->getMessage(),
                ]);
            }
            $occupations     = DB::table('occupations')->where('status', 'Active')->orderBy('occupations_id', 'ASC')->get();
            $relationships   = DB::table('relationships')->where('status', 'Active')->orderBy('relationships_id', 'ASC')->get();
            $tasks_types     = DB::table('tasks_types')->where('status', 'Active')->orderBy('tasks_types_id', 'ASC')->get();
            $product         = DB::table('products')->where('products_id', $id)->first();
            return view('users.products_buy', compact('product', 'occupations', 'relationships', 'tasks_types'));
        }
    }
    // -------------- PRODUCTS BUY ------------- //

    // -------------- PRODUCTS CLAIMS ------------- //
     public function products_claims(){
    	if (!session()->has('id')) {
            return redirect('/');
        } else{ 
            $purchased_products = DB::table('products_purchases')
                                ->where('users_customers_id', session('id'))
                                ->select('products_purchases.*')
                                ->whereIn('products_purchases_id', function($query) {
                                    $query->select(DB::raw('MAX(products_purchases_id)'))
                                        ->from('products_purchases')
                                        ->groupBy('products_id');
                                })
                                ->orderBy('products_purchases_id', 'ASC')
                                ->get(); 

            foreach($purchased_products as $purchased_product) {
               $purchased_product->product  = DB::table('products')->where('products_id', $purchased_product->products_id)->first();
            }
            return view('users.products_claims', compact('purchased_products'));
        }
    }
    // -------------- PRODUCTS CLAIMS ------------- //
    
    // -------------- TRACK ------------- //
    public function users_customers_track(){
    	if (!session()->has('id')) {
            return redirect('/');
        } else{
            return view('users.users_customers_track');
        }
    }
    // -------------- TRACK ------------- //
    
    // -------------- CONNECT ------------- //
    public function users_customers_connect(){
    	if (!session()->has('id')) {
            return redirect('/');
        } else{
            return view('users.users_customers_connect');
        }
    }
    // -------------- CONNECT ------------- //

    // -------------- CONNECT BLOG ------------- //
    public function users_customers_connect_blog($blog_id){
       return view('users.users_customers_connect_blog', compact('blog_id'));
    }
    // -------------- CONNECT BLOG ------------- //
    
    // -------------- PROFILE ------------- //
    public function users_customers_profile(){
    	if (!session()->has('id')) {
            return redirect('/');
        } else{
            return view('users.users_customers_profile');
        }
    }
    // -------------- PROFILE ------------- //
    
    // -------------- PROFILE EDIT ------------- //
    public function users_customers_profile_edit(){
    	if (!session()->has('id')) {
            return redirect('/');
        } else{
            return view('users.users_customers_profile_edit');
        }
    }
    // -------------- PROFILE EDIT ------------- //

    // -------------- BILLING PAYMENT ------------- //
    public function users_customers_billing_payment(){
        if (!session()->has('id')) {
            return redirect('/');
        } else{
            return view('users.users_customers_billing_payment');
        }
    }
    // -------------- BILLING PAYMENT ------------- //
    
    // -------------- TRANSACTIONS ------------- //
    public function users_customers_transactions(){
        if (!session()->has('id')) {
            return redirect('/');
        } else{
            return view('users.users_customers_transactions');
        }
    }
    // -------------- TRANSACTIONS ------------- //

    // -------------- SETTINGS ------------- //
    public function users_customers_settings(){
        if (!session()->has('id')) {
            return redirect('/');
        } else{
            return view('users.users_customers_settings');
        }
    }
    // -------------- SETTINGS ------------- //
    
    // -------------- FAQS------------- //
    public function users_customers_faqs(){
    	if (!session()->has('id')) {
            return redirect('/');
        } else{
            return view('users.users_customers_faqs');
        }
    }
    // -------------- FAQS ------------- //
    
    // -------------- MESSAGE------------- //
    public function users_customers_message($user_id = null){
        if (!session()->has('id')) {
            return redirect('/');
        } else{
            return view('users.users_customers_message', compact('user_id'));
        }
    }
    // -------------- MESSAGE------------- //

    
    // -------------- STRIPE PAYMENT SUCCESS ------------- //
    public function stripe_payment_success(Request $request){
        if (!session()->has('id')) {
            return redirect('/');
        }

        $session_id = $request->query('session_id');
        $purchase_id = $request->query('purchase_id');

        if (!$session_id || !$purchase_id) {
            return redirect('/users/products')->with('error', 'Invalid payment session.');
        }

        // Call API to handle success
        $apiController = new ApiController();
        $apiRequest = new Request();
        $apiRequest->merge([
            'session_id' => $session_id,
            'purchase_id' => $purchase_id
        ]);

        $response = $apiController->handleStripeSuccess($apiRequest);
        $responseData = json_decode($response->getContent(), true);

        if ($responseData['status'] === 'success') {
            return view('users.stripe_payment_success', ['purchase_id' => $purchase_id])->with('success', 'Payment completed successfully! Your purchase has been confirmed.');
        } else {
            return redirect('/users/products')->with('error', $responseData['message'] ?? 'Payment verification failed.');
        }
    }
    // -------------- STRIPE PAYMENT SUCCESS ------------- //

    // -------------- PRODUCT VIEW ------------- //
    public function product_view($product_id){
        if (!session()->has('id')) {
            return redirect('/');
        } else{
            try {
                app(\App\services\InsuretechSyncService::class)->pullProductsFromAdmin();
            } catch (\Throwable $exception) {
                \Log::warning('Unable to pull admin products in product_view.', [
                    'error' => $exception->getMessage(),
                ]);
            }
            $product = DB::table('products')->where('products_id', $product_id)->first();
            if (!$product) {
                return redirect('/users/products')->with('error', 'Product not found.');
            }
            // Only show download if THIS user has a paid purchase for this product
            $purchase = DB::table('products_purchases')
                ->where('products_id', $product_id)
                ->where('users_customers_id', session('id'))
                ->where('payment_status', 'Successful')
                ->orderBy('products_purchases_id', 'DESC')
                ->first();
            return view('users.product_view', compact('product', 'purchase'));
        }
    }
    // -------------- PRODUCT VIEW ------------- //

    // -------------- DOWNLOAD INVOICE ------------- //
    public function download_invoice($purchase_id) {
        $purchase    = DB::table('products_purchases')->where('products_purchases_id', $purchase_id)->first();
        if (!$purchase) {
            abort(404);
        }
        $product     = DB::table('products')->where('products_id', $purchase->products_id)->first();
        $customer    = DB::table('users_customers')->where('users_customers_id', $purchase->users_customers_id)->first();
        $beneficiary = null;
        $task        = null;
        if (in_array($purchase->product_type, ['A','B'])) {
            $beneficiary = DB::table('products_purchases_beneficiaries')->where('products_purchases_id', $purchase_id)->first();
            if ($beneficiary) {
                $beneficiary->occupation   = DB::table('occupations')->where('occupations_id', $beneficiary->occupations_id)->value('name');
                $beneficiary->relationship = DB::table('relationships')->where('relationships_id', $beneficiary->relationships_id)->value('name');
            }
        }
        if ($purchase->product_type === 'C') {
            $task = DB::table('products_purchases_tasks')->where('products_purchases_id', $purchase_id)->first();
        }
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.invoice', compact('purchase', 'product', 'customer', 'beneficiary', 'task'));
        return $pdf->download('invoice-'.$purchase_id.'.pdf');
    }
    // -------------- DOWNLOAD INVOICE ------------- //

    // -------------- STRIPE PAYMENT CANCEL ------------- //
    public function stripe_payment_cancel(Request $request){
        if (!session()->has('id')) {
            return redirect('/');
        }

        $purchase_id = $request->query('purchase_id');

        if (!$purchase_id) {
            return redirect('/users/products')->with('error', 'Invalid purchase session.');
        }

        // Call API to handle cancellation
        $apiController = new ApiController();
        $apiRequest = new Request();
        $apiRequest->merge(['purchase_id' => $purchase_id]);

        $response = $apiController->handleStripeCancel($apiRequest);
        $responseData = json_decode($response->getContent(), true);

        return view('users.stripe_payment_cancel')->with('warning', 'Payment was cancelled. You can try purchasing again.');
    }
    // -------------- STRIPE PAYMENT CANCEL ------------- //
}
