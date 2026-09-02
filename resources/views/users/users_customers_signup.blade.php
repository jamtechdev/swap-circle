@extends('layout.auth.master')

@section('title', ($brandName ?? 'Swap Circle') . ' — Get Started')

@php
    $heroTitle = 'Join the circle.<br>Start exchanging.';
    $heroText = 'Create your account as an individual or organisation and connect with trusted community opportunities.';
@endphp

@section('content')
    <div class="text-center lg:text-left">
        <p class="text-sm font-bold uppercase tracking-widest text-forest/60">Get started with</p>
        <img src="{{ asset('uploads/system_image/' . $brandLogo) }}" alt="{{ $brandName }}" class="mx-auto mt-4 h-12 w-auto lg:mx-0">
        <h1 class="mt-5 font-display text-3xl font-bold text-forest">Choose your account type</h1>
        <p class="mt-2 text-sm text-gray-500">Select how you'd like to join {{ $brandName }}.</p>
    </div>

    <div class="mt-10 space-y-4">
        <a href="{{ url('/users/signup_individual') }}" class="group flex items-center gap-4 rounded-2xl border border-gray-100 bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:border-lime hover:shadow-lg">
            <span class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl bg-lime-soft text-2xl">👤</span>
            <span class="flex-1 text-left">
                <strong class="block text-base font-bold text-forest group-hover:text-forest-mid">As an Individual</strong>
                <span class="text-sm text-gray-500">For personal use — wallets, products, and community swaps.</span>
            </span>
            <svg class="h-5 w-5 text-gray-300 transition group-hover:translate-x-1 group-hover:text-lime" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>

        <a href="{{ url('/users/signup_corporate') }}" class="group flex items-center gap-4 rounded-2xl border border-gray-100 bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:border-lime hover:shadow-lg">
            <span class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl bg-forest/10 text-2xl">🏢</span>
            <span class="flex-1 text-left">
                <strong class="block text-base font-bold text-forest group-hover:text-forest-mid">As a Corporate</strong>
                <span class="text-sm text-gray-500">For businesses and organisations managing team accounts.</span>
            </span>
            <svg class="h-5 w-5 text-gray-300 transition group-hover:translate-x-1 group-hover:text-lime" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>
    </div>

    <p class="mt-8 text-center text-sm text-gray-500 lg:text-left">
        Already a user?
        <a href="{{ url('/login') }}" class="font-bold text-forest hover:text-lime-hover">Sign In</a>
    </p>
@endsection

@section('content_width')
    max-w-lg
@endsection
