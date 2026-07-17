@props([
    'hasHeader'  => true,
    'hasFooter'  => true,
])

<!DOCTYPE html>
<html lang="en" class="light">
<head>
    <!-- Title slot -->
    <title>{{ $title ?? '' }}</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />

    {{-- PrimeVue CSS --}}
    {{-- <link rel="stylesheet" href="https://unpkg.com/primevue@4/resources/themes/aura-light-blue/theme.css" id="theme-link" /> --}}
    <link rel="stylesheet" href="https://unpkg.com/primeicons/primeicons.css" />
    {{-- <link rel="stylesheet" href="https://unpkg.com/primevue/resources/primevue.min.css" /> --}}
    {{-- <link rel="stylesheet" href="https://unpkg.com/primeflex@3/primeflex.css" /> --}}

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
</head>
<body class="h-full bg-ink-50 dark:bg-gray-9000 card text-ink-900 dark:text-ink-50 font-body antialiased dark-app">

<div id="adminVueApp">
    <!-- Flash Message Blade Component -->
    <x-admin::flash-group />

    <!-- Confirm Modal Blade Component -->
    <x-admin::modal.confirm />

    <admin-layout></admin-layout>
</div> 

<script>
    // Register admin vue app.
    window.addEventListener("DOMContentLoaded", function (event) {
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
            currentTheme: document.documentElement.className || 'light',
            notifCount: 3,

            navItems: [
                { icon: 'pi pi-home', label: 'Dashboard', route: '{{ route("admin.dashboard") }}', active: "{{ request()->routeIs('admin.dashboard') }}" },
                { icon: 'pi pi-users', label: 'Users', route: '{{ route("admin.users.index") }}', active: "{{ request()->routeIs('admin.users.index') }}" },
                { icon: 'pi pi-users', label: 'Clients', route: '{{ route("admin.clients.index") }}', active: "{{ request()->routeIs('admin.clients.index') }}" },
                { icon: 'pi pi-building', label: 'Clubs & Branches', route: '{{ route("admin.clubs.index") }}', active: "{{ request()->routeIs('admin.clubs.index') }}" },
                { icon: 'pi pi-map', label: 'Floors', route: '{{ route("admin.floors.index") }}', active: "{{ request()->routeIs('admin.floors.index') }}" },
                { icon: 'pi pi-table', label: 'Tables', route: '{{ route("admin.tables.index") }}', active: "{{ request()->routeIs('admin.tables.index') }}" },
                { icon: 'pi pi-calendar', label: 'Events', route: '{{ route("admin.events.index") }}', active: "{{ request()->routeIs('admin.events.index') }}" },
                { icon: 'pi pi-ticket', label: 'Bookings', route: '{{ route("admin.bookings.index") }}', active: "{{ request()->routeIs('admin.bookings.index') }}" },
                { icon: 'pi pi-credit-card', label: 'Payments', route: '{{ route("admin.payments.index") }}', active: "{{ request()->routeIs('admin.payments.index') }}" },
                { icon: 'pi pi-percentage', label: 'Promo Codes', route: '{{ route("admin.promo_codes.index") }}', active: "{{ request()->routeIs('admin.promo_codes.index') }}" },
                { icon: 'pi pi-bell', label: 'Notifications', route: '{{ route("admin.notifications.index") }}', active: "{{ request()->routeIs('admin.notifications.index') }}" },
                { icon: 'pi pi-star', label: 'Reviews', route: '{{ route("admin.reviews.index") }}', active: "{{ request()->routeIs('admin.reviews.index') }}" },
                { icon: 'pi pi-cog', label: 'Settings', route: '{{ route("admin.settings.index") }}', active: "{{ request()->routeIs('admin.settings.index') }}" },
                { icon: 'pi pi-list', label: 'Audit Logs', route: '{{ route("admin.audit_logs.index") }}', active: "{{ request()->routeIs('admin.audit_logs.index') }}" },
                { icon: 'pi pi-shield', label: 'Roles & Permissions', route: '{{ route("admin.roles.index") }}', active: "{{ request()->routeIs('admin.roles.index') }}" },
                { section: 'Account' },
                { icon: 'pi pi-user', label: 'My Profile', route: '{{ route("admin.profile.index") }}', active: "{{ request()->routeIs('admin.profile.index') }}" },
            ],

            themes: [
                { key: 'light', label: 'Light', icon: 'pi pi-sun' },
                { key: 'dark', label: 'Dark', icon: 'pi pi-moon' },
                { key: 'ocean', label: 'Ocean', icon: 'pi pi-cloud' },
                { key: 'rose', label: 'Rose', icon: 'pi pi-heart' },
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
        const saved = localStorage.getItem('admin-theme') || '{{ session("theme", "light") }}';

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
                <div  class="sidebar-logo">
                    <div class="logo-mark">A</div>
                    <span class="logo-text">AdminPanel</span>
                </div>

                <button class=" lg:hidden absolute top-4 right-4 text-(--sidebar-text)" @click="toggleMobile">
                    <i class="pi pi-times  "></i>
                </button>
            </div>

            <nav class="sidebar-nav">

                <template v-for="item in navItems" :key="item.label || item.section">
                    <div v-if="item.section" class="sidebar-section-label">@{{ item.section }}</div>
                    <a v-else :href="item.route" :class="['nav-item', { active: item.active }]">
                        <i :class="['nav-icon', item.icon]"></i>
                        <span class="nav-label">@{{ item.label }}</span>
                    </a>
                </template>
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
                        :title="t.label"
                    >
                        <i :class="t.icon" style="font-size:13px;"></i>
                    </button>
                </div>  

                <button class="header-icon-btn">
                    <i class="pi pi-bell" style="font-size:14px;"></i>
                    <span class="badge">@{{ notifCount }}</span>
                </button>

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
    window.resolveApi = function (endpoint) {
        "{{ env('BACKEND_URL') }}" + '/' + endpoint.replace(/^\/+/, '');
    };



</script>

<!-- Add dynamic scripts -->
@stack('scripts')
</body>
</html>
