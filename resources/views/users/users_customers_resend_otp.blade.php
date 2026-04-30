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
        <title><?php echo $system_name[0]->description; ?> :: Resend OTP</title>

        <link href="{{ asset('users/assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('users/assets/css/style.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('users/assets/css/custom.css') }}" rel="stylesheet" type="text/css" />
    </head>
    <body>
        <div id="wrapper">
            <div class="container-fluid">
                <div class="row login min-vh-100">
                    <!-- LEFT SECTION -->
                    <div class="col-lg-6 left d-lg-flex align-items-center justify-content-center justify-content-md-start py-5 d-none" style="background-image: url('{{ asset($auth_bg_image) }}'); background-size: cover; background-position: center right;">
                        <img src="{{ asset($auth_image) }}" class="img-fluid w-75 mx-auto" alt="image">    
                    </div>

                    <!-- RIGHT SECTION -->
                    <div class="col-lg-6 d-flex flex-column justify-content-sm-around justify-content-center align-items-center flex-wrap py-5">
                        <div class="logo text-center">
                            <img src="{{ asset('uploads/system_image/'.$system_image[0]->description) }}" class="img-fluid img-logo mb-3" alt="logo">
                            <h3 class="main-heading">Didn't receive OTP?</h3>
                            <p class="sub-heading">Request a new verification code to be sent to your email.</p>
                        </div>

                        <!-- RESEND OTP FORM -->
                        <form id="frm_resend_otp" class="mt-5 w-100">
                            @csrf
                            <input type="hidden" name="users_customers_id" id="users_customers_id" value="{{ $users_customers_id ?? '' }}">
                            
                            <div class="form-group position-relative mb-4">
                                <span class="input-icon">
                                    <img src="{{ asset('users/assets/images/icons/email.png') }}" alt="icon" class="img-fluid">
                                </span>
                                <input type="email" class="form-control" placeholder="Enter your email address" name="email" id="email" required>
                                <span class="error_msg" id="error_email"></span>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-login btn-primary" id="btn_resend">
                                    <span id="btn_resend_text">Resend OTP</span>
                                    <span id="btn_resend_loader" style="display:none;">
                                        <span class="spinner-border spinner-border-sm me-2" role="status"></span>Sending...
                                    </span>
                                </button>
                            </div>

                            <div class="text-center mt-4">
                                <a href="{{ url('/users/signup_individual') }}" class="text-muted text-decoration-none">
                                    â† Back to Signup
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- SCRIPTS -->
        <script src="{{ asset('users/assets/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('users/assets/js/jquery.min.js') }}"></script>
        <script src="{{ asset('users/assets/js/jquery.validate.min.js') }}"></script>
        <script>
            $(document).ready(function() {
                // FORM VALIDATION
                $("#frm_resend_otp").validate({
                    rules: {
                        email: {
                            required: true,
                            email: true
                        }
                    },
                    messages: {
                        email: {
                            required: "Please enter your email address.",
                            email: "Please enter a valid email address."
                        }
                    },
                    errorPlacement: function(error, element) {
                        $("#error_" + element.attr("name")).html(error);
                    },
                    submitHandler: function(form) {
                        // Show loader
                        $("#btn_resend_text").hide();
                        $("#btn_resend_loader").show();
                        $("#btn_resend").prop("disabled", true);

                        var settings = {
                            "url": "{{ rtrim(config('app.api_url'), '/') }}/resend_otp",
                            "method": "POST",
                            "timeout": 0,
                            "headers": {
                                "Content-Type": "application/json"
                            },
                            "data": JSON.stringify({
                                "users_customers_id": $("#users_customers_id").val(),
                            }),
                        };

                        $.ajax(settings).done(function(response) {
                            $("#btn_resend_text").show();
                            $("#btn_resend_loader").hide();
                            $("#btn_resend").prop("disabled", false);

                            if (response.status === "success") {
                                toastr.success(response.message + " New code sent to " + (response.method_used || 'your email'));
                                // Optionally redirect back to OTP page after 2 seconds
                                setTimeout(function() {
                                    window.location.href = "/users/verification_code/" + $("#users_customers_id").val();
                                }, 2000);
                            } else {
                                toastr.error(response.message || "Failed to resend OTP");
                            }
                        }).fail(function() {
                            $("#btn_resend_text").show();
                            $("#btn_resend_loader").hide();
                            $("#btn_resend").prop("disabled", false);
                            toastr.error("Network error. Please try again.");
                        });

                        return false;
                    }
                });
            });
        </script>

        <!-- TOASTERS -->
        <link href="{{ asset('toasters/toastr.min.css') }}" rel="stylesheet" type="text/css" />
        <script src="{{ asset('toasters/toastr.min.js') }}"></script>
        <script>
            toastr.options = {
                "closeButton": true,
                "positionClass": "toast-top-right",
                "timeOut": "5000"
            };
        </script>
        <!-- TOASTERS -->
    </body>
</html>
