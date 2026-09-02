@extends('layout.users.master')

@section('page_title', 'Checkout')
@section('page_subtitle', $product->name ?? 'Complete your purchase')

@section('content')
    @php
        $requiresAgeLimit = (int) ($product->products_id ?? 0) === 1 || \Illuminate\Support\Str::contains(
            \Illuminate\Support\Str::lower((string) ($product->name ?? '')),
            'nigerian community beneficiary'
        );
    @endphp
    <input type="hidden" id="product_age_limited" value="{{ $requiresAgeLimit ? 1 : 0 }}">
    <style>
        .box-container {
            display: flex;
            gap: 7px; /* spacing between boxes */
            align-items: center;
        }
        .box {
            width: 18px;
            height: 18px;
            border: 1px solid #333; /* border around each box */
            background-color: white; /* empty box */
        }
        .box.filled {
            background-color: green; /* filled box */
        }
        .acknowledge_text {
            display: block;
            margin-bottom: 0.25rem;
        }
        /* FIX jQuery UI Datepicker month/year UI */
.ui-datepicker {
    z-index: 9999 !important;
}

.ui-datepicker-header {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 6px;
}

.ui-datepicker-title {
    display: flex;
    gap: 6px;
}

.ui-datepicker select.ui-datepicker-month,
.ui-datepicker select.ui-datepicker-year {
    width: auto;
    min-width: 80px;
    padding: 2px 6px;
    font-size: 14px;
    border-radius: 4px;
}

/* ===== SELECT2 FIX FOR SWAP CIRCLE UI ===== */

/* Main select */
.select2-container {
    width: 100% !important;
}

.select2-container--default .select2-selection--single {
    height: 40px;
    border-radius: 999px;
    border: 1px solid #ced4da;
    background-color: #fff;
    padding: 0 16px;
    display: flex;
    align-items: center;
}

/* Selected text */
.select2-container--default .select2-selection--single
.select2-selection__rendered {
    color: #212529;
    font-size: 15px;
    line-height: 38px;
    padding-left: 0;
}

/* Arrow fix */
.select2-container--default .select2-selection--single
.select2-selection__arrow {
    height: 38px;
    right: 14px;
}

/* Focus state */
.select2-container--default.select2-container--focus
.select2-selection--single {
    border-color: #28c76f;
    box-shadow: 0 0 0 0.15rem rgba(40, 199, 111, 0.25);
}

/* ===== DROPDOWN FIX ===== */

.select2-dropdown {
    border-radius: 14px;
    border: 1px solid #ced4da;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
    overflow: hidden;
    margin-top: 6px;
}

/* Options */
.select2-results__option {
    padding: 12px 18px;
    font-size: 14px;
    color: #212529;               /* ensure readable default text */
    background-color: #ffffff;
}

/* ===== HOVER FIX (IMPORTANT) ===== */
.select2-results__option--highlighted {
    background-color: #e9f7ef !important;
    color: #14532d !important;    /* DARK GREEN â€” text will NOT disappear */
    font-weight: 500;
}

/* ===== SELECTED OPTION FIX ===== */
.select2-results__option--selected {
    background-color: #28c76f !important;
    color: #ffffff !important;    /* force white text */
    font-weight: 500;
}

/* ================================
   FIX: Keep FULL rounding when open
   ================================ */

/* Keep full pill shape even when open */
.select2-container--open
.select2-selection--single {
    border-radius: 999px !important;
}

/* Ensure arrow side stays rounded */
.select2-container--default
.select2-selection--single
.select2-selection__arrow {
    border-top-right-radius: 999px;
    border-bottom-right-radius: 999px;
}

/* Prevent visual clipping */
.select2-container--default
.select2-selection--single {
    overflow: hidden;
}



    </style>
    @php
        $checkoutPrice = $product->custom_price ?? $product->price ?? null;
        $checkoutCurrency = $product->currency_symbol ?? '₦';
        $checkoutFormTitle = $product->type === 'C' ? 'Task Details' : 'Beneficiary Details';
    @endphp
    <div class="page-content-wrapper">
        <div class="page-content-tab">
            <div class="container-fluid px-4 pb-4 portal-purchase-wrap">
                <div class="portal-checkout-page">
                    <div class="portal-checkout-toolbar">
                        <a href="{{ url('/users/products') }}" class="portal-checkout-back">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M19 12H5"/><path d="M12 19l-7-7 7-7"/></svg>
                            Back to marketplace
                        </a>
                        <div class="portal-checkout-steps" aria-label="Checkout progress">
                            <span class="portal-checkout-steps__item is-active">1. Details</span>
                            <span class="portal-checkout-steps__line"></span>
                            <span class="portal-checkout-steps__item">2. Payment</span>
                        </div>
                    </div>

                    <div class="row g-4 align-items-start portal-checkout-grid">
                        <div class="col-xl-8 col-lg-7">
                                    @if($product->type == 'A')
                                        <form id="frm_prodA_details" class="portal-purchase-form">
                                            @csrf
                                            <div class="portal-purchase-panel">
                                                <div class="portal-purchase-form__body">
                                                    <h3 class="portal-form-block-title">Personal information</h3>
                                                    <div class="row mt-0">
                                                        <input type="hidden" id="prodA_products_id" value="{{ $product->products_id }}" readonly disabled>
                                                        <div class="col-lg-4 col-md-4">
                                                            <div class="form-group mb-3">
                                                                <label class="form-label mb-2">First Name</label>
                                                                <input type="text" name="prodA_first_name" id="prodA_first_name" placeholder="Enter First Name" class="form-control letters-only" maxlength="80" autocomplete="given-name">
                                                                <span class="error_msg" id="error_prodA_first_name"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4 col-md-6">
                                                            <div class="form-group mb-3">
                                                                <label class="form-label mb-2">Surname</label>
                                                                <input type="text" name="prodA_surname" id="prodA_surname" placeholder="Enter Surname" class="form-control letters-only" maxlength="80" autocomplete="family-name">
                                                                <span class="error_msg" id="error_prodA_surname"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4 col-md-6">
                                                            <div class="form-group mb-3">
                                                                <label class="form-label mb-2">Gender</label>
                                                                <select class="form-select form-select-lg" aria-label=".form-select-lg example" name="prodA_gender" id="prodA_gender">  
                                                                    <option value="" disabled selected hidden>--Select--</option> 
                                                                    <option value="Male">Male</option>
                                                                    <option value="Female">Female</option>
                                                                </select>
                                                                <span class="error_msg" id="error_prodA_gender"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4 col-md-4">
                                                            <div class="form-group mb-3">
                                                                <label class="form-label mb-2">Date of Birth</label>
                                                                <input type="text" class="form-control dob" placeholder="DD-MM-YYYY" name="prodA_dob" id="prodA_dob">
                                                                <span class="error_msg" id="error_prodA_dob"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-8 col-md-8">
                                                            <div class="form-group mb-3">
                                                                <label class="form-label mb-2">Address</label>
                                                                <input type="text" class="form-control" placeholder="Enter Address" name="prodA_address" id="prodA_address">
                                                                <span class="error_msg" id="error_prodA_address"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4 col-md-4">
                                                            <div class="form-group mb-3">
                                                                <label class="form-label mb-2">Occupation</label>
                                                                <select class="form-select form-select-lg" aria-label=".form-select-lg example" name="prodA_occupations_id" id="prodA_occupations_id">  
                                                                    <option value="" disabled selected hidden>--Select--</option> 
                                                                    @foreach($occupations as $occupation)
                                                                    <option value="{{ $occupation->occupations_id }}">{{ $occupation->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                                <span class="error_msg" id="error_prodA_occupations_id"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4 col-md-4">
                                                            <div class="form-group mb-3">
                                                                <label class="form-label mb-2">Relationship</label>
                                                                <select class="form-select form-select-lg" aria-label=".form-select-lg example" name="prodA_relationships_id" id="prodA_relationships_id">  
                                                                    <option value="" disabled selected hidden>--Select--</option> 
                                                                    @foreach($relationships as $relationship)
                                                                    <option value="{{ $relationship->relationships_id }}">{{ $relationship->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                                <span class="error_msg" id="error_prodA_relationships_id"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4 col-md-4">
                                                            <div class="form-group mb-3">
                                                                <label class="form-label mb-2">Phone Number</label>
                                                                <input type="text" class="form-control digits-only" placeholder="Enter Phone Number" name="prodA_nin" id="prodA_nin" maxlength="11" inputmode="numeric" pattern="\d*" autocomplete="tel">
                                                                <span class="error_msg" id="error_prodA_nin"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-12 portal-form-section">
                                                            <p class="portal-form-section__title">Cover &amp; documents</p>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-lg-4 col-md-6">
                                                                <div class="form-group mb-3">
                                                                    <label class="form-label mb-2">Cover Duration</label>
                                                                    <select class="form-select form-select-lg cover_duration" aria-label=".form-select-lg example" name="prodA_cover_duration" id="prodA_cover_duration" data-product="A">  
                                                                        <option value="Monthly">Monthly</option>
                                                                        <option value="Yearly">Annual</option>
                                                                    </select>
                                                                    <span class="error_msg" id="error_prodA_cover_duration"></span>
                                                                </div>
                                                                <input type="hidden" class="cover_start_date" data-product="A" name="prodA_cover_start_date" id="prodA_cover_start_date">
                                                                <input type="hidden" class="cover_end_date" data-product="A" id="prodA_cover_end_date">
                                                            </div>
                                                            <!-- Optional document uploads -->
                                                            <div class="col-lg-8 col-md-6">
                                                                <div class="row doc-upload-grid">
                                                                    <div class="col-lg-6 col-md-6">
                                                                        <div class="form-group mb-3">
                                                                            <label class="form-label mb-2">1. Identity Information (Passport/ID)</label>
                                                                            <div class="control-group file-upload" id="file-upload-prodA-identity">
                                                                                <div class="image-box text-center mx-auto">
                                                                                    <img src="{{ asset('users/assets/images/icons/document-upload.png') }}" class="img-fluid" id="prodA_nin_document_preview" alt="">
                                                                                </div>
                                                                                <div class="controls">
                                                                                    <input type="file" accept="image/png, image/jpg, image/jpeg" name="prodA_nin_document" id="prodA_nin_document" hidden />
                                                                                    <span class="error_msg" id="error_prodA_nin_document"></span>
                                                                                    <textarea rows="10" cols="50" id="prodA_nin_document_string" readonly disabled hidden></textarea>
                                                                                </div>
                                                                            </div>
                                                                            <p class="doc-upload-hint">Optional — you can buy now and provide this later.</p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-6 col-md-6">
                                                                        <div class="form-group mb-3">
                                                                            <label class="form-label mb-2">2. Proof of Address</label>
                                                                            <div class="control-group file-upload" id="file-upload-prodA-address">
                                                                                <div class="image-box text-center mx-auto">
                                                                                    <img src="{{ asset('users/assets/images/icons/document-upload.png') }}" class="img-fluid" id="prodA_address_document_preview" alt="">
                                                                                </div>
                                                                                <div class="controls">
                                                                                    <input type="file" accept="image/png, image/jpg, image/jpeg" name="prodA_address_document" id="prodA_address_document" hidden />
                                                                                    <span class="error_msg" id="error_prodA_address_document"></span>
                                                                                    <textarea rows="10" cols="50" id="prodA_address_document_string" readonly disabled hidden></textarea>
                                                                                </div>
                                                                            </div>
                                                                            <p class="doc-upload-hint">Optional — you can buy now and provide this later.</p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>                                                   
                                                    </div>
                                                </div>
                                                <div class="portal-form-footer">
                                                    <p class="portal-form-footer__hint">Review your details before continuing to payment.</p>
                                                    <div class="portal-form-actions">
                                                        <a href="{{ url('/users/products') }}" class="btn btn-outline-primary">Cancel</a>
                                                        <button type="submit" id="btnBuyNowA" class="btn btn-primary">Continue to payment</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>  
                                    @endif

                                    @if($product->type == 'B')
                                        <form id="frm_prodB_details" class="portal-purchase-form">
                                            @csrf
                                            <div class="portal-purchase-panel">
                                                <div class="portal-purchase-form__body">
                                                    <h3 class="portal-form-block-title">Personal information</h3>
                                                    <div class="row mt-0">
                                                        <input type="hidden" id="prodB_products_id" value="{{ $product->products_id }}" readonly disabled>
                                                        <div class="col-lg-4 col-md-6">
                                                            <div class="form-group mb-3">
                                                                <label class="form-label mb-2">First Name</label>
                                                                <input type="text" name="prodB_first_name" id="prodB_first_name" placeholder="Enter First Name" class="form-control letters-only" maxlength="80" autocomplete="given-name">
                                                                <span class="error_msg" id="error_prodB_first_name"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4 col-md-6">
                                                            <div class="form-group mb-3">
                                                                <label class="form-label mb-2">Surname</label>
                                                                <input type="text" name="prodB_surname" id="prodB_surname" placeholder="Enter Surname" class="form-control letters-only" maxlength="80" autocomplete="family-name">
                                                                <span class="error_msg" id="error_prodB_surname"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4 col-md-6">
                                                            <div class="form-group mb-3">
                                                                <label class="form-label mb-2">Gender</label>
                                                                <select class="form-select form-select-lg" aria-label=".form-select-lg example" name="prodB_gender" id="prodB_gender">  
                                                                    <option value="" disabled selected hidden>--Select--</option> 
                                                                    <option value="Male">Male</option>
                                                                    <option value="Female">Female</option>
                                                                </select>
                                                                <span class="error_msg" id="error_prodB_gender"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4 col-md-4">
                                                            <div class="form-group mb-3">
                                                                <label class="form-label mb-2">Date of Birth</label>
                                                                <input type="text" class="form-control dob" placeholder="DD-MM-YYYY" name="prodB_dob" id="prodB_dob">
                                                                <span class="error_msg" id="error_prodB_dob"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-8 col-md-8">
                                                            <div class="form-group mb-3">
                                                                <label class="form-label mb-2">Address</label>
                                                                <input type="text" class="form-control" placeholder="Enter Address" name="prodB_address" id="prodB_address">
                                                                <span class="error_msg" id="error_prodB_address"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4 col-md-4">
                                                            <div class="form-group mb-3">
                                                                <label class="form-label mb-2">Occupation</label>
                                                                <select class="form-select form-select-lg" aria-label=".form-select-lg example" name="prodB_occupations_id" id="prodB_occupations_id">  
                                                                    <option value="" disabled selected hidden>--Select--</option> 
                                                                    @foreach($occupations as $occupation)
                                                                    <option value="{{ $occupation->occupations_id }}">{{ $occupation->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                                <span class="error_msg" id="error_prodB_occupations_id"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4 col-md-4">
                                                            <div class="form-group mb-3">
                                                                <label class="form-label mb-2">Relationship</label>
                                                                <select class="form-select form-select-lg" aria-label=".form-select-lg example" name="prodB_relationships_id" id="prodB_relationships_id">  
                                                                    <option value="" disabled selected hidden>--Select--</option> 
                                                                    @foreach($relationships as $relationship)
                                                                    <option value="{{ $relationship->relationships_id }}">{{ $relationship->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                                <span class="error_msg" id="error_prodB_relationships_id"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4 col-md-4">
                                                            <div class="form-group mb-3">
                                                                <label class="form-label mb-2">Phone Number</label>
                                                                <input type="text" class="form-control digits-only" placeholder="Enter Phone Number" name="prodB_nin" id="prodB_nin" maxlength="11" inputmode="numeric" pattern="\d*" autocomplete="tel">
                                                                <span class="error_msg" id="error_prodB_nin"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-12 portal-form-section">
                                                            <p class="portal-form-section__title">Cover &amp; documents</p>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-lg-4 col-md-6">
                                                                <div class="form-group mb-3">
                                                                    <label class="form-label mb-2">Cover Duration</label>
                                                                    <select class="form-select form-select-lg cover_duration" aria-label=".form-select-lg example" name="prodB_cover_duration" id="prodB_cover_duration" data-product="B">  
                                                                        <option value="Monthly">Monthly</option>
                                                                        <option value="Yearly">Annual</option>
                                                                    </select>
                                                                    <span class="error_msg" id="error_prodB_cover_duration"></span>
                                                                </div>
                                                                <input type="hidden" class="cover_start_date" data-product="B" name="prodB_cover_start_date" id="prodB_cover_start_date">
                                                                <input type="hidden" class="cover_end_date" data-product="B" id="prodB_cover_end_date">
                                                            </div>
                                                            <!-- Optional document uploads -->
                                                            <div class="col-lg-8 col-md-6">
                                                                <div class="row doc-upload-grid">
                                                                    <div class="col-lg-6 col-md-6">
                                                                        <div class="form-group mb-3">
                                                                            <label class="form-label mb-2">1. Identity Information (Passport/ID)</label>
                                                                            <div class="control-group file-upload" id="file-upload-prodB-identity">
                                                                                <div class="image-box text-center mx-auto">
                                                                                    <img src="{{ asset('users/assets/images/icons/document-upload.png') }}" class="img-fluid" id="prodB_nin_document_preview" alt="">
                                                                                </div>
                                                                                <div class="controls">
                                                                                    <input type="file" accept="image/png, image/jpg, image/jpeg" name="prodB_nin_document" id="prodB_nin_document" hidden />
                                                                                    <span class="error_msg" id="error_prodB_nin_document"></span>
                                                                                    <textarea rows="10" cols="50" id="prodB_nin_document_string" readonly disabled hidden></textarea>
                                                                                </div>
                                                                            </div>
                                                                            <p class="doc-upload-hint">Optional — you can buy now and provide this later.</p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-6 col-md-6">
                                                                        <div class="form-group mb-3">
                                                                            <label class="form-label mb-2">2. Proof of Address</label>
                                                                            <div class="control-group file-upload" id="file-upload-prodB-address">
                                                                                <div class="image-box text-center mx-auto">
                                                                                    <img src="{{ asset('users/assets/images/icons/document-upload.png') }}" class="img-fluid" id="prodB_address_document_preview" alt="">
                                                                                </div>
                                                                                <div class="controls">
                                                                                    <input type="file" accept="image/png, image/jpg, image/jpeg" name="prodB_address_document" id="prodB_address_document" hidden />
                                                                                    <span class="error_msg" id="error_prodB_address_document"></span>
                                                                                    <textarea rows="10" cols="50" id="prodB_address_document_string" readonly disabled hidden></textarea>
                                                                                </div>
                                                                            </div>
                                                                            <p class="doc-upload-hint">Optional — you can buy now and provide this later.</p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>                                                   
                                                    </div>
                                                </div>
                                                <div class="portal-form-footer">
                                                    <p class="portal-form-footer__hint">Review your details before continuing to payment.</p>
                                                    <div class="portal-form-actions">
                                                        <a href="{{ url('/users/products') }}" class="btn btn-outline-primary">Cancel</a>
                                                        <button type="submit" id="btnBuyNowB" class="btn btn-primary">Continue to payment</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form> 
                                    @endif

                                    @if($product->type == 'C')
                                        <form id="frm_prodC_details" class="portal-purchase-form">
                                            @csrf
                                            <div class="portal-purchase-panel">
                                                <div class="portal-purchase-form__body">
                                                    @php
                                                        $prod_valid = DB::table('products_purchases as pp')
                                                            ->join('products_purchases_tasks as ppt', 'pp.products_purchases_id', '=', 'ppt.products_purchases_id')
                                                            ->where('pp.users_customers_id', session('id'))
                                                            ->where('pp.product_type', $product->type)
                                                            ->whereColumn('ppt.delivery_requests_consumed', '<', 'ppt.delivery_request_limit')
                                                            ->select('pp.*', 'ppt.*')
                                                            ->first();

                                                        $limit = $prod_valid ? $prod_valid->delivery_request_limit : $product->delivery_request_limit;
                                                        $consumed = $prod_valid ? $prod_valid->delivery_requests_consumed : 0;
                                                    @endphp
                                                    <div class="portal-form-block-head">
                                                        <h3 class="portal-form-block-title mb-0">Task information</h3>
                                                        <div class="portal-usage-meter portal-usage-meter--inline">
                                                            <p class="portal-usage-meter__label">{{ $product->name }} remaining</p>
                                                            <div class="box-container">
                                                                @for ($i = 0; $i < $limit; $i++)
                                                                    <div class="box {{ $i < $consumed ? 'filled' : '' }}"></div>
                                                                @endfor
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row mt-0">
                                                        <input type="hidden" id="prodC_products_id" value="{{ $product->products_id }}" readonly disabled>
                                                        <input type="hidden" data-product="C" id="prodC_cover_start_date" value="" readonly disabled>
                                                        <input type="hidden" data-product="C" id="prodC_cover_end_date" value="" readonly disabled>
                                                        <div class="col-lg-4 col-md-4">
                                                            <div class="form-group mb-3">
                                                                <label class="form-label mb-2">Task Type</label>
                                                                <select class="form-select form-select-lg" aria-label=".form-select-lg example" name="prodC_tasks_types_id" id="prodC_tasks_types_id">  
                                                                    <option value="" disabled selected hidden>--Select--</option> 
                                                                    @foreach($tasks_types as $task_type)
                                                                    <option value="{{ $task_type->tasks_types_id }}">{{ $task_type->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                                <span class="error_msg" id="error_prodC_tasks_types_id"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4 col-md-4">
                                                            <div class="form-group mb-3">
                                                                <label class="form-label mb-2">Task Name</label>
                                                                <input type="text" class="form-control" placeholder="Enter Task Name" name="prodC_task" id="prodC_task">
                                                                <span class="error_msg" id="error_prodC_task"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4 col-md-4">
                                                            <div class="form-group mb-3">
                                                                <label class="form-label mb-2">Task Date</label>
                                                                <input type="text" class="form-control task_date" placeholder="DD-MM-YYYY" name="prodC_task_date" id="prodC_task_date">
                                                                <span class="error_msg" id="error_prodC_task_date"></span>
                                                            </div>
                                                        </div>
                                                        <!-- <div class="col-lg-6 col-md-6">
                                                            <div class="form-group mb-3">
                                                                <label class="form-label mb-2">Select Task Time</label>
                                                                <input type="text" class="form-control" placeholder="Select Task Time" name="prodC_task_time" id="prodC_task_time">
                                                                <span class="error_msg" id="error_prodC_task_time"></span>
                                                            </div>
                                                        </div> -->
                                                        <div class="col-lg-12 col-md-12">
                                                            <div class="form-group mb-3">
                                                                <label class="form-label mb-2">Description</label>
                                                                <textarea class="form-control" rows="2" placeholder="Type Description..." name="prodC_description" id="prodC_description" style="height:auto;"></textarea>
                                                                <span class="error_msg" id="error_prodC_description"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4 col-md-4">
                                                            <div class="form-group mb-3">
                                                                <label class="form-label mb-2">Contact Person Name</label>
                                                                <input type="text" class="form-control letters-only" placeholder="Enter Person Name" name="prodC_contact_person_name" id="prodC_contact_person_name" maxlength="80">
                                                                <span class="error_msg" id="error_prodC_contact_person_name"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4 col-md-4">
                                                            <div class="form-group mb-3">
                                                                <label class="form-label mb-2">Contact Person Phone No.</label>
                                                                <input type="text" class="form-control digits-only" placeholder="Enter Person Phone No." name="prodC_person_phone" id="prodC_person_phone" maxlength="15" inputmode="numeric" pattern="\d*">
                                                                <span class="error_msg" id="error_prodC_person_phone"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-12 col-md-12 pb-3">
                                                            <div class="form-check">
                                                                <input type="checkbox" class="form-check-input" id="prodC_acknowledged" name="prodC_acknowledged">
                                                                <label class="form-check-label" for="prodC_acknowledged">
                                                                    <div class="acknowledge_text">
                                                                        I acknowledge that all the data I have provided is validated and agree to use one of my<br>
                                                                        {{ $product->delivery_request_limit }} total <strong>{{ $product->name }}</strong> for this task.
                                                                    </div>
                                                                    <span class="error_msg" id="error_prodC_acknowledged"></span>
                                                                </label>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                                <div class="portal-form-footer">
                                                    <p class="portal-form-footer__hint">Confirm task details to proceed with your request.</p>
                                                    <div class="portal-form-actions">
                                                        <a href="{{ url('/users/products') }}" class="btn btn-outline-primary">Cancel</a>
                                                        <button type="submit" id="btnBuyNowC" class="btn btn-primary">Submit task</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    @endif
                        </div>

                        <div class="col-xl-4 col-lg-5">
                            <aside class="portal-checkout-summary">
                                <div class="portal-checkout-summary__head">
                                    <span class="portal-checkout-summary__label">Order summary</span>
                                    <h3 class="portal-checkout-summary__product">{{ $product->name }}</h3>
                                </div>
                                <ul class="portal-checkout-summary__list">
                                    <li>
                                        <span>Product type</span>
                                        <strong>Type {{ $product->type }}</strong>
                                    </li>
                                    <li>
                                        <span>Form step</span>
                                        <strong>{{ $checkoutFormTitle }}</strong>
                                    </li>
                                    <li>
                                        <span>Status</span>
                                        <strong>{{ $product->status ?? 'Active' }}</strong>
                                    </li>
                                </ul>
                                <div class="portal-checkout-summary__total">
                                    <span>Total</span>
                                    @if($checkoutPrice !== null && $checkoutPrice !== '')
                                        <strong>{{ $checkoutCurrency }}{{ number_format((float) $checkoutPrice, 2) }}</strong>
                                    @else
                                        <strong>—</strong>
                                    @endif
                                </div>
                                <p class="portal-checkout-summary__note">Complete the form on the left, then continue to payment.</p>
                            </aside>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script') 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.6.3/jquery-ui-timepicker-addon.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.6.3/jquery-ui-timepicker-addon.min.js"></script>
    <script>
        function yearsAgoDate(years) {
            var date = new Date();
            date.setFullYear(date.getFullYear() - years);
            return date;
        }

        function parseDob(value) {
            var parts = (value || '').split('-');
            if (parts.length !== 3) {
                return null;
            }

            return new Date(parseInt(parts[2], 10), parseInt(parts[1], 10) - 1, parseInt(parts[0], 10));
        }

        function ageFromDob(dob) {
            var today = new Date();
            var age = today.getFullYear() - dob.getFullYear();
            var monthDiff = today.getMonth() - dob.getMonth();
            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < dob.getDate())) {
                age--;
            }
            return age;
        }

        function calculateCoverEndDate(product) {
            var startDateVal = $('#prod' + product + '_cover_start_date').val();
            var duration = $('#prod' + product + '_cover_duration').val();

            if (!startDateVal || !duration) {
                $('#prod' + product + '_cover_end_date').val('');
                return;
            }

            // Parse YYYY-MM-DD (hidden field format)
            var parts = startDateVal.split('-');
            var year  = parseInt(parts[0], 10);
            var month = parseInt(parts[1], 10) - 1;
            var day   = parseInt(parts[2], 10);

            var startDate = new Date(year, month, day);
            var endDate = new Date(startDate.getTime());

            if (duration === "Monthly") {
                endDate.setMonth(endDate.getMonth() + 1);
            } else if (duration === "Yearly") {
                endDate.setFullYear(endDate.getFullYear() + 1);
            }

            endDate.setDate(endDate.getDate() - 1);

            // Format YYYY-MM-DD
            var yyyy = endDate.getFullYear();
            var mm = ("0" + (endDate.getMonth() + 1)).slice(-2);
            var dd = ("0" + endDate.getDate()).slice(-2);

            $('#prod' + product + '_cover_end_date').val(yyyy + "-" + mm + "-" + dd);
        }
        $(document).ready(function() {
            window.swapProductRequiresAgeLimit = $('#product_age_limited').val() === '1';

            if ($.validator && !$.validator.methods.productAgeLimit) {
                $.validator.addMethod('productAgeLimit', function(value) {
                    if (!window.swapProductRequiresAgeLimit || !value) {
                        return true;
                    }

                    var dob = parseDob(value);
                    if (!dob || isNaN(dob.getTime())) {
                        return false;
                    }

                    var age = ageFromDob(dob);
                    return age >= 18 && age <= 65;
                }, 'Age must be between 18 and 65 years.');
            }

            var dobPickerOptions = {
                dateFormat: "dd-mm-yy",
                maxDate: window.swapProductRequiresAgeLimit ? yearsAgoDate(18) : 0,
                minDate: window.swapProductRequiresAgeLimit ? yearsAgoDate(65) : null,
                changeMonth: true,
                changeYear: true,
                yearRange: window.swapProductRequiresAgeLimit ? "-65:-18" : "-100:+0",
                onSelect: function(dateText, inst) {
                    $(this).valid(); 
                }
            };

            // DOB fields (canâ€™t select future dates)
            $('.dob').datepicker(dobPickerOptions);

            if (window.swapProductRequiresAgeLimit) {
                $('#prodA_dob, #prodB_dob').each(function () {
                    if ($(this).length && $(this).rules) {
                        $(this).rules('add', {
                            productAgeLimit: true,
                            messages: {
                                productAgeLimit: 'Age must be between 18 and 65 years.'
                            }
                        });
                    }
                });
            }

            // On selecting start date
            $('.cover_start_date').datepicker({
                dateFormat: "dd-mm-yy",
                minDate: 0,
                changeMonth: true,
                changeYear: true,
                onSelect: function () {
                    var product = $(this).data('product');
                    calculateCoverEndDate(product);

                    // âœ… Hide validation error when user selects a date
                    var form = $(this).closest('form');
                    form.validate().element($(this)); // revalidate this input
                }
            });

            // On changing duration
            $('.cover_duration').on('change', function () {
                var product = $(this).data('product');
                calculateCoverEndDate(product);
            });

            $(".task_date").datepicker({
                dateFormat: "dd-mm-yy",
                minDate: 0,
                changeMonth: true,
                changeYear: true,
                onSelect: function(dateText, inst) {
                    $(this).valid(); 
                }
            });
            flatpickr("#prodC_task_time", {
                enableTime: true,
                noCalendar: true,
                dateFormat: "H:i",   // 24-hour format
                time_24hr: true,
                onOpen: function(selectedDates, dateStr, instance) {
                    // Get the task date
                    let taskDateStr = $("#prodC_task_date").val(); // assuming you have a date field
                    if (!taskDateStr) return;

                    let today = new Date();
                    let selectedDate = new Date(taskDateStr);

                    // If selected date is today, disable past times
                    if (selectedDate.toDateString() === today.toDateString()) {
                        let hours = today.getHours();
                        let minutes = today.getMinutes();

                        // Set minimum time to current time
                        instance.set("minTime", `${hours}:${minutes}`);
                    } else {
                        // For future dates, allow any time
                        instance.set("minTime", "00:00");
                    }
                }
            });

            $(document).on("change", "input[type='file'][id^='prod'][id$='_document']", function (event) {
                const input = event.target;
                const file = input.files[0];
                const $input = $(input);

                if (!file) return;

                const reader = new FileReader();
                const inputId = $input.attr('id'); // e.g. prodA_nin_document / prodA_address_document
                const match = inputId.match(/prod([A-Z])_(nin_document|address_document)/i);
                if (!match) {
                    return;
                }

                const productType = match[1].toUpperCase();
                const fieldKey = match[2];
                const previewImg = $(`#prod${productType}_${fieldKey}_preview`);
                const textArea = $(`#prod${productType}_${fieldKey}_string`);

                reader.onload = function (e) {
                    const fullBase64 = e.target.result;
                    const cleanBase64 = fullBase64.replace(/^data:image\/(png|jpg|jpeg);base64,/, "");

                    previewImg.attr("src", fullBase64);
                    textArea.val(cleanBase64);

                    const form = $input.closest("form");
                    if (form.length && form.data("validator")) {
                        form.validate().element($input);
                    }

                    $(`#error_${$input.attr("name")}`).empty();
                };

                reader.readAsDataURL(file);
            });

            // Block invalid characters while typing
            $(document).on('input', '.letters-only', function () {
                this.value = this.value.replace(/[^A-Za-z\s\-']/g, '');
            });
            $(document).on('input', '.digits-only', function () {
                this.value = this.value.replace(/\D/g, '');
            });
        });
        $(document).ready(function () {
            // Function to format date as YYYY-MM-DD
            function formatDate(date) {
                const yyyy = date.getFullYear();
                const mm = ('0' + (date.getMonth() + 1)).slice(-2);
                const dd = ('0' + date.getDate()).slice(-2);
                return `${yyyy}-${mm}-${dd}`;
            }

            // Get today's date
            const startDate = new Date();
            const todayFormatted = formatDate(startDate);

            // Set cover_start_date to today for A, B, C
            $('#prodA_cover_start_date').val(todayFormatted);
            $('#prodB_cover_start_date').val(todayFormatted);
            $('#prodC_cover_start_date').val(todayFormatted);

            // Calculate cover_end_date based on selected duration for A and B
            calculateCoverEndDate('A');
            calculateCoverEndDate('B');

            // For C, always yearly
            const endDateC = new Date(startDate);
            endDateC.setFullYear(endDateC.getFullYear() + 1);
            endDateC.setDate(endDateC.getDate() - 1);
            $('#prodC_cover_end_date').val(formatDate(endDateC));
        });
        /* --------------- submit productC form --------------- */
        // $(document).ready(function() {
        //     $('#prodC_acknowledged').on('click', function(e) {
        //         e.preventDefault(); // prevent checkbox default behavior
        //         var form = $('#frm_prodC_details');
        //         // Use jQuery Validation's submitHandler
        //         if (form.valid()) { 
        //             // Form is valid
        //             $(this).prop('checked', true); // show checkbox checked
        //             form.trigger('submit'); // trigger submission in a way jQuery Validation handles
        //         } else {
        //             // Form invalid
        //             $(this).prop('checked', false); // keep unchecked
        //         }
        //     });
        // });
        /* --------------- submit productC form --------------- */
    </script>
@endsection