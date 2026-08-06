<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Title slot -->
    <title>{{ $title ?? '' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php
    // Tells Laravel Vite to apply these attributes to the generated
    Vite::useStyleTagAttributes([
    'media' => 'print',
    'onload' => "this.media='all'"
    ]);
    @endphp

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