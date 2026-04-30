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
        <link href="{{ asset('users/assets/css/style.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('users/assets/css/custom.css') }}" rel="stylesheet" type="text/css" />
    </head>
    <body>
        <div id="wrapper">
            <div class="container-fluid">
                <div class="row login min-vh-100">
                    <!-- LEFT SECTION START -->
                    <div class="col-md-7 left d-md-flex align-items-center justify-content-center justify-content-md-start py-5 d-none">
                        <img src="{{ asset('users/assets/images/congratulations.png') }}" class="img-fluid w-75" alt="image">
                    </div>
                    <!-- LEFT SECTION END -->

                    <!-- RIGHT SECTION START -->
                    <div class="col-md-5 d-flex flex-column justify-content-sm-around justify-content-center align-items-center flex-wrap  py-5">
                        <div class="logo text-center">
                            <h3 class="main-heading">Congratulations ..!</h3>
                            <p class="sub-heading">Your email has been verified successfully.</p>
                        </div>
                        <!-- SIGNED UP USER DATA START -->
                        <div class="profile text-center mt-5">
                            <!-- PROFILE PIC -->
                            <img src="{{ asset(session()->get('profile_pic')) }}" class="img-fluid" alt="img" id="verified_user">
                            <!-- NAME -->
                            <h5 class="fw-bolder mt-3">{{ session()->get('first_name') }} {{ session()->get('last_name') }}</h5>
                            <!-- EMAIL -->
                            <p class="sub-heading mt-4"><span class="input-icon"><img src="{{ asset('users/assets/images/icons/email-1.png') }}" class="img-fluid"></span> {{ session()->get('email') }}</p>
                            <!-- PHONE NUMBER -->
                            <p class="sub-heading"><span class="input-icon"><img src="{{ asset('users/assets/images/icons/phone.png') }}" class="img-fluid" ></span> {{ session()->get('phone') }}</p>
                            <img src="{{ asset('users/assets/images/congratulations.png') }}" class="img-fluid d-block d-md-none w-100 h-auto mt-3" alt="image">
                        </div>
                        <!-- SIGNED UP USER DATA END -->
                        <div class="text-center mt-5 w-sm-auto w-100">
                            <a class="btn btn-login btn-primary mb-4" href="{{ url('/users/products') }}" role="button">View Products</a>
                        </div> 
                    </div>
                    <!-- RIGHT SECTION END -->
                </div>
            </div>
        </div>
    </body>
</html>