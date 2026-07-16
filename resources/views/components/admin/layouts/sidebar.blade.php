<aside class="w-64 flex-shrink-0 flex flex-col bg-ink-950 dark:bg-black border-r border-ink-800 overflow-y-auto">

            {{-- Logo --}}
            <div class="h-16 flex items-center px-6 border-b border-ink-800">
                <div class="flex items-center gap-3">
                    <div class="w-7 h-7 bg-accent rounded-sm flex items-center justify-center">
                        <svg class="w-4 h-4 text-ink-950" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                        </svg>
                    </div>
                    <span class="font-display font-700 text-lg text-white tracking-tight">AdminOS</span>
                </div>
            </div>

            {{-- Nav --}}
            <nav class="flex-1 px-3 py-5 space-y-0.5">
                <p class="px-3 mb-2 text-[10px] font-mono font-500 text-ink-500 uppercase tracking-widest">Main</p>

                <a href="{{ route('admin.dashboard') }}"
                   class="relative flex items-center gap-3 px-3 py-2.5 rounded-md text-sm font-500 group
                          {{ request()->routeIs('admin.dashboard') ? 'nav-item-active bg-ink-800 text-white' : 'text-ink-400 hover:bg-ink-800 hover:text-white' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Dashboard
                </a>

                <a href="{{ route('admin.users.index') }}"
                   class="relative flex items-center gap-3 px-3 py-2.5 rounded-md text-sm font-500 group
                          {{ request()->routeIs('admin.users.*') ? 'nav-item-active bg-ink-800 text-white' : 'text-ink-400 hover:bg-ink-800 hover:text-white' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Users
                    <span class="ml-auto font-mono text-[11px] bg-ink-700 text-ink-300 px-1.5 py-0.5 rounded">{{ \App\Models\User::count() }}</span>
                </a>

                <a href="{{ route('admin.profile') }}"
                   class="relative flex items-center gap-3 px-3 py-2.5 rounded-md text-sm font-500 group
                          {{ request()->routeIs('admin.profile') ? 'nav-item-active bg-ink-800 text-white' : 'text-ink-400 hover:bg-ink-800 hover:text-white' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Profile
                </a>

                <div class="pt-4">
                    <p class="px-3 mb-2 text-[10px] font-mono font-500 text-ink-500 uppercase tracking-widest">System</p>
                </div>

                <a href="#" class="relative flex items-center gap-3 px-3 py-2.5 rounded-md text-sm font-500 text-ink-400 hover:bg-ink-800 hover:text-white">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Settings
                </a>
            </nav>

            {{-- User footer --}}
            <div class="p-3 border-t border-ink-800">
                <div class="flex items-center gap-3 px-3 py-2.5 rounded-md hover:bg-ink-800 cursor-pointer group">
                    <div class="w-7 h-7 rounded-full bg-accent flex items-center justify-center flex-shrink-0">
                        <span class="text-[11px] font-display font-700 text-ink-950">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-500 text-white truncate">{{ auth()->user()->name }}</p>
                        <p class="text-[10px] font-mono text-ink-500 truncate">{{ auth()->user()->email }}</p>
                    </div>
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit" class="text-ink-600 hover:text-red-400 transition-colors" title="Logout">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </aside>
