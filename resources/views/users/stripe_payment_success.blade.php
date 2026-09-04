@extends('layout.users.master')

@section('page_title', 'Payment Confirmed')
@section('page_subtitle', 'Your order is complete — download your invoice below')

@section('content')
<div class="page-content-wrapper">
    <div class="page-content-tab">
        <div class="container-fluid px-4 pb-4">
            <div class="row justify-content-center">
                <div class="col-lg-7 col-xl-6">
                    <div class="portal-payment-success">
                        <div class="portal-payment-success__hero">
                            <div class="portal-payment-success__badge" aria-hidden="true">
                                <span class="portal-payment-success__ring"></span>
                                <span class="portal-payment-success__icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="20 6 9 17 4 12"/>
                                    </svg>
                                </span>
                            </div>

                            <p class="portal-payment-success__eyebrow">Order confirmed</p>
                            <h1 class="portal-payment-success__title">Payment Successful!</h1>
                            <p class="portal-payment-success__text">
                                Thank you for your purchase. Your payment has been processed and your order is confirmed.
                            </p>

                            @if(session('success'))
                                <div class="portal-payment-success__alert" role="status">{{ session('success') }}</div>
                            @endif

                            <div class="portal-payment-success__actions">
                                <a href="{{ $portalHomeUrl ?? url('/users/dashboard') }}" class="portal-payment-success__btn portal-payment-success__btn--primary">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                                        <polyline points="9 22 9 12 15 12 15 22"/>
                                    </svg>
                                    Go to Home
                                </a>
                                <a href="{{ url('/users/products') }}" class="portal-payment-success__btn portal-payment-success__btn--secondary">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <circle cx="9" cy="21" r="1"/>
                                        <circle cx="20" cy="21" r="1"/>
                                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
                                    </svg>
                                    Continue Shopping
                                </a>
                            </div>
                        </div>

                        @if(isset($purchase_id))
                        <div class="portal-payment-success__invoice">
                            <div class="portal-payment-success__invoice-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                    <polyline points="14 2 14 8 20 8"/>
                                    <line x1="16" y1="13" x2="8" y2="13"/>
                                    <line x1="16" y1="17" x2="8" y2="17"/>
                                    <polyline points="10 9 9 9 8 9"/>
                                </svg>
                            </div>
                            <div class="portal-payment-success__invoice-copy">
                                <h2 class="portal-payment-success__invoice-title">Your Invoice is Ready</h2>
                                <p class="portal-payment-success__invoice-text">Download your official payment invoice as a PDF for your records.</p>
                            </div>
                            <a href="{{ url('/users/download-invoice/'.$purchase_id) }}" class="portal-payment-success__download">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                    <polyline points="7 10 12 15 17 10"/>
                                    <line x1="12" y1="15" x2="12" y2="3"/>
                                </svg>
                                Download Invoice
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
