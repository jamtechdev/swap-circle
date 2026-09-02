@extends('layout.users.master')

@section('page_title', 'Product Details')
@section('page_subtitle', 'View and manage product information')

@section('content')
    <div class="page-content-wrapper">
        <div class="page-content-tab">
            <div class="container-fluid px-4 pb-4">
                @php
                    $displayPrice = $product->custom_price ?? $product->price ?? null;
                    $currencySymbol = $product->currency_symbol ?? '₦';
                    $latestPurchase = DB::table('products_purchases')
                        ->where('products_id', $product->products_id)
                        ->where('users_customers_id', session('id'))
                        ->where('payment_status', 'Successful')
                        ->orderBy('products_purchases_id', 'DESC')
                        ->first();
                @endphp

                <div class="portal-product-hero mb-4">
                    <div class="portal-product-hero__content">
                        <span class="portal-product-hero__eyebrow">Product overview</span>
                        <h2 class="portal-product-hero__title">{{ $product->name }}</h2>
                        <p class="portal-product-hero__meta">Type {{ $product->type }} · ID #{{ $product->products_id }}</p>
                    </div>
                    <span class="portal-badge {{ $product->status == 'Active' ? 'portal-badge-active' : 'portal-badge-type' }}">
                        {{ $product->status }}
                    </span>
                </div>

                <div class="row g-4">
                    <div class="col-lg-8">
                        <div class="card portal-product-card">
                            <div class="card-body">
                                <div class="portal-card-header">
                                    <h4>Product Information</h4>
                                </div>

                                <div class="row g-3 mb-4">
                                    <div class="col-sm-6">
                                        <div class="portal-detail-tile">
                                            <span class="portal-detail-tile__label">Product Name</span>
                                            <span class="portal-detail-tile__value">{{ $product->name }}</span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="portal-detail-tile">
                                            <span class="portal-detail-tile__label">Price</span>
                                            @if($displayPrice !== null && $displayPrice !== '')
                                                <span class="portal-detail-tile__value portal-detail-tile__value--price">{{ $currencySymbol }}{{ number_format((float) $displayPrice, 2) }}</span>
                                            @else
                                                <span class="portal-detail-tile__value text-muted">Price not set</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="portal-detail-tile">
                                            <span class="portal-detail-tile__label">Product Type</span>
                                            <span class="portal-detail-tile__value">{{ $product->type }}</span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="portal-detail-tile">
                                            <span class="portal-detail-tile__label">Status</span>
                                            <span class="portal-detail-tile__value">
                                                <span class="portal-badge {{ $product->status == 'Active' ? 'portal-badge-active' : 'portal-badge-type' }}">{{ $product->status }}</span>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                @if($product->description)
                                <div class="portal-description">
                                    <h5 class="portal-description__title">Description</h5>
                                    <div class="portal-description__body">{!! nl2br(e($product->description)) !!}</div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="card portal-product-card portal-product-actions">
                            <div class="card-body">
                                <div class="portal-card-header">
                                    <h4>Quick Actions</h4>
                                </div>

                                <div class="portal-action-list">
                                    <a href="{{ url('/users/product/'.$product->type.'/'.$product->products_id) }}" class="portal-action-btn portal-action-btn--primary">
                                        <span class="portal-action-btn__icon" aria-hidden="true">
                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                                        </span>
                                        <span class="portal-action-btn__content">
                                            <span class="portal-action-btn__label">Purchase Product</span>
                                            <span class="portal-action-btn__hint">Proceed to checkout</span>
                                        </span>
                                        <span class="portal-action-btn__arrow" aria-hidden="true">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18l6-6-6-6"/></svg>
                                        </span>
                                    </a>

                                    <a href="{{ url('/users/products') }}" class="portal-action-btn portal-action-btn--secondary">
                                        <span class="portal-action-btn__icon" aria-hidden="true">
                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5"/><path d="M12 19l-7-7 7-7"/></svg>
                                        </span>
                                        <span class="portal-action-btn__content">
                                            <span class="portal-action-btn__label">Back to Products</span>
                                            <span class="portal-action-btn__hint">Browse marketplace</span>
                                        </span>
                                        <span class="portal-action-btn__arrow" aria-hidden="true">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18l6-6-6-6"/></svg>
                                        </span>
                                    </a>

                                    <a href="{{ url('/users/dashboard') }}" class="portal-action-btn portal-action-btn--ghost">
                                        <span class="portal-action-btn__icon" aria-hidden="true">
                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                                        </span>
                                        <span class="portal-action-btn__content">
                                            <span class="portal-action-btn__label">Dashboard</span>
                                            <span class="portal-action-btn__hint">Return to overview</span>
                                        </span>
                                        <span class="portal-action-btn__arrow" aria-hidden="true">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18l6-6-6-6"/></svg>
                                        </span>
                                    </a>
                                </div>

                                @if($latestPurchase)
                                <div class="portal-invoice-box mt-4">
                                    <p class="portal-invoice-box__text">Your invoice is ready to download</p>
                                    <a href="{{ url('/users/download-invoice/'.$latestPurchase->products_purchases_id) }}" class="portal-action-btn portal-action-btn--invoice w-100">
                                        <span class="portal-action-btn__icon" aria-hidden="true">
                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                                        </span>
                                        <span class="portal-action-btn__content">
                                            <span class="portal-action-btn__label">Download Invoice</span>
                                        </span>
                                    </a>
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
