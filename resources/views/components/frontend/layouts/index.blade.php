@props([
'hasHeader' => true,
'hasFooter' => true,
'hasSidebar' => true,
'title' => 'The Midnight Club | Premier Nightlife Destination',
'description' => 'Reserve your spot at Gurugram’s most exclusive clubs, high-energy dance floors, and rooftop lounges. Experience ultimate VIP nightlife at The Midnight Club.',
'keywords' => 'nightclub, gurgaon, midnight club, party, vip table, bottle service, nightlife',
'ogImage' => logo()
])

<!DOCTYPE html>
<html lang="en" class="dark">

<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <meta name="description" content="{{ $description }}">
    <meta name="keywords" content="{{ $keywords }}">
    <meta name="robots" content="index, follow">

    <!-- Canonical URL -->
    <link rel="canonical" href="{{ url()->current() }}" />

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ $title }}">
    <meta property="og:description" content="{{ $description }}">
    <meta property="og:image" content="{{ $ogImage }}">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="{{ $title }}">
    <meta property="twitter:description" content="{{ $description }}">
    <meta property="twitter:image" content="{{ $ogImage }}">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/x-icon" href="{{ Vite::asset('resources/images/icon.ico') }}">

    <!-- Add Schema Markup Scripts -->
    @stack('schema_scripts')

    {{-- Optimize Laravel Vite CSS --}}
    @php
    // Tells Laravel Vite to apply these attributes to the generated
    Vite::useStyleTagAttributes([
    'media' => 'print',
    'onload' => "this.media='all'"
    ]);
    @endphp

    <!-- Link js files -->
    @vite(['resources/frontend/css/app.css', 'resources/frontend/js/app.js'])

    {{-- Dynamic Theme CSS (per-user, server-generated) --}}
    <style id="theme-vars">
        :root {
            --font-family: 'DM Sans', sans-serif;
            --font-mono: 'DM Mono', monospace;
            --sidebar-width: 260px;
            --header-height: 64px;
            --radius: 10px;
        }
    </style>

    <!-- QRCode Generator CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <!-- FontAwesome 6 Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Add dynamic css -->
    @stack('styles')

    <style>
        body {
            background-color: #0b0c10;
            font-family: 'Inter', sans-serif;
        }

        .gradient-text {
            background: linear-gradient(90deg, #ec4899, #a855f7);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .glow {
            box-shadow: 0 0 25px rgba(168, 85, 247, 0.3);
        }

        [v-cloak] {
            display: none !important;
        }

        #initial-loader {
            display: none;
        }

        body #frontendVueApp[v-cloak]+#initial-loader {
            /* background-color: #111827; */
            /* gray-900 */
        }

        .loader-spinner {
            width: 50px;
            height: 50px;
            border: 4px solid #e2e8f0;
            border-radius: 50%;
            border-top-color: #3b82f6;
            /* blue-500 */
            animation: loader-spin 1s linear infinite;
        }

        body .loader-spinner {
            border-color: #374151;
            border-top-color: #3b82f6;
        }

        @keyframes loader-spin {
            to {
                transform: rotate(360deg);
            }
        }
    </style>
</head>

<body class="text-gray-100 antialiased">

    <div id="frontendVueApp" v-cloak>
        <frontend-layout></frontend-layout>
    </div>

    <div id="initial-loader">
        <div class="loader-spinner"></div>
    </div>

    <script>
        // Register admin vue app.
        window.addEventListener("DOMContentLoaded", function(event) {
            frontendVueApp.mount("#frontendVueApp");
        });
    </script>

    <script type="module">
        frontendVueApp.component('frontend-layout', {
            template: '#frontend-layout-template',

            data() {
                return {
                    mobileMenuOpen: false
                }
            },

            methods: {

            },

            mounted() {}
        });
    </script>

    <script type="text/x-template" id="frontend-layout-template">
        <x-frontend::layouts.header /> 
        <main class="pt-32">
            {{ $slot }}
        </main>
        <x-frontend::layouts.footer />
    </script>

    <script>
        function toggleAudioVibe() {
            isAudioVibeActive = !isAudioVibeActive;
            const icon = document.getElementById('sound-icon');
            const bpm = document.getElementById('bpm-indicator');
            const eqBars = document.querySelectorAll('.eq-bar');

            if (isAudioVibeActive) {
                icon.className = 'fas fa-volume-high text-[11px] text-neon-pink ml-1';
                bpm.textContent = '138 BPM';
                eqBars.forEach(bar => bar.style.animationPlayState = 'running');
            } else {
                icon.className = 'fas fa-volume-xmark text-[11px] text-gray-500 ml-1';
                bpm.textContent = 'MUTED';
                eqBars.forEach(bar => bar.style.animationPlayState = 'paused');
            }
        }
    </script>

    <!-- Add dynamic scripts -->
    @stack('scripts')

</body>

</html>