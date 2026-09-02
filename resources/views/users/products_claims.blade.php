@extends('layout.users.master')

@section('page_title', 'Claims')
@section('page_subtitle', 'Submit and track product claims')

@section('content')
    <div class="page-content-wrapper">
        <div class="page-content-tab">
            <div class="container-fluid px-4 pb-4">
                <div class="portal-claims-panel">
                    @if(count($purchased_products) > 0)
                        <div class="portal-claims-intro">
                            <div class="portal-claims-intro__icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none">
                                    <path d="M9 12h6M12 9v6M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" stroke="currentColor" stroke-width="1.75" stroke-linecap="round"/>
                                </svg>
                            </div>
                            <div>
                                <span class="portal-claims-intro__label">Product protection</span>
                                <h1 class="portal-claims-intro__title">Submit a claim</h1>
                                <p class="portal-claims-intro__text">Claims open {{ $claimWaitingDays }} days after a successful purchase. Upload supporting documents if you have them.</p>
                            </div>
                        </div>

                        @if($eligiblePurchases->isEmpty())
                        <div class="alert alert-warning border-0 rounded-3 mb-4" role="status">
                            None of your products are eligible for claims yet. Check the dates below or contact support if you need help.
                        </div>
                        @endif

                        <div class="card mb-4 border-0 shadow-sm">
                            <div class="card-body">
                                <h2 class="h6 mb-3 text-forest">Your products</h2>
                                <div class="table-responsive">
                                    <table class="table table-sm mb-0 align-middle">
                                        <thead>
                                            <tr>
                                                <th>Product</th>
                                                <th>Purchased</th>
                                                <th>Claim status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($purchased_products as $item)
                                            <tr>
                                                <td>{{ $item->product->name ?? 'Product' }}</td>
                                                <td>{{ \Carbon\Carbon::parse($item->date_added)->format('d M Y') }}</td>
                                                <td>
                                                    @if($item->existing_claim ?? false)
                                                        <span class="badge bg-secondary">Claim submitted</span>
                                                    @elseif($item->claim_eligibility['eligible'] ?? false)
                                                        <span class="badge bg-success">Eligible now</span>
                                                    @else
                                                        <span class="badge bg-warning text-dark">{{ $item->claim_eligibility['reason'] ?? 'Not eligible yet' }}</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="portal-claims-alert d-none" id="claim_success_banner" role="status">
                            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M20 6 9 17l-5-5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <span>Your claim was submitted successfully. We’ll review it and get back to you.</span>
                        </div>

                        <div class="portal-claims-card">
                            <form id="frm_claim_product" class="portal-claims-form portal-purchase-form" @if($eligiblePurchases->isEmpty()) data-claims-disabled="1" @endif>
                                @csrf

                                <div class="portal-claims-form__grid">
                                    <div class="portal-claims-field">
                                        <label class="portal-claims-field__label" for="products_purchases_id">Product</label>
                                        <select class="form-select portal-select-search" name="products_purchases_id" id="products_purchases_id" data-placeholder="Choose product">
                                            <option value="" selected disabled>Choose product</option>
                                            @foreach($purchased_products as $item)
                                                @php
                                                    $canClaim = ($item->claim_eligibility['eligible'] ?? false) && !($item->existing_claim ?? false);
                                                @endphp
                                                <option value="{{ $item->products_purchases_id }}" @if(!$canClaim) disabled @endif>
                                                    {{ $item->product->name }}@if(!$canClaim) — {{ $item->existing_claim ?? false ? 'Already claimed' : ($item->claim_eligibility['reason'] ?? 'Not eligible') }}@endif
                                                </option>
                                            @endforeach
                                        </select>
                                        <span class="error_msg" id="error_products_purchases_id"></span>
                                    </div>

                                    <div class="portal-claims-field">
                                        <label class="portal-claims-field__label" for="claim_date">Date of incident</label>
                                        <input type="text" class="form-control date_field portal-claims-date" placeholder="Choose date of incident" name="claim_date" id="claim_date" autocomplete="off">
                                        <span class="error_msg" id="error_claim_date"></span>
                                    </div>

                                    <div class="portal-claims-field portal-claims-field--full">
                                        <label class="portal-claims-field__label" for="claim_notes">Notes</label>
                                        <textarea class="form-control" name="claim_notes" id="claim_notes" placeholder="Describe what happened and any relevant details…" rows="4"></textarea>
                                        <span class="error_msg" id="error_claim_notes"></span>
                                    </div>
                                </div>

                                <div class="portal-claims-upload-section">
                                    <div class="portal-claims-upload-section__head">
                                        <h2 class="portal-claims-upload-section__title">Supporting documents</h2>
                                        <p class="portal-claims-upload-section__hint">Optional — you can submit now and provide these later.</p>
                                    </div>

                                    <div class="portal-claims-upload-grid">
                                        <div class="portal-claims-upload-card">
                                            <label class="portal-claims-upload-card__label">Identity information</label>
                                            <p class="portal-claims-upload-card__sub">Passport or government ID</p>
                                            <div class="control-group file-upload" id="file-upload1">
                                                <div class="image-box text-center mx-auto portal-claims-upload-box">
                                                    <img src="{{ asset('users/assets/images/icons/document-upload.png') }}" class="img-fluid" id="claim_image1_preview" alt="">
                                                </div>
                                                <div class="controls">
                                                    <input type="file" accept="image/png, image/jpg, image/jpeg" name="claim_image1" id="claim_image1" hidden>
                                                    <span class="error_msg" id="error_claim_image1"></span>
                                                    <textarea id="claim_image1_string" hidden></textarea>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="portal-claims-upload-card">
                                            <label class="portal-claims-upload-card__label">Proof of address</label>
                                            <p class="portal-claims-upload-card__sub">Utility bill or bank statement</p>
                                            <div class="control-group file-upload" id="file-upload2">
                                                <div class="image-box text-center mx-auto portal-claims-upload-box">
                                                    <img src="{{ asset('users/assets/images/icons/document-upload.png') }}" class="img-fluid" id="claim_image2_preview" alt="">
                                                </div>
                                                <div class="controls">
                                                    <input type="file" accept="image/png, image/jpg, image/jpeg" name="claim_image2" id="claim_image2" hidden>
                                                    <span class="error_msg" id="error_claim_image2"></span>
                                                    <textarea id="claim_image2_string" hidden></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="portal-claims-ack">
                                    <label class="portal-claims-ack__label">
                                        <input type="checkbox" class="portal-claims-ack__input" id="acknowledged" name="acknowledged">
                                        <span class="portal-claims-ack__box" aria-hidden="true"></span>
                                        <span class="portal-claims-ack__text">I acknowledge that all the data I have provided is authentic and I may be held liable if found otherwise.</span>
                                    </label>
                                    <span class="error_msg" id="error_acknowledged"></span>
                                </div>

                                <div class="portal-claims-actions">
                                    <button type="submit" class="portal-claims-submit" id="claim_submit_btn" @if($eligiblePurchases->isEmpty()) disabled @endif>Submit claim</button>
                                </div>
                            </form>
                        </div>
                    @else
                        <div class="portal-claims-empty">
                            <div class="portal-claims-empty__icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none">
                                    <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z" stroke="currentColor" stroke-width="1.75" stroke-linejoin="round"/>
                                    <path d="M3 6h18M16 10a4 4 0 0 1-8 0" stroke="currentColor" stroke-width="1.75" stroke-linecap="round"/>
                                </svg>
                            </div>
                            <h2 class="portal-claims-empty__title">No purchases yet</h2>
                            <p class="portal-claims-empty__text">You haven't bought any products yet. Once you make a purchase, you can submit a claim here.</p>
                            <a href="{{ url('/users/products') }}" class="portal-claims-empty__cta">Browse products</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function () {
            portalInitSelect2($('#products_purchases_id'));

            $('#claim_date').datepicker({
                dateFormat: 'dd-mm-yy',
                changeMonth: true,
                changeYear: true,
                onSelect: function () {
                    $(this).valid();
                }
            });

            $(document).on('change', "input[type='file'][id^='claim_image']", function () {
                var input = $(this);
                var file = input[0].files[0];
                if (!file) return;

                var reader = new FileReader();
                var inputId = input.attr('id');
                var previewImg = $('#' + inputId + '_preview');
                var textArea = $('#' + inputId + '_string');

                reader.onload = function (e) {
                    var fullBase64 = e.target.result;
                    var cleanBase64 = fullBase64.replace(/^data:image\/(png|jpg|jpeg);base64,/, '');
                    if (previewImg.length) {
                        previewImg.attr('src', fullBase64).addClass('image');
                        previewImg.closest('.portal-claims-upload-box').addClass('has-file');
                    }
                    if (textArea.length) textArea.val(cleanBase64);
                };

                reader.readAsDataURL(file);

                var form = input.closest('form');
                if (form.length && form.data('validator')) {
                    form.validate().element(input);
                    $('#error_' + inputId).html('');
                }
            });
        });
    </script>
@endsection
