<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ $brand['name'] }} — A community exchange platform for services, products, and trusted connections across borders.">
    <title>{{ $brand['name'] }} — Community Exchange Platform</title>
    <link rel="icon" type="image/png" sizes="24x24" href="{{ asset('uploads/system_image/favico.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;0,9..40,800&family=Fraunces:opsz,wght@9..144,600;9..144,700&display=swap" rel="stylesheet">
    @vite(['resources/css/landing.css', 'resources/js/landing.js'])
</head>
<body class="landing-page bg-white text-gray-800 antialiased overflow-x-hidden">
    @yield('content')
    @include('components.cookie-consent')
</body>
</html>
