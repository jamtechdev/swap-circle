@extends('layout.users.master')
@section('content')
    <div class="page-content-wrapper">
        <div class="page-content-tab">
            <div class="container-fluid px-4 pb-4">
                <div class="wallet-wrapper">
                <div class="wallet-tabs mt-0">
                    <div class="row">
                        <div class="col-12">
                            <div class="card shadow-sm border-0">
                                <div class="card-header bg-primary text-white py-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h5 class="mb-0"><span class="me-2">📦</span>Product Details</h5>
                                            <small class="opacity-75">View and manage product information</small>
                                        </div>
                                        <div>
                                            <span class="badge badge-{{ $product->status == 'Active' ? 'success' : 'danger' }} fs-6">
                                                {{ $product->status }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body p-4">
                                    <div class="row">

                                        <!-- Product Information -->
                                        <div class="col-lg-8 mb-4">
                                            <div class="card border-0 bg-light">
                                                <div class="card-body">
                                                    <h6 class="card-title text-primary mb-4"><span class="me-2">ℹ️</span>Product Information</h6>
                                                    <div class="row g-3">
                                                        <div class="col-md-6">
                                                            <div class="d-flex align-items-center mb-3">
                                                                <div class="flex-shrink-0">
                                                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width:40px;height:40px;"><span>🏷️</span></div>
                                                                </div>
                                                                <div class="flex-grow-1 ms-3">
                                                                    <small class="text-muted d-block">Product Name</small>
                                                                    <strong class="text-dark">{{ $product->name }}</strong>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="d-flex align-items-center mb-3">
                                                                <div class="flex-shrink-0">
                                                                    <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center" style="width:40px;height:40px;"><span>💰</span></div>
                                                                </div>
                                                                <div class="flex-grow-1 ms-3">
                                                                    <small class="text-muted d-block">Price</small>
                                                                    <strong class="text-success">£{{ number_format($product->price, 2) }}</strong>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="d-flex align-items-center mb-3">
                                                                <div class="flex-shrink-0">
                                                                    <div class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center" style="width:40px;height:40px;"><span>📋</span></div>
                                                                </div>
                                                                <div class="flex-grow-1 ms-3">
                                                                    <small class="text-muted d-block">Product Type</small>
                                                                    <strong class="text-info">{{ $product->type }}</strong>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="d-flex align-items-center mb-3">
                                                                <div class="flex-shrink-0">
                                                                    <div class="bg-warning text-white rounded-circle d-flex align-items-center justify-content-center" style="width:40px;height:40px;"><span>✅</span></div>
                                                                </div>
                                                                <div class="flex-grow-1 ms-3">
                                                                    <small class="text-muted d-block">Status</small>
                                                                    <strong class="text-warning">{{ $product->status }}</strong>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @if($product->description)
                                                        <div class="col-12">
                                                            <div class="d-flex align-items-start mb-3">
                                                                <div class="flex-shrink-0">
                                                                    <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center" style="width:40px;height:40px;"><span>📝</span></div>
                                                                </div>
                                                                <div class="flex-grow-1 ms-3">
                                                                    <small class="text-muted d-block">Description</small>
                                                                    <p class="text-dark mb-0">{{ $product->description }}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Actions Panel -->
                                        <div class="col-lg-4 mb-4">
                                            <div class="card border-0 bg-light">
                                                <div class="card-body">
                                                    <h6 class="card-title text-primary mb-4"><span class="me-2">⚙️</span>Quick Actions</h6>
                                                    <div class="d-grid gap-2">
                                                        <a href="{{ url('/users/products') }}" class="btn btn-outline-primary btn-lg">
                                                            <span class="me-2">⬅️</span>Back to Products
                                                        </a>
                                                        <a href="{{ url('/users/product/'.$product->type.'/'.$product->products_id) }}" class="btn btn-success btn-lg">
                                                            <span class="me-2">🛒</span>Purchase Product
                                                        </a>
                                                        <a href="{{ url('/users/dashboard') }}" class="btn btn-outline-secondary btn-lg">
                                                            <span class="me-2">📊</span>Dashboard
                                                        </a>
                                                    </div>

                                                    @php
                                                        $latestPurchase = DB::table('products_purchases')
                                                            ->where('products_id', $product->products_id)
                                                            ->where('users_customers_id', session('id'))
                                                            ->where('payment_status', 'Successful')
                                                            ->orderBy('products_purchases_id', 'DESC')
                                                            ->first();
                                                    @endphp

                                                    @if($latestPurchase)
                                                    <div class="mt-4 p-3 text-center" style="background:#f0f4fb;border-radius:10px;">
                                                        <div class="mx-auto mb-2" style="width:40px;height:40px;background:#1a3c6e;border-radius:50%;display:flex;align-items:center;justify-content:center;">
                                                            <i class="fas fa-file-invoice text-white"></i>
                                                        </div>
                                                        <p class="small text-muted mb-2">Your invoice is ready to download</p>
                                                        <a href="{{ url('/users/download-invoice/'.$latestPurchase->products_purchases_id) }}" class="btn btn-warning btn-lg w-100">
                                                            <i class="fas fa-download me-2"></i>Download Invoice
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
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
@endsection
