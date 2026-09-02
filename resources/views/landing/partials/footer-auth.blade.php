@if ($auth['logged_in'])
    <a href="{{ $auth['dashboard_url'] }}" class="rounded-full border border-white/30 px-6 py-2 text-sm font-bold text-white hover:bg-white/10">{{ $auth['display_name'] }}</a>
    <a href="{{ $auth['products_url'] }}" class="rounded-full bg-lime px-6 py-2 text-sm font-bold text-forest-deep hover:bg-lime-hover">Browse Products</a>
@else
    <a href="{{ $auth['login_url'] }}" class="rounded-full border border-white/30 px-6 py-2 text-sm font-bold text-white hover:bg-white/10">Log In</a>
    <a href="{{ $auth['signup_url'] }}" class="rounded-full bg-lime px-6 py-2 text-sm font-bold text-forest-deep hover:bg-lime-hover">Sign Up</a>
@endif
