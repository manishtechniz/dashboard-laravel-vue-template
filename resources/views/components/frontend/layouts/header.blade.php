<!-- NOTCHBUDDY-STYLE FLOATING ISLAND NAV -->
<header class="fixed top-4 inset-x-0 flex justify-center px-4 z-40">
    <div class="notch-capsule rounded-full px-5 py-2.5 flex items-center justify-between gap-4 max-w-5xl w-full">

        <!-- Logo -->
        <a href="{{ route('frontend.home') }}" class="flex items-center gap-3 group">
            <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-pink-500 to-purple-600 p-[1.5px] shadow-[0_0_15px_rgba(236,72,153,0.5)]">
                <div class="w-full h-full bg-midnight rounded-full flex items-center justify-center">
                    <i class="fas fa-compact-disc text-xs text-pink-500 group-hover:rotate-180 transition-transform duration-700"></i>
                </div>
            </div>
            <span class="font-syne text-xs font-black tracking-wider uppercase text-white">
                <img src="{{ Vite::asset('resources/images/logo.png') }}" style="width: 87px;" alt="logo" class="rounded-full object-cover shadow-sm">
            </span>
            <!-- Music Graphic -->
            <div class="flex items-end gap-[3px] h-4 cursor-pointer" title="Playing Live Music">
                <div class="w-1 bg-pink-500 rounded-t-sm animate-music-bar music-bar-1" style="height: 100%"></div>
                <div class="w-1 bg-purple-500 rounded-t-sm animate-music-bar music-bar-2" style="height: 100%"></div>
                <div class="w-1 bg-pink-400 rounded-t-sm animate-music-bar music-bar-3" style="height: 100%"></div>
                <div class="w-1 bg-purple-400 rounded-t-sm animate-music-bar music-bar-4" style="height: 100%"></div>
            </div>
        </a>

        <!-- Links -->
        <nav class="hidden lg:flex items-center gap-6 text-xs font-bold tracking-wider text-gray-300">
            @php
            $isHome = Route::currentRouteName() === 'frontend.home';
            @endphp

            <a href="{{ $isHome ? '#why-us' : route('frontend.home') . '#why-us' }}"
                class="hover:text-pink-400 transition">
                Why Us
            </a>

            <a href="{{ $isHome ? '#features' : route('frontend.home') . '#features' }}"
                class="hover:text-purple-400 transition">
                Experience
            </a>

            <a href="{{ $isHome ? '#gallery' : route('frontend.home') . '#gallery' }}"
                class="hover:text-pink-400 transition">
                Gallery
            </a>

            <a href="{{ $isHome ? '#faq' : route('frontend.home') . '#faq' }}"
                class="hover:text-pink-400 transition">
                FAQ
            </a>

            <a href="{{ route('frontend.about') }}"
                class="hover:text-pink-400 transition">
                About Us
            </a>
        </nav>

        <!-- Nav Action -->
        <div class="flex items-center gap-4">
            <!-- Download App Button -->
            <a href="https://play.google.com/store/apps/details?id=com.themidnightclub.app" class="hidden sm:block md:flex relative items-center justify-center p-[1.5px] rounded-full overflow-hidden group hover:scale-[1.02] transition-all duration-300 shadow-[0_0_20px_rgba(168,85,247,0.25)] hover:shadow-[0_0_35px_rgba(236,72,153,0.6)]">
                <!-- Glowing Aura -->
                <span class="absolute inset-0 bg-gradient-to-r from-pink-500 via-purple-500 to-pink-500 opacity-80 group-hover:opacity-100 blur-[4px] transition-opacity duration-300"></span>
                <!-- Solid Border Gradient -->
                <span class="absolute inset-0 bg-gradient-to-r from-pink-500 via-purple-500 to-pink-500 opacity-100"></span>

                <!-- Inner Glassy Pill -->
                <div class="relative z-10 flex items-center gap-2.5 bg-gray-900/95 group-hover:bg-gray-900/80 backdrop-blur-xl px-4 py-1.5 rounded-full text-xs font-black text-white tracking-widest uppercase transition-colors">
                    <!-- Icon Container -->
                    <div class="w-6 h-6 rounded-full bg-gradient-to-br from-pink-500/20 to-purple-500/20 flex items-center justify-center border border-pink-500/30 group-hover:border-pink-400 group-hover:bg-pink-500/30 transition-all">
                        <i class="fab fa-google-play text-[10px] text-pink-400 group-hover:text-white transition-colors group-hover:animate-bounce"></i>
                    </div>
                    <span class="bg-gradient-to-r from-white to-gray-300 bg-clip-text text-transparent group-hover:from-white group-hover:to-white transition-all">Get App</span>
                </div>
            </a>

            <!-- Book VIP Button -->
            <a href="{{ $isHome ? '#reserve' : route('frontend.home') . '#reserve' }}" class="hidden sm:block px-8 py-3 rounded-full bg-gradient-to-r from-purple-500 to-pink-500 text-xs font-bold text-white shadow-[0_0_15px_rgba(168,85,247,0.4)] hover:shadow-[0_0_20px_rgba(236,72,153,0.7)] hover:scale-105 active:scale-95 transition text-center whitespace-nowrap">
                BOOK VIP
            </a>
            <button @click="mobileMenuOpen = true" class="lg:hidden text-gray-300 hover:text-white px-8 py-2.5 ">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </div>

    <!-- Mobile Drawer -->
    <div v-if="mobileMenuOpen" class="fixed inset-0 z-50 bg-midnight/95 backdrop-blur-2xl p-8 flex flex-col justify-center items-center text-center gap-6">
        <button @click="mobileMenuOpen = false" class="absolute top-6 right-6 text-gray-400 hover:text-white text-2xl">
            <i class="fas fa-times"></i>
        </button>
        <!-- <span class=" text-2xl font-black text-white tracking-wider">THE MIDNIGHT CLUB</span> -->
        @php
        $isHome = Route::currentRouteName() === 'frontend.home';
        $homeUrl = route('frontend.home');
        @endphp

        <a href="{{ $isHome ? '#why-us' : $homeUrl . '#why-us' }}"
            @click="mobileMenuOpen = false"
            class="text-lg font-bold text-gray-300 hover:text-pink-400">
            Why Us
        </a>

        <a href="{{ $isHome ? '#features' : $homeUrl . '#features' }}"
            @click="mobileMenuOpen = false"
            class="text-lg font-bold text-gray-300 hover:text-purple-400">
            Club Experience
        </a>

        <a href="{{ $isHome ? '#gallery' : $homeUrl . '#gallery' }}"
            @click="mobileMenuOpen = false"
            class="text-lg font-bold text-gray-300 hover:text-pink-400">
            Gallery
        </a>

        <a href="{{ route('frontend.about') }}"
            @click="mobileMenuOpen = false"
            class="text-lg font-bold text-gray-300 hover:text-pink-400">
            About
        </a>

        <a href="{{ $isHome ? '#testimonials' : $homeUrl . '#testimonials' }}"
            @click="mobileMenuOpen = false"
            class="text-lg font-bold text-gray-300 hover:text-purple-400">
            Testimonials
        </a>

        <a href="{{ $isHome ? '#faq' : $homeUrl . '#faq' }}"
            @click="mobileMenuOpen = false"
            class="text-lg font-bold text-gray-300 hover:text-pink-400">
            FAQ
        </a>

        <!-- Download App Button -->
        <a href="https://play.google.com/store/apps/details?id=com.themidnightclub.app" class="sm:block md:flex relative items-center justify-center p-[1.5px] rounded-full overflow-hidden group hover:scale-[1.02] transition-all duration-300 shadow-[0_0_20px_rgba(168,85,247,0.25)] hover:shadow-[0_0_35px_rgba(236,72,153,0.6)]">
            <!-- Glowing Aura -->
            <span class="absolute inset-0 bg-gradient-to-r from-pink-500 via-purple-500 to-pink-500 opacity-80 group-hover:opacity-100 blur-[4px] transition-opacity duration-300"></span>
            <!-- Solid Border Gradient -->
            <span class="absolute inset-0 bg-gradient-to-r from-pink-500 via-purple-500 to-pink-500 opacity-100"></span>

            <!-- Inner Glassy Pill -->
            <div class="relative z-10 flex items-center gap-2.5 bg-gray-900/95 group-hover:bg-gray-900/80 backdrop-blur-xl px-4 py-1.5 rounded-full text-xs font-black text-white tracking-widest uppercase transition-colors">
                <!-- Icon Container -->
                <div class="w-6 h-6 rounded-full bg-gradient-to-br from-pink-500/20 to-purple-500/20 flex items-center justify-center border border-pink-500/30 group-hover:border-pink-400 group-hover:bg-pink-500/30 transition-all">
                    <i class="fab fa-google-play text-[10px] text-pink-400 group-hover:text-white transition-colors group-hover:animate-bounce"></i>
                </div>
                <span class="bg-gradient-to-r from-white to-gray-300 bg-clip-text text-transparent group-hover:from-white group-hover:to-white transition-all">Get App</span>
            </div>
        </a>

        <a href="{{ $isHome ? '#reserve' : $homeUrl . '#reserve' }}"
            @click="mobileMenuOpen = false"
            class="px-8 py-3 rounded-full bg-gradient-to-r from-purple-500 to-pink-500 text-white font-bold text-sm">
            Book Table Now
        </a>
    </div>
</header>