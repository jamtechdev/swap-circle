@extends('layout.auth.master')

@section('title', ($brandName ?? 'Swap Circle') . ' — Corporate coming soon')
@section('back_url', url('/users/signup'))
@section('back_label', 'Back to account type')

@php
    $heroTitle = 'Corporate accounts<br>are almost ready.';
    $heroText = 'We are finishing corporate onboarding. Individual accounts are open now — company sign up will return here soon.';
@endphp

@section('content')
    <div class="auth-coming-soon">
        <span class="auth-coming-soon__badge">Coming soon</span>
        <img
            src="{{ asset('uploads/system_image/' . $brandLogo) }}"
            alt="{{ $brandName }}"
            class="auth-coming-soon__logo"
        >
        <h1 class="auth-coming-soon__title">Corporate sign up is coming soon</h1>
        <p class="auth-coming-soon__text">
            Company accounts are paused for now. Join as an individual today, or check back shortly to register your organisation on {{ $brandName }}.
        </p>
        <div class="auth-coming-soon__actions">
            <a href="{{ url('/users/signup_individual') }}" class="auth-btn-primary">Sign up as an Individual</a>
            <a href="{{ url('/login') }}" class="auth-btn-outline">Log In</a>
        </div>
    </div>
@endsection
