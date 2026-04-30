@extends('layout.users.master')
@section('content')
    <div class="page-content-wrapper">
        <div class="page-content-tab">
            <div class="container-fluid px-4 pb-4">
                <div class="wallet-wrapper">
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-2">
                        <h3 class="fw-bold sub-heading text-black">Track rates of currencies</h3>
                    </div>
                    <div class="wallet-tabs">
                        <ul class="nav nav-pills me-auto mb-3" id="pills-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-buy" type="button" role="tab" aria-controls="pills-buy" aria-selected="false">Buy</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-sell" type="button" role="tab" aria-controls="pills-sell" aria-selected="false">Sell</button>
                            </li>
                        </ul>
                        <div class="tab-content" id="pills-tabContent">
                            <!-- buy start -->
                            <div class="tab-pane fade show active" id="pills-buy" role="tabpanel" aria-labelledby="pills-profile-tab" tabindex="0">
                                <div class="row">
                                    <div class="col-lg-6 mx-auto mb-0">
                                       <div class="card bg-transparent rate-box-top mb-4">
                                        <div class="card-body px-4">
                                            <div class="d-flex justify-content-between align-items-end mb-4">
                                                <h4 class="fw-bold mb-3" id="buy_from_currency_code">USD</h4>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="d-flex align-items-center gap-3" id="select_tag">
                                                    <select class="w-auto form-select-lg mb-0 bg-transparent fw-normal track-title" aria-label=".form-select-lg example" id="buy_from_currency">
                                                        <option symbol="$" value="2" disabled selected hidden>$</option>
                                                    </select>
                                                    <input type="text" class="form-control sub-heading text-black fw-bold mb-0 border border-2" placeholder="enter amount" id="buy_entered_amount" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="half-circle-top"></div>
                                        </div> 
                                        <div class="position-relative">
                                            <a href="#" class="track-btn"><img src="{{ asset('users/assets/images/icons/Repeat.svg') }}" alt="" srcset=""></a>
                                        </div>                                         
                                        <div class="card border-0 rate-box-bottom">
                                        <div class="half-circle-top bottom"></div>
                                        <div class="card-body px-4 pt-0">
                                            <div class="d-flex justify-content-between align-items-end mb-4">
                                                <h4 class="fw-bold mb-3" id="buy_to_currency_code">EUR</h4>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <div class="d-flex align-items-center" id="select_tag">
                                                    <select class="w-auto form-select-lg mb-0 bg-transparent fw-normal track-title" aria-label=".form-select-lg example" id="buy_to_currency">
                                                        <option symbol="â‚¬"value="11" disabled selected hidden>â‚¬</option>
                                                    </select>
                                                    <p class="sub-heading text-black fw-bold mb-0 track-title" id="buy_converted_amount"></p>
                                                </div>
                                            </div>
                                        </div>
                                       </div> 
                                       <div class="d-flex flex-column p-3 d-none" id="buy_rates">
                                            <div class="d-flex pb-2">
                                                <span class="text-success" style="margin-right: 105.3px;">Live Rate</span> 
                                                <span class="fw-bold" id="buy_live_rate"></span>
                                            </div>
                                            <div class="d-flex">
                                                <span class="text-success" style="margin-right: 90px;">Admin Rate</span>  
                                                <span class="fw-bold" id="buy_admin_rate"></span>
                                            </div> 
                                            <!-- <div class="mb-0 text-danger d-flex px-5" id="buy_entered_amount2"></div>
                                            <div class="mb-0 text-success d-flex px-5" id="buy_converted_amount2"> </div>
                                            <div class="mb-0 text-black d-flex px-5" id="buy_base_amount"></div>  -->
                                        </div>                                         
                                    </div>
                                    <div class="col-12 mt-4">
                                        <button class="btn btn-login btn-primary w-100" onclick="convert_buy_currency()">Convert Currency</a>
                                        <!-- <a href="{{ url('/users/billing_payment') }}" class="btn btn-login btn-primary w-100">Buy APPL</a> -->
                                    </div>
                                </div>
                            </div>
                            <!-- buy end -->
                            
                            <!-- sell start -->
                            <div class="tab-pane fade" id="pills-sell" role="tabpanel" aria-labelledby="pills-profile-tab" tabindex="0">
                                <div class="row">
                                    <div class="col-lg-6 mx-auto mb-0">
                                       <div class="card bg-transparent rate-box-top mb-4">
                                        <div class="card-body px-4">
                                            <div class="d-flex justify-content-between align-items-end mb-4">
                                                <h4 class="fw-bold mb-3" id="sell_from_currency_code">USD</h4>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="d-flex align-items-center" id="select_tag">
                                                    <select class="w-auto form-select-lg mb-0 bg-transparent fw-normal track-title" aria-label=".form-select-lg example" id="sell_from_currency">
                                                        <option symbol="$" value="2" disabled selected hidden>$</option>
                                                    </select>
                                                    <input type="text" class="form-control" placeholder="enter amount" id="sell_entered_amount" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="half-circle-top"></div>
                                        </div> 
                                        <div class="position-relative">
                                            <a href="#" class="track-btn"><img src="{{ asset('users/assets/images/icons/Repeat.svg') }}" alt="" srcset=""></a>
                                        </div>                                         
                                        <div class="card border-0 rate-box-bottom">
                                            <div class="half-circle-top bottom"></div>
                                            <div class="card-body px-4 pt-0">
                                                <div class="d-flex justify-content-between align-items-end mb-4">
                                                    <h4 class="fw-bold mb-3" id="sell_to_currency_code">EUR</h4>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center mb-3">
                                                    <div class="d-flex align-items-center" id="select_tag">
                                                        <select class="w-auto form-select-lg mb-0 bg-transparent fw-normal track-title" aria-label=".form-select-lg example" id="sell_to_currency">
                                                            <option symbol="â‚¬" value="11" disabled selected hidden>â‚¬</option>
                                                        </select>
                                                        <p class="sub-heading text-black fw-bold mb-0 track-title" id="sell_converted_amount"></p>
                                                    </div>
                                                </div>
                                            </div>
                                       </div> 
                                       <div class="d-flex flex-column p-3 d-none" id="sell_rates">
                                            <div class="d-flex pb-2">
                                                <span class="text-success" style="margin-right: 105.3px;">Live Rate</span> 
                                                <span class="fw-bold" id="sell_live_rate"></span>
                                            </div>
                                            <div class="d-flex">
                                                <span class="text-success" style="margin-right: 90px;">Admin Rate</span>  
                                                <span class="fw-bold" id="sell_admin_rate"></span>
                                            </div> 
                                            <!-- <div class="mb-0 text-danger d-flex px-5" id="sell_entered_amount2"></div>
                                            <div class="mb-0 text-success d-flex px-5" id="sell_converted_amount2"></div>
                                            <div class="mb-0 text-black d-flex px-5" id="sell_base_amount"></div>  -->
                                        </div>                                         
                                    </div>
                                    <div class="col-12 mt-4">
                                        <button class="btn btn-login btn-primary w-100" onclick="convert_sell_currency()">Convert Currency</a>
                                        <!-- <a href="{{ url('/users/billing_payment') }}" class="btn btn-login btn-primary w-100">Buy APPL</a> -->
                                    </div>
                                </div>
                            </div>
                            <!-- sell end -->
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
            get_buy_sell_currencies();
        });
    </script>
@endsection