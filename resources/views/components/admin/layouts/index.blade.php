@props([
'hasHeader' => true,
'hasFooter' => true,
])

<!DOCTYPE html>
<html lang="en" class="dark">

<head>
    <!-- Title slot -->
    <title>{{ $title ?? '' }}</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Link the PWA Manifest -->
    <!-- <link rel="manifest" href="{{ asset('web.dev/manifest.json') }}">
    <meta name="theme-color" content="#4f46e5"> -->

    {{-- Optimize Laravel Vite CSS --}}
    @php
    // Tells Laravel Vite to apply these attributes to the generated
    Vite::useStyleTagAttributes([
    'media' => 'print',
    'onload' => "this.media='all'"
    ]);
    @endphp

    <!-- Link js files -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

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

    <!-- Add dynamic css -->
    @stack('styles')

    <style>
        [v-cloak] {
            display: none !important;
        }

        #initial-loader {
            display: none;
        }

        #adminVueApp[v-cloak]+#initial-loader {
            display: flex;
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background-color: #f8fafc;
            z-index: 99999;
            justify-content: center;
            align-items: center;
        }

        html.dark #adminVueApp[v-cloak]+#initial-loader {
            background-color: #111827;
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

        html.dark .loader-spinner {
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

<body class="h-full bg-ink-50 dark:bg-gray-9000 card text-ink-900 dark:text-ink-50 font-body antialiased dark-app">

    <div id="adminVueApp" v-cloak>
        <!-- Flash Message Blade Component -->
        <x-admin::flash-group />

        <!-- Confirm Modal Blade Component -->
        <x-admin::modal.confirm />

        <admin-layout></admin-layout>
    </div>

    <div id="initial-loader">
        <div class="loader-spinner"></div>
    </div>
    <script>
        // Register admin vue app.
        window.addEventListener("DOMContentLoaded", function(event) {
            adminVueApp.mount("#adminVueApp");
        });
    </script>

    <script type="module">
        adminVueApp.component('admin-layout', {
            template: '#admin-layout-template',

            data() {
                return {
                    sidebarCollapsed: false,
                    mobileSidebarOpen: false,
                    currentTheme: document.documentElement.className || 'dark',
                    notifCount: 3,

                    navItems: @json($adminMenu ?? []),

                    themes: [
                        // {
                        //     key: 'light',
                        //     label: 'Light',
                        //     icon: 'pi pi-sun'
                        // },
                        {
                            key: 'dark',
                            label: 'Dark',
                            icon: 'pi pi-moon'
                        },
                        {
                            key: 'ocean',
                            label: 'Ocean',
                            icon: 'pi pi-cloud'
                        },
                        // {
                        //     key: 'rose',
                        //     label: 'Rose',
                        //     icon: 'pi pi-heart'
                        // },
                    ],
                };
            },

            methods: {
                applyTheme(theme) {
                    document.documentElement.className = theme;
                    this.currentTheme = theme;

                    localStorage.setItem('admin-theme', theme);
                },

                toggleSidebar() {
                    this.sidebarCollapsed = !this.sidebarCollapsed;
                },

                toggleMobile() {
                    this.mobileSidebarOpen = !this.mobileSidebarOpen;
                }
            },

            mounted() {
                const saved = localStorage.getItem('admin-theme') || '{{ session("theme", "dark") }}';

                this.applyTheme(saved);
            }
        });
    </script>

    <script type="text/x-template" id="admin-layout-template">
        {{-- <Toast /> --}}

        <div class="admin-shell">
            {{-- Sidebar --}}
            <aside :class="['admin-sidebar', { collapsed: sidebarCollapsed, 'mobile-open': mobileSidebarOpen }]">
                <div class="relative">
                    <div class="sidebar-logo">
                        <div class="logo-mark">A</div>
                        <span class="logo-text">AdminPanel</span>
                    </div>

                    <button class=" lg:hidden absolute top-4 right-4 text-(--sidebar-text)" @click="toggleMobile">
                        <i class="pi pi-times  "></i>
                    </button>
                </div>

                <nav class="sidebar-nav">
                    <!-- @{{ navItems }} -->
                    <template v-for="item in navItems" :key="item.label || item.section">
                        <div v-if="item.section" class="sidebar-section-label">@{{ item.section }}</div>
                        <a v-else-if="item.visibility !== 'hidden'" :href="item.url" :class="['nav-item', { active: item.route =='{{ request()->route()->getName() }}' }]">
                            <i :class="['nav-icon', item.icon]"></i>
                            <span class="nav-label">@{{ item.label }}</span>
                        </a>
                    </template>

                    @if (hasPermission('admin.profile.index'))
                    <div class="sidebar-section-label">Account</div>

                    <a href="{{ route("admin.profile.index") }}" class="nav-item {{ request()->routeIs('admin.profile.index') ? 'active' : '' }}">
                        <i class="pi pi-user nav-icon"></i>
                        <span class="nav-label">My Profile</span>
                    </a>
                    @endif
                </nav>

                <div class="sidebar-footer">
                    <div class="nav-item" style="cursor:default; opacity:0.6;" v-if="!sidebarCollapsed">
                        <i class="nav-icon pi pi-circle-fill" style="color: #22c55e; font-size:8px;"></i>
                        <span class="nav-label" style="font-size:12px;">v1.0.0 · Online</span>
                    </div>
                </div>
            </aside>

            {{-- Main --}}
            <div :class="['admin-main', { 'sidebar-collapsed': sidebarCollapsed }]" class="overflow-x-hidden">
                {{-- Header --}}
                <header class="admin-header">
                    <button class="header-toggle-btn hidden lg:block" @click="toggleSidebar">
                        <i class="pi pi-bars"></i>
                    </button>

                    <button class="header-toggle-btn lg:hidden" @click="toggleMobile">
                        <i class="pi pi-bars"></i>
                    </button>

                    <div class="header-search">
                        <i class="pi pi-search" style="color: var(--text-muted); font-size:13px;"></i>
                        <input type="text" placeholder="Search anything..." />
                    </div>

                    <div class="header-spacer"></div>

                    {{-- Theme Switcher --}}
                    <div style="display:flex; gap:4px;">
                        <button
                            v-for="t in themes"
                            :key="t.key"
                            class="header-icon-btn"
                            :style="currentTheme === t.key ? 'background: var(--accent-light); color: var(--accent); border-color: var(--accent)' : ''"
                            @click="applyTheme(t.key)"
                            :title="t.label">
                            <i :class="t.icon" style="font-size:13px;"></i>
                        </button>
                    </div>

                    <!-- <button class="header-icon-btn">
                        <i class="pi pi-bell" style="font-size:14px;"></i>
                        <span class="badge">@{{ notifCount }}</span>
                    </button> -->

                    <div class="avatar" title="My Profile">AU</div>
                </header>

                {{-- Content Slot --}}
                <main class="sadmin-content p-3 md:p-7 ">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </script>

    <script>
        window.resolveApi = function(endpoint) {
            "{{ env('BACKEND_URL') }}" + '/' + endpoint.replace(/^\/+/, '');
        };
    </script>

    <!-- Add dynamic scripts -->
    @stack('scripts')

    <!-- <script>
        if ('serviceWorker' in navigator) {
            console.log(1);
            window.addEventListener('load', () => {
                console.log(2);
                navigator.serviceWorker.register("{{ asset('sw.js') }}");
            });
        }
    </script> -->
</body>

</html>