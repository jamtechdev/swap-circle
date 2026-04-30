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
            <div class="page-content-wrapper"> 
                <div class="page-content-tab">
                    <div class="container-fluid px-4 pb-4">
                        <div class="connects-wrapper">
                            <input type="hidden" id="connect_articles_id" value="{{ $blog_id }}">
                            <!-- connect article blog start -->
                            <div class="others mt-3" id="connect_article_blog">
                                <!-- <div class="row mt-0 d-flex justify-content-center">
                                    <div class="col-lg-5 col-md-7">
                                        <div class="card text-start border-0 rounded-4 overflow-hidden p-2">
                                            <div class="card-image position-relative">
                                                <img class="card-img-top img-fluid" src="{{ asset('users/assets/images/food-2.png') }}" alt="Title"> 
                                            </div>
                                            <div class="card-body px-0 py-2">
                                                <h2 class="fw-bold">Mobile Airtime</h2>
                                                <p class="">Get discount form VTU airtime recharge on all networks.</p>
                                            </div>
                                        </div> 
                                    </div> 
                                </div> -->
                            </div>
                            <!-- connect article blog end -->
                        </div>
                    </div>
                </div> 
            </div>
        </div>
        @include('layout.users.scripts')
        
        <script>
        $(document).ready(function() {
            get_connect_article_blog();
        });
    </script>
    </body>
</html>