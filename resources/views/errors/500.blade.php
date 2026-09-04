@extends('layout.auth.master')

@section('title', ($brandName ?? 'Swap Circle') . ' — Something went wrong')
@section('back_url', url('/'))
@section('back_label', 'Back to home')

@php
    $heroTitle = 'Something went<br>wrong on our side.';
    $heroText = 'Please try again in a moment. You can return home or sign in while we sort this out.';
@endphp

@section('content')
    <div class="auth-coming-soon">
        <span class="auth-coming-soon__badge">500</span>
        <img
            src="{{ asset('uploads/system_image/' . ($brandLogo ?? 'logo.png')) }}"
            alt="{{ $brandName ?? 'Swap Circle' }}"
            class="auth-coming-soon__logo"
        >
        <h1 class="auth-coming-soon__title">Something went wrong</h1>
        <p class="auth-coming-soon__text">
            An unexpected error occurred. Please try again shortly — your account and data are safe.
        </p>
        <div class="auth-coming-soon__actions">
            <a href="{{ url('/') }}" class="auth-btn-primary">Back to home</a>
            <a href="{{ url('/login') }}" class="auth-btn-outline">Back to login</a>
        </div>
    </div>
@endsection
