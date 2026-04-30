@extends('layout.users.master')
@section('content') 
    <div class="page-content-wrapper">
        <div class="page-content-tab">
            <div class="container-fluid px-4 pb-4">
                <div class="wallet-wrapper">
                    <!-- link start -->
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb d-flex align-items-center">
                            <li class="breadcrumb-item"><a href="{{ url('/users/dashboard') }}" class="text-primary">Home</a></li>
                            <li class="mx-3  d-flex align-items-center">
                                <svg width="5" height="10" viewBox="0 0 5 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M4.21749 3.11406C5.22417 4.12074 5.25773 5.73205 4.31816 6.77904L4.21749 6.88529L1.47119 9.47108C1.21084 9.73143 0.788734 9.73143 0.528385 9.47108C0.288062 9.23076 0.269576 8.8526 0.472926 8.59107L0.528385 8.52827L3.27468 5.94248C3.76797 5.44919 3.79393 4.66553 3.35257 4.14168L3.27468 4.05687L0.528385 1.47108C0.268035 1.21073 0.268035 0.78862 0.528385 0.52827C0.768707 0.287947 1.14686 0.269461 1.40839 0.472811L1.47119 0.52827L4.21749 3.11406Z" fill="#21333B"/>
                                </svg>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page"><a href="javascript:void()">List of Wallets</a></li> 
                        </ol>
                    </nav>
                    <!-- link end -->

                    <!-- wallets start -->
                    <ul class="wallet-items d-flex flex-wrap justify-content-start list-unstyled mt-4" id="all_wallets"></ul>
                    <!-- wallets end-->
                </div>
            </div>
        </div> 
        <div class="d-flex justify-content-center align-items-center position-fixed" style="bottom: 20px; left: 60%; transform: translateX(-50%);">
            <button class="btn btn-primary btn-login w-100" onclick="get_create_swap_params()" style="padding: 0px 100px;">Create Swap</button>
        </div>
        <!-- modal create swap start -->
        <div class="modal fade" id="mdl_create_swap" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body py-5 px-4">
                        <div class="d-flex align-items-center mb-5">
                            <svg class="flex-grow-0 pointer" data-bs-dismiss="modal" aria-label="Close" width="12" height="20" viewBox="0 0 12 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M10 20L0 10L10 0L11.775 1.775L3.55 10L11.775 18.225L10 20Z" fill="#4BD16F"/>
                            </svg>
                            <h2 class="flex-grow-1 modal-heading">Create Swap</h2>
                        </div>
                        <div class="row mt-0">
                            <div class="col-lg-8 mx-auto">
                                <form id="frm_create_swap">
                                    @csrf
                                    <div class="col-12 d-flex align-items-between justify-content-between gap-4 mb-3">
                                        <h6 class="text-black mb-0">Base Currency</h6>
                                        <p class="mb-0 fs-6 text-black" id="cs_base_currency"></p>
                                    </div>
                                    <div class="form-group mb-4">
                                        <label class="form-label mb-3">From Account</label>
                                        <select class="form-select form-select-lg" aria-label=".form-select-lg example" name="cs_from_account" id="cs_from_account"></select>
                                        <span class="error_msg" id="error_cs_from_account"></span>
                                        <span class="text-success" id="cs_from_account_amount"></span>
                                    </div>
                                    <div class="form-group mb-4">
                                        <label class="form-label mb-3">Total Amount</label>
                                        <input type="text" name="cs_total_amount" id="cs_total_amount" placeholder="Enter Amount" class="form-control text-capitalize" min="1" step="0.01">
                                        <span class="error_msg" id="error_cs_total_amount"></span>
                                    </div>
                                    <div class="form-group mb-4">
                                        <label class="form-label mb-3">To Account</label>
                                        <select class="form-select form-select-lg" aria-label=".form-select-lg example" name="cs_to_account" id="cs_to_account"></select>
                                        <span class="error_msg" id="error_cs_to_account"></span>
                                        <span class="text-success" id="cs_to_account_amount"></span>
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
        <!-- modal create swap end -->
    </div>
@endsection
@section('script') 
    <script>
        $(document).ready(function() {
            get_all_wallets();
        });
    </script>
@endsection