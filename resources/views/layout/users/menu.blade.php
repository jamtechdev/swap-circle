@php
    $displayName = trim((string) (session('first_name') . ' ' . session('last_name'))) ?: (string) session('email');
    $nameParts = preg_split('/\s+/', trim($displayName)) ?: [];
    $initials = collect($nameParts)
        ->filter()
        ->take(2)
        ->map(fn ($part) => strtoupper(substr($part, 0, 1)))
        ->join('') ?: 'U';
@endphp

<!-- side navbar start -->
<div id="sidebar-wrapper" class="portal-sidebar">
    <div class="portal-sidebar-inner">
        <div class="sidebar-logo">
            <a href="{{ url('/users/dashboard') }}" class="portal-sidebar-brand" aria-label="Go to dashboard home">
                <img src="{{ asset('uploads/system_image/'.$system_image[0]->description) }}" class="img-fluid img-logo" alt="{{ $system_name[0]->description ?? 'Swap Circle' }}">
            </a>
        </div>

        <nav class="portal-sidebar-nav" aria-label="Portal navigation">
            <p class="portal-sidebar-nav__label">Menu</p>
            <div class="list-group list-group-flush portal-sidebar-menu">
                <a href="{{ url('/users/dashboard') }}" class="list-group-item list-group-item-action {{ (request()->is('users/dashboard') || request()->is('users/wallets')) ? 'active' : '' }}">
                    <span class="portal-nav-icon" aria-hidden="true">
                        <svg class="portal-nav-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 10.5L12 3l9 7.5V20a1 1 0 01-1 1h-5v-6H9v6H4a1 1 0 01-1-1v-9.5z"/></svg>
                    </span>
                    <span class="portal-nav-label">Home</span>
                </a>
                <a href="{{ url('/users/products') }}" class="list-group-item list-group-item-action {{ (request()->is('users/products*')) || (request()->is('users/product*')) ? 'active' : '' }}">
                    <span class="portal-nav-icon" aria-hidden="true">
                        <svg class="portal-nav-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/><path d="M3.3 7.7L12 12.5l8.7-4.8M12 22.5V12.5"/></svg>
                    </span>
                    <span class="portal-nav-label">Products</span>
                </a>
                <a href="{{ url('/users/track') }}" class="list-group-item list-group-item-action {{ (request()->is('users/track')) ? 'active' : '' }}">
                    <span class="portal-nav-icon" aria-hidden="true">
                        <svg class="portal-nav-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 2L11 13"/><path d="M22 2l-7 20-4-9-9-4 20-7z"/></svg>
                    </span>
                    <span class="portal-nav-label">Track</span>
                </a>
                <a href="{{ url('/users/connect') }}" class="list-group-item list-group-item-action {{ (request()->is('users/connect*')) ? 'active' : '' }}">
                    <span class="portal-nav-icon" aria-hidden="true">
                        <svg class="portal-nav-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
                    </span>
                    <span class="portal-nav-label">Connect</span>
                </a>
                <a href="{{ url('/users/profile') }}" class="list-group-item list-group-item-action {{ (request()->is('users/profile*') || request()->is('users/billing_payment') || request()->is('users/transactions') || request()->is('users/settings')) ? 'active' : '' }}">
                    <span class="portal-nav-icon" aria-hidden="true">
                        <svg class="portal-nav-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    </span>
                    <span class="portal-nav-label">Profile</span>
                </a>
                <a href="{{ url('/users/message') }}" class="list-group-item list-group-item-action {{ (request()->is('users/message*')) ? 'active' : '' }}">
                    <span class="portal-nav-icon" aria-hidden="true">
                        <svg class="portal-nav-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg>
                    </span>
                    <span class="portal-nav-label">Message</span>
                </a>
                <a href="{{ url('/users/claims') }}" class="list-group-item list-group-item-action {{ (request()->is('users/claims')) ? 'active' : '' }}">
                    <span class="portal-nav-icon" aria-hidden="true">
                        <svg class="portal-nav-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    </span>
                    <span class="portal-nav-label">Claims</span>
                </a>
            </div>
        </nav>

        <div class="portal-sidebar-footer d-none d-md-block">
            <div class="portal-sidebar-user">
                <span class="portal-sidebar-user__avatar" aria-hidden="true">{{ $initials }}</span>
                <div class="portal-sidebar-user__meta">
                    <p>Signed in as</p>
                    <strong title="{{ $displayName }}">{{ $displayName }}</strong>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- side navbar end-->
