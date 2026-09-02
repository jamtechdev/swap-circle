@extends('layout.legal.master')

@section('content')
@php
    $legalPages = [
        'privacy' => ['label' => 'Privacy Policy', 'desc' => 'How we collect and use your data', 'icon' => '🔒', 'url' => url('/privacy')],
        'terms'   => ['label' => 'Terms of Service', 'desc' => 'Rules for using our platform', 'icon' => '📋', 'url' => url('/terms')],
        'cookies' => ['label' => 'Cookie Policy', 'desc' => 'Cookies and tracking preferences', 'icon' => '🍪', 'url' => url('/cookies')],
        'gdpr'    => ['label' => 'GDPR & Data Protection', 'desc' => 'Your rights and our obligations', 'icon' => '🛡️', 'url' => url('/gdpr')],
    ];
@endphp

<header class="legal-nav">
    <div class="legal-nav__inner">
        <x-brand-mark
            :href="url('/')"
            :label="$brand['name']"
            :logo="asset('uploads/system_image/' . $brand['logo'])"
            variant="inline"
            tone="light"
            class="legal-nav__brand"
        />
        <div class="legal-nav__actions">
            <a href="{{ url('/') }}" class="legal-btn-ghost">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M15 19l-7-7 7-7"/></svg>
                Home
            </a>
            @if($auth['logged_in'])
                <a href="{{ $auth['dashboard_url'] }}" class="legal-btn-primary">Dashboard</a>
            @else
                <a href="{{ $auth['login_url'] }}" class="legal-btn-primary">Sign In</a>
            @endif
        </div>
    </div>
</header>

<section class="legal-hero">
    <div class="legal-hero__orb legal-hero__orb--1"></div>
    <div class="legal-hero__orb legal-hero__orb--2"></div>
    <div class="legal-hero__inner">
        <span class="legal-hero__eyebrow">Legal &amp; Compliance</span>
        <h1 class="legal-hero__title">{{ $title }}</h1>
        <p class="legal-hero__subtitle">{{ $subtitle }}</p>
    </div>
</section>

<main class="legal-main">
    <aside class="legal-sidebar">
        <nav class="legal-tabs" aria-label="Legal pages">
            @foreach ($legalPages as $key => $page)
                <a href="{{ $page['url'] }}" class="{{ $slug === $key ? 'is-active' : '' }}">{{ $page['label'] }}</a>
            @endforeach
        </nav>

        <nav class="legal-sidebar__nav" aria-label="Legal documents">
            <div class="legal-sidebar__label">Documents</div>
            @foreach ($legalPages as $key => $page)
                <a href="{{ $page['url'] }}" class="legal-sidebar__link {{ $slug === $key ? 'is-active' : '' }}">
                    <span class="legal-sidebar__icon" aria-hidden="true">{{ $page['icon'] }}</span>
                    <span class="legal-sidebar__text">
                        <strong>{{ $page['label'] }}</strong>
                        <span>{{ $page['desc'] }}</span>
                    </span>
                </a>
            @endforeach
        </nav>

        <div class="legal-sidebar__card">
            <h3>Questions about your data?</h3>
            <p>Our team can help with privacy requests, account data, or cookie preferences.</p>
            <a href="mailto:support@swapcircle.trade">support@swapcircle.trade →</a>
        </div>
    </aside>

    <article class="legal-content-card">
        <div class="legal-content-card__bar"></div>
        <div class="legal-content-card__head">
            <span class="legal-content-card__meta">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                Official document
            </span>
            <span class="legal-content-card__updated">Last updated {{ date('F Y') }}</span>
        </div>
        <div class="legal-prose">
            {!! $body !!}
        </div>
        <div class="legal-content-card__footer">
            <p>By using {{ $brand['name'] }}, you agree to these policies where applicable.</p>
            @if($auth['logged_in'])
                <a href="{{ $auth['dashboard_url'] }}" class="legal-btn-primary">Back to Dashboard</a>
            @else
                <a href="{{ $auth['signup_url'] }}" class="legal-btn-primary">Create an Account</a>
            @endif
        </div>
    </article>
</main>

<footer class="legal-footer">
    <div class="legal-footer__inner">
        <span>&copy; {{ date('Y') }} {{ $brand['name'] }}. All rights reserved.</span>
        <nav class="legal-footer__links" aria-label="Legal footer">
            @foreach ($legalPages as $key => $page)
                <a href="{{ $page['url'] }}" class="{{ $slug === $key ? 'is-active' : '' }}">{{ $key === 'gdpr' ? 'GDPR' : ($key === 'terms' ? 'Terms' : ucfirst($key)) }}</a>
            @endforeach
        </nav>
    </div>
</footer>
@endsection
