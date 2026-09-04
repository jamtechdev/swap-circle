@extends('layout.users.master')

@section('page_title', 'Payment Confirmed')
@section('page_subtitle', 'Your order is complete — download your invoice below')

@section('content')
{{-- Critical styles kept in-page so icons/layout survive even if Vite assets are stale --}}
<style>
.portal-payment-success {
    background: #fff;
    border: 1px solid rgba(26, 71, 42, 0.08);
    border-radius: 24px;
    box-shadow: 0 20px 50px rgba(15, 46, 28, 0.07);
    overflow: hidden;
    position: relative;
}
.portal-payment-success::before {
    content: '';
    position: absolute;
    inset: 0 0 auto 0;
    height: 140px;
    background: linear-gradient(180deg, rgba(238, 246, 200, 0.65) 0%, transparent 100%);
    pointer-events: none;
}
.portal-payment-success__hero {
    position: relative;
    z-index: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    padding: 2.5rem 1.75rem 1.75rem;
}
.portal-payment-success__badge {
    width: 88px;
    height: 88px;
    margin-bottom: 1.35rem;
    position: relative;
    flex-shrink: 0;
}
.portal-payment-success__ring {
    position: absolute;
    inset: 0;
    border-radius: 50%;
    background: rgba(200, 230, 53, 0.28);
}
.portal-payment-success__icon {
    position: absolute;
    inset: 10px;
    border-radius: 50%;
    background: linear-gradient(145deg, #1a472a 0%, #245c38 100%);
    color: #c8e635;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 10px 28px rgba(26, 71, 42, 0.28);
}
.portal-payment-success__icon svg,
.portal-payment-success__btn svg,
.portal-payment-success__invoice-icon svg,
.portal-payment-success__download svg {
    width: 18px !important;
    height: 18px !important;
    max-width: 18px !important;
    max-height: 18px !important;
    display: block;
    flex-shrink: 0;
}
.portal-payment-success__icon svg {
    width: 32px !important;
    height: 32px !important;
    max-width: 32px !important;
    max-height: 32px !important;
}
.portal-payment-success__invoice-icon svg {
    width: 24px !important;
    height: 24px !important;
    max-width: 24px !important;
    max-height: 24px !important;
}
.portal-payment-success__eyebrow {
    margin: 0 0 0.5rem;
    font-size: 0.6875rem;
    font-weight: 700;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: #64748b;
}
.portal-payment-success__title {
    margin: 0 0 0.75rem;
    font-family: 'DM Sans', sans-serif;
    font-size: clamp(1.5rem, 3vw, 1.875rem);
    font-weight: 800;
    line-height: 1.2;
    color: #1a472a;
}
.portal-payment-success__text {
    max-width: 380px;
    margin: 0 0 1.5rem;
    font-size: 0.9375rem;
    line-height: 1.65;
    color: #64748b;
}
.portal-payment-success__alert {
    width: 100%;
    max-width: 420px;
    margin: 0 0 1.25rem;
    padding: 0.75rem 1rem;
    border-radius: 12px;
    background: #eef6c8;
    border: 1px solid rgba(200, 230, 53, 0.45);
    color: #1a472a;
    font-size: 0.875rem;
    font-weight: 600;
}
.portal-payment-success__actions {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 0.75rem;
    width: 100%;
}
.portal-payment-success__btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    min-height: 48px;
    padding: 0.75rem 1.35rem;
    border-radius: 14px;
    font-size: 0.875rem;
    font-weight: 700;
    text-decoration: none !important;
}
.portal-payment-success__btn--primary {
    background: linear-gradient(135deg, #c8e635 0%, #b8d42a 100%);
    color: #0f2e1c !important;
    box-shadow: 0 6px 18px rgba(200, 230, 53, 0.35);
}
.portal-payment-success__btn--secondary {
    background: #fff;
    color: #1a472a !important;
    border: 1px solid rgba(26, 71, 42, 0.18);
}
.portal-payment-success__invoice {
    position: relative;
    z-index: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    gap: 0.875rem;
    margin: 0 1.5rem 1.5rem;
    padding: 1.5rem 1.25rem 1.35rem;
    background: linear-gradient(180deg, #f8faf6 0%, #eef6c8 100%);
    border: 1px solid rgba(200, 230, 53, 0.4);
    border-radius: 18px;
}
.portal-payment-success__invoice-icon {
    width: 52px;
    height: 52px;
    border-radius: 14px;
    background: #1a472a;
    color: #c8e635;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.portal-payment-success__invoice-title {
    margin: 0 0 0.35rem;
    font-family: 'DM Sans', sans-serif;
    font-size: 1.0625rem;
    font-weight: 700;
    color: #1a472a;
}
.portal-payment-success__invoice-text {
    margin: 0;
    font-size: 0.8125rem;
    line-height: 1.55;
    color: #64748b;
}
.portal-payment-success__download {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    width: 100%;
    max-width: 280px;
    min-height: 48px;
    padding: 0.75rem 1.25rem;
    border-radius: 14px;
    background: linear-gradient(135deg, #1a472a 0%, #245c38 100%);
    color: #fff !important;
    font-size: 0.875rem;
    font-weight: 700;
    text-decoration: none !important;
}
@media (max-width: 575.98px) {
    .portal-payment-success__hero { padding: 2rem 1.25rem 1.5rem; }
    .portal-payment-success__actions { flex-direction: column; align-items: stretch; }
    .portal-payment-success__btn { width: 100%; }
    .portal-payment-success__invoice { margin: 0 1rem 1rem; }
    .portal-payment-success__download { max-width: none; }
}
</style>

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
                                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" focusable="false">
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
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                                        <polyline points="9 22 9 12 15 12 15 22"/>
                                    </svg>
                                    Go to Home
                                </a>
                                <a href="{{ url('/users/products') }}" class="portal-payment-success__btn portal-payment-success__btn--secondary">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
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
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" focusable="false">
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
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
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
