@extends('layout.users.master')
@section('content')
    <div class="page-content-wrapper"> 
        <div class="page-content-tab">
            <div class="container-fluid px-4 pb-4">
                <div class="connects-wrapper">
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
                        <h3 class="fw-bold sub-heading text-black"><span class="text-success">Swap Circle</span> Connect</h3>
                    </div>
                    <!-- connect categories start -->
                    <div class="d-flex flex-wrap align-items-center gap-4" id="connect_categories"></div>
                    <!-- connect categories end -->

                    <!-- popular articles start -->
                    <div class="most-popular mt-5">
                        <p class="fw-bold">Most Popular</p>
                        <div class="splide" id="slider-1" aria-label="...">
                            <div class="splide__track">
                                <ul class="splide__list" id="popular_articles"></ul>
                            </div>
                        </div>
                    </div>
                    <!-- popular articles end -->

                    <!-- other articles start -->
                    <div class="others mt-5">
                        <p class="fw-bold">Others</p>
                        <div class="splide" id="slider-2" aria-label="...">
                            <div class="splide__track">
                                <ul class="splide__list" id="other_articles"></ul>
                            </div>
                        </div>
                       <!-- <div class="row mt-4">
                            <div class="col-lg-4 col-md-6">
                                <div class="card text-start border-0 rounded-4 overflow-hidden p-2">
                                    <div class="card-image position-relative">
                                        <img class="card-img-top img-fluid" src="{{ asset('users/assets/images/food-2.png') }}" alt="Title">
                                        <div class="position-absolute top-0 end-0 text-end p-2">
                                            <div class="d-flex justify-content-end gap-2 mb-2">
                                                <span class="card-icon">
                                                    <svg width="20" height="18" viewBox="0 0 20 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M10.62 17.71C10.28 17.83 9.72 17.83 9.38 17.71C6.48 16.72 0 12.59 0 5.59C0 2.5 2.49 0 5.56 0C7.38 0 8.99 0.88 10 2.24C11.01 0.88 12.63 0 14.44 0C17.51 0 20 2.5 20 5.59C20 12.59 13.52 16.72 10.62 17.71Z" fill="#EF3C3C"/>
                                                        </svg>                                            
                                                </span>
                                                <span class="card-icon">
                                                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M2.75293 1.875V10.8525C2.75293 11.5875 3.09793 12.285 3.69043 12.7275L7.59792 15.6525C8.43042 16.275 9.57792 16.275 10.4104 15.6525L14.3179 12.7275C14.9104 12.285 15.2554 11.5875 15.2554 10.8525V1.875H2.75293Z" stroke="#4BD16F" stroke-width="1.5" stroke-miterlimit="10"/>
                                                        <path d="M1.5 1.875H16.5" stroke="#4BD16F" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round"/>
                                                        <path d="M6 6H12" stroke="#4BD16F" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                                        <path d="M6 9.75H12" stroke="#4BD16F" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                                    </svg>                                        
                                                </span>
                                            </div>
                                            <h4 class="card-title text-white mb-0 fw-bold">Flat 50% OFF</h4>
                                            <p class="card-text text-white mb-2">On Food Orders</p>
                                            <a href="#" class="btn btn-order">ORDEr NOW</a>
                                        </div>
                                    </div>
                                    <div class="card-body px-0 py-2">
                                        <h4 class="card-title fw-bold">Mobile Airtime</h4>
                                        <p class="card-text">Get discount form VTU airtime recharge on all networks.</p>
                                    </div>
                                </div> 
                            </div>
                            <div class="col-lg-4 col-md-6">
                                <div class="card text-start border-0 rounded-4 overflow-hidden p-2">
                                    <div class="card-image position-relative">
                                        <img class="card-img-top img-fluid" src="{{ asset('users/assets/images/food-2.png') }}" alt="Title">
                                        <div class="position-absolute top-0 end-0 text-end p-2">
                                            <div class="d-flex justify-content-end gap-2 mb-2">
                                                <span class="card-icon">
                                                    <svg width="20" height="18" viewBox="0 0 20 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M10.62 17.71C10.28 17.83 9.72 17.83 9.38 17.71C6.48 16.72 0 12.59 0 5.59C0 2.5 2.49 0 5.56 0C7.38 0 8.99 0.88 10 2.24C11.01 0.88 12.63 0 14.44 0C17.51 0 20 2.5 20 5.59C20 12.59 13.52 16.72 10.62 17.71Z" fill="#EF3C3C"/>
                                                        </svg>                                            
                                                </span>
                                                <span class="card-icon">
                                                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M2.75293 1.875V10.8525C2.75293 11.5875 3.09793 12.285 3.69043 12.7275L7.59792 15.6525C8.43042 16.275 9.57792 16.275 10.4104 15.6525L14.3179 12.7275C14.9104 12.285 15.2554 11.5875 15.2554 10.8525V1.875H2.75293Z" stroke="#4BD16F" stroke-width="1.5" stroke-miterlimit="10"/>
                                                        <path d="M1.5 1.875H16.5" stroke="#4BD16F" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round"/>
                                                        <path d="M6 6H12" stroke="#4BD16F" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                                        <path d="M6 9.75H12" stroke="#4BD16F" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                                    </svg>                                        
                                                </span>
                                            </div>
                                            <h4 class="card-title text-white mb-0 fw-bold">Flat 50% OFF</h4>
                                            <p class="card-text text-white mb-2">On Food Orders</p>
                                            <a href="#" class="btn btn-order">ORDEr NOW</a>
                                        </div>
                                    </div>
                                    <div class="card-body px-0 py-2">
                                        <h4 class="card-title fw-bold">Mobile Airtime</h4>
                                        <p class="card-text">Get discount form VTU airtime recharge on all networks.</p>
                                    </div>
                                </div> 
                            </div>
                            <div class="col-lg-4 col-md-6">
                                <div class="card text-start border-0 rounded-4 overflow-hidden p-2">
                                    <div class="card-image position-relative">
                                        <img class="card-img-top img-fluid" src="{{ asset('users/assets/images/food-2.png') }}" alt="Title">
                                        <div class="position-absolute top-0 end-0 text-end p-2">
                                            <div class="d-flex justify-content-end gap-2 mb-2">
                                                <span class="card-icon">
                                                    <svg width="20" height="18" viewBox="0 0 20 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M10.62 17.71C10.28 17.83 9.72 17.83 9.38 17.71C6.48 16.72 0 12.59 0 5.59C0 2.5 2.49 0 5.56 0C7.38 0 8.99 0.88 10 2.24C11.01 0.88 12.63 0 14.44 0C17.51 0 20 2.5 20 5.59C20 12.59 13.52 16.72 10.62 17.71Z" fill="#EF3C3C"/>
                                                    </svg>                                            
                                                </span>
                                                <span class="card-icon">
                                                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M2.75293 1.875V10.8525C2.75293 11.5875 3.09793 12.285 3.69043 12.7275L7.59792 15.6525C8.43042 16.275 9.57792 16.275 10.4104 15.6525L14.3179 12.7275C14.9104 12.285 15.2554 11.5875 15.2554 10.8525V1.875H2.75293Z" stroke="#4BD16F" stroke-width="1.5" stroke-miterlimit="10"/>
                                                        <path d="M1.5 1.875H16.5" stroke="#4BD16F" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round"/>
                                                        <path d="M6 6H12" stroke="#4BD16F" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                                        <path d="M6 9.75H12" stroke="#4BD16F" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                                    </svg>                                        
                                                </span>
                                            </div>
                                            <h4 class="card-title text-white mb-0 fw-bold">Flat 50% OFF</h4>
                                            <p class="card-text text-white mb-2">On Food Orders</p>
                                            <a href="#" class="btn btn-order">ORDEr NOW</a>
                                        </div>
                                    </div>
                                    <div class="card-body px-0 py-2">
                                        <h4 class="card-title fw-bold">Mobile Airtime</h4>
                                        <p class="card-text">Get discount form VTU airtime recharge on all networks.</p>
                                    </div>
                                </div> 
                            </div>  
                        </div> -->
                    </div>
                    <!-- other articles end -->
                </div>
            </div>
        </div> 
    </div>
@endsection
@section('script') 
    <script>
        $(document).ready(function() {
            get_connect_categories();
            get_popular_articles();
            get_other_articles();
        });
    </script>
@endsection