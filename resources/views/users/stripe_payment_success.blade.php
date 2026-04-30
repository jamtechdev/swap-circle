@extends('layout.users.master')
@section('content')
<div class="page-content-wrapper">
    <div class="page-content-tab">
        <div class="container-fluid px-4 pb-4">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-5 text-center">

                            <div class="mb-4">
                                <div class="mx-auto" style="width:80px;height:80px;background:#28a745;border-radius:50%;display:flex;align-items:center;justify-content:center;">
                                    <i class="fas fa-check text-white" style="font-size:36px;"></i>
                                </div>
                            </div>

                            <h2 class="mb-3 text-success">Payment Successful!</h2>
                            <p class="text-muted mb-4">Thank you for your purchase. Your payment has been successfully processed and your order has been confirmed.</p>

                            @if(session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif

                            <div class="d-flex justify-content-center flex-wrap gap-3 mb-4">
                                <a href="{{ url('/users/dashboard') }}" class="btn btn-primary px-4">
                                    <i class="fas fa-tachometer-alt me-2"></i>Go to Dashboard
                                </a>
                                <a href="{{ url('/users/products') }}" class="btn btn-outline-primary px-4">
                                    <i class="fas fa-shopping-cart me-2"></i>Continue Shopping
                                </a>
                            </div>

                            @if(isset($purchase_id))
                            <div class="card border-0 mt-2" style="background:#f0f4fb;border-radius:10px;">
                                <div class="card-body py-4">
                                    <div class="mb-3">
                                        <div class="mx-auto mb-2" style="width:48px;height:48px;background:#1a3c6e;border-radius:50%;display:flex;align-items:center;justify-content:center;">
                                            <i class="fas fa-file-invoice text-white" style="font-size:20px;"></i>
                                        </div>
                                        <h6 class="fw-bold text-dark mb-1">Your Invoice is Ready</h6>
                                        <p class="text-muted small mb-0">Click below to download your official payment invoice as PDF</p>
                                    </div>
                                    <a href="{{ url('/users/download-invoice/'.$purchase_id) }}" class="btn btn-success btn-lg px-5">
                                        <i class="fas fa-download me-2"></i>Download Invoice
                                    </a>
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
