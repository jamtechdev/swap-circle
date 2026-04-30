@extends('layout.users.master')
@section('content')
    <div class="page-content-wrapper">
        <div class="page-content-tab">
            <div class="container-fluid px-4 pb-4">
                <div class="billing-payment">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb d-flex align-items-center">
                            <li class="breadcrumb-item">
                                <a href="{{ url('/users/profile') }}" class="text-primary">Profile</a>
                            </li>
                            <li class="mx-3">
                                <svg width="5" height="10" viewBox="0 0 5 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M4.21749 3.11406C5.22417 4.12074 5.25773 5.73205 4.31816 6.77904L4.21749 6.88529L1.47119 9.47108C1.21084 9.73143 0.788734 9.73143 0.528385 9.47108C0.288062 9.23076 0.269576 8.8526 0.472926 8.59107L0.528385 8.52827L3.27468 5.94248C3.76797 5.44919 3.79393 4.66553 3.35257 4.14168L3.27468 4.05687L0.528385 1.47108C0.268035 1.21073 0.268035 0.78862 0.528385 0.52827C0.768707 0.287947 1.14686 0.269461 1.40839 0.472811L1.47119 0.52827L4.21749 3.11406Z" fill="#21333B"/>
                                </svg>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                <a href="javascript:void(0)">Accounts</a>
                            </li> 
                        </ol>
                    </nav>
                    <div class="row mt-4">
                        <div class="col-12 mb-4">
                            <h3 class="fw-bold sub-heading text-black">Your Account</h3>
                        </div>
                    </div>
                    <!-- all accounts start -->
                    <div class="row" id="all_accounts"></div>
                    <!-- all accounts end -->

                    <!-- modal withdraw amount start -->
                    <div class="modal fade modal-lg" id="mdl_withdraw_amount" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-body p-5">
                                    <div class="d-flex align-items-center mb-4">
                                        <svg class="flex-grow-0 pointer" data-bs-dismiss="modal" aria-label="Close" width="12" height="20" viewBox="0 0 12 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M10 20L0 10L10 0L11.775 1.775L3.55 10L11.775 18.225L10 20Z" fill="#4BD16F"/>
                                        </svg>
                                        <h2 class="flex-grow-1 modal-heading">Account Detail</h2>
                                    </div>
                                    <form id="frm_withdraw_amount">
                                        @csrf
                                        <div class="row mt-0">
                                            <input type="hidden" id="wa_accounts_id" value="" readonly>
                                            <div class="col-lg-6 col-md-6">
                                                <div class="form-group mb-4">
                                                    <label class="form-label mb-3">Currency</label>
                                                    <select class="form-select form-select-lg" aria-label=".form-select-lg example" name="wa_currency" id="wa_currency">   
                                                    </select>
                                                    <span class="error_msg" id="error_wa_currency"></span>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6">
                                                <div class="form-group mb-4">
                                                    <label class="form-label mb-3">Bank Name</label>
                                                    <input type="text" name="wa_bank_name" id="wa_bank_name" class="form-control" readonly>
                                                    <span class="error_msg" id="error_wa_bank_name"></span>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6">
                                                <div class="form-group mb-4">
                                                    <label class="form-label mb-3">Account Number</label>
                                                    <input type="text" name="wa_account_number" id="wa_account_number" class="form-control" readonly>
                                                    <span class="error_msg" id="wa_account_number"></span>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6">
                                                <div class="form-group mb-4">
                                                    <label class="form-label mb-3">Amount</label>
                                                    <input type="text" name="wa_amount" id="wa_amount"  placeholder="Enter Amount" class="form-control" min="1" step="0.01">
                                                    <span class="error_msg" id="error_wa_amount"></span>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12">
                                                <div class="form-group mb-4">
                                                    <label class="form-label mb-3">Account Notes</label>
                                                    <textarea name="wa_account_notes" id="wa_account_notes" placeholder="Account Notes" class="form-control"></textarea>
                                                    <span class="error_msg" id="error_wa_account_notes"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-4 mx-auto">
                                                <div class="mt-10">
                                                    <button type="submit" class="btn btn-login btn-primary w-100">WithDraw</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>                     
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- modal withdraw amount end -->

                    <!-- add account -->
                    <div class="d-flex justify-content-center align-items-center position-fixed" style="bottom: 20px; left: 60%; transform: translateX(-50%);">
                        <button class="btn btn-primary btn-login w-100" onclick="get_add_account_params()" style="padding: 0px 100px;">Add Account</button>
                    </div>
                    <!-- add account -->

                    <!-- modal add account start -->
                    <div class="modal fade modal-lg" id="mdl_add_account" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-body py-5 px-4">
                                    <div class="d-flex align-items-center mb-5">
                                        <svg class="flex-grow-0 pointer" data-bs-dismiss="modal" aria-label="Close" width="12" height="20" viewBox="0 0 12 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M10 20L0 10L10 0L11.775 1.775L3.55 10L11.775 18.225L10 20Z" fill="#4BD16F"/>
                                        </svg>
                                        <h2 class="flex-grow-1 modal-heading">Add Account</h2>
                                    </div>
                                    <!-- <div class="row mt-37"> -->
                                        <!-- <div class="col-lg-11 mx-auto"> -->
                                            <form id="frm_add_account">
                                                @csrf
                                                <div class="row px-3">
                                                    <div class="col-md-6">
                                                        <div class="form-group mb-4">
                                                            <label class="form-label mb-3">Currency</label>
                                                            <select class="form-select form-select-lg" aria-label=".form-select-lg example" name="account_currency" id="account_currency">
                                                                <option value="" disabled selected hidden>Select currency</option>
                                                            </select>
                                                            <span class="error_msg" id="error_account_currency"></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group mb-4">
                                                            <label class="form-label mb-3">Full name of account holder</label>
                                                            <input type="text" name="account_holder_name" id="account_holder_name" placeholder="Account holder name" class="form-control">
                                                            <span class="error_msg" id="error_account_holder_name"></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group mb-4">
                                                            <label class="form-label mb-3">Bank Name</label>
                                                            <input type="text" name="account_bank_name" id="account_bank_name" placeholder="Bank name" class="form-control">
                                                            <span class="error_msg" id="error_account_bank_name"></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group mb-4">
                                                            <label class="form-label mb-3">Branch Code</label>
                                                            <input type="text" name="account_branch_code" id="account_branch_code" placeholder="******" class="form-control">
                                                            <span class="error_msg" id="error_account_branch_code"></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group mb-4">
                                                            <label class="form-label mb-3">Account Number</label>
                                                            <input type="text" name="account_number" id="account_number" placeholder="************" class="form-control">
                                                            <span class="error_msg" id="error_account_number"></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label mb-3">BIC/IBAN</label>
                                                            <input type="text" name="account_iban" id="account_iban" placeholder="************" class="form-control">
                                                            <span class="error_msg" id="error_account_iban"></span>
                                                        </div>
                                                    </div>
                                                    <div class="mt-37">
                                                        <button type="submit" class="btn btn-login btn-primary w-100">Save</button>
                                                    </div>
                                                </div>
                                            </form>
                                        <!-- </div> -->
                                    <!-- </div>                        -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- modal add account end -->
                </div>
            </div>
        </div> 
    </div>
@endsection
@section('script') 
    <script>
        $(document).ready(function() {
            get_all_accounts();
        });
    </script>
@endsection