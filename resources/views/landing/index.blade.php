@extends('layout.landing.master')

@section('content')
@php
    $asset = fn (?string $path) => \App\Support\LandingContent::assetUrl($path);
@endphp

{{-- Navigation --}}
<nav id="scNav" class="fixed inset-x-0 top-0 z-50 h-20 border-b border-gray-100 bg-white shadow-sm">
    <div class="mx-auto flex h-full max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
        <x-brand-mark
            :href="url('/')"
            :label="$brand['name']"
            :logo="asset('uploads/system_image/' . $brand['logo'])"
            variant="nav"
            tone="light"
        />

        <ul id="scNavMenu" class="pointer-events-none absolute left-0 right-0 top-20 max-h-0 overflow-hidden bg-white opacity-0 shadow-lg transition-all duration-300 md:pointer-events-auto md:static md:flex md:max-h-none md:items-center md:gap-8 md:bg-transparent md:opacity-100 md:shadow-none">
            <li class="border-b border-gray-100 md:border-0"><a data-nav-link href="#features" class="block px-6 py-4 text-sm font-semibold text-gray-800 transition hover:text-lime md:px-0 md:py-0">Features</a></li>
            <li class="border-b border-gray-100 md:border-0"><a data-nav-link href="#how-it-works" class="block px-6 py-4 text-sm font-semibold text-gray-800 transition hover:text-lime md:px-0 md:py-0">How It Works</a></li>
            <li class="border-b border-gray-100 md:border-0"><a data-nav-link href="#products" class="block px-6 py-4 text-sm font-semibold text-gray-800 transition hover:text-lime md:px-0 md:py-0">Products</a></li>
            <li class="border-b border-gray-100 md:border-0"><a data-nav-link href="#community" class="block px-6 py-4 text-sm font-semibold text-gray-800 transition hover:text-lime md:px-0 md:py-0">Community</a></li>
            <li class="md:border-0"><a data-nav-link href="#insights" class="block px-6 py-4 text-sm font-semibold text-gray-800 transition hover:text-lime md:px-0 md:py-0">Insights</a></li>
            @unless($auth['logged_in'])
                <li class="border-t border-gray-100 md:hidden"><a href="{{ $auth['login_url'] }}" class="block px-6 py-4 text-sm font-semibold text-forest transition hover:text-lime">Log In</a></li>
                <li class="border-b border-gray-100 md:hidden"><a href="{{ $auth['signup_url'] }}" class="block px-6 py-4 text-sm font-semibold text-forest transition hover:text-lime">Sign Up</a></li>
            @else
                <li class="border-t border-gray-100 md:hidden"><a href="{{ $auth['dashboard_url'] }}" class="block px-6 py-4 text-sm font-semibold text-forest transition hover:text-lime">Dashboard</a></li>
            @endunless
        </ul>

        <div class="flex items-center gap-3">
            @include('landing.partials.nav-actions')
            <button id="scNavToggle" data-nav-toggle type="button" class="relative flex h-10 w-10 flex-col items-center justify-center gap-1.5 md:hidden" aria-label="Toggle menu">
                <span class="block h-0.5 w-6 rounded bg-forest transition-all"></span>
                <span class="block h-0.5 w-6 rounded bg-forest transition-all"></span>
                <span class="block h-0.5 w-6 rounded bg-forest transition-all"></span>
            </button>
        </div>
    </div>
</nav>

{{-- Hero --}}
<section id="home" class="relative flex min-h-screen items-center overflow-hidden">
    <img src="{{ $asset($content['hero']['image']) }}" alt="" class="absolute inset-0 h-full w-full object-cover">
    <div class="absolute inset-0 bg-linear-to-br from-forest-deep/90 via-forest/75 to-forest/40"></div>
    <div class="pointer-events-none absolute -right-20 top-20 h-72 w-72 rounded-full bg-lime/30 blur-3xl animate-pulse-orb"></div>
    <div class="pointer-events-none absolute -left-16 bottom-24 h-48 w-48 rounded-full bg-green-400/20 blur-3xl animate-pulse-orb" style="animation-delay:-3s"></div>

    <div class="relative z-10 mx-auto grid w-full max-w-7xl gap-12 px-4 pb-16 pt-32 sm:px-6 lg:grid-cols-2 lg:items-center lg:px-8 lg:pt-28">
        <div data-aos="fade-right">
            <span class="mb-5 inline-flex items-center gap-2 rounded-full border border-lime/35 bg-lime/15 px-4 py-1.5 text-xs font-bold uppercase tracking-wider text-lime">{{ $content['hero']['eyebrow'] }}</span>
            <h1 class="font-display text-4xl font-bold leading-tight text-white sm:text-5xl lg:text-6xl">
                {{ $content['hero']['title'] }} <span class="text-lime">{{ $content['hero']['title_highlight'] }}</span>
            </h1>
            <p class="mt-5 max-w-xl text-lg leading-relaxed text-white/85">
                {{ $content['hero']['subtitle'] }}
            </p>
            <div class="mt-8 flex flex-wrap gap-4">
                @if ($auth['logged_in'])
                    <a href="{{ $auth['products_url'] }}" class="rounded-full bg-lime px-8 py-3.5 text-sm font-bold text-forest-deep shadow-xl shadow-lime/35 transition hover:-translate-y-0.5 hover:bg-lime-hover">Browse Products</a>
                    <a href="{{ $auth['dashboard_url'] }}" class="rounded-full border-2 border-white/85 px-8 py-3.5 text-sm font-bold text-white transition hover:bg-white/10">Go to Dashboard</a>
                @else
                    <a href="{{ $auth['signup_url'] }}" class="rounded-full bg-lime px-8 py-3.5 text-sm font-bold text-forest-deep shadow-xl shadow-lime/35 transition hover:-translate-y-0.5 hover:bg-lime-hover">Choose a Plan for Them</a>
                    <a href="{{ $auth['login_url'] }}" class="rounded-full border-2 border-white/85 px-8 py-3.5 text-sm font-bold text-white transition hover:bg-white/10">Sign In</a>
                @endif
            </div>
            <div class="mt-8 flex flex-wrap items-center gap-6 text-sm text-white/75">
                <span class="flex items-center gap-2">{{ $content['hero']['trust_line_1'] }}</span>
                <span class="flex items-center gap-2">{{ $content['hero']['trust_line_2'] }}</span>
            </div>
        </div>

        <div class="hidden lg:block" data-aos="fade-left" data-aos-delay="200">
            <div class="animate-float rounded-3xl border border-white/20 bg-white/10 p-8 backdrop-blur-xl">
                <p class="mb-4 text-sm text-white/70">{{ $content['hero']['activity_label'] }}</p>
                <div class="grid grid-cols-2 gap-4">
                    @foreach ([1, 2, 3, 4] as $n)
                        <div class="rounded-xl bg-white/10 p-4 text-center">
                            @if ($n < 4)
                                <strong data-count="{{ $content['hero']['stat_'.$n.'_value'] }}" data-suffix="{{ $content['hero']['stat_'.$n.'_suffix'] }}" class="block text-2xl font-extrabold text-lime">0</strong>
                            @else
                                <strong class="block text-2xl font-extrabold text-lime">{{ $content['hero']['stat_4_value'] }}{{ $content['hero']['stat_4_suffix'] }}</strong>
                            @endif
                            <span class="text-xs text-white/70">{{ $content['hero']['stat_'.$n.'_label'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Diaspora bridge --}}
<section class="border-b border-gray-100 bg-white py-12">
    <div class="mx-auto flex max-w-5xl flex-wrap items-center justify-center gap-6 px-4 sm:gap-10">
        <div class="flex gap-3" data-aos="fade-up">
            @foreach (['🇬🇧', '🇺🇸', '🇨🇦', '🇪🇺'] as $flag)
                <span class="flex h-12 w-12 items-center justify-center rounded-full bg-gray-50 text-2xl shadow-md transition hover:scale-110">{{ $flag }}</span>
            @endforeach
        </div>
        <span class="text-3xl text-lime animate-pulse" data-aos="zoom-in">→</span>
        <div class="flex gap-3" data-aos="fade-up" data-aos-delay="100">
            @foreach (['🇬🇭', '🇳🇬', '🇰🇪'] as $flag)
                <span class="flex h-12 w-12 items-center justify-center rounded-full bg-gray-50 text-2xl shadow-md transition hover:scale-110">{{ $flag }}</span>
            @endforeach
        </div>
        <div class="max-w-sm text-center" data-aos="fade-up" data-aos-delay="200">
            <h3 class="font-display text-xl font-bold text-forest">{{ $content['bridge']['title'] }}</h3>
            <p class="mt-1 text-sm text-gray-500">{{ $content['bridge']['text'] }}</p>
        </div>
    </div>
</section>

{{-- Features --}}
<section id="features" class="bg-lime-soft/40 py-20 lg:py-28">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid items-center gap-12 lg:grid-cols-2 lg:gap-16">
            <div class="relative overflow-hidden rounded-3xl shadow-2xl shadow-forest/15" data-aos="fade-right">
                <img src="{{ $asset($content['features']['image']) }}" alt="Family using mobile app" class="aspect-4/5 w-full object-cover">
                <span class="absolute bottom-6 left-6 rounded-xl bg-lime px-4 py-2 text-sm font-bold text-forest-deep shadow-lg">{{ $content['features']['badge'] }}</span>
            </div>
            <div>
                <span class="text-xs font-bold uppercase tracking-widest text-forest" data-aos="fade-up">{{ $content['features']['eyebrow'] }}</span>
                <h2 class="mt-2 font-display text-3xl font-bold text-forest sm:text-4xl" data-aos="fade-up">{{ $content['features']['title'] }}</h2>
                <div class="mt-8 grid gap-4 sm:grid-cols-2">
                    @foreach ($content['features']['items'] as $i => $feature)
                        <div class="rounded-2xl border border-gray-100 bg-white p-5 transition hover:-translate-y-1 hover:border-lime hover:shadow-lg" data-aos="fade-up" data-aos-delay="{{ $i * 50 }}">
                            <div class="mb-3 flex h-11 w-11 items-center justify-center rounded-xl bg-lime/20 text-xl">{{ $feature['icon'] }}</div>
                            <h4 class="font-bold text-forest">{{ $feature['title'] }}</h4>
                            <p class="mt-1 text-sm leading-relaxed text-gray-500">{{ $feature['text'] }}</p>
                        </div>
                    @endforeach
                </div>
                <a href="{{ \App\Support\LandingAuth::ctaSignupOrProducts($auth) }}" class="mt-8 inline-flex rounded-full bg-forest px-8 py-3.5 text-sm font-bold text-white transition hover:-translate-y-0.5 hover:bg-forest-mid" data-aos="fade-up">{{ $auth['logged_in'] ? 'Browse Products' : 'Get Started in Minutes' }}</a>
            </div>
        </div>
    </div>
</section>

{{-- How it works --}}
<section id="how-it-works" class="bg-lime-soft/40 py-20 lg:py-28">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-2xl text-center">
            <span class="text-xs font-bold uppercase tracking-widest text-forest" data-aos="fade-up">{{ $content['how']['eyebrow'] }}</span>
            <h2 class="mt-2 font-display text-3xl font-bold text-forest sm:text-4xl" data-aos="fade-up">{{ $content['how']['title'] }}</h2>
            <p class="mt-4 text-gray-500" data-aos="fade-up">{{ $content['how']['subtitle'] }}</p>
        </div>
        <div class="mt-14 grid gap-6 md:grid-cols-3">
            @foreach ($content['how']['steps'] as $i => $step)
                <div class="group relative rounded-3xl border border-gray-100 bg-white p-8 transition hover:-translate-y-2 hover:shadow-xl hover:shadow-forest/10" data-aos="fade-up" data-aos-delay="{{ $i * 150 }}">
                    <span class="absolute right-6 top-6 text-4xl opacity-10">{{ $step['icon'] }}</span>
                    <div class="mb-5 flex h-12 w-12 items-center justify-center rounded-xl bg-forest text-sm font-extrabold text-lime">{{ $step['number'] }}</div>
                    <h4 class="text-lg font-bold text-forest">{{ $step['title'] }}</h4>
                    <p class="mt-2 text-sm leading-relaxed text-gray-500">{{ $step['text'] }}</p>
                </div>
            @endforeach
        </div>
        <div class="mt-12 text-center" data-aos="fade-up">
            <a href="{{ \App\Support\LandingAuth::ctaSignupOrProducts($auth) }}" class="inline-flex rounded-full bg-lime px-8 py-3.5 text-sm font-bold text-forest-deep shadow-lg shadow-lime/30 transition hover:-translate-y-0.5 hover:bg-lime-hover">{{ $auth['logged_in'] ? 'View Products' : 'Calculate Your Premium' }}</a>
        </div>
    </div>
</section>

{{-- Why choose us --}}
<section id="community" class="relative overflow-hidden bg-forest py-20 text-white lg:py-28">
    <div class="pointer-events-none absolute inset-0 opacity-[0.04]" style="background-image:url(&quot;data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23ffffff'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4z'/%3E%3C/g%3E%3C/svg%3E&quot;)"></div>
    <div class="relative mx-auto grid max-w-7xl items-center gap-12 px-4 sm:px-6 lg:grid-cols-2 lg:px-8">
        <div data-aos="fade-right">
            <span class="text-xs font-bold uppercase tracking-widest text-lime">{{ $content['community']['eyebrow'] }}</span>
            <h2 class="mt-2 font-display text-3xl font-bold sm:text-4xl">{{ $content['community']['title'] }}</h2>
            <ul class="mt-8 space-y-4">
                @foreach ($content['community']['items'] as $item)
                    <li class="flex items-start gap-3 border-b border-white/10 pb-4 text-sm text-white/90">
                        <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-lime text-xs font-extrabold text-forest-deep">✓</span>
                        {{ $item }}
                    </li>
                @endforeach
            </ul>
            <a href="{{ \App\Support\LandingAuth::ctaSignupOrDashboard($auth) }}" class="mt-8 inline-flex rounded-full bg-lime px-8 py-3.5 text-sm font-bold text-forest-deep transition hover:-translate-y-0.5 hover:bg-lime-hover">{{ $auth['logged_in'] ? 'Go to Dashboard' : 'Join the Community' }}</a>
        </div>
        <div class="relative flex justify-center" data-aos="fade-left">
            <div class="h-64 w-64 overflow-hidden rounded-full border-4 border-lime shadow-[0_0_0_12px_rgba(200,230,53,0.15)] sm:h-72 sm:w-72" title="Main community avatar">
                <img src="{{ $asset($content['community']['main_image']) }}" alt="Community member" class="h-full w-full object-cover">
            </div>
            @foreach ([
                ['top-0 left-1/2 -translate-x-1/2', 0, 'Community member top'],
                ['bottom-8 right-0', 1, 'Community member bottom right'],
                ['bottom-8 left-0', 2, 'Community member bottom left'],
            ] as $sat)
                <div class="absolute {{ $sat[0] }} h-14 w-14 overflow-hidden rounded-full border-[3px] border-white shadow-lg animate-float">
                    <img src="{{ $asset($content['community']['satellite_images'][$sat[1]] ?? '') }}" alt="{{ $sat[2] }}" class="h-full w-full object-cover">
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Products --}}
<section id="products" class="py-20 lg:py-28">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-2xl text-center">
            <span class="text-xs font-bold uppercase tracking-widest text-forest" data-aos="fade-up">{{ $content['products']['eyebrow'] }}</span>
            <h2 class="mt-2 font-display text-3xl font-bold text-forest sm:text-4xl" data-aos="fade-up">{{ $content['products']['title'] }}</h2>
            <p class="mt-4 text-gray-500" data-aos="fade-up">{{ $content['products']['subtitle'] }}</p>
        </div>
        <div class="mt-14 grid gap-6 md:grid-cols-3">
            @foreach ($content['products']['items'] as $i => $product)
                <article class="group overflow-hidden rounded-3xl border border-gray-100 bg-white transition hover:-translate-y-2 hover:shadow-xl hover:shadow-forest/10" data-aos="fade-up" data-aos-delay="{{ $i * 150 }}">
                    <div class="relative h-48 overflow-hidden bg-forest/5">
                        <img src="{{ $asset($product['image']) }}" alt="{{ $product['title'] }}" loading="lazy" class="h-full w-full object-cover transition duration-500 group-hover:scale-105" data-fallback-image>
                    </div>
                    <div class="p-6">
                        <span class="inline-block rounded-full bg-lime/20 px-3 py-1 text-xs font-bold text-forest">{{ $product['badge'] }}</span>
                        <h4 class="mt-3 text-lg font-bold text-forest">{{ $product['title'] }}</h4>
                        <p class="mt-2 text-sm text-gray-500">{{ $product['text'] }}</p>
                        <a href="{{ \App\Support\LandingAuth::ctaLoginOrProducts($auth) }}" class="mt-4 inline-flex items-center gap-1 text-sm font-bold text-forest transition group-hover:gap-2 hover:text-lime-hover">{{ $auth['logged_in'] ? 'View Products' : 'Sign In to View' }} →</a>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>

{{-- Testimonial --}}
<section class="bg-lime-soft/40 py-20 lg:py-28">
    <div class="mx-auto grid max-w-7xl items-center gap-12 px-4 sm:px-6 lg:grid-cols-2 lg:px-8">
        <div class="group relative min-h-[280px] overflow-hidden rounded-3xl bg-forest/10 shadow-2xl sm:min-h-[320px] lg:min-h-[360px]" data-aos="fade-right">
            <img src="{{ $asset($content['testimonial']['video_image']) }}" alt="Family testimonial" loading="lazy" class="absolute inset-0 h-full w-full object-cover" data-fallback-image>
            <div class="absolute inset-0 flex items-center justify-center bg-forest/35 transition group-hover:bg-forest/50">
                <span class="flex h-16 w-16 items-center justify-center rounded-full bg-lime text-xl text-forest-deep shadow-xl transition group-hover:scale-110">▶</span>
            </div>
        </div>
        <div data-aos="fade-left">
            <blockquote class="font-display text-2xl font-semibold leading-snug text-forest sm:text-3xl">{{ $content['testimonial']['quote'] }}</blockquote>
            <div class="mt-6 flex items-center gap-4">
                <img src="{{ $asset($content['testimonial']['avatar']) }}" alt="" class="h-12 w-12 rounded-full object-cover">
                <div>
                    <strong class="block text-forest">{{ $content['testimonial']['name'] }}</strong>
                    <span class="text-sm text-gray-500">{{ $content['testimonial']['role'] }}</span>
                </div>
            </div>
            <a href="{{ $auth['logged_in'] ? $auth['products_url'] : url('/users/signup') }}" class="mt-8 inline-flex rounded-full bg-forest px-8 py-3.5 text-sm font-bold text-white transition hover:bg-forest-mid">{{ $auth['logged_in'] ? 'Explore Products' : 'See How It Works — 2 Min' }}</a>
        </div>
    </div>
</section>

{{-- Partners ticker --}}
@php
    $partners = $content['partners']['items'];
@endphp
<section class="relative overflow-hidden border-y border-lime/25 bg-linear-to-b from-white via-lime-soft/40 to-white py-12 lg:py-16" aria-label="Trusted partner networks">
    <div class="mx-auto max-w-7xl px-4 text-center sm:px-6 lg:px-8" data-aos="fade-up">
        <span class="inline-block rounded-full border border-lime/40 bg-lime/15 px-3 py-1 text-xs font-bold uppercase tracking-widest text-forest">{{ $content['partners']['eyebrow'] }}</span>
        <h3 class="mt-3 font-display text-xl font-bold text-forest sm:text-2xl">{{ $content['partners']['title'] }}</h3>
        <p class="mt-1 text-sm text-gray-500">{{ $content['partners']['subtitle'] }}</p>
    </div>

    <div class="relative mt-10">
        <div class="pointer-events-none absolute inset-y-0 left-0 z-10 w-16 bg-linear-to-r from-white via-white/90 to-transparent sm:w-28"></div>
        <div class="pointer-events-none absolute inset-y-0 right-0 z-10 w-16 bg-linear-to-l from-white via-white/90 to-transparent sm:w-28"></div>

        <div class="flex w-max animate-ticker items-center gap-5 px-4 sm:gap-6">
            @foreach (array_merge($partners, $partners) as $partner)
                <div class="group flex shrink-0 items-center gap-3 rounded-2xl border border-gray-100/80 bg-white/90 px-4 py-3 shadow-sm backdrop-blur-sm transition duration-300 hover:-translate-y-1 hover:border-lime/50 hover:shadow-lg hover:shadow-forest/5 sm:px-5 sm:py-3.5">
                    @if(!empty($partner['image']))
                        <img
                            src="{{ $asset($partner['image']) }}"
                            alt="{{ $partner['name'] }}"
                            class="h-10 w-auto max-w-[8.5rem] shrink-0 object-contain transition group-hover:scale-105 sm:h-11 sm:max-w-[9.5rem]"
                            loading="lazy"
                            decoding="async"
                        >
                    @else
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl text-[10px] font-extrabold tracking-tight transition group-hover:scale-105 sm:text-xs {{ $partner['badge'] ?? 'bg-forest text-lime' }}">{{ $partner['abbr'] ?? '?' }}</span>
                        <span class="whitespace-nowrap text-sm font-bold text-forest/75 transition group-hover:text-forest sm:text-base">{{ $partner['name'] }}</span>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    <div class="mx-auto mt-8 flex max-w-7xl flex-wrap items-center justify-center gap-x-8 gap-y-2 px-4 text-center text-xs font-semibold uppercase tracking-wider text-gray-400 sm:px-6 lg:px-8">
        @foreach ($content['partners']['trust_badges'] as $badge)
            <span class="flex items-center gap-2"><span class="h-1.5 w-1.5 rounded-full bg-lime"></span> {{ $badge }}</span>
        @endforeach
    </div>
</section>

{{-- CTA --}}
<section class="relative overflow-hidden py-20 lg:py-28">
    <div class="absolute inset-0 bg-linear-to-br from-lime via-lime-hover to-forest"></div>
    <div class="pointer-events-none absolute -right-24 -top-24 h-96 w-96 rounded-full bg-white/10"></div>
    <div class="pointer-events-none absolute -bottom-16 left-16 h-64 w-64 rounded-full bg-white/10"></div>
    <div class="relative mx-auto grid max-w-7xl items-center gap-12 px-4 sm:px-6 lg:grid-cols-2 lg:px-8">
        <div class="overflow-hidden rounded-3xl shadow-2xl" data-aos="fade-right">
            <img src="{{ $asset($content['cta']['image']) }}" alt="Team" class="aspect-4/3 w-full object-cover">
        </div>
        <div data-aos="fade-left">
            <h2 class="font-display text-3xl font-bold text-forest-deep sm:text-4xl">{{ $content['cta']['title'] }}</h2>
            <p class="mt-4 max-w-md text-forest-deep/75">{{ $content['cta']['text'] }}</p>
            <a href="{{ \App\Support\LandingAuth::ctaSignupOrDashboard($auth) }}" class="mt-8 inline-flex rounded-full bg-forest px-8 py-3.5 text-sm font-bold text-white shadow-lg transition hover:-translate-y-0.5 hover:bg-forest-mid">{{ $auth['logged_in'] ? 'Go to Dashboard' : 'Get Started Free' }}</a>
        </div>
    </div>
</section>

{{-- App showcase --}}
<section class="bg-forest py-20 text-white lg:py-28">
    <div class="mx-auto grid max-w-7xl items-center gap-12 px-4 sm:px-6 lg:grid-cols-2 lg:px-8">
        <div class="relative flex justify-center" data-aos="fade-right">
            <div class="animate-float w-64 rounded-[2rem] bg-forest-deep p-3 shadow-2xl">
                <div class="min-h-[420px] rounded-[1.6rem] bg-linear-to-b from-forest to-forest-mid p-6">
                    <div class="mb-6 text-lg font-extrabold text-lime">{{ $brand['name'] }}</div>
                    @foreach ($content['app']['rows'] as $row)
                        <div class="mb-3 rounded-xl bg-white/10 px-4 py-3 text-sm">{{ $row }}</div>
                    @endforeach
                    <div class="rounded-xl bg-lime px-4 py-3 text-sm font-bold text-forest-deep">{{ $content['app']['cta_row'] }}</div>
                </div>
            </div>
            <div class="absolute right-4 top-8 animate-float rounded-xl bg-white px-4 py-2 text-xs font-bold text-forest shadow-lg" style="animation-delay:-1s">{{ $content['app']['toast_1'] }}</div>
            <div class="absolute bottom-16 left-0 animate-float rounded-xl bg-white px-4 py-2 text-xs font-bold text-forest shadow-lg" style="animation-delay:-2s">{{ $content['app']['toast_2'] }}</div>
        </div>
        <div data-aos="fade-left">
            <span class="text-xs font-bold uppercase tracking-widest text-lime">{{ $content['app']['eyebrow'] }}</span>
            <h2 class="mt-2 font-display text-3xl font-bold sm:text-4xl">{{ $content['app']['title'] }}</h2>
            <p class="mt-4 leading-relaxed text-white/80">{{ $content['app']['text'] }}</p>
            <a href="{{ \App\Support\LandingAuth::ctaSignupOrDashboard($auth) }}" class="mt-8 inline-flex rounded-full bg-lime px-8 py-3.5 text-sm font-bold text-forest-deep transition hover:bg-lime-hover">{{ $auth['logged_in'] ? 'Open Dashboard' : 'Explore the Platform' }}</a>
        </div>
    </div>
</section>

{{-- Insights --}}
<section id="insights" class="py-20 lg:py-28">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-2xl text-center">
            <span class="text-xs font-bold uppercase tracking-widest text-forest" data-aos="fade-up">{{ $content['insights']['eyebrow'] }}</span>
            <h2 class="mt-2 font-display text-3xl font-bold text-forest sm:text-4xl" data-aos="fade-up">{{ $content['insights']['title'] }}</h2>
        </div>

        @if(count($insightPosts) > 0)
            <div class="sc-insights-carousel mt-14" data-insights-carousel data-aos="fade-up">
                <button type="button" class="sc-insights-carousel__nav sc-insights-carousel__nav--prev" data-carousel-prev aria-label="Previous insight">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" aria-hidden="true"><path d="M12.5 15 7.5 10l5-5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </button>

                <div class="sc-insights-carousel__viewport">
                    <div class="sc-insights-carousel__track" data-carousel-track tabindex="0" aria-label="Community insights carousel">
                        @foreach ($insightPosts as $post)
                            <article class="sc-insights-carousel__slide">
                                <a href="{{ \App\Support\LandingInsights::articleUrl($auth, $post) }}" class="group flex h-full flex-col overflow-hidden rounded-2xl border border-gray-100 bg-white transition hover:-translate-y-1 hover:border-lime/40 hover:shadow-lg">
                                    <div class="relative h-44 overflow-hidden bg-forest/5">
                                        @if(!empty($post['image']))
                                            <img src="{{ $asset($post['image']) }}" alt="{{ $post['title'] }}" loading="lazy" class="h-full w-full object-cover transition duration-500 group-hover:scale-105" data-fallback-image>
                                        @else
                                            <div class="h-full w-full bg-linear-to-br from-forest/20 via-lime/20 to-forest/10"></div>
                                        @endif
                                    </div>
                                    <div class="flex flex-1 flex-col p-5">
                                        @if(!empty($post['date']))
                                            <time class="text-xs text-gray-400">{{ $post['date'] }}</time>
                                        @endif
                                        <h4 class="mt-2 flex-1 font-bold leading-snug text-forest">{{ $post['title'] }}</h4>
                                        <span class="mt-3 inline-block text-sm font-bold text-forest transition group-hover:text-lime-hover">Read more →</span>
                                    </div>
                                </a>
                            </article>
                        @endforeach
                    </div>
                </div>

                <button type="button" class="sc-insights-carousel__nav sc-insights-carousel__nav--next" data-carousel-next aria-label="Next insight">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" aria-hidden="true"><path d="M7.5 5 12.5 10l-5 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </button>

                <div class="sc-insights-carousel__dots" data-carousel-dots role="tablist" aria-label="Insight slides"></div>
            </div>

            <div class="mt-10 text-center" data-aos="fade-up">
                <a href="{{ \App\Support\LandingAuth::ctaConnect($auth) }}" class="inline-flex items-center gap-2 rounded-full border border-forest/15 bg-white px-5 py-2.5 text-sm font-bold text-forest transition hover:border-lime hover:bg-lime/10 hover:text-forest">
                    {{ $auth['logged_in'] ? 'Explore all in Connect' : 'Log in to read in Connect' }} →
                </a>
            </div>
        @endif
    </div>
</section>

{{-- Footer --}}
<footer class="bg-gray-900 pt-16 text-white/75">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="border-b border-white/10 pb-12 text-center" data-aos="fade-up">
            <h3 class="font-display text-2xl font-bold text-white">Connect with us</h3>
            <div class="mt-6 flex justify-center gap-3">
                @php
                    $socialUrl = fn (?string $url) => filled($url) ? $url : '#';
                    $socialLinks = [
                        [
                            'label' => 'Instagram',
                            'href' => $socialUrl($content['footer']['instagram_url'] ?? null),
                            'icon' => '<svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="5" stroke="currentColor" stroke-width="1.75"/><circle cx="12" cy="12" r="4.25" stroke="currentColor" stroke-width="1.75"/><circle cx="17.2" cy="6.8" r="1.1" fill="currentColor"/></svg>',
                        ],
                        [
                            'label' => 'Facebook',
                            'href' => $socialUrl($content['footer']['facebook_url'] ?? null),
                            'icon' => '<svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M14 8.5h2.5l-.5 3H14v9h-3.5v-9H9V8.5h1.5V6.8c0-2.2 1.3-3.8 3.7-3.8H14v3h-1.4c-.8 0-.9.4-.9 1v1.3Z"/></svg>',
                        ],
                        [
                            'label' => 'X',
                            'href' => $socialUrl($content['footer']['x_url'] ?? null),
                            'icon' => '<svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M16.6 4h3.1l-6.8 7.8L21 20h-6.1l-4.8-6.3L4.6 20H1.5l7.3-8.4L3 4h6.2l4.3 5.7L16.6 4Zm-1.1 14.3h1.7L8.7 5.6H6.9l8.6 12.7Z"/></svg>',
                        ],
                    ];
                @endphp
                @foreach ($socialLinks as $social)
                    <a
                        href="{{ $social['href'] }}"
                        class="flex h-11 w-11 items-center justify-center rounded-full bg-white/10 text-sm transition hover:-translate-y-1 hover:bg-lime hover:text-forest-deep"
                        aria-label="{{ $social['label'] }}"
                        @if($social['href'] !== '#') target="_blank" rel="noopener noreferrer" @endif
                    >{!! $social['icon'] !!}</a>
                @endforeach
            </div>
            <div class="mt-6 flex flex-wrap justify-center gap-3">
                @include('landing.partials.footer-auth')
            </div>
        </div>

        <div class="grid gap-10 py-12 sm:grid-cols-2 lg:grid-cols-4">
            <div>
                <x-brand-mark
                    :href="url('/')"
                    :label="$brand['name']"
                    :logo="asset('uploads/system_image/' . $brand['logo'])"
                    variant="inline"
                    tone="dark"
                />
                <p class="mt-4 text-sm leading-relaxed">{{ $content['footer']['tagline'] }}</p>
            </div>
            <div>
                <h5 class="text-sm font-bold uppercase tracking-wider text-white">Quick Links</h5>
                <ul class="mt-4 space-y-2 text-sm">
                    <li><a href="#features" class="hover:text-lime">Features</a></li>
                    <li><a href="#how-it-works" class="hover:text-lime">How It Works</a></li>
                    <li><a href="#products" class="hover:text-lime">Products</a></li>
                    <li><a href="{{ $auth['logged_in'] ? $auth['dashboard_url'] : $auth['signup_url'] }}" class="hover:text-lime">{{ $auth['logged_in'] ? 'Dashboard' : 'Sign Up' }}</a></li>
                </ul>
            </div>
            <div>
                <h5 class="text-sm font-bold uppercase tracking-wider text-white">Company</h5>
                <ul class="mt-4 space-y-2 text-sm">
                    <li><a href="#" class="hover:text-lime">About Us</a></li>
                    <li><a href="{{ url('/privacy') }}" class="hover:text-lime">Privacy Policy</a></li>
                    <li><a href="{{ url('/terms') }}" class="hover:text-lime">Terms of Service</a></li>
                    <li><a href="{{ url('/gdpr') }}" class="hover:text-lime">GDPR</a></li>
                    <li><a href="#" class="hover:text-lime">Contact</a></li>
                </ul>
            </div>
            <div>
                <h5 class="text-sm font-bold uppercase tracking-wider text-white">Newsletter</h5>
                <p class="mt-4 text-sm">{{ $content['footer']['newsletter_text'] }}</p>
                <form class="mt-3 flex gap-2" onsubmit="event.preventDefault()">
                    <input type="email" placeholder="Your email" class="min-w-0 flex-1 rounded-full border border-white/15 bg-white/5 px-4 py-2.5 text-sm text-white placeholder:text-white/40 outline-none focus:border-lime">
                    <button type="submit" class="shrink-0 rounded-full bg-lime px-4 py-2.5 text-sm font-bold text-forest-deep hover:bg-lime-hover">Subscribe</button>
                </form>
            </div>
        </div>

        <div class="flex flex-wrap items-center justify-between gap-4 border-t border-white/10 py-6 text-xs">
            <span>&copy; {{ date('Y') }} {{ $brand['name'] }}. All rights reserved.</span>
            <div class="flex gap-6">
                <a href="{{ url('/privacy') }}" class="hover:text-lime">Privacy</a>
                <a href="{{ url('/terms') }}" class="hover:text-lime">Terms</a>
                <a href="{{ url('/cookies') }}" class="hover:text-lime">Cookies</a>
                <a href="{{ url('/gdpr') }}" class="hover:text-lime">GDPR</a>
            </div>
        </div>
    </div>
</footer>

@endsection
