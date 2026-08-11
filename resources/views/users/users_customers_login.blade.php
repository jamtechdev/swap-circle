<!DOCTYPE html>
<html lang="en">
    <head>
        <?php
            $system_image_file = 'logo.png';
            $system_name_text = config('app.name', 'Swap Circle');
            $auth_bg_image = 'public/users/assets/images/login-bg.jpg';
            $auth_image = 'public/users/assets/images/Rocket_Boy_Flatline.png';
            $main_heading_label1 = 'Welcome to';
            $main_heading_label2 = 'Swap Circle';
            $subheading_label = 'A community exchange platform for services. Connect, exchange, and grow together.';
            $forgot_password_link_label = 'Forgot Password?';
            $btn_login_label = 'Login';
            $signup_text_lable = "New to the community?";
            $signup_link_label = 'Get started here!';

            try {
                $system_image_file = optional(DB::table('system_settings')->select('description')->where('type', 'system_image')->first())->description ?: $system_image_file;
                $system_name_text = optional(DB::table('system_settings')->select('description')->where('type', 'system_name')->first())->description ?: $system_name_text;
                $auth_bg_image = optional(DB::table('system_settings')->select('description')->where('type', 'auth_bg_image')->first())->description ?: $auth_bg_image;
                $auth_image = optional(DB::table('system_settings')->select('description')->where('type', 'auth_image')->first())->description ?: $auth_image;
                $main_heading_label1 = optional(DB::table('system_settings')->select('description')->where('type', 'main_heading_label1')->first())->description ?: $main_heading_label1;
                $main_heading_label2 = optional(DB::table('system_settings')->select('description')->where('type', 'main_heading_label2')->first())->description ?: $main_heading_label2;
                $subheading_label = optional(DB::table('system_settings')->select('description')->where('type', 'subheading_label')->first())->description ?: $subheading_label;
                $forgot_password_link_label = optional(DB::table('system_settings')->select('description')->where('type', 'forgot_password_link_label')->first())->description ?: $forgot_password_link_label;
                $btn_login_label = optional(DB::table('system_settings')->select('description')->where('type', 'btn_login_label')->first())->description ?: $btn_login_label;
                $signup_text_lable = optional(DB::table('system_settings')->select('description')->where('type', 'signup_text_lable')->first())->description ?: $signup_text_lable;
                $signup_link_label = optional(DB::table('system_settings')->select('description')->where('type', 'signup_link_label')->first())->description ?: $signup_link_label;
            } catch (\Throwable $e) {
                // Keep defaults when DB is unavailable.
            }
        ?>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo $system_name_text; ?> :: Users Customers Portal</title>
        
        <link rel="icon" type="image" sizes="24x24" href="{{ asset('uploads/system_image/favico.png') }}">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600;9..144,700;9..144,800&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
        <link href="{{ asset('users/assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('users/assets/css/style.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('users/assets/css/custom.css') }}" rel="stylesheet" type="text/css" />
    </head>
    <body class="login-page-body">
        <div id="wrapper" class="login-wrapper">
            <div class="container-fluid p-0">
                <div class="row login g-0 min-vh-100">
                    <!-- LEFT SECTION START -->
                    <div class="col-lg-6 left community-hero d-none d-lg-flex flex-column justify-content-between">
                        <div class="community-hero-glow"></div>
                        <div class="community-hero-orb community-hero-orb-1"></div>
                        <div class="community-hero-orb community-hero-orb-2"></div>
                        <div class="community-hero-top">
                            <p class="community-hero-brand">Swap Circle</p>
                        </div>
                        <div class="community-hero-mid text-center">
                            <img src="{{ asset($auth_image) }}" class="img-fluid community-hero-img" alt="Swap Circle community">
                        </div>
                        <div class="community-hero-copy text-white">
                            <p class="community-hero-eyebrow mb-3">Community Exchange Platform</p>
                            <h2 class="community-hero-title mb-3">Trade services.<br>Build connections.</h2>
                            <p class="community-hero-text mb-0">Swap Circle brings people together to exchange skills, services, and opportunities inside one trusted community.</p>
                        </div>
                    </div>
                    <!-- LEFT SECTION END -->

                    <!-- RIGHT SECTION START -->
                    <div class="col-lg-6 d-flex flex-column justify-content-center align-items-center py-5 px-3 login-panel">
                        <div class="login-card w-100">
                            <div class="logo text-center">
                                <img src="{{ asset('uploads/system_image/'.$system_image_file) }}" class="img-fluid img-logo" alt="{{ $system_name_text }}">
                                <h1 class="main-heading login-welcome-heading mt-4 mb-0">
                                    <span class="d-block welcome-line">{{ $main_heading_label1 }}</span>
                                    <span class="community-brand-heading">{{ $main_heading_label2 }}</span>
                                </h1>
                                <p class="sub-heading community-subheading mt-3 mb-0">{{ $subheading_label }}</p>
                            </div>
                            <div class="login-tabs text-center mt-4 w-100 d-flex flex-column align-items-center">
                                <ul class="nav nav-pills mb-4 mx-auto" id="pills-tab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                      <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">Individual</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                      <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">Corporate</button>
                                    </li>
                                </ul>
                                <div class="tab-content w-100" id="pills-tabContent">
                                    <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab" tabindex="0">
                                        <!-- FORM LOGIN INDIVIDUAL START -->
                                        <form id="frm_login_individual">
                                            @csrf
                                            <div class="form-group position-relative mb-3">
                                                <span class="input-icon"><img src="{{ asset('users/assets/images/icons/email.png') }}" class="img-fluid" alt=""></span>
                                                <input type="email" class="form-control" placeholder="Email address" aria-label="Email address" name="email" id="email" autocomplete="email">
                                                <span class="error_msg" id="error_email"></span>
                                            </div>
                                            <div class="form-group position-relative w-pass mb-2">
                                                <span class="input-icon"><img src="{{ asset('users/assets/images/icons/lock.png') }}" class="img-fluid" alt=""></span>
                                                <input type="password" class="form-control" placeholder="Enter password" aria-label="Enter password" name="password" id="password" autocomplete="current-password">
                                                <span class="input-icon right"><img src="{{ asset('users/assets/images/icons/Eye-slash 1.png') }}" class="img-fluid" id="icon_password" alt="Show password"></span>
                                                <span class="error_msg" id="error_password"></span>
                                                <a href="{{ url('/users/forgot_password') }}" class="forgot-link text-success">{{ $forgot_password_link_label }}</a>
                                            </div>
                                            <button type="submit" class="btn btn-login btn-primary w-100 mt-4">{{ $btn_login_label }}</button>
                                        </form>
                                        <!-- FORM LOGIN INDIVIDUAL END -->
                                    </div>
                                    <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab" tabindex="0">
                                        <!-- FORM LOGIN COMPANY START -->
                                        <form id="frm_login_company">
                                            @csrf
                                            <div class="form-group position-relative mb-3">
                                                <span class="input-icon"><img src="{{ asset('users/assets/images/icons/email.png') }}" class="img-fluid" alt=""></span>
                                                <input type="email" class="form-control" placeholder="Email address" aria-label="Email address" name="company_email" id="company_email" autocomplete="email">
                                                <span class="error_msg" id="error_company_email"></span>
                                            </div>
                                            <div class="form-group position-relative w-pass mb-2">
                                                <span class="input-icon"><img src="{{ asset('users/assets/images/icons/lock.png') }}" class="img-fluid" alt=""></span>
                                                <input type="password" class="form-control" placeholder="Enter password" aria-label="Enter password" name="company_password" id="company_password" autocomplete="current-password">
                                                <span class="input-icon right"><img src="{{ asset('users/assets/images/icons/Eye-slash 1.png') }}" class="img-fluid" id="icon_company_password" alt="Show password"></span>
                                                <span class="error_msg" id="error_company_password"></span>
                                                <a href="{{ url('/users/forgot_password') }}" class="forgot-link text-success">{{ $forgot_password_link_label }}</a>
                                            </div>
                                            <button type="submit" class="btn btn-login btn-primary w-100 mt-4">{{ $btn_login_label }}</button>
                                        </form>
                                        <!-- FORM LOGIN COMPANY END -->
                                    </div>
                                </div>
                                <p class="signup-prompt mt-4 mb-0">{{ $signup_text_lable }} <a href="{{ url('/users/signup') }}">{{ $signup_link_label }}</a></p>
                            </div>
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
                    } else{
                        input.attr("type", "password");
                    }
                });
                $("#icon_company_password").on("click", function() {
                    var input = $("#company_password");
                    if (input.attr("type") === "password") {
                        input.attr("type", "text");
                    } else{
                        input.attr("type", "password");
                    }
                });
                // -------------- SHOW / HIDE PASSWORD VALUE ------------- //

                // -------------- FORM LOGIN INDIVIDUAL VALIDATION ------------- //
                $("#frm_login_individual").validate({
                    rules: {
                        email: {
                            required: true,
                            email: true
                        },
                        password: {
                            required: true,
                        },
                    },
                    messages: {
                        email: {
                            required: "This field is required.",
                            email: "Please enter a valid email address."
                        },
                        password: {
                            required: "This field is required."
                        },
                    },
                    errorPlacement: function (error, element) {
                        //error.insertAfter (element);
                        if (element.attr("name") == "email") {
                            $("#error_email").html(error);
                        } else if (element.attr("name") == "password") {
                            $("#error_password").html(error);
                        }
                    }
                }); 
                // -------------- FORM LOGIN INDIVIDUAL VALIDATION ------------- //

                // -------------- FORM LOGIN INDIVIDUAL SUBMISSION ------------- //
                $("#frm_login_individual").on("submit", function (event) { //alert('hy'); alert("{{ rtrim(config('app.api_url'), '/') }}/signin");
                    event.preventDefault();
                    if ($("#frm_login_individual").valid()) {
                        var email = $("#email").val();        
                        var password = $("#password").val(); 

                        var settings = {
                            // "url": "{{ env('API_URL') }}" + "signin",
                            "url": "{{ rtrim(config('app.api_url'), '/') }}/signin",
                            "method": "POST",
                            "timeout": 0,
                            "headers": {
                                "Content-Type": "application/json"
                            },
                    
                            "data": JSON.stringify({
                                "email": email,
                                "password": password,
                            }),
                        };

                        $.ajax(settings).done(function (response) { 
                            if (response.status == "error") { 
                                Command: toastr["error"](response.message);
                            } else{
                                if (response.data.users_customers_type == "Individual") {
                                    var settings = {
                                        "url": "/",
                                        "method": "POST",
                                        "timeout": 0,
                                        "headers": {
                                            "Content-Type": "application/json"
                                        },
                    
                                        "data": JSON.stringify({
                                            //"data": response.data,
                                            "_token": "{{ csrf_token() }}",
                                            "users_customers_type": response.data.users_customers_type,
                                            "users_customers_id": response.data.users_customers_id,
                                            "profile_pic": response.data.profile_pic,
                                            "first_name": response.data.first_name,
                                            "last_name": response.data.last_name,
                                            "email": response.data.email,
                                            "phone": response.data.phone,
                                        }),
                                    };

                                    $.ajax(settings).done(function (response) {
                                        if (response == true) { 
                                            window.location.href = "/users/products";
                                        } else{
                                            Command: toastr["error"]("Oops! Something went wrong. Try again");
                                        } 
                                    });
                                } else{
                                    Command: toastr["error"]("Please select the correct user type for login.");
                                }
                            }
                        });
                    }
                });
                // -------------- FORM LOGIN INDIVIDUAL SUBMISSION ------------- //

                // -------------- FORM LOGIN COMPANY VALIDATION ------------- //
                $("#frm_login_company").validate({
                    rules: {
                        company_email: {
                            required: true,
                            email: true
                        },
                        company_password: {
                            required: true
                        },
                    },
                    messages: {
                        company_email: {
                            required: "This field is required.",
                            email: "Please enter a valid email address."
                        },
                        company_password: {
                            required: "This field is required."
                        },
                    },
                    errorPlacement: function (error, element) {
                        //error.insertAfter (element);
                        if (element.attr("name") == "company_email") {
                            $("#error_company_email").html(error);
                        } else if (element.attr("name") == "company_password") {
                            $("#error_company_password").html(error);
                        }
                    }
                }); 
                // -------------- FORM LOGIN COMPANY VALIDATION ------------- //

                // -------------- FORM LOGIN COMPANY SUBMISSION ------------- //
                $("#frm_login_company").on("submit", function (event) {
                    event.preventDefault();
                    if ($("#frm_login_company").valid()) {
                        var email = $("#company_email").val();        
                        var password = $("#company_password").val();  

                        var settings = {
                            "url": "{{ rtrim(config('app.api_url'), '/') }}/signin",
                            "method": "POST",
                            "timeout": 0,
                            "headers": {
                                "Content-Type": "application/json"
                            },
                    
                            "data": JSON.stringify({
                                "email": email,
                                "password": password,
                            }),
                        };

                        $.ajax(settings).done(function (response) {
                            if (response.status == "error") { 
                                Command: toastr['error'](response.message);
                            } else{
                                if (response.data.users_customers_type == "Company") {
                                    var settings = {
                                        "url": "/",
                                        "method": "POST",
                                        "timeout": 0,
                                        "headers": {
                                            "Content-Type": "application/json"
                                        },
                    
                                        "data": JSON.stringify({
                                            //"data": response.data,
                                            "_token": "{{ csrf_token() }}",
                                            "users_customers_type": response.data.users_customers_type,
                                            "users_customers_id": response.data.users_customers_id,
                                            "profile_pic": response.data.profile_pic,
                                            "company_name": response.data.company_name,
                                            "first_name": response.data.first_name,
                                            "email": response.data.email,
                                            "phone": response.data.phone,
                                        }),
                                    };

                                    $.ajax(settings).done(function (response) {
                                        if (response == true){ 
                                            window.location.href = "/users/products";
                                        } else{
                                            Command: toastr["error"]("Oops! Something went wrong. Try again");
                                        } 
                                    });  
                                } else{
                                    Command: toastr["error"]("Please select the correct user type for login.");
                                }
                            }
                        });
                    }
                });
                // -------------- FORM LOGIN COMPANY SUBMISSION ------------- //
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