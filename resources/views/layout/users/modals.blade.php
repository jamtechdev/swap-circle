<!-- modal send offer start -->
<div class="modal fade" id="mdl_send_offer" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body py-5 px-4 text-center">
                <div class="d-flex justify-content-center align-items-center gap-2 mb-30">
                    <input type="hidden" id="so_swap_offers_id" value="">

                    <p class="mb-0 fs-4 text-black" id="so_from_currency"></p>
                    <span class="plane-icon wh-40 bg-primary mx-2">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M3.625 8.625C3.625 8.27982 3.34518 8 3 8C2.65482 8 2.375 8.27982 2.375 8.625C2.375 11.0412 4.33375 13 6.75 13H11.4907L10.6829 13.808L10.6309 13.8669C10.4402 14.1121 10.4575 14.4666 10.6828 14.6919C10.9269 14.936 11.3226 14.936 11.5667 14.692L13.4417 12.8172L13.4419 12.817L13.4935 12.7585C13.4775 12.7792 13.4601 12.7988 13.4417 12.8172M11.4907 11.75H6.75L6.61444 11.7471C4.95144 11.6761 3.625 10.3055 3.625 8.625" fill="#E8F9DC"/>
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M5.3167 1.30806C5.07262 1.06398 4.67689 1.06398 4.43281 1.30806L2.55781 3.18306L2.55599 3.18513C2.53906 3.20221 2.52312 3.22026 2.50825 3.23919L2.50582 3.24194C2.31518 3.48712 2.33251 3.84164 2.55781 4.06694L4.43281 5.94194L4.49169 5.99393C4.73688 6.18458 5.09139 6.16724 5.3167 5.94194L5.36869 5.88306C5.55933 5.63788 5.542 5.28336 5.3167 5.05806L4.5087 4.25H9.24999L9.38554 4.25289C11.0485 4.32386 12.375 5.69453 12.375 7.375C12.375 7.72018 12.6548 8 13 8C13.3452 8 13.625 7.72018 13.625 7.375C13.625 4.95875 11.6662 3 9.24999 3H4.5087L5.3167 2.19194L5.36869 2.13306C5.55933 1.88788 5.542 1.53336 5.3167 1.30806Z" fill="white"/>
                        </svg>
                    </span>
                    <p class="mb-0 fs-4 text-black" id="so_exchange_rate"></p> 
                </div>
                <div class="d-flex flex-column gap-5">
                    <div class="row px-5">
                        <div class="col-lg-12 d-flex justify-content-between">
                            <div class="col-lg-6 d-flex align-items-left justify-content-left mb-3">Amount</div>
                            <div class="col-lg-6 d-flex mb-3">
                                <div class="mb-0 text-danger d-flex">
                                    <span class="plane-icon bg-danger me-2">
                                        <img src="{{ asset('users/assets/images/icons/mini-icon/send-down.png') }}" alt="">
                                    </span>
                                    <span id="so_amount"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 d-flex justify-content-between">
                            <div class="col-lg-6 d-flex align-items-left justify-content-left mb-3">Converted Amount</div>
                            <div class="col-lg-6 mb-3">
                                <div class="mb-0 text-success d-flex"> 
                                    <span class="plane-icon bg-primary me-2">
                                        <img src="{{ asset('users/assets/images/icons/mini-icon/send.png') }}" alt="">
                                    </span>
                                    <span id="so_converted_amount"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 d-flex justify-content-between">
                            <div class="col-lg-6 d-flex align-items-left justify-content-left mb-3">Base Amount</div>
                            <div class="col-lg-6 mb-3">
                                <div class="mb-0 text-black d-flex">
                                    <span class="plane-icon bg-black me-2">
                                        <img src="{{ asset('users/assets/images/icons/mini-icon/account_balance.png') }}" alt="">
                                    </span>
                                    <span id="so_base_amount"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <a href="javascript:void(0)" class="btn btn-login btn-primary w-100 mt-0" data-bs-dismiss="modal" onclick="send_offer()" style="margin-top: -25px !important;">Send Offer</a>
                </div>
            </div>
        </div>
    </div>
</div> 
<!-- modal send offer end -->

<!-- modal create offer start -->
<div class="modal fade modal-xl" id="mdl_create_offer" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body p-5">
                <div class="d-flex align-items-center mb-5">
                    <svg class="flex-grow-0 pointer" data-bs-dismiss="modal" aria-label="Close" width="12" height="20" viewBox="0 0 12 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M10 20L0 10L10 0L11.775 1.775L3.55 10L11.775 18.225L10 20Z" fill="#4BD16F"/>
                    </svg>
                    <h2 class="flex-grow-1 modal-heading">Create Offer</h2>
                </div>
                <form id="frm_create_offer">
                    @csrf
                    <div class="row mt-37">
                        <div class="col-lg-4 col-md-6">
                            <div class="form-group mb-4">
                                <label class="form-label mb-3">From Account</label>
                                <select class="form-select form-select-lg" aria-label=".form-select-lg example" name="co_from_account" id="co_from_account">
                                    <option value="" disabled selected hidden>Select</option>
                                </select>
                                <span class="error_msg" id="error_co_from_account"></span>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="form-group mb-4">
                                <label class="form-label mb-3">Total Amount</label>
                                <input type="text" name="co_total_amount" id="co_total_amount" placeholder="Enter Amount" class="form-control" min="1" step="0.01">
                                <span class="error_msg" id="error_co_total_amount"></span>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="form-group mb-4">
                                <label class="form-label mb-3">Exchange Currency</label>
                                <select class="form-select form-select-lg" aria-label=".form-select-lg example" name="co_exchange_currency" id="co_exchange_currency">
                                    <option value="" disabled selected hidden>Select</option>
                                </select>
                                <span class="error_msg" id="error_co_exchange_currency"></span>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="form-group mb-4">
                                <label class="form-label mb-3">Exchange Rate</label>
                                <input type="text" name="co_exchange_rate" id="co_exchange_rate" placeholder="Enter Amount" class="form-control" min="0.01" step="0.01">
                                <span class="error_msg" id="error_co_exchange_rate"></span>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="form-group mb-4">
                                <label class="form-label mb-3">Expires In</label>
                                <input type="text" name="co_expires_in" id="co_expires_in" placeholder="Enter time in hours" class="form-control">
                                <span class="error_msg" id="error_co_expires_in"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 mx-auto">
                            <div class="mt-37">
                                <button type="submit" class="btn btn-login btn-primary w-100">Save</button>
                            </div>
                        </div>
                    </div>
                </form>                      
            </div>
        </div>
    </div>
</div>
<!-- modal create offer end -->