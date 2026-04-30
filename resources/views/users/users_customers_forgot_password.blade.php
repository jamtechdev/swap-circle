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
                            <h3 class="main-heading mt-5">Forget Password?</h3>
                            <p class="sub-heading mt-2">Enter your registered email to reset <br/> your password.</p>
                            <!-- FORM FORGOT PASSWORD START -->
                            <form id="frm_forgot_password" class="mt-5">
                                @csrf
                                <!-- EMAIL -->
                                <div class="form-group position-relative mb-3">
                                    <span class="input-icon"><img src="{{ asset('users/assets/images/icons/email.png') }}" class="img-fluid" ></span>
                                    <input type="email" class="form-control" placeholder="Email address" aria-label="Email" name="email" id="email">
                                    <span class="error_msg" id="error_email"></span>
                                </div>
                                <div class="pt-4">
                                    <button type="submit" class="btn btn-login btn-primary w-100 mt-4">Next</button>
                                </div>
                            </form>
                            <!-- FORM FORGOT PASSWORD END -->
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
                // -------------- FORM FORGOT PASSWORD VALIDATION ------------- //
                $("#frm_forgot_password").validate({
                    rules: {
                        email: {
                            required: true,
                            email: true
                        },
                    },
                    messages: {
                        email: {
                            required: "This field is required.",
                            email: "Please enter a valid email address."
                        },
                    },
                    errorPlacement: function (error, element) {
                        //error.insertAfter (element);
                        if (element.attr("name") == "email") {
                            $("#error_email").html(error);
                        }
                    }
                });
                // -------------- FORM FORGOT PASSWORD VALIDATION ------------- //

                // -------------- FORM FORGOT PASSWORD SUBMISSION ------------- //
                $("#frm_forgot_password").on("submit", function (event) {
                    event.preventDefault();
                    if ($("#frm_forgot_password").valid()) {
                        var email = $("#email").val();  

                        var settings = {
                            "url": "{{ rtrim(config('app.api_url'), '/') }}/forgot_password",
                            "method": "POST",
                            "timeout": 0,
                            "headers": {
                                "Content-Type": "application/json"
                            },
                    
                            "data": JSON.stringify({
                                "email": email,
                            }),
                        };

                        $.ajax(settings).done(function (response) {
                            if (response.status == "error") { 
                                Command: toastr["error"](response.message);
                            } else{                      
                                var users_customers_id = response.data.data.users_customers_id;  alert(response.data.otp);
                                window.location.href = "/users/verification_code/" + users_customers_id;
                            }
                        });  
                    }
                });
                // -------------- FORM FORGOT PASSWORD SUBMISSION ------------- //
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