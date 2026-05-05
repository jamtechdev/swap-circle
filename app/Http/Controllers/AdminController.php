<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use App\Models\Event_post;
use App\Models\Tag;
use App\Models\Event_tag;
use App\Models\{
    UsersCustomersWallet,
    SystemCurrency,
    SystemCountry,
    UsersCustomersTxns,
    SwapWallet,
    SwapOffer,
    SwapOfferRequest,
    Feedback,
    FAQ,
    FavoriteSwapOffer
};
use App\Http\Controllers\Controller;
use App\User;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;
use Artisan;
use Illuminate\Support\Facades\Validator;
use Session;

class AdminController extends Controller
{
    public $successStatus = 200;
    public $errorStatus = 401;

    // -------------- CACHE PAGE ------------- //
    public function clear_cache(Request $request)
    {
        $exitCode = Artisan::call('route:clear');
        $exitCode = Artisan::call('config:cache');
        $exitCode = Artisan::call('cache:clear');
        $exitCode = Artisan::call('view:clear');

        Session::flash('success', 'Cache Cleared!');
        return redirect('admin/dashboard');
    }
    // -------------- CACHE PAGE ------------- //

    // -------------- LOGIN PAGE ------------- //
    public function index(Request $request)
    {
        if ($request->session()->has('admin_id')) {
            return redirect('admin/dashboard');
        } else {
            return view('admin.login');
        }
    }
    // -------------- LOGIN PAGE ------------- //

    // -------------- LOGIN AUTHENTICATION ------------- //
    public function login(Request $request)
    {
        $validateData = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $postData = $request->all();
        $ifExists = DB::table('users_system')->where('email', $postData['email'])->where('password', $postData['password'])->first();
        if (!empty($ifExists)) {
            $request->session()->put([
                'id' => $ifExists->users_system_id,
                'admin_id' => $ifExists->users_system_id,
                'users_system_roles_id' => $ifExists->users_system_roles_id,
                'user_image' => $ifExists->user_image,
                'fname' => $ifExists->first_name,
                'lname' => '',
                'email' => $ifExists->email,
            ]);
            Session::flash('success', ' Logged in successfully.');
            return redirect('admin/dashboard');
        } else {
            Session::flash('error', 'Invalid Email/Password');
            return redirect()->back();
        }
    }
    // -------------- LOGIN AUTHENTICATION ------------- //

    // -------------- LOGOUT ------------- //
    public function logout(Request $request)
    {
        // $request->session()->flush();
        $request->session()->forget('admin_id');
        return redirect('admin/');
    }
    // -------------- LOGOUT ------------- //

    // ------------- DASHBOARD -------------- //
    public function dashboard()
    {
        if (session()->has('admin_id')) {
            $total_users_customers     = number_format(DB::table('users_customers')->count());
            $system_currency    = DB::table('system_settings')->select('description')->where('type', 'system_currency')->first();
            $dbToken = (string) optional(DB::table('system_settings')->where('type', 'insuretech_partner_token')->first())->description;
            $dbBaseUrl = (string) optional(DB::table('system_settings')->where('type', 'insuretech_admin_base_url')->first())->description;
            $dbTimeout = (string) optional(DB::table('system_settings')->where('type', 'insuretech_request_timeout')->first())->description;

            $token = trim($dbToken) !== '' ? trim($dbToken) : (string) env('INSURETECH_PARTNER_TOKEN', '');
            $adminBaseUrl = rtrim(trim($dbBaseUrl) !== '' ? trim($dbBaseUrl) : (string) env('INSURETECH_ADMIN_BASE_URL', ''), '/');
            $requestTimeout = (int) (trim($dbTimeout) !== '' ? trim($dbTimeout) : env('INSURETECH_REQUEST_TIMEOUT', 20));
            $isTokenConfigured = $token !== '';
            $isApiConnected = false;
            $connectionMessage = 'Token missing in .env';

            if ($isTokenConfigured && $adminBaseUrl !== '') {
                try {
                    $response = Http::timeout($requestTimeout > 0 ? $requestTimeout : 20)
                        ->acceptJson()
                        ->withToken($token)
                        ->get($adminBaseUrl.'/api/v1/partner/products');

                    if ($response->successful()) {
                        $isApiConnected = true;
                        $connectionMessage = 'Connected to insurtech admin API';
                    } else {
                        $connectionMessage = 'Token present but API handshake failed (HTTP '.$response->status().')';
                    }
                } catch (\Throwable $exception) {
                    $connectionMessage = 'Token present but API unreachable';
                }
            } elseif ($isTokenConfigured) {
                $connectionMessage = 'Token present but INSURETECH_ADMIN_BASE_URL missing';
            }

            return view('admin.dashboard', compact(
                'total_users_customers',
                'isTokenConfigured',
                'isApiConnected',
                'connectionMessage'
            ));
        } else {
            return redirect('admin/');
        }
    }
    // ------------- DASHBOARD -------------- //

    // ------------- MANAGE CUSTOMERS -------------- //
    public function users_customers(Request $request)
    {
        if ($request->session()->has('admin_id')) {
            if (empty($request->get('filter'))) {
                $users = DB::table('users_customers')->orderBy('users_customers_id', 'DESC')->get();
            } else {
                $users = DB::table('users_customers')->where('status', $request->get('filter'))->orderBy('users_customers_id', 'DESC')->get();
            }

            $filter = $request->get('filter');
            return view('admin.users_customers', compact('users', 'filter'));
        } else {
            return redirect('admin');
        }
    }
    // ------------- MANAGE CUSTOMERS -------------- //

    // ------------- MANAGE CUSTOMERS VIEW -------------- //
    public function users_customers_view($id)
    {
        if (session()->has('admin_id')) {
            $users_data         = DB::table('users_customers')->where('users_customers_id', $id)->get();
            return view('admin.users_customers_view', compact('users_data'));
        } else {
            return redirect('admin');
        }
    }
    // ------------- MANAGE CUSTOMERS VIEW -------------- //

    // ------------- UPDATE CUSTOMERS -------------- //
    public function users_customers_update(Request $req, $id, $status)
    {
        $update_array['status'] = $status;
        if ($req->status == 'Active') {
            $update_array['verified_badge'] = 'Yes';
        }
        $updated = DB::table('users_customers')->where('users_customers_id', $id)->update($update_array);
        if ($updated) {
            Session::flash('success', ' Data Updated successfully');
            return back()->with('success', 'Data Updated successfully');
        } else {
            Session::flash('error', ' Oops! something went wrong');
            return back()->with('errors', 'Oops! something went wrong');
        }
    }
    // ------------- UPDATE CUSTOMERS -------------- //

    // ------------- DELETE CUSTOMERS -------------- //
    public function users_customers_delete(Request $req, $id)
    {
        if (session()->has('admin_id')) {
            if (!empty($req->id)) {
                $checkdata = DB::table('users_customers')->where('users_customers_id', $id)->where('status', '!=', 'Deleted')->first();

                if ($checkdata) {
                    $del = DB::table('users_customers')->where('users_customers_id', '=', $id)->update(array('status' => 'Deleted'));
                    if ($del) {
                        Session::flash('success', ' Data Deleted successfully');
                        return redirect('admin/users_customers');
                    } else {
                        Session::flash('error', ' Oops! something went wrong');
                        return redirect('admin/users_customers');
                    }
                } else {
                    Session::flash('error', ' This record is already deleted in status');
                    return redirect('admin/users_customers');
                }
            } else {
                Session::flash('error', ' No Data Found');
                return redirect('admin/users_customers');
            }
        } else {
            return redirect('admin');
        }
    }
    // ------------- DELETE CUSTOMERS -------------- //

    /*** SUPPORT ***/
    public function support()
    {
        if (!session()->has('admin_id')) {
            return redirect('admin');
        } else {
            return view('admin.support');
        }
    }
    /*** SUPPORT ***/

    // ------------- ACCOUNT SETTINGS -------------- //
    public function account_settings()
    {
        if (session()->has('admin_id')) {
            $page_name = 'account_settings';
            $fetch_data = DB::table('users_system')->where('users_system_id', session('admin_id'))->get();
            return view('admin.account_settings', compact('fetch_data', 'page_name'));
        } else {
            return redirect('admin');
        }
    }
    // ------------- ACCOUNT SETTINGS -------------- //

    // ------------- UPDATE ACCOUNT SETTINGS -------------- //
    public function account_settings_update(Request $req, $id)
    {
        $insert = array();
        $insert['first_name'] = $req->first_name;
        $insert['email'] = $req->email;
        $insert['password'] = $req->password;

        $insert['city'] = $req->city;
        $insert['address'] = $req->address;
        $insert['mobile'] = $req->mobile;

        if ($req->hasfile('image')) {
            $file = $req->file('image');
            if ($file->isValid()) {
                $ext = $file->extension();
                $path = public_path('uploads/users_system/');
                $prefix = 'user-' . md5(time());
                $img_name = $prefix . '.' . $ext;
                if ($file->move($path, $img_name)) {
                    $insert['user_image'] = 'uploads/users_system/' . $img_name;
                }
            }
        }

        $a = DB::table('users_system')->where('users_system_id', '=', $id)->update($insert);
        if ($a) {
            Session::flash('success', ' Profile Updated successfully');
            return redirect('admin/account_settings');
        } else {
            Session::flash('error', ' oops! something went wrong');
            return redirect('admin/account_settings');
        }
    }
    // ------------- UPDATE ACCOUNT SETTINGS -------------- //

    // ------------- MANAGE SYSTEM SETTINGS -------------- //
    public function system_settings(Request $request)
    {
        if ($request->session()->has('admin_id')) {
            $page_name = 'system_settings';
            $system_settings = DB::table('system_settings')->get();
            $system_currency = DB::table('system_currencies')->get();
            return view('admin.system_settings', compact('system_settings', 'page_name', 'system_currency'));
        } else {
            return redirect('admin');
        }
    }
    // ------------- MANAGE SYSTEM ROLES EDIT -------------- //

    // ------------- MANAGE SYSTEM USERS ROLES DATA -------------- //
    public function system_settings_edit(Request $req)
    { //echo '<pre>'; print_r($req->all()); exit;
        $page_name  = $req->page_name;

        if (isset($req->invite_text)) {
            $data['description']          = $req->invite_text;
            DB::table('system_settings')->where('type', 'invite_text')->update($data);
        }

        if (isset($req->transfer_instructions)) {
            $data['description']          = $req->transfer_instructions;
            DB::table('system_settings')->where('type', 'transfer_instructions')->update($data);
        }

        if (isset($req->admin_share)) {
            $data['description']          = $req->admin_share;
            DB::table('system_settings')->where('type', 'admin_share')->update($data);
        }

        if (isset($req->email)) {
            $data['description']          = $req->email;
            DB::table('system_settings')->where('type', 'email')->update($data);
        }

        if (isset($req->phone)) {
            $data['description']          = $req->phone;
            DB::table('system_settings')->where('type', 'phone')->update($data);
        }

        if (isset($req->system_name)) {
            $data['description']          = $req->system_name;
            DB::table('system_settings')->where('type', 'system_name')->update($data);
        }

        if (isset($req->address)) {
            $data['description']          = $req->address;
            DB::table('system_settings')->where('type', 'address')->update($data);
        }

        if (isset($req->system_currencies_id)) {
            $data['description']          = $req->system_currencies_id;
            DB::table('system_settings')->where('type', 'system_currencies_id')->update($data);
        }

        if (isset($req->social_login)) {
            $data['description']          = $req->social_login;
            DB::table('system_settings')->where('type', 'social_login')->update($data);
        }

        if (isset($req->about_text)) {
            $data['description']          = $req->about_text;
            DB::table('system_settings')->where('type', 'about_text')->update($data);
        }

        if (isset($req->terms_text)) {
            $data['description']          = $req->terms_text;
            DB::table('system_settings')->where('type', 'terms_text')->update($data);
        }

        if (isset($req->privacy_text)) {
            $data['description']          = $req->privacy_text;
            DB::table('system_settings')->where('type', 'privacy_text')->update($data);
        }
        if (isset($req->swap_offer_expire)) {
            $data['description']          = $req->swap_offer_expire;
            DB::table('system_settings')->where('type', 'swap_offer_expire')->update($data);
        }

        if (isset($req->main_heading_label1)) {
            $data['description']          = $req->main_heading_label1;
            DB::table('system_settings')->where('type', 'main_heading_label1')->update($data);
        }

        if (isset($req->main_heading_label2)) {
            $data['description']          = $req->main_heading_label2;
            DB::table('system_settings')->where('type', 'main_heading_label2')->update($data);
        }

        if (isset($req->subheading_label)) {
            $data['description']          = $req->subheading_label;
            DB::table('system_settings')->where('type', 'subheading_label')->update($data);
        }

        if (isset($req->forgot_password_link_label)) {
            $data['description']          = $req->forgot_password_link_label;
            DB::table('system_settings')->where('type', 'forgot_password_link_label')->update($data);
        }

        if (isset($req->btn_login_label)) {
            $data['description']          = $req->btn_login_label;
            DB::table('system_settings')->where('type', 'btn_login_label')->update($data);
        }

        if (isset($req->signup_text_lable)) {
            $data['description']          = $req->signup_text_lable;
            DB::table('system_settings')->where('type', 'signup_text_lable')->update($data);
        }

        if (isset($req->signup_link_label)) {
            $data['description']          = $req->signup_link_label;
            DB::table('system_settings')->where('type', 'signup_link_label')->update($data);
        }
        if (isset($req->login_bg_image)) {
            $image              = $req->file('login_bg_image');
            $image1_name        = $image->getClientOriginalName();
            $destinationPath    = public_path() . '/users/auth';
            $image_n            = $image1_name;
            $uploaded           = $image->move($destinationPath, $image1_name);

            $data['description'] = 'users/auth/' . $image_n;
            DB::table('system_settings')->where('type', 'auth_bg_image')->update($data);
        }

        if (isset($req->login_image)) {
            $image              = $req->file('login_image');
            $image1_name        = $image->getClientOriginalName();
            $destinationPath    = public_path() . '/users/auth';
            $image_n            = $image1_name;
            $uploaded           = $image->move($destinationPath, $image1_name);

            $data['description'] = 'users/auth/' . $image_n;
            DB::table('system_settings')->where('type', 'auth_image')->update($data);
        }

        if (isset($req->image)) {
            $image              = $req->file('image');
            $image1_name        = $image->getClientOriginalName();
            $destinationPath    = public_path() . '/uploads/system_image';
            $image_n            = $image1_name;
            $uploaded           = $image->move($destinationPath, $image1_name);

            $data['description'] = $image_n;
            DB::table('system_settings')->where('type', 'system_image')->update($data);
        }

        if (isset($req->insuretech_admin_base_url)) {
            DB::table('system_settings')->updateOrInsert(
                ['type' => 'insuretech_admin_base_url'],
                ['description' => trim((string) $req->insuretech_admin_base_url)]
            );
        }

        if (isset($req->insuretech_partner_token)) {
            DB::table('system_settings')->updateOrInsert(
                ['type' => 'insuretech_partner_token'],
                ['description' => trim((string) $req->insuretech_partner_token)]
            );
        }

        if (isset($req->insuretech_request_timeout)) {
            DB::table('system_settings')->updateOrInsert(
                ['type' => 'insuretech_request_timeout'],
                ['description' => max(1, (int) $req->insuretech_request_timeout)]
            );
        }

        session()->flash('success', 'System settings updated successfully!');
        return redirect('admin/' . $page_name);
    }
    // ------------- MANAGE SYSTEM USERS ROLES DATA -------------- //

    // ------------- MANAGE SYSTEM USERS -------------- //
    public function users_system(Request $request)
    {
        if ($request->session()->has('admin_id')) {
            $page_name = 'users_system';
            $users = db::table('users_system')->orderBy('users_system_id', 'DESC')->get();
            return view('admin.users_system', compact('users', 'page_name'));
        } else {
            return redirect('admin');
        }
    }
    // ------------- MANAGE SYSTEM USERS -------------- //

    // ------------- UPDATE SYSTEM USERS -------------- //
    public function users_system_update(Request $req)
    {
        $update_array['status'] = $req->status;
        $updated = DB::table('users_system')->where('users_system_id', $req->id)->update($update_array);
        if ($updated) {
            Session::flash('success', ' Data Updated successfully');
            return redirect('admin/users_system');
        } else {
            Session::flash('error', ' Oops! something went wrong');
            return back()->with('errors', 'Oops! something went wrong');
        }
    }
    // ------------- UPDATE SYSTEM USERS -------------- //

    // ------------- DELETE SYSTEM USERS -------------- //
    public function users_system_delete(Request $req)
    {
        if (session()->has('admin_id')) {
            if (!empty($req->id)) {
                $checkdata = DB::table('users_system')->where('users_system_id', $req->id)->get();

                if (count($checkdata) != 0) {
                    $del = DB::table('users_system')->where('users_system_id', $req->id)->delete();
                    if ($del) {
                        Session::flash('success', ' Data Deleted successfully');
                        return redirect('admin/users_system');
                    } else {
                        Session::flash('error', ' Oops! something went wrong');
                        return redirect('admin/users_system');
                    }
                } else {
                    Session::flash('error', ' This record is already deleted in status');
                    return redirect('admin/users_system');
                }
            } else {
                Session::flash('error', ' No Data Found');
                return redirect('admin/users_system');
            }
        } else {
            return redirect('admin');
        }
    }
    // ------------- DELETE SYSTEM USERS -------------- //

    // ------------- MANAGE SYSTEM USERS ADD -------------- //
    public function users_system_add(Request $request)
    {
        if ($request->session()->has('admin_id')) {
            $page_name = 'users_system';
            $roles = DB::table('users_system_roles')->orderBy('users_system_roles_id', 'DESC')->get();
            return view('admin.users_system_add', compact('roles', 'page_name'));
        } else {
            return redirect('admin');
        }
    }
    // ------------- MANAGE SYSTEM USERS ADD -------------- //

    // ------------- MANAGE SYSTEM USERS ADD DATA -------------- //
    public function users_system_add_data(Request $req)
    {
        $save_data['users_system_roles_id']     = $req->users_system_roles_id;
        $save_data['first_name']                = $req->first_name;
        $save_data['email']                     = $req->email;
        $save_data['password']                  = $req->password;
        $save_data['mobile']                    = $req->mobile;
        $save_data['city']                      = $req->city;
        $save_data['address']                   = $req->address;
        $save_data['status']                    = $req->status;

        if (isset($req->image)) {
            $image = $req->file('image');
            $image1_name = $image->getClientOriginalName();
            $destinationPath = public_path() . '/uploads/users_system';
            $image_n =  "uploads/users_system/" . $image1_name;
            $image->move($destinationPath, $image1_name);

            $save_data['user_image'] = $image_n;
        }
        $users_system_id = DB::table('users_system')->insertGetId($save_data);

        if ($users_system_id > 0) {
            session()->flash('success', 'User added successfully!');
            return redirect('admin/users_system');
        } else {
            session()->flash('error', 'Oops! Somrthing went wrong. Please try again.');
            return redirect()->back();
        }
    }
    // ------------- MANAGE SYSTEM USERS ADD DATA -------------- //

    // ------------- MANAGE SYSTEM USERS EDIT -------------- //
    public function users_system_edit(Request $request)
    {
        if ($request->session()->has('admin_id')) {
            $page_name = 'users_system';
            $roles = DB::table('users_system_roles')->orderBy('users_system_roles_id', 'DESC')->get();
            $users_system = DB::table('users_system')->where('users_system_id', $request->id)->get()->first();
            return view('admin.users_system_edit', compact('roles', 'users_system', 'page_name'));
        } else {
            return redirect('admin');
        }
    }
    // ------------- MANAGE SYSTEM USERS EDIT -------------- //

    // ------------- MANAGE SYSTEM USERS EDIT DATA -------------- //
    public function users_system_edit_data(Request $req)
    {
        $update_data['users_system_roles_id']     = $req->users_system_roles_id;
        $update_data['first_name']                = $req->first_name;
        $update_data['email']                     = $req->email;
        $update_data['password']                  = $req->password;
        $update_data['mobile']                    = $req->mobile;
        $update_data['city']                      = $req->city;
        $update_data['address']                   = $req->address;
        $update_data['status']                    = $req->status;

        if (isset($req->image)) {
            $image = $req->file('image');
            $image1_name = $image->getClientOriginalName();
            $destinationPath = public_path() . '/uploads/users_system';
            $image_n =  "uploads/users_system/" . $image1_name;
            $image->move($destinationPath, $image1_name);

            $update_data['user_image'] = $image_n;
        }
        $updated = DB::table('users_system')->where('users_system_id', $req->users_system_id)->update($update_data);

        if ($updated > 0) {
            session()->flash('success', 'User updated successfully!');
            return redirect('admin/users_system');
        } else {
            session()->flash('error', 'Oops! Somrthing went wrong. Please try again.');
            return redirect()->back();
        }
    }
    // ------------- MANAGE SYSTEM USERS EDIT DATA -------------- //

    // ------------- MANAGE SYSTEM ROLES -------------- //
    public function users_system_roles(Request $request)
    {
        if ($request->session()->has('admin_id')) {
            $page_name = 'users_system_roles';
            $users_system_roles = db::table('users_system_roles')->orderBy('users_system_roles_id', 'DESC')->get();
            return view('admin.users_system_roles', compact('users_system_roles', 'page_name'));
        } else {
            return redirect('admin');
        }
    }
    // ------------- MANAGE SYSTEM ROLES -------------- //

    // ------------- MANAGE SYSTEM ROLES ADD -------------- //
    public function users_system_roles_add(Request $request)
    {
        if ($request->session()->has('admin_id')) {
            $page_name = 'users_system_roles';
            return view('admin.users_system_roles_add', compact('page_name'));
        } else {
            return redirect('admin');
        }
    }
    // ------------- MANAGE SYSTEM ROLES ADD -------------- //

    // ------------- MANAGE SYSTEM ROLES ADD DATA -------------- //
    public function users_system_roles_add_data(Request $req)
    {
        $data['dashboard']           = $req->dashboard;
        $data['users_customers']     = $req->users_customers;
        $data['users_system']        = $req->users_system;
        $data['users_system_roles']  = $req->users_system_roles;
        $data['system_settings']     = $req->system_settings;
        $data['account_settings']    = $req->account_settings;
        $data['swap_offers']         = $req->swap_offers;
        $data['users_customers_trxns']  = $req->users_customers_trxns;
        $data['admin_rate']          = $req->admin_rate;
        $data['rate_api']            = $req->rate_api;
        $data['currency_rate']       = $req->currency_rate;
        $data['connect_categories']  = $req->connect_categories;
        $data['connect_articles']    = $req->connect_articles;
        $data['users_customers_faqs'] = $req->users_customers_faqs;

        $users_system_id = DB::table('users_system_roles')->insertGetId($data);

        if ($users_system_id > 0) {
            session()->flash('success', 'Role added successfully!');
            return redirect('admin/users_system_roles');
        } else {
            session()->flash('error', 'Oops! Somrthing went wrong. Please try again.');
            return redirect()->back();
        }
    }
    // ------------- MANAGE SYSTEM ROLES ADD DATA -------------- //

    // ------------- MANAGE SYSTEM ROLES EDIT -------------- //
    public function users_system_roles_edit(Request $request, $id)
    {
        if (session()->has('admin_id')) {
            $page_name = 'users_system_roles';
            $users_system_roles = DB::table('users_system_roles')->where('users_system_roles_id', $id)->get()->first();
            return view('admin.users_system_roles_edit', compact('users_system_roles', 'page_name'));
        } else {
            return redirect('admin');
        }
    }
    // ------------- MANAGE SYSTEM ROLES EDIT -------------- //

    // ------------- MANAGE SYSTEM USERS ROLES DATA -------------- //
    public function users_system_roles_edit_data(Request $req)
    {
        $data['name']                = $req->name;
        $data['status']              = $req->status;
        $data['dashboard']           = $req->dashboard;
        $data['users_customers']     = $req->users_customers;
        $data['users_system']        = $req->users_system;
        $data['users_system_roles']  = $req->users_system_roles;
        $data['system_settings']     = $req->system_settings;
        $data['account_settings']    = $req->account_settings;
        $data['swap_offers']         = $req->swap_offers;
        $data['users_customers_trxns']  = $req->users_customers_trxns;
        $data['admin_rate']          = $req->admin_rate;
        $data['rate_api']            = $req->rate_api;
        $data['currency_rate']       = $req->currency_rate;
        $data['connect_categories']  = $req->connect_categories;
        $data['connect_articles']    = $req->connect_articles;
        $data['users_customers_faqs'] = $req->users_customers_faqs;

        $updated = DB::table('users_system_roles')->where('users_system_roles_id', $req->users_system_roles_id)->update($data);

        if ($updated > 0) {
            session()->flash('success', 'Role updated successfully!');
            return redirect('admin/users_system_roles');
        } else {
            session()->flash('error', 'Oops! Somrthing went wrong. Please try again.');
            return redirect()->back();
        }
    }
    // ------------- MANAGE SYSTEM USERS ROLES DATA -------------- //

    // ------------- DELETE SYSTEM USERS ROLES -------------- //
    public function users_system_roles_delete(Request $req)
    {
        if (session()->has('admin_id')) {
            if (!empty($req->id)) {
                $checkdata = DB::table('users_system')->where('users_system_roles_id', $req->id)->get();

                if (count($checkdata) == 0) {
                    $del = DB::table('users_system_roles')->where('users_system_roles_id', $req->id)->delete();
                    if ($del) {
                        Session::flash('success', ' Data Deleted successfully');
                        return redirect('admin/users_system_roles');
                    } else {
                        Session::flash('error', ' Oops! something went wrong');
                        return redirect('admin/users_system_roles');
                    }
                } else {
                    Session::flash('error', ' This role is assigned to some users. Delete the users first.');
                    return redirect('admin/users_system_roles');
                }
            } else {
                Session::flash('error', ' No Data Found');
                return redirect('admin/users_system_roles');
            }
        } else {
            return redirect('admin');
        }
    }
    // ------------- DELETE SYSTEM USERS ROLES -------------- //

    // ------------- MANAGE SYSTEM  ABOUT US -------------- //
    public function system_about_us(Request $request)
    {
        if ($request->session()->has('admin_id')) {
            $page_name = 'system_about_us';
            $system_settings = DB::table('system_settings')->get();
            return view('admin.system_about_us', compact('system_settings', 'page_name'));
        } else {
            return redirect('admin');
        }
    }
    // ------------- MANAGE SYSTEM ABOUT US -------------- //

    // ------------- MANAGE SYSTEM TERMS -------------- //
    public function system_terms(Request $request)
    {
        if ($request->session()->has('admin_id')) {
            $page_name = 'system_terms';
            $system_settings = DB::table('system_settings')->get();
            return view('admin.system_terms', compact('system_settings', 'page_name'));
        } else {
            return redirect('admin');
        }
    }
    // ------------- MANAGE SYSTEM TERMS -------------- //

    // ------------- MANAGE SYSTEM PRIVACY -------------- //
    public function system_privacy(Request $request)
    {
        if ($request->session()->has('admin_id')) {
            $page_name = 'system_privacy';
            $system_settings = DB::table('system_settings')->get();
            return view('admin.system_privacy', compact('system_settings', 'page_name'));
        } else {
            return redirect('admin');
        }
    }
    // ------------- MANAGE SYSTEM PRIVACY -------------- //

    // ------------- MANAGE USERS CUSTOMERS FAQs -------------- //
    public function users_customers_faqs_fetch(Request $request)
    {
        if (session()->has('admin_id')) {
            if (empty($request->get('filter'))) {
                $faqs = DB::table('faqs')->orderBy('faqs_id', 'DESC')->get();
            } else {
                $faqs = DB::table('faqs')->where('status', $request->get('filter'))->orderBy('faqs_id', 'DESC')->get();
            }

            $filter = $request->get('filter');
            return response()->json([
                'faqs' => $faqs,
                'filter' => $filter,
            ]);
            // return view('admin.users_customers_faqs', compact('faqs', 'filter'));
        } else {
            return redirect('admin');
        }
    }
    // ------------- MANAGE USERS CUSTOMERS FAQs -------------- //

    // ------------- MANAGE USERS CUSTOMERS FAQs PAGE -------------- //
    public function users_customers_faqs()
    {
        if (session()->has('admin_id')) {
            return view('admin.users_customers_faqs');
        } else {
            return redirect('admin');
        }
    }
    // ------------- MANAGE USERS CUSTOMERS FAQs PAGE -------------- //

    // ------------- MANAGE USERS CUSTOMERS FAQs SINGLE VIEW -------------- //
    public function users_customers_view_faqs($id)
    {
        if (session()->has('admin_id')) {
            $faq_data         = DB::table('faqs')->where('faqs_id', $id)->first();
            return view('admin.users_customers_view_faq', compact('faq_data'));
        } else {
            return redirect('admin');
        }
    }
    // ------------- MANAGE USERS CUSTOMERS FAQs SINGLE VIEW -------------- //

    // ------------- UPDATE CUSTOMERS FAQ -------------- //
    public function users_customers_update_faq(Request $req)
    {
        $update_array['status'] = $req->status;
        $updated = DB::table('faqs')->where('faqs_id', $req->faqs_id)->update($update_array);
        if ($updated) {
            $response["code"] = 200;
            $response["status"] = "success";
            $response["message"] = "Data Updated successfully";
        } else {
            $response["code"] = 404;
            $response["status"] = "error";
            $response["message"] = "Oops! Something went wrong.";
        }

        return response()
            ->json(array('status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
            ->header('Content-Type', 'application/json');
    }
    // ------------- UPDATE CUSTOMERS FAQ -------------- //

    // ------------- DELETE CUSTOMERS -------------- //
    public function users_customers_delete_faq(Request $req)
    {
        if ($req->faqs_id) {
            $checkdata = DB::table('faqs')->where('faqs_id', $req->faqs_id)->where('status', '!=', 'Deleted')->first();

            if ($checkdata) {
                $del = DB::table('faqs')->where('faqs_id', '=', $req->faqs_id)->update(array('status' => 'Deleted'));
                if ($del) {
                    $response["code"] = 200;
                    $response["status"] = "success";
                    $response["message"] = "Data Deleted successfully";
                } else {
                    $response["code"] = 404;
                    $response["status"] = "error";
                    $response["message"] = "Oops! Something went wrong.";
                }
            } else {
                $response["code"] = 404;
                $response["status"] = "error";
                $response["message"] = "This record is already deleted in status.";
            }
        } else {
            $response["code"] = 404;
            $response["status"] = "error";
            $response["message"] = "No Data Found.";
        }
        return response()
            ->json(array('status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
            ->header('Content-Type', 'application/json');
    }
    // ------------- DELETE CUSTOMERS -------------- //


    // ------------- MANAGE USERS CUSTOMERS ADD FAQ DATA -------------- //
    public function users_customers_add_faq_data(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'question' => 'required',
            'answer' => 'required',
        ]);
        if (!$validator->fails()) {
            $save_data['question']     = $req->question;
            $save_data['answer']       = $req->answer;

            $faq_id = DB::table('faqs')->insertGetId($save_data);
            if ($faq_id) {
                $response["code"] = 200;
                $response["status"] = "success";
                $response["message"] = "FAQ Added";
            } else {
                $response["code"] = 404;
                $response["status"] = "error";
                $response["message"] = "Oops! Something went wrong.";
            }
        } else {
            $response["code"] = 404;
            $response["status"] = "error";
            $response["message"] = 'All fields are required';
        }

        return response()
            ->json(array('status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
            ->header('Content-Type', 'application/json');
    }
    // ------------- MANAGE USERS CUSTOMERS ADD FAQ DATA -------------- //

    // ------------- MANAGE USERS CUSTOMERS EDIT FAQ -------------- //
    public function users_customers_edit_faq($id)
    {
        if (session()->has('admin_id')) {
            $page_name = 'users_customers_edit_faq';
            $faq = DB::table('faqs')->where('faqs_id', $id)->first();
            if ($faq) {
                $response["code"] = 200;
                $response["status"] = "success";
                $response["data"] = $faq;
            } else {
                $response["code"] = 404;
                $response["status"] = "error";
                $response["message"] = "Data not found.";
            }
            // return view('admin.users_customers_edit_faq', compact('faq', 'page_name'));
        } else {
            $response["code"] = 404;
            $response["status"] = "error";
            $response["message"] = "Oops! Something went wrong.";
        }
        return response()
            ->json(array('status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
            ->header('Content-Type', 'application/json');
    }
    // ------------- MANAGE USERS CUSTOMERS EDIT FAQ -------------- //

    // ------------- MANAGE USERS CUSTOMERS EDIT FAQ DATA -------------- //
    public function users_customers_edit_faq_data(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'question' => 'required',
            'answer' => 'required',
        ]);
        if (!$validator->fails()) {
            $update_data['question']     = $req->question;
            $update_data['answer']       = $req->answer;

            $updated = DB::table('faqs')->where('faqs_id', $req->faqs_id)->update($update_data);
            if ($updated) {
                $response["code"] = 200;
                $response["status"] = "success";
                $response["message"] = "Faq updated successfully!";
            } else {
                $response["code"] = 404;
                $response["status"] = "error";
                $response["message"] = "Oops! Something went wrong.";
            }
        } else {
            $response["code"] = 404;
            $response["status"] = "error";
            $response["message"] = 'All fields are required';
        }

        return response()
            ->json(array('status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
            ->header('Content-Type', 'application/json');
    }
    // ------------- MANAGE USERS CUSTOMERS EDIT FAQ DATA -------------- //

    // ------------- MANAGE SWAP OFFERS -------------- //
    public function swap_offers(Request $request)
    {
        if (!$request->session()->has('admin_id')) {
            return redirect('admin');
        }

        $filter = $request->get('filter');

        // Fetch data (with or without filter)
        $fetch_data = SwapOffer::with(['from_currency', 'to_currency'])
            ->when($filter, function ($query) use ($filter) {
                return $query->where('status', $filter);
            })
            ->orderBy('swap_offers_id', 'DESC')
            ->get();

        $adminshare = new \stdClass();
        $adminshare->totalAdminShare = 0;
        $adminshare->system_currency = null;

        $get_data = [];

        foreach ($fetch_data as $data) {

            // Fetch user (offer creator)
            $offerCreatedBy = DB::table('users_customers')
                ->where('users_customers_id', $data->users_customers_id)
                ->first();

            // ❌ Skip if user not found
            // if (!$offerCreatedBy) {
            //     continue;
            // }

            // Assign data
            $data->offerCreatedBy = $offerCreatedBy;

            $data->swap_offers_requests = DB::table('swap_offers_requests')
                ->where('swap_offers_id', $data->swap_offers_id)
                ->get();

            $data->system_currency = DB::table('system_currencies')
                ->where('system_currencies_id', $data->system_currencies_id)
                ->first();

            $data->time_ago = \Carbon\Carbon::parse($data->date_added)->diffForHumans();

            $get_data[] = $data;

            // Admin share calculation
            $adminshare->totalAdminShare += $data->admin_share_amount;

            if (!$adminshare->system_currency) {
                $adminshare->system_currency = $data->system_currency;
            }
        }

        return view('admin.swap_offers', compact('get_data', 'filter', 'adminshare'));
    }
    // ------------- MANAGE SWAP OFFERS -------------- //

    // ------------- PRODUCTS -------------- //
    public function manage_products()
    {
        if (session()->has('admin_id')) {
            $products = DB::table('products')->where('status', '!=', 'Deleted')->where(function($q) { $q->whereNull('insurtech_status')->orWhere('insurtech_status', 'Active'); })->orderBy('products_id', 'ASC')->get();
            return view('admin.products', compact('products'));
        } else {
            return redirect('admin');
        }
    }
    // ------------- PRODUCTS -------------- //

    // ------------- PRODUCTS EDIT -------------- //
    public function products_edit(Request $req)
    {
        if (!session()->has('admin_id')) {
            return redirect('admin');
        }

        $custom_price = $req->custom_price;
        // If empty string or not provided, set to null (use base price)
        $custom_price = ($custom_price !== null && $custom_price !== '') ? (float) $custom_price : null;

        DB::table('products')
            ->where('products_id', $req->products_id)
            ->update(['custom_price' => $custom_price]);

        Session::flash('success', 'Product price updated successfully.');
        return redirect('admin/manage_products');
    }
    // ------------- PRODUCTS EDIT -------------- //

    // ------------- PRODUCTS ADD -------------- //
    public function products_add(Request $req)
    {
        if (!session()->has('admin_id')) {
            return redirect('admin');
        }

        Session::flash('error', 'Product creation is disabled on Swap. Please create products from Insurtech Admin portal.');
        return redirect('admin/manage_products');
    }
    // ------------- PRODUCTS ADD -------------- //

    // ------------- PRODUCTS UPDATE -------------- //
    public function products_update($status, $id)
    {
        if (session()->has('admin_id')) {
            DB::table('products')->where('products_id', $id)->update([
                'status' => $status,
                'date_modified' => date('Y-m-d H:i:s')
            ]);
            Session::flash('success', 'Product status updated successfully.');
            return redirect('admin/manage_products');
        } else {
            return redirect('admin');
        }
    }
    // ------------- PRODUCTS UPDATE -------------- //

    // ------------- MANAGE USERS CUSTOMERS TRANACTIONS -------------- //
    public function users_customers_trxns(Request $request)
    {
        if (!session()->has('admin_id')) {
            return redirect('admin');
        }

        // Get filter
        $filter = $request->get('filter');

        // Fetch data (with or without filter)
        $fetch_data = UsersCustomersTxns::when($filter, function ($query) use ($filter) {
            return $query->where('status', $filter);
        })
            ->orderBy('users_customers_txns_id', 'DESC')
            ->get();

        $get_data = [];
        $adminshare = new \stdClass();
        $adminshare->totalAdminShare = 0;
        $adminshare->system_currency = null;

        foreach ($fetch_data as $data) {

            // Fetch sender & receiver
            $sender = DB::table('users_customers')
                ->where('users_customers_id', $data->from_users_customers_id)
                ->first();

            $receiver = DB::table('users_customers')
                ->where('users_customers_id', $data->to_users_customers_id)
                ->first();

            // ❌ Skip if sender or receiver not found
            if (!$sender || !$receiver) {
                continue;
            }

            // Assign relations
            $data->sender = $sender;
            $data->receiver = $receiver;

            $data->sender_currency = DB::table('system_currencies')
                ->where('system_currencies_id', $data->from_system_currencies_id)
                ->first();

            $data->receiver_currency = DB::table('system_currencies')
                ->where('system_currencies_id', $data->to_system_currencies_id)
                ->first();

            $data->payment_method = DB::table('payment_method')
                ->where('payment_method_id', $data->payment_method_id)
                ->first();

            $data->system_country = DB::table('system_countries')
                ->where('system_countries_id', $data->system_countries_id)
                ->first();

            $data->system_currency = DB::table('system_currencies')
                ->where('system_currencies_id', $data->system_currencies_id)
                ->first();

            // Add to final array
            $get_data[] = $data;

            // Calculate admin share
            $adminshare->totalAdminShare += $data->admin_share_amount;

            // Set currency (last one or first — depends on your use case)
            if (!$adminshare->system_currency) {
                $adminshare->system_currency = $data->system_currency;
            }
        }

        return view('admin.users_customers_transactions', compact('get_data', 'filter', 'adminshare'));
    }
    // ------------- MANAGE USERS CUSTOMERS TRANACTIONS -------------- //

    // ------------- PRODUCTS TRANACTIONS -------------- //
    // public function products_transactions(Request $request){
    //     if (session()->has('admin_id')) {
    //         $products_purchases = DB::table('products_purchases')->orderBy('products_purchases_id', 'DESC')->get();

    //         foreach($products_purchases as $prod_purchase) {
    //             $prod_purchase->initiator = DB::table('users_customers')->where('users_customers_id', $prod_purchase->users_customers_id)->first();

    //             $prod_purchase->product = DB::table('products')->where('products_id', $prod_purchase->products_id)->first();
    //             if ($prod_purchase->product->type == 'A' || $prod_purchase->product->type == 'B') {
    //                 $prod_purchase->beneficiary                 = DB::table('products_purchases_beneficiaries')->where('products_purchases_id', $prod_purchase->products_purchases_id)->first();
    //                 $prod_purchase->beneficiary->occupation     = DB::table('occupations')->where('occupations_id', $prod_purchase->beneficiary->occupations_id)->first()->name;
    //                 $prod_purchase->beneficiary->relationship   = DB::table('relationships')->where('relationships_id', $prod_purchase->beneficiary->relationships_id)->first()->name;
    //             }
    //             if ($prod_purchase->product->type == 'C') {
    //                 $prod_purchase->task_details              = DB::table('products_purchases_tasks')->where('products_purchases_id', $prod_purchase->products_purchases_id)->first();
    //                 $prod_purchase->task_details->task_type   = DB::table('tasks_types')->where('tasks_types_id', $prod_purchase->task_details->tasks_types_id)->first()->name;
    //             }
    //         }
    //         return view('admin.products_transactions', compact('products_purchases'));
    //     } else {
    //         return redirect('admin');
    //     }
    // }
    public function products_transactions(Request $request)
    {
        if (!session()->has('admin_id')) {
            return redirect('admin');
        }

        $products_purchases = DB::table('products_purchases')
            ->orderBy('products_purchases_id', 'DESC')
            ->get();

        foreach ($products_purchases as $prod_purchase) {

            // Initiator
            $prod_purchase->initiator = DB::table('users_customers')
                ->where('users_customers_id', $prod_purchase->users_customers_id)
                ->first();

            // Product
            $prod_purchase->product = DB::table('products')
                ->where('products_id', $prod_purchase->products_id)
                ->first();

            if (!$prod_purchase->product) {
                continue;
            }

            /* ---------- TYPE A / B ---------- */
            if (in_array($prod_purchase->product->type, ['A', 'B'])) {

                $prod_purchase->beneficiary = DB::table('products_purchases_beneficiaries')
                    ->where('products_purchases_id', $prod_purchase->products_purchases_id)
                    ->first();

                if ($prod_purchase->beneficiary) {

                    $prod_purchase->beneficiary->occupation =
                        DB::table('occupations')
                        ->where('occupations_id', $prod_purchase->beneficiary->occupations_id)
                        ->value('name');

                    $prod_purchase->beneficiary->relationship =
                        DB::table('relationships')
                        ->where('relationships_id', $prod_purchase->beneficiary->relationships_id)
                        ->value('name');
                }
            }

            /* ---------- TYPE C ---------- */
            if ($prod_purchase->product->type === 'C') {

                $prod_purchase->task_details = DB::table('products_purchases_tasks')
                    ->where('products_purchases_id', $prod_purchase->products_purchases_id)
                    ->first();

                if ($prod_purchase->task_details) {
                    $prod_purchase->task_details->task_type =
                        DB::table('tasks_types')
                        ->where('tasks_types_id', $prod_purchase->task_details->tasks_types_id)
                        ->value('name');
                }
            }
        }

        return view('admin.products_transactions', compact('products_purchases'));
    }

    // ------------- PRODUCTS TRANACTIONS -------------- //

    // ------------- PRODUCTS CLAIMS -------------- //
    public function products_claims(Request $request)
    {
        if (session()->has('admin_id')) {
            $products_purchases_claims = DB::table('products_purchases_claims')->orderBy('products_purchases_claims_id', 'DESC')->get();

            foreach ($products_purchases_claims as $prod_purchase_claim) {
                $prod_purchase_claim->products_purchases                    = DB::table('products_purchases')->where('products_purchases_id', $prod_purchase_claim->products_purchases_id)->first();
                $prod_purchase_claim->products_purchases->users_customers   = DB::table('users_customers')->where('users_customers_id', $prod_purchase_claim->products_purchases->users_customers_id)->first();
                $prod_purchase_claim->products_purchases->products          = DB::table('products')->where('products_id', $prod_purchase_claim->products_purchases->products_id)->first();
            }
            return view('admin.products_claims', compact('products_purchases_claims'));
        } else {
            return redirect('admin');
        }
    }
    // ------------- PRODUCTS CLAIMS -------------- //

    // ------------- TASKS TYPES -------------- //
    public function tasks_types(Request $request)
    {
        if (session()->has('admin_id')) {
            $page_name     = 'tasks_types';
            $tasks_types   = DB::table('tasks_types')->where('status', '!=', 'Deleted')->orderBy('tasks_types_id', 'DESC')->get();
            return view('admin.tasks_types', compact('tasks_types', 'page_name'));
        } else {
            return redirect('admin');
        }
    }
    // ------------- TASKS TYPES -------------- //

    // ------------- TASKS TYPES ADD -------------- //
    public function tasks_types_add(Request $req)
    {
        if (session()->has('admin_id')) {
            $task_exist = DB::table('tasks_types')->where([['name', $req->name], ['status', '!=', 'Deleted']])->count();

            if ($task_exist == 0) {
                $data = [
                    'name'       => $req->name,
                    'date_added' => date('Y-m-d H:i:s')
                ];
                $tasks_types_id = DB::table('tasks_types')->insertGetId($data);

                if ($tasks_types_id > 0) {
                    session()->flash('success', 'Task type added successfully!');
                    return redirect('admin/tasks_types');
                } else {
                    session()->flash('error', 'Oops! Something went wrong. Please try again.');
                    return redirect()->back();
                }
            } else {
                session()->flash('error', 'Task type already exist.');
                return redirect()->back();
            }
        } else {
            return redirect('admin');
        }
    }
    // ------------- TASKS TYPES ADD -------------- //

    // ------------- TASKS TYPES EDIT -------------- //
    public function tasks_types_edit(Request $req)
    {
        if (session()->has('admin_id')) {
            $data = [
                'name'          => $req->edit_name,
                'date_modified' => date('Y-m-d H:i:s')
            ];
            $updated = DB::table('tasks_types')->where('tasks_types_id', $req->tasks_types_id)->update($data);

            if ($updated > 0) {
                session()->flash('success', 'Task type updated successfully!');
                return redirect('admin/tasks_types');
            } else {
                session()->flash('error', 'Oops! Somrthing went wrong. Please try again.');
                return redirect()->back();
            }
        } else {
            return redirect('admin');
        }
    }
    // ------------- TASKS TYPES EDIT -------------- //

    // ------------- TASKS TYPES UPDATE -------------- //
    public function tasks_types_update($status, $id)
    {
        if (session()->has('admin_id')) {
            $data = [
                'status'          => $status,
                'date_modified'   => date('Y-m-d H:i:s')
            ];
            $status_updated = DB::table('tasks_types')->where('tasks_types_id', $id)->update($data);

            if ($status_updated) {
                if ($status == 'Active') {
                    $message = 'activated';
                } elseif ($status == 'Inactive') {
                    $message = 'deactivated';
                } else {
                    $message = 'deleted';
                }
                Session::flash('success', 'Taks type ' . $message . ' successfully.');
            } else {
                Session::flash('error', ' Oops! something went wrong');
            }
            return redirect('admin/tasks_types');
        } else {
            return redirect('admin');
        }
    }
    // ------------- TASKS TYPES UPDATE -------------- //

    // ------------- OCCUPATIONS -------------- //
    public function occupations(Request $request)
    {
        if (session()->has('admin_id')) {
            $page_name     = 'occupations';
            $occupations   = DB::table('occupations')->where('status', '!=', 'Deleted')->orderBy('occupations_id', 'DESC')->get();
            return view('admin.occupations', compact('occupations', 'page_name'));
        } else {
            return redirect('admin');
        }
    }
    // ------------- OCCUPATIONS -------------- //

    // ------------- OCCUPATIONS ADD -------------- //
    public function occupations_add(Request $req)
    {
        if (session()->has('admin_id')) {
            $occupation_exist = DB::table('occupations')->where([['name', $req->name], ['status', '!=', 'Deleted']])->count();

            if ($occupation_exist == 0) {
                $data = [
                    'name'       => $req->name,
                    'date_added' => date('Y-m-d H:i:s')
                ];
                $occupations_id = DB::table('occupations')->insertGetId($data);

                if ($occupations_id > 0) {
                    session()->flash('success', 'Occupation added successfully!');
                    return redirect('admin/occupations');
                } else {
                    session()->flash('error', 'Oops! Something went wrong. Please try again.');
                    return redirect()->back();
                }
            } else {
                session()->flash('error', 'Occupation already exist.');
                return redirect()->back();
            }
        } else {
            return redirect('admin');
        }
    }
    // ------------- OCCUPATIONS ADD -------------- //

    // ------------- OCCUPATIONS EDIT -------------- //
    public function occupations_edit(Request $req)
    {
        if (session()->has('admin_id')) {
            $data = [
                'name'          => $req->edit_name,
                'date_modified' => date('Y-m-d H:i:s')
            ];
            $updated = DB::table('occupations')->where('occupations_id', $req->occupations_id)->update($data);

            if ($updated > 0) {
                session()->flash('success', 'Occupations updated successfully!');
                return redirect('admin/occupations');
            } else {
                session()->flash('error', 'Oops! Somrthing went wrong. Please try again.');
                return redirect()->back();
            }
        } else {
            return redirect('admin');
        }
    }
    // ------------- OCCUPATIONS EDIT -------------- //

    // ------------- OCCUPATIONS UPDATE -------------- //
    public function occupations_update($status, $id)
    {
        if (session()->has('admin_id')) {
            $data = [
                'status'          => $status,
                'date_modified'   => date('Y-m-d H:i:s')
            ];
            $status_updated = DB::table('occupations')->where('occupations_id', $id)->update($data);

            if ($status_updated) {
                if ($status == 'Active') {
                    $message = 'activated';
                } elseif ($status == 'Inactive') {
                    $message = 'deactivated';
                } else {
                    $message = 'deleted';
                }
                Session::flash('success', 'Occupation ' . $message . ' successfully.');
            } else {
                Session::flash('error', ' Oops! something went wrong');
            }
            return redirect('admin/occupations');
        } else {
            return redirect('admin');
        }
    }
    // ------------- OCCUPATIONS UPDATE -------------- //

    // ------------- RELATIONSHIPS -------------- //
    public function relationships(Request $request)
    {
        if (session()->has('admin_id')) {
            $page_name     = 'relationships';
            $relationships   = DB::table('relationships')->where('status', '!=', 'Deleted')->orderBy('relationships_id', 'DESC')->get();
            return view('admin.relationships', compact('relationships', 'page_name'));
        } else {
            return redirect('admin');
        }
    }
    // ------------- RELATIONSHIPS -------------- //

    // ------------- RELATIONSHIPS ADD -------------- //
    public function relationships_add(Request $req)
    {
        if (session()->has('admin_id')) {
            $relationship_exist = DB::table('relationships')->where([['name', $req->name], ['status', '!=', 'Deleted']])->count();

            if ($relationship_exist == 0) {
                $data = [
                    'name'       => $req->name,
                    'date_added' => date('Y-m-d H:i:s')
                ];
                $relationships_id = DB::table('relationships')->insertGetId($data);

                if ($relationships_id > 0) {
                    session()->flash('success', 'Relationship added successfully!');
                    return redirect('admin/relationships');
                } else {
                    session()->flash('error', 'Oops! Something went wrong. Please try again.');
                    return redirect()->back();
                }
            } else {
                session()->flash('error', 'Occupation already exist.');
                return redirect()->back();
            }
        } else {
            return redirect('admin');
        }
    }
    // ------------- RELATIONSHIPS ADD -------------- //

    // ------------- RELATIONSHIPS EDIT -------------- //
    public function relationships_edit(Request $req)
    {
        if (session()->has('admin_id')) {
            $data = [
                'name'          => $req->edit_name,
                'date_modified' => date('Y-m-d H:i:s')
            ];
            $updated = DB::table('relationships')->where('relationships_id', $req->relationships_id)->update($data);

            if ($updated > 0) {
                session()->flash('success', 'Relationship updated successfully!');
                return redirect('admin/relationships');
            } else {
                session()->flash('error', 'Oops! Somrthing went wrong. Please try again.');
                return redirect()->back();
            }
        } else {
            return redirect('admin');
        }
    }
    // ------------- RELATIONSHIPS EDIT -------------- //

    // ------------- RELATIONSHIPS UPDATE -------------- //
    public function relationships_update($status, $id)
    {
        if (session()->has('admin_id')) {
            $data = [
                'status'          => $status,
                'date_modified'   => date('Y-m-d H:i:s')
            ];
            $status_updated = DB::table('relationships')->where('relationships_id', $id)->update($data);

            if ($status_updated) {
                if ($status == 'Active') {
                    $message = 'activated';
                } elseif ($status == 'Inactive') {
                    $message = 'deactivated';
                } else {
                    $message = 'deleted';
                }
                Session::flash('success', 'Relationship ' . $message . ' successfully.');
            } else {
                Session::flash('error', ' Oops! something went wrong');
            }
            return redirect('admin/relationships');
        } else {
            return redirect('admin');
        }
    }
    // ------------- RELATIONSHIPS UPDATE -------------- //

    // ------------- MANAGE PAYMENT METHODS -------------- //
    public function payment_methods(Request $request)
    {
        if (session()->has('admin_id')) {
            if (empty($request->get('filter'))) {
                $get_data   =  DB::table('payment_method')->orderBy('payment_method_id', 'DESC')->get();
            } else {
                $get_data   = DB::table('payment_method')->where('status', $request->get('filter'))->orderBy('payment_method_id', 'DESC')->get();
            }
            $filter = $request->get('filter');

            return view('admin.payment_methods', compact('get_data', 'filter'));
        } else {
            return redirect('admin');
        }
    }
    // ------------- MANAGE PAYMENT METHODS -------------- //

    // ------------- UPDATE PAYMENT METHODS -------------- //
    public function payment_methods_update(Request $req, $id, $status)
    {
        $update_array['status'] = $status;
        $updated = DB::table('payment_method')->where('payment_method_id', $id)->update($update_array);
        if ($updated) {
            Session::flash('success', ' Data Updated successfully');
            return back()->with('success', 'Data Updated successfully');
        } else {
            Session::flash('error', ' Oops! something went wrong');
            return back()->with('errors', 'Oops! something went wrong');
        }
    }
    // ------------- UPDATE PAYMENT METHODS -------------- //

    // ------------- DELETE PAYMENT METHODS -------------- //
    public function payment_methods_delete(Request $req, $id)
    {
        if (session()->has('admin_id')) {
            if (!empty($req->id)) {
                $checkdata = DB::table('payment_method')->where('payment_method_id', $id)->where('status', '!=', 'Deleted')->first();

                if ($checkdata) {
                    $del = DB::table('payment_method')->where('payment_method_id', '=', $id)->update(array('status' => 'Deleted'));
                    if ($del) {
                        Session::flash('success', ' Data Deleted successfully');
                        return redirect('admin/payment_methods');
                    } else {
                        Session::flash('error', ' Oops! something went wrong');
                        return redirect('admin/payment_methods');
                    }
                } else {
                    Session::flash('error', ' This record is already deleted in status');
                    return redirect('admin/payment_methods');
                }
            } else {
                Session::flash('error', ' No Data Found');
                return redirect('admin/payment_methods');
            }
        } else {
            return redirect('admin');
        }
    }
    // ------------- DELETE PAYMENT METHODS -------------- //

    // ------------- MANAGE PAYMENT METHODS ADD -------------- //
    public function payment_methods_add(Request $request)
    {
        if ($request->session()->has('admin_id')) {
            $page_name = 'payment_methods_add';
            return view('admin.payment_methods_add', compact('page_name'));
        } else {
            return redirect('admin');
        }
    }
    // ------------- MANAGE PAYMENT METHODS ADD -------------- //

    // ------------- MANAGE PAYMENT METHODS ADD DATA -------------- //
    public function payment_methods_add_data(Request $req)
    {
        $save_data['name']     = $req->name;
        $save_data['type']       = $req->type;

        $faq_id = DB::table('payment_method')->insertGetId($save_data);

        if ($faq_id) {
            session()->flash('success', 'Payment Method added successfully!');
            return redirect(route('payment_methods'));
        } else {
            session()->flash('error', 'Oops! Somrthing went wrong. Please try again.');
            return redirect()->back();
        }
    }
    // ------------- MANAGE PAYMENT METHODS ADD DATA -------------- //

    // ------------- MANAGE PAYMENT METHODS EDIT -------------- //
    public function payment_methods_edit($id)
    {
        if (session()->has('admin_id')) {
            $page_name = 'payment_methods_edit';
            $payment_method = DB::table('payment_method')->where('payment_method_id', $id)->first();
            return view('admin.payment_methods_edit', compact('payment_method', 'page_name'));
        } else {
            return redirect('admin');
        }
    }
    // ------------- MANAGE PAYMENT METHODS EDIT -------------- //

    // ------------- MANAGE PAYMENT METHODS EDIT DATA -------------- //
    public function payment_methods_edit_data(Request $req, $id)
    {
        $update_data['name']     = $req->name;
        $update_data['type']       = $req->type;

        $updated = DB::table('payment_method')->where('payment_method_id', $id)->update($update_data);

        if ($updated) {
            session()->flash('success', 'User updated successfully!');
            return redirect(route('payment_methods'));
        } else {
            session()->flash('error', 'Oops! Somrthing went wrong. Please try again.');
            return redirect()->back();
        }
    }
    // ------------- MANAGE PAYMENT METHODS EDIT DATA -------------- //


    // ------------- MANAGE CONNECT CATEGORY -------------- //
    public function connect_categories_fetch(Request $request)
    {
        if (session()->has('admin_id')) {
            if (empty($request->get('filter'))) {
                $connectCategories = DB::table('connect_categories')->orderBy('connect_categories_id', 'DESC')->get();
            } else {
                $connectCategories = DB::table('connect_categories')->where('status', $request->get('filter'))->orderBy('connect_categories_id', 'DESC')->get();
            }

            $filter = $request->get('filter');
            return response()->json([
                'connectCategories' => $connectCategories,
                'filter' => $filter,
            ]);
        } else {
            return redirect('admin');
        }
    }
    // ------------- MANAGE CONNECT CATEGORY -------------- //

    // ------------- MANAGE CONNECT CATEGORY PAGE -------------- //
    public function connect_categories()
    {
        if (session()->has('admin_id')) {
            return view('admin.connect_categories');
        } else {
            return redirect('admin');
        }
    }
    // ------------- MANAGE CONNECT CATEGORY PAGE -------------- //

    // ------------- UPDATE CONNECT CATEGORY -------------- //
    public function connect_category_update(Request $req)
    {
        $update_array['status'] = $req->status;
        $updated = DB::table('connect_categories')->where('connect_categories_id', $req->connect_categories_id)->update($update_array);
        if ($updated) {
            $response["code"] = 200;
            $response["status"] = "success";
            $response["message"] = "Data Updated successfully";
        } else {
            $response["code"] = 404;
            $response["status"] = "error";
            $response["message"] = "Oops! Something went wrong.";
        }

        return response()
            ->json(array('status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
            ->header('Content-Type', 'application/json');
    }
    // ------------- UPDATE CONNECT CATEGORY -------------- //

    // ------------- DELETE CONNECT CATEGORY -------------- //
    public function connect_category_delete(Request $req)
    {
        if ($req->connect_categories_id) {
            $checkdata = DB::table('connect_categories')->where('connect_categories_id', $req->connect_categories_id)->where('status', '!=', 'Deleted')->first();

            if ($checkdata) {
                $del = DB::table('connect_categories')->where('connect_categories_id', '=', $req->connect_categories_id)->update(array('status' => 'Deleted'));
                if ($del) {
                    $response["code"] = 200;
                    $response["status"] = "success";
                    $response["message"] = "Data Deleted successfully";
                } else {
                    $response["code"] = 404;
                    $response["status"] = "error";
                    $response["message"] = "Oops! Something went wrong.";
                }
            } else {
                $response["code"] = 404;
                $response["status"] = "error";
                $response["message"] = "This record is already deleted in status.";
            }
        } else {
            $response["code"] = 404;
            $response["status"] = "error";
            $response["message"] = "No Data Found.";
        }
        return response()
            ->json(array('status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
            ->header('Content-Type', 'application/json');
    }
    // ------------- DELETE CONNECT CATEGORY -------------- //


    // ------------- MANAGE ADD CONNECT CATEGORY -------------- //
    public function connect_category_add_data(Request $req)
    {
        if (isset($req->name) && isset($req->icon_image)) {
            $save_data['name']     = $req->name;
            if (isset($req->icon_image)) {
                $icon_image = $req->icon_image;
                $prefix = time();
                $img_name = $prefix . '.png';
                $image_path = public_path('uploads/connect_category_icon/') . $img_name;

                file_put_contents($image_path, base64_decode($icon_image));
                $save_data['icon'] = 'uploads/connect_category_icon/' . $img_name;
            }

            $connect_categories_id = DB::table('connect_categories')->insertGetId($save_data);
            if ($connect_categories_id) {
                $response["code"] = 200;
                $response["status"] = "success";
                $response["message"] = "Connect Categoies Added";
            } else {
                $response["code"] = 404;
                $response["status"] = "error";
                $response["message"] = "Oops! Something went wrong.";
            }
        } else {
            $response["code"] = 404;
            $response["status"] = "error";
            $response["message"] = 'All fields are required';
        }

        return response()
            ->json(array('status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
            ->header('Content-Type', 'application/json');
    }
    // ------------- MANAGE ADD CONNECT CATEGORY -------------- //

    // ------------- MANAGE CONNECT CATEGORY -------------- //
    public function connect_category_edit($id)
    {
        if (session()->has('admin_id')) {
            $page_name = 'connect_category_edit';
            $connect_category = DB::table('connect_categories')->where('connect_categories_id', $id)->first();
            if ($connect_category) {
                $response["code"] = 200;
                $response["status"] = "success";
                $response["data"] = $connect_category;
            } else {
                $response["code"] = 404;
                $response["status"] = "error";
                $response["message"] = "Data not found.";
            }
        } else {
            $response["code"] = 404;
            $response["status"] = "error";
            $response["message"] = "Oops! Something went wrong.";
        }
        return response()
            ->json(array('status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
            ->header('Content-Type', 'application/json');
    }
    // ------------- MANAGE CONNECT CATEGORY -------------- //

    // ------------- MANAGE CONNECT CATEGORY DATA -------------- //
    public function connect_category_edit_data(Request $req)
    {
        if (isset($req->name) && isset($req->icon_image)) {
            $update_data['name']     = $req->name;
            if (isset($req->icon_image)) {
                $icon_image = $req->icon_image;
                $prefix = time();
                $img_name = $prefix . '.png';
                $image_path = public_path('uploads/connect_category_icon/') . $img_name;

                file_put_contents($image_path, base64_decode($icon_image));
                $update_data['icon'] = 'uploads/connect_category_icon/' . $img_name;
            }

            $updated = DB::table('connect_categories')->where('connect_categories_id', $req->connect_categories_id)->update($update_data);
            if ($updated) {
                $response["code"] = 200;
                $response["status"] = "success";
                $response["message"] = "Connect Category updated successfully!";
            } else {
                $response["code"] = 404;
                $response["status"] = "error";
                $response["message"] = "Oops! Something went wrong.";
            }
        } else {
            $response["code"] = 404;
            $response["status"] = "error";
            $response["message"] = 'All fields are required';
        }

        return response()
            ->json(array('status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
            ->header('Content-Type', 'application/json');
    }
    // ------------- MANAGE CONNECT CATEGORY DATA -------------- //


    // ------------- MANAGE CONNECT ARTICLE -------------- //
    public function connect_articles_fetch(Request $request)
    {
        if (session()->has('admin_id')) {
            if (empty($request->get('filter'))) {
                $connectArticles = DB::table('connect_articles')->orderBy('connect_articles_id', 'DESC')->get();
            } else {
                $connectArticles = DB::table('connect_articles')->where('status', $request->get('filter'))->orderBy('connect_articles_id', 'DESC')->get();
            }

            $filter = $request->get('filter');
            return response()->json([
                'connectArticles' => $connectArticles,
                'filter' => $filter,
            ]);
        } else {
            return redirect('admin');
        }
    }
    // ------------- MANAGE CONNECT ARTICLE -------------- //

    // ------------- MANAGE CONNECT ARTICLE PAGE -------------- //
    public function connect_articles()
    {
        if (session()->has('admin_id')) {
            return view('admin.connect_articles');
        } else {
            return redirect('admin');
        }
    }
    // ------------- MANAGE CONNECT ARTICLE PAGE -------------- //

    // ------------- UPDATE CONNECT ARTICLE -------------- //
    public function connect_article_update(Request $req)
    {
        $update_array['status'] = $req->status;
        $updated = DB::table('connect_articles')->where('connect_articles_id', $req->connect_articles_id)->update($update_array);
        if ($updated) {
            $response["code"] = 200;
            $response["status"] = "success";
            $response["message"] = "Data Updated successfully";
        } else {
            $response["code"] = 404;
            $response["status"] = "error";
            $response["message"] = "Oops! Something went wrong.";
        }

        return response()
            ->json(array('status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
            ->header('Content-Type', 'application/json');
    }
    // ------------- UPDATE CONNECT ARTICLE -------------- //

    // ------------- DELETE CONNECT ARTICLE -------------- //
    public function connect_article_delete(Request $req)
    {
        if ($req->connect_articles_id) {
            $checkdata = DB::table('connect_articles')->where('connect_articles_id', $req->connect_articles_id)->where('status', '!=', 'Deleted')->first();

            if ($checkdata) {
                $del = DB::table('connect_articles')->where('connect_articles_id', '=', $req->connect_articles_id)->update(array('status' => 'Deleted'));
                if ($del) {
                    $response["code"] = 200;
                    $response["status"] = "success";
                    $response["message"] = "Data Deleted successfully";
                } else {
                    $response["code"] = 404;
                    $response["status"] = "error";
                    $response["message"] = "Oops! Something went wrong.";
                }
            } else {
                $response["code"] = 404;
                $response["status"] = "error";
                $response["message"] = "This record is already deleted in status.";
            }
        } else {
            $response["code"] = 404;
            $response["status"] = "error";
            $response["message"] = "No Data Found.";
        }
        return response()
            ->json(array('status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
            ->header('Content-Type', 'application/json');
    }
    // ------------- DELETE CONNECT ARTICLE -------------- //


    // ------------- MANAGE ADD CONNECT ARTICLE -------------- //
    public function connect_article_add_data(Request $req)
    {
        if (isset($req->title) && isset($req->description) && isset($req->image)  && isset($req->connect_categories_id)) {
            $save_data['title']     = $req->title;
            $save_data['description']     = $req->description;
            $save_data['connect_categories_id']     = $req->connect_categories_id;
            if (isset($req->image)) {
                $image = $req->image;
                $prefix = time();
                $img_name = $prefix . '.jpeg';
                $image_path = public_path('uploads/connect_article/') . $img_name;

                file_put_contents($image_path, base64_decode($image));
                $save_data['image'] = 'uploads/connect_article/' . $img_name;
            }

            $connect_articles_id = DB::table('connect_articles')->insertGetId($save_data);
            if ($connect_articles_id) {
                $response["code"] = 200;
                $response["status"] = "success";
                $response["message"] = "Connect Article Added";
            } else {
                $response["code"] = 404;
                $response["status"] = "error";
                $response["message"] = "Oops! Something went wrong.";
            }
        } else {
            $response["code"] = 404;
            $response["status"] = "error";
            $response["message"] = 'All fields are required';
        }

        return response()
            ->json(array('status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
            ->header('Content-Type', 'application/json');
    }
    // ------------- MANAGE ADD CONNECT ARTICLE -------------- //

    // ------------- MANAGE CONNECT ARTICLE -------------- //
    public function connect_article_edit($id)
    {
        if (session()->has('admin_id')) {
            $page_name = 'connect_article_edit';
            $data = DB::table('connect_articles')->where('connect_articles_id', $id)->first();
            if ($data) {
                $response["code"] = 200;
                $response["status"] = "success";
                $response["data"] = $data;
            } else {
                $response["code"] = 404;
                $response["status"] = "error";
                $response["message"] = "Data not found.";
            }
        } else {
            $response["code"] = 404;
            $response["status"] = "error";
            $response["message"] = "Oops! Something went wrong.";
        }
        return response()
            ->json(array('status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
            ->header('Content-Type', 'application/json');
    }
    // ------------- MANAGE CONNECT ARTICLE -------------- //

    // ------------- MANAGE CONNECT ARTICLE DATA -------------- //
    public function connect_article_edit_data(Request $req)
    {
        if (isset($req->title) && isset($req->description) && isset($req->connect_categories_id)) {
            $update_data['title']     = $req->title;
            $update_data['description']     = $req->description;
            $update_data['connect_categories_id']     = $req->connect_categories_id;
            if (isset($req->image)) {
                $image = $req->image;
                $prefix = time();
                $img_name = $prefix . '.jpeg';
                $image_path = public_path('uploads/connect_article/') . $img_name;

                file_put_contents($image_path, base64_decode($image));
                $update_data['image'] = 'uploads/connect_article/' . $img_name;
            }

            $updated = DB::table('connect_articles')->where('connect_articles_id', $req->connect_articles_id)->update($update_data);
            if ($updated) {
                $response["code"] = 200;
                $response["status"] = "success";
                $response["message"] = "Connect ARTICLE updated successfully!";
            } else {
                $response["code"] = 404;
                $response["status"] = "error";
                $response["message"] = "Oops! Something went wrong.";
            }
        } else {
            $response["code"] = 404;
            $response["status"] = "error";
            $response["message"] = 'All fields are required';
        }

        return response()
            ->json(array('status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
            ->header('Content-Type', 'application/json');
    }
    // ------------- MANAGE CONNECT ARTICLE DATA -------------- //

    // ------------- MANAGE RATE API -------------- //
    public function rate_api_fetch(Request $request)
    {
        if (session()->has('admin_id')) {
            if (empty($request->get('filter'))) {
                $rate_api = DB::table('rate_api')->orderBy('rate_api_id', 'DESC')->get();
            } else {
                $rate_api = DB::table('rate_api')->where('status', $request->get('filter'))->orderBy('rate_api_id', 'DESC')->get();
            }

            $filter = $request->get('filter');
            return response()->json([
                'rate_api' => $rate_api,
                'filter' => $filter,
            ]);
        } else {
            return redirect('admin');
        }
    }
    // ------------- MANAGE RATE API -------------- //

    // ------------- MANAGE RATE API PAGE -------------- //
    public function rate_api()
    {
        if (session()->has('admin_id')) {
            return view('admin.rate_api');
        } else {
            return redirect('admin');
        }
    }
    // ------------- MANAGE RATE API PAGE -------------- //

    // ------------- UPDATE RATE API -------------- //
    public function rate_api_update(Request $req)
    {
        $update_array['status'] = $req->status;
        $updated = DB::table('rate_api')->where('rate_api_id', $req->rate_api_id)->update($update_array);
        if ($updated) {
            $response["code"] = 200;
            $response["status"] = "success";
            $response["message"] = "Data Updated successfully";
        } else {
            $response["code"] = 404;
            $response["status"] = "error";
            $response["message"] = "Oops! Something went wrong.";
        }

        return response()
            ->json(array('status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
            ->header('Content-Type', 'application/json');
    }
    // ------------- UPDATE RATE API -------------- //

    // ------------- DELETE RATE API -------------- //
    public function rate_api_delete(Request $req)
    {
        if ($req->rate_api_id) {
            $checkdata = DB::table('rate_api')->where('rate_api_id', $req->rate_api_id)->where('status', '!=', 'Deleted')->first();

            if ($checkdata) {
                $del = DB::table('rate_api')->where('rate_api_id', '=', $req->rate_api_id)->update(array('status' => 'Deleted'));
                if ($del) {
                    $response["code"] = 200;
                    $response["status"] = "success";
                    $response["message"] = "Data Deleted successfully";
                } else {
                    $response["code"] = 404;
                    $response["status"] = "error";
                    $response["message"] = "Oops! Something went wrong.";
                }
            } else {
                $response["code"] = 404;
                $response["status"] = "error";
                $response["message"] = "This record is already deleted in status.";
            }
        } else {
            $response["code"] = 404;
            $response["status"] = "error";
            $response["message"] = "No Data Found.";
        }
        return response()
            ->json(array('status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
            ->header('Content-Type', 'application/json');
    }
    // ------------- DELETE RATE API -------------- //


    // ------------- MANAGE ADD RATE API -------------- //
    public function rate_api_add_data(Request $req)
    {
        if (isset($req->name) && isset($req->url)) {
            $save_data['name']     = $req->name;
            $save_data['url']     = $req->url;

            $rate_api_id = DB::table('rate_api')->insertGetId($save_data);
            if ($rate_api_id) {
                $response["code"] = 200;
                $response["status"] = "success";
                $response["message"] = "Rate Api Added";
            } else {
                $response["code"] = 404;
                $response["status"] = "error";
                $response["message"] = "Oops! Something went wrong.";
            }
        } else {
            $response["code"] = 404;
            $response["status"] = "error";
            $response["message"] = 'All fields are required';
        }

        return response()
            ->json(array('status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
            ->header('Content-Type', 'application/json');
    }
    // ------------- MANAGE ADD RATE API -------------- //

    // ------------- MANAGE RATE API -------------- //
    public function rate_api_edit($id)
    {
        if (session()->has('admin_id')) {
            $page_name = 'rate_api_edit';
            $rate_api = DB::table('rate_api')->where('rate_api_id', $id)->first();
            if ($rate_api) {
                $response["code"] = 200;
                $response["status"] = "success";
                $response["data"] = $rate_api;
            } else {
                $response["code"] = 404;
                $response["status"] = "error";
                $response["message"] = "Data not found.";
            }
        } else {
            $response["code"] = 404;
            $response["status"] = "error";
            $response["message"] = "Oops! Something went wrong.";
        }
        return response()
            ->json(array('status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
            ->header('Content-Type', 'application/json');
    }
    // ------------- MANAGE RATE API -------------- //

    // ------------- MANAGE RATE API DATA -------------- //
    public function rate_api_edit_data(Request $req)
    {
        if (isset($req->name) && isset($req->url)) {
            $update_data['name']     = $req->name;
            $update_data['url']     = $req->url;

            $updated = DB::table('rate_api')->where('rate_api_id', $req->rate_api_id)->update($update_data);
            if ($updated) {
                $response["code"] = 200;
                $response["status"] = "success";
                $response["message"] = "RATE API updated successfully!";
            } else {
                $response["code"] = 404;
                $response["status"] = "error";
                $response["message"] = "Oops! Something went wrong.";
            }
        } else {
            $response["code"] = 404;
            $response["status"] = "error";
            $response["message"] = 'All fields are required';
        }

        return response()
            ->json(array('status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
            ->header('Content-Type', 'application/json');
    }
    // ------------- MANAGE RATE API DATA -------------- //

    // ------------- MANAGE RATE API PAGE -------------- //
    public function currency_rate()
    {
        if (session()->has('admin_id')) {
            $system_setting_currency = DB::table('system_settings')->select('description')->where('type', 'system_currencies_id')->first();
            $system_currency = DB::table('system_currencies')->where('system_currencies_id', $system_setting_currency->description)->first();
            $currencies = DB::table('system_currencies')->get();
            $rate_api = DB::table('rate_api')->get();
            $system_currency_code = $system_currency->code;

            $final_data = [];

            foreach ($rate_api as $api) {
                if ($api->name == "Exchangerate") {
                    $url = $api->url;
                    $req_url = "$url?$system_currency_code";
                    $response_json = file_get_contents($req_url);

                    if (false !== $response_json) {
                        try {
                            $response = json_decode($response_json);

                            if ($response->success === true) {
                                $data = $response->rates;

                                $final_data = [];
                                foreach ($currencies as $currency) {
                                    $currencyCode = $currency->code;
                                    if (isset($data->$currencyCode)) {
                                        $final_data[$currencyCode] = $data->$currencyCode;
                                    }
                                }
                                $final_data = collect($final_data)->map(function ($value, $key) use ($currencies) {
                                    $currency = $currencies->firstWhere('code', $key);
                                    $symbol = $currency ? $currency->symbol : '';

                                    return [
                                        'code' => $key,
                                        'value' => number_format($value, 2),
                                        'symbol' => $symbol,
                                    ];
                                })->values()->all();
                            }
                        } catch (Exception $e) {
                            // Handle JSON parse error...
                        }
                    }
                }
            }

            return view('admin.currency_rate', compact('final_data'));
        } else {
            return redirect('admin');
        }
    }
    // ------------- MANAGE RATE API PAGE -------------- //

    // ------------- MANAGE ADNIN RATE API -------------- //
    public function admin_rate_fetch(Request $request)
    {
        if (session()->has('admin_id')) {
            if (empty($request->get('filter'))) {
                $admin_rate = DB::table('system_currencies')->orderBy('system_currencies_id', 'DESC')->get();
            } else {
                $admin_rate = DB::table('system_currencies')->where('status', $request->get('filter'))->orderBy('system_currencies_id', 'DESC')->get();
            }

            $filter = $request->get('filter');
            return response()->json([
                'admin_rate' => $admin_rate,
                'filter' => $filter,
            ]);
        } else {
            return redirect('admin');
        }
    }
    // ------------- MANAGE ADNIN RATE API -------------- //

    // ------------- MANAGE ADNIN RATE API PAGE -------------- //
    public function admin_rate()
    {
        if (session()->has('admin_id')) {
            return view('admin.admin_rate');
        } else {
            return redirect('admin');
        }
    }
    // ------------- MANAGE ADNIN RATE API PAGE -------------- //

    // ------------- MANAGE ADNIN RATE API -------------- //
    public function admin_rate_edit($id)
    {
        if (session()->has('admin_id')) {
            $page_name = 'admin_rate_edit';
            $admin_rate = DB::table('system_currencies')->where('system_currencies_id', $id)->first();
            if ($admin_rate) {
                $response["code"] = 200;
                $response["status"] = "success";
                $response["data"] = $admin_rate;
            } else {
                $response["code"] = 404;
                $response["status"] = "error";
                $response["message"] = "Data not found.";
            }
        } else {
            $response["code"] = 404;
            $response["status"] = "error";
            $response["message"] = "Oops! Something went wrong.";
        }
        return response()
            ->json(array('status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
            ->header('Content-Type', 'application/json');
    }
    // ------------- MANAGE ADNIN RATE API -------------- //

    // ------------- MANAGE ADNIN RATE API DATA -------------- //
    public function admin_rate_edit_data(Request $req)
    {
        if (isset($req->admin_rate)) {
            $update_data['admin_rate']     = $req->admin_rate;

            $updated = DB::table('system_currencies')->where('system_currencies_id', $req->system_currencies_id)->update($update_data);
            if ($updated) {
                $response["code"] = 200;
                $response["status"] = "success";
                $response["message"] = "Data updated successfully!";
            } else {
                $response["code"] = 404;
                $response["status"] = "error";
                $response["message"] = "Oops! Something went wrong.";
            }
        } else {
            $response["code"] = 404;
            $response["status"] = "error";
            $response["message"] = 'All fields are required';
        }

        return response()
            ->json(array('status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
            ->header('Content-Type', 'application/json');
    }
    // ------------- MANAGE ADNIN RATE API DATA -------------- //

    // ------------- MANAGE ADNIN RATE API DATA -------------- //
    public function refresh_rate_data(Request $req)
    {
        $response = ["code" => 200, "status" => "success", "message" => "Currency rates updated successfully"];

        $system_setting_currency = DB::table('system_settings')->where('type', 'system_currencies_id')->first();
        $system_currency_id = DB::table('system_currencies')->where('system_currencies_id', $system_setting_currency->description)->first();
        $system_currencies = DB::table('system_currencies')->get();

        foreach ($system_currencies as $key => $system_currency) {
            $req_url = "https://api.exchangerate.host/convert?from=$system_currency_id->code&to=$system_currency->code&amount=1";
            $response_json = file_get_contents($req_url);

            if (false !== $response_json) {
                try {
                    $url_response = json_decode($response_json);
                    if ($url_response->success === true) {
                        $data = $url_response->result;
                        if ($data) {
                            DB::table('system_currencies')->where('system_currencies_id', $system_currency->system_currencies_id)->update(['admin_rate' => $data]);
                        }
                    }
                } catch (Exception $e) {
                    $response = ["code" => 404, "status" => "error", "message" => $e->getMessage()];
                }
            } else {
                $response = ["code" => 404, "status" => "error", "message" => "Something Wrong"];
            }
        }

        return response()->json($response)->header('Content-Type', 'application/json');
    }
    // ------------- MANAGE ADNIN RATE API DATA -------------- //

    // ------------- MANAGE  FUND WALLETS -------------- //
    public function fund_wallet_requests_fetch(Request $req)
    {
        if (session()->has('admin_id')) {
            if (!$req->filter) {
                $fundWallets = DB::table('fund_wallets')->orderBy('fund_wallets_id', 'DESC')->get();
                $filter = '';
            } else {
                $fundWallets = DB::table('fund_wallets')->where('status', $req->filter)->orderBy('fund_wallets_id', 'DESC')->get();
                $filter = $req->filter;
            }

            return response()->json([
                'fundWallets' => $fundWallets,
                'filter' => $filter,
            ]);
        } else {
            return redirect('admin');
        }
    }
    // ------------- MANAGE  FUND WALLETS -------------- //

    // ------------- MANAGE FUND WALLETS  PAGE -------------- //
    public function fund_wallet_requests()
    {
        if (session()->has('admin_id')) {
            return view('admin.fund_wallet_requests');
        } else {
            return redirect('admin');
        }
    }
    // ------------- MANAGE FUND WALLETS  PAGE -------------- //

    // ------------- UPDATE FUND WALLETS   -------------- //
    public function fund_wallet_requests_update(Request $req)
    {
        $update_array['status'] = $req->status;
        if ($req->status === "Funded") {
            $data = DB::table('fund_wallets')->where('fund_wallets_id', $req->fund_wallets_id)->first();
            $wallet = DB::table('users_customers_wallets')->where('users_customers_wallets_id', $data->users_customers_wallets_id)->first();
            $total_amount = $wallet->wallet_amount + $data->amount;
            $wallet_update = DB::table('users_customers_wallets')->where('users_customers_wallets_id', $data->users_customers_wallets_id)->update(['wallet_amount' => $total_amount]);
            // send mail to user
            $users_data = DB::table('users_customers')->where('users_customers_id', $wallet->users_customers_id)->first();
            $onlyEmail = $users_data->email;
            $details = [
                "title" => "Fund Deposited",
                "body" => 'Payment transaction approved and deposited to wallet.'
            ];
            $sendMail = Mail::to($onlyEmail)->send(new SendMail($details));
            // send mail to user

            $dataInsert = [
                'users_type' => "Admin",
                'senders_id' => session('id'),
                'receivers_id' => $wallet->users_customers_id,
                'message' => 'Payment transaction approved and deposited to wallet.',
                'date_added' => date('Y-m-d H:i:s'),
                'date_modified' => date('Y-m-d H:i:s')
            ];

            DB::table('notifications')->insert($dataInsert);
        }
        $updated = DB::table('fund_wallets')->where('fund_wallets_id', $req->fund_wallets_id)->update($update_array);
        if ($updated) {
            $response["code"] = 200;
            $response["status"] = "success";
            $response["message"] = "Data Updated successfully";
        } else {
            $response["code"] = 404;
            $response["status"] = "error";
            $response["message"] = "Oops! Something went wrong.";
        }

        return response()
            ->json(array('status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
            ->header('Content-Type', 'application/json');
    }
    // ------------- UPDATE  FUND WALLETS -------------- //

    // ------------- DELETE  FUND WALLETS -------------- //
    public function fund_wallet_requests_delete(Request $req)
    {
        if ($req->fund_wallets_id) {
            $checkdata = DB::table('fund_wallets')->where('fund_wallets_id', $req->fund_wallets_id)->where('status', '!=', 'Deleted')->first();

            if ($checkdata) {
                $del = DB::table('fund_wallets')->where('fund_wallets_id', '=', $req->fund_wallets_id)->update(array('status' => 'Deleted'));
                if ($del) {
                    $response["code"] = 200;
                    $response["status"] = "success";
                    $response["message"] = "Data Deleted successfully";
                } else {
                    $response["code"] = 404;
                    $response["status"] = "error";
                    $response["message"] = "Oops! Something went wrong.";
                }
            } else {
                $response["code"] = 404;
                $response["status"] = "error";
                $response["message"] = "This record is already deleted in status.";
            }
        } else {
            $response["code"] = 404;
            $response["status"] = "error";
            $response["message"] = "No Data Found.";
        }
        return response()
            ->json(array('status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
            ->header('Content-Type', 'application/json');
    }
    // ------------- DELETE  FUND WALLETS -------------- //

    // ------------- MANAGE FUND WALLETS  -------------- //
    public function fund_wallet_requests_edit($id)
    {
        if (session()->has('admin_id')) {
            $page_name = 'fund_wallet_edit';
            $fund_wallet = DB::table('fund_wallets')->where('fund_wallets_id', $id)->first();
            if ($fund_wallet) {
                $response["code"] = 200;
                $response["status"] = "success";
                $response["data"] = $fund_wallet;
            } else {
                $response["code"] = 404;
                $response["status"] = "error";
                $response["message"] = "Data not found.";
            }
        } else {
            $response["code"] = 404;
            $response["status"] = "error";
            $response["message"] = "Oops! Something went wrong.";
        }
        return response()
            ->json(array('status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
            ->header('Content-Type', 'application/json');
    }
    // ------------- MANAGE FUND WALLETS  -------------- //

    // ------------- MANAGE WITHDRAW WALLETS -------------- //
    public function withdraw_wallets_requests_fetch(Request $req)
    {
        if (session()->has('admin_id')) {
            if (!$req->filter) {
                $fetch_data = DB::table('withdraw_wallets_requests')->orderBy('withdraw_wallets_requests_id', 'DESC')->get();
                $filter = '';
            } else {
                $fetch_data = DB::table('withdraw_wallets_requests')->where('status', $req->filter)->orderBy('withdraw_wallets_requests_id', 'DESC')->get();
                $filter = $req->filter;
            }
            $get_data = [];
            foreach ($fetch_data as $key => $WithdrawWallet) {
                $wallet = DB::table('users_customers_wallets')->where('users_customers_wallets_id', $WithdrawWallet->users_customers_wallets_id)->first();
                $WithdrawWallet->wallet = $wallet;
                $WithdrawWallet->system_currency = $wallet ? DB::table('system_currencies')->where('system_currencies_id', $wallet->system_currencies_id)->first() : null;
                $WithdrawWallet->users_customer = DB::table('users_customers')->where('users_customers_id', $WithdrawWallet->users_customers_id)->first();
                $get_data[] = $WithdrawWallet;
            }
            return response()->json([
                'WithdrawWallets' => $get_data,
                'filter' => $filter,
            ]);
        } else {
            return redirect('admin');
        }
    }
    // ------------- MANAGE WITHDRAW WALLETS -------------- //

    // ------------- MANAGEWITHDRAW WALLETS  PAGE -------------- //
    public function withdraw_wallets_requests()
    {
        if (session()->has('admin_id')) {
            return view('admin.withdraw_wallets_requests');
        } else {
            return redirect('admin');
        }
    }
    // ------------- MANAGEWITHDRAW WALLETS  PAGE -------------- //

    // ------------- UPDATEWITHDRAW WALLETS   -------------- //
    public function withdraw_wallets_requests_update(Request $req)
    {
        $update_array['status'] = $req->status;
        $updated = DB::table('withdraw_wallets_requests')->where('withdraw_wallets_requests_id', $req->withdraw_wallets_requests_id)->update($update_array);
        if ($updated) {
            $response["code"] = 200;
            $response["status"] = "success";
            $response["message"] = "Data Updated successfully";
        } else {
            $response["code"] = 404;
            $response["status"] = "error";
            $response["message"] = "Oops! Something went wrong.";
        }

        return response()
            ->json(array('status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
            ->header('Content-Type', 'application/json');
    }
    // ------------- UPDATE WITHDRAW WALLETS -------------- //

    // ------------- DELETE WITHDRAW WALLETS -------------- //
    public function withdraw_wallets_requests_delete(Request $req)
    {
        if ($req->withdraw_wallets_requests_id) {
            $checkdata = DB::table('withdraw_wallets_requests')->where('withdraw_wallets_requests_id', $req->withdraw_wallets_requests_id)->where('status', '!=', 'Deleted')->first();

            if ($checkdata) {
                $del = DB::table('withdraw_wallets_requests')->where('withdraw_wallets_requests_id', '=', $req->withdraw_wallets_requests_id)->update(array('status' => 'Deleted'));
                if ($del) {
                    $response["code"] = 200;
                    $response["status"] = "success";
                    $response["message"] = "Data Deleted successfully";
                } else {
                    $response["code"] = 404;
                    $response["status"] = "error";
                    $response["message"] = "Oops! Something went wrong.";
                }
            } else {
                $response["code"] = 404;
                $response["status"] = "error";
                $response["message"] = "This record is already deleted in status.";
            }
        } else {
            $response["code"] = 404;
            $response["status"] = "error";
            $response["message"] = "No Data Found.";
        }
        return response()
            ->json(array('status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
            ->header('Content-Type', 'application/json');
    }
    // ------------- DELETE WITHDRAW WALLETS -------------- //

    // ------------- MANAGEWITHDRAW WALLETS  -------------- //
    public function withdraw_wallets_requests_edit($id)
    {
        if (session()->has('admin_id')) {
            $page_name = 'fund_wallet_edit';
            $fund_wallet = DB::table('withdraw_wallets_requests')->where('withdraw_wallets_requests_id', $id)->first();
            $wallet = DB::table('users_customers_wallets')->where('users_customers_wallets_id', $fund_wallet->users_customers_wallets_id)->first();
            $fund_wallet->wallet = $wallet;
            $fund_wallet->system_currency = DB::table('system_currencies')->where('system_currencies_id', $wallet->system_currencies_id)->first();
            $fund_wallet->users_customer = DB::table('users_customers')->where('users_customers_id', $fund_wallet->users_customers_id)->first();
            if ($fund_wallet) {
                $response["code"] = 200;
                $response["status"] = "success";
                $response["data"] = $fund_wallet;
            } else {
                $response["code"] = 404;
                $response["status"] = "error";
                $response["message"] = "Data not found.";
            }
        } else {
            $response["code"] = 404;
            $response["status"] = "error";
            $response["message"] = "Oops! Something went wrong.";
        }
        return response()
            ->json(array('status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
            ->header('Content-Type', 'application/json');
    }
    // ------------- MANAGEWITHDRAW WALLETS  -------------- //
}
