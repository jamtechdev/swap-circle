@extends('layout.users.master')

@section('page_title', 'Claims')
@section('page_subtitle', 'Submit and track product claims')

@section('content')
    @php
        $totalProducts = count($purchased_products);
        $eligibleCount = $eligiblePurchases->count();
        $submittedCount = collect($purchased_products)->filter(fn ($item) => !empty($item->existing_claim))->count();
        $waitingCount = max(0, $totalProducts - $eligibleCount - $submittedCount);
    @endphp

    <div class="page-content-wrapper">
        <div class="page-content-tab">
            <div class="container-fluid px-4 pb-4">
                <div class="portal-claims-panel">
                    @if($totalProducts > 0)
                        <div class="portal-claims-hero">
                            <div class="portal-claims-intro">
                                <div class="portal-claims-intro__icon" aria-hidden="true">
                                    <svg viewBox="0 0 24 24" fill="none">
                                        <path d="M12 3 4 6v6c0 5 3.4 8.4 8 9 4.6-.6 8-4 8-9V6l-8-3Z" stroke="currentColor" stroke-width="1.75" stroke-linejoin="round"/>
                                        <path d="m9 12 2 2 4-4" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                                <div>
                                    <span class="portal-claims-intro__label">Product protection</span>
                                    <h1 class="portal-claims-intro__title">Submit a claim</h1>
                                    <p class="portal-claims-intro__text">
                                        @if((int) $claimWaitingDays === 0)
                                            Claims are available immediately after a successful purchase. Upload supporting documents if you have them.
                                        @else
                                            Claims open {{ $claimWaitingDays }} days after a successful purchase. Upload supporting documents if you have them.
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <div class="portal-claims-stats" aria-label="Claims summary">
                                <div class="portal-claims-stat">
                                    <span class="portal-claims-stat__value">{{ $totalProducts }}</span>
                                    <span class="portal-claims-stat__label">Products</span>
                                </div>
                                <div class="portal-claims-stat portal-claims-stat--ready">
                                    <span class="portal-claims-stat__value">{{ $eligibleCount }}</span>
                                    <span class="portal-claims-stat__label">Eligible</span>
                                </div>
                                <div class="portal-claims-stat portal-claims-stat--wait">
                                    <span class="portal-claims-stat__value">{{ $waitingCount }}</span>
                                    <span class="portal-claims-stat__label">Waiting</span>
                                </div>
                                <div class="portal-claims-stat">
                                    <span class="portal-claims-stat__value">{{ $submittedCount }}</span>
                                    <span class="portal-claims-stat__label">Submitted</span>
                                </div>
                            </div>
                        </div>

                        @if($eligiblePurchases->isEmpty())
                        <div class="portal-claims-banner portal-claims-banner--wait" role="status">
                            <span class="portal-claims-banner__icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none">
                                    <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.75"/>
                                    <path d="M12 8v5M12 16h.01" stroke="currentColor" stroke-width="1.75" stroke-linecap="round"/>
                                </svg>
                            </span>
                            <div>
                                <p class="portal-claims-banner__title">No products eligible yet</p>
                                <p class="portal-claims-banner__text">Check the waiting dates below, or contact support if you need help sooner.</p>
                            </div>
                        </div>
                        @else
                        <div class="portal-claims-banner portal-claims-banner--ready" role="status">
                            <span class="portal-claims-banner__icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none">
                                    <path d="M20 6 9 17l-5-5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </span>
                            <div>
                                <p class="portal-claims-banner__title">{{ $eligibleCount }} {{ \Illuminate\Support\Str::plural('product', $eligibleCount) }} ready to claim</p>
                                <p class="portal-claims-banner__text">Choose an eligible product in the form below to continue.</p>
                            </div>
                        </div>
                        @endif

                        <section class="portal-claims-list" aria-labelledby="claims-products-heading">
                            <div class="portal-claims-list__head">
                                <h2 id="claims-products-heading" class="portal-claims-list__title">Your products</h2>
                                <p class="portal-claims-list__sub">Status for each paid purchase</p>
                            </div>

                            <div class="portal-claims-product-grid">
                                @foreach($purchased_products as $item)
                                    @php
                                        $isSubmitted = !empty($item->existing_claim);
                                        $isEligible = !$isSubmitted && ($item->claim_eligibility['eligible'] ?? false);
                                        $statusClass = $isSubmitted ? 'is-submitted' : ($isEligible ? 'is-eligible' : 'is-waiting');
                                        $statusLabel = $isSubmitted
                                            ? 'Claim submitted'
                                            : ($isEligible ? 'Eligible now' : ($item->claim_eligibility['reason'] ?? 'Not eligible yet'));
                                        $eligibleAt = $item->claim_eligibility['eligible_at'] ?? null;
                                        $daysRemaining = $item->claim_eligibility['days_remaining'] ?? null;
                                    @endphp
                                    <article class="portal-claims-product {{ $statusClass }}">
                                        <div class="portal-claims-product__top">
                                            <div class="portal-claims-product__icon" aria-hidden="true">
                                                <svg viewBox="0 0 24 24" fill="none">
                                                    <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z" stroke="currentColor" stroke-width="1.75"/>
                                                    <path d="M3.3 7.7 12 12.5l8.7-4.8M12 22.5V12.5" stroke="currentColor" stroke-width="1.75"/>
                                                </svg>
                                            </div>
                                            <div class="portal-claims-product__meta">
                                                <h3 class="portal-claims-product__name">{{ $item->product->name ?? 'Product' }}</h3>
                                                <p class="portal-claims-product__date">Purchased {{ \Carbon\Carbon::parse($item->date_added)->format('d M Y') }}</p>
                                            </div>
                                        </div>
                                        <div class="portal-claims-product__status">
                                            <span class="portal-claims-pill">{{ $statusLabel }}</span>
                                            @if(!$isSubmitted && !$isEligible && $eligibleAt)
                                                <span class="portal-claims-product__hint">
                                                    @if($daysRemaining !== null && $daysRemaining > 0)
                                                        {{ $daysRemaining }} {{ \Illuminate\Support\Str::plural('day', $daysRemaining) }} remaining
                                                    @else
                                                        Opens {{ \Carbon\Carbon::parse($eligibleAt)->format('d M Y') }}
                                                    @endif
                                                </span>
                                            @endif
                                        </div>
                                    </article>
                                @endforeach
                            </div>
                        </section>

                        <div class="portal-claims-alert d-none" id="claim_success_banner" role="status">
                            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M20 6 9 17l-5-5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <span>Your claim was submitted successfully. We’ll review it and get back to you.</span>
                        </div>

                        <div class="portal-claims-card">
                            <div class="portal-claims-card__head">
                                <div>
                                    <h2 class="portal-claims-card__title">Claim details</h2>
                                    <p class="portal-claims-card__sub">Tell us what happened and attach documents if available.</p>
                                </div>
                            </div>

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
                            <h2 class="portal-claims-empty__title">No paid purchases yet</h2>
                            <p class="portal-claims-empty__text">Claims are available only after a successful product payment. Buy a product, complete Stripe checkout, then return here.</p>
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
