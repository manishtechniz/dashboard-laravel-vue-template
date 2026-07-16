@extends('admin.layouts.app')

@section('title', 'Theme Management')
@section('page-title', 'Theme Management')
@section('page-subtitle', 'Customize colors, fonts and layout for global or per-user themes')

@section('content')
<div x-data="themeManager()" class="space-y-6">

    {{-- Tabs --}}
    <div class="flex gap-1 p-1 rounded-xl max-w-xs" style="background: var(--color-surface)">
        <button @click="activeTab = 'global'"
                :style="activeTab === 'global' ? 'background: var(--color-primary); color: white' : 'color: var(--color-text-muted)'"
                class="flex-1 text-xs font-semibold py-2.5 rounded-lg transition-all">Global Default</button>
        <button @click="activeTab = 'users'"
                :style="activeTab === 'users' ? 'background: var(--color-primary); color: white' : 'color: var(--color-text-muted)'"
                class="flex-1 text-xs font-semibold py-2.5 rounded-lg transition-all">Per User</button>
    </div>

    {{-- ======= GLOBAL THEME TAB ======= --}}
    <div x-show="activeTab === 'global'" x-transition>
        <form method="POST" action="{{ route('admin.theme.global') }}">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- Presets --}}
                <div class="card p-6 lg:col-span-3">
                    <h3 class="text-sm font-bold mb-4" style="color: var(--color-text)">Theme Presets</h3>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-3">
                        @foreach($presets as $key => $preset)
                        <button type="button"
                                @click="applyPreset({{ json_encode($preset) }})"
                                class="group relative p-3 rounded-xl border-2 transition-all hover:scale-105"
                                :class="selectedPreset === '{{ $key }}' ? 'border-blue-500' : ''"
                                style="border-color: var(--color-border)">
                            <div class="w-full aspect-square rounded-lg mb-2 flex items-end gap-0.5 p-1 overflow-hidden"
                                 style="background: {{ $preset['background_color'] ?? '#ffffff' }}">
                                <div class="flex-1 rounded h-1/2" style="background: {{ $preset['sidebar_color'] }}"></div>
                                <div class="flex-[3] rounded h-1/3" style="background: {{ $preset['primary_color'] }}"></div>
                            </div>
                            <p class="text-xs font-semibold text-center" style="color: var(--color-text)">{{ $preset['label'] }}</p>
                        </button>
                        @endforeach
                    </div>
                </div>

                {{-- Colors --}}
                <div class="card p-6 lg:col-span-2">
                    <h3 class="text-sm font-bold mb-5" style="color: var(--color-text)">Color Palette</h3>
                    <div class="grid grid-cols-2 gap-4">
                        @foreach([
                            ['key' => 'primary_color',    'label' => 'Primary'],
                            ['key' => 'secondary_color',  'label' => 'Secondary'],
                            ['key' => 'accent_color',     'label' => 'Accent'],
                            ['key' => 'background_color', 'label' => 'Background'],
                            ['key' => 'surface_color',    'label' => 'Surface'],
                            ['key' => 'sidebar_color',    'label' => 'Sidebar'],
                            ['key' => 'sidebar_text',     'label' => 'Sidebar Text'],
                            ['key' => 'text_color',       'label' => 'Text'],
                            ['key' => 'text_muted',       'label' => 'Muted Text'],
                            ['key' => 'border_color',     'label' => 'Border'],
                        ] as $field)
                        <div class="flex items-center gap-3">
                            <div class="relative shrink-0">
                                <input type="color"
                                       name="{{ $field['key'] }}"
                                       x-model="theme.{{ $field['key'] }}"
                                       class="w-10 h-10 cursor-pointer rounded-lg border-2 p-0.5"
                                       style="border-color: var(--color-border)"
                                       value="{{ $globalTheme[$field['key']] ?? '#000000' }}">
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs font-semibold" style="color: var(--color-text)">{{ $field['label'] }}</p>
                                <p class="text-xs font-mono" style="color: var(--color-text-muted)" x-text="theme.{{ $field['key'] }}"></p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Typography + Layout + Preview --}}
                <div class="space-y-4">
                    {{-- Typography --}}
                    <div class="card p-5">
                        <h3 class="text-sm font-bold mb-4" style="color: var(--color-text)">Typography</h3>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-xs font-semibold mb-1.5" style="color: var(--color-text-muted)">Font Family</label>
                                <select name="font_family" x-model="theme.font_family" class="form-input w-full px-3 py-2 text-sm">
                                    @foreach(['Plus Jakarta Sans', 'Inter', 'Poppins', 'DM Sans', 'Sora', 'Outfit', 'Space Grotesk'] as $font)
                                    <option value="{{ $font }}" {{ ($globalTheme['font_family'] ?? '') === $font ? 'selected' : '' }}>{{ $font }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold mb-1.5" style="color: var(--color-text-muted)">Border Radius</label>
                                <select name="border_radius" x-model="theme.border_radius" class="form-input w-full px-3 py-2 text-sm">
                                    @foreach(['0rem' => 'Sharp', '0.25rem' => 'Slight', '0.5rem' => 'Medium', '0.75rem' => 'Large', '1rem' => 'Extra Large', '1.5rem' => 'Pill'] as $val => $lbl)
                                    <option value="{{ $val }}" {{ ($globalTheme['border_radius'] ?? '') === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Layout Options --}}
                    <div class="card p-5">
                        <h3 class="text-sm font-bold mb-4" style="color: var(--color-text)">Layout</h3>
                        <div class="space-y-3">
                            <label class="flex items-center justify-between">
                                <span class="text-sm" style="color: var(--color-text)">Dark Mode</span>
                                <button type="button" @click="theme.dark_mode = !theme.dark_mode"
                                        :style="theme.dark_mode ? 'background: var(--color-primary)' : 'background: var(--color-border)'"
                                        class="relative w-11 h-6 rounded-full transition-colors">
                                    <input type="hidden" name="dark_mode" :value="theme.dark_mode ? 1 : 0">
                                    <span :class="theme.dark_mode ? 'translate-x-5' : 'translate-x-0.5'"
                                          class="absolute top-0.5 left-0 w-5 h-5 bg-white rounded-full shadow transition-transform"></span>
                                </button>
                            </label>
                            <label class="flex items-center justify-between">
                                <span class="text-sm" style="color: var(--color-text)">Compact Sidebar</span>
                                <button type="button" @click="theme.compact_sidebar = !theme.compact_sidebar"
                                        :style="theme.compact_sidebar ? 'background: var(--color-primary)' : 'background: var(--color-border)'"
                                        class="relative w-11 h-6 rounded-full transition-colors">
                                    <input type="hidden" name="compact_sidebar" :value="theme.compact_sidebar ? 1 : 0">
                                    <span :class="theme.compact_sidebar ? 'translate-x-5' : 'translate-x-0.5'"
                                          class="absolute top-0.5 left-0 w-5 h-5 bg-white rounded-full shadow transition-transform"></span>
                                </button>
                            </label>
                            <div>
                                <label class="block text-xs font-semibold mb-1.5" style="color: var(--color-text-muted)">Sidebar Position</label>
                                <select name="sidebar_position" x-model="theme.sidebar_position" class="form-input w-full px-3 py-2 text-sm">
                                    <option value="left">Left</option>
                                    <option value="right">Right</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Live Preview Card --}}
                    <div class="card p-4 overflow-hidden">
                        <p class="text-xs font-bold uppercase tracking-wider mb-3" style="color: var(--color-text-muted)">Live Preview</p>
                        <div class="rounded-lg overflow-hidden text-xs" style="border: 1px solid var(--color-border)">
                            <div class="flex">
                                <div class="w-12 p-2 space-y-1.5" :style="`background: ${theme.sidebar_color}`">
                                    <div class="w-full h-1.5 rounded opacity-70" style="background: rgba(255,255,255,0.5)"></div>
                                    <div class="w-full h-1.5 rounded" :style="`background: ${theme.primary_color}`"></div>
                                    <div class="w-full h-1.5 rounded opacity-40" style="background: rgba(255,255,255,0.5)"></div>
                                    <div class="w-full h-1.5 rounded opacity-40" style="background: rgba(255,255,255,0.5)"></div>
                                </div>
                                <div class="flex-1 p-2" :style="`background: ${theme.background_color}`">
                                    <div class="flex gap-1 mb-2">
                                        <div class="flex-1 h-3 rounded-sm" :style="`background: ${theme.primary_color}`"></div>
                                        <div class="flex-1 h-3 rounded-sm" :style="`background: ${theme.accent_color}; opacity: 0.6`"></div>
                                        <div class="flex-1 h-3 rounded-sm" :style="`background: ${theme.surface_color}; border: 1px solid ${theme.border_color}`"></div>
                                    </div>
                                    <div class="h-8 rounded-sm" :style="`background: ${theme.surface_color}; border: 1px solid ${theme.border_color}`"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="lg:col-span-3 flex items-center gap-3">
                    <button type="submit" class="btn-primary px-6 py-2.5 text-sm font-semibold">
                        Save Global Theme & Clear Cache
                    </button>
                    <form method="POST" action="{{ route('admin.theme.flush') }}" class="inline">
                        @csrf
                        <button type="submit" class="btn-secondary px-4 py-2.5 text-sm font-semibold">
                            Flush Cache Only
                        </button>
                    </form>
                    <span class="text-xs" style="color: var(--color-text-muted)">
                        Changes apply globally to all users without a custom theme
                    </span>
                </div>
            </div>
        </form>
    </div>

    {{-- ======= PER-USER TAB ======= --}}
    <div x-show="activeTab === 'users'" x-transition>
        <div class="card overflow-hidden">
            <div class="p-6 pb-4">
                <h3 class="text-base font-bold" style="color: var(--color-text)">Per-User Theme Overrides</h3>
                <p class="text-xs mt-1" style="color: var(--color-text-muted)">Users with custom themes override the global default. Cache is per-user and cleared on save.</p>
            </div>
            <table class="w-full data-table">
                <thead>
                    <tr>
                        <th class="text-left">User</th>
                        <th class="text-left hidden md:table-cell">Custom Theme</th>
                        <th class="text-left hidden lg:table-cell">Primary Color</th>
                        <th class="text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>
                            <div class="flex items-center gap-3">
                                <img src="{{ $user->avatar_url }}" class="w-8 h-8 rounded-full object-cover">
                                <div>
                                    <p class="text-sm font-semibold" style="color: var(--color-text)">{{ $user->name }}</p>
                                    <p class="text-xs" style="color: var(--color-text-muted)">{{ $user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="hidden md:table-cell">
                            @if($user->theme_config)
                            <span class="badge bg-blue-100 text-blue-700">Custom</span>
                            @else
                            <span class="badge bg-gray-100 text-gray-500">Global Default</span>
                            @endif
                        </td>
                        <td class="hidden lg:table-cell">
                            @if($user->theme_config && isset($user->theme_config['primary_color']))
                            <div class="flex items-center gap-2">
                                <div class="w-5 h-5 rounded-full border" style="background: {{ $user->theme_config['primary_color'] }}; border-color: var(--color-border)"></div>
                                <span class="text-xs font-mono" style="color: var(--color-text-muted)">{{ $user->theme_config['primary_color'] }}</span>
                            </div>
                            @else
                            <span class="text-xs" style="color: var(--color-text-muted)">—</span>
                            @endif
                        </td>
                        <td>
                            <div class="flex items-center gap-2">
                                <button @click="editUser({{ $user->id }}, '{{ $user->name }}', {{ json_encode($user->theme_config ?? []) }})"
                                        class="btn-primary px-3 py-1.5 text-xs font-semibold">
                                    Edit Theme
                                </button>
                                @if($user->theme_config)
                                <form method="POST" action="{{ route('admin.theme.user.reset', $user) }}"
                                      onsubmit="return confirm('Reset {{ $user->name }}\'s theme to global default?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-secondary px-3 py-1.5 text-xs font-semibold text-red-500">Reset</button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- User Theme Edit Modal --}}
        <div x-show="editingUser" x-transition
             class="fixed inset-0 z-50 flex items-center justify-center px-4">
            <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="editingUser = null"></div>
            <div class="relative card max-w-md w-full p-6 shadow-2xl z-10"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100">
                <div class="flex items-center justify-between mb-5">
                    <h4 class="font-bold" style="color: var(--color-text)">
                        Theme for <span x-text="editingUser?.name" style="color: var(--color-primary)"></span>
                    </h4>
                    <button @click="editingUser = null" class="p-1 btn-secondary rounded-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <template x-if="editingUser">
                    <form :action="`/admin/theme/user/${editingUser.id}`" method="POST">
                        @csrf
                        <div class="grid grid-cols-2 gap-3 mb-4">
                            @foreach(['primary_color' => 'Primary', 'accent_color' => 'Accent', 'sidebar_color' => 'Sidebar', 'background_color' => 'Background'] as $key => $lbl)
                            <div class="flex items-center gap-2">
                                <input type="color" :name="'{{ $key }}'" :value="editingUser.theme?.{{ $key }} || globalTheme.{{ $key }}"
                                       class="w-10 h-10 cursor-pointer rounded-lg p-0.5" style="border: 2px solid var(--color-border)">
                                <span class="text-sm" style="color: var(--color-text)">{{ $lbl }}</span>
                            </div>
                            @endforeach
                        </div>

                        {{-- Hidden fields for required values --}}
                        @foreach(['secondary_color','surface_color','sidebar_text','text_color','text_muted','border_color'] as $hf)
                        <input type="hidden" name="{{ $hf }}" :value="editingUser.theme?.{{ $hf }} || globalTheme.{{ $hf }}">
                        @endforeach
                        <input type="hidden" name="font_family" :value="editingUser.theme?.font_family || globalTheme.font_family || 'Plus Jakarta Sans'">
                        <input type="hidden" name="border_radius" :value="editingUser.theme?.border_radius || globalTheme.border_radius || '0.5rem'">
                        <input type="hidden" name="dark_mode" :value="editingUser.theme?.dark_mode ? 1 : 0">
                        <input type="hidden" name="compact_sidebar" :value="editingUser.theme?.compact_sidebar ? 1 : 0">
                        <input type="hidden" name="sidebar_position" :value="editingUser.theme?.sidebar_position || 'left'">

                        <button type="submit" class="w-full btn-primary py-2.5 text-sm font-semibold">
                            Save User Theme
                        </button>
                    </form>
                </template>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function themeManager() {
    const globalTheme = @json($globalTheme);
    return {
        activeTab: 'global',
        editingUser: null,
        selectedPreset: null,
        theme: { ...globalTheme },
        globalTheme,

        applyPreset(preset) {
            this.theme = { ...this.theme, ...preset };
        },

        editUser(id, name, config) {
            this.editingUser = { id, name, theme: { ...globalTheme, ...(config || {}) } };
        }
    }
}
</script>
@endpush
