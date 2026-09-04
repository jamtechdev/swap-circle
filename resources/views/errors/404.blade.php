@extends('layout.auth.master')

@section('title', ($brandName ?? 'Swap Circle') . ' — Page not found')
@section('back_url', url('/'))
@section('back_label', 'Back to home')

@php
    $heroTitle = 'This page<br>isn\'t here.';
    $heroText = 'The link may be outdated or mistyped. Head home or sign in to keep exploring ' . ($brandName ?? 'Swap Circle') . '.';
@endphp

@section('content')
    <div class="auth-coming-soon">
        <span class="auth-coming-soon__badge">404</span>
        <img
            src="{{ asset('uploads/system_image/' . ($brandLogo ?? 'logo.png')) }}"
            alt="{{ $brandName ?? 'Swap Circle' }}"
            class="auth-coming-soon__logo"
        >
        <h1 class="auth-coming-soon__title">Page not found</h1>
        <p class="auth-coming-soon__text">
            We couldn't find that page. It may have moved, or the link might be incorrect.
        </p>
        <div class="auth-coming-soon__actions">
            <a href="{{ url('/') }}" class="auth-btn-primary">Back to home</a>
            <a href="{{ url('/login') }}" class="auth-btn-outline">Back to login</a>
        </div>
    </div>
@endsection
