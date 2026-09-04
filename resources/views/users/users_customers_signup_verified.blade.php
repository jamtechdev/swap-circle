@extends('layout.auth.master')

@section('title', ($brandName ?? 'Swap Circle') . ' — Welcome')

@php
    $heroEyebrow = 'You\'re in';
    $heroTitle = 'Welcome to<br>the circle.';
    $heroText = 'Your email is verified and your account is ready. Explore products, connect with the community, and start exchanging.';
    $authImage = 'users/assets/images/congratulations.png';
    $displayName = trim(session('first_name') . ' ' . session('last_name'));
    if (session('users_customers_type') === 'corporate' && session('company_name')) {
        $displayName = session('company_name');
    }
@endphp

@section('content_width')
    max-w-lg
@endsection

@section('content')
    <div class="text-center">
        <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-lime text-4xl shadow-lg shadow-lime/30">
            ✓
        </div>
        <h1 class="mt-6 text-3xl font-bold text-forest sm:text-4xl">You're verified</h1>
        <p class="mt-2 text-sm text-gray-500">Your email has been verified successfully.</p>
    </div>

    <div class="auth-success-card mt-8">
        @if(session('profile_pic'))
            <img src="{{ asset(session('profile_pic')) }}" alt="{{ $displayName }}" class="mx-auto h-24 w-24 rounded-2xl border-4 border-white object-cover shadow-md">
        @else
            <div class="mx-auto flex h-24 w-24 items-center justify-center rounded-2xl bg-forest/10 text-3xl font-bold text-forest">
                {{ strtoupper(substr(session('first_name') ?: 'U', 0, 1)) }}
            </div>
        @endif

        <h2 class="mt-4 text-xl font-bold text-forest">{{ $displayName }}</h2>

        <div class="mt-4 space-y-2 text-sm text-gray-600">
            @if(session('email'))
                <p class="flex items-center justify-center gap-2">
                    <svg class="h-4 w-4 shrink-0 text-forest" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    {{ session('email') }}
                </p>
            @endif
            @if(session('phone'))
                <p class="flex items-center justify-center gap-2">
                    <svg class="h-4 w-4 shrink-0 text-forest" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    {{ session('phone') }}
                </p>
            @endif
        </div>
    </div>

    <div class="mt-8 space-y-3">
        <a href="{{ url('/users/products') }}" class="auth-btn-primary">View Products</a>
        <a href="{{ $redirectUrl ?? url('/users/dashboard') }}" class="auth-btn-outline">Continue to Portal</a>
    </div>
@endsection
