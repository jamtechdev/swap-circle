@extends('layout.users.master')
@section('content') 
    <div class="page-content-wrapper">
        <div class="page-content-tab">
            <div class="container-fluid px-4 pb-4">
                <div class="mb-3">
                    <a href="{{ url('/users/wallets') }}" class="btn btn-primary">Fund Wallets</a>

                    <!-- modal create wallet start -->
                    <div class="modal fade" id="mdl_create_wallet" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-body py-5 px-5">
                                    <div class="d-flex align-items-center mb-5">
                                        <svg class="flex-grow-0 pointer" data-bs-dismiss="modal" aria-label="Close" width="12" height="20" viewBox="0 0 12 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M10 20L0 10L10 0L11.775 1.775L3.55 10L11.775 18.225L10 20Z" fill="#4BD16F"/>
                                        </svg>
                                        <h2 class="flex-grow-1 modal-heading">Create Wallet</h2>
                                    </div>
                                    <div class="row mt-37">
                                        <div class="col-lg-10 mx-auto">
                                            <form id="frm_create_wallet">
                                                @csrf
                                                <div class="form-group mb-4">
                                                    <label class="form-label mb-3">Base Currency</label>
                                                    <select class="form-select form-select-lg" aria-label=".form-select-lg example" id="cw_base_currency" name="cw_base_currency">
                                                        <option value="" disabled selected hidden>Select Currency</option>
                                                    </select>
                                                    <span class="error_msg" id="error_cw_base_currency"></span>
                                                </div>
                                                <div class="mt-37">
                                                    <button type="submit" class="btn btn-login btn-primary w-100">Save</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>                       
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- modal create wallet end -->

                    <!-- modal send currency start -->
                    <div class="modal fade modal-xl" id="mdl_send_currency" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-body p-5">
                                    <div class="d-flex align-items-center mb-4">
                                        <svg class="flex-grow-0 pointer" data-bs-dismiss="modal" aria-label="Close" width="12" height="20" viewBox="0 0 12 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M10 20L0 10L10 0L11.775 1.775L3.55 10L11.775 18.225L10 20Z" fill="#4BD16F"/>
                                        </svg>
                                        <h2 class="flex-grow-1 modal-heading">Send Currency</h2>
                                    </div>
                                    <form id="frm_send_currency">
                                        @csrf
                                        <div class="row mt-0">
                                            <div class="col-12 d-flex align-items-center justify-content-center gap-4 mt-0 mb-4">
                                                <h4 class="sub-heading text-black m-0">Base Currency</h4>
                                                <p class="m-0 fs-5 text-black" id="sc_base_currency"></p>
                                            </div>
                                            <div class="col-lg-4 col-md-6">
                                                <div class="form-group mb-4">
                                                    <label class="form-label mb-3">From Currency</label>
                                                    <select class="form-select form-select-lg" aria-label=".form-select-lg example" name="sc_from_currency" id="sc_from_currency">   
                                                        <option value="" disabled selected hidden>Select Currency</option>
                                                    </select>
                                                    <span class="error_msg" id="error_sc_from_currency"></span>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-6">
                                                <div class="form-group mb-4">
                                                    <label class="form-label mb-3">Total Amount</label>
                                                    <input type="text" name="sc_total_amount" id="sc_total_amount" placeholder="Enter Amount" class="form-control" min="1" step="0.01">
                                                    <span class="error_msg" id="error_sc_total_amount"></span>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-6">
                                                <div class="form-group mb-4">
                                                    <label class="form-label mb-3">Exchange Currency</label>
                                                    <select class="form-select form-select-lg" aria-label=".form-select-lg example" name="sc_exchange_currency" id="sc_exchange_currency">
                                                        <option value="" disabled selected hidden>Select Currency</option>
                                                    </select>
                                                    <span class="error_msg" id="error_sc_exchange_currency"></span>
                                                </div>
                                            </div>
                                            <div class="col-4 d-flex align-items-left justify-content-left gap-4 mb-4">
                                                <h4 class="sub-heading text-black mb-0">Exchange Rate</h4>
                                                <p class="mb-0 fs-4 text-black fw-bolder" id="sc_exchange_rate"></p>
                                            </div>
                                            <div class="col-8 d-flex align-items-left justify-content-left gap-4 mb-4">
                                                <h4 class="sub-heading text-black mb-0">Exchange Amount</h4>
                                                <p class="mb-0 fs-4 text-black fw-bolder" id="sc_exchange_amount"></p>
                                            </div>
                                            <div class="col-lg-4 col-md-6">
                                                <div class="form-group mb-4">
                                                    <label class="form-label mb-3">Send to</label>
                                                    <input type="text" name="sc_email" id="sc_email" placeholder="Enter Email" class="form-control">
                                                    <span class="error_msg" id="error_sc_email"></span>

                                                    <div class="text-black" id="suggested_users"></div>
                                                    <input type="hidden" id="suggested_users_id" value="" disabled>
                                                </div> 
                                            </div>
                                            <!-- <div class="col-lg-4 col-md-6">
                                                <div class="form-group mb-4">
                                                    <label class="form-label mb-3">Select Country</label>
                                                    <select class="form-select form-select-lg" aria-label=".form-select-lg example" name="sc_country" id="sc_country">
                                                        <option value="" disabled selected hidden>Select Country</option>
                                                    </select>
                                                    <span class="error_msg" id="error_sc_country"></span>
                                                </div>
                                            </div> -->
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-4 mx-auto">
                                                <div class="mt-10">
                                                    <button type="submit" class="btn btn-login btn-primary w-100">Next</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>                     
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- modal send currency end -->

                    <!-- modal send currency2 start -->
                    <div class="modal fade" id="mdl_send_currency2" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-body p-5">
                                    <div class="d-flex align-items-center mb-5">
                                        <svg class="flex-grow-0 pointer" data-bs-dismiss="modal" aria-label="Close" width="12" height="20" viewBox="0 0 12 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M10 20L0 10L10 0L11.775 1.775L3.55 10L11.775 18.225L10 20Z" fill="#4BD16F"/>
                                        </svg>
                                        <h2 class="flex-grow-1 modal-heading">Send currency</h2>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <p class="mb-30 fs-6 fw-semibold text-left">Total Amount</p>
                                        <div class="d-flex justify-content-center gap-3 mb-30">
                                            <p class="mb-0 fs-4 text-black fw-bolder" id="sc2_from_amount"></p>
                                            <span class="plane-icon bg-primary mx-2">
                                                <img src="{{ asset('users/assets/images/icons/mini-icon/Repeat.png') }}" class="img-fluid" alt="">
                                            </span>
                                            <p class="mb-0 fs-4 text-black fw-bolder" id="sc2_exchange_amount"></p>
                                        </div>
                                        <p class="mb-4 fs-6 fw-semibold text-left">Receiver</p>
                                        <div class="d-flex justify-content-center align-items-center gap-3 mb-4">
                                            <img src="" class="img-fluid h-48 w-48 rounded-full" id="sc2_receiver_image" alt="">
                                            <div class="text-start">
                                                <h5 class="fw-bolder fs-18 mb-1 text-black" id="sc2_receiver_name"></h5>
                                                <p class="mb-0 text-primary" id="sc2_receiver_email"></p>
                                            </div>
                                        </div>
                                        <p class="mb-4 fs-6 fw-semibold text-left">Date</p>
                                        <div class="d-flex justify-content-center gap-3 mb-30">
                                            <h5 class="fw-bolder fs-4" id="sc2_current_date"></h5>
                                        </div>
                                        <button class="btn btn-login btn-primary w-100" onclick="send_currency()">Send</button>
                                    </div>                     
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- modal send currency2 end -->
                </div>
                
                <div class="wallet-wrapper">
                    <!-- create wallet -->
                    <ul class="wallet-items d-flex flex-wrap justify-content-start list-unstyled">
                        <li class="wallet-item wallet-create d-flex align-items-center pointer" onclick="get_create_wallet_currencies()">
                            <img src="{{ asset('users/assets/images/icons/add-circle.png') }}" class="img-fluid me-2 d-block" alt="image">
                            <span class="text-black">Create <br/> Wallet</span>
                        </li>
                        <ul class="wallet-items d-flex flex-wrap justify-content-start list-unstyled" id="wallets"></ul>
                    </ul>
                    <!-- create wallet --> 

                    <div class="wallet-tabs mt-4">
                        <ul class="nav nav-pills mb-4 mx-auto" id="pills-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                              <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-transactions" type="button" role="tab" aria-controls="pills-transactions" aria-selected="true">All Transactions</button>
                            </li>
                            <li class="nav-item" role="presentation">
                              <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-offers" type="button" role="tab" aria-controls="pills-offers" aria-selected="false">ðŸ”¥ Hot Swap Offers</button>
                            </li>
                            <!-- <li class="nav-item" role="presentation">
                                <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-rate-table" type="button" role="tab" aria-controls="pills-rate-table" aria-selected="false">Rate Table</button>
                            </li> -->
                        </ul>
                        <div class="tab-content" id="pills-tabContent">
                            <!-- transactions start -->
                            <div class="tab-pane fade show active" id="pills-transactions" role="tabpanel" aria-labelledby="pills-home-tab" tabindex="0">
                                <div class="row">
                                    <div class="row" id="transactions"></div>
                                    <!-- send currency -->
                                    <div class="position-fixed bottom-0 end-0 mb-0 me-3">
                                        <a href="javascript:void(0)" class="btn rounded-full btn-rounded ms-auto mb-2" onclick="get_send_currency_params()">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M7.1099 5.96028L16.1299 2.95028C20.1799 1.60028 22.3799 3.81028 21.0399 7.86028L18.0299 16.8803C16.0099 22.9503 12.6899 22.9503 10.6699 16.8803L9.7799 14.2003L7.0999 13.3103C1.0399 11.3003 1.0399 7.99028 7.1099 5.96028Z" fill="white"/>
                                                <path d="M12.1201 11.6296L15.9301 7.80957L12.1201 11.6296Z" fill="#292D32"/>
                                                <path d="M12.1201 12.38C11.9301 12.38 11.7401 12.31 11.5901 12.16C11.3001 11.87 11.3001 11.39 11.5901 11.1L15.3901 7.28C15.6801 6.99 16.1601 6.99 16.4501 7.28C16.7401 7.57 16.7401 8.05 16.4501 8.34L12.6501 12.16C12.5001 12.3 12.3101 12.38 12.1201 12.38Z" fill="#A6EBB8"/>
                                            </svg>
                                        </a>
                                    </div>
                                    <!-- send currency -->
                                </div>
                            </div>
                            <!-- transactions end -->

                            <!-- ðŸ”¥ hot swap offers start -->
                            <div class="tab-pane fade" id="pills-offers" role="tabpanel" aria-labelledby="pills-profile-tab" tabindex="0">
                                <div class="row" id="hot_swap_offers"></div>
                                <!-- create offer -->
                                <div class="d-flex justify-content-center align-items-center position-fixed" style="bottom: 20px; left: 60%; transform: translateX(-50%);">
                                    <button class="btn btn-primary btn-login w-100" onclick="get_create_offer_params()" style="padding: 0px 100px;">Create Offer</button>
                                </div>
                                <!-- create offer -->
                            </div>
                            <!-- ðŸ”¥ hot swap offers end -->

                            <!-- RATE TABLE START -->
                            <!-- <div class="tab-pane fade" id="pills-rate-table" role="tabpanel" aria-labelledby="pills-profile-tab" tabindex="0">
                                <div class="row">
                                    <div class="col-sm-6 col-xl-4">
                                        <div class="card border-0 mb-3">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center border-bottom pb-3 mb-3">
                                                    <p class="mb-0">Â£1</p>
                                                    <span class="plane-icon bg-primary mx-2">
                                                        <img src="{{ asset('users/assets/images/icons/mini-icon/Repeat.png') }}" alt="">
                                                    </span>
                                                    <p class="mb-0">â‚¦890.00</p>
                                                </div>
                                                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                                                    <div class="mb-0 text-danger d-flex align-items-center">
                                                        <span class="plane-icon bg-danger me-2">
                                                        <img src="{{ asset('users/assets/images/icons/mini-icon/send-down.png') }}" alt="">
                                                        </span> <span>â‚¦889.00</span>
                                                    </div>
                                                    <div class="mb-0 text-success d-flex align-items-center">
                                                        <span class="plane-icon bg-primary me-2">
                                                        <img src="{{ asset('users/assets/images/icons/mini-icon/send.png') }}" alt="">
                                                        </span>
                                                        <span>â‚¦889.00</span>
                                                    </div>
                                                    <div class="mb-0 text-black d-flex align-items-center">
                                                        <span class="plane-icon bg-black me-2">
                                                        <img src="{{ asset('users/assets/images/icons/mini-icon/account_balance.png') }}" alt="">
                                                        </span>
                                                        <span>â‚¦889.00</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>                                            
                                    </div>
                                    <div class="col-sm-6 col-xl-4">
                                        <div class="card border-0 mb-3">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center border-bottom pb-3 mb-3">
                                                    <p class="mb-0">Â£1</p>
                                                    <span class="plane-icon bg-primary mx-2">
                                                        <img src="{{ asset('users/assets/images/icons/mini-icon/Repeat.png') }}" alt="">
                                                    </span>
                                                    <p class="mb-0">â‚¦890.00</p>
                                                </div>
                                                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                                                    <div class="mb-0 text-danger d-flex align-items-center">
                                                        <span class="plane-icon bg-danger me-2">
                                                        <img src="{{ asset('users/assets/images/icons/mini-icon/send-down.png') }}" alt="">
                                                        </span> <span>â‚¦889.00</span>
                                                    </div>
                                                    <div class="mb-0 text-success d-flex align-items-center">
                                                        <span class="plane-icon bg-primary me-2">
                                                        <img src="{{ asset('users/assets/images/icons/mini-icon/send.png') }}" alt="">
                                                        </span>
                                                        <span>â‚¦889.00</span>
                                                    </div>
                                                    <div class="mb-0 text-black d-flex align-items-center">
                                                        <span class="plane-icon bg-black me-2">
                                                        <img src="{{ asset('users/assets/images/icons/mini-icon/account_balance.png') }}" alt="">
                                                        </span>
                                                        <span>â‚¦889.00</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>                                            
                                    </div>
                                    <div class="col-sm-6 col-xl-4">
                                        <div class="card border-0 mb-3">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center border-bottom pb-3 mb-3">
                                                    <p class="mb-0">Â£1</p>
                                                    <span class="plane-icon bg-primary mx-2">
                                                        <img src="{{ asset('users/assets/images/icons/mini-icon/Repeat.png') }}" alt="">
                                                    </span>
                                                    <p class="mb-0">â‚¦890.00</p>
                                                </div>
                                                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                                                    <div class="mb-0 text-danger d-flex align-items-center">
                                                        <span class="plane-icon bg-danger me-2">
                                                        <img src="{{ asset('users/assets/images/icons/mini-icon/send-down.png') }}" alt="">
                                                        </span> <span>â‚¦889.00</span>
                                                    </div>
                                                    <div class="mb-0 text-success d-flex align-items-center">
                                                        <span class="plane-icon bg-primary me-2">
                                                        <img src="{{ asset('users/assets/images/icons/mini-icon/send.png') }}" alt="">
                                                        </span>
                                                        <span>â‚¦889.00</span>
                                                    </div>
                                                    <div class="mb-0 text-black d-flex align-items-center">
                                                        <span class="plane-icon bg-black me-2">
                                                        <img src="{{ asset('users/assets/images/icons/mini-icon/account_balance.png') }}" alt="">
                                                        </span>
                                                        <span>â‚¦889.00</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>                                            
                                    </div>
                                    <div class="col-sm-6 col-xl-4">
                                        <div class="card border-0 mb-3">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center border-bottom pb-3 mb-3">
                                                    <p class="mb-0">Â£1</p>
                                                    <span class="plane-icon bg-primary mx-2">
                                                        <img src="{{ asset('users/assets/images/icons/mini-icon/Repeat.png') }}" alt="">
                                                    </span>
                                                    <p class="mb-0">â‚¦890.00</p>
                                                </div>
                                                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                                                    <div class="mb-0 text-danger d-flex align-items-center">
                                                        <span class="plane-icon bg-danger me-2">
                                                        <img src="{{ asset('users/assets/images/icons/mini-icon/send-down.png') }}" alt="">
                                                        </span> <span>â‚¦889.00</span>
                                                    </div>
                                                    <div class="mb-0 text-success d-flex align-items-center">
                                                        <span class="plane-icon bg-primary me-2">
                                                        <img src="{{ asset('users/assets/images/icons/mini-icon/send.png') }}" alt="">
                                                        </span>
                                                        <span>â‚¦889.00</span>
                                                    </div>
                                                    <div class="mb-0 text-black d-flex align-items-center">
                                                        <span class="plane-icon bg-black me-2">
                                                        <img src="{{ asset('users/assets/images/icons/mini-icon/account_balance.png') }}" alt="">
                                                        </span>
                                                        <span>â‚¦889.00</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>                                            
                                    </div>
                                    <div class="col-sm-6 col-xl-4">
                                        <div class="card border-0 mb-3">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center border-bottom pb-3 mb-3">
                                                    <p class="mb-0">Â£1</p>
                                                    <span class="plane-icon bg-primary mx-2">
                                                        <img src="{{ asset('users/assets/images/icons/mini-icon/Repeat.png') }}" alt="">
                                                    </span>
                                                    <p class="mb-0">â‚¦890.00</p>
                                                </div>
                                                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                                                    <div class="mb-0 text-danger d-flex align-items-center">
                                                        <span class="plane-icon bg-danger me-2">
                                                        <img src="{{ asset('users/assets/images/icons/mini-icon/send-down.png') }}" alt="">
                                                        </span> <span>â‚¦889.00</span>
                                                    </div>
                                                    <div class="mb-0 text-success d-flex align-items-center">
                                                        <span class="plane-icon bg-primary me-2">
                                                        <img src="{{ asset('users/assets/images/icons/mini-icon/send.png') }}" alt="">
                                                        </span>
                                                        <span>â‚¦889.00</span>
                                                    </div>
                                                    <div class="mb-0 text-black d-flex align-items-center">
                                                        <span class="plane-icon bg-black me-2">
                                                        <img src="{{ asset('users/assets/images/icons/mini-icon/account_balance.png') }}" alt="">
                                                        </span>
                                                        <span>â‚¦889.00</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>                                            
                                    </div>
                                    <div class="col-sm-6 col-xl-4">
                                        <div class="card border-0 mb-3">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center border-bottom pb-3 mb-3">
                                                    <p class="mb-0">Â£1</p>
                                                    <span class="plane-icon bg-primary mx-2">
                                                        <img src="{{ asset('users/assets/images/icons/mini-icon/Repeat.png') }}" alt="">
                                                    </span>
                                                    <p class="mb-0">â‚¦890.00</p>
                                                </div>
                                                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                                                    <div class="mb-0 text-danger d-flex align-items-center">
                                                        <span class="plane-icon bg-danger me-2">
                                                        <img src="{{ asset('users/assets/images/icons/mini-icon/send-down.png') }}" alt="">
                                                        </span> <span>â‚¦889.00</span>
                                                    </div>
                                                    <div class="mb-0 text-success d-flex align-items-center">
                                                        <span class="plane-icon bg-primary me-2">
                                                        <img src="{{ asset('users/assets/images/icons/mini-icon/send.png') }}" alt="">
                                                        </span>
                                                        <span>â‚¦889.00</span>
                                                    </div>
                                                    <div class="mb-0 text-black d-flex align-items-center">
                                                        <span class="plane-icon bg-black me-2">
                                                        <img src="{{ asset('users/assets/images/icons/mini-icon/account_balance.png') }}" alt="">
                                                        </span>
                                                        <span>â‚¦889.00</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>                                            
                                    </div>
                                    <div class="col-sm-6 col-xl-4">
                                        <div class="card border-0 mb-3">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center border-bottom pb-3 mb-3">
                                                    <p class="mb-0">Â£1</p>
                                                    <span class="plane-icon bg-primary mx-2">
                                                        <img src="{{ asset('users/assets/images/icons/mini-icon/Repeat.png') }}" alt="">
                                                    </span>
                                                    <p class="mb-0">â‚¦890.00</p>
                                                </div>
                                                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                                                    <div class="mb-0 text-danger d-flex align-items-center">
                                                        <span class="plane-icon bg-danger me-2">
                                                        <img src="{{ asset('users/assets/images/icons/mini-icon/send-down.png') }}" alt="">
                                                        </span> <span>â‚¦889.00</span>
                                                    </div>
                                                    <div class="mb-0 text-success d-flex align-items-center">
                                                        <span class="plane-icon bg-primary me-2">
                                                        <img src="{{ asset('users/assets/images/icons/mini-icon/send.png') }}" alt="">
                                                        </span>
                                                        <span>â‚¦889.00</span>
                                                    </div>
                                                    <div class="mb-0 text-black d-flex align-items-center">
                                                        <span class="plane-icon bg-black me-2">
                                                        <img src="{{ asset('users/assets/images/icons/mini-icon/account_balance.png') }}" alt="">
                                                        </span>
                                                        <span>â‚¦889.00</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>                                            
                                    </div>
                                    <div class="col-sm-6 col-xl-4">
                                        <div class="card border-0 mb-3">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center border-bottom pb-3 mb-3">
                                                    <p class="mb-0">Â£1</p>
                                                    <span class="plane-icon bg-primary mx-2">
                                                        <img src="{{ asset('users/assets/images/icons/mini-icon/Repeat.png') }}" alt="">
                                                    </span>
                                                    <p class="mb-0">â‚¦890.00</p>
                                                </div>
                                                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                                                    <div class="mb-0 text-danger d-flex align-items-center">
                                                        <span class="plane-icon bg-danger me-2">
                                                        <img src="{{ asset('users/assets/images/icons/mini-icon/send-down.png') }}" alt="">
                                                        </span> <span>â‚¦889.00</span>
                                                    </div>
                                                    <div class="mb-0 text-success d-flex align-items-center">
                                                        <span class="plane-icon bg-primary me-2">
                                                        <img src="{{ asset('users/assets/images/icons/mini-icon/send.png') }}" alt="">
                                                        </span>
                                                        <span>â‚¦889.00</span>
                                                    </div>
                                                    <div class="mb-0 text-black d-flex align-items-center">
                                                        <span class="plane-icon bg-black me-2">
                                                        <img src="{{ asset('users/assets/images/icons/mini-icon/account_balance.png') }}" alt="">
                                                        </span>
                                                        <span>â‚¦889.00</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>                                            
                                    </div>
                                    <div class="col-sm-6 col-xl-4">
                                        <div class="card border-0 mb-3">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center border-bottom pb-3 mb-3">
                                                    <p class="mb-0">Â£1</p>
                                                    <span class="plane-icon bg-primary mx-2">
                                                        <img src="{{ asset('users/assets/images/icons/mini-icon/Repeat.png') }}" alt="">
                                                    </span>
                                                    <p class="mb-0">â‚¦890.00</p>
                                                </div>
                                                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                                                    <div class="mb-0 text-danger d-flex align-items-center">
                                                        <span class="plane-icon bg-danger me-2">
                                                        <img src="{{ asset('users/assets/images/icons/mini-icon/send-down.png') }}" alt="">
                                                        </span> <span>â‚¦889.00</span>
                                                    </div>
                                                    <div class="mb-0 text-success d-flex align-items-center">
                                                        <span class="plane-icon bg-primary me-2">
                                                        <img src="{{ asset('users/assets/images/icons/mini-icon/send.png') }}" alt="">
                                                        </span>
                                                        <span>â‚¦889.00</span>
                                                    </div>
                                                    <div class="mb-0 text-black d-flex align-items-center">
                                                        <span class="plane-icon bg-black me-2">
                                                        <img src="{{ asset('users/assets/images/icons/mini-icon/account_balance.png') }}" alt="">
                                                        </span>
                                                        <span>â‚¦889.00</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>                                            
                                    </div>
                                </div>
                            </div> -->
                            <!-- RATE TABLE END -->
                        </div>
                    </div> 
                </div>
            </div>
        </div>  
    </div>
@endsection
@section('script') 
    <script>
        /* --------------- HANDLE LOGIN TOASTER --------------- */
        var isFirstView = localStorage.getItem('isFirstView') || '';
        if (isFirstView !== 'Yes') {
            /* show message to use as this is first view. */
            localStorage.setItem('isFirstView', 'Yes');
        }
        /* --------------- HANDLE LOGIN TOASTER --------------- */

        $(document).ready(function() {
            get_my_wallets();
            get_transactions();
            get_hot_swap_offers();
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