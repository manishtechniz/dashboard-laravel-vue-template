@extends('admin.layouts.app')

@section('title', 'Settings')
@section('page-title', 'Settings')
@section('page-subtitle', 'System configuration and preferences')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Left column --}}
    <div class="lg:col-span-2 space-y-6">

        {{-- General Settings --}}
        <div class="card p-6">
            <h3 class="text-base font-bold mb-5" style="color: var(--color-text)">General Settings</h3>
            <form class="space-y-4" x-data="{}">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider mb-1.5" style="color: var(--color-text-muted)">Application Name</label>
                        <input type="text" value="AdminPanel" class="form-input w-full px-3 py-2.5 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider mb-1.5" style="color: var(--color-text-muted)">Timezone</label>
                        <select class="form-input w-full px-3 py-2.5 text-sm">
                            <option>UTC</option>
                            <option>Asia/Kolkata</option>
                            <option>America/New_York</option>
                            <option>Europe/London</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider mb-1.5" style="color: var(--color-text-muted)">App URL</label>
                    <input type="url" value="http://localhost:8000" class="form-input w-full px-3 py-2.5 text-sm">
                </div>
                <div class="flex items-center justify-between py-3 px-4 rounded-xl" style="background: var(--color-bg); border: 1px solid var(--color-border)">
                    <div>
                        <p class="text-sm font-semibold" style="color: var(--color-text)">Maintenance Mode</p>
                        <p class="text-xs" style="color: var(--color-text-muted)">Take the application offline</p>
                    </div>
                    <div x-data="{ on: false }">
                        <button type="button" @click="on = !on"
                                :style="on ? 'background: #ef4444' : 'background: var(--color-border)'"
                                class="relative w-11 h-6 rounded-full transition-colors">
                            <span :class="on ? 'translate-x-5' : 'translate-x-0.5'"
                                  class="absolute top-0.5 left-0 w-5 h-5 bg-white rounded-full shadow transition-transform"></span>
                        </button>
                    </div>
                </div>
                <div class="pt-2">
                    <button type="button" class="btn-primary px-5 py-2.5 text-sm font-semibold">Save Settings</button>
                </div>
            </form>
        </div>

        {{-- Notification Preferences --}}
        <div class="card p-6">
            <h3 class="text-base font-bold mb-5" style="color: var(--color-text)">Notification Preferences</h3>
            <div class="space-y-3">
                @foreach([
                    ['label' => 'New user registration', 'desc' => 'Get notified when a new user signs up', 'default' => true],
                    ['label' => 'Security alerts', 'desc' => 'Unusual login or activity alerts', 'default' => true],
                    ['label' => 'Weekly digest', 'desc' => 'Summary of activity and stats', 'default' => false],
                    ['label' => 'Theme changes', 'desc' => 'When a user modifies their theme', 'default' => false],
                    ['label' => 'System updates', 'desc' => 'Application and dependency updates', 'default' => true],
                ] as $notif)
                <div class="flex items-center justify-between py-3 px-4 rounded-xl transition-colors"
                     style="border: 1px solid var(--color-border)"
                     x-data="{ on: {{ $notif['default'] ? 'true' : 'false' }} }">
                    <div>
                        <p class="text-sm font-semibold" style="color: var(--color-text)">{{ $notif['label'] }}</p>
                        <p class="text-xs mt-0.5" style="color: var(--color-text-muted)">{{ $notif['desc'] }}</p>
                    </div>
                    <button type="button" @click="on = !on"
                            :style="on ? 'background: var(--color-primary)' : 'background: var(--color-border)'"
                            class="relative w-11 h-6 rounded-full transition-colors shrink-0 ml-4">
                        <span :class="on ? 'translate-x-5' : 'translate-x-0.5'"
                              class="absolute top-0.5 left-0 w-5 h-5 bg-white rounded-full shadow transition-transform"></span>
                    </button>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Cache Management --}}
        <div class="card p-6">
            <h3 class="text-base font-bold mb-1" style="color: var(--color-text)">Cache Management</h3>
            <p class="text-xs mb-5" style="color: var(--color-text-muted)">Theme configurations are cached server-side per user. Clear caches here if settings don't reflect immediately.</p>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                @foreach([
                    ['label' => 'Flush Theme Cache', 'desc' => 'Clears all per-user and global theme caches', 'href' => route('admin.theme.flush'), 'color' => 'btn-primary'],
                    ['label' => 'Clear Config Cache', 'desc' => 'Rebuilds the config cache', 'href' => '#', 'color' => 'btn-secondary'],
                    ['label' => 'Clear All Caches', 'desc' => 'Config, route, view and data caches', 'href' => '#', 'color' => 'btn-secondary'],
                ] as $cache)
                <form method="POST" action="{{ $cache['href'] }}">
                    @csrf
                    <button type="submit"
                            class="{{ $cache['color'] }} w-full text-left p-4 text-sm"
                            style="border-radius: var(--border-radius)">
                        <p class="font-semibold">{{ $cache['label'] }}</p>
                        <p class="text-xs mt-1 opacity-70">{{ $cache['desc'] }}</p>
                    </button>
                </form>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Right column --}}
    <div class="space-y-6">

        {{-- System Info --}}
        <div class="card p-5">
            <h3 class="text-sm font-bold mb-4" style="color: var(--color-text)">System Info</h3>
            <div class="space-y-2.5">
                @foreach([
                    ['label' => 'Laravel', 'val' => app()->version()],
                    ['label' => 'PHP', 'val' => PHP_VERSION],
                    ['label' => 'Environment', 'val' => app()->environment()],
                    ['label' => 'Cache Driver', 'val' => config('cache.default')],
                    ['label' => 'DB Driver', 'val' => config('database.default')],
                    ['label' => 'Debug Mode', 'val' => config('app.debug') ? 'ON' : 'OFF'],
                ] as $info)
                <div class="flex items-center justify-between text-sm">
                    <span style="color: var(--color-text-muted)">{{ $info['label'] }}</span>
                    <span class="font-semibold font-mono text-xs px-2 py-0.5 rounded" style="background: var(--color-bg); color: var(--color-text)">{{ $info['val'] }}</span>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Storage Usage --}}
        <div class="card p-5">
            <h3 class="text-sm font-bold mb-4" style="color: var(--color-text)">Storage Usage</h3>
            <div class="space-y-3">
                @foreach([
                    ['label' => 'Logs', 'used' => 42, 'total' => 500],
                    ['label' => 'Uploads', 'used' => 128, 'total' => 1024],
                    ['label' => 'Cache', 'used' => 15, 'total' => 256],
                ] as $store)
                @php $pct = round(($store['used'] / $store['total']) * 100); @endphp
                <div>
                    <div class="flex justify-between text-xs mb-1">
                        <span style="color: var(--color-text)">{{ $store['label'] }}</span>
                        <span style="color: var(--color-text-muted)">{{ $store['used'] }}MB / {{ $store['total'] }}MB</span>
                    </div>
                    <div class="h-1.5 rounded-full overflow-hidden" style="background: var(--color-border)">
                        <div class="h-full rounded-full"
                             style="width: {{ $pct }}%; background: {{ $pct > 80 ? '#ef4444' : 'var(--color-primary)' }}; transition: width 0.8s ease"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Quick Links --}}
        <div class="card p-5">
            <h3 class="text-sm font-bold mb-4" style="color: var(--color-text)">Quick Links</h3>
            <div class="space-y-1.5">
                @foreach([
                    ['href' => route('admin.users.index'), 'label' => 'Manage Users'],
                    ['href' => route('admin.theme.index'), 'label' => 'Theme Settings'],
                    ['href' => route('admin.profile'), 'label' => 'My Profile'],
                ] as $link)
                <a href="{{ $link['href'] }}"
                   class="flex items-center justify-between px-3 py-2.5 rounded-lg text-sm font-medium transition-colors"
                   style="color: var(--color-text)"
                   onmouseover="this.style.background='color-mix(in srgb, var(--color-primary) 8%, transparent)'"
                   onmouseout="this.style.background=''">
                    {{ $link['label'] }}
                    <svg class="w-4 h-4 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
