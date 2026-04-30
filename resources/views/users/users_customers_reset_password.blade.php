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
                            <h3 class="main-heading mt-5">Reset Password?</h3>
                            <p class="sub-heading mt-2">Create new password for your <br/> <?php echo $system_name[0]->description; ?> account.</p>
                            <!-- FORM RESET PASSWORD START -->
                            <form id="frm_reset_password" class="mt-5">
                                @csrf
                                <!-- EMAIL -->
                                <input type="hidden" id="email" value="{{ $email}}">
                                <!-- OTP -->
                                <input type="hidden" id="otp" value="{{ $otp }}">
                                <!-- PASSWORD -->
                                <div class="form-group position-relative w-pass mb-3">
                                    <span class="input-icon"><img src="{{ asset('users/assets/images/icons/lock.png') }}" class="img-fluid"></span>
                                    <input type="password" class="form-control" placeholder="New password" aria-label="Password" name="password" id="password">
                                    <span class="input-icon right"><img src="{{ asset('users/assets/images/icons/Eye-slash 1.png') }}" class="img-fluid" id="icon_password"></span>
                                    <span class="error_msg" id="error_password"></span>
                                </div>
                                <!-- CONFIRM PASSWORD -->
                                <div class="form-group position-relative w-pass mb-3">
                                    <span class="input-icon"><img src="{{ asset('users/assets/images/icons/lock.png') }}" class="img-fluid"></span>
                                    <input type="password" class="form-control" placeholder="Confirm new password" aria-label="Confirm password" name="confirm_password" id="confirm_password">
                                    <span class="input-icon right"><img src="{{ asset('users/assets/images/icons/Eye-slash 1.png') }}" class="img-fluid" id="icon_confirm_password"></span>
                                    <span class="error_msg" id="error_confirm_password"></span>
                                </div>
                                <div class="pt-4">
                                    <button type="submit" class="btn btn-login btn-primary w-100 mt-4">Next</button>
                                </div>
                            </form>
                            <!-- FORM RESET PASSWORD END -->
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
                // -------------- SHOW / HIDE PASSWORD VALUE ------------- //
                $("#icon_password").on("click", function() {
                    var input = $("#password");
                    if (input.attr("type") === "password") {
                        input.attr("type", "text");
                    } else {
                        input.attr("type", "password");
                    }
                });
                $("#icon_confirm_password").on("click", function() {
                    var input = $("#confirm_password");
                    if (input.attr("type") === "password") {
                        input.attr("type", "text");
                    } else {
                        input.attr("type", "password");
                    }
                });
                // -------------- SHOW / HIDE PASSWORD VALUE ------------- //

                // -------------- FORM RESET PASSWORD VALIDATION ------------- //
                $("#frm_reset_password").validate({
                    rules: {
                        password: {
                            required: true,
                            minlength: 7
                        },
                        confirm_password: {
                            required: true,
                            equalTo: "#password"
                        }
                    },
                    messages: {
                        password: {
                            required: "This field is required.",
                            minlength: "Password should be at least 7 characters long."
                        },
                        confirm_password: {
                            required: "This field is required.",
                            equalTo: "Please enter the same value as password."
                        },
                    },
                    errorPlacement: function (error, element) {
                        //error.insertAfter (element);
                        if (element.attr("name") == "password") {
                            $("#error_password").html(error);
                        } else if (element.attr("name") == "confirm_password") {
                            $("#error_confirm_password").html(error);
                        }
                    }
                });
                // -------------- FORM RESET PASSWORD VALIDATION ------------- //

                // -------------- FORM RESET PASSWORD SUBMISSION ------------- //
                $("#frm_reset_password").on("submit", function (event) {
                    event.preventDefault();
                    if ($("#frm_reset_password").valid()) {
                        var email = $("#email").val();                        
                        var otp = $("#otp").val();                            
                        var password = $("#password").val();                  
                        var confirm_password = $("#confirm_password").val();  

                        var settings = {
                            "url": "{{ rtrim(config('app.api_url'), '/') }}/modify_password",
                            "method": "POST",
                            "timeout": 0,
                            "headers": {
                                "Content-Type": "application/json"
                            },
                    
                            "data": JSON.stringify({
                                "email": email,
                                "otp": otp,
                                "password": password,
                                "confirm_password": confirm_password,
                            }),
                        };

                        $.ajax(settings).done(function (response) {
                            if (response.status == "error"){ 
                                Command: toastr["error"](response.message);
                            } else{
                                window.location.href = "/";
                            }
                        });
                    }
                });
                // -------------- FORM RESET PASSWORD SUBMISSION ------------- //
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