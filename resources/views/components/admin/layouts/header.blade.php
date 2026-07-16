<header
    class="h-16 flex items-center justify-between px-6 bg-white dark:bg-ink-900 border-b border-ink-100 dark:border-ink-800 flex-shrink-0">
    <div>
        <h1 class="font-display font-700 text-lg text-ink-900 dark:text-white">@yield('title', 'Dashboard')</h1>
        <p class="text-xs font-mono text-ink-400">@yield('subtitle', 'Welcome back, ' . auth()->user()->name)</p>
    </div>

    <div class="flex items-center gap-3">
        {{-- Date badge --}}
        <span
            class="hidden sm:flex font-mono text-xs text-ink-400 dark:text-ink-500 bg-ink-50 dark:bg-ink-800 border border-ink-100 dark:border-ink-700 px-3 py-1.5 rounded-md">
            {{ now()->format('D, d M Y') }}
        </span>

        {{-- Dark mode toggle (Vue powered) --}}
        {{-- <v-dark-toggle></v-dark-toggle> --}}

        {{-- Notification bell --}}
        <button
            class="relative w-9 h-9 flex items-center justify-center rounded-lg border border-ink-100 dark:border-ink-700 bg-white dark:bg-ink-800 hover:border-ink-300 dark:hover:border-ink-600 transition-colors">
            <svg class="w-4 h-4 text-ink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
            <span class="absolute top-1.5 right-1.5 w-1.5 h-1.5 bg-accent rounded-full animate-pulse-dot"></span>
        </button>
    </div>
</header>
{{--
<script type="text/x-template" id="v-dark-toggle-template">
    <button
        @click="toggle"
        class="w-9 h-9 flex items-center justify-center rounded-lg border border-ink-100 dark:border-ink-700 bg-white dark:bg-ink-800 hover:border-ink-300 dark:hover:border-ink-600 transition-colors"
        :title="dark ? 'Switch to light mode' : 'Switch to dark mode'"
    >
        <svg v-if="!dark" class="w-4 h-4 text-ink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
        </svg>
        <svg v-else class="w-4 h-4 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
        </svg>
    </button>
</script>

<script>
    adminVueApp.component('v-dark-toggle', {
        template: '#v-dark-toggle-template',
        data() {
            return {
                dark: document.documentElement.classList.contains('dark') ||
                    localStorage.getItem('adminDark') === 'true'
            };
        },
        mounted() {
            if (this.dark) document.documentElement.classList.add('dark');
        },
        methods: {
            toggle() {
                this.dark = !this.dark;
                document.documentElement.classList.toggle('dark', this.dark);
                localStorage.setItem('adminDark', this.dark);
            }
        }
    });
</script> --}}
