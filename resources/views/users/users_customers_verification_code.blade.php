<!DOCTYPE html>
<html lang="en">
    <head>
        <?php 
            $system_image=DB::table('system_settings')->select('description')->where('type', 'system_image')->get(); 
            $system_name=DB::table('system_settings')->select('description')->where('type', 'system_name')->get();
            $auth_bg_image=DB::table('system_settings')->select('description')->where('type', 'auth_bg_image')->first()->description; 
            $auth_image=DB::table('system_settings')->select('description')->where('type', 'auth_image')->first()->description;  
        ?>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo $system_name[0]->description; ?> :: Users Customers Portal</title>

        <link href="{{ asset('users/assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('users/assets/css/style.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('users/assets/css/custom.css') }}" rel="stylesheet" type="text/css" />
        <style>
            .otp-input { width:55px !important; height:55px !important; text-align:center; font-size:22px; font-weight:bold; border:2px solid #1a3c6e !important; border-radius:8px !important; box-shadow:none !important; background:#fff; color:#1a3c6e; padding:0; }
            .otp-input:focus { border-color:#0d6efd !important; background:#f0f4fb; outline:none; box-shadow:0 0 0 3px rgba(13,110,253,.15) !important; }
        </style>
    </head>
    <body>
        <div id="wrapper">
            <div class="container-fluid">
                <div class="row login min-vh-100">
                    <!-- LEFT SECTION START -->
                    <!-- <div class="col-lg-6 left d-lg-flex align-items-center justify-content-center justify-content-md-start py-5 d-none">
                        <img src="{{ asset('users/assets/images/Rocket_Boy_Flatline.png') }}" class="img-fluid w-75 mx-auto" alt="image">
                    </div> -->
                    <div class="col-lg-6 left d-lg-flex align-items-center justify-content-center justify-content-md-start py-5 d-none" style="background-image: url('{{ asset($auth_bg_image) }}'); background-size: cover; background-position: center right;">
                        <img src="{{ asset($auth_image) }}" class="img-fluid w-75 mx-auto" alt="image">    
                    </div>
                    <!-- LEFT SECTION END -->

                    <!-- RIGHT SECTION START -->
                    <div class="col-lg-6 d-flex flex-column justify-content-sm-around justify-content-center align-items-center flex-wrap  py-5">
                        <div class="logo text-center">
                            <img src="{{ asset('uploads/system_image/'.$system_image[0]->description) }}" class="img-fluid img-logo" alt="image">
                            <h3 class="main-heading mt-5">Verification Code</h3>
                            <p class="sub-heading mt-2">Enter verification code sent to your <br/> registered email.</p>
                            <!-- FORM VERIFICATION CODE START -->
                            <form id="frm_verification_code" class="mt-5">
                                @csrf
                                <!-- USERS CUSTOMERS ID -->
                                <input type="hidden" id="users_customers_id" value="{{ $id }}">
                                <!-- OTP -->
                                <div class="form-group position-relative mb-3 input_otp">
                                    <div class="d-flex flex-row justify-content-center align-items-center gap-2">
                                        <input type="text" class="form-control otp-input" name="digit_1" id="digit_1" maxlength="1" autocomplete="off" style="width:55px;height:55px;text-align:center;font-size:22px;font-weight:bold;border:2px solid #1a3c6e;border-radius:8px;box-shadow:none;">
                                        <input type="text" class="form-control otp-input" name="digit_2" id="digit_2" maxlength="1" autocomplete="off" style="width:55px;height:55px;text-align:center;font-size:22px;font-weight:bold;border:2px solid #1a3c6e;border-radius:8px;box-shadow:none;">
                                        <input type="text" class="form-control otp-input" name="digit_3" id="digit_3" maxlength="1" autocomplete="off" style="width:55px;height:55px;text-align:center;font-size:22px;font-weight:bold;border:2px solid #1a3c6e;border-radius:8px;box-shadow:none;">
                                        <input type="text" class="form-control otp-input" name="digit_4" id="digit_4" maxlength="1" autocomplete="off" style="width:55px;height:55px;text-align:center;font-size:22px;font-weight:bold;border:2px solid #1a3c6e;border-radius:8px;box-shadow:none;">
                                    </div>
                                    <span class="error_msg" id="error_otp"></span>
                                </div>
                                <div class="pt-4">
                                    <button type="submit" class="btn btn-login btn-primary w-100 mt-4">Next</button>
                                </div>
                            </form>
                            <!-- FORM VERIFICATION CODE END -->
                        </div>
                    </div>
                    <!-- RIGHT SECTION END -->
                </div>
            </div>
        </div>

        <!-- SCRIPTS -->
        <script src="{{ asset('users/assets/js/bootstrap.bundle.js') }}"></script>
        <script src="{{ asset('users/assets/js/jquery.min.js') }}"></script>
        <script src="{{ asset('users/assets/js/jquery.validate.min.js') }}"></script>
        <script>
            $(document).ready(function() {
                // -------------- OTP INPUT BEHAVIOUR ------------- //
                var otpInputs = $(".otp-input");

                otpInputs.on("keyup", function(e) {
                    var val = $(this).val().replace(/[^0-9]/g, "");
                    $(this).val(val);
                    if (val && $(this).next(".otp-input").length) {
                        $(this).next(".otp-input").focus();
                    }
                    if (e.key === "Backspace" && !val && $(this).prev(".otp-input").length) {
                        $(this).prev(".otp-input").focus();
                    }
                });

                // Copy-paste support: paste 4 digits into boxes
                otpInputs.on("paste", function(e) {
                    e.preventDefault();
                    var pasted = (e.originalEvent.clipboardData || window.clipboardData).getData("text").replace(/[^0-9]/g, "").substring(0, 4);
                    otpInputs.each(function(i) {
                        $(this).val(pasted[i] || "");
                    });
                    otpInputs.last().focus();
                });

                otpInputs.first().focus();
                // -------------- OTP INPUT BEHAVIOUR ------------- //

                // -------------- FORM VERIFICATION CODE VALIDATION ------------- //
                $("#frm_verification_code").validate({
                    rules: {
                        digit_1: {
                            required: true
                        },
                        digit_2: {
                            required: true
                        },
                        digit_3: {
                            required: true
                        },
                        digit_4: {
                            required: true
                        },
                    },
                    messages: {
                        digit_1: {
                            required: "Enter 4 digit verification code."
                        },
                        digit_2: {
                            required: "Enter 4 digit verification code."
                        },
                        digit_3: {
                            required: "Enter 4 digit verification code."
                        },
                        digit_4: {
                            required: "Enter 4 digit verification code."
                        },
                    },
                    errorPlacement: function (error, element) {
                        //error.insertAfter (element);
                        if ((element.attr("name") == "digit_1") || (element.attr("name") == "digit_2") || (element.attr("name") == "digit_3") || (element.attr("name") == "digit_4")) {
                            $("#error_otp").html(error);
                        } 
                    }
                });
                // -------------- FORM VERIFICATION CODE VALIDATION ------------- //

                // -------------- FORM VERIFICATION CODE SUBMISSION ------------- //
                $("#frm_verification_code").on("submit", function (event) {
                    event.preventDefault();
                    if ($("#frm_verification_code").valid()) {
                        var users_customers_id = $("#users_customers_id").val();  
                        var digit_1 = $("#digit_1").val();                        
                        var digit_2 = $("#digit_2").val();                        
                        var digit_3 = $("#digit_3").val();                       
                        var digit_4 = $("#digit_4").val();                        
                        var otp = digit_1 + digit_2 + digit_3 + digit_4;          

                        var settings = {
                            "url": "{{ rtrim(config('app.api_url'), '/') }}/users_customers_verify_otp",
                            "method": "POST",
                            "timeout": 0,
                            "headers": {
                                "Content-Type": "application/json"
                            },
                    
                            "data": JSON.stringify({
                                "users_customers_id": users_customers_id,
                                "verify_otp": otp,
                            }),
                        };

                        $.ajax(settings).done(function (response) {
                            if (response.status == "error"){ 
                                Command: toastr["error"](response.message);
                            } else{
                                // OTP verified - now set session and go to congratulations
                                $.ajax({
                                    "url": "/users/signup_process",
                                    "method": "POST",
                                    "timeout": 0,
                                    "headers": { "Content-Type": "application/json" },
                                    "data": JSON.stringify({
                                        "_token":               "{{ csrf_token() }}",
                                        "users_customers_type": response.data.users_customers_type,
                                        "users_customers_id":   response.data.users_customers_id,
                                        "profile_pic":          response.data.profile_pic,
                                        "first_name":           response.data.first_name,
                                        "last_name":            response.data.last_name,
                                        "company_name":         response.data.company_name,
                                        "email":                response.data.email,
                                        "phone":                response.data.phone,
                                    }),
                                }).done(function(res) {
                                    window.location.href = "/users/signup_verified";
                                });
                            }
                        });
                    }
                });
                // -------------- FORM VERIFICATION CODE SUBMISSION ------------- //
            });
        </script>
        <!-- SCRIPTS -->

         <!-- TOASTERS -->
        <link href="{{asset('toasters/toastr.min.css')}}" rel="stylesheet" type="text/css" />   
        <script src="{{asset('toasters/toastr.min.js')}}" type="text/javascript"></script>
        <script>
            toastr.options = {
                "closeButton": true,
                "debug": false,
                "positionClass": "toast-top-right",
                "onclick": null,
                "showDuration": "1000",
                "hideDuration": "1000",
                "timeOut": "5000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            }
            //Command: toastr['success']("hello");

            <?php if(Session::has('success')){ ?> Command: toastr['success']("<?php echo Session('success'); ?>"); <?php } ?>
            <?php if(Session::has('error')){ ?> Command: toastr['error']("<?php echo Session('error'); ?>"); <?php } ?>
            <?php if(Session::has('warning')){ ?> Command: toastr['warning']("<?php echo Session('warning'); ?>"); <?php } ?>
            <?php if(Session::has('info')){ ?> Command: toastr['info']("<?php echo Session('info'); ?>"); <?php } ?>
        </script>
        <!-- TOASTERS -->
    </body>
</html>