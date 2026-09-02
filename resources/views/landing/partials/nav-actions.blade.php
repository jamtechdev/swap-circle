@if ($auth['logged_in'])
    <a href="{{ $auth['dashboard_url'] }}" data-nav-user class="max-w-[7rem] truncate rounded-full border-2 px-3 py-2 text-sm font-bold transition sm:max-w-[11rem] sm:px-4" title="{{ $auth['display_name'] }}">
        {{ $auth['display_name'] }}
    </a>
    <a href="{{ $auth['dashboard_url'] }}" class="rounded-full bg-lime px-4 py-2 text-sm font-bold text-forest-deep shadow-lg shadow-lime/30 transition hover:-translate-y-0.5 hover:bg-lime-hover sm:px-5">Dashboard</a>
@else
    <a href="{{ $auth['login_url'] }}" data-nav-login class="rounded-full border-2 px-3 py-2 text-sm font-bold transition sm:px-5">Log In</a>
    <a href="{{ $auth['signup_url'] }}" class="rounded-full bg-lime px-4 py-2 text-sm font-bold text-forest-deep shadow-lg shadow-lime/30 transition hover:-translate-y-0.5 hover:bg-lime-hover sm:px-5">Get Started</a>
@endif
