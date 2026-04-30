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
                                <div class="cancel-icon mx-auto" style="width: 80px; height: 80px; background-color: #ffc107; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-times text-white" style="font-size: 36px;"></i>
                                </div>
                            </div>
                            
                            <h2 class="mb-3 text-warning">Payment Cancelled</h2>
                            <p class="mb-4">Your payment was cancelled. No charges were made to your account. You can try purchasing again whenever you're ready.</p>
                            
                            @if(session('warning'))
                                <div class="alert alert-warning">
                                    {{ session('warning') }}
                                </div>
                            @endif
                            
                            <div class="d-flex justify-content-center gap-3">
                                <a href="{{ url('/users/products') }}" class="btn btn-primary">
                                    <i class="fas fa-shopping-cart me-2"></i>Try Again
                                </a>
                                <a href="{{ url('/users/dashboard') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-tachometer-alt me-2"></i>Go to Dashboard
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
