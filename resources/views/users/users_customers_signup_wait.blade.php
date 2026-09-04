@extends('layout.auth.master')

@section('title', ($brandName ?? 'Swap Circle') . ' — Verifying')

@php
    $heroTitle = 'Hang tight.<br>Almost ready.';
    $heroText = 'We\'re verifying your details and setting up your account. This usually takes just a moment.';
@endphp

@section('content')
    <div class="text-center">
        <img src="{{ asset('uploads/system_image/' . $brandLogo) }}" alt="{{ $brandName }}" class="mx-auto h-12 w-auto">
        <h1 class="mt-8 text-3xl font-bold text-forest">Please wait…</h1>
        <p class="mt-2 text-sm font-semibold text-lime-hover">Verifying your account</p>

        <div class="mt-10 flex justify-center gap-2">
            <span class="h-3 w-3 animate-bounce rounded-full bg-lime [animation-delay:-0.3s]"></span>
            <span class="h-3 w-3 animate-bounce rounded-full bg-lime [animation-delay:-0.15s]"></span>
            <span class="h-3 w-3 animate-bounce rounded-full bg-lime"></span>
        </div>

        <img src="{{ asset('users/assets/images/verifiy-screen.png') }}" alt="" class="mx-auto mt-10 max-h-48 w-auto opacity-90">
    </div>
@endsection
