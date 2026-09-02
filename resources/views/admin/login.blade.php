@extends('layout.auth.master')

@section('title', ($brandName ?? 'Swap Circle') . ' — Admin Sign In')

@php
    $heroEyebrow = 'Admin Portal';
    $heroTitle = 'Manage your platform.<br>Stay in control.';
    $heroText = 'Access dashboards, users, products, rates, and system settings from one secure place.';
@endphp

@section('content')
    <div class="text-center lg:text-left">
        <img src="{{ asset('uploads/system_image/' . $brandLogo) }}" alt="{{ $brandName }}" class="mx-auto h-12 w-auto lg:mx-0">
        <span class="mt-6 inline-flex items-center rounded-full border border-forest/15 bg-lime-soft px-3 py-1 text-xs font-bold uppercase tracking-wider text-forest">Admin access</span>
        <h1 class="mt-3 font-display text-3xl font-bold text-forest sm:text-4xl">Welcome back</h1>
        <p class="mt-2 text-sm text-gray-500">Sign in to the {{ $brandName }} admin dashboard.</p>
    </div>

    <form method="POST" action="{{ url('/admin/login') }}" class="mt-8 space-y-4" novalidate>
        @csrf

        <div>
            <label for="email" class="auth-label">Email address</label>
            <div class="relative">
                <span class="auth-input-icon">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </span>
                <input
                    type="email"
                    id="email"
                    name="email"
                    class="auth-input"
                    placeholder="admin@swapcircle.com"
                    value="{{ old('email') }}"
                    autocomplete="email"
                    required
                >
            </div>
        </div>

        <div>
            <label for="password" class="auth-label">Password</label>
            <div class="relative">
                <span class="auth-input-icon">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                </span>
                <input
                    type="password"
                    id="password"
                    name="password"
                    class="auth-input pr-11"
                    placeholder="Enter password"
                    autocomplete="current-password"
                    required
                >
                <button type="button" class="auth-input-toggle" data-toggle-password="#password" aria-label="Show password" aria-pressed="false"></button>
            </div>
        </div>

        <button type="submit" class="auth-btn-primary mt-2">Sign in to admin</button>
    </form>

    <p class="mt-8 text-center text-sm text-gray-500 lg:text-left">
        Not an admin?
        <a href="{{ url('/login') }}" class="font-bold text-forest hover:text-lime-hover">User portal login</a>
    </p>
@endsection
