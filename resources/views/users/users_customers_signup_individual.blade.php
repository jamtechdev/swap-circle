<!DOCTYPE html>
<html lang="en">
    <head>
        <?php 
            $system_image=DB::table('system_settings')->select('description')->where('type', 'system_image')->get(); 
            $system_name=DB::table('system_settings')->select('description')->where('type', 'system_name')->get(); 
        ?>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo $system_name[0]->description; ?> :: Users Customers Portal</title>

        <link href="{{ asset('users/assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('users/assets/plugin/select-flag/css/intlTelInput.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('users/assets/css/jquery.ui.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('users/assets/css/style.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('users/assets/css/custom.css') }}" rel="stylesheet" type="text/css" />
    </head>
    <body>
        <div id="signup">
            <div class="container py-100">
                <div class="row">
                    <div class="col-12 text-center mb-2 logo">
                        <h3 class="main-heading mb-3">Welcome..!</h3>
                        <img src="{{ asset('uploads/system_image/'.$system_image[0]->description) }}" class="img-fluid mb-3 img-logo" alt="logo">
                        <p class="sub-heading">Create your <?php echo $system_name[0]->description; ?> account.</p>
                    </div>
                </div>
                <!-- FORM SIGNUP START -->
                <form id="frm_signup">
                    @csrf
                    <div class="row px-3">
                        <div class="col-md-4 col-sm-6 mb-1"></div>
                        <!-- PROFILE PIC -->
                        <div class="col-md-4 col-sm-6 mb-1">
                            <div class="control-group text-center mx-auto file-upload" id="upload_profile">
                                <div class="image-box text-center mx-auto">
                                    <img src="{{ asset('users/assets/images/icons/document-upload.png') }}" class="img-fluid" id="profile_pic_preview" alt="">
                                </div>
                                <div class="controls">
                                    <input type="file" accept="image/png, image/jpg, image/jpeg" name="profile_pic" id="profile_pic" hidden multiple/>
                                    <span class="error_msg" id="error_profile_pic"></span>
                                    <textarea rows="10" cols="50" id="profile_pic_string" hidden></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6 mb-1"></div>
                        <!-- FIRST NAME -->
                        <div class="col-md-4 col-sm-12 mb-3">
                            <div class="form-group position-relative">
                                <span class="input-icon"><img src="{{ asset('users/assets/images/icons/profile-1.png') }}" alt="icon" class="img-fluid"></span>
                                <input type="text" class="form-control" placeholder="First name" aria-label="First name" name="first_name" id="first_name">
                                <span class="error_msg" id="error_first_name"></span>
                            </div>
                        </div>
                        <!-- SUR NAME -->
                        <div class="col-md-4 col-sm-12 mb-3">
                            <div class="form-group position-relative">
                                <span class="input-icon"><img src="{{ asset('users/assets/images/icons/profile-1.png') }}" alt="icon" class="img-fluid"></span>
                                <input type="text" class="form-control" placeholder="Surname" aria-label="Surname" name="sur_name" id="sur_name">
                                <span class="error_msg" id="error_sur_name"></span>
                            </div>
                        </div>
                        <!-- PHONE NUMBER -->
                        <div class="col-md-4 col-sm-12 mb-3">
                            <div class="form-group position-relative">
                                <input type="tel" class="form-control w-100" placeholder="Phone number" aria-label="Phone number" name="phone_number" id="phone_number" maxlength="11" oninput="this.value=this.value.replace(/[^0-9]/g,'');">
                                <span class="error_msg" id="error_phone_number"></span>
                            </div>
                        </div>
                        <!-- EMAIL -->
                        <div class="col-md-4 col-sm-12 mb-3">
                            <div class="form-group position-relative">
                                <span class="input-icon"><img src="{{ asset('users/assets/images/icons/email.png') }}" alt="icon" class="img-fluid"></span>
                                <input type="email" class="form-control" placeholder="Email address" aria-label="Email address" name="email" id="email">
                                <span class="error_msg" id="error_email"></span>
                            </div>
                        </div>
                        <!-- PASSWORD -->
                        <div class="col-md-4 col-sm-12 mb-3">
                            <div class="form-group position-relative w-pass">
                                <span class="input-icon"><img src="{{ asset('users/assets/images/icons/lock.png') }}" alt="icon" class="img-fluid"></span>
                                <input type="password" class="form-control" placeholder="Create password" aria-label="Create password" name="password" id="password">
                                <span class="input-icon right"><img src="{{ asset('users/assets/images/icons/Eye-slash 1.png') }}" alt="icon" class="img-fluid" id="icon_password"></span>
                                <span class="error_msg" id="error_password"></span>
                            </div>
                        </div>
                        <!-- CONFIRM PASSWORD -->
                        <div class="col-md-4 col-sm-12 mb-3">
                            <div class="form-group position-relative w-pass">
                                <span class="input-icon"><img src="{{ asset('users/assets/images/icons/lock.png') }}" alt="icon" class="img-fluid"></span>
                                <input type="password" class="form-control" placeholder="Confirm password" aria-label="Confirm password" name="confirm_password" id="confirm_password">
                                <span class="input-icon right"><img src="{{ asset('users/assets/images/icons/Eye-slash 1.png') }}" alt="icon" class="img-fluid" id="icon_confirm_password"></span>
                                <span class="error_msg" id="error_confirm_password"></span>
                            </div>
                        </div>
                        <!-- ADDRESS: STREET -->
                        <div class="col-md-4 col-sm-12 mb-3">
                            <div class="form-group position-relative">
                                <span class="input-icon"><img src="{{ asset('users/assets/images/icons/location.png') }}" alt="icon" class="img-fluid"></span>
                                <input type="text" class="form-control" placeholder="Street address" name="street" id="street">
                                <span class="error_msg" id="error_street"></span>
                            </div>
                        </div>
                        <!-- ADDRESS: CITY / STATE -->
                        <div class="col-md-4 col-sm-12 mb-3">
                            <div class="form-group position-relative">
                                <span class="input-icon"><img src="{{ asset('users/assets/images/icons/location.png') }}" alt="icon" class="img-fluid"></span>
                                <input type="text" class="form-control" placeholder="City / State" name="city_state" id="city_state">
                                <span class="error_msg" id="error_city_state"></span>
                            </div>
                        </div>
                        <!-- ADDRESS: COUNTRY -->
                        <div class="col-md-4 col-sm-12 mb-5">
                            <div class="form-group position-relative">
                                <span class="input-icon"><img src="{{ asset('users/assets/images/icons/location.png') }}" alt="icon" class="img-fluid"></span>
                                <input type="text" class="form-control" placeholder="Country" name="country" id="country">
                                <span class="error_msg" id="error_country"></span>
                            </div>
                        </div>

                        <div class="col-sm-6 mx-auto mt-3">
                            <button type="submit" class="btn btn-login btn-primary w-100" id="btn_signup">
                                <span id="btn_signup_text">Next</span>
                                <span id="btn_signup_loader" style="display:none;">
                                    <span class="spinner-border spinner-border-sm me-2" role="status"></span>Please wait...
                                </span>
                            </button>
                        </div>
                    </div>
                </form>
                <!-- FORM SIGNUP END -->
            </div>
        </div>

        <!-- SCRIPTS-->
        <script src="{{ asset('users/assets/js/bootstrap.bundle.js') }}"></script>
        <script src="{{ asset('users/assets/js/jquery.min.js') }}"></script>
        <script src="{{ asset('users/assets/js/jquery.validate.min.js') }}"></script>
        <script src="{{ asset('users/assets/js/jquery.ui.min.js') }}"></script>
        <script src="{{ asset('users/assets/js/jquery.additional.methods.js') }}"></script>
        <script src="{{ asset('users/assets/plugin/select-flag/js/intlTelInput-jquery.js') }}"></script>
        <script src="{{ asset('users/assets/js/file-upload/index.js') }}"></script>
        <script src="{{ asset('users/assets/plugin/select-flag/js/utils.js')}}"></script>
        <script>
            $(document).ready(function() {
                // SHOW / HIDE PASSWORD
                $("#icon_password").on("click", function() {
                    var input = $("#password");
                    input.attr("type", input.attr("type") === "password" ? "text" : "password");
                });
                $("#icon_confirm_password").on("click", function() {
                    var input = $("#confirm_password");
                    input.attr("type", input.attr("type") === "password" ? "text" : "password");
                });

                // PROFILE PIC PREVIEW
                function previewImage(image, preview, string) {
                    var previewEl = document.querySelector(preview);
                    var fileImage = image.files[0];
                    var reader = new FileReader();
                    reader.addEventListener("load", function() {
                        previewEl.src = reader.result;
                        document.querySelector(string).value = reader.result.toString().replace(/^data:(.*,)?/, "");
                    }, false);
                    if (fileImage) { reader.readAsDataURL(fileImage); }
                }
                document.querySelector("#profile_pic").addEventListener("change", function() {
                    previewImage(this, "#profile_pic_preview", "#profile_pic_string");
                });

                // FORM VALIDATION
                $("#frm_signup").validate({
                    ignore: [],
                    rules: {
                        profile_pic:      { required: true },
                        first_name:       { required: true, minlength: 3 },
                        sur_name:         { required: true, minlength: 3 },
                        phone_number:     { required: true, digits: true, minlength: 11, maxlength: 11 },
                        email:            { required: true, email: true },
                        password:         { required: true, minlength: 7 },
                        confirm_password: { required: true, equalTo: "#password" },
                        street:           { required: true },
                        city_state:       { required: true },
                        country:          { required: true },
                    },
                    messages: {
                        profile_pic:      { required: "Select profile picture." },
                        first_name:       { required: "This field is required.", minlength: "At least 3 characters." },
                        sur_name:         { required: "This field is required.", minlength: "At least 3 characters." },
                        phone_number:     { required: "This field is required.", digits: "Please enter a valid phone number (digits only).", minlength: "Phone number must be 11 digits.", maxlength: "Phone number must be 11 digits." },
                        email:            { required: "This field is required.", email: "Enter a valid email." },
                        password:         { required: "This field is required.", minlength: "At least 7 characters." },
                        confirm_password: { required: "This field is required.", equalTo: "Passwords do not match." },
                        street:           { required: "This field is required." },
                        city_state:       { required: "This field is required." },
                        country:          { required: "This field is required." },
                    },
                    errorPlacement: function(error, element) {
                        $("#error_" + element.attr("name")).html(error);
                    }
                });

                // FORM SUBMISSION
                $("#frm_signup").on("submit", function(event) {
                    event.preventDefault();
                    if ($("#frm_signup").valid()) {
                        // show loader
                        $("#btn_signup_text").hide();
                        $("#btn_signup_loader").show();
                        $("#btn_signup").prop("disabled", true);

                        var location = $("#street").val() + ", " + $("#city_state").val() + ", " + $("#country").val();

                        var settings = {
                            "url": "{{ rtrim(config('app.api_url'), '/') }}/signup",
                            "method": "POST",
                            "timeout": 0,
                            "headers": { "Content-Type": "application/json" },
                            "data": JSON.stringify({
                                "users_customers_type": "Individual",
                                "profile_pic":  $("#profile_pic_string").val(),
                                "first_name":   $("#first_name").val(),
                                "last_name":    $("#sur_name").val(),
                                "phone":        $("#phone_number").val(),
                                "email":        $("#email").val(),
                                "password":     $("#password").val(),
                                "location":     location,
                            }),
                        };

                        $.ajax(settings).done(function(response) {
                            if (response.status == "error") {
                                Command: toastr["error"](response.message);
                                $("#btn_signup_text").show();
                                $("#btn_signup_loader").hide();
                                $("#btn_signup").prop("disabled", false);
                            } else {
                                // Don't set session yet - redirect to OTP verification first
                                window.location.href = "/users/verification_code/" + response.data.users_customers_id;
                            }
                        });
                    }
                });
            });
        </script>
        <!-- SCRIPTS -->

        <!-- TOASTERS -->
        <link href="{{asset('toasters/toastr.min.css')}}" rel="stylesheet" type="text/css" />   
        <script src="{{asset('toasters/toastr.min.js')}}" type="text/javascript"></script>
        <script>
            toastr.options = {"closeButton": true, "positionClass": "toast-top-right", "timeOut": "5000"};
            <?php if(Session::has('success')){ ?> Command: toastr['success']("<?php echo Session('success'); ?>"); <?php } ?>
            <?php if(Session::has('error')){ ?> Command: toastr['error']("<?php echo Session('error'); ?>"); <?php } ?>
            <?php if(Session::has('warning')){ ?> Command: toastr['warning']("<?php echo Session('warning'); ?>"); <?php } ?>
            <?php if(Session::has('info')){ ?> Command: toastr['info']("<?php echo Session('info'); ?>"); <?php } ?>
        </script>
        <!-- TOASTERS -->
    </body>
</html>
