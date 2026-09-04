@extends('layout.users.master')

@section('page_title', 'Products')
@section('page_subtitle', 'Browse and purchase community products')

@section('content')
    <div class="page-content-wrapper">
        <div class="page-content-tab">
            <div class="container-fluid px-4 pb-4">
                <div class="portal-marketplace-panel">
                    <div class="portal-marketplace-head">
                        <div class="portal-marketplace-head__text">
                            <span class="portal-marketplace-head__label">Marketplace</span>
                            <p class="portal-marketplace-head__lead">Health, protection & service products for diaspora families</p>
                        </div>
                        <span class="portal-marketplace-head__count">{{ count($products) }} products</span>
                    </div>

                <div class="offers-wrapper">
                    @if(count($products) === 0)
                        <div class="portal-empty-state">
                            <p>No products are available right now. Please check back soon.</p>
                            @if($hasPurchases ?? false)
                            <a href="{{ $portalHomeUrl ?? url('/users/dashboard') }}" class="btn btn-outline-primary">Back to Home</a>
                            @endif
                        </div>
                    @else
                        <div class="row g-3 portal-marketplace-grid">
                            @foreach($products as $item)
                                @php
                                    $productInfo = trim((string) ($item->product_information ?? ''));
                                    $productInfoModalId = 'productInfoModal' . $item->products_id;
                                    $defaultProductImage = asset('images/upload.svg');
                                    $image = trim((string) ($item->image ?? ''));
                                    $imageHost = $image ? parse_url($image, PHP_URL_HOST) : null;
                                    $isDeadLocalImage = in_array($imageHost, ['127.0.0.1', 'localhost'], true);
                                    $productImageUrl = $image && !$isDeadLocalImage
                                        ? (\Illuminate\Support\Str::startsWith($image, ['http://', 'https://']) ? $image : asset($image))
                                        : $defaultProductImage;
                                    $displayPrice = $item->custom_price ?? $item->price ?? null;
                                    $currencySymbol = $item->currency_symbol ?? '₦';
                                    $isPlaceholder = ($productImageUrl === $defaultProductImage);
                                    $productInitial = strtoupper(mb_substr(trim($item->name), 0, 1));
                                @endphp
                                <div class="col-sm-6 col-lg-4 col-xxl-3">
                                    <article class="portal-marketplace-card">
                                        <div class="portal-marketplace-card__media{{ $isPlaceholder ? ' portal-marketplace-card__media--placeholder' : '' }}">
                                            @if(!$isPlaceholder)
                                                <img
                                                    src="{{ $productImageUrl }}"
                                                    alt="{{ $item->name }}"
                                                    class="portal-marketplace-card__image"
                                                    loading="lazy"
                                                    onerror="this.classList.add('d-none');this.nextElementSibling.classList.remove('d-none');this.closest('.portal-marketplace-card__media').classList.add('portal-marketplace-card__media--placeholder');"
                                                >
                                            @endif
                                            <span class="portal-marketplace-card__initial{{ $isPlaceholder ? '' : ' d-none' }}" aria-hidden="true">{{ $productInitial }}</span>
                                            <span class="portal-marketplace-card__type">Type {{ $item->type }}</span>
                                        </div>

                                        <div class="portal-marketplace-card__body">
                                            <a href="{{ url('users/product/view/' . $item->products_id) }}" class="portal-marketplace-card__title">{{ $item->name }}</a>

                                            @if($item->description)
                                                <p class="portal-marketplace-card__desc">{{ \Illuminate\Support\Str::limit(strip_tags($item->description), 85) }}</p>
                                            @endif

                                            <div class="portal-marketplace-card__footer">
                                                <div class="portal-marketplace-card__meta">
                                                    @if($displayPrice !== null && $displayPrice !== '')
                                                        <span class="portal-marketplace-card__price"><span class="portal-marketplace-card__currency">{{ $currencySymbol }}</span>{{ number_format((float) $displayPrice, 2) }}</span>
                                                    @else
                                                        <span class="portal-marketplace-card__price portal-marketplace-card__price--muted">Price on request</span>
                                                    @endif
                                                    <span class="portal-marketplace-card__status{{ ($item->status ?? 'Active') !== 'Active' ? ' portal-marketplace-card__status--inactive' : '' }}">
                                                        <span class="portal-marketplace-card__status-dot"></span>{{ $item->status ?? 'Active' }}
                                                    </span>
                                                </div>
                                                <div class="portal-marketplace-card__actions">
                                                    <a href="{{ url('users/product/' . $item->type . '/' . $item->products_id) }}" class="portal-marketplace-btn portal-marketplace-btn--primary">
                                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                                                        Buy now
                                                    </a>
                                                    <button type="button" class="portal-marketplace-btn portal-marketplace-btn--secondary" data-bs-toggle="modal" data-bs-target="#{{ $productInfoModalId }}">
                                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
                                                        Details
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </article>

                                    <div class="modal fade portal-product-modal" id="{{ $productInfoModalId }}" tabindex="-1" aria-labelledby="{{ $productInfoModalId }}Label" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
                                            <div class="modal-content portal-product-modal__content-shell">
                                                <div class="modal-header portal-product-modal__header">
                                                    <div class="portal-product-modal__heading">
                                                        <h5 class="modal-title" id="{{ $productInfoModalId }}Label">{{ $item->name }}</h5>
                                                        <p class="portal-product-modal__subtitle mb-0">Product information &amp; details</p>
                                                    </div>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body portal-product-modal__body">
                                                    <div class="portal-product-modal__layout">
                                                        <div class="portal-product-modal__media{{ $isPlaceholder ? ' portal-product-modal__media--placeholder' : '' }}">
                                                            @if(!$isPlaceholder)
                                                                <img src="{{ $productImageUrl }}" alt="{{ $item->name }}" onerror="this.onerror=null;this.src='{{ $defaultProductImage }}';this.parentElement.classList.add('portal-product-modal__media--placeholder');">
                                                            @else
                                                                <span class="portal-product-modal__media-initial" aria-hidden="true">{{ $productInitial }}</span>
                                                            @endif
                                                        </div>
                                                        <div class="portal-product-modal__main">
                                                            <div class="portal-product-modal__meta">
                                                                <span class="portal-product-modal__chip">Type {{ $item->type }}</span>
                                                                @if($displayPrice !== null && $displayPrice !== '')
                                                                    <span class="portal-product-modal__price">{{ $currencySymbol }}{{ number_format((float) $displayPrice, 2) }}@if(in_array($item->type, ['A', 'B'], true))<small>/month</small>@endif</span>
                                                                @endif
                                                                <span class="portal-product-modal__chip portal-product-modal__chip--status">{{ $item->status ?? 'Active' }}</span>
                                                            </div>

                                                            @if($productInfo !== '')
                                                                <div class="portal-product-modal__content">{!! $productInfo !!}</div>
                                                            @elseif($item->description)
                                                                <div class="portal-product-modal__content portal-product-modal__content--summary">
                                                                    <p class="portal-product-modal__summary-label">Summary</p>
                                                                    <p class="mb-0">{{ \Illuminate\Support\Str::limit(strip_tags((string) $item->description), 320) }}</p>
                                                                </div>
                                                            @else
                                                                <div class="portal-product-modal__empty">
                                                                    <p class="mb-0">No detailed product information has been added yet.</p>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer portal-product-modal__footer">
                                                    <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Close</button>
                                                    <a href="{{ url('users/product/' . $item->type . '/' . $item->products_id) }}" class="btn btn-primary">Buy now</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
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
        $(document).ready(function() {
            $('.dob').datepicker({
                dateFormat: "yy-mm-dd",
                maxDate: 0,
                changeMonth: true,
                changeYear: true,
                yearRange: "-100:+0"
            });

            $('.cover_start_date').datepicker({
                dateFormat: "yy-mm-dd",
                minDate: 0,
                changeMonth: true,
                changeYear: true,
                onSelect: function(dateText, inst) {
                    var product = $(this).data('product');
                    var startDate = $(this).datepicker('getDate');

                    if (startDate) {
                        var endDate = new Date(startDate);
                        endDate.setFullYear(endDate.getFullYear() + 1);
                        endDate.setDate(endDate.getDate() - 1);

                        var yyyy = endDate.getFullYear();
                        var mm = ("0" + (endDate.getMonth() + 1)).slice(-2);
                        var dd = ("0" + endDate.getDate()).slice(-2);

                        $('#prod' + product + '_cover_end_date').val(yyyy + "-" + mm + "-" + dd);
                    }
                }
            });

            $(".task_date").datepicker({
                dateFormat: "yy-mm-dd",
                minDate: 0,
                changeMonth: true,
                changeYear: true
            });
            flatpickr("#prodC_task_time", {
                enableTime: true,
                noCalendar: true,
                dateFormat: "H:i",
                time_24hr: true,
                onOpen: function(selectedDates, dateStr, instance) {
                    let taskDateStr = $("#prodC_task_date").val();
                    if (!taskDateStr) return;

                    let today = new Date();
                    let selectedDate = new Date(taskDateStr);

                    if (selectedDate.toDateString() === today.toDateString()) {
                        let hours = today.getHours();
                        let minutes = today.getMinutes();
                        instance.set("minTime", `${hours}:${minutes}`);
                    } else {
                        instance.set("minTime", "00:00");
                    }
                }
            });
        });
        $(document).ready(function () {
            $(document).on("change", "input[type='file'][id^='prod'][id$='_identity_document']", function (event) {
                const input = event.target;
                const file = input.files[0];

                if (!file) return;

                const reader = new FileReader();
                const inputId = $(input).attr('id');
                const productType = inputId.match(/prod([A-Z])_/i)[1].toUpperCase();
                const previewImg = $(`#prod${productType}_identity_document_preview`);
                const textArea = $(`#prod${productType}_identity_document_string`);

                reader.onload = function (e) {
                    const fullBase64 = e.target.result;
                    const cleanBase64 = fullBase64.replace(/^data:image\/(png|jpg|jpeg);base64,/, "");

                    if (previewImg.length) {
                        previewImg.attr("src", fullBase64);
                    }

                    if (textArea.length) {
                        textArea.val(cleanBase64);
                    }
                };

                reader.readAsDataURL(file);
            });
        });
        $(document).ready(function () {
            function formatDate(date) {
                const yyyy = date.getFullYear();
                const mm = ('0' + (date.getMonth() + 1)).slice(-2);
                const dd = ('0' + date.getDate()).slice(-2);
                return `${yyyy}-${mm}-${dd}`;
            }

            const startDate = new Date();
            const endDate = new Date(startDate);
            endDate.setFullYear(endDate.getFullYear() + 1);
            endDate.setDate(endDate.getDate() - 1);

            $('#prodC_cover_start_date').val(formatDate(startDate));
            $('#prodC_cover_end_date').val(formatDate(endDate));
        });
    </script>
@endsection
