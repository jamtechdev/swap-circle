<!DOCTYPE html>
<html lang="en">
    <head>
        <?php
            $system_image_file = 'logo.png';
            $system_name_text = config('app.name', 'Swap Circle');
            $auth_image = 'users/assets/images/Rocket_Boy_Flatline.png';

            try {
                $system_image_file = optional(DB::table('system_settings')->select('description')->where('type', 'system_image')->first())->description ?: $system_image_file;
                $system_name_text = optional(DB::table('system_settings')->select('description')->where('type', 'system_name')->first())->description ?: $system_name_text;
                $auth_image_setting = optional(DB::table('system_settings')->select('description')->where('type', 'auth_image')->first())->description;
                if (!empty($auth_image_setting)) {
                    $auth_image = preg_replace('#^public/#', '', $auth_image_setting);
                }
            } catch (\Throwable $e) {
                // Keep defaults when DB is unavailable.
            }
        ?>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo $system_name_text; ?> :: Get Started</title>

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
                            <h2 class="community-hero-title mb-3">Join the circle.<br>Start exchanging.</h2>
                            <p class="community-hero-text mb-0">Create your account as an individual or organisation and connect with trusted community opportunities.</p>
                        </div>
                    </div>
                    <!-- LEFT SECTION END -->

                    <!-- RIGHT SECTION START -->
                    <div class="col-lg-6 d-flex flex-column justify-content-center align-items-center py-5 px-3 login-panel">
                        <div class="login-card w-100 text-center">
                            <div class="logo">
                                <p class="signup-kicker mb-2">Get Started with</p>
                                <img src="{{ asset('uploads/system_image/'.$system_image_file) }}" class="img-fluid img-logo" alt="{{ $system_name_text }}">
                                <p class="sub-heading community-subheading mt-3 mb-0">Choose how you want to join Swap Circle.</p>
                            </div>

                            <div class="signup-choice-actions text-center mt-5 w-100">
                                <a class="btn btn-login btn-primary mb-3 w-100" href="{{ url('/users/signup_individual') }}" role="button">As an Individual</a>
                                <a class="btn btn-login btn-outline-primary mb-4 w-100 signup-corporate-btn" href="{{ url('/users/signup_corporate') }}" role="button">As a Corporate</a>
                                <p class="signup-prompt mb-0">Already a user? <a href="{{ url('/') }}">Sign In</a></p>
                            </div>
                        </div>
                    </div>
                    <!-- RIGHT SECTION END -->
                </div>
            </div>
        </div>
    </body>
</html>
