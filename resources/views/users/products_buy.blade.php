@extends('layout.users.master')
@section('content') 
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
    height: 52px;
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
    line-height: 52px;
    padding-left: 0;
}

/* Arrow fix */
.select2-container--default .select2-selection--single
.select2-selection__arrow {
    height: 52px;
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
    <div class="page-content-wrapper">
        <div class="page-content-tab">
            <div class="container-fluid px-4 pb-4">
                <!-- <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                    <h3 class="fw-bold sub-heading text-black">Marketplace</h3>
                </div> -->

                <div class="offers-wrapper">
                    <div class="wallet-tabs mt-1">
                        <div class="tab-content" id="pills-tabContent">
                            <div class="row">
                                <div class="col-md-12 col-xl-12">
                                    @if($product->type == 'A')
                                        <form id="frm_prodA_details">
                                            @csrf
                                            <div class="card border-0 mb-3">
                                                <div class="card-body p-4">
                                                    <div class="d-flex align-items-center justify-content-left pb-0 mb-4 flex-wrap">
                                                        <span class="btn btn-success px-3 py-2 ">
                                                            Insert Beneficiary Details
                                                        </span>
                                                    </div>
                                                    <div class="row mt-0">
                                                        <input type="hidden" id="prodA_products_id" value="{{ $product->products_id }}" readonly disabled>
                                                        <div class="col-lg-4 col-md-6">
                                                            <div class="form-group mb-4">
                                                                <label class="form-label mb-3">First Name</label>
                                                                <input type="text" name="prodA_first_name" id="prodA_first_name" placeholder="Enter First Name" class="form-control">
                                                                <span class="error_msg" id="error_prodA_first_name"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4 col-md-6">
                                                            <div class="form-group mb-4">
                                                                <label class="form-label mb-3">Surname</label>
                                                                <input type="text" name="prodA_surname" id="prodA_surname" placeholder="Enter Surname" class="form-control">
                                                                <span class="error_msg" id="error_prodA_surname"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4 col-md-6">
                                                            <div class="form-group mb-4">
                                                                <label class="form-label mb-3">Gender</label>
                                                                <select class="form-select form-select-lg" aria-label=".form-select-lg example" name="prodA_gender" id="prodA_gender">  
                                                                    <option value="" disabled selected hidden>--Select--</option> 
                                                                    <option value="Male">Male</option>
                                                                    <option value="Female">Female</option>
                                                                </select>
                                                                <span class="error_msg" id="error_prodA_gender"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4 col-md-4">
                                                            <div class="form-group mb-4">
                                                                <label class="form-label mb-3">Date of Birth</label>
                                                                <input type="text" class="form-control dob" placeholder="DD-MM-YYYY" name="prodA_dob" id="prodA_dob">
                                                                <span class="error_msg" id="error_prodA_dob"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-8 col-md-8">
                                                            <div class="form-group mb-4">
                                                                <label class="form-label mb-3">Address</label>
                                                                <input type="text" class="form-control" placeholder="Enter Address" name="prodA_address" id="prodA_address">
                                                                <span class="error_msg" id="error_prodA_address"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4 col-md-4">
                                                            <div class="form-group mb-4">
                                                                <label class="form-label mb-3">Occupation</label>
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
                                                            <div class="form-group mb-4">
                                                                <label class="form-label mb-3">Relationship</label>
                                                                <select class="form-select form-select-lg" aria-label=".form-select-lg example" name="prodA_relationships_id" id="prodA_relationships_id">  
                                                                    <option value="" disabled selected hidden>--Select--</option> 
                                                                    @foreach($relationships as $relationships)
                                                                    <option value="{{ $relationships->relationships_id }}">{{ $relationships->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                                <span class="error_msg" id="error_prodA_relationships_id"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4 col-md-4">
                                                            <div class="form-group mb-4">
                                                                <label class="form-label mb-3">Phone Number</label>
                                                                <input type="text" class="form-control" placeholder="Enter Phone Number" name="prodA_nin" id="prodA_nin" maxlength="11" pattern="\d*">
                                                                <span class="error_msg" id="error_prodA_nin"></span>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <!-- Left side: Upload ID -->
                                                            <div class="col-lg-8 col-md-6">
                                                                <div class="row">
                                                                    <div class="col-lg-6 col-md-6">
                                                                        <div class="form-group mb-4">
                                                                            <label class="form-label mb-3">Cover Duration</label>
                                                                            <select class="form-select form-select-lg cover_duration" aria-label=".form-select-lg example" name="prodA_cover_duration" id="prodA_cover_duration" data-product="A">  
                                                                                <option value="Monthly">Monthly</option>
                                                                                <option value="Yearly">Yearly</option>
                                                                            </select>
                                                                            <span class="error_msg" id="error_prodA_cover_duration"></span>
                                                                        </div>
                                                                    </div>
                                                                    <input type="hidden" class="cover_start_date" data-product="A" name="prodA_cover_start_date" id="prodA_cover_start_date">
                                                                    <input type="hidden" class="cover_end_date" data-product="A" id="prodA_cover_end_date">
                                                                </div>
                                                            </div>
                                                            <!-- Right side: 4 input fields (2x2 grid) -->
                                                            <div class="col-lg-4 col-md-6">
                                                                <div class="form-group mb-4">
                                                                    <label class="form-label mb-3">Upload Proof of Identity</label>
                                                                    <div class="control-group file-upload" id="file-upload1">
                                                                        <div class="image-box text-center mx-auto">
                                                                            <img src="{{ asset('users/assets/images/icons/document-upload.png') }}" class="img-fluid" id="prodA_nin_preview" alt="">
                                                                        </div>
                                                                        <div class="controls">
                                                                            <input type="file" accept="image/png, image/jpg, image/jpeg" name="prodA_nin_document" id="prodA_nin_document" hidden />
                                                                            <span class="error_msg" id="error_prodA_nin_document"></span>
                                                                            <textarea rows="10" cols="50" id="prodA_nin_document_string" readonly disabled hidden></textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>                                                   
                                                    </div>
                                                </div>  
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-3 ms-auto text-end" style="margin-right: -25px !important;">
                                                    <div class="mt-10">
                                                       <!--  <button type="submit" class="btn btn-login btn-primary w-auto">Buy Now</button> -->
                                                       <button type="submit" id="btnBuyNowA" class="btn btn-login btn-primary w-auto"> Buy Now </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>  
                                    @endif

                                    @if($product->type == 'B')
                                        <form id="frm_prodB_details">
                                            @csrf
                                            <div class="card border-0 mb-3">
                                                <div class="card-body p-4">
                                                    <div class="d-flex align-items-center justify-content-left pb-0 mb-4 flex-wrap">
                                                        <span class="btn btn-success px-3 py-2 ">
                                                            Insert Beneficiary Details
                                                        </span>
                                                    </div>
                                                    <div class="row mt-0">
                                                        <input type="hidden" id="prodB_products_id" value="{{ $product->products_id }}" readonly disabled>
                                                        <div class="col-lg-4 col-md-6">
                                                            <div class="form-group mb-4">
                                                                <label class="form-label mb-3">First Name</label>
                                                                <input type="text" name="prodB_first_name" id="prodB_first_name" placeholder="Enter First Name" class="form-control">
                                                                <span class="error_msg" id="error_prodB_first_name"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4 col-md-6">
                                                            <div class="form-group mb-4">
                                                                <label class="form-label mb-3">Surname</label>
                                                                <input type="text" name="prodB_surname" id="prodB_surname" placeholder="Enter Surname" class="form-control">
                                                                <span class="error_msg" id="error_prodB_surname"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4 col-md-6">
                                                            <div class="form-group mb-4">
                                                                <label class="form-label mb-3">Gender</label>
                                                                <select class="form-select form-select-lg" aria-label=".form-select-lg example" name="prodB_gender" id="prodB_gender">  
                                                                    <option value="" disabled selected hidden>--Select--</option> 
                                                                    <option value="Male">Male</option>
                                                                    <option value="Female">Female</option>
                                                                </select>
                                                                <span class="error_msg" id="error_prodB_gender"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4 col-md-4">
                                                            <div class="form-group mb-4">
                                                                <label class="form-label mb-3">Date of Birth</label>
                                                                <input type="text" class="form-control dob" placeholder="DD-MM-YYYY" name="prodB_dob" id="prodB_dob">
                                                                <span class="error_msg" id="error_prodB_dob"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-8 col-md-8">
                                                            <div class="form-group mb-4">
                                                                <label class="form-label mb-3">Address</label>
                                                                <input type="text" class="form-control" placeholder="Enter Address" name="prodB_address" id="prodB_address">
                                                                <span class="error_msg" id="error_prodB_address"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4 col-md-4">
                                                            <div class="form-group mb-4">
                                                                <label class="form-label mb-3">Occupation</label>
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
                                                            <div class="form-group mb-4">
                                                                <label class="form-label mb-3">Relationship</label>
                                                                <select class="form-select form-select-lg" aria-label=".form-select-lg example" name="prodB_relationships_id" id="prodB_relationships_id">  
                                                                    <option value="" disabled selected hidden>--Select--</option> 
                                                                    @foreach($relationships as $relationships)
                                                                    <option value="{{ $relationships->relationships_id }}">{{ $relationships->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                                <span class="error_msg" id="error_prodB_relationships_id"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4 col-md-4">
                                                            <div class="form-group mb-4">
                                                                <label class="form-label mb-3">Phone Number</label>
                                                                <input type="text" class="form-control" placeholder="Enter Phone Number" name="prodB_nin" id="prodB_nin" maxlength="11" pattern="\d*">
                                                                <span class="error_msg" id="error_prodB_nin"></span>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <!-- Left side: Upload ID -->
                                                            <div class="col-lg-8 col-md-6">
                                                                <div class="row">
                                                                    <div class="col-lg-6 col-md-6">
                                                                        <div class="form-group mb-4">
                                                                            <label class="form-label mb-3">Cover Duration</label>
                                                                            <select class="form-select form-select-lg cover_duration" aria-label=".form-select-lg example" name="prodB_cover_duration" id="prodB_cover_duration" data-product="B">  
                                                                                <option value="Monthly">Monthly</option>
                                                                                <option value="Yearly">Yearly</option>
                                                                            </select>
                                                                            <span class="error_msg" id="prodB_cover_duration"></span>
                                                                        </div>
                                                                    </div>
                                                                    <input type="hidden" class="cover_start_date" data-product="B" name="prodB_cover_start_date" id="prodB_cover_start_date">
                                                                    <input type="hidden" class="cover_end_date" data-product="B" id="prodB_cover_end_date">
                                                                </div>
                                                            </div>
                                                            <!-- Right side: 4 input fields (2x2 grid) -->
                                                            <div class="col-lg-4 col-md-6">
                                                                <div class="form-group mb-4">
                                                                    <label class="form-label mb-3">Upload Proof of Identity</label>
                                                                    <div class="control-group file-upload" id="file-upload1">
                                                                        <div class="image-box text-center mx-auto">
                                                                            <img src="{{ asset('users/assets/images/icons/document-upload.png') }}" class="img-fluid" id="prodB_nin_document_preview" alt="">
                                                                        </div>
                                                                        <div class="controls">
                                                                            <input type="file" accept="image/png, image/jpg, image/jpeg" name="prodB_nin_document" id="prodB_nin_document" hidden />
                                                                            <span class="error_msg" id="error_prodB_nin_document"></span>
                                                                            <textarea rows="10" cols="50" id="prodB_nin_document_string" readonly disabled hidden></textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>                                                   
                                                    </div>
                                                </div>  
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-3 ms-auto text-end" style="margin-right: -25px !important;">
                                                    <div class="mt-10">
                                                        <!-- <button type="submit" class="btn btn-login btn-primary w-auto">Buy Now</button> -->
                                                         <button type="submit" id="btnBuyNowB" class="btn btn-login btn-primary w-auto"> Buy Now </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form> 
                                    @endif

                                    @if($product->type == 'C')
                                        <form id="frm_prodC_details">
                                            @csrf
                                            <div class="card border-0 mb-3">
                                                <div class="card-body p-4">
                                                    <div class="d-flex align-items-between justify-content-between pb-0 mb-4 flex-wrap" style="padding-right: 40px;">
                                                        <div class="d-flex align-items-center justify-content-left pb-0 mb-4 flex-wrap">
                                                            <span class="btn btn-success px-3 py-2 ">
                                                                Insert Beneficiary Details
                                                            </span>
                                                        </div>  
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
                                                         <div class="d-flex flex-column align-items-end">
    <!-- Text above boxes -->
    <p class="mb-1 fw-bold text-muted w-100 text-start" style="font-size: 14px;">
        {{ $product->name }}'s Remaining
    </p>

    <!-- Boxes below -->
    <div class="box-container d-flex justify-content-end gap-1">
        @for ($i = 0; $i < $limit; $i++)
            <div class="box {{ $i < $consumed ? 'filled' : '' }}"></div>
        @endfor
    </div>
</div>

                                                        <!-- <div class="box-container">
                                                            @for ($i = 0; $i < $limit; $i++)
                                                                <div class="box {{ $i < $consumed ? 'filled' : '' }}"></div>
                                                            @endfor
                                                        </div> -->
                                                    </div>
                                                    <div class="row mt-0">
                                                        <input type="hidden" id="prodC_products_id" value="{{ $product->products_id }}" readonly disabled>
                                                        <input type="hidden" data-product="C" id="prodC_cover_start_date" value="" readonly disabled>
                                                        <input type="hidden" data-product="C" id="prodC_cover_end_date" value="" readonly disabled>
                                                        <div class="col-lg-4 col-md-4">
                                                            <div class="form-group mb-4">
                                                                <label class="form-label mb-3">Task Type</label>
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
                                                            <div class="form-group mb-4">
                                                                <label class="form-label mb-3">Task Name</label>
                                                                <input type="text" class="form-control" placeholder="Enter Task Name" name="prodC_task" id="prodC_task">
                                                                <span class="error_msg" id="error_prodC_task"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4 col-md-4">
                                                            <div class="form-group mb-4">
                                                                <label class="form-label mb-3">Task Date</label>
                                                                <input type="text" class="form-control task_date" placeholder="DD-MM-YYYY" name="prodC_task_date" id="prodC_task_date">
                                                                <span class="error_msg" id="error_prodC_task_date"></span>
                                                            </div>
                                                        </div>
                                                        <!-- <div class="col-lg-6 col-md-6">
                                                            <div class="form-group mb-4">
                                                                <label class="form-label mb-3">Select Task Time</label>
                                                                <input type="text" class="form-control" placeholder="Select Task Time" name="prodC_task_time" id="prodC_task_time">
                                                                <span class="error_msg" id="error_prodC_task_time"></span>
                                                            </div>
                                                        </div> -->
                                                        <div class="col-lg-12 col-md-12">
                                                            <div class="form-group mb-4">
                                                                <label class="form-label mb-3">Description</label>
                                                                <textarea class="form-control" rows="2" placeholder="Type Description..." name="prodC_description" id="prodC_description" style="height:auto;"></textarea>
                                                                <span class="error_msg" id="error_prodC_description"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4 col-md-4">
                                                            <div class="form-group mb-4">
                                                                <label class="form-label mb-3">Contact Person Name</label>
                                                                <input type="text" class="form-control" placeholder="Enter Person Name" name="prodC_contact_person_name" id="prodC_contact_person_name">
                                                                <span class="error_msg" id="error_prodC_contact_person_name"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4 col-md-4">
                                                            <div class="form-group mb-4">
                                                                <label class="form-label mb-3">Contact Person Phone No.</label>
                                                                <input type="text" class="form-control" placeholder="Enter Person Phone No." name="prodC_person_phone" id="prodC_person_phone">
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
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-3 ms-auto text-end" style="margin-right: -25px !important;">
                                                    <div class="mt-10">
                                                      <!--<button type="submit" class="btn btn-login btn-primary w-auto">Submit</button> -->
                                                        <button type="submit" id="btnBuyNowC" class="btn btn-login btn-primary w-auto"> Buy Now </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    @endif
                                </div>
                            </div>
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
            // DOB fields (canâ€™t select future dates)
            $('.dob').datepicker({
                dateFormat: "dd-mm-yy", 
                maxDate: 0,
                changeMonth: true,
                changeYear: true,
                yearRange: "-100:+0",
                onSelect: function(dateText, inst) {
                    $(this).valid(); 
                }
            });

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

            $(document).on("change", "input[type='file'][id^='prod'][id$='_nin_document']", function (event) {
                const input = event.target;
                const file = input.files[0];
                const $input = $(input);

                if (!file) return;

                const reader = new FileReader();

                // Extract product type dynamically (A or B)
                const inputId = $input.attr('id'); // e.g. "prodA_identity_document"
                const productType = inputId.match(/prod([A-Z])_/i)[1].toUpperCase(); // => A or B

                // Get corresponding elements
                const previewImg = $(`#prod${productType}_nin_document_preview`);
                const textArea = $(`#prod${productType}_nin_document_string`);

                reader.onload = function (e) {
                    const fullBase64 = e.target.result;

                    // âœ… Remove "data:image/...;base64," part
                    const cleanBase64 = fullBase64.replace(/^data:image\/(png|jpg|jpeg);base64,/, "");

                    // Show preview image
                    previewImg.attr("src", fullBase64);

                    // Store clean Base64 in textarea
                    textArea.val(cleanBase64);

                    // âœ… Revalidate BOTH: file input and NIN input
                    const form = $input.closest("form");
                    if (form.length && form.data("validator")) {
                        form.validate().element($input); // Recheck this field
                        form.validate().element(form.find(`[name*="_nin"]`)); // Recheck paired NIN field
                    }

                    // âœ… Also remove manual error text if jQuery validate placed it in a span
                    $(`#error_${$input.attr("name")}`).empty();
                };

                reader.readAsDataURL(file);
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