@extends('layout.users.master')
@section('content') 
    <style>
        .image-wrapper {
            width: 185px;          
            height: 120px;        
            overflow: hidden;      /* hide overflow */
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;   /* rounded corners */
        }
        .image-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;     /* cover container without distortion */
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
    line-height: 52px;
    padding-left: 0;
}

/* Arrow fix */
.select2-container--default .select2-selection--single
.select2-selection__arrow {
    height: 40px;
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
                            <div class="container mt-0">
                                @if(count($purchased_products) > 0)
                                <div class="card shadow-sm border-0 px-4" style="background: #EAF5EF;">
                                    <h4 class="text-center mb-4 fw-bold">Submit a Claim</h4>
                                    <form id="frm_claim_product">
                                        @csrf
                                        <div class="row g-3">
                                            <!-- Product -->
                                            <div class="col-md-3">
                                                <button class="btn btn-primary w-100 mt-3" type="button" style="cursor:default;">Product</button>
                                            </div>
                                            <div class="col-md-9 d-flex align-items-end">
                                                <select class="form-select" name="products_purchases_id" id="products_purchases_id">
                                                    <option value="" selected disabled>Choose Product</option>
                                                    @foreach($purchased_products as $key => $item)
                                                        <option value="{{ $item->products_purchases_id }}">
                                                            {{ $item->product->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <span class="error_msg" id="error_products_purchases_id"></span>
                                            </div>

                                            <!-- Date of Incident -->
                                            <div class="col-md-3">
                                                <button class="btn btn-primary w-100" type="button" style="cursor:default;">Date</button>
                                            </div>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control date_field" placeholder="Choose date of incident" name="claim_date" id="claim_date">
                                                <span class="error_msg" id="error_claim_date"></span>
                                            </div>

                                            <!-- Notes -->
                                            <div class="col-md-3">
                                                <button class="btn btn-primary w-100" type="button" style="cursor:default;">Notes</button>
                                            </div>
                                            <div class="col-md-9">
                                                <textarea class="form-control" name="claim_notes" id="claim_notes" placeholder="Notes to the claim" rows="4" style="background: #F7FDF9;"></textarea>
                                                <span class="error_msg" id="error_claim_notes"></span>
                                            </div>

                                            <!-- Upload Row (3 Upload Buttons in One Line) -->
                                            <div class="col-md-3">
                                                <button class="btn btn-primary w-100" type="button" style="cursor:default;">Upload</button>
                                            </div>
                                            <div class="col-md-9 d-flex gap-4 flex-wrap">
                                                <!-- Upload 1 -->
                                                <div class="col-md-3">
                                                    <div class="control-group file-upload" id="file-upload1">
                                                        <div class="image-box text-center mx-auto">
                                                            <img src="{{ asset('users/assets/images/icons/document-upload.png') }}" class="img-fluid" id="claim_image1_preview" alt="">
                                                        </div>
                                                        <div class="controls">
                                                            <input type="file" accept="image/png, image/jpg, image/jpeg" name="claim_image1" id="claim_image1" hidden />
                                                            <span class="error_msg" id="error_claim_image1"></span>
                                                            <textarea id="claim_image1_string" hidden></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Upload 2 -->
                                                <div class="col-md-3">
                                                    <div class="control-group file-upload" id="file-upload2">
                                                        <div class="image-box text-center mx-auto">
                                                            <img src="{{ asset('users/assets/images/icons/document-upload.png') }}" class="img-fluid" id="claim_image2_preview" alt="">
                                                        </div>
                                                        <div class="controls">
                                                            <input type="file" accept="image/png, image/jpg, image/jpeg" name="claim_image2" id="claim_image2" hidden />
                                                            <span class="error_msg" id="error_claim_image2"></span>
                                                            <textarea id="claim_image2_string" hidden></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Upload 3 -->
                                                <div class="col-md-3">
                                                    <div class="control-group file-upload" id="file-upload3">
                                                        <div class="image-box text-center mx-auto">
                                                            <img src="{{ asset('users/assets/images/icons/document-upload.png') }}" class="img-fluid" id="claim_image3_preview" alt="">
                                                        </div>
                                                        <div class="controls">
                                                            <input type="file" accept="image/png, image/jpg, image/jpeg" name="claim_image3" id="claim_image3" hidden />
                                                            <span class="error_msg" id="error_claim_image3"></span>
                                                            <textarea id="claim_image3_string" hidden></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Acknowledgement -->
                                            <!-- <div class="col-md-3">
                                                <button class="btn btn-primary w-100" type="button" style="cursor:default;">Agreement</button>
                                            </div> -->
                                            <div class="col-md-9">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input p-2" id="acknowledged" name="acknowledged">
                                                    <label class="form-check-label" for="acknowledged">
                                                        I acknowledge that all the data I have provided is authentic and to be held liable if found otherwise.
                                                    </label>
                                                    <span class="error_msg" id="error_acknowledged"></span>
                                                </div>
                                            </div>

                                            <div class="col-md-12 text-center mt-4">
                                                <button type="submit" class="btn btn-primary px-5 py-2">Submit Claim</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                @else
                                <div class="d-flex justify-content-center align-items-center" style="height: 70vh;">
                                    <div class="card shadow-lg border-0 text-center p-5" style="background: #f9fdfb; max-width: 500px;">
                                        <h4 class="fw-bold text-muted mb-2">No Purchases Yet</h4>
                                        <p class="text-secondary mb-4">
                                            You havenâ€™t bought any products yet.<br>
                                            Once you make a purchase, you can submit a claim here.
                                        </p>
                                    </div>
                                </div>
                                @endif
                            </div>
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
            $("#claim_date").datepicker({
                dateFormat: "dd-mm-yy",
                changeMonth: true,
                changeYear: true,
                onSelect: function(dateText, inst) {
                    $(this).valid(); 
                }
            });
        });
        $(document).ready(function () {
            $(document).on("change", "input[type='file'][id^='claim_image']", function (event) {
                const input = $(this);
                const file = input[0].files[0];

                if (!file) return;

                const reader = new FileReader();

                const inputId = input.attr('id'); // e.g. claim_image1
                const previewImg = $(`#${inputId}_preview`);
                const textArea = $(`#${inputId}_string`);

                reader.onload = function (e) {
                    const fullBase64 = e.target.result;
                    const cleanBase64 = fullBase64.replace(/^data:image\/(png|jpg|jpeg);base64,/, "");

                    if (previewImg.length) previewImg.attr("src", fullBase64);
                    if (textArea.length) textArea.val(cleanBase64);
                };

                reader.readAsDataURL(file);

                // âœ… Hide the validation error for this specific input
                const form = input.closest("form");
                if (form.length && form.data("validator")) {
                    // Mark the field as valid
                    form.validate().element(input);
                    
                    // If you use a custom error placement
                    $(`#error_${inputId}`).html('');
                }
            });
        });
    </script>
@endsection