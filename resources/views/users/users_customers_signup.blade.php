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
        <h1 class="mt-5 text-3xl font-bold text-forest">Choose your account type</h1>
        <p class="mt-2 text-sm text-gray-500">Select how you'd like to join {{ $brandName }}.</p>
    </div>

    <div class="mt-10 space-y-4">
        <a href="{{ url('/users/signup_individual') }}" class="group flex items-center gap-4 rounded-2xl border border-gray-100 bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:border-lime hover:shadow-lg">
            <span class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl bg-lime-soft text-2xl">👤</span>
            <span class="flex-1 text-left">
                <strong class="block text-base font-bold text-forest group-hover:text-forest-mid">As an Individual</strong>
                <span class="text-sm text-gray-500">For personal use — products, community, and support.</span>
            </span>
            <svg class="h-5 w-5 text-gray-300 transition group-hover:translate-x-1 group-hover:text-lime" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>

        <a href="{{ url('/users/signup_corporate') }}" class="group flex items-center gap-4 rounded-2xl border border-gray-100 bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:border-lime hover:shadow-lg">
            <span class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl bg-forest/10 text-2xl">🏢</span>
            <span class="flex-1 text-left">
                <strong class="flex flex-wrap items-center gap-2 text-base font-bold text-forest group-hover:text-forest-mid">
                    As a Corporate
                    @unless(config('signup.corporate_enabled'))
                        <span class="rounded-full bg-lime/30 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider text-forest">Coming soon</span>
                    @endunless
                </strong>
                <span class="text-sm text-gray-500">For businesses and organisations managing team accounts.</span>
            </span>
            <svg class="h-5 w-5 text-gray-300 transition group-hover:translate-x-1 group-hover:text-lime" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>
    </div>

    <div class="auth-login-banner">
        <div class="text-center sm:text-left">
            <p class="text-base font-bold text-forest">Already have an account?</p>
            <p class="mt-1 text-sm text-gray-500">Log in to access your dashboard, products, and messages.</p>
        </div>
        <a href="{{ url('/login') }}" class="auth-btn-outline mt-4 w-full sm:mt-0 sm:w-auto sm:min-w-[11rem] sm:px-8">Log In</a>
    </div>
@endsection

@section('content_width')
    max-w-lg
@endsection
