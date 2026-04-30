@extends('layout.users.master')
@section('content')
    <div class="page-content-wrapper">
        <div class="page-content-tab">
            <div class="container-fluid px-4 pb-4">
                <input type="hidden" id="swap_offers_id" value="{{ $offer_id }}">
                <!-- modal accept offer start -->
                <div class="modal fade" id="mdl_accept_offer" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-body py-5 px-4 text-center">
                                <div class="d-flex justify-content-center align-items-center gap-2 mb-30">
                                    <input type="hidden" id="swap_offers_request_id" value="">
                                    <input type="hidden" id="request_sender_id" value="">

                                    <p class="mb-0 fs-4 text-black" id="accept_offer_from_currency"></p>
                                    <span class="plane-icon wh-40 bg-primary mx-2">
                                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M3.625 8.625C3.625 8.27982 3.34518 8 3 8C2.65482 8 2.375 8.27982 2.375 8.625C2.375 11.0412 4.33375 13 6.75 13H11.4907L10.6829 13.808L10.6309 13.8669C10.4402 14.1121 10.4575 14.4666 10.6828 14.6919C10.9269 14.936 11.3226 14.936 11.5667 14.692L13.4417 12.8172L13.4419 12.817L13.4935 12.7585C13.4775 12.7792 13.4601 12.7988 13.4417 12.8172M11.4907 11.75H6.75L6.61444 11.7471C4.95144 11.6761 3.625 10.3055 3.625 8.625" fill="#E8F9DC"/>
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M5.3167 1.30806C5.07262 1.06398 4.67689 1.06398 4.43281 1.30806L2.55781 3.18306L2.55599 3.18513C2.53906 3.20221 2.52312 3.22026 2.50825 3.23919L2.50582 3.24194C2.31518 3.48712 2.33251 3.84164 2.55781 4.06694L4.43281 5.94194L4.49169 5.99393C4.73688 6.18458 5.09139 6.16724 5.3167 5.94194L5.36869 5.88306C5.55933 5.63788 5.542 5.28336 5.3167 5.05806L4.5087 4.25H9.24999L9.38554 4.25289C11.0485 4.32386 12.375 5.69453 12.375 7.375C12.375 7.72018 12.6548 8 13 8C13.3452 8 13.625 7.72018 13.625 7.375C13.625 4.95875 11.6662 3 9.24999 3H4.5087L5.3167 2.19194L5.36869 2.13306C5.55933 1.88788 5.542 1.53336 5.3167 1.30806Z" fill="white"/>
                                        </svg>
                                    </span>
                                    <p class="mb-0 fs-4 text-black" id="accept_offer_exchange_rate"></p>
                                </div>
                                <div class="d-flex flex-column gap-5">
                                    <div class="row px-5">
                                        <div class="col-lg-6 d-flex align-items-left justify-content-left mb-3">Amount</div>
                                        <div class="col-lg-6 d-flex mb-3">
                                            <div class="mb-0 text-danger d-flex">
                                                <span class="plane-icon bg-danger me-2">
                                                    <img src="{{ asset('users/assets/images/icons/mini-icon/send-down.png') }}" alt="">
                                                </span>
                                                <span id="accept_offer_amount"></span>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 d-flex align-items-left justify-content-left mb-3">Converted Amount</div>
                                        <div class="col-lg-6 mb-3">
                                            <div class="mb-0 text-success d-flex"> 
                                                <span class="plane-icon bg-primary me-2">
                                                    <img src="{{ asset('users/assets/images/icons/mini-icon/send.png') }}" alt="">
                                                </span>
                                                <span id="accept_offer_converted_amount"></span>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 d-flex align-items-left justify-content-left mb-3">Base Amount</div>
                                        <div class="col-lg-6 mb-3">
                                            <div class="mb-0 text-black d-flex">
                                                <span class="plane-icon bg-black me-2">
                                                    <img src="{{ asset('users/assets/images/icons/mini-icon/account_balance.png') }}" alt="">
                                                </span>
                                                <span id="accept_offer_base_amount"></span>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 d-flex ml-5">
                                            <small class="text-danger">Note: Are you sure to accept this offer ?</small>
                                        </div>
                                    </div>
                                    <a href="javascript:void(0)" class="btn btn-login btn-primary w-100" onclick="accept_offer_request()" style="margin-top: -20px !important;">Accept Offer</a>
                                </div>                      
                            </div>
                        </div>
                    </div>
                </div> 
                <!-- modal accept offer end -->
                <div class="transactions">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb d-flex align-items-center">
                            <li class="breadcrumb-item">
                                <a href="{{ url('/users/offers') }}" class="text-primary">Offers</a>
                            </li>
                            <li class="mx-3">
                                <svg width="5" height="10" viewBox="0 0 5 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M4.21749 3.11406C5.22417 4.12074 5.25773 5.73205 4.31816 6.77904L4.21749 6.88529L1.47119 9.47108C1.21084 9.73143 0.788734 9.73143 0.528385 9.47108C0.288062 9.23076 0.269576 8.8526 0.472926 8.59107L0.528385 8.52827L3.27468 5.94248C3.76797 5.44919 3.79393 4.66553 3.35257 4.14168L3.27468 4.05687L0.528385 1.47108C0.268035 1.21073 0.268035 0.78862 0.528385 0.52827C0.768707 0.287947 1.14686 0.269461 1.40839 0.472811L1.47119 0.52827L4.21749 3.11406Z" fill="#21333B"/>
                                </svg>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                <a href="javascript:void()">Offer Requests</a>
                            </li> 
                        </ol>
                    </nav>
                    <!-- offer requests start -->
                    <div class="row mt-5" id="offer_requests"></div>
                    <!-- offer requests end -->
                </div>
            </div>
        </div> 
    </div>
@endsection
@section('script') 
    <script>
        $(document).ready(function() {
            get_offer_requests();
        });
    </script>
@endsection