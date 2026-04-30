@extends('layout.users.master')
@section('content') 
    <div class="page-content-wrapper">
        <div class="page-content-tab">
            <div class="container-fluid px-4 pb-4">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                    <h3 class="fw-bold sub-heading text-black">Marketplace</h3>
                </div>
                <div class="offers-wrapper">
                    <div class="wallet-tabs mt-4">
                        <ul class="nav nav-pills mb-4 mx-auto" id="pills-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                              <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-all-offers" type="button" role="tab" aria-controls="pills-all-offers" aria-selected="true">All Offers</button>
                            </li>
                            <li class="nav-item" role="presentation">
                              <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-favorite" type="button" role="tab" aria-controls="pills-favorite" aria-selected="false">Favorite</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-my-offers" type="button" role="tab" aria-controls="pills-my-offers" aria-selected="false">My Offers</button>
                            </li>
                        </ul>
                        <div class="tab-content" id="pills-tabContent">
                            <!-- all offers start -->
                            <div class="tab-pane fade show active" id="pills-all-offers" role="tabpanel" aria-labelledby="pills-home-tab" tabindex="0">
                                <div class="row" id="all_offers"></div>
                            </div>
                            <!-- all offers end -->

                            <!-- 🔥 favorite start -->
                            <div class="tab-pane fade" id="pills-favorite" role="tabpanel" aria-labelledby="pills-profile-tab" tabindex="0">
                                <div class="row" id="favorite_offers"></div>   
                            </div>
                            <!-- favorite end -->

                            <!-- my offers start -->
                            <div class="tab-pane fade" id="pills-my-offers" role="tabpanel" aria-labelledby="pills-profile-tab" tabindex="0">
                                <div class="row" id="my_offers"></div>
                                <!-- create offer -->
                                <div class="d-flex justify-content-center align-items-center position-fixed" style="bottom: 20px; left: 60%; transform: translateX(-50%);">
                                    <button class="btn btn-primary btn-login w-100" onclick="get_create_offer_params()" style="padding: 0px 100px;">Create Offer</button>
                                </div>
                                <!-- create offer -->
                            </div>
                            <!-- my offers end -->
                        </div>
                    </div> 
                </div>
            </div>
        </div> 
    </div>
@endsection
@section('script') 
    <script>
        $(document).ready(function() {
            get_all_offers();
            get_favorite_offers();
            get_my_offers();

            activate_target_tab();
        });
        
        /* --------------- LOAD DATETIME PICKER --------------- */
        document.addEventListener('DOMContentLoaded', function() {
            flatpickr("#co_expires_in", {
                enableTime: true,            // Enable time selection
                enableSeconds: true,         // Enable seconds selection
                dateFormat: "Y-m-d H:i:S",   // Format: YYYY-MM-DD HH:mm:ss (with seconds)
                time_24hr: true,             // Use 24-hour time format
                minuteIncrement: 1,          // Minute increment step (1 minute)
                secondIncrement: 1,          // Second increment step (1 second)
                placeholder: "Select date and time"
            });
        });
        /* --------------- LOAD DATETIME PICKER --------------- */
    </script>
@endsection