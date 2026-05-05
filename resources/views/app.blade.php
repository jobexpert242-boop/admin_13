<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

    @php
        $site = \App\Models\SiteSetting::first();
    @endphp

    @if ($site && $site->favaicon)
        <link rel="icon" type="image/png" href="{{ asset('storage/' . $site->favaicon) }}">
    @else
        <link rel="shortcut icon" href="{{ asset('/storage/images/logo.webp') }}" type="image/x-icon">
    @endif

    @routes
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @inertiaHead
</head>

<body>
    @inertia
</body>

</html>
