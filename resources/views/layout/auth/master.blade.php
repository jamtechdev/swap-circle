<!DOCTYPE html>
<html lang="en" class="h-full scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title', $brandName)</title>
    <link rel="icon" type="image/png" sizes="24x24" href="{{ asset('uploads/system_image/favico.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;0,9..40,800&display=swap" rel="stylesheet">
    @vite(['resources/css/auth.css', 'resources/js/auth.js'])
    @stack('head')
</head>
<body class="auth-page min-h-full bg-white text-gray-800 antialiased">
    <div class="flex min-h-screen flex-col lg:flex-row">
        {{-- Hero panel --}}
        <aside class="relative hidden w-full overflow-hidden bg-linear-to-br from-forest via-forest-mid to-forest-deep lg:flex lg:w-[46%] lg:flex-col lg:justify-between lg:p-10 xl:p-14">
            <div class="pointer-events-none absolute -right-16 top-10 h-64 w-64 rounded-full bg-lime/20 blur-3xl"></div>
            <div class="pointer-events-none absolute -left-10 bottom-20 h-48 w-48 rounded-full bg-white/10 blur-2xl"></div>

            <div class="relative z-10">
                <a href="{{ url('/') }}" class="text-2xl font-bold text-white">{{ $brandName }}</a>
            </div>

            <div class="relative z-10 flex flex-1 items-center justify-center py-10">
                <img src="{{ \App\Support\LandingContent::assetUrl($authImage) }}" alt="" class="max-h-[340px] w-auto animate-auth-float drop-shadow-2xl">
            </div>

            <div class="relative z-10 text-white">
                <span class="inline-block rounded-full border border-lime/30 bg-lime/15 px-3 py-1 text-xs font-bold uppercase tracking-wider text-lime">{{ $heroEyebrow }}</span>
                <h2 class="mt-4 text-3xl font-bold leading-tight xl:text-4xl">{!! $heroTitle !!}</h2>
                <p class="mt-3 max-w-md text-sm leading-relaxed text-white/80">{{ $heroText }}</p>
            </div>
        </aside>

        {{-- Form panel --}}
        <main class="flex flex-1 flex-col">
            <header class="flex items-center justify-between gap-4 px-4 py-4 sm:px-8 lg:px-12">
                <div class="flex min-w-0 items-center gap-3">
                    <a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-forest transition hover:text-lime-hover lg:hidden">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        Home
                    </a>
                    @hasSection('back_url')
                        <a href="@yield('back_url')" class="inline-flex items-center gap-2 text-sm font-semibold text-forest transition hover:text-lime-hover">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                            @yield('back_label', 'Back')
                        </a>
                    @endif
                </div>
                @unless(request()->is('login', 'admin'))
                    <div class="flex shrink-0 items-center gap-2 sm:gap-3">
                        <span class="hidden text-sm font-medium text-gray-600 md:inline">Already have an account?</span>
                        <a href="{{ url('/login') }}" class="auth-btn-header">Log In</a>
                    </div>
                @endunless
            </header>

            <div class="flex flex-1 items-center justify-center px-4 pb-10 sm:px-8 lg:px-12">
                <div class="w-full @yield('content_width', 'max-w-md')">
                    @yield('content')
                </div>
            </div>
        </main>
    </div>

    <script src="{{ asset('users/assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('users/assets/js/jquery.validate.min.js') }}"></script>
    <link href="{{ asset('toasters/toastr.min.css') }}" rel="stylesheet">
    <script src="{{ asset('toasters/toastr.min.js') }}"></script>
    <script>
        toastr.options = { closeButton: true, positionClass: 'toast-top-right', timeOut: 5000 };
        @if(Session::has('success')) toastr.success(@json(Session::get('success'))); @endif
        @if(Session::has('error')) toastr.error(@json(Session::get('error'))); @endif
        @if(Session::has('warning')) toastr.warning(@json(Session::get('warning'))); @endif
        @if(Session::has('info')) toastr.info(@json(Session::get('info'))); @endif
    </script>
    @stack('scripts')
    @include('components.cookie-consent')
</body>
</html>
