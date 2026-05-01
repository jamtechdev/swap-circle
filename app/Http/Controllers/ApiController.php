<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;
// use Stripe\PaymentIntent;
use App\Mail\SendMail;
use DB;
use App\Models\{UsersCustomersWallet,SystemCurrency,SystemCountry,UsersCustomersTxns,SwapWallet,SwapOffer,SwapOfferRequest,Feedback,FAQ,
  FavoriteSwapOffer,FavoriteConnectArticle,ConnectArticleView,UserCustomerAccount};
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use App\Helpers\Helper;
use App\services\InsuretechSyncService;
use App\Http\Controllers\PushNotificationController;
use Barryvdh\DomPDF\Facade\Pdf;

class ApiController extends Controller{
  protected $pnc;
//   public function __construct(PushNotificationController $pnc){
//     $this->pnc = $pnc;
//   }
public function paymentSuccess(Request $request)
{
    Stripe::setApiKey(env('STRIPE_SECRET'));

    $session = StripeSession::retrieve($request->session_id);

    if ($session->payment_status !== 'paid') {
        return redirect('/')->with('error', 'Payment not completed');
    }

    $data = [
        'name'   => $session->customer_details->name ?? 'Customer',
        'email'  => $session->customer_details->email ?? '',
        'amount' => $session->amount_total / 100,
    ];

    // Generate PDF and save temporarily
    $pdf      = Pdf::loadView('pdf.invoice', $data);
    $pdfPath  = storage_path('app/invoice_' . $session->id . '.pdf');
    $pdf->save($pdfPath);

    // Send invoice via email
    $this->send_simple_mail(
        $data['email'],
        'Your Invoice - Swap Circle',
        '<p>Dear ' . $data['name'] . ', please find your invoice attached.</p>',
        [$pdfPath]
    );

    // Clean up temp file
    @unlink($pdfPath);

    return $pdf->download('invoice.pdf');
}


  /* SEND NOTIFICATIONS */
  public function send_notification($data){
    DB::table('notifications')->insert($data);
  }
  /* SEND NOTIFICATIONS */

  /* DECODE IMAGE */
  public function decode_image($img , $path_url, $prefix, $random, $postfix){                                   
    $data = base64_decode($img);
    $file_name = $prefix.$random.$postfix.'.jpeg';
    $file = $path_url.$file_name;
    $success = file_put_contents($file, $data);
    return $file_name; 
  }
  /* DECODE IMAGE */

  /* SEND SIMPLE MAIL */
  // public function send_simple_mail($to, $subject, $message){
  //   $headers = "MIME-Version: 1.0" . "\r\n";
  //   $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
  //   $headers .= 'From: <info@swapcircle.trade>' . "\r\n";

  //   mail($to,$subject,$message,$headers);
  // }
  public function send_simple_mail($to, $subject, $message, $attachments = [])
  {
      $headers  = "MIME-Version: 1.0\r\n";
      $headers .= "Content-type:text/html;charset=UTF-8\r\n";
      $headers .= "From: Swap Circle <info@swapcircle.trade>\r\n";
  
      try {
          // âœ… If SMTP configured â†’ use Laravel Mail
          if (env('MAIL_MAILER') === 'smtp' && env('MAIL_HOST')) {
  
              \Mail::send([], [], function ($mail) use ($to, $subject, $message, $attachments) {
  
                  $mail->to($to)
                       ->subject($subject)
                       ->html($message);
  
                  // âœ… Attach files only if provided
                  if (!empty($attachments)) {
                      foreach ($attachments as $file) {
                          if (!empty($file) && file_exists($file)) {
                              $mail->attach($file);
                          }
                      }
                  }
              });
  
              return true;
          }
  
      } catch (\Exception $e) {
          \Log::warning('SMTP mail failed, using fallback: ' . $e->getMessage());
      }
  
      // âœ… Fallback (no attachments supported here)
      return mail($to, $subject, $message, $headers);
  }

  /* SEND SIMPLE MAIL */

  
  /* GENERATE PURCHASE EMAIL HTML FOR PRODUCT TYPES A & B */
  private function generatePurchaseEmailHTML_AB($purchase, $customer, $product, $beneficiary, $purchase_id = null) {
    $productName      = htmlspecialchars($product->name);
    $coverDuration    = htmlspecialchars($purchase->cover_duration);
    $coverStartDate   = date('d-m-Y', strtotime($purchase->cover_start_date));
    $coverEndDate     = date('d-m-Y', strtotime($purchase->cover_end_date));
    $firstName        = htmlspecialchars($beneficiary->first_name);
    $surname          = htmlspecialchars($beneficiary->surname);
    $gender           = htmlspecialchars($beneficiary->gender);
    $dateOfBirth      = date('d-m-Y', strtotime($beneficiary->date_of_birth)); 
    $address          = htmlspecialchars($beneficiary->address);
    $occupation       = htmlspecialchars(DB::table('occupations')->where('occupations_id', $beneficiary->occupations_id)->first()->name); 
    $relationship     = htmlspecialchars(DB::table('relationships')->where('relationships_id', $beneficiary->relationships_id)->first()->name); 
    
    if (!empty($beneficiary->phone_number)) {
      $ninInfo = htmlspecialchars($beneficiary->phone_number);
    } elseif (!empty($beneficiary->nin_document)) {
      $ninInfo = '<img src="' . asset($beneficiary->nin_document) . '" alt="NIN Document" style="max-width:200px; max-height:150px; border:1px solid #ccc; border-radius:4px;" />';
    } else {
      $ninInfo = 'Not Provided';
    }

    $invoiceUrl = $purchase_id ? url('/api/download-invoice/'.$purchase_id) : '';
    return '
      <html>
      <head>
        <style>
          body {
            font-family: Arial, sans-serif; 
            color: #333;
            margin: 0;
            padding: 20px;
            background-color: #fff;
          }
          .email-text-fullwidth {
            width: 100%;
            font-size: 14px;
            line-height: 1.5;
            margin-bottom: 20px;
            box-sizing: border-box;
          }
          .receipt-container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto 20px auto;
            background: #f9f9f9;
            border: 1px solid #ccc;
            padding: 20px;
            box-sizing: border-box;
          }
          .receipt-container h2 {
            text-align: center;
            font-weight: bold;
            margin-bottom: 20px;
            font-size: 18px;
          }
          .receipt-container table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
            color: #333;
          }
          .receipt-container th, 
          .receipt-container td {
            border: 1px solid #ccc;
            padding: 8px 12px;
            vertical-align: top;
          }
          .receipt-container th {
            text-align: left;
            width: 180px;
            background-color: #f0f0f0;
            font-weight: 600;
          }
        </style>
      </head>
      <body>

        <div class="email-text-fullwidth">
          Dear ' . trim($customer->first_name . ' ' . ($customer->last_name ?? '')) . ',<br><br>
          Thank you for your purchase with <strong>Swap Circle</strong>. Your order has been successfully received. Please find below the details of your purchase:
        </div>
        <div class="receipt-container">
          <h2>Swap Circle - Purchase Confirmation</h2>
          <table>
            <tr>
              <th>Product Name</th>
              <td>' . $productName . '</td>
            </tr>
            <tr>
              <th>Cover Duration</th>
              <td>' . $coverDuration . '</td>
            </tr>
            <tr>
              <th>Cover Start Date</th>
              <td>' . $coverStartDate . '</td>
            </tr>
            <tr>
              <th>Cover End Date</th>
              <td>' . $coverEndDate . '</td>
            </tr>
            <tr>
              <th>Beneficiary First Name</th>
              <td>' . $firstName . '</td>
            </tr>
            <tr>
              <th>Beneficiary Surname</th>
              <td>' . ($surname ?? '-') . '</td>
            </tr>
            <tr>
              <th>Beneficiary Gender</th>
              <td>' . $gender . '</td>
            </tr>
            <tr>
              <th>Beneficiary Date of Birth</th>
              <td>' . $dateOfBirth . '</td>
            </tr>
            <tr>
              <th>Beneficiary Address</th>
              <td>' . $address . '</td>
            </tr>
            <tr>
              <th>Beneficiary Occupation</th>
              <td>' . $occupation . '</td>
            </tr>
            <tr>
              <th>Beneficiary Relationship</th>
              <td>' . $relationship . '</td>
            </tr>
            <tr>
              <th>Beneficiary Phone Number</th>
              <td>' . $ninInfo . '</td>
            </tr>
          </table>
        </div>
        <div class="email-text-fullwidth">
          Regards,<br>
          <strong>Swap Circle Team</strong>
        </div>

        ' . ($purchase_id ? '
        <div style="margin-top:24px;border-top:2px solid #eee;padding-top:20px;text-align:center;">
          <p style="font-size:14px;font-weight:bold;color:#1a3c6e;margin-bottom:14px;">&#128196; Download Your Documents</p>
          <table style="width:100%;max-width:500px;margin:0 auto;border-collapse:collapse;">
            <tr>
              <td style="padding:0 8px 0 0;width:50%;">
                  <a href="' . $invoiceUrl . '" style="display:block;text-align:center;padding:12px 10px;background:#28a745;color:#fff;text-decoration:none;border-radius:6px;font-size:13px;font-weight:bold;font-family:Arial,sans-serif;">&#128196; Download Invoice</a>


                </td>
              </td>
             
             
            </tr>
          </table>
          <p style="font-size:11px;color:#999;margin-top:10px;">If buttons do not work, copy and paste the links into your browser.</p>
        </div>
        ' : '') . '

      </body>
      </html>';
  }
  /* GENERATE PURCHASE EMAIL HTML FOR PRODUCT TYPES A & B */

  /* GENERATE PURCHASE EMAIL HTML FOR PRODUCT TYPE C */
  private function generatePurchaseEmailHTML_C($purchase, $customer, $product, $task, $purchase_id = null) {
    $productName          = htmlspecialchars($product->name);
    $taskType             = htmlspecialchars(DB::table('tasks_types')->where('tasks_types_id', $task->tasks_types_id)->first()->name); 
    $taskName             = htmlspecialchars($task->task);
    $taskDate             = date('d-m-Y', strtotime($task->task_date));
    $description          = htmlspecialchars($task->description);
    $contactPersonName    = htmlspecialchars($task->recipient_name);
    $contactPersonPhone   = htmlspecialchars($task->recipient_phone);
    $deliveriesLimit      = $task->delivery_request_limit;
    $deliveriesUsed       = $task->delivery_requests_consumed;

    $invoiceUrl = $purchase_id ? url('/api/download-invoice/'.$purchase_id) : '';
    return '
      <html>
      <head>
        <style>
          body {
            font-family: Arial, sans-serif; 
            color: #333;
            margin: 0;
            padding: 20px;
            background-color: #fff;
          }
          .email-text-fullwidth {
            width: 100%;
            font-size: 14px;
            line-height: 1.5;
            margin-bottom: 20px;
          }
          .receipt-container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto 20px auto;
            background: #f9f9f9;
            border: 1px solid #ccc;
            padding: 20px;
            box-sizing: border-box;
          }
          .receipt-container h2 {
            text-align: center;
            font-weight: bold;
            margin-bottom: 20px;
            font-size: 18px;
          }
          .receipt-container table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
            color: #333;
          }
          .receipt-container th, 
          .receipt-container td {
            border: 1px solid #ccc;
            padding: 8px 12px;
            vertical-align: top;
          }
          .receipt-container th {
            text-align: left;
            width: 180px;
            background-color: #f0f0f0;
            font-weight: 600;
          }
        </style>
      </head>
      <body>

        <div class="email-text-fullwidth">
          Dear ' . trim($customer->first_name . ' ' . ($customer->last_name ?? '')) . ',<br><br>
          Thank you for your task request with <strong>Swap Circle</strong>. Please find below the details of your requested task:
        </div>
        <div class="receipt-container">
          <h2>Swap Circle - Task Request Confirmation</h2>
          <table>
            <tr><th>Product Name</th><td>' . $productName . '</td></tr>
            <tr><th>Task Type</th><td>' . $taskType . '</td></tr>
            <tr><th>Task Name</th><td>' . $taskName . '</td></tr>
            <tr><th>Task Date</th><td>' . $taskDate . '</td></tr>
            <tr><th>Description</th><td>' . $description . '</td></tr>
            <tr><th>Contact Person</th><td>' . $contactPersonName . '</td></tr>
            <tr><th>Contact Phone</th><td>' . $contactPersonPhone . '</td></tr>
            <tr><th>Delivery Requests Limit</th><td>' . $deliveriesLimit . '</td></tr>
            <tr><th>Delivery Requests Consumed</th><td>' . $deliveriesUsed . '</td></tr>
          </table>
        </div>
        <div class="email-text-fullwidth">
          Regards,<br>
          <strong>Swap Circle Team</strong>
        </div>

        ' . ($purchase_id ? '
        <div style="margin-top:24px;border-top:2px solid #eee;padding-top:20px;text-align:center;">
          <p style="font-size:14px;font-weight:bold;color:#1a3c6e;margin-bottom:14px;">&#128196; Download Your Documents</p>
          <table style="width:100%;max-width:500px;margin:0 auto;border-collapse:collapse;">
            <tr>
             <td style="padding:0 8px 0 0;width:50%;">
                  <a href="' . $invoiceUrl . '" style="display:block;text-align:center;padding:12px 10px;background:#28a745;color:#fff;text-decoration:none;border-radius:6px;font-size:13px;font-weight:bold;font-family:Arial,sans-serif;">&#128196; Download Invoice</a>


                </td>
                  border-radius:6px;font-size:13px;font-weight:bold;">
                  Download Invoice
                </a>
              </td>
                         
            </tr>
          </table>
          <p style="font-size:11px;color:#999;margin-top:10px;">If buttons do not work, copy and paste the links into your browser.</p>
        </div>
        ' : '') . '

      </body>
      </html>';
  }
  /* GENERATE PURCHASE EMAIL HTML FOR PRODUCT TYPE C */

  /* USERS CUSTOMERS DETAILS */
  public function users_customers_profile(Request $req){
    if (isset($req->users_customers_id)) {
      $email = DB::table('users_customers')->where('users_customers_id', $req->users_customers_id)->get()->count();
      if ($email>0) {
        $userDetail=DB::table('users_customers')->where('users_customers_id', $req->users_customers_id)->get()->first();
        if (isset($userDetail) && $userDetail != null) {
          $response["code"] = 200;
          $response["status"] = "success";
          $response["data"] = $userDetail;
        } else{
          $response["code"] = 404;
          $response["status"] = "error";
          $response["message"] = "User do not exist.";
        }
      } else {
        $response["code"] = 404;
        $response["status"] = "error";
        $response["message"] = "Email does not exits.";
      }
    } else {
      $response["code"] = 404;
      $response["status"] = "error";
      $response["message"] = "All fields are needed.";
    }

    return response()
    ->json(array( 'status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
    ->header('Content-Type', 'application/json');
  }
  /* USERS CUSTOMERS DETAILS */

  /* LOGIN USERS CUSTOMERS */
  public function users_customers_login(Request $req){
    if (isset($req->email) && isset($req->password)) {
      $email = DB::table('users_customers')->where('email', $req->email)->first();
      if ($email) {
        $data=DB::table('users_customers')->where('email', $req->email)->first();
        $password=$data->password;
        $id = $data->users_customers_id;
        if (md5($req->password) == $password) {
          if($data->status == 'Active'){
            if($req->one_signal_id){
              $update=DB::table('users_customers')->where('email', $req->email)->update(['one_signal_id'=>$req->one_signal_id]);
            }
              $update_last_activity=DB::table('users_customers')->where('email', $req->email)->update(['last_activity'=>Carbon::now()]);

            $userDetail=DB::table('users_customers')->where('users_customers_id', $id)->get()->first();
            if (isset($userDetail) && $userDetail != null) {
              $response["code"] = 200;
              $response["status"] = "success";
              $response["data"] = $userDetail;
            } else{
              $response["code"] = 404;
              $response["status"] = "error";
              $response["message"] = "User do not exist.";
            }
          } else {
            $response["code"] = 404;
            $response["status"] = "error";
            $response["message"] = "Your account is in ".$data->status." status. Please contact admin.";
          }
        } else {
          $response["code"] = 404;
          $response["status"] = "error";
          $response["message"] = "Password do not match.";
        }
      }else{
        $response["code"] = 404;
        $response["status"] = "error";
        $response["message"] = "Email does not exits.";
      }
    } else {
      $response["code"] = 404;
      $response["status"] = "error";
      $response["message"] = "All fields are needed.";
    }

    return response()
    ->json(array( 'status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
    ->header('Content-Type', 'application/json');
  }
  /* LOGIN USERS CUSTOMERS */

  /* SIGNUP USERS CUSTOMERS */
  public function users_customers_signup(Request $req){
    if (isset($req->first_name) && isset($req->phone) && isset($req->email) && isset($req->password) && isset($req->location)) {
      $email = DB::table('users_customers')->where('email', $req->email)->get()->count();

      if($email == 0) {
        if(isset($req->one_signal_id)){
        	$saveData['one_signal_id']        = $req->one_signal_id;
        }
        $saveData['users_customers_type'] = $req->users_customers_type;

        if($req->users_customers_type == 'Company'){
          $saveData['company_name']           = $req->company_name;
        }

        if($req->users_customers_type == 'Individual'){
          $saveData['last_name']            = $req->last_name;
        }

        $saveData['id_number']         	  = $req->id_number;
        $saveData['first_name']           = $req->first_name;
        $saveData['phone']                = $req->phone;
        $saveData['email']                = $req->email;
        $saveData['password']             = md5($req->password);
        $saveData['location']             = $req->location;

        if ($req->one_signal_id) {
          $saveData['one_signal_id'] = $req->one_signal_id;
        }

        if(isset($req->valid_document)){
          $valid_document = $req->valid_document;
          $prefix = time();
          $img_name = $prefix.'.jpeg';
          $image_path = public_path('uploads/users_documents/').$img_name;

          file_put_contents($image_path, base64_decode($valid_document));
          $saveData['valid_document'] = 'uploads/users_documents/'.$img_name;
        }

        if(isset($req->id_front_image)){
          $id_front_image = $req->id_front_image;
          $prefix = time();
          $img_name = $prefix.'.jpeg';
          $image_path = public_path('uploads/users_id_front_image/').$img_name;

          file_put_contents($image_path, base64_decode($id_front_image));
          $saveData['id_front_image'] = 'uploads/users_id_front_image/'.$img_name;
        }

        if(isset($req->id_back_image)){ 
          $id_back_image = $req->id_back_image;
          $prefix = time();
          $img_name = $prefix.'.jpeg';
          $image_path = public_path('uploads/users_id_back_image/').$img_name;

          file_put_contents($image_path, base64_decode($id_back_image));
          $saveData['id_back_image'] = 'uploads/users_id_back_image/'.$img_name;
        }

        if(isset($req->profile_pic)){
          $profile_pic = $req->profile_pic;
          $prefix = time();
          $img_name = $prefix . '.jpeg';
          $image_path = public_path('uploads/users_customers/') . $img_name;

          file_put_contents($image_path, base64_decode($profile_pic));
          $saveData['profile_pic'] = 'uploads/users_customers/'. $img_name;
        }
        if(isset($req->refer_code)){
          $receiver_id=base64_decode($req->refer_code);
          $system_currencies_id=2;
          $receive_amount=20;
	        $receiver_wallet = UsersCustomersWallet::where([
            'users_customers_id' => $receiver_id,
            'system_currencies_id'=>$system_currencies_id
          ])->first();
          while(!$receiver_wallet){
            $wallet = UsersCustomersWallet::firstOrCreate(
                      ['users_customers_id' => $receiver_id,
                      'system_currencies_id'=>$system_currencies_id],
                      ['users_customers_id' => $receiver_id,
                      'system_currencies_id'=>$system_currencies_id]
                  );
          }
          $receiver_wallet_update = UsersCustomersWallet::where([
            'users_customers_id' => $receiver_id,
            'system_currencies_id'=>$system_currencies_id
          ])->update([
            "wallet_amount"=>$receive_amount
          ]);
	      }
        
        $saveData['notifications']        = 'Yes';
        if(isset($req->account_type)){
	        $saveData['account_type']     = $req->account_type;
	    }
        $saveData['social_acc_type']      = 'None';
        $saveData['google_access_token']  = '';

        $saveData['verified_badge']       = 'No';
        $saveData['date_expiry']       	  = $req->date_expiry;
        $saveData['date_added']           = date('Y-m-d H:i:s');
        $saveData['last_activity']        = Carbon::now();
        

        $users_customers_id   = DB::table('users_customers')->insertGetId($saveData);
        $users_customers      = DB::table('users_customers')->where('users_customers_id', $users_customers_id)->first();

          $otp = rand(1000, 9999);
          DB::table('users_customers')->where('users_customers_id', $users_customers_id)->update(['verify_code' => $otp]);
           $otpMessage = '
        <div style="font-family:Arial,sans-serif;max-width:600px;margin:0 auto;padding:0;">
          <div style="background:#1a3c6e;padding:24px 30px;border-radius:6px 6px 0 0;">
            <h1 style="color:#fff;margin:0;font-size:22px;letter-spacing:1px;">Confirm Your Email Address</h1>
            <p style="color:rgba(255,255,255,0.8);margin:6px 0 0;font-size:13px;">Swap Circle - Email Verification</p>
          </div>
          <div style="background:#fff;border:1px solid #ddd;border-top:none;border-radius:0 0 6px 6px;padding:28px 30px;">
            <p style="font-size:15px;color:#333;margin-bottom:8px;">Hello,</p>
            <p style="font-size:14px;color:#555;line-height:1.7;margin-bottom:20px;">
              Thank you for registering with <strong>Swap Circle</strong>.<br>
              To complete your registration and verify your email address, please enter the confirmation code below:
            </p>
            <div style="text-align:center;margin:28px 0;">
              <div style="display:inline-block;background:#f0f4fb;border:2px solid #1a3c6e;border-radius:10px;padding:18px 36px;">
                <span style="font-size:40px;font-weight:bold;letter-spacing:14px;color:#1a3c6e;">' . $otp . '</span>
              </div>
              <p style="font-size:12px;color:#999;margin-top:12px;">This code expires in <strong>30 minutes</strong>.</p>
            </div>
            <p style="font-size:14px;color:#555;line-height:1.7;">
              Enter this code on the verification page to confirm your email address and activate your account.
            </p>
            <p style="font-size:12px;color:#aaa;border-top:1px solid #eee;padding-top:16px;margin-top:24px;">
              If you did not create an account with Swap Circle, please ignore this email.<br>
              For support: <a href="mailto:support@swapcircle.trade" style="color:#1a3c6e;">support@swapcircle.trade</a>
            </p>
          </div>
        </div>';
          $this->send_simple_mail($users_customers->email, 'Verify Your Email - Swap Circle', $otpMessage);


        $response["code"]     = 200;   
        $response["status"]   = "success";
        $response["data"]     = $users_customers;
      } else {
        $response["code"]     = 401;
        $response["status"]   = "error";
        $response["message"]  = "Email already exists.";
      }
    } else {
      $response["code"] = 404;
      $response["status"] = "error";
      $response["message"] = "All fields are needed.";
    }
    
    return response()
     ->json(array( 'status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
     ->header('Content-Type', 'application/json');
  }
  /* SIGNUP USERS CUSTOMERS */

   /* VERIFY SIGNUP USERS CUSTOMERS OTP */
   public function users_customers_verify_otp(Request $req){
    if (isset($req->users_customers_id) && isset($req->verify_otp)) {
     $verifyOTP = DB::table('users_customers')->select('verify_code')->where('users_customers_id', $req->users_customers_id)->first();
     $verifyOTPDB = $verifyOTP->verify_code;
     if ($verifyOTPDB == $req->verify_otp) {

       $users_customer = DB::table('users_customers')->where('users_customers_id', $req->users_customers_id)->first();

        // Mark email as verified
        DB::table('users_customers')->where('users_customers_id', $req->users_customers_id)->update(['verified_badge' => 'Yes']);

        // Send welcome confirmation email
        $name = $users_customer->first_name . ($users_customer->last_name ? ' ' . $users_customer->last_name : ($users_customer->company_name ? ' (' . $users_customer->company_name . ')' : ''));
        $welcomeMsg = '<div style="font-family:Arial,sans-serif;max-width:600px;margin:0 auto;">
          <div style="background:#1a3c6e;padding:24px 30px;border-radius:6px 6px 0 0;">
            <h1 style="color:#fff;margin:0;font-size:22px;">Welcome to Swap Circle!</h1>
            <p style="color:rgba(255,255,255,0.8);margin:6px 0 0;font-size:13px;">Your email has been successfully verified.</p>
          </div>
          <div style="background:#f9f9f9;border:1px solid #ddd;border-top:none;border-radius:0 0 6px 6px;padding:28px 30px;">
            <p style="font-size:15px;color:#333;">Dear <strong>' . $name . '</strong>,</p>
            <p style="font-size:14px;color:#555;line-height:1.6;">Thank you for registering with <strong>Swap Circle</strong>. Your email address has been verified and your account is now active.</p>
            <p style="font-size:14px;color:#555;line-height:1.6;">You can now log in and explore our products.</p>
            <div style="text-align:center;margin:28px 0;">
              <a href="' . url('/') . '" style="display:inline-block;padding:12px 32px;background:#1a3c6e;color:#fff;text-decoration:none;border-radius:6px;font-size:14px;font-weight:bold;">Login to Your Account</a>
            </div>
            <p style="font-size:12px;color:#999;border-top:1px solid #eee;padding-top:16px;margin-top:16px;">If you did not create this account, please contact us at support@swapcircle.trade</p>
          </div>
        </div>';
        $this->send_simple_mail($users_customer->email, 'Welcome to Swap Circle - Email Verified!', $welcomeMsg);

       $response["code"] = 200;
       $response["status"] = "success";
       $response["data"] = $users_customer;
     } else {
       $response["code"] = 404;
       $response["status"] = "error";
       $response["message"] = "Otp do not match.";
     }
   }else{
     $response["code"] = 404;
     $response["status"] = "error";
     $response["message"] = "All fields are required.";
   }
   
   return response()
    ->json(array( 'status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
    ->header('Content-Type', 'application/json');
 }  
 /* VERIFY SIGNUP USERS CUSTOMERS OTP */

  /* UPDATE PROFILE */
  public function update_profile(Request $req){
    if(isset($req->users_customers_id) && isset($req->first_name) && isset($req->last_name) && isset($req->notifications)) {
      $updateData['users_customers_id'] = $req->users_customers_id;
      $saveData['users_customers_type']           = $req->users_customers_type;

      if($req->users_customers_type == 'Company'){
        $saveData['company_name']           = $req->company_name;
      }
      if(isset($req->phone)){
        $updateData['phone']              = $req->phone;
      }

      $updateData['first_name']         = $req->first_name;
      $updateData['last_name']          = $req->last_name;
      $updateData['location']           = $req->location;
      $updateData['notifications']      = $req->notifications;

      if(isset($req->valid_document)){
        $valid_document = $req->valid_document;
        $prefix = time();
        $img_name = $prefix . '.jpeg';
        $image_path = public_path('uploads/users_documents/') . $img_name;

        file_put_contents($image_path, base64_decode($valid_document));
        $updateData['valid_document'] = 'uploads/users_documents/'. $img_name;
      }

      if(isset($req->profile_pic)){
        $profile_pic = $req->profile_pic;
        $prefix = time();
        $img_name = $prefix . '.jpeg';
        $image_path = public_path('uploads/users_customers/') . $img_name;

        file_put_contents($image_path, base64_decode($profile_pic));
        $updateData['profile_pic'] = 'uploads/users_customers/'. $img_name;
      }

      DB::table('users_customers')->where('users_customers_id', $req->users_customers_id)->update($updateData);
      $updatedData = DB::table('users_customers')->where('users_customers_id', $req->users_customers_id)->get();
 
      $response["code"] = 200;
      $response["status"] = "success";
      $response["data"] = $updatedData;
    } else {
      $response["code"] = 404;
      $response["status"] = "error";
      $response["message"] = "All fields are needed.";
    }

    return response()
      ->json(array( 'status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
      ->header('Content-Type', 'application/json');
  }
  /* UPDATE PROFILE */

  /* FORGETPASSWORD API */
  public function forgot_password(Request $req){
    if (isset($req)) {
      $email=DB::table('users_customers')->where('email', $req->email)->get()->count();
      if ($email>0) {
        $data = DB::table('users_customers')->where('email', $req->email)->first();
        $id = $data->users_customers_id;
        $onlyEmail = $req->email;
        $otp = rand(1000,9999);
        /*$details = [
            "title"=>"Email Verification Code",
            "data"=>$data,
            "body"=> $otp
        ];
        $otpSended= Mail::to($onlyEmail)->send(new SendMail($details));*/

        /* send mail */
        $templateBody = $data->first_name .' '. $data->last_name;
        $otp = rand(1000, 9999);
        // $templateBody = str_replace('{otp}', $otp, $templateBody); // Assuming you want to replace the OTP
    
        $to       = $req->email;
        $subject  = 'Forgot Password';
        $message  = 'Hy, reset your password by as follows.';
        $this->send_simple_mail($to, $subject, $message);
        /* send mail */

        $otpData=array(
         'verify_code'=>$otp
        );
        $UserotpUpdate=DB::table('users_customers')->where('users_customers_id', $id)->update($otpData);

        $details = array('otp' => $otp,'data'=>$data, 'message' => 'OTP sent in the email.');
        $response["code"] = 200;
        $response["status"] = "success";
        $response["data"] = $details;
      }else{
        $response["code"] = 404;
        $response["status"] = "error";
        $response["message"] = "Email does not exists.";
      }
    }else{
      $response["code"] = 404;
      $response["status"] = "error";
      $response["message"] = "Please enter email address.";
    }
    
    return response()
      ->json(array( 'status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
      ->header('Content-Type', 'application/json');
  }
  /* FORGETPASSWORD API */

  /* MODIFY PASSWORD */
  public function modify_password(Request $req){
    if (isset($req->email) && isset($req->otp) && isset($req->password) && isset($req->confirm_password)) {
      $forgetOtp = DB::table('users_customers')->select('verify_code')->where('email', $req->email)->first();
      $otpforgetdb = $forgetOtp->verify_code;
      if ($otpforgetdb == $req->otp) {
        if ($req->confirm_password == $req->password) {
          $otpData=[
           'verify_code'=> null,
           'password' => md5($req->password)
          ];
          
          $UserotpUpdate =DB::table('users_customers')->where('email', $req->email)->update($otpData);
          $users_customer = DB::table('users_customers')->where('email', $req->email)->first();
          
          $response["code"] = 200;
          $response["status"] = "success";
          $response["data"] = $users_customer;
        } else {
          $response["code"] = 404;
          $response["status"] = "error";
          $response["message"] = "Password and confirm password do not match.";
        }
      } else {
        $response["code"] = 404;
        $response["status"] = "error";
        $response["message"] = "Otp do not match.";
      }
    }else{
      $response["code"] = 404;
      $response["status"] = "error";
      $response["message"] = "All fields are required.";
    }
    
    return response()
     ->json(array( 'status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
     ->header('Content-Type', 'application/json');
  }
  /* MODIFY PASSWORD */

  /* CHANGE PASSWORD */
  public function change_password(Request $req){
    if (isset($req->email) && isset($req->old_password) && isset($req->password) && isset($req->confirm_password)) {
      $old_password = DB::table('users_customers')->select('password')->where('email', $req->email)->first();
      $old_passwordDB = $old_password->password;
      if ($old_passwordDB == md5($req->old_password)) {
        if ($req->confirm_password == $req->password) {
          $otpData=array('password' => md5($req->password));          
          $UserotpUpdate =DB::table('users_customers')->where('email', $req->email)->update($otpData);
          $users_customers = DB::table('users_customers')->where('email', $req->email)->get();
          
          $response["code"] = 200;
          $response["status"] = "success";
          $response["data"] = $users_customers;
        } else {
          $response["code"] = 404;
          $response["status"] = "error";
          $response["message"] = "Password and confirm password do not match.";
        }
      } else {
        $response["code"] = 404;
        $response["status"] = "error";
        $response["message"] = "Old password is not correct.";
      }
    }else{
      $response["code"] = 404;
      $response["status"] = "error";
      $response["message"] = "All fields are required.";
    }
    
    return response()
     ->json(array( 'status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
     ->header('Content-Type', 'application/json');
  }
  /* CHANGE PASSWORD */

  /* DELETE ACCOUNT API */
  public function delete_account(Request $req){
    if (isset($req->user_email) && isset($req->delete_reason) && isset($req->comments)) {
      $users_customers = DB::table('users_customers')->where('email', $req->user_email)->get()->count();
      if ($users_customers>0) {
        $users_customers_delete = DB::table('users_customers_delete')->where('email', $req->user_email)->get()->count();
        if ($users_customers_delete == 0) { 
          $data = array(
            'email'=>$req->user_email,
            'delete_reason'=> $req->delete_reason,
            'comments'=> $req->comments,
            'date_added'=>date('Y-m-d H:i:s'),
            'status'=>'Pending'
          );
          $users_customers_id   = DB::table('users_customers_delete')->insertGetId($data);

          $response["code"] = 200;
          $response["status"] = "success";
          $response["message"] = "Delete account request recieved successfully.";
        }else{
          $response["code"] = 404;
          $response["status"] = "error";
          $response["message"] = "Delete account request already sent. Please wait out team will get back to you in 24 hours.";
        }
      }else{
        $response["code"] = 404;
        $response["status"] = "error";
        $response["message"] = "Email does not exists.";
      }
    }else{
      $response["code"] = 404;
      $response["status"] = "error";
      $response["message"] = "All fields are required.";
    }
    
    return response()
      ->json(array( 'status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
      ->header('Content-Type', 'application/json');
  }
  /* DELETE ACCOUNT API */

  /* GET SYSTEM SETTINGS */
  public function system_settings(){
    $fetch_data   =  DB::table('system_settings')->get();
    
    if (!empty($fetch_data)) {
      $response["code"] = 200;
      $response["status"] = "success";
      $response["data"] = $fetch_data;
    } else {
      $response["code"] = 404;
      $response["status"] = "error";
      $response["message"] = "no data found.";
    }
    return response()
      ->json(array( 'status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
      ->header('Content-Type', 'application/json');
  }
  /* GET SYSTEM SETTINGS */

  /* NOTIFICATIONS API */
  public function notifications(Request $req){
    if (isset($req->users_customers_id)) {
      $notifications  = DB::table('notifications')->where('receivers_id', $req->users_customers_id)->orderBy('notifications_id','DESC')->get();
      $data=[];
      foreach($notifications as $notification){
        $notification->notification_sender= DB::table('users_customers')->where('users_customers_id', $notification->senders_id)->select("first_name","last_name","profile_pic")->first();
        $notification->time_ago=Carbon::parse($notification->date_added)->diffForHumans();
        $data[]=$notification;
      }

      $response["code"] = 200;
      $response["status"] = "success";
      $response["data"] = $data;
    } else {
      $response["code"] = 404;
      $response["status"] = "error";
      $response["message"] = "All fields are required.";
    }
    
    return response()
      ->json(array( 'status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
      ->header('Content-Type', 'application/json');
  }
  /* NOTIFICATIONS API */

  /* UNREAD NOTIFICATIONS API */
  public function notifications_unread(Request $req){
    if (isset($req->users_customers_id)) {
      $notifications  = DB::table('notifications')->where('receivers_id', $req->users_customers_id)->where('notifications.status', 'Unread')->orderBy('notifications_id','DESC')->get();

      $data = array("status"=>'Read');
      $updateProfile=DB::table('notifications')->where('receivers_id', $req->users_customers_id)->where('status', 'Unread')->update($data);

      $response["code"] = 200;
      $response["status"] = "success";
      $response["data"] = $notifications;
    } else {
      $response["code"] = 404;
      $response["status"] = "error";
      $response["message"] = "All fields are required.";
    }
    
    return response()
      ->json(array( 'status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
      ->header('Content-Type', 'application/json');
  }
  /* UNREAD NOTIFICATIONS API */

  /*** UNREADED  MESSAGES ***/
  public function unreaded_messages(Request $req){  
    if (isset($req->users_customers_id)){
      $unread_chat = DB::table('chat_messages')->where(['receiver_id'=>$req->users_customers_id,'status'=>'Unread'])->get()->count();
      $response["code"] = 200;
      $response["status"] = "success";
      $response["data"] = $unread_chat;
    } else {
      $response["code"] = 404;
      $response["status"] = "error";
      $response["message"] = "All fields are required.";
    }
    
    return response()
      ->json(array( 'status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
      ->header('Content-Type', 'application/json');
  }
  /*** UNREADED  MESSAGES ***/

  /*** CHAT HEADS ***/
  public function getAllChat(Request $req){  
    if (isset($req->users_customers_id)) {
      $final_chat_array = array();
      $chat_list = DB::table('chat_list')->where('sender_id', $req->users_customers_id)->orWhere('receiver_id', $req->users_customers_id)->get();
      foreach($chat_list as $key => $chat){
        $chat_array = array();
        $chat_array['sender_id'] = $chat->sender_id;
        $chat_array['receiver_id'] = $chat->receiver_id;

        $chat_message = DB::table('chat_messages') 
        ->whereIn('sender_id',[$chat->receiver_id,$chat->sender_id])
        ->whereIn('receiver_id',[$chat->receiver_id,$chat->sender_id])
        ->orderBy('chat_message_id', 'desc')
        ->first();
        if ($chat_message) {
          $date_request = Helper::get_day_difference($chat_message->send_date);
          $chat_array['date'] = $date_request;
          $chat_array['status'] = $chat_message->status;
          $chat_array['last_message'] = $chat_message->message;
        } else {
          $date_request = Helper::get_day_difference($chat->date_request);
          $chat_array['date'] = $date_request;
          $chat_array['last_message'] = 'No Message sent or recieved.';
        }
        if($chat->sender_id==$req->users_customers_id){
            $receiver_data = DB::table('users_customers')->where('users_customers_id',$chat->receiver_id)->first();
            $chat_array['user_data'] = $receiver_data;
        }
        if($chat->receiver_id==$req->users_customers_id){
          // $chat_message = DB::table('chat_messages')->whereIn('sender_id',  [$chat->receiver_id,$chat->sender_id])->orderBy('chat_message_id','DESC')->first();
          $sender_data = DB::table('users_customers')->where('users_customers_id',$chat->sender_id)->first();
          $chat_array['user_data'] = $sender_data;
        
          // if ($chat_message) {
          //   $date_request = Helper::get_day_difference($chat_message->send_date);
          //   $chat_array['date'] = $date_request;
          //   $chat_array['status'] = $chat_message->status;
          //   $chat_array['last_message'] = $chat_message->message;
          // } else {
          //   $date_request = Helper::get_day_difference($chat->date_request);
          //   $chat_array['date'] = $date_request;
          //   $chat_array['last_message'] = 'No Message sent or recieved.';
          // }
        }
        $final_chat_array[] = $chat_array;
      }

      if (count($final_chat_array)>0) {
        $response["code"] = 200;
        $response["status"] = "success";
        $response["data"] = $final_chat_array;
      } else{
        $response["code"] = 404;
        $response["status"] = "error";
        $response["message"] = "chat unavailable.";
      }
    } else {
      $response["code"] = 404;
      $response["status"] = "error";
      $response["message"] = "Enter All Fields.";
    }

    return response()
    ->json(array( 'status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
    ->header('Content-Type', 'application/json');
  }
  /*** CHAT HEADS ***/

  /*** CHAT MESSAGES ***/
  public function user_chat(Request $req){
    if (isset($req->requestType)) {
      $request_type = $req->requestType;
      switch ($request_type) {
        case "startChat":
          if(isset($req->users_customers_id) && isset($req->other_users_customers_id)){
            $check_request = DB::table('chat_list')->where([ ['sender_id', $req->users_customers_id], ['receiver_id', $req->other_users_customers_id]])->orWhere([ ['sender_id', $req->other_users_customers_id], ['receiver_id', $req->users_customers_id]])->count();
            if($check_request > 0){
              $response["code"] = 200;
              $response["status"] = "success";
              $response["message"] = 'chat already started';    
            } else {
              $data_save = array(
                  'sender_id'=> $req->users_customers_id,
                  'receiver_id'=> $req->other_users_customers_id,
                  'date_request'=> date('Y-m-d'),
                  'created_at' => Carbon::now()
              );
              $requestSend = DB::table('chat_list')->insert($data_save);
              
              if($requestSend){
                  $response["code"] = 200;
                  $response["status"] = "success";
                  $response["message"] = 'chat started';
                } else {
                  $response["code"] = 404;
                  $response["status"] = "error";
                  $response["message"] = 'Error in starting chat';
                }
            }
          } else {
            $response["code"] = 404;
            $response["status"] = "error";
            $response["message"] = 'All fields are required';      
          }
        break;   
        
        case "sendMessage":
          if(isset($req->users_customers_id) && isset($req->other_users_customers_id) && isset($req->content) && isset($req->messageType)){
            $message_details = array(
              'sender_id'=> $req->users_customers_id,
              'receiver_id'=> $req->other_users_customers_id,
              'sender_type'=> $req->sender_type,
              'message'=>  $req->content,//json_encode($req->content) ,
              'message_type'=> $req->messageType,
              'send_date'=> date('Y-m-d'),
              'send_time'=> date('H:i:s'),
              'created_at'=> date('Y-m-d H:i:s'),
              'status'=> 'Unread'
            );

            $insertedId = DB::table('chat_messages')->insertGetId($message_details);
            if ($insertedId) {
              $notif_receiver = DB::table('users_customers')->where('users_customers_id', $req->other_users_customers_id)->first();

              if ($notif_receiver->notifications == 'Yes') {
                $notif_sender   = DB::table('users_customers')->where('users_customers_id', $req->users_customers_id)->first();
                $sender_name    = empty($notif_sender->last_name) 
                                  ? $notif_sender->first_name
                                  : $notif_sender->first_name.' '.$notif_sender->last_name;

                /* notification */
                $msg_text        = $sender_name.' sent you a new message.';
                $one_signal_id   = $notif_receiver->one_signal_id;
                $type            = 'Message Received';
                $message         = $msg_text;
                $notif_pushed    = $this->pnc->push_notification($one_signal_id, $type, $message);

                $dataInsert = array(
                  'users_type'      => 'User',
                  'senders_id'      => $req->users_customers_id,
                  'receivers_id'    => $req->other_users_customers_id,
                  'message'         => 'A new message has been recieved.',
                  'date_added'      => date('Y-m-d H:i:s'),
                  'date_modified'   => date('Y-m-d H:i:s')
                );
                $this->send_notification($dataInsert);
                /* notification */
              }

              $messageDetails =  DB::table('chat_messages')->where('chat_message_id', $insertedId)->first();
              $messageDetails->message = json_decode($messageDetails->message);
              if($messageDetails->message_type == 'attachment'){
                $messageDetails->message = config('base_urls.chat_attachments_base_url').$messageDetails->message;
              }

              $response["code"] = 200;
              $response["status"] = "success";
              $response["message"] = 'Message sent successfully.';  
            } else {
              $response["code"] = 404;
              $response["status"] = "error";
              $response["message"] = 'Oops! Something went wrong.';  
            }
          } else {
            $response["code"] = 404;
            $response["status"] = "error";
            $response["message"] = 'All fields are required';  
          }
        break;
                                       
        case "getMessages":
          if(isset($req->users_customers_id) && isset($req->other_users_customers_id)){
            $chat_array   = array();
            $day_array    = array();
            $result       = DB::table('chat_messages')->where([['sender_id',$req->other_users_customers_id], ['receiver_id', $req->users_customers_id]])->update(array('status' => 'Read'));  
            
            $all_chat = DB::table('chat_messages')
                        ->where([
                          ['sender_id',$req->users_customers_id],
                          ['receiver_id',$req->other_users_customers_id]
                        ])
                        ->orWhere([
                          ['sender_id',$req->other_users_customers_id], 
                          ['receiver_id',$req->users_customers_id]
                        ])
                        ->orderBy('chat_message_id','ASC')
                        ->get();

            if(sizeof($all_chat) > 0){
              foreach($all_chat as $key => $chat){
                $get_data['sender_type'] = $chat->sender_type;

                $chat->message = $chat->message; //json_decode($chat->message);
                $day = Helper::get_day_difference($chat->send_date);

                if (in_array($day, $day_array, TRUE)) {
                  $get_data['date']= '';
                } else {
                  array_push($day_array, $day);
                  $get_data['date']= $day;
                } 
                
                $get_data['time']    =  date('h:i A',strtotime($chat->send_time));
                $get_data['msgType'] = $chat->message_type;

                if ($chat->message_type=='attachment') {
                  $attachment = config('base_urls.chat_attachments_base_url') . $chat->message;
                  $get_data['message'] = $attachment;
                } else {
                  $get_data['message'] = $chat->message;
                }
                $sender_data = DB::table('users_customers')->where('users_customers_id',$chat->sender_id)->first();
                $get_data['user_data'] = $sender_data;
              
                array_push($chat_array, $get_data);
                
                if (!empty($chat_array) ) {
                  $result =  DB::table('chat_messages')->where([
                    ['sender_id',$req->other_users_customers_id],
                    ['receiver_id',$req->users_customers_id]
                  ])->update(array('status'=>'Read'));
                }
              }

              if($chat_array){
                $response["code"] = 200;
                $response["status"] = "success";
                $response["data"] = $chat_array; 
              } else {
                $response["code"] = 404;
                $response["status"] = "error";
                $response["message"] = 'Error in chat array'; 
              }
            } else {
              $response["code"] = 404;
              $response["status"] = "error";
              $response["message"] = 'no chat history'; 
            }                       
          } else {
            $response["code"] = 404;
            $response["status"] = "error";
            $response["message"] = 'All fields are needed'; 
          }
        break;

        case "updateMessages":
          if(isset($req->users_customers_id) && isset($req->other_users_customers_id)){
            $user_id = $req->users_customers_id;
            $other_user_id  = $req->other_users_customers_id;
            $chat_array =array();
  
            $all_chat =  DB::table('chat_messages')
              ->where([['sender_id', $other_user_id], ['receiver_id',$user_id],['status','Unread']])
              ->orderBy('chat_message_id', 'ASC')->get();
            
            if(sizeof($all_chat) > 0){
              foreach($all_chat as $chat){
                $get_data['chat_message_id'] = $chat->chat_message_id;
                $get_data['sender_type'] = $chat->sender_type;

                $chat->message = json_decode($chat->message);                
                $get_data['time'] =  date('h:i A',strtotime($chat->send_date));
                $get_data['msgType'] = $chat->message_type;
                if($chat->message_type =='attachment'){
                  $image = config('base_urls.chat_attachments_base_url') . $chat->message;
                  $get_data['message'] = $image;
                } else { 
                  $get_data['message'] = $chat->message;
                } 

                $sender_data = DB::table('users_customers')->where('users_customers_id',$req->other_users_customers_id)->get();
                $get_data['users_data'] = $sender_data[0];
                array_push($chat_array, $get_data);
              }
               
              if(!empty($chat_array)){
                $result =  DB::table('chat_messages')->where([
                  ['sender_id',$other_user_id],
                  ['receiver_id',$user_id]
                  ])->update(array('status'=>'Read'));
              }
                         
              $chat_length   =  DB::table('chat_messages')->where([
                ['sender_id', $user_id],
                ['receiver_id',$other_user_id]
                ])->orWhere([
                    ['sender_id', $other_user_id],
                ['receiver_id',$user_id]
              ])->orderBy('chat_messages_id','ASC')->count();
            
              $finalDataset = array(
                  "chat_length" => $chat_length,
                  "unread_messages" => $chat_array,
              );

              $response["code"] = 200;
              $response["status"] = "success";
              $response["data"] = $finalDataset; 
            } else {
              $response["code"] = 404;
              $response["status"] = "error";
              $response["message"] = "no chat found"; 
            }
          } else {
            $response["code"] = 404;
            $response["status"] = "error";
            $response["message"] = "All fields are needed"; 
          }
        break;    
      }
    } else {
      $response["code"] = 404;
      $response["status"] = "error";
      $response["message"] = "Request type not Found"; 
    }

    return response()
     ->json(array( 'status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
     ->header('Content-Type', 'application/json');
  }
  /*** CHAT MESSAGES ***/

  /* EMAIL EXIST API */
  public function email_exist(Request $req){
    if (isset($req->email)) {
      $email=DB::table('users_customers')->where('email', $req->email)->first();
      if ($email) {
        $response["code"] = 200;
        $response["status"] = "error";
        $response["message"]  ="Email already exists.";
      }else{
        $response["code"] = 404;
        $response["status"] = "success";
        $response["message"] = "Email does not exists.";
      }
    }else{ 
      $response["code"] = 404;
      $response["status"] = "error";
      $response["message"] = "Please enter email address.";
    }
    
    return response()
      ->json(array( 'status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
      ->header('Content-Type', 'application/json');
  }
  /* EMAIL EXIST API */

  /* GET ALL USER */
  public function all_users(Request $req){
  	if(isset($req->users_customers_id)){
	  	$fetch_data   =  DB::table('users_customers')
	  	->where('users_customers_id','!=',$req->users_customers_id)
	  	->where('status','Active')->get();
	    
	    if (count($fetch_data)>0) {
	      $response["code"] = 200;
	      $response["status"] = "success";
	      $response["data"] = $fetch_data;
	    } else {
	      $response["code"] = 404;
	      $response["status"] = "error"; 
	      $response["message"] = "no data found.";
	    }
    } else {
      $response["code"] = 404;
      $response["status"] = "error";
      $response["message"] = "All fields are required!"; 
    }
    return response()
    ->json(array( 'status' => $response["status"],isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
      ->header('Content-Type', 'application/json');
  }
  /* GET ALL USER */

  /* GET ALL USER SUGGESTED */
  public function all_users_suggested(Request $req){
    if(isset($req->email)){
      $fetch_data   =  DB::table('users_customers')
      ->where('email','Like', "%" . $req->email. "%")
      ->where('status','Active')->get();
      
      if (count($fetch_data)>0) {
        foreach($fetch_data as $data) {
          $users_customers_wallets = DB::table('users_customers_wallets')
                                    ->where([
                                      ['users_customers_id', $data->users_customers_id], 
                                      ['status', 'Active']
                                    ])
                                    ->get();

          foreach($users_customers_wallets as $wallet) {
            $wallet->system_currencies = DB::table('system_currencies')->where('system_currencies_id', $wallet->system_currencies_id)->first();
          }
          $data->users_customers_wallets = $users_customers_wallets;
        }
        $response["code"] = 200;
        $response["status"] = "success";
        $response["data"] = $fetch_data;
      } else {
        $response["code"] = 404;
        $response["status"] = "error"; 
        $response["message"] = "no data found.";
      }
    } else {
      $response["code"] = 404;
      $response["status"] = "error";
      $response["message"] = "All fields are required!"; 
    }
    return response()
    ->json(array( 'status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
      ->header('Content-Type', 'application/json');
  }
  /* GET ALL USER SUGGESTED */

  /* USER TRIGGER NOTIFICATION PERMISSION */
  public function notification_permission(Request $req){
  	if (isset($req->users_customers_id)) {
  		$userId=['users_customers_id'=>$req->users_customers_id,'status'=>'Active'];
      $user=DB::table('users_customers')->where($userId)->first();
        if ($user) {
	    	if($user->notifications=="Yes"){
	    		$saveData['notifications'] ='No';
	    		$users_customers_id   = DB::table('users_customers')->where($userId)->update($saveData);
	    		$data=DB::table('users_customers')->where($userId)->first();
	    		  $response["code"] = 200;
			      $response["status"] = "success";
			      $response["data"] = $data;
	    	}else{
	    		$saveData['notifications'] ='Yes';
	    		$users_customers_id   = DB::table('users_customers')->where($userId)->update($saveData);
	    		$data=DB::table('users_customers')->where($userId)->first();
	    		  $response["code"] = 200;
			      $response["status"] = "success";
			      $response["data"] = $data;
	    	}
	    }else{
	      $response["code"] = 404;
	      $response["status"] = "error";
	      $response["message"] = "User does not exists.";
	    }
    }else{ 
      $response["code"] = 404;
      $response["status"] = "error";
      $response["message"] = "All fields are needed.";
    }
    return response()
    ->json(array( 'status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
      ->header('Content-Type', 'application/json');
  }
  /* USER TRIGGER NOTIFICATION PERMISSION */

  /* ALL CURRENCIES */
  public function all_currencies(){
    $fetch_data   =  SystemCurrency::with('country')->where('status', 'Active')->get();
    
    if (count($fetch_data)>0) {
      $response["code"] = 200;
      $response["status"] = "success";
      $response["data"] = $fetch_data;
    } else {
      $response["code"] = 404;
      $response["status"] = "error";
      $response["message"] = "no data found.";
    }
    return response()
      ->json(array( 'status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
      ->header('Content-Type', 'application/json');
  }
  /* ALL CURRENCIES */

  /* GET CURRENCIES BY ID */
  public function get_currencies_by_id(Request $req){
    if (isset($req->system_currencies_id)){
      $fetch_data   =  DB::table('system_currencies')->where('system_currencies_id', $req->system_currencies_id)->get();
      
      if (!empty($fetch_data)) {
        $response["code"] = 200;
        $response["status"] = "success";
        $response["data"] = $fetch_data;
      } else {
        $response["code"] = 404;
        $response["status"] = "error";
        $response["message"] = "no data found.";
      }
    } else {
      $response["code"] = 404;
      $response["status"] = "error";
      $response["message"] = "All fields are needed.";
    }
    return response()
      ->json(array( 'status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
      ->header('Content-Type', 'application/json');
  }
  /* GET CURRENCIES BY ID */

  /* ALL COUNTRIES */
  public function all_countries(){
    $fetch_data   =  DB::table('system_countries')->get();
    
    if (!empty($fetch_data)) {
      $response["code"] = 200;
      $response["status"] = "success";
      $response["data"] = $fetch_data;
    } else {
      $response["code"] = 404;
      $response["status"] = "error";
      $response["message"] = "no data found.";
    }
    return response()
      ->json(array( 'status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
      ->header('Content-Type', 'application/json');
  }
  /* ALL COUNTRIES */

  /* CREATE WALLET */
  public function create_wallet(Request $req){
    if (isset($req->users_customers_id) && isset($req->system_currencies_id)) {
      $userId=['users_customers_id'=>$req->users_customers_id];
      $user=DB::table('users_customers')->where($userId)->first();
      if ($user->status == 'Active') {
        $system_currency=DB::table('system_currencies')->where('system_currencies_id',$req->system_currencies_id)->first();
        if ($system_currency) {
          $wallet = UsersCustomersWallet::firstOrCreate(
                    ['users_customers_id' => $req->users_customers_id,'system_currencies_id'=>$req->system_currencies_id],
                    ['users_customers_id' => $req->users_customers_id,'system_currencies_id'=>$req->system_currencies_id]
                );
          $data=UsersCustomersWallet::with('currency')->where(['users_customers_id' => $req->users_customers_id,'system_currencies_id'=>$req->system_currencies_id])->first();

            $response["code"] = 200;
            $response["status"] = "success";
            $response["data"] = $data;
        }else{
          $response["code"] = 404;
          $response["status"] = "error";
          $response["message"] = "Currency does not exist.";
        }
      }else{
        $response["code"] = 404;
        $response["status"] = "error";
        $response["message"] = "Your account is in ".$user->status." status. Please contact admin.";
      }
    }else{ 
      $response["code"] = 404;
      $response["status"] = "error";
      $response["message"] = "All fields are needed.";
    }
    return response()
    ->json(array( 'status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
      ->header('Content-Type', 'application/json');
  }
  /* CREATE WALLET */

  /* GET WALLET */
  public function get_wallet(Request $req){
    if (isset($req->users_customers_id)) {
      $fetch_data = UsersCustomersWallet::with('currency')->where('users_customers_id',$req->users_customers_id)->get();
    
      if (!empty($fetch_data)) {
        $response["code"] = 200;
        $response["status"] = "success";
        $response["data"] = $fetch_data;
      } else {
        $response["code"] = 404;
        $response["status"] = "error";
        $response["message"] = "no data found.";
      }
    }else{ 
      $response["code"] = 404;
      $response["status"] = "error";
      $response["message"] = "All fields are needed.";
    }

    return response()
    ->json(array( 'status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
      ->header('Content-Type', 'application/json');
  }
  /* GET WALLET */

  /* GET CURRENCY CONVERTER */
  public function currency_converter(Request $req){
    if (isset($req->sender_currency_id) && isset($req->receiver_currency_id) && isset($req->from_amount)) {
      
      $sender_currency    = DB::table('system_currencies')->where('system_currencies_id', $req->sender_currency_id)->get()->first();
      $receiver_currency  = DB::table('system_currencies')->where('system_currencies_id', $req->receiver_currency_id)->get()->first();

      $base_currency   = $sender_currency->code; 
      $req_url         = "https://api.exchangerate-api.com/v4/latest/$base_currency";
      $response_json   = file_get_contents($req_url, false, stream_context_create(['ssl' => ['verify_peer' => false, 'verify_peer_name' => false]]));
      $response        = json_decode($response_json, true); 

      if ($response && isset($response['rates'])) {
        $to_currency_code    = $receiver_currency->code; 
        $amount_to_convert   = $req->from_amount; 

        // calculate converted amount
        $temp_converted_rate = $response['rates'][$to_currency_code];

        $from_amount        = $req->from_amount;
        $converted_amount   = $temp_converted_rate * $req->from_amount;
        $converted_rate     = $temp_converted_rate;

        $response["code"] = 404;
        $response["status"] = "success";
        $response['data'] = array('from_amount' => $from_amount, 
                                  'converted_rate' => $converted_rate, 
                                  'converted_amount' => $converted_amount
                                );
      } else {
        $response["code"] = 404;
        $response["status"] = "error";
        $response["message"] = "Unable to fetch exchange rates.";
      }   
    } else { 
      $response["code"] = 404;
      $response["status"] = "error";
      $response["message"] = "All fields are needed.";
    }

    return response()
    ->json(array( 'status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
      ->header('Content-Type', 'application/json');
  }
  /* GET CURRENCY CONVERTER */


  /* TRANSFER CURRENCY */
  public function transfer_currency(Request $req){ 
    if (isset($req->from_users_customers_id) && isset($req->from_system_currencies_id) && isset($req->from_amount) && isset($req->to_users_customers_id) && isset($req->to_system_currencies_id) && isset($req->payment_method_id) && isset($req->system_currencies_id)) {
      $sender = DB::table('users_customers')->where('users_customers_id',$req->from_users_customers_id)->first();
     
      if ($sender->status == 'Active') {
        $receiver = DB::table('users_customers')
                    ->where('users_customers_id', $req->to_users_customers_id)
                    ->orWhere('email',$req->to_users_customers_id)
                    ->first();
        
        if ($receiver->status == 'Active') {
          $sender_currency = DB::table('system_currencies')->where('system_currencies_id',$req->from_system_currencies_id)->first();

          if ($sender_currency) {
            $receiver_currency = DB::table('system_currencies')->where('system_currencies_id',$req->to_system_currencies_id)->first();
           
            if ($receiver_currency) {
              $sender_wallet = UsersCustomersWallet::where([
                                'users_customers_id' => $req->from_users_customers_id,
                                'system_currencies_id'=>$req->from_system_currencies_id
                              ])->first();

              if ($sender_wallet) {
                  $receiver_wallet = UsersCustomersWallet::where([
                                      'users_customers_id' => $req->to_users_customers_id,
                                      'system_currencies_id'=>$req->to_system_currencies_id
                                    ])->first();

                  while(!$receiver_wallet) {
                    $wallet = UsersCustomersWallet::firstOrCreate(
                                ['users_customers_id' => $req->to_users_customers_id,
                                'system_currencies_id'=>$req->to_system_currencies_id],
                                ['users_customers_id' => $req->to_users_customers_id,
                                'system_currencies_id'=>$req->to_system_currencies_id
                              ]);
                  }
                  $system_currencies = DB::table('system_currencies')->where('system_currencies_id',$req->system_currencies_id)->first();

                  if ($sender_wallet->wallet_amount >= $req->from_amount) {
                    $base_currency   = $sender_currency->code; 
                    $req_url         = "https://api.exchangerate-api.com/v4/latest/$base_currency";
                    $response_json   = file_get_contents($req_url, false, stream_context_create(['ssl' => ['verify_peer' => false, 'verify_peer_name' => false]]));

                    if (false !== $response_json) {
                      try {
                        $url_response = json_decode($response_json);

                        // Check if the API response is valid and contains the required rates
                        if (isset($url_response->rates) && isset($url_response->rates->{$system_currencies->code})) {
                          $conversion_rate      = $url_response->rates->{$system_currencies->code}; // Conversion rate for the target currency
                          $sender_amount_result = $req->from_amount * $conversion_rate; // Convert the amount

                          $admin_share_amount = $sender_amount_result * $system_currencies->margin; // Calculate the admin share

                          if ($sender_amount_result > $admin_share_amount) {
                            $sender_converted_amount = $sender_amount_result - $admin_share_amount; // Final amount after admin share
                            $converted_rate          = $conversion_rate; // The rate used for conversion
                          } else {
                            $response["code"] = 404;
                            $response["status"] = "error";
                            $response["message"] = "Transfer fee greater than transfer amount";
                          }
                        } else {
                          $response["code"] = 404;
                          $response["status"] = "error";
                          $response["message"] = "Conversion rate not available for the selected currency.";
                        }
                      } catch (Exception $e) {
                        // Handle JSON parse error...
                        $response["code"] = 404;
                        $response["status"] = "error";
                        $response['message'] = $e->getMessage();
                      }
                    } else {
                      $response["code"] = 404;
                      $response["status"] = "error";
                      $response["message"] = "Something went wrong while fetching the exchange rates.";
                    }
                    
                    $base_currency = $system_currencies->code;
                    $req_url = "https://api.exchangerate-api.com/v4/latest/$base_currency";
                    $response_json = file_get_contents($req_url, false, stream_context_create(['ssl' => ['verify_peer' => false, 'verify_peer_name' => false]]));

                    if (false !== $response_json) {
                      try {
                        $url_response = json_decode($response_json);

                        // Check if the API response is valid and contains the required rates
                        if (isset($url_response->rates) && isset($url_response->rates->{$receiver_currency->code})) {
                          $conversion_rate = $url_response->rates->{$receiver_currency->code}; // Conversion rate for the receiver's currency
                          $receiver_amount_result = $sender_converted_amount * $conversion_rate; // Convert the sender's converted amount to receiver's currency
                        } else {
                          $response["code"] = 404;
                          $response["status"] = "error";
                          $response["message"] = "Conversion rate not available for the receiver's currency.";
                        }
                      } catch (Exception $e) {
                        // Handle JSON parse error...
                        $response["code"] = 404;
                        $response["status"] = "error";
                        $response['message'] = $e->getMessage();
                      }
                    } else {
                      $response["code"] = 404;
                      $response["status"] = "error";
                      $response["message"] = "Something went wrong while fetching the exchange rates.";
                    }

                    //GET BASE CURRENCY CONVERSION
                    // admin_share
                    $admin_share = DB::table('system_settings')->where('type', 'admin_share')->first()->description;
                    // $admin_share_amount = round((($converted_amount*$admin_share)/100),2);
                    // $admin_share_amount = 0;
                    $sender_amount          = $sender_wallet->wallet_amount - $req->from_amount;
                    $receiver_amount        = $receiver_wallet->wallet_amount + $receiver_amount_result;
                    $receiver_amount_txns   = $receiver_amount_result;
                    // admin_share
                    
                    // $data=[
                    //   'margin' => $receiver_currency->margin,
                    //   'sender_converted_amount' => $sender_converted_amount,
                    //   'receiver_amount' => $receiver_amount,
                    //   'receiver_amount_txns' => $receiver_amount_txns,
                    //   'from_amount' => $req->from_amount,
                    // ];
                    $sender_wallet_updated = UsersCustomersWallet::where([
                                                'users_customers_id' => $req->from_users_customers_id,
                                                'system_currencies_id'=>$req->from_system_currencies_id
                                              ])->update([
                                                "wallet_amount"=>$sender_amount
                                              ]);

                    $receiver_wallet_updated = UsersCustomersWallet::where([
                                                'users_customers_id' => $req->to_users_customers_id,
                                                'system_currencies_id'=>$req->to_system_currencies_id
                                              ])->update([
                                                "wallet_amount"=>$receiver_amount
                                              ]);

                    $data=UsersCustomersTxns::create([   
                      "from_users_customers_id"   => $req->from_users_customers_id,
                      "from_system_currencies_id" => $req->from_system_currencies_id,
                      "from_amount"               => $req->from_amount, 
                      "to_users_customers_id"     => $req->to_users_customers_id,
                      "to_system_currencies_id"   => $req->to_system_currencies_id,
                      "to_amount"                 => $receiver_amount_txns, 
                      "payment_method_id"         => $req->payment_method_id,
                      "admin_share"               => $admin_share, 
                      "admin_share_amount"        => $admin_share_amount, 
                      // "system_countries_id"       => $req->system_countries_id,          
                      "system_currencies_id"      => $req->system_currencies_id,          
                      "base_amount"               => $sender_amount_result,        
                      'status'                    => "Pending",
                    ]);
                    if (isset($req->system_countries_id)) {
                      $data["system_countries_id"] = $req->system_countries_id;       
                    }

                    if ($sender_wallet_updated && $receiver_wallet_updated) {
                      if ($receiver->notifications == 'Yes') {
                        $amount    = $receiver_currency->symbol.''.$receiver_amount_txns;
                        $country   = DB::table('system_countries')->select('code')
                                    ->where('system_countries_id', $receiver_currency->system_countries_id)
                                    ->first()->code;     
                                    
                        $sender_name = empty($sender->last_name) 
                                        ? $sender->first_name
                                        : $sender->first_name.' '.$sender->last_name;

                        /* notification */
                        $msg_text        = 'transferred '.$amount.' in your '.$country.' wallet.';
                        $one_signal_id   = $receiver->one_signal_id;
                        $type            = 'Currency Received';
                        $message         = $sender_name.' '.$msg_text ;
                        $notif_pushed    = $this->pnc->push_notification($one_signal_id, $type, $message);

                        $dataInsert = array(
                          'users_type'      => 'User',
                          'senders_id'      => $sender->users_customers_id,
                          'receivers_id'    => $receiver->users_customers_id,
                          'message'         => $msg_text,
                          'date_added'      => date('Y-m-d H:i:s'),
                          'date_modified'   => date('Y-m-d H:i:s')
                        );
                        $this->send_notification($dataInsert);
                        /* notification */
                      }
                    }
              
                    $response["code"] = 200;
                    $response["status"] = "success";
                    $response["data"] = $data;
                  }else{
                    $response["code"] = 404;
                    $response["status"] = "error";
                    $response["message"] = "You have not sufficient amount in your wallet to transfer.";
                  } 
              }else{
                $response["code"] = 404;
                $response["status"] = "error";
                $response["message"] = "You have no wallet with this currency";
              }
            }else{
              $response["code"] = 404;
              $response["status"] = "error";
              $response["message"] = "Receiver Currency does not exists.";
            }  
          }else{
            $response["code"] = 404;
            $response["status"] = "error";
            $response["message"] = "Your Currency does not exists.";
          }
        }else{
          $response["code"] = 404;
          $response["status"] = "error";
          $response["message"] = "Your account is in ".$receiver->status." status. Please contact admin.";
        }  
      }else{
        $response["code"] = 404;
        $response["status"] = "error";
        $response["message"] = "Your account is in ".$sender->status." status. Please contact admin.";
      }
    }else{ 
      $response["code"] = 404;
      $response["status"] = "error";
      $response["message"] = "All fields are needed.";
    }
    return response()
    ->json(array( 'status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
    ->header('Content-Type', 'application/json');
  }
  /* TRANSFER CURRENCY */

  /* GET ALL USER transactions */
  public function all_transactions(Request $req){
    if(isset($req->users_customers_id)){
      //$fetch_data   =  UsersCustomersTxns::where('status','Approved')->where('from_users_customers_id',$req->users_customers_id)->orWhere('to_users_customers_id',$req->users_customers_id)
      $fetch_data   =  UsersCustomersTxns::where('from_users_customers_id',$req->users_customers_id)->orWhere('to_users_customers_id',$req->users_customers_id)->orderBy('users_customers_txns_id','DESC')->get();
      $get_data=[];
      foreach ($fetch_data as $key => $data) {
        $from_system_currencies = DB::table('system_currencies')->where('system_currencies_id',$data->from_system_currencies_id)->first();
        if (!empty($from_system_currencies)) {
          $data->from_system_currencies = $from_system_currencies->symbol;
        }

        $to_system_currencies = DB::table('system_currencies')->where('system_currencies_id',$data->to_system_currencies_id)->first();
        if (!empty($to_system_currencies)) {
          $data->to_system_currencies = $to_system_currencies->symbol;
        }
        
        if($data->to_users_customers_id==$req->users_customers_id){
          $data->from_users_customers=DB::table('users_customers')->where('users_customers_id', $data->from_users_customers_id)->first();
        }
        if($data->from_users_customers_id==$req->users_customers_id){
          $data->to_users_customers=DB::table('users_customers')->where('users_customers_id', $data->to_users_customers_id)->first();
        }
        $get_data[]=$data;
      }
      if (count($get_data)>0) {
        $response["code"] = 200;
        $response["status"] = "success";
        $response["data"] = $get_data;
      } else {
        $response["code"] = 404;
        $response["status"] = "error"; 
        $response["message"] = "No transactions available.";
      }
    } else {
      $response["code"] = 404;
      $response["status"] = "error";
      $response["message"] = "All fields are required!"; 
    }
    return response()
    ->json(array( 'status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
      ->header('Content-Type', 'application/json');
  }
  /* GET ALL USER transactions */

  /* SWAP WALLET AMOUNT*/
  public function wallet_swap(Request $req){
    if (isset($req->users_customers_id) && isset($req->from_users_customers_wallets_id) && isset($req->amount_from) && isset($req->to_users_customers_wallets_id) && isset($req->system_currencies_id)) 
    {
      $user=DB::table('users_customers')->where(['users_customers_id'=>$req->users_customers_id,'status'=>'Active'])->first();
      if ($user) 
      {
        $sender_wallet = UsersCustomersWallet::where([
                          'users_customers_id' => $req->users_customers_id,
                          'users_customers_wallets_id'=>$req->from_users_customers_wallets_id
                        ])->with('currency')->first();
        if($sender_wallet)
        {
          $receiver_wallet = UsersCustomersWallet::where([
                              'users_customers_id' => $req->users_customers_id,
                              'users_customers_wallets_id'=>$req->to_users_customers_wallets_id
                            ])->with('currency')->first();
          if($receiver_wallet)
          {
            $system_currencies = DB::table('system_currencies')->where('system_currencies_id',$req->system_currencies_id)->first();
            if($sender_wallet->wallet_amount != 0 && $sender_wallet->wallet_amount >= $req->amount_from){
              $sender_currency_code = $sender_wallet->currency->code;

              $base_currency   = $sender_currency_code; 
              $req_url         = "https://api.exchangerate-api.com/v4/latest/$base_currency";
              $response_json   = file_get_contents($req_url, false, stream_context_create(['ssl' => ['verify_peer' => false, 'verify_peer_name' => false]]));

              if (false !== $response_json) {
                try {
                  $url_response = json_decode($response_json);

                  // check if API response contains required rates
                  if (isset($url_response->rates) && isset($url_response->rates->{$system_currencies->code})) {
                    $conversion_rate = $url_response->rates->{$system_currencies->code};

                    // calculate converted amount
                    $sender_amount_result = $req->amount_from * $conversion_rate; 

                    // calculate admin share based on margin
                    $admin_share_amount = $sender_amount_result * $system_currencies->margin; 

                    if ($sender_amount_result > $admin_share_amount) {
                      $sender_converted_amount   = $sender_amount_result - $admin_share_amount; 
                      $converted_rate            = $conversion_rate; 
                    } else {
                      return response()->json([
                        "status" => "error",
                        "message" => "Transfer fee greater than transfer amount"
                      ])->header('Content-Type', 'application/json');
                    }
                  } else {
                    return response()->json([
                      "status" => "error",
                      "message" => "Conversion rate not available for the selected currency."
                    ])->header('Content-Type', 'application/json');
                  }
                } catch (Exception $e) {
                  // Handle JSON parse error...
                  return response()->json([
                    "code" => 404,
                    "status" => "error",
                    "message" => $e->getMessage()
                  ])->header('Content-Type', 'application/json');
                }
              } else {
                return response()->json([
                  "code" => 404,
                  "status" => "error",
                  "message" => "Something went wrong while fetching the exchange rates."
                ])->header('Content-Type', 'application/json');
              }

              // admin_share
              $base_currency            = $system_currencies->code; 
              $receiver_currency_code   = $receiver_wallet->currency->code; 
              $req_url                  = "https://api.exchangerate-api.com/v4/latest/$base_currency";
              $response_json            = file_get_contents($req_url, false, stream_context_create(['ssl' => ['verify_peer' => false, 'verify_peer_name' => false]]));

              if (false !== $response_json) {
                try {
                  $url_response = json_decode($response_json);

                  // check if API response contains required rates
                  if (isset($url_response->rates) && isset($url_response->rates->{$receiver_currency_code})) {
                    $conversion_rate = $url_response->rates->{$receiver_currency_code}; 

                    // calculate converted amount for receiver
                    $receiver_amount_result = $sender_converted_amount * $conversion_rate;  
                  } else {
                    return response()->json([
                      "status" => "error",
                      "message" => "Conversion rate not available for the receiver's currency."
                    ])->header('Content-Type', 'application/json');
                  }
                } catch (Exception $e) {
                  // Handle JSON parse error...
                  return response()->json([
                    "code" => 404,
                    "status" => "error",
                    "message" => $e->getMessage()
                  ])->header('Content-Type', 'application/json');
                }
              } else {
                return response()->json([
                  "code" => 404,
                  "status" => "error",
                  "message" => "Something went wrong while fetching the exchange rates."
                ])->header('Content-Type', 'application/json');
              }

              $admin_share = DB::table('system_settings')->where('type', 'admin_share')->first()->description;
              //GET BASE CURRENCY CONVERSION
              $sender_amount          = $sender_wallet->wallet_amount - $req->amount_from;
              $receiver_amount        = $receiver_wallet->wallet_amount + $receiver_amount_result;
              $receiver_amount_txns   = $receiver_amount_result;

              $sender_wallet_update = UsersCustomersWallet::where([
                'users_customers_id' => $req->users_customers_id,
                'users_customers_wallets_id'=>$req->from_users_customers_wallets_id
              ])->update([
                "wallet_amount"=>$sender_amount
              ]);

              $receiver_wallet_updated=UsersCustomersWallet::where([
                'users_customers_id' => $req->users_customers_id,
                'users_customers_wallets_id'=>$req->to_users_customers_wallets_id
              ])->update([
                "wallet_amount"=>$receiver_amount
              ]);

              $data=SwapWallet::create([   
                "users_customers_id"              => $req->users_customers_id,
                "from_users_customers_wallets_id" => $req->from_users_customers_wallets_id,
                "to_users_customers_wallets_id"   => $req->to_users_customers_wallets_id,
                "amount_from"                     => $req->amount_from, 
                "amount_to"                       => $receiver_amount_txns, 
                "exchange_rate"                   => $converted_rate, 
                "admin_share"                     => $admin_share, 
                "admin_share_amount"              => $admin_share_amount,          
                "system_currencies_id"            => $req->system_currencies_id,          
                "base_amount"                     => $sender_amount_result,        
                "status"                          => "Successful",
              ]);

              $response["code"] = 200;
              $response["status"] = "success";
              $response["data"] = $data;
            }else{
              $response["code"] = 404;
              $response["status"] = "error";
              $response["message"] = "You have not sufficient amount in your wallet to transfer.";
            }
          }else{
            $response["code"] = 404;
            $response["status"] = "error";
            $response["message"] = "Wallet not exist.";
          }    
        }else{
          $response["code"] = 404;
          $response["status"] = "error";
          $response["message"] = "Wallet not exist.";
        }  
      }else{
        $response["code"] = 404;
        $response["status"] = "error";
        $response["message"] = "Your account is in ".$user->status." status. Please contact admin.";
      }
    }else{ 
      $response["code"] = 404;
      $response["status"] = "error";
      $response["message"] = "All fields are needed.";
    }
    return response()
    ->json(array( 'status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
      ->header('Content-Type', 'application/json');
  }
  /* SWAP WALLET AMOUNT*/

  /* SWAP OFFER*/
  public function swap_offer(Request $req){
    if (isset($req->users_customers_id) && isset($req->from_system_currencies_id) && isset($req->to_system_currencies_id) && isset($req->from_amount) && isset($req->exchange_rate) && isset($req->system_currencies_id) && isset($req->expiry_time)) 
    {
      $user=DB::table('users_customers')->where(['users_customers_id'=>$req->users_customers_id,'status'=>'Active'])->first();
      if ($user) 
      {
        $sender_currency = UsersCustomersWallet::where([
            'users_customers_id' => $req->users_customers_id,
            'system_currencies_id'=>$req->from_system_currencies_id
          ])->with('currency')->first();
        if($sender_currency)
        {
          $receiver_currency= SystemCurrency::where('system_currencies_id',$req->to_system_currencies_id)->first();
          if($receiver_currency){
            if($sender_currency->wallet_amount != 0 && $sender_currency->wallet_amount >= $req->from_amount){

              $temp_converted_rate     = $req->exchange_rate - $receiver_currency->margin;

              $converted_amount   = $temp_converted_rate * $req->from_amount;
              // $converted_rate   = number_format($temp_converted_rate,2);
              $converted_rate   = $temp_converted_rate;
                      

              // admin_share
              $admin_share = DB::table('system_settings')->where('type', 'admin_share')->first()->description;
              // $admin_share_amount = round((($converted_amount*$admin_share)/100),2);
              $admin_share_amount = 0;
              $sender_amount = $sender_currency->wallet_amount - $req->from_amount;
              // $receiver_amount_txns = $converted_amount - $admin_share_amount;

              
              // admin_share
              
              //GET BASE CURRENCY CONVERSION
              $base_amount = 0;
              $system_currencies = DB::table('system_currencies')->where('system_currencies_id',$req->system_currencies_id)->first();
              $sender_currency_code=$sender_currency->currency->code;
              $req_url_base = "https://api.exchangerate.host/convert?from=$sender_currency_code&to=$system_currencies->code&amount=$req->from_amount";
              $response_json_base = file_get_contents($req_url_base, false, stream_context_create(['ssl' => ['verify_peer' => false, 'verify_peer_name' => false]]));
              if(false !== $response_json_base) {
                  try {
                      $url_response_base = json_decode($response_json_base);
                      if($url_response_base->success === true) {
                          $base_amount   = $url_response_base->result;
                      }
                  } catch(Exception $e) {
                      // Handle JSON parse error...
                    $response["code"] = 404;
                    $response["status"] = "error";
                    $response['message'] = $e->getMessage();
                  }
              }else{
                $response["code"] = 404;
                $response["status"] = "error";
                $response["message"] = "Something Wrong";
              } 
              
              // Add hours to the current time
              // $new_time = Carbon::now()->addHours($req->expiry_time);
              $save_data=[   
                "users_customers_id"              => $req->users_customers_id,
                "from_system_currencies_id"       => $req->from_system_currencies_id,
                "to_system_currencies_id"         => $req->to_system_currencies_id,
                "from_amount"                     => $req->from_amount, 
                "to_amount"                       => $converted_amount, 
                "exchange_rate"                   => $req->exchange_rate, 
                "admin_share"                     => $admin_share, 
                "admin_share_amount"              => $admin_share_amount,          
                "system_currencies_id"            => $req->system_currencies_id,          
                "base_amount"                     => $base_amount,        
                "expiry_date_time"                => $req->expiry_time, 
                'date_added'                      => Carbon::now(),      
                "status"                          => "Pending",
              ];
              $swap_offers_id   = DB::table('swap_offers')->insertGetId($save_data);
              $swap_offers_data      = DB::table('swap_offers')->where('swap_offers_id', $swap_offers_id)->first();

              $response["code"] = 200;
              $response["status"] = "success";
              $response["data"] = $swap_offers_data;
            }else{
              $response["code"] = 404;
              $response["status"] = "error";
              $response["message"] = "You have not sufficient amount in your wallet to transfer.";
            }
          }else{
            $response["code"] = 404;
            $response["status"] = "error";
            $response["message"] = "Receiver currency not exist.";
          }    
        }else{
          $response["code"] = 404;
          $response["status"] = "error";
          $response["message"] = "You wallet with that currency not exist.";
        }  
      }else{
        $response["code"] = 404;
        $response["status"] = "error";
        $response["message"] = "Your account is in ".$user->status." status. Please contact admin.";
      }
    }else{ 
      $response["code"] = 404;
      $response["status"] = "error";
      $response["message"] = "All fields are needed.";
    }
    return response()
    ->json(array( 'status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
      ->header('Content-Type', 'application/json');
  }
  /* SWAP OFFER*/

  /* SWAP OFFER REQUEST*/
  public function swap_offer_request(Request $req){
    if (isset($req->from_users_customers_id) && isset($req->swap_offers_id)) 
    {
      $user=DB::table('users_customers')->where(['users_customers_id'=>$req->from_users_customers_id,'status'=>'Active'])->first();
      if ($user) 
      {
        $swap_offer = SwapOffer::where(['swap_offers_id'=>$req->swap_offers_id,'status'=>'Pending'])->first(); 
        if($swap_offer){
          $request_exist = DB::table('swap_offers_requests')
                            ->where([
                              ['swap_offers_id', $req->swap_offers_id], 
                              ['from_users_customers_id', $req->from_users_customers_id], 
                              ['status', 'Pending']
                            ])
                            ->count(); 

          if ($request_exist == 0) {
            $user_wallet = DB::table('users_customers_wallets')
                          ->where([
                            ['users_customers_id', $req->from_users_customers_id], 
                            ['system_currencies_id', $swap_offer->to_system_currencies_id],
                            ['status', 'Active']
                          ])
                          ->first();

            if (!empty($user_wallet)) {
              if ($user_wallet->wallet_amount >= $swap_offer->to_amount) {
                $data = [
                  "from_users_customers_id"  => $req->from_users_customers_id,
                  "swap_offers_id"           => $req->swap_offers_id,      
                  "status"                   => "Pending",
                ];
                $inserted = DB::table('swap_offers_requests')->insertGetId($data);

                $notif_receiver = DB::table('users_customers')->where('users_customers_id', $swap_offer->users_customers_id)->first();

                if ($notif_receiver->notifications == 'Yes') { 
                  /* notification */
                  $msg_text        = ucfirst($user->first_name).' sent you a request for swap offer.';
                  $one_signal_id   = $notif_receiver->one_signal_id;
                  $type            = 'Swap Offer Request';
                  $message         = $msg_text ;
                  $notif_pushed    = $this->pnc->push_notification($one_signal_id, $type, $message);

                  $dataInsert = array(
                    'users_type'      => 'User',
                    'senders_id'      => $req->from_users_customers_id,
                    'receivers_id'    => $swap_offer->users_customers_id,
                    'message'         => $msg_text,
                    'date_added'      => date('Y-m-d H:i:s'),
                    'date_modified'   => date('Y-m-d H:i:s'),
                    'status'          => 'Unread'
                  );
                  if ($data) {
                    $this->send_notification($dataInsert);
                  }
                  /* notification */
                }
                $response["code"] = 200;
                $response["status"] = "success";
                $response["data"] = $data; 
              } else {
                $response["code"] = 404;
                $response["status"] = "error";
                $response["message"] = "You don't have sufficient amount in your wallet.";
              }
            } else {
              $response["code"] = 404;
              $response["status"] = "error";
              $response["message"] = "Wallet deactivted or does not exist.";
            }
          } else {
            $response["code"] = 404;
            $response["status"] = "error";
            $response["message"] = "You've already sent request for this offer.";
          }
        }else{
          $response["code"] = 404;
          $response["status"] = "error";
          $response["message"] = "Swap offer not exist.";
        }
      }else{
        $response["code"] = 404;
        $response["status"] = "error";
        $response["message"] = "Your account is in ".$user->status." status. Please contact admin.";
      }
    }else{ 
      $response["code"] = 404;
      $response["status"] = "error";
      $response["message"] = "All fields are needed.";
    }
    return response()
    ->json(array( 'status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
      ->header('Content-Type', 'application/json');
  }
  /* SWAP OFFER REQUEST*/

  /* GET ALL SWAP OFFERS */
  public function all_swap_offers(Request $req){
    if(isset($req->users_customers_id)){
      $swap_offer_expire = DB::table('system_settings')->where('type', 'swap_offer_expire')->first()->description; // return day like 7,4,5
      $expiryDate = Carbon::now()->addDays($swap_offer_expire)->format('Y-m-d');
      $fetch_data = SwapOffer::with(['from_currency', 'to_currency'])
                    ->where('status', 'Pending')
                    ->where('users_customers_id', '!=', $req->users_customers_id)
                    ->where('expiry_date_time', '>', date('Y-m-d H:i:s'))
                    ->whereDate('date_added', '<=', $expiryDate) // Apply expiry date filter
                    ->orderBy('swap_offers_id', 'DESC')
                    ->get();

      $users_wallets   =  UsersCustomersWallet::where(['users_customers_id'=>$req->users_customers_id,'status'=>'Active'])->get();
      $get_data=[];
      $get_data_time_check=[];
      foreach ($fetch_data as $key => $data) {
        $data->time_ago=Carbon::parse($data->date_added)->diffForHumans();

        $liked = DB::table('swap_offers_favourite')->where(['users_customers_id'=>$req->users_customers_id, 'swap_offers_id'=>$data->swap_offers_id, 'status'=>'Active'])->count();
        if($liked > 0){
          $data->liked = 'Yes';
        } else {
          $data->liked = 'No';
        }
        $get_data_time_check[]=$data;
      }
      foreach ($get_data_time_check as $key => $data) {
        foreach($users_wallets as $key => $wallet){
          if($data->to_system_currencies_id==$wallet->system_currencies_id){
            $get_data[]=$data;
          }
        }
      }
      if (count($get_data)>0) {
        $response["code"] = 200;
        $response["status"] = "success";
        $response["data"] = $get_data;
      } else {
        $response["code"] = 404;
        $response["status"] = "error"; 
        $response["message"] = "no data found.";
      }
    } else {
      $response["code"] = 404;
      $response["status"] = "error";
      $response["message"] = "All fields are required!"; 
    }
    return response()
    ->json(array( 'status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
      ->header('Content-Type', 'application/json');
  }
  /* GET ALL SWAP OFFERS */

  /* SWAP OFFER REQUEST APPROVE*/
  /* SWAP OFFER REQUEST APPROVE*/
  public function swap_offer_request_approve(Request $req){
    if (isset($req->swap_offers_requests_id) && isset($req->swap_offers_id) && isset($req->from_users_customers_id) ) {
      $user = DB::table('users_customers')->where(['users_customers_id'=>$req->from_users_customers_id, 'status'=>'Active'])->first();

      if ($user) {
        $swap_offer = SwapOffer::where(['swap_offers_id'=>$req->swap_offers_id, 'status'=>'Pending'])->first();

        if ($swap_offer) {
          $swap_offer_request = SwapOfferRequest::where(['swap_offers_requests_id'=>$req->swap_offers_requests_id, 'status'=>'Pending'])->first();

          if ($swap_offer_request) {
            $sender_send_wallet = UsersCustomersWallet::where([
                                  'users_customers_id' => $swap_offer->users_customers_id,
                                  'system_currencies_id' => $swap_offer->from_system_currencies_id
                                ])->with('currency')->first();

            if ($sender_send_wallet) {
              $sender_receive_wallet = UsersCustomersWallet::where([
                                        'users_customers_id' => $swap_offer->users_customers_id,
                                        'system_currencies_id'=>$swap_offer->to_system_currencies_id
                                      ])->with('currency')->first();

              if ($sender_receive_wallet) {
                $receiver_receive_wallet = UsersCustomersWallet::where([
                                            'users_customers_id' => $req->from_users_customers_id,
                                            'system_currencies_id'=>$swap_offer->from_system_currencies_id
                                          ])->with('currency')->first();

                if ($receiver_receive_wallet) {
                  $receiver_send_wallet = UsersCustomersWallet::where([
                                          'users_customers_id' => $req->from_users_customers_id,
                                          'system_currencies_id'=>$swap_offer->to_system_currencies_id
                                        ])->with('currency')->first();

                  if ($receiver_send_wallet) {
                    DB::beginTransaction();
                    try {
                      if ($sender_send_wallet->wallet_amount >= $swap_offer->from_amount && $receiver_send_wallet->wallet_amount >= $swap_offer->to_amount) {
                        // detect amount from sender wallet
                        $sender_send_amount         = $sender_send_wallet->wallet_amount - $swap_offer->from_amount;
                        $sender_send_wallet_updated = UsersCustomersWallet::where([
                                                        'users_customers_id' => $swap_offer->users_customers_id,
                                                        'system_currencies_id'=>$swap_offer->from_system_currencies_id
                                                      ])->update([
                                                        "wallet_amount"=>$sender_send_amount
                                                      ]);
                        // detect amount from sender wallet

                        // add amount to receiver wallet
                        $receiver_receive_amount         = $receiver_receive_wallet->wallet_amount + $swap_offer->from_amount;
                        $receiver_receive_wallet_updated = UsersCustomersWallet::where([
                                                            'users_customers_id' => $req->from_users_customers_id,
                                                            'system_currencies_id'=>$swap_offer->from_system_currencies_id
                                                          ])->update([
                                                            "wallet_amount"=>$receiver_receive_amount
                                                          ]);
                        // add amount to receiver wallet

                        // detect amount from receiver wallet
                        $receiver_send_amount         = $receiver_send_wallet->wallet_amount - $swap_offer->to_amount;
                        $receiver_send_wallet_updated = UsersCustomersWallet::where([
                                                        'users_customers_id' => $req->from_users_customers_id,
                                                        'system_currencies_id'=>$swap_offer->to_system_currencies_id
                                                      ])->update([
                                                        "wallet_amount"=>$receiver_send_amount
                                                      ]);
                        // detect amount from receiver wallet
                        
                        // add amount to sender wallet
                        $sender_receive_amount         = $sender_receive_wallet->wallet_amount + $swap_offer->to_amount;
                        $sender_receive_wallet_updated = UsersCustomersWallet::where([
                                                          'users_customers_id' => $swap_offer->users_customers_id,
                                                          'system_currencies_id'=>$swap_offer->to_system_currencies_id
                                                        ])->update([
                                                          "wallet_amount"=>$sender_receive_amount
                                                        ]);
                        // add amount to sender wallet
                      } else {
                        $insuff_amount_holder = "";
                        if ($sender_send_wallet->wallet_amount < $swap_offer->from_amount && $receiver_send_wallet->wallet_amount < $swap_offer->to_amount) {
                          $insuff_amount_holder = "Both sender and receiver don't";
                        } else if ($sender_send_wallet->wallet_amount < $swap_offer->from_amount) {
                          $insuff_amount_holder = "Sender doesn't";
                        } else {
                          $insuff_amount_holder = "Receiver doesn't";
                        }

                        $response["code"] = 404;
                        $response["status"] = "error";
                        $response["message"] = $insuff_amount_holder." have sufficient amount in wallet to transfer.";

                        return response()
                        ->json(array( 'status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
                        ->header('Content-Type', 'application/json');
                      }
                      
                      $data           = SwapOfferRequest::where("swap_offers_requests_id",$req->swap_offers_requests_id)->update(["status"=>"Accepted"]);
                      $pending_offers = SwapOfferRequest::where(["swap_offers_id"=>$req->swap_offers_id,"status"=>"Pending"])->get();
                      foreach ($pending_offers as $key => $offer) {
                        $offer_update=SwapOfferRequest::where("swap_offers_requests_id",$offer->swap_offers_requests_id)->update(["status"=>"Rejected"]);
                      }
                      // update swap offer
                      $swap_offer_updated = DB::table('swap_offers')->where('swap_offers_id', $req->swap_offers_id)->update(["status"=>"Accepted"]);

                      if ($user->notifications == 'Yes') {
                        $notif_sender = DB::table('users_customers')->select('first_name', 'last_name')
                        ->where('users_customers_id', $swap_offer->users_customers_id)
                        ->first(); 
                        $sender_name = empty($notif_sender->last_name) 
                        ? $notif_sender->first_name
                        : $notif_sender->first_name.' '.$notif_sender->last_name;
                        
                        /* notification */
                        $msg_text        = 'accepted your swap offer request.';
                        $one_signal_id   = $user->one_signal_id;
                        $type            = 'Swap Offer Accepted';
                        $message         = $sender_name.' '.$msg_text ;
                        $notif_pushed    = $this->pnc->push_notification($one_signal_id, $type, $message);

                        $dataInsert = array(
                          'users_type'      => "User",
                          'senders_id'      => $swap_offer->users_customers_id,
                          'receivers_id'    => $user->users_customers_id,
                          'message'         => $msg_text,
                          'date_added'      => date('Y-m-d H:i:s'),
                          'date_modified'   => date('Y-m-d H:i:s')
                        );
                        $this->send_notification($dataInsert);
                        /* notification */
                      }
                      DB::commit();
                      $response["code"] = 200;
                      $response["status"] = "success";
                      $response["data"] = $data;

                    } catch (\Exception $ex) {
                      DB::rollback();
                      $response["code"] = 404;
                      $response["status"] = "error";
                      $response["message"] = "Something Wrong";
                    } 
                  } else {
                    $response["code"] = 404;
                    $response["status"] = "error";
                    $response["message"] = "Receiver sending wallet does not exist.";
                  }    
                } else {
                  $response["code"] = 404;
                  $response["status"] = "error";
                  $response["message"] = "Receiver receiving wallet does not exist.";
                }    
              } else {
                $response["code"] = 404;
                $response["status"] = "error";
                $response["message"] = "Sender receiving wallet does not exist.";
              }    
            } else {
              $response["code"] = 404;
              $response["status"] = "error";
              $response["message"] = "Sender sending wallet does not exist.";
            }
          } else {
            $response["code"] = 404;
            $response["status"] = "error";
            $response["message"] = "Swap offer request does not exist or processed.";
          }    
        }else{
          $response["code"] = 404;
          $response["status"] = "error";
          $response["message"] = "Swap offer does not exist.";
        }  
      } else {
        $response["code"] = 404;
        $response["status"] = "error";
        $response["message"] = "Your account is in ".$user->status." status. Please contact admin.";
      }
    } else { 
      $response["code"] = 404;
      $response["status"] = "error";
      $response["message"] = "All fields are needed.";
    }
    return response()
    ->json(array( 'status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
    ->header('Content-Type', 'application/json');
  }

  /* SWAP OFFER REQUEST APPROVE*/

   /* GET USER SWAP OFFERS Requests */
   public function user_swap_offers_requests(Request $req){
    if(isset($req->swap_offers_id)){
      $get_data = SwapOfferRequest::where(["swap_offers_id"=>$req->swap_offers_id,'status'=>'Pending'])->get();

      $final_list = []; 
      foreach ($get_data as $key => $data) {
        $data->user_data = DB::table('users_customers')->where(['users_customers_id'=>$data->from_users_customers_id])->first();
        $final_list[] = $data;
      } 

      if (count($final_list) > 0) {
        $response["code"] = 200;
        $response["status"] = "success";
        $response["data"] = $final_list;
      } else {
        $response["code"] = 404;
        $response["status"] = "error"; 
        $response["message"] = "no data found.";
      }
    } else {
      $response["code"] = 404;
      $response["status"] = "error";
      $response["message"] = "All fields are required!"; 
    }
    return response()
    ->json(array( 'status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
      ->header('Content-Type', 'application/json');
  }
  /* GET USER SWAP OFFERS Requests */

  /* GET USER SWAP OFFERS */
  public function user_swap_offers(Request $req){
    if(isset($req->users_customers_id)){
      $fetch_data = SwapOffer::with(['from_currency','to_currency'])
                    ->where(['users_customers_id'=>$req->users_customers_id,'status'=>'Pending'])
                    ->where('expiry_date_time', '>', date('Y-m-d H:i:s'))
                    ->orderBy('swap_offers_id','DESC')
                    ->get();
      $get_data = []; 
      foreach ($fetch_data as $key => $data) {
        $data->time_ago       = Carbon::parse($data->date_added)->diffForHumans();
        $data->total_requests = DB::table('swap_offers_requests')
                                ->where([
                                  ['swap_offers_id', $data->swap_offers_id], 
                                  ['status', 'Pending']
                                ])
                                ->count();
        $get_data[] = $data;
      }
      if (count($get_data)>0) {
        $response["code"] = 200;
        $response["status"] = "success";
        $response["data"] = $get_data;
      } else {
        $response["code"] = 404;
        $response["status"] = "error"; 
        $response["message"] = "no data found.";
      }
    } else {
      $response["code"] = 404;
      $response["status"] = "error";
      $response["message"] = "All fields are required!"; 
    }
    return response()
    ->json(array( 'status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
      ->header('Content-Type', 'application/json');
  }
  /* GET USER SWAP OFFERS */

  /* SELL CURRENCY RATE */
  public function sell_currency_rate(Request $req){
    if (isset($req->from_system_currencies_id) && isset($req->to_system_currencies_id) && isset($req->from_amount)) 
    {
      $from_currency=DB::table('system_currencies')->where(['system_currencies_id'=>$req->from_system_currencies_id,'status'=>'Active'])->first();
      if ($from_currency) 
      {
        $to_currency=DB::table('system_currencies')->where(['system_currencies_id'=>$req->to_system_currencies_id,'status'=>'Active'])->first();
        if ($to_currency) 
        {   
          $base_currency   = $from_currency->code; 
          $req_url         = "https://api.exchangerate-api.com/v4/latest/$base_currency";
          $response_json   = file_get_contents($req_url, false, stream_context_create(['ssl' => ['verify_peer' => false, 'verify_peer_name' => false]]));
          $response        = json_decode($response_json, true); 
          
          if ($response && isset($response['rates'])) {
            $to_currency_code    = $to_currency->code; 
            $amount_to_convert   = $req->from_amount; 
        
            // calculate converted amount
            $conversion_rate    = $response['rates'][$to_currency_code];
            $converted_amount   = $amount_to_convert * $conversion_rate;
        
            // apply margin
            $margin   = $to_currency->margin; 
            $value    = $converted_amount - ($converted_amount * $margin);
                   
            if ($value > 0) {
              $converted_amount   = $value;
            } else {
              $converted_amount   = 0;
            }
            $admin_rate_amount = $to_currency->admin_rate * $req->from_amount;
            $data = new \stdClass(); 

            $data->converte_rate       = $conversion_rate;
            $data->converted_amount    = $converted_amount;
            $data->admin_rate_amount   = $admin_rate_amount;

            $response["code"] = 200;
            $response["status"] = "success";
            $response["data"] = $data;
          } else {
            $response["code"] = 404;
            $response["status"] = "error";
            $response["message"] = "Unable to fetch exchange rates.";
          }   
        }else{
          $response["code"] = 404;
          $response["status"] = "error";
          $response["message"] = "From Currency does not exists.";
        }
      }else{
        $response["code"] = 404;
        $response["status"] = "error";
        $response["message"] = "To Currency does not exists.";
      }
    }else{ 
      $response["code"] = 404;
      $response["status"] = "error";
      $response["message"] = "All fields are needed.";
    }
    return response()
    ->json(array( 'status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
      ->header('Content-Type', 'application/json');
  }
  /* SELL CURRENCY RATE */

  /* BUY CURRENCY RATE */
  public function buy_currency_rate(Request $req){
    if (isset($req->from_system_currencies_id) && isset($req->to_system_currencies_id) && isset($req->from_amount)) 
    {
      $from_currency=DB::table('system_currencies')->where(['system_currencies_id'=>$req->from_system_currencies_id,'status'=>'Active'])->first();
      if ($from_currency) 
      {  
        $to_currency=DB::table('system_currencies')->where(['system_currencies_id'=>$req->to_system_currencies_id,'status'=>'Active'])->first();
        if ($to_currency) 
        {    
          $base_currency   = $from_currency->code; 
          $req_url         = "https://api.exchangerate-api.com/v4/latest/$base_currency";
           $ctx           = stream_context_create(['ssl' => ['verify_peer' => false, 'verify_peer_name' => false]]);
           $response_json   = file_get_contents($req_url, false, $ctx);
          $response        = json_decode($response_json, true);

          if ($response && isset($response['rates'])) {
            $to_currency_code    = $to_currency->code; 
            $amount_to_convert   = $req->from_amount; 

            // calculate converted amount
            $conversion_rate    = $response['rates'][$to_currency_code];
            $converted_amount   = $amount_to_convert * $conversion_rate;
            $converted_amount   = $conversion_rate * $req->from_amount;

            $admin_rate_amount = $to_currency->admin_rate * $req->from_amount;
            $data = new \stdClass(); 

            $data->converte_rate       = $conversion_rate;
            $data->converted_amount    = $converted_amount;
            $data->admin_rate_amount   = $admin_rate_amount;

            $response["code"] = 200;
            $response["status"] = "success";
            $response["data"] = $data;
          } else {
            $response["code"] = 404;
            $response["status"] = "error";
            $response["message"] = "Unable to fetch exchange rates.";
          }   
        }else{
          $response["code"] = 404;
          $response["status"] = "error";
          $response["message"] = "From Currency does not exists.";
        }
      }else{
        $response["code"] = 404;
        $response["status"] = "error";
        $response["message"] = "To Currency does not exists.";
      }
    }else{ 
      $response["code"] = 404;
      $response["status"] = "error";
      $response["message"] = "All fields are needed.";
    }
    return response()
    ->json(array( 'status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
      ->header('Content-Type', 'application/json');
  }
  /* BUY CURRENCY RATE */

  /* USER FEEDBACK */
  public function user_feedback(Request $req){
    if (isset($req->users_customers_id) && isset($req->name) && isset($req->email) && isset($req->subject)) {
      $user_exist=DB::table('users_customers')->where(['users_customers_id'=>$req->users_customers_id])->first();
      if ($user_exist){
        $user=DB::table('users_customers')->where(['users_customers_id'=>$req->users_customers_id,'status'=>'Active'])->first();
        if ($user){
          $saveData['users_customers_id']   = $req->users_customers_id;
          $saveData['name']                 = $req->name;
          $saveData['email']                = $req->email;
          $saveData['subject']              = $req->subject;
        
          $feedback      = Feedback::updateOrCreate(['users_customers_id' => $req->users_customers_id],$saveData);

          $response["code"]     = 200;   
          $response["status"]   = "success";
          $response["data"]     = $feedback;
        }else{
          $response["code"] = 404;
          $response["status"] = "error";
          $response["message"] = "Your account is in ".$user->status." status. Please contact admin.";
        }
      }else{
        $response["code"] = 404;
        $response["status"] = "error";
        $response["message"] = "User not exist.";
      }
    } else {
      $response["code"] = 404;
      $response["status"] = "error";
      $response["message"] = "All fields are needed.";
    }
    
    return response()
     ->json(array( 'status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
     ->header('Content-Type', 'application/json');
  }
  /* USER FEEDBACK */

  /* ALL FAQs*/
  public function all_faqs(){
    $faqs = FAQ::where('status','Active')->get();
    
    if (count($faqs)>0) {
      $response["code"] = 200;
      $response["status"] = "success";
      $response["data"] = $faqs;
    } else {
      $response["code"] = 404;
      $response["status"] = "error";
      $response["message"] = "no data found.";
    }
    return response()
      ->json(array( 'status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
      ->header('Content-Type', 'application/json');
  }
  /* ALL FAQs*/
  
  /* ALL FAVORITE SWAP OFFERS*/
  public function all_favorite_swaps_offers(Request $req){
    if (isset($req->users_customers_id)) {
      $user=DB::table('users_customers')->where(['users_customers_id'=>$req->users_customers_id,'status'=>'Active'])->first();
      if ($user) {
        $favorites = FavoriteSwapOffer::where(['users_customers_id'=>$req->users_customers_id,'status'=>'Active'])->get();

        $data=[];
        foreach ($favorites as $key => $favorite) {
          $swap_offer = SwapOffer::where(['swap_offers_id'=>$favorite->swap_offers_id,'status'=>'Pending'])
                        ->where('expiry_date_time', '>', date('Y-m-d H:i:s'))
                        ->first(); 

          if (!empty($swap_offer)) {
            $data_string = $swap_offer;
            $data_string->from_currency = DB::table('system_currencies')->where(['system_currencies_id'=>$data_string->from_system_currencies_id])->first();
            $data_string->from_currency->country = DB::table('system_countries')->where(['code'=>$data_string->from_currency->code])->first();
  
            $data_string->to_currency = DB::table('system_currencies')->where(['system_currencies_id'=>$data_string->to_system_currencies_id])->first();
            $data_string->to_currency->country = DB::table('system_countries')->where(['code'=>$data_string->to_currency->code])->first();
  
            $data_string->base_currency = DB::table('system_currencies')->where(['system_currencies_id'=>$data_string->system_currencies_id])->first();
            $data_string->base_currency->country = DB::table('system_countries')->where(['code'=>$data_string->base_currency->code])->first();
            $data[] = $data_string;
          }
        }
        $get_data_time_check=[];
        foreach ($data as $key => $single_data) {
          $single_data->time_ago=Carbon::parse($single_data->date_added)->diffForHumans();
          $get_data_time_check[]=$single_data;
        }

        if (count($data)>0) {
          $response["code"] = 200;
          $response["status"] = "success";
          $response["data"] = $data;
        } else {
          $response["code"] = 404;
          $response["status"] = "error";
          $response["message"] = "no data found.";
        }
      }else{
        $response["code"] = 404;
        $response["status"] = "error";
        $response["message"] = "User does not exists.";
      }
    }else{ 
      $response["code"] = 404;
      $response["status"] = "error";
      $response["message"] = "All fields are needed.";
    }
    return response()
      ->json(array( 'status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
      ->header('Content-Type', 'application/json');
  }
  /* ALL FAVORITE SWAP OFFERS*/
  
  /* ADD FAVORITE SWAP OFFERS*/
  public function add_favorite_swaps_offers(Request $req){
    if (isset($req->users_customers_id) && isset($req->swap_offers_id)) {
      $user=DB::table('users_customers')->where(['users_customers_id'=>$req->users_customers_id,'status'=>'Active'])->first();
      if($user) {
        $swap_offer=SwapOffer::where(['swap_offers_id'=>$req->swap_offers_id,'status'=>'Pending'])->first();
        if($swap_offer) {
          $favorite_offer=FavoriteSwapOffer::where(['users_customers_id' => $req->users_customers_id,'swap_offers_id' => $req->swap_offers_id,'status'=>'Deleted'])->first();
          if($favorite_offer) {
            $update_offer = FavoriteSwapOffer::where(
              ['users_customers_id' => $req->users_customers_id,'swap_offers_id' => $req->swap_offers_id])->update(['status' => "Active"]);
            $updated_offer = FavoriteSwapOffer::where(
              ['users_customers_id' => $req->users_customers_id,'swap_offers_id' => $req->swap_offers_id])->first();
            $response["code"] = 200;
            $response["status"] = "success";
            $response["data"] = $updated_offer;
          }else{
            $favorite_offer = FavoriteSwapOffer::firstOrCreate(
              ['users_customers_id' => $req->users_customers_id,'swap_offers_id' => $req->swap_offers_id],
              ['users_customers_id' => $req->users_customers_id,'swap_offers_id' => $req->swap_offers_id]);;
  
            $response["code"] = 200;
            $response["status"] = "success";
            $response["data"] = $favorite_offer;
          }
        }else{
          $response["code"] = 404;
          $response["status"] = "error";
          $response["message"] = "Swap offer does not exists.";
        }
      }else{
        $response["code"] = 404;
        $response["status"] = "error";
        $response["message"] = "User does not exists.";
      }
    }else{ 
      $response["code"] = 404;
      $response["status"] = "error";
      $response["message"] = "All fields are needed.";
    }
    return response()
      ->json(array( 'status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
      ->header('Content-Type', 'application/json');
  }
  /* ADD FAVORITE SWAP OFFERS*/
  
  /* REMOVE FAVORITE SWAP OFFERS*/
  public function remove_favorite_swaps_offers(Request $req){
    if (isset($req->users_customers_id) && isset($req->swap_offers_id)) {
      $user=DB::table('users_customers')->where(['users_customers_id'=>$req->users_customers_id,'status'=>'Active'])->first();
      if ($user) {
        $swap_offer=SwapOffer::where(['swap_offers_id'=>$req->swap_offers_id,'status'=>'Pending'])->first();
        if ($swap_offer) {
          $remove_offer = FavoriteSwapOffer::where(
            ['users_customers_id' => $req->users_customers_id,'swap_offers_id' => $req->swap_offers_id])->update(['status' => "Deleted"]);
            if($remove_offer){
              $response["code"] = 200;
              $response["status"] = "success";
              $response["message"] = "Remove Successfully";
            }else{
              $response["code"] = 404;
              $response["status"] = "error";
              $response["message"] = "Data not updated.";
            }
        }else{
          $response["code"] = 404;
          $response["status"] = "error";
          $response["message"] = "Swap offer does not exists.";
        }
      }else{
        $response["code"] = 404;
        $response["status"] = "error";
        $response["message"] = "User does not exists.";
      }
    }else{ 
      $response["code"] = 404;
      $response["status"] = "error";
      $response["message"] = "All fields are needed.";
    }
    return response()
      ->json(array( 'status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
      ->header('Content-Type', 'application/json');
  }
  /* REMOVE FAVORITE SWAP OFFERS*/

  /* SWAP OFFER REQUEST REJECT*/
  public function swap_offer_request_reject(Request $req){
    if (isset($req->swap_offers_requests_id))
    {
      $pending_offer=SwapOfferRequest::where(["swap_offers_requests_id"=>$req->swap_offers_requests_id,"status"=>"Pending"])->get();
      if ($pending_offer) 
      {
        $offer_update=SwapOfferRequest::where("swap_offers_requests_id",$req->swap_offers_requests_id)->update(["status"=>"Rejected"]);
        if($offer_update){
            $response["code"] = 200;
            $response["status"] = "success";
            $response["message"] = "Updated Successfully";
          }else{
            $response["code"] = 404;
            $response["status"] = "error";
            $response["message"] = "Data not updated.";
        }
      }else{
        $response["code"] = 404;
        $response["status"] = "error";
        $response["message"] = "Swap offer request not exist.";
      } 
    }else{ 
      $response["code"] = 404;
      $response["status"] = "error";
      $response["message"] = "All fields are needed.";
    }
    return response()
    ->json(array( 'status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
      ->header('Content-Type', 'application/json');
  }
  /* SWAP OFFER REQUEST REJECT*/

  /* GET USER WALLET DETAIL */
  public function user_wallet_detail(Request $req){
    if (isset($req->users_customers_wallets_id) && isset($req->users_customers_id)) {
      $fetch_data  =UsersCustomersWallet::with('currency')->where(
        ['users_customers_id'=>$req->users_customers_id,'users_customers_wallets_id'=>$req->users_customers_wallets_id])->first();
    
      if ($fetch_data) {
        $response["code"] = 200;
        $response["status"] = "success";
        $response["data"] = $fetch_data;
      } else {
        $response["code"] = 404;
        $response["status"] = "error";
        $response["message"] = "no data found.";
      }
    }else{ 
      $response["code"] = 404;
      $response["status"] = "error";
      $response["message"] = "All fields are needed.";
    }

    return response()
    ->json(array( 'status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
      ->header('Content-Type', 'application/json');
  }
  /* GET USER WALLET DETAIL*/

  
  /* ALL CONNECT CATEGORIES*/
  public function connect_categories(){
    $connect_categories = DB::table('connect_categories')->where('status','Active')->get();
    
    if (count($connect_categories)>0) {
      $response["code"] = 200;
      $response["status"] = "success";
      $response["data"] = $connect_categories;
    } else {
      $response["code"] = 404;
      $response["status"] = "error";
      $response["message"] = "no data found.";
    }
    return response()
      ->json(array( 'status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
      ->header('Content-Type', 'application/json');
  }
  /* ALL CONNECT CATEGORIES*/

  /* ALL CONNECT ARTICLES*/
  public function connect_articles(Request $req){
    if (isset($req->users_customers_id)) {
      $fetch_data = DB::table('connect_articles')->where('status','Active')->get();
      $get_data=[];
        foreach ($fetch_data as $key => $data) {
          $liked = FavoriteConnectArticle::where(['users_customers_id'=>$req->users_customers_id, 'connect_articles_id'=>$data->connect_articles_id, 'status'=>'Active'])->count();
          if($liked > 0){
            $data->liked = 'Yes';
          } else {
            $data->liked = 'No';
          }
          $get_data[]=$data;
        }
      
      if (count($get_data)>0) {
        $response["code"] = 200;
        $response["status"] = "success";
        $response["data"] = $get_data;
      } else {
        $response["code"] = 404;
        $response["status"] = "error";
        $response["message"] = "no data found.";
      }
    }else{ 
      $response["code"] = 404;
      $response["status"] = "error";
      $response["message"] = "All fields are needed.";
    }
    return response()
      ->json(array( 'status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
      ->header('Content-Type', 'application/json');
  }
  /* ALL CONNECT ARTICLES*/

   /* ADD FAVORITE CONNECT ARTICLE*/
   public function add_favorite_connect_articles(Request $req){
    if (isset($req->users_customers_id) && isset($req->connect_articles_id)) {
      $user=DB::table('users_customers')->where(['users_customers_id'=>$req->users_customers_id,'status'=>'Active'])->first();
      if($user) {
        $connect_article=DB::table('connect_articles')->where(['connect_articles_id'=>$req->connect_articles_id,'status'=>'Active'])->first();
        if($connect_article) {
          $favorite_article=FavoriteConnectArticle::where(['users_customers_id' => $req->users_customers_id,'connect_articles_id' => $req->connect_articles_id,'status'=>'Deleted'])->first();
          if($favorite_article) {
            $update_article = FavoriteConnectArticle::where(
              ['users_customers_id' => $req->users_customers_id,'connect_articles_id' => $req->connect_articles_id])->update(['status' => "Active"]);
            $updated_article = FavoriteConnectArticle::where(
              ['users_customers_id' => $req->users_customers_id,'connect_articles_id' => $req->connect_articles_id])->first();
            $response["code"] = 200;
            $response["status"] = "success";
            $response["data"] = $updated_article;
          }else{
            $favorite_article = FavoriteConnectArticle::firstOrCreate(
              ['users_customers_id' => $req->users_customers_id,'connect_articles_id' => $req->connect_articles_id],
              ['users_customers_id' => $req->users_customers_id,'connect_articles_id' => $req->connect_articles_id]);;
  
            $response["code"] = 200;
            $response["status"] = "success";
            $response["data"] = $favorite_article;
          }
        }else{
          $response["code"] = 404;
          $response["status"] = "error";
          $response["message"] = "Connect Article does not exists.";
        }
      }else{
        $response["code"] = 404;
        $response["status"] = "error";
        $response["message"] = "User does not exists.";
      }
    }else{ 
      $response["code"] = 404;
      $response["status"] = "error";
      $response["message"] = "All fields are needed.";
    }
    return response()
      ->json(array( 'status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
      ->header('Content-Type', 'application/json');
  }
  /* ADD FAVORITE CONNECT ARTICLE*/

  /* REMOVE FAVORITE CONNECT ARTICLE*/
  public function remove_favorite_connect_articles(Request $req){
    if (isset($req->users_customers_id) && isset($req->connect_articles_id)) {
      $user=DB::table('users_customers')->where(['users_customers_id'=>$req->users_customers_id,'status'=>'Active'])->first();
      if ($user) {
        $connect_article=DB::table('connect_articles')->where(['connect_articles_id'=>$req->connect_articles_id,'status'=>'Active'])->first();
        if ($connect_article) {
          $remove_offer = FavoriteConnectArticle::where(
            ['users_customers_id' => $req->users_customers_id,'connect_articles_id' => $req->connect_articles_id])->update(['status' => "Deleted"]);
            if($remove_offer){
              $response["code"] = 200;
              $response["status"] = "success";
              $response["message"] = "Remove Successfully";
            }else{
              $response["code"] = 404;
              $response["status"] = "error";
              $response["message"] = "Data not updated.";
            }
        }else{
          $response["code"] = 404;
          $response["status"] = "error";
          $response["message"] = "Connect Article does not exists.";
        }
      }else{
        $response["code"] = 404;
        $response["status"] = "error";
        $response["message"] = "User does not exists.";
      }
    }else{ 
      $response["code"] = 404;
      $response["status"] = "error";
      $response["message"] = "All fields are needed.";
    }
    return response()
      ->json(array( 'status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
      ->header('Content-Type', 'application/json');
  }
  /* REMOVE FAVORITE CONNECT ARTICLE*/

  /*CONNECT ARTICEL VIEW*/
  public function connect_article_view(Request $req){
    if(isset($req->users_customers_id) && isset($req->connect_articles_id)) {
      $user=DB::table('users_customers')->where(['users_customers_id'=>$req->users_customers_id,'status'=>'Active'])->first();
        if ($user){
          $article=DB::table('connect_articles')->where(['connect_articles_id'=>$req->connect_articles_id,'status'=>'Active'])->first();
          if ($article) {
          DB::beginTransaction();
          try {
            $userid = ['users_customers_id'=>$req->users_customers_id,'connect_articles_id'=>$req->connect_articles_id]; 
                                
                  $viewed=ConnectArticleView::firstOrCreate($userid,[
                      'users_customers_id'=>$req->users_customers_id,
                      'connect_articles_id'=>$req->connect_articles_id,
                  ]);

                  if($viewed){
                DB::commit();
                    $changedStatus=ConnectArticleView::where($userid)->first();
                    $response["code"] = 200;
                    $response["status"] = "success";
                    $response["data"] = $changedStatus;
                   }
          } catch (\Exception $ex) {
              DB::rollback();
              $response["code"] = 404;
              $response["status"] = "error";
              $response['message'] = $ex->getMessage();
          }
        }else{  
          $response["code"] = 404;
          $response["status"] = "error";
          $response["message"] = "Connect Article does not exists.";
        }    
      }else{  
        $response["code"] = 404;
        $response["status"] = "error";
        $response["message"] = "User does not exists.";
      }
    }else{
      $response["code"] = 404;
      $response["status"] = "error";
      $response["message"] = "All fields is required.";
    }
    return response()
      ->json(array( 'status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
      ->header('Content-Type', 'application/json');
  }
  /*CONNECT ARTICEL VIEW*/

  /* POPULAR CONNECT ARTICLES */
  public function popular_connect_articles(Request $req){
    if (isset($req->users_customers_id)) {
      $articles= DB::table('connect_articles')->where('status','Active')->get();
      $views=ConnectArticleView::all();
      $array=[];
      
      foreach ($articles as $key => $article) {
        foreach ($views as $key => $view) {
          // Articles views like
          $liked = FavoriteConnectArticle::where(['users_customers_id'=>$req->users_customers_id, 'connect_articles_id'=>$article->connect_articles_id, 'status'=>'Active'])->count();
          if($liked > 0){
            $article->liked = 'Yes';
          } else {
            $article->liked = 'No';
          }
          // Articles views like

          // Articles views count
          $article->view_count=0;
          if($view->connect_articles_id == $article->connect_articles_id){
            $article->view_count+=1;
          }
          // Articles views count
        }
        $array[]=$article;
      }
      $get_data = collect($array)->sortByDesc('view_count')->values()->all();
      if (count($get_data)>0) {
        $response["code"] = 200;
        $response["status"] = "success";
        $response["data"] = $get_data;
      } else {
        $response["code"] = 404;
        $response["status"] = "error";
        $response["message"] = "no data found.";
      }
    }else{ 
      $response["code"] = 404;
      $response["status"] = "error";
      $response["message"] = "All fields are needed.";
    }
    return response()
      ->json(array( 'status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
      ->header('Content-Type', 'application/json');
  }
  /* POPULAR CONNECT ARTICLES*/

   /* FAVORITE CONNECT ARTICLES*/
   public function favorite_connect_articles(Request $req){
    if (isset($req->users_customers_id)) {
      $user=DB::table('users_customers')->where(['users_customers_id'=>$req->users_customers_id,'status'=>'Active'])->first();
      if ($user) {
        $fetch_data = DB::table('connect_articles')->get();
        $get_data=[];
          foreach ($fetch_data as $key => $data) {
            $liked = FavoriteConnectArticle::where(['users_customers_id'=>$req->users_customers_id, 'connect_articles_id'=>$data->connect_articles_id, 'status'=>'Active'])->first();
            if($liked){
              $data->liked = 'Yes';
              $get_data[]=$data;
            }
          }

        if (count($get_data)>0) {
          $response["code"] = 200;
          $response["status"] = "success";
          $response["data"] = $get_data;
        } else {
          $response["code"] = 404;
          $response["status"] = "error";
          $response["message"] = "no data found.";
        }
      }else{
        $response["code"] = 404;
        $response["status"] = "error";
        $response["message"] = "User does not exists.";
      }
    }else{ 
      $response["code"] = 404;
      $response["status"] = "error";
      $response["message"] = "All fields are needed.";
    }
    return response()
      ->json(array( 'status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
      ->header('Content-Type', 'application/json');
  }
  /* FAVORITE CONNECT ARTICLES*/

  /* ADD ACCOUNT */
  public function add_acount(Request $req){
    if (isset($req->users_customers_id) && isset($req->system_currencies_id) && isset($req->full_name) && isset($req->iban) && isset($req->branch_code) && isset($req->account_no) && isset($req->bank_name)) {
      $user=DB::table('users_customers')->where(['users_customers_id'=>$req->users_customers_id,'status'=>'Active'])->first();
      if ($user) {
        $system_currency=DB::table('system_currencies')->where('system_currencies_id',$req->system_currencies_id)->first();
        if ($system_currency) {
          $account = DB::table('users_customers_accounts')->insertGetId([
                              'users_customers_id' => $req->users_customers_id,
                              'system_currencies_id'=>$req->system_currencies_id,
                              'full_name'=>$req->full_name,
                              'iban'=>$req->iban,
                              'branch_code'=>$req->branch_code,
                              'account_no'=>$req->account_no,
                              "bank_name"=>$req->bank_name
                            ]);
                if($account){
                  $data=UserCustomerAccount::with('account_currency')->where(
                      ['users_customers_id' => $req->users_customers_id,'system_currencies_id'=>$req->system_currencies_id,'iban'=>$req->iban]
                    )->first();
                    $response["code"] = 200;
                    $response["status"] = "success";
                    $response["data"] = $data;
          }else{
            $response["code"] = 404;
            $response["status"] = "error";
            $response["message"] = "Oops! Something went wrong.";
          }
        }else{
          $response["code"] = 404;
          $response["status"] = "error";
          $response["message"] = "Currency does not exists.";
        }
      }else{
        $response["code"] = 404;
        $response["status"] = "error";
        $response["message"] = "User does not exists.";
      }
    }else{ 
      $response["code"] = 404;
      $response["status"] = "error";
      $response["message"] = "All fields are needed.";
    }
    return response()
    ->json(array( 'status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
      ->header('Content-Type', 'application/json');
  }
  /* ADD ACCOUNT */

  /* ALL ACCOUNTS */
  public function all_acounts(Request $req){
    if (isset($req->users_customers_id)) {
      $user=DB::table('users_customers')->where(['users_customers_id'=>$req->users_customers_id,'status'=>'Active'])->first();
      if ($user) {
        $fetch_data=UserCustomerAccount::with('account_currency')->where(['users_customers_id' => $req->users_customers_id,'status'=>'Active'])->get();
        $get_data=[];
          foreach ($fetch_data as $key => $data) {
            $data->user_data=DB::table('users_customers')->where(['users_customers_id'=>$req->users_customers_id,'status'=>'Active'])->first();
            $get_data[]=$data;
          }
          if(count($get_data)>0){
            $response["code"] = 200;
            $response["status"] = "success";
            $response["data"] = $get_data;
          }else{
            $response["code"] = 404;
            $response["status"] = "error";
            $response["message"] = "Data not updated.";
          }
      }else{
        $response["code"] = 404;
        $response["status"] = "error";
        $response["message"] = "User does not exists.";
      }
    }else{ 
      $response["code"] = 404;
      $response["status"] = "error";
      $response["message"] = "All fields are needed.";
    }
    return response()
    ->json(array( 'status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
      ->header('Content-Type', 'application/json');
  }
  /* ALL ACCOUNTS */

  /* ALL CONNECT ARTICLES By CATEGORY*/
  public function connect_articles_by_category(Request $req){
    if (isset($req->users_customers_id) && isset($req->connect_categories_id)) {
      $fetch_data = DB::table('connect_articles')->where(['connect_categories_id'=>$req->connect_categories_id,'status'=>'Active'])->get();
      $get_data=[];
        foreach ($fetch_data as $key => $data) {
          $liked = FavoriteConnectArticle::where(['users_customers_id'=>$req->users_customers_id, 'connect_articles_id'=>$data->connect_articles_id, 'status'=>'Active'])->count();
          if($liked > 0){
            $data->liked = 'Yes';
          } else {
            $data->liked = 'No';
          }
          $get_data[]=$data;
        }
      
      if (count($get_data)>0) {
        $response["code"] = 200;
        $response["status"] = "success";
        $response["data"] = $get_data;
      } else {
        $response["code"] = 404;
        $response["status"] = "error";
        $response["message"] = "no data found.";
      }
    }else{ 
      $response["code"] = 404;
      $response["status"] = "error";
      $response["message"] = "All fields are needed.";
    }
    return response()
      ->json(array( 'status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
      ->header('Content-Type', 'application/json');
  }
  /* ALL CONNECT ARTICLES By CATEGORY*/

  /* POPULAR CONNECT ARTICLES By CATEYGORY*/
  public function popular_connect_articles_by_category(Request $req){
    if (isset($req->users_customers_id) && isset($req->connect_categories_id)) {
      $articles=DB::table('connect_articles')->where(['connect_categories_id'=>$req->connect_categories_id,'status'=>'Active'])->get();
      $views=ConnectArticleView::all();
      $array=[];
      
      foreach ($articles as $key => $article) {
        foreach ($views as $key => $view) {
          // Articles views like
          $liked = FavoriteConnectArticle::where(['users_customers_id'=>$req->users_customers_id, 'connect_articles_id'=>$article->connect_articles_id, 'status'=>'Active'])->count();
          if($liked > 0){
            $article->liked = 'Yes';
          } else {
            $article->liked = 'No';
          }
          // Articles views like

          // Articles views count
          $article->view_count=0;
          if($view->connect_articles_id == $article->connect_articles_id){
            $article->view_count+=1;
          }
          // Articles views count
        }
        $array[]=$article;
      }
      $get_data = collect($array)->sortByDesc('view_count')->values()->all();
      if (count($get_data)>0) {
        $response["code"] = 200;
        $response["status"] = "success";
        $response["data"] = $get_data;
      } else {
        $response["code"] = 404;
        $response["status"] = "error";
        $response["message"] = "no data found.";
      }
    }else{ 
      $response["code"] = 404;
      $response["status"] = "error";
      $response["message"] = "All fields are needed.";
    }
    return response()
      ->json(array( 'status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
      ->header('Content-Type', 'application/json');
  }
  /* POPULAR CONNECT ARTICLES By CATEYGORY*/

  /* USERS CUSTOMERS LAST ACTIVITY*/
  public function users_customers_last_activity(Request $req){
    if (isset($req->users_customers_id)) {
      $user = DB::table('users_customers')->where('users_customers_id', $req->users_customers_id)->first();
      if ($user) {
        $update=DB::table('users_customers')->where('users_customers_id', $req->users_customers_id)->update(['last_activity'=>Carbon::now()]);

            $userDetail=DB::table('users_customers')->where('users_customers_id', $req->users_customers_id)->first();
            if (isset($userDetail) && $userDetail != null) {
              $response["code"] = 200;
              $response["status"] = "success";
              $response["data"] = $userDetail;
            } else{
              $response["code"] = 404;
              $response["status"] = "error";
              $response["message"] = "User do not exist.";
            }
      }else{
        $response["code"] = 404;
        $response["status"] = "error";
        $response["message"] = "User does not exits.";
      }
    } else {
      $response["code"] = 404;
      $response["status"] = "error";
      $response["message"] = "All fields are needed.";
    }

    return response()
    ->json(array( 'status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
    ->header('Content-Type', 'application/json');
  }
  /* USERS CUSTOMERS LAST ACTIVITY*/

  /* USERS CUSTOMERS ACTIVITY INTERVAL */
  public function users_customers_activity_interval(Request $req){
    if (isset($req->users_customers_id)) {
      $user = DB::table('users_customers')->where('users_customers_id', $req->users_customers_id)->first();
      if ($user) {
        
        $start = Carbon::parse($user->last_activity);
        $end = Carbon::parse(Carbon::now());
        $diffInMinutes = $end->diffInMinutes($start);
        $formattedMinutes = str_pad($diffInMinutes, 2, '0', STR_PAD_LEFT);
        if($formattedMinutes <= $user->activity_interval){
          $response["code"] = 200;
          $response["status"] = "success";
          $response["data"] = true;
        } else{
          $response["code"] = 404;
          $response["status"] = "error";
          $response["data"] = false;
        }
      }else{
        $response["code"] = 404;
        $response["status"] = "error";
        $response["message"] = "User does not exits.";
      }
    } else {
      $response["code"] = 404;
      $response["status"] = "error";
      $response["message"] = "All fields are needed.";
    }

    return response()
    ->json(array( 'status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
    ->header('Content-Type', 'application/json');
  }
  /* USERS CUSTOMERS ACTIVITY INTERVAL */

  /* UPDATE PROFILE */
   public function update_activity_interval(Request $req){
    if(isset($req->users_customers_id) && isset($req->activity_interval)) {
      
      $updateData['activity_interval']              = $req->activity_interval;

      DB::table('users_customers')->where('users_customers_id', $req->users_customers_id)->update($updateData);
      $updatedData = DB::table('users_customers')->where('users_customers_id', $req->users_customers_id)->get();
 
      $response["code"] = 200;
      $response["status"] = "success";
      $response["data"] = $updatedData;
    } else {
      $response["code"] = 404;
      $response["status"] = "error";
      $response["message"] = "All fields are needed.";
    }

    return response()
      ->json(array( 'status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
      ->header('Content-Type', 'application/json');
  }
  /* UPDATE PROFILE */

  /* FUND WALLET REQUEST */
  public function fund_wallet_request(Request $req){
    if(isset($req->users_customers_id) && isset($req->users_customers_wallets_id)&& isset($req->image) && isset($req->bank_name) && isset($req->amount) && isset($req->description)) {

    $user_wallet = DB::table('users_customers_wallets')->where(['users_customers_wallets_id'=>$req->users_customers_wallets_id,"users_customers_id"=>$req->users_customers_id])->first();
      if($user_wallet){

        $saveData['users_customers_id']       	  = $req->users_customers_id;
        $saveData['users_customers_wallets_id']   = $req->users_customers_wallets_id;
        $saveData['bank_name']       	            = $req->bank_name;
        $saveData['amount']       	              = $req->amount;
        $saveData['description']       	          = $req->description;
        if(isset($req->image)){
          $image = $req->image;
          $prefix = time();
          $img_name = $prefix . '.jpeg';
          $image_path = public_path('uploads/fund_wallet/') . $img_name;

          file_put_contents($image_path, base64_decode($image));
          $saveData['image'] = 'uploads/fund_wallet/'. $img_name;
        }

        $fund_wallet_id   = DB::table('fund_wallets')->insertGetId($saveData);

        $data = DB::table('fund_wallets')->where('fund_wallets_id', $fund_wallet_id)->first();
  
        $response["code"] = 200;
        $response["status"] = "success";
        $response["data"] = $data;
        
      } else {
        $response["code"] = 404;
        $response["status"] = "error";
        $response["message"] = "Wallet not exist.";
      }
    } else {
      $response["code"] = 404;
      $response["status"] = "error";
      $response["message"] = "All fields are needed.";
    }

    return response()
      ->json(array( 'status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
      ->header('Content-Type', 'application/json');
  }
  /* FUND WALLET REQUEST */

  /* WITHDRAW WALLETS REQUEST */
  public function withdraw_wallets_request(Request $req){
    if(isset($req->users_customers_id) && isset($req->users_customers_wallets_id) && isset($req->users_customers_accounts_id) && isset($req->amount) && isset($req->description)) {

    $user_wallet = DB::table('users_customers_wallets')->where(['users_customers_wallets_id'=>$req->users_customers_wallets_id,"users_customers_id"=>$req->users_customers_id])->first();
    if($user_wallet){
      
      $user_account = DB::table('users_customers_accounts')->where(['users_customers_accounts_id'=>$req->users_customers_accounts_id,"users_customers_id"=>$req->users_customers_id])->first();
        if($user_account->system_currencies_id==$user_wallet->system_currencies_id){

          $saveData['users_customers_id']       	  = $req->users_customers_id;
          $saveData['users_customers_wallets_id']   = $req->users_customers_wallets_id;
          $saveData['users_customers_accounts_id']  = $req->users_customers_accounts_id;
          $saveData['amount']       	              = $req->amount;
          $saveData['description']       	          = $req->description;

          $withdraw_wallets_request_id   = DB::table('withdraw_wallets_requests')->insertGetId($saveData);

          $data = DB::table('withdraw_wallets_requests')->where('withdraw_wallets_requests_id', $withdraw_wallets_request_id)->first();
    
          $response["code"] = 200;
          $response["status"] = "success";
          $response["data"] = $data;
          
        } else {
          $response["code"] = 404;
          $response["status"] = "error";
          $response["message"] = "User has not account with this currency.";
        }
      } else {
        $response["code"] = 404;
        $response["status"] = "error";
        $response["message"] = "Wallet not exist.";
      }
    } else {
      $response["code"] = 404;
      $response["status"] = "error";
      $response["message"] = "All fields are needed.";
    }

    return response()
      ->json(array( 'status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
      ->header('Content-Type', 'application/json');
  }
  /* WITHDRAW WALLETS REQUEST */

  /* PURCHASE PRODUCT */
  // public function purchase_product(Request $req){
  //     // Common required fields
  //     $commonFields = ['users_customers_id', 'products_id', 'cover_duration', 'type'];

  //     foreach($commonFields as $field) {
  //       if(!isset($req->$field) || $req->$field === '') {
  //         return response()->json([
  //           'status' => 'error',
  //           'message' => "Field '$field' is required."
  //       ], 400);
  //       }
  //     }

  //     $type = $req->type; // 'A', 'B', or 'C'

  //     // Product-specific required fields
  //     $productFields = [];

  //     if ($type == 'A') {
  //       $productFields = ['first_name','surname','gender','date_of_birth','address','occupations_id','relationships_id', 'nin', 'nin_document'];
  //     } elseif ($type == 'B') {
  //       $productFields = ['first_name','surname','gender','date_of_birth','address','occupations_id','relationships_id', 'nin', 'nin_document'];
  //     } elseif ($type == 'C') {
  //       $productFields = ['tasks_types_id', 'task_date','task_time', 'contact_no'];
  //     } else {
  //       return response()->json([
  //         'status' => 'error',
  //         'message' => "Invalid product type."
  //       ], 400);
  //     }

  //     // Check if all product-specific fields are present
  //     foreach($productFields as $field) {
  //         if(!isset($req->$field) || $req->$field === '') {
  //             return response()->json([
  //                 'status' => 'error',
  //                 'message' => "Field '$field' is required for product type $type."
  //             ], 400);
  //         }
  //     }

  //     // Build data for insertion
  //     $data = [
  //       'users_customers_id' => $req->users_customers_id,
  //       'products_id'        => $req->products_id,
  //       'cover_duration'     => $req->cover_duration,
  //       'cover_start_date'   => $req->cover_start_date,
  //       'cover_end_date'     => $req->cover_end_date,
  //       'payment_method_id'  => 1,
  //       'transaction_number' => '12345',
  //       'payment_status'     => 'Pending',
  //       'payment_message'    => 'Waiting for payment',
  //       'date_added'         => date('Y-m-d H:i:s')
  //     ];
  //     $products_purchases_id = DB::table('products_purchases')->insertGetId($data);

  //     if ($products_purchases_id) {
  //       $user_tag_updated = DB::table('users_customers')
  //                           ->where('users_customers_id', $req->users_customers_id)
  //                           ->update(['users_customers_tag' => 'Community Member']);

  //       if ($type === 'A' || $type === 'B') {
  //         $data2 = [
  //           'products_purchases_id'   => $products_purchases_id,
  //           'first_name'              => $req->first_name,
  //           'surname'                 => $req->surname,
  //           'gender'                  => $req->gender,
  //           'date_of_birth'           => $req->date_of_birth,
  //           'address'                 => $req->address,
  //           'occupations_id'          => $req->occupations_id,
  //           'relationships_id'        => $req->relationships_id
  //         ];
  //         if (isset($req->nin) && !empty($req->nin)) {
  //           $data2['phone_number'] = $req->nin;
  //         }
  //         if (isset($req->nin_document) && !empty($req->nin_document)) {
  //           $nin_document = $req->nin_document;
  //           $prefix = time();
  //           $img_name = $prefix.'.jpeg';
  //           $image_path = public_path('uploads/identity_documents/').$img_name;
  //           file_put_contents($image_path, base64_decode($identity_document));
  //           $data2['nin_document'] = 'uploads/identity_documents/'.$img_name;
  //         }
          
  //         DB::table('products_purchases_beneficiaries')->insertGetId($data2);
  //       } 
  //       if ($type === 'C') {
  //         $data2 = [
  //           'products_purchases_id'   => $products_purchases_id,
  //           'tasks_types_id'          => $req->tasks_types_id,
  //           'task_date'               => $req->task_date,
  //           'task_time'               => $req->task_time,
  //           'contact_no'              => $req->contact_no
  //         ];
  //         DB::table('products_purchases_tasks')->insertGetId($data2);
  //       }
  //       $purchased_product = DB::table('products_purchases')->where('products_purchases_id', $products_purchases_id)->first();

  //       return response()->json([
  //         'status' => 'success',
  //         'data' => $purchased_product
  //       ], 200);
  //     } else {
  //       return response()->json([
  //         'status' => 'error',
  //         'message' => 'Something went wrong. Please try again.'
  //       ], 500);
  //     }
  // }
  public function purchase_product(Request $req){
    /*  Common fields requirement */
    $commonFields = ['users_customers_id', 'products_id', 'cover_duration', 'type'];

    foreach ($commonFields as $field) {
      if (empty($req->$field)) {
        return response()->json(['status' => 'error', 'message' => "Field '$field' is required."], 400);
      }
    }
    if (!DB::table('users_customers')->where([['users_customers_id', $req->users_customers_id], ['status', 'Active']])->exists()) {
      return response()->json(['status' => 'error', 'message' => "users_customers_id '{$req->users_customers_id}' does not exist."], 400);
    }
    if (!DB::table('products')->where([['products_id', $req->products_id], ['type', $req->type], ['status', 'Active']])->exists()) {
      return response()->json(['status' => 'error', 'message' => "product does not exist."], 400);
    }
    /*  Common fields requirement */

    $type = $req->type; // A, B, or C
    $productFields = [];

    if ($type === 'A' || $type === 'B') {
      $productFields = ['first_name', 'surname', 'gender', 'date_of_birth', 'address', 'occupations_id', 'relationships_id'];

      // check all required fields except nin/nin_document
      foreach ($productFields as $field) {
        if (empty($req->$field)) {
          return response()->json(['status' => 'error', 'message' => "Field '$field' is required for product type $type."], 400);
        }
      }
      if (!DB::table('occupations')->where([['occupations_id', $req->occupations_id], ['status', 'Active']])->exists()) {
        return response()->json(['status' => 'error', 'message' => "occupations_id '{$req->occupations_id}' does not exist."], 400);
      }
      if (!DB::table('relationships')->where([['relationships_id', $req->relationships_id], ['status', 'Active']])->exists()) {
        return response()->json(['status' => 'error', 'message' => "relationships_id '{$req->relationships_id}' does not exist."], 400);
      }
    } elseif ($type === 'C') {
      $productFields = ['tasks_types_id', 'task', 'task_date', 'description', 'recipient_name', 'recipient_phone'];

      foreach ($productFields as $field) {
        if (empty($req->$field)) {
          return response()->json(['status' => 'error', 'message' => "Field '$field' is required for product type C."], 400);
        }
      }
      if (!DB::table('tasks_types')->where([['tasks_types_id', $req->tasks_types_id], ['status', 'Active']])->exists()) {
        return response()->json(['status' => 'error', 'message' => "tasks_types_id '{$req->tasks_types_id}' does not exist."], 400);
      }
    } else {
      return response()->json(['status' => 'error','message' => 'Invalid product type.'], 400);
    }

    $prod_purchased = [];

    /* handle product type A & B */
    if (in_array($type, ['A', 'B'])) {
      /* products_purchases */
      $data = [
        'users_customers_id'   => $req->users_customers_id,
        'products_id'          => $req->products_id,
        'product_type'         => $type,
        'cover_duration'       => $req->cover_duration,
        'cover_start_date'     => $req->cover_start_date,
        'cover_end_date'       => $req->cover_end_date,
        'payment_method_id'    => 1,
        'transaction_number'   => 'SWAP-'.time().'-'.mt_rand(1000, 9999),
        'payment_status'       => 'Pending',
        'payment_message'      => 'Waiting for payment',
        'date_added'           => date('Y-m-d H:i:s')
      ];
      $products_purchases_id = DB::table('products_purchases')->insertGetId($data);
      if (!$products_purchases_id) {
        return response()->json(['status' => 'error', 'message' => 'Something went wrong. Please try again.'], 500);
      }
      /* products_purchases */

      $data2 = [
        'products_purchases_id'   => $products_purchases_id,
        'first_name'              => $req->first_name,
        'surname'                 => $req->surname,
        'gender'                  => $req->gender,
        'date_of_birth'           => $req->date_of_birth,
        'address'                 => $req->address,
        'occupations_id'          => $req->occupations_id,
        'relationships_id'        => $req->relationships_id
      ];
      if (!empty($req->nin)) {
        $data2['phone_number'] = $req->nin;
      }
      if (!empty($req->nin_document)) {
        $prefix = time();
        $img_name = $prefix . '.jpeg';
        $image_path = public_path('uploads/identity_documents/') . $img_name;

        file_put_contents($image_path, base64_decode($req->nin_document));
        $data2['nin_document'] = 'uploads/identity_documents/' . $img_name;
      }
      DB::table('products_purchases_beneficiaries')->insert($data2);

      $prod_purchased                                    = DB::table('products_purchases')->where('products_purchases_id', $products_purchases_id)->first();
      $prod_purchased->products_purchases_beneficiaries  = DB::table('products_purchases_beneficiaries')->where('products_purchases_id', $products_purchases_id)->first();

      $customer   = DB::table('users_customers')->where('users_customers_id', $req->users_customers_id)->first();
      $product    = DB::table('products')->where('products_id', $req->products_id)->first();

      /* send mail */ 
      $to               = $customer->email;
    
      $subject          = 'Purchase Confirmation';

      $productName      = htmlspecialchars($product->name);
      $coverDuration    = htmlspecialchars($req->cover_duration);
      $coverStartDate   = date('d-m-Y', strtotime($req->cover_start_date));
      $coverEndDate     = date('d-m-Y', strtotime($req->cover_end_date));
      $firstName        = htmlspecialchars($req->first_name);
      $surname          = htmlspecialchars($req->surname);
      $gender           = htmlspecialchars($req->gender);
      $dateOfBirth      = date('d-m-Y', strtotime($req->date_of_birth)); 
      $address          = htmlspecialchars($req->address);
      $occupation       = htmlspecialchars(DB::table('occupations')->where('occupations_id', $req->occupations_id)->first()->name); 
      $relationship     = htmlspecialchars(DB::table('relationships')->where('relationships_id', $req->relationships_id)->first()->name); 
      if (!empty($req->nin)) {
        // Use the NIN text
        $ninInfo = htmlspecialchars($req->nin);
      } elseif (!empty($data2['nin_document'])) {
        // Use the uploaded NIN document as an image
        $ninInfo = '<img src="' . asset($data2['nin_document']) . '" alt="NIN Document" style="max-width:200px; max-height:150px; border:1px solid #ccc; border-radius:4px;" />';
      } else {
        $ninInfo = 'Not Provided';
      }
      $message = '
        <html>
        <head>
          <style>
            body {
              font-family: Arial, sans-serif; 
              color: #333;
              margin: 0;
              padding: 20px;
              background-color: #fff;
            }
            .email-text-fullwidth {
              width: 100%;
              font-size: 14px;
              line-height: 1.5;
              margin-bottom: 20px;
              box-sizing: border-box;
            }
            .receipt-container {
              width: 100%;
              max-width: 600px;
              margin: 0 auto 20px auto;
              background: #f9f9f9;
              border: 1px solid #ccc;
              padding: 20px;
              box-sizing: border-box;
            }
            .receipt-container h2 {
              text-align: center;
              font-weight: bold;
              margin-bottom: 20px;
              font-size: 18px;
            }
            .receipt-container table {
              width: 100%;
              border-collapse: collapse;
              font-size: 14px;
              color: #333;
            }
            .receipt-container th, 
            .receipt-container td {
              border: 1px solid #ccc;
              padding: 8px 12px;
              vertical-align: top;
            }
            .receipt-container th {
              text-align: left;
              width: 180px;
              background-color: #f0f0f0;
              font-weight: 600;
            }
          </style>
        </head>
        <body>

          <div class="email-text-fullwidth">
            Dear ' . trim($customer->first_name . ' ' . ($customer->last_name ?? '')) . ',<br><br>
            Thank you for your purchase with <strong>Swap Circle</strong>. Your order has been successfully received. Please find below the details of your purchase:
          </div>
          <div class="receipt-container">
            <h2>Swap Circle - Purchase Confirmation</h2>
            <table>
              <tr>
                <th>Product Name</th>
                <td>' . $productName . '</td>
              </tr>
              <tr>
                <th>Cover Duration</th>
                <td>' . $coverDuration . '</td>
              </tr>
              <tr>
                <th>Cover Start Date</th>
                <td>' . $coverStartDate . '</td>
              </tr>
              <tr>
                <th>Cover End Date</th>
                <td>' . $coverEndDate . '</td>
              </tr>
              <tr>
                <th>Beneficiary First Name</th>
                <td>' . $firstName . '</td>
              </tr>
              <tr>
                <th>Beneficiary Surname</th>
                <td>' . ($surname ?? '-') . '</td>
              </tr>
              <tr>
                <th>Beneficiary Gender</th>
                <td>' . $gender . '</td>
              </tr>
              <tr>
                <th>Beneficiary Date of Birth</th>
                <td>' . $dateOfBirth . '</td>
              </tr>
              <tr>
                <th>Beneficiary Address</th>
                <td>' . $address . '</td>
              </tr>
              <tr>
                <th>Beneficiary Occupation</th>
                <td>' . $occupation . '</td>
              </tr>
              <tr>
                <th>Beneficiary Relationship</th>
                <td>' . $relationship . '</td>
              </tr>
              <tr>
                <th>Beneficiary Phone Number</th>
                <td>' . $ninInfo . '</td>
              </tr>
            </table>
          </div>
          <div class="email-text-fullwidth">
            Regards,<br>
            <strong>Swap Circle Team</strong>
          </div>



















        </body>
        </html>';
   

  /* ========= SEND MAIL ========= */
  $this->send_simple_mail($to, $subject, $this->generatePurchaseEmailHTML_AB($prod_purchased, $customer, $product, $prod_purchased->products_purchases_beneficiaries));

    
    }
    /* handle product type A & B */

    /* handle product type C */
    if ($type === 'C') {
      $prod_valid = DB::table('products_purchases as pp')
                    ->join('products_purchases_tasks as ppt', 'pp.products_purchases_id', '=', 'ppt.products_purchases_id')
                    ->where('pp.users_customers_id', $req->users_customers_id) 
                    ->where('pp.product_type', $type)                          
                    ->whereColumn('ppt.delivery_requests_consumed', '<', 'ppt.delivery_request_limit') 
                    ->select('pp.*', 'ppt.*') 
                    ->first();  

      if ($prod_valid) {
        $data2 = [
          'delivery_requests_consumed'   => (int) $prod_valid->delivery_requests_consumed + 1,
          'date_modified'                => date('Y-m-d H:i:s')
        ];
        $updated                                   = DB::table('products_purchases_tasks')->where('products_purchases_tasks_id', $prod_valid->products_purchases_tasks_id)->update($data2);
        $prod_purchased                            = DB::table('products_purchases')->where('products_purchases_id', $prod_valid->products_purchases_id)->first();
        $prod_purchased->products_purchases_tasks  = DB::table('products_purchases_tasks')->where('products_purchases_id', $prod_valid->products_purchases_id)->first();
      } else {
        /* products_purchases */
        $data = [
          'users_customers_id'   => $req->users_customers_id,
          'products_id'          => $req->products_id,
          'product_type'         => $type,
          'cover_duration'       => $req->cover_duration,
          'cover_start_date'     => $req->cover_start_date,
          'cover_end_date'       => $req->cover_end_date,
          'payment_method_id'    => 1,
          'transaction_number'   => 'SWAP-'.time().'-'.mt_rand(1000, 9999),
          'payment_status'       => 'Pending',
          'payment_message'      => 'Waiting for payment',
          'date_added'           => date('Y-m-d H:i:s')
        ];
        $products_purchases_id = DB::table('products_purchases')->insertGetId($data); 
        if (!$products_purchases_id) {
          return response()->json(['status' => 'error', 'message' => 'Something went wrong. Please try again.'], 500);
        }
        /* products_purchases */
        $product = DB::table('products')->where('products_id', $req->products_id)->first();
        $data2 = [
          'products_purchases_id'        => $products_purchases_id,
          'tasks_types_id'               => $req->tasks_types_id,
          'task'                         => $req->task,
          'task_date'                    => $req->task_date,
          'description'                  => $req->description,
          'recipient_name'               => $req->recipient_name,
          'recipient_phone'              => $req->recipient_phone,
          'delivery_request_limit'       => $product->delivery_request_limit,
          'delivery_requests_consumed'   => 1,
          'date_added'                   => date('Y-m-d H:i:s')
        ];
        $products_purchases_tasks_id               = DB::table('products_purchases_tasks')->insertGetId($data2);    
        $prod_purchased                            = DB::table('products_purchases')->where('products_purchases_id', $products_purchases_id)->first();
        $prod_purchased->products_purchases_tasks  = DB::table('products_purchases_tasks')->where('products_purchases_id', $products_purchases_id)->first();
      }

      $customer   = DB::table('users_customers')->where('users_customers_id', $req->users_customers_id)->first();
      $product    = DB::table('products')->where('products_id', $req->products_id)->first();
     
      /* send mail */ 
      $to                   = $customer->email;
      $subject              = 'Purchase Confirmation';

      $productName          = htmlspecialchars($product->name);
      $taskType             = htmlspecialchars(DB::table('tasks_types')->where('tasks_types_id', $req->tasks_types_id)->first()->name); 
      $taskName             = htmlspecialchars($req->task);
      $taskDate             = date('d-m-Y', strtotime($req->task_date));
      $description          = htmlspecialchars($req->description);
      $contactPersonName    = htmlspecialchars($req->recipient_name);
      $contactPersonPhone   = htmlspecialchars($req->recipient_phone);
      $deliveriesLimit      = $prod_purchased->products_purchases_tasks->delivery_request_limit;
      $deliveriesUsed       = $prod_purchased->products_purchases_tasks->delivery_requests_consumed;
      $message = '
        <html>
        <head>
          <style>
            body {
              font-family: Arial, sans-serif; 
              color: #333;
              margin: 0;
              padding: 20px;
              background-color: #fff;
            }
            .email-text-fullwidth {
              width: 100%;
              font-size: 14px;
              line-height: 1.5;
              margin-bottom: 20px;
            }
            .receipt-container {
              width: 100%;
              max-width: 600px;
              margin: 0 auto 20px auto;
              background: #f9f9f9;
              border: 1px solid #ccc;
              padding: 20px;
              box-sizing: border-box;
            }
            .receipt-container h2 {
              text-align: center;
              font-weight: bold;
              margin-bottom: 20px;
              font-size: 18px;
            }
            .receipt-container table {
              width: 100%;
              border-collapse: collapse;
              font-size: 14px;
              color: #333;
            }
            .receipt-container th, 
            .receipt-container td {
              border: 1px solid #ccc;
              padding: 8px 12px;
              vertical-align: top;
            }
            .receipt-container th {
              text-align: left;
              width: 180px;
              background-color: #f0f0f0;
              font-weight: 600;
            }
          </style>
        </head>
        <body>

          <div class="email-text-fullwidth">
            Dear ' . trim($customer->first_name . ' ' . ($customer->last_name ?? '')) . ',<br><br>
            Thank you for your task request with <strong>Swap Circle</strong>. Please find below the details of your requested task:
          </div>
          <div class="receipt-container">
            <h2>Swap Circle - Task Request Confirmation</h2>
            <table>
              <tr><th>Product Name</th><td>' . $productName . '</td></tr>
              <tr><th>Task Type</th><td>' . $taskType . '</td></tr>
              <tr><th>Task Name</th><td>' . $taskName . '</td></tr>
              <tr><th>Task Date</th><td>' . $taskDate . '</td></tr>
              <tr><th>Description</th><td>' . $description . '</td></tr>
              <tr><th>Contact Person</th><td>' . $contactPersonName . '</td></tr>
              <tr><th>Contact Phone</th><td>' . $contactPersonPhone . '</td></tr>
              <tr><th>Delivery Requests Limit</th><td>' . $deliveriesLimit . '</td></tr>
              <tr><th>Delivery Requests Consumed</th><td>' . $deliveriesUsed . '</td></tr>
            </table>
          </div>
          <div class="email-text-fullwidth">
            Regards,<br>
            <strong>Swap Circle Team</strong>
          </div>




















        </html>';

             

/* ========= SEND MAIL ========= */
  $this->send_simple_mail($to, $subject, $this->generatePurchaseEmailHTML_C($prod_purchased, $customer, $product, $prod_purchased->products_purchases_tasks));
       
      /* send mail */
    }
    /* handle product type C  */

    $user_tag_updated   = DB::table('users_customers')->where('users_customers_id', $req->users_customers_id)->update(['users_customers_tag' => 'Community Member']);

    $this->triggerInsuretechPurchaseSync(
      !empty($prod_purchased->products_purchases_id) ? (int) $prod_purchased->products_purchases_id : null,
      'purchase_product'
    );

    return response()->json(['status' => 'success', 'data'   => $prod_purchased], 200);
  }
  /* PURCHASE PRODUCT */

  /* CLAIM PURCHASED PRODUCT */
  public function claim_purchased_product(Request $req){
    if(isset($req->products_purchases_id) && isset($req->date) && isset($req->description) && isset($req->image1) && isset($req->image2) && isset($req->image3)) {
    //   $claim_exist = DB::table('products_purchases_claims')->where('products_purchases_id', $req->products_purchases_id)->count();

    //   if ($claim_exist == 0) {
        $data = [
          'products_purchases_id'   => $req->products_purchases_id,
          'date'                    => $req->date,
          'description'             => $req->description,
          'date_added'              => date('Y-m-d H:i:s')
        ];
        if (isset($req->image1)) {
          $image1 = $req->image1;
          $prefix = time();
          $img_name = uniqid() . '.jpeg';
          $image_path = public_path('uploads/products_claims/').$img_name;
          file_put_contents($image_path, base64_decode($image1));

          $data['image1'] = 'uploads/products_claims/'.$img_name;
        }
        if (isset($req->image2)) {
          $image2 = $req->image2;
          $prefix = time();
          $img_name = uniqid() . '.jpeg';
          $image_path = public_path('uploads/products_claims/').$img_name;
          file_put_contents($image_path, base64_decode($image2));

          $data['image2'] = 'uploads/products_claims/'.$img_name;
        }
        if (isset($req->image3)) {
          $image3 = $req->image3;
          $prefix = time();
          $img_name = uniqid() . '.jpeg';
          $image_path = public_path('uploads/products_claims/').$img_name;
          file_put_contents($image_path, base64_decode($image3));

          $data['image3'] = 'uploads/products_claims/'.$img_name;
        }
        $products_purchases_claims_id = DB::table('products_purchases_claims')->insertGetId($data);

        if ($products_purchases_claims_id) {
          $purchased_product   = DB::table('products_purchases')->where('products_purchases_id', $req->products_purchases_id)->first();
          $customer            = DB::table('users_customers')->where('users_customers_id', $purchased_product->users_customers_id)->first();
          $product             = DB::table('products')->where('products_id', $purchased_product->products_id)->first();
          
          /* send mail */ 
          $to             = $customer->email;
          $subject        = 'Claim Submission Confirmation';
          $productName    = htmlspecialchars($product->name);
          $incidentDate   = date('d-m-Y', strtotime($req->date));
          $description    = nl2br(htmlspecialchars($req->description));

          $docs = [
            $data['image1'] ?? null, 
            $data['image2'] ?? null, 
            $data['image3'] ?? null
          ];

          $imagesHtml = '';
          foreach ($docs as $doc) {
            if ($doc) {
              $imagesHtml .= '<img src="' . asset($doc) . '" style="max-width:100px; max-height:100px; margin-right:10px; vertical-align:middle;" />';
            }
          }

          $message = '
          <html>
          <head>
            <style>
              body {
                font-family: Arial, sans-serif; 
                color: #333;
                margin: 0;
                padding: 20px;
                background-color: #fff;
              }
              .email-text-fullwidth {
                width: 100%;
                font-size: 14px;
                line-height: 1.5;
                margin-bottom: 20px;
                box-sizing: border-box;
              }
              .receipt-container {
                width: 100%;
                max-width: 600px;
                margin: 0 auto 20px auto;
                background: #f9f9f9;
                border: 1px solid #ccc;
                padding: 20px;
                box-sizing: border-box;
              }
              .receipt-container h2 {
                text-align: center;
                font-weight: bold;
                margin-bottom: 20px;
                font-size: 18px;
              }
              .receipt-container table {
                width: 100%;
                border-collapse: collapse;
                font-size: 14px;
                color: #333;
              }
              .receipt-container th, 
              .receipt-container td {
                border: 1px solid #ccc;
                padding: 8px 12px;
                vertical-align: top;
              }
              .receipt-container th {
                text-align: left;
                width: 180px;
                background-color: #f0f0f0;
                font-weight: 600;
              }
              /* Flexbox for horizontal images */
              .images-row {
                display: flex;
                gap: 10px; /* spacing between images */
                flex-wrap: wrap; /* optional: wrap if screen too small */
              }
              .images-row img {
                border: 1px solid #ddd;
                border-radius: 4px;
                max-width: 100px;
                max-height: 100px;
                object-fit: contain;
              }
            </style>
          </head>
          <body>

            <div class="email-text-fullwidth">
              Dear ' . trim($customer->first_name . ' ' . ($customer->last_name ?? '')) . ',<br><br>
              Thank you for submitting your product claim with <strong>Swap Circle</strong>. Weâ€™ve received your claim and our team will begin processing it shortly.Please find below a summary of your claim details for your reference:
            </div>

            <div class="receipt-container">
              <h2>Swap Circle - Claim Receipt</h2>
              <table>
                <tr>
                  <th>Product Name</th>
                  <td>' . htmlspecialchars($product->name) . '</td>
                </tr>
                <tr>
                  <th>Date of Incident</th>
                  <td>' . date('d-m-Y', strtotime($req->date)) . '</td>
                </tr>
                <tr>
                  <th>Description</th>
                  <td>' . nl2br(htmlspecialchars($req->description)) . '</td>
                </tr>
                <tr>
                  <th>Supporting Documents</th>
                  <td class="images-row">';
                      foreach ($docs as $doc) {
                          if ($doc) {
                              $message .= '<img src="' . asset($doc) . '" alt="Supporting Document" />';
                          }
                      }
          $message .= '</td>
                </tr>
              </table>
            </div>

            <div class="email-text-fullwidth">
              Regards,<br>
              <strong>Swap Circle Team</strong>
            </div>

          </body>
          </html>';


          $sent = $this->send_simple_mail($to, $subject, $message);
          /* send mail */

          $response["code"] = 200;
          $response["status"] = "success";
          $response["message"] = 'Product claim submitted successfully.';
        } else {
          $response["code"] = 404;
          $response["status"] = "error";
          $response["message"] = "Something went wrong.";
        }
    //   } else {
    //     $response["code"] = 404;
    //     $response["status"] = "error";
    //     $response["message"] = "This product is already claimed.";
    //   }
    } else {
      $response["code"] = 404;
      $response["status"] = "error";
      $response["message"] = "All fields are needed.";
    }
    return response()
      ->json(array( 'status' => $response["status"], isset($response["message"]) ? 'message' : 'data' => isset($response["message"]) ? $response["message"] : $response["data"]))
      ->header('Content-Type', 'application/json');
  }
  /* CLAIM PURCHASED PRODUCT */

// public function initiateStripePayment(Request $req)
// {

//     // âœ… Load Stripe manually (inside function â€” namespace safe)
//     require_once base_path('vendor/stripe-php/init.php');

//     $req->validate([
//         'products_purchases_id' => 'required|integer'
//     ]);

//     $purchase = DB::table('products_purchases')
//         ->join('products', 'products.products_id', '=', 'products_purchases.products_id')
//         ->where('products_purchases.products_purchases_id', $req->products_purchases_id)
//         ->first();

//     if (!$purchase) {
//         return response()->json(['status' => 'error', 'message' => 'Invalid purchase'], 400);
//     }

//    //dd(class_exists(\Stripe\Stripe::class));

//     // âœ… Use fully-qualified Stripe class names
//     // \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
//     //Stripe::setApiKey(config('services.stripe.secret'));

//     // âœ… STATIC KEY (server-side only)
//     \Stripe\Stripe::setApiKey(
//         'STRIPE_SECRET_PLACEHOLDER'
//     );

//     // \Stripe\Stripe::setApiKey(
//     // config('services.stripe.secret')
//     // );
//     // dd(config('services.stripe.secret'));

//     // ðŸ”¹ Create PaymentIntent WITHOUT payment method
//    $intent = \Stripe\PaymentIntent::create([
//         'amount' => $purchase->price * 100,
//         'currency' => 'ngn', // or usd
//         'payment_method_types' => ['card'],
//         'metadata' => [
//             'products_purchases_id' => $purchase->products_purchases_id
//         ]
//     ]);

//     // ðŸ”¹ Save Stripe data
//     DB::table('products_purchases')
//         ->where('products_purchases_id', $purchase->products_purchases_id)
//         ->update([
//             'stripe_payment_intent' => $intent->id,
//             'stripe_payment_status' => $intent->status,
//             'payment_message' => 'Stripe payment initiated'
//         ]);

//     return response()->json([
//         'status' => 'success',
//         'stripe_payment_intent' => $intent->id,
//         'stripe_payment_status' => $intent->status
//     ]);
// }

public function initiateStripePayment(Request $req)
{
    $req->validate([
        'products_purchases_id' => 'required|integer'
    ]);

    $purchase = DB::table('products_purchases')->where('products_purchases_id', $req->products_purchases_id)->first();
    
    if (!$purchase) {
        return response()->json(['status' => 'error', 'message' => 'Invalid purchase'], 400);
    }

    // Get product details to get the price
    $product = DB::table('products')->where('products_id', $purchase->products_id)->first();
    
    if (!$product) {
        return response()->json(['status' => 'error', 'message' => 'Product not found'], 400);
    }

    if (!class_exists(\Stripe\Stripe::class)) {
        return response()->json([
            'status' => 'error',
            'message' => 'Stripe SDK is not installed. Run: composer require stripe/stripe-php',
        ], 500);
    }

    $stripeSecret = (string) config('services.stripe.secret');
    if ($stripeSecret === '') {
        return response()->json([
            'status' => 'error',
            'message' => 'Stripe secret key is missing. Set STRIPE_SECRET in .env',
        ], 500);
    }

    \Stripe\Stripe::setApiKey($stripeSecret);

     // Always use GBP
     $currency = 'gbp';
     $amount = (int) round(($product->custom_price ?? $product->price) * 100);
     if ($amount < 30) {
         return response()->json(['status' => 'error', 'message' => 'Amount below Stripe minimum'], 400);
     }


    try {
        // ðŸ”¹ Create Stripe Checkout Session
        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => $currency,
                    'product_data' => [
                         'name' => $product->name,
                         'description' => $product->description ?? 'Swap Circle Product',
                    ],
                    'unit_amount' => $amount,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => url('/users/stripe/success?session_id={CHECKOUT_SESSION_ID}&purchase_id=' . $purchase->products_purchases_id),
            'cancel_url' => url('/users/stripe/cancel?purchase_id=' . $purchase->products_purchases_id),
            'metadata' => [
                'products_purchases_id' => $purchase->products_purchases_id,
                'users_customers_id' => $purchase->users_customers_id,
                'products_id' => $purchase->products_id,
            ],
        ]);

        // ðŸ”¹ Save Stripe session data
        DB::table('products_purchases')
            ->where('products_purchases_id', $purchase->products_purchases_id)
            ->update([
                'stripe_payment_intent' => $session->id, // Using existing column to store session ID
                'payment_message' => 'Stripe Checkout session created'
            ]);

        return response()->json([
            'status' => 'success',
            'checkout_url' => $session->url,
            'session_id' => $session->id
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Failed to create Stripe Checkout session: ' . $e->getMessage()
        ], 500);
    }
}

public function handleStripeSuccess(Request $req)
{
    $req->validate([
        'session_id' => 'required|string',
        'purchase_id' => 'required|integer'
    ]);

    // Log incoming request for debugging
    \Log::info('Stripe Success Request:', [
        'session_id' => $req->session_id,
        'purchase_id' => $req->purchase_id
    ]);

    try {
        if (!class_exists(\Stripe\Stripe::class)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Stripe SDK is not installed. Run: composer require stripe/stripe-php',
            ], 500);
        }

        $stripeSecret = (string) config('services.stripe.secret');
        if ($stripeSecret === '') {
            return response()->json([
                'status' => 'error',
                'message' => 'Stripe secret key is missing. Set STRIPE_SECRET in .env',
            ], 500);
        }

        \Stripe\Stripe::setApiKey($stripeSecret);
        // Retrieve the Checkout Session from Stripe
        $session = \Stripe\Checkout\Session::retrieve($req->session_id);

        \Log::info('Stripe Session Retrieved:', [
            'session_id' => $session->id,
            'payment_status' => $session->payment_status,
            'status' => $session->status
        ]);

        if (!$session) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid Stripe session'
            ], 400);
        }

        // Verify the purchase matches
        if ($session->metadata->products_purchases_id != $req->purchase_id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Purchase ID mismatch'
            ], 400);
        }

        // Get purchase details
        $purchase = DB::table('products_purchases')->where('products_purchases_id', $req->purchase_id)->first();
        
        if (!$purchase) {
            return response()->json([
                'status' => 'error',
                'message' => 'Purchase not found'
            ], 404);
        }

        // Check payment status
        if ($session->payment_status === 'paid') {
            // Update purchase record
            DB::table('products_purchases')
                ->where('products_purchases_id', $req->purchase_id)
                ->update([
                    'payment_status' => 'Successful',
                    'stripe_payment_status' => 'succeeded',
                    'payment_message' => 'Payment completed successfully',
                    'stripe_payment_intent' => $session->payment_intent,
                    'date_modified' => date('Y-m-d H:i:s')
                ]);

            \Log::info('Payment Status Updated to Paid', [
                'purchase_id' => $req->purchase_id,
                'payment_intent' => $session->payment_intent
            ]);

            // Get user and product details for email
            $user = DB::table('users_customers')->where('users_customers_id', $purchase->users_customers_id)->first();
            $product = DB::table('products')->where('products_id', $purchase->products_id)->first();

            // Send confirmation email based on product type
            if ($user && $product) {
                $to = $user->email;
                $subject = 'Purchase Confirmation - ' . $product->name;
                
                \Log::info('Email Sending Attempt:', [
                    'user_email' => $to,
                    'product_type' => $purchase->product_type,
                    'purchase_id' => $purchase->products_purchases_id
                ]);
                
                if ($purchase->product_type === 'A' || $purchase->product_type === 'B') {
                    // Get beneficiary details for product types A & B
                    $beneficiary = DB::table('products_purchases_beneficiaries')
                        ->where('products_purchases_id', $purchase->products_purchases_id)
                        ->first();
                    
                    \Log::info('Beneficiary Data:', ['beneficiary_found' => $beneficiary ? 'yes' : 'no']);
                    
                    if ($beneficiary) {
                        $message = $this->generatePurchaseEmailHTML_AB($purchase, $user, $product, $beneficiary, $purchase->products_purchases_id);
                        $mailResult = $this->send_simple_mail($to, $subject, $message);
                        
                        \Log::info('Email Sent Result:', [
                            'mail_result' => $mailResult,
                            'to' => $to,
                            'subject' => $subject
                        ]);
                    } else {
                        \Log::warning('No beneficiary found for purchase', ['purchase_id' => $purchase->products_purchases_id]);
                    }
                } elseif ($purchase->product_type === 'C') {
                    // Get task details for product type C
                    $task = DB::table('products_purchases_tasks')
                        ->where('products_purchases_id', $purchase->products_purchases_id)
                        ->first();
                    
                    \Log::info('Task Data:', ['task_found' => $task ? 'yes' : 'no']);
                    
                    if ($task) {
                        $message = $this->generatePurchaseEmailHTML_C($purchase, $user, $product, $task, $purchase->products_purchases_id);
                        $mailResult = $this->send_simple_mail($to, $subject, $message);
                        
                        \Log::info('Email Sent Result:', [
                            'mail_result' => $mailResult,
                            'to' => $to,
                            'subject' => $subject
                        ]);
                    } else {
                        \Log::warning('No task found for purchase', ['purchase_id' => $purchase->products_purchases_id]);
                    }
                } else {
                    \Log::warning('Unknown product type', ['product_type' => $purchase->product_type]);
                }
            } else {
                \Log::warning('User or Product not found', [
                    'user_found' => $user ? 'yes' : 'no',
                    'product_found' => $product ? 'yes' : 'no',
                    'purchase_id' => $purchase->products_purchases_id
                ]);
            }

            $this->triggerInsuretechPurchaseSync((int) $purchase->products_purchases_id, 'stripe_handle_success');

            return response()->json([
                'status' => 'success',
                'message' => 'Payment verified and purchase confirmed successfully'
            ]);

        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Payment not completed. Status: ' . $session->payment_status
            ], 400);
        }

    } catch (\Exception $e) {
        \Log::error('Stripe Success Handler Error:', [
            'error' => $e->getMessage(),
            'session_id' => $req->session_id,
            'purchase_id' => $req->purchase_id
        ]);
        
        return response()->json([
            'status' => 'error',
            'message' => 'Payment verification failed: ' . $e->getMessage()
        ], 500);
    }
}

public function handleStripeCancel(Request $req)
{
    $req->validate([
        'purchase_id' => 'required|integer'
    ]);

    try {
        // Update purchase record to reflect cancellation
        DB::table('products_purchases')
            ->where('products_purchases_id', $req->purchase_id)
            ->update([
                'payment_message' => 'Payment cancelled by user',
                'date_modified' => date('Y-m-d H:i:s')
            ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Payment was cancelled. You can try purchasing again.'
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Failed to process cancellation: ' . $e->getMessage()
        ], 500);
    }
}


public function sendTestMail()
{
    $to = 'devjammer991@gmail.com';

    Mail::send([], [], function ($message) use ($to) {
        $message->to($to)
            ->subject('Test Email from Laravel')
            ->setBody(
                '<h2>Test Email</h2>
                 <p>This is working now âœ…</p>
                 ',
                'text/html'
            );
    });

    return "Mail Sent Successfully!";
}

private function triggerInsuretechPurchaseSync(?int $purchaseId, string $source): void
{
    if (! $purchaseId || $purchaseId <= 0) {
        return;
    }

    try {
        app(InsuretechSyncService::class)->runSync(['products_purchases_id' => $purchaseId]);
    } catch (\Throwable $syncException) {
        \Log::warning('Insuretech sync failed.', [
            'source' => $source,
            'purchase_id' => $purchaseId,
            'error' => $syncException->getMessage(),
        ]);
    }
}
} 
