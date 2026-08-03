<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Title slot -->
    <title>{{ $title ?? '' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&family=Poppins:wght@300;400;500;600;700&family=DM+Sans:wght@300;400;500;600;700&family=Sora:wght@300;400;600;700&family=Outfit:wght@300;400;500;600;700&family=Space+Grotesk:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&family=DM+Mono:wght@400;500&display=swap"
        rel="stylesheet" />

    {{-- PrimeVue CSS --}}
    <!-- <link rel="stylesheet" href="https://unpkg.com/primevue@4/resources/themes/aura-light-blue/theme.css"
        id="theme-link" /> -->
    <link rel="stylesheet" href="https://unpkg.com/primeicons/primeicons.css" />
    <link rel="stylesheet" href="https://unpkg.com/primeflex@3/primeflex.css" />

    <!-- Link js files -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Add dynamic css -->
    @stack('styles')
</head>

<body>
    <div id="adminVueApp">
        <!-- Flash Message Blade Component -->
        <x-admin::flash-group />

        {{ $slot }}
    </div>

    <script>
        // Register admin vue app.
        window.addEventListener("DOMContentLoaded", function(event) {
            adminVueApp.mount("#adminVueApp");
        });
    </script>


    <script>
        window.resolveApi = function(endpoint) {
            return "{{ env('BACKEND_URL') }}" + '/' + endpoint.replace(/^\/+/, '');
        };
    </script>

    <!-- Add dynamic css -->
    @stack('scripts')

</body>

</html>