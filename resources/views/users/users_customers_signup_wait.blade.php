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
    </head>
    <body class="bg-black">
        <div id="wrapper">
            <div class="container-fluid">
                <div class="row align-items-center min-vh-100">
                    <div class="col-md-5 mx-auto text-center">
                        <h3 class="title text-white">Please Wait...</h3>
                        <h3 class="title text-success">Verifying your ID</h3>
                        <ul class="list-unstyled d-flex justify-content-center loading-animation">
                            <li></li>
                            <li></li>
                            <li></li>
                            <li></li>
                        </ul>
                        <img src="{{ asset('users/assets/images/verifiy-screen.png') }}" class="img-fluid mt-5" alt="image">
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>