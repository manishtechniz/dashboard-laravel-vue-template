<x-frontend::layouts title="Midnight Club Gurugram | Best Nightclub in Gurgaon" description="Book your VIP table and digital passes at The Midnight Club. Experience Midnight Club Gurugram - nightlife, DJs, events, parties, drinks, table booking and unforgettable nights in Gurgaon." keywords="midnight club gurugram, midnight club gurgaon, midnightclub gurugram, best club in gurugram, best club in gurgaon, best nightclub in gurugram, nightclub in gurgaon, nightlife in gurugram, club booking gurgaon, club booking gurugram">

    <v-home></v-home>

    @pushOnce('styles')
    <!-- Google Fonts & CDN dependencies -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Syne:wght@700;800;900&family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

    <style>
        @keyframes music-bar {
            0% {
                transform: scaleY(0.3);
            }

            100% {
                transform: scaleY(1);
            }
        }

        .animate-music-bar {
            animation: music-bar 0.5s ease-in-out infinite alternate;
            transform-origin: bottom;
        }

        .music-bar-1 {
            animation-duration: 0.4s;
        }

        .music-bar-2 {
            animation-duration: 0.7s;
        }

        .music-bar-3 {
            animation-duration: 0.5s;
        }

        .music-bar-4 {
            animation-duration: 0.6s;
        }
    </style>
    @endPushOnce

    @pushOnce('scripts')
    <script type="text/x-template" id="v-home-template">
        <div class="relative bg-midnight text-gray-100 min-h-screen overflow-x-hidden selection:bg-pink-500 selection:text-white font-sans">
            
            <!-- Floating Action Buttons -->
            <div class="fixed bottom-6 right-6 z-40 flex flex-col gap-3 items-end">
                <!-- Pass History FAB (Only if passes exist) -->
                <a v-if="myPasses.length > 0" href="#my-passes" class="flex items-center justify-center w-12 h-12 bg-gradient-to-br from-purple-600 to-pink-500 rounded-full shadow-[0_0_20px_rgba(236,72,153,0.5)] text-white hover:scale-110 transition group relative">
                    <i class="fas fa-history text-xl"></i>
                    <!-- Tooltip -->
                    <span class="absolute right-14 bg-gray-900 text-white text-xs px-3 py-1.5 rounded-lg border border-gray-700 opacity-0 group-hover:opacity-100 transition whitespace-nowrap pointer-events-none">Booking History</span>
                </a>
                <!-- App Download FAB -->
                <a href="https://play.google.com/store/apps/details?id=com.themidnightclub.app" target="_blank" class="flex items-center justify-center w-12 h-12 bg-gray-900 border border-gray-700 hover:border-pink-500 rounded-full shadow-[0_0_15px_rgba(168,85,247,0.3)] text-pink-400 hover:scale-110 transition group relative">
                    <i class="fab fa-google-play text-xl"></i>
                    <!-- Tooltip -->
                    <span class="absolute right-14 bg-gray-900 text-white text-xs px-3 py-1.5 rounded-lg border border-gray-700 opacity-0 group-hover:opacity-100 transition whitespace-nowrap pointer-events-none">Get the App</span>
                </a>
            </div>

            <!-- Ambient Cursor Spotlight -->
            <div id="cursor-glow" class="cursor-spotlight hidden lg:block" :style="{ left: cursor.x + 'px', top: cursor.y + 'px' }"></div>

            <!-- Background Ambient Glows & Lasers -->
            <div class="fixed inset-0 pointer-events-none overflow-hidden z-0">
                <div class="absolute -top-32 left-1/4 w-[650px] h-[650px] bg-purple-600/15 rounded-full blur-[160px] animate-pulse"></div>
                <div class="absolute top-1/3 -right-24 w-[600px] h-[600px] bg-pink-500/15 rounded-full blur-[170px] animate-pulse" style="animation-delay: 1.5s;"></div>
                <div class="absolute top-0 left-1/2 -translate-x-1/2 w-4/5 h-[420px] bg-gradient-to-b from-purple-500/20 via-pink-500/10 to-transparent blur-3xl opacity-40 animate-laser origin-top"></div>
            </div> 

            <!-- Mobile Drawer moved to header -->

            <main class="relative z-10">   
                <!-- Hero Section -->
                <section class="relative pb-24 px-6 overflow-hidden">
                    <div class="absolute -top-24 -left-24 w-[440px] h-[440px] rounded-full bg-neonPurple/25 blur-[120px] tmc-orb-a"></div>
                    <div class="absolute bottom-0 right-0 w-[480px] h-[480px] rounded-full bg-neonPink/20 blur-[130px] tmc-orb-b"></div>
                    <div class="absolute inset-0 pointer-events-none overflow-hidden">
                        <div class="tmc-laser absolute top-0 left-1/3 w-2 h-[140%] bg-gradient-to-b from-neonPurple/60 to-transparent"></div>
                        <div class="tmc-laser absolute top-0 left-1/2 w-2 h-[140%] bg-gradient-to-b from-neonPink/50 to-transparent" style="animation-delay:1.5s"></div>
                        <div class="tmc-laser absolute top-0 left-2/3 w-2 h-[140%] bg-gradient-to-b from-neonPurple/40 to-transparent" style="animation-delay:3s"></div>
                    </div>

                    <div class="relative max-w-6xl mx-auto grid lg:grid-cols-[1.1fr_0.9fr] gap-14 items-center">
                        <div class="text-center lg:text-left">
                            <div class="inline-flex items-center gap-2 bg-purple-900/30 border border-purple-500/30 text-purple-300 px-4 py-1.5 rounded-full text-xs font-semibold tracking-wide uppercase mb-6">
                                <span class="w-1.5 h-1.5 rounded-full bg-neonPink tmc-live-dot"></span>
                                Gurugram's Ultimate Nightlife Experience
                            </div>
                            <h1 class="tmc-glow-title text-5xl md:text-5xl font-bold tracking-tight mb-5 leading-tight">
                                Elevate your nightlife.<br>
                                <span class="gradient-text">Book VIP tables &amp; events.</span>
                            </h1>
                            <p class="font-serif-club text-2xl md:text-3xl text-white/70 mb-6">the city's tables, your name on the list.</p>
                            <p class="text-gray-400 text-lg max-w-xl mx-auto lg:mx-0 mb-10">
                                Reserve premium tables instantly, catch the DJ lineup before anyone else, and walk into <b>The Midnight Club</b> already on the guest list.
                            </p>

                            <div class="flex flex-col sm:flex-row justify-center lg:justify-start gap-4 m-auto">
                                <a href="https://play.google.com/store/apps/details?id=com.themidnightclub.app" target="_blank"
                                    class="flex items-center justify-center bg-gray-900 border border-gray-700 hover:border-purple-500 px-6 py-3.5 rounded-xl glow transition">
                                    <i class="fab fa-google-play text-xl mr-3 text-neonPink"></i>
                                    <div class="text-left">
                                        <div class="text-xs text-gray-400">GET IT ON</div>
                                        <div class="text-sm font-semibold text-white">Google Play</div>
                                    </div>
                                </a>
                                <!-- Book Table CTA -->
                                <a href="#reserve"
                                    class="w-full sm:w-auto flex items-center justify-center gap-2.5 bg-gradient-to-r from-neon-purple to-neon-pink px-9 py-4 rounded-2xl font-bold text-white shadow-neon-pink hover:scale-105 active:scale-95 transition-all duration-300 text-base">
                                    <i class="fas fa-champagne-glasses text-sm"></i>
                                    <span>Book Table Online</span>
                                </a>
                            </div>
                        </div>

                        <!-- club motion graphic: turntable + equalizer + dancing crowd -->
                        <div class="relative tmc-float flex justify-center">
                            <svg viewBox="0 0 320 320" class="w-64 h-64 sm:w-80 sm:h-80 drop-shadow-[0_0_40px_rgba(168,85,247,0.35)]">
                                <circle cx="160" cy="160" r="150" fill="#1f293d" />
                                <circle cx="160" cy="160" r="150" fill="none" stroke="url(#tmcRingGrad)" stroke-width="2" />
                                <g class="tmc-vinyl">
                                    <circle cx="160" cy="160" r="120" fill="#0b0c10" />
                                    <circle cx="160" cy="160" r="112" fill="none" stroke="#2a3550" stroke-width="1" />
                                    <circle cx="160" cy="160" r="96" fill="none" stroke="#2a3550" stroke-width="1" />
                                    <circle cx="160" cy="160" r="80" fill="none" stroke="#2a3550" stroke-width="1" />
                                    <circle cx="160" cy="160" r="64" fill="none" stroke="#2a3550" stroke-width="1" />
                                    <circle cx="160" cy="160" r="34" fill="#a855f7" />
                                    <circle cx="160" cy="160" r="6" fill="#0b0c10" />
                                </g>
                                <g class="tmc-tonearm">
                                    <rect x="240" y="60" width="10" height="90" rx="5" fill="#ec4899" />
                                    <circle cx="245" cy="58" r="14" fill="#ec4899" />
                                    <circle cx="245" cy="58" r="6" fill="#0b0c10" />
                                </g>
                                <defs>
                                    <linearGradient id="tmcRingGrad" x1="0" y1="0" x2="1" y2="1">
                                        <stop offset="0%" stop-color="#a855f7" />
                                        <stop offset="100%" stop-color="#ec4899" />
                                    </linearGradient>
                                </defs>
                            </svg>

                            <!-- dancing crowd silhouette strip -->
                            <svg viewBox="0 0 320 60" class="tmc-crowd-silhouette absolute -bottom-6 left-1/2 -translate-x-1/2 w-64 opacity-70">
                                <path d="M10 60V30q4-14 10-4v34" fill="#a855f7" style="animation-delay:0s" />
                                <path d="M40 60V22q4-16 10-3v41" fill="#ec4899" style="animation-delay:.2s" />
                                <path d="M70 60V34q4-12 10-2v28" fill="#a855f7" style="animation-delay:.4s" />
                                <path d="M240 60V24q4-15 10-4v40" fill="#ec4899" style="animation-delay:.3s" />
                                <path d="M270 60V32q4-13 10-3v31" fill="#a855f7" style="animation-delay:.1s" />
                                <path d="M300 60V26q4-14 10-3v37" fill="#ec4899" style="animation-delay:.5s" />
                            </svg>

                            <div @click="toggleAudioMode" class="flex absolute -bottom-4 -left-4 sm:-left-8 tmc-grad-border rounded-xl bg-gray-900 px-4 py-3 shadow-xl items-center gap-3 border border-gray-800 cursor-pointer hover:bg-gray-800 transition">
                                <div class="w-12 h-6 flex items-end justify-between">
                                    <canvas ref="visualizerCanvas" class="w-full h-full opacity-80"></canvas>
                                </div>
                                <p class="text-xs text-white/60">Live now · DJ Kaayan <span class="text-[9px] font-bold text-pink-400 ml-1">@{{ bpmLabel }}</span></p>
                                <audio ref="bgMusic" src="https://cdn.pixabay.com/download/audio/2022/03/10/audio_c8c8a73467.mp3?filename=electronic-future-beats-117997.mp3" loop preload="none"></audio>
                            </div>
                            <span class="tmc-glint absolute top-4 right-6 w-2 h-2 rounded-full bg-white"></span>
                            <span class="tmc-glint absolute top-16 right-0 w-1.5 h-1.5 rounded-full bg-white" style="animation-delay:.8s"></span>
                            <span class="tmc-glint absolute bottom-14 right-10 w-1.5 h-1.5 rounded-full bg-white" style="animation-delay:1.4s"></span>
                        </div>
                    </div>
                </section>

                <!-- INFINITE MARQUEE STRIP -->
                <div class="border-y border-white/10 py-3 bg-midnight/90 overflow-hidden">
                    <div class="flex animate-marquee whitespace-nowrap text-white/30 font-bold text-xl tracking-wider gap-8">
                        <span>LIVE DJ SETS</span>•<span>VIP TABLES</span>•<span>360° DOLBY AUDIO</span>•<span>SPARKLER BOTTLE PARADES</span>•<span>GUEST LISTS</span>•
                        <span>LIVE DJ SETS</span>•<span>VIP TABLES</span>•<span>360° DOLBY AUDIO</span>•<span>SPARKLER BOTTLE PARADES</span>•<span>GUEST LISTS</span>•
                    </div>
                </div>

                <!-- ABOUT US PREVIEW -->
                <section class="py-24 px-6 max-w-7xl mx-auto border-b border-white/5">
                    <div class="grid md:grid-cols-2 gap-12 items-center">
                        <div class="order-2 md:order-1 relative">
                            <!-- Image Composition -->
                            <div class="relative z-10 rounded-3xl overflow-hidden shadow-2xl border border-white/10">
                                <img src="https://images.unsplash.com/photo-1517457373958-b7bdd4587205?q=80&w=1000&auto=format&fit=crop" class="w-full h-auto object-cover" alt="The Midnight Club Experience">
                            </div>
                            <!-- Decorative Elements -->
                            <div class="absolute -bottom-6 -right-6 w-48 h-48 bg-purple-500/20 blur-3xl rounded-full z-0"></div>
                            <div class="absolute -top-6 -left-6 w-32 h-32 bg-pink-500/20 blur-3xl rounded-full z-0"></div>
                        </div>
                        <div class="order-1 md:order-2">
                            <div class="inline-block bg-pink-900/30 border border-pink-500/30 text-neon-pink px-4 py-1.5 rounded-full text-xs font-bold tracking-widest uppercase mb-4">
                                Welcome to The Sanctuary
                            </div>
                            <h2 class="text-4xl sm:text-5xl font-bold text-white mb-6">
                                The <span class="text-transparent bg-clip-text bg-gradient-to-r from-pink-500 to-purple-500">Midnight</span> Experience
                            </h2>
                            <p class="text-gray-400 text-sm leading-relaxed mb-6">
    Tucked away in the heart of Gurgaon, The Midnight Club was built for the crowd that never wants the night to end. Pushing right through the dark until 7 AM, the room runs on low frequencies, hypnotic laser rigs, and continuous sets from resident and guest DJs who know how to pace a crowd through every phase of the morning.
</p>
<p class="text-gray-400 text-sm leading-relaxed mb-8">
    Whether you spend your night lost in the center of the pit or watching the room unfold from a leather VIP booth, every detail is considered from our craft cocktail list and late-night kitchen service to acoustics tuned specifically so you feel the bass in your chest. When the rest of the city shuts down, we're just getting into rhythm.
</p>
                            <a href="{{ route('frontend.about') }}" class="inline-flex items-center gap-2 px-8 py-3.5 rounded-full bg-gradient-to-r from-pink-500 to-purple-600 text-white font-bold text-xs uppercase tracking-wider shadow-[0_0_30px_rgba(236,72,153,0.3)] hover:shadow-[0_0_50px_rgba(236,72,153,0.5)] hover:scale-105 transition-all duration-300">
                                Know More <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </section>

                <!-- MY PASSES SECTION (Local Storage) -->
                <section v-if="myPasses.length > 0" id="my-passes" class="py-12 bg-gray-900 border-y border-white/5 overflow-hidden">
                    <div class="max-w-6xl mx-auto px-6">
                        <div class="flex items-center justify-between mb-8">
                            <div>
                                <h2 class=" text-2xl font-bold text-white mb-1"><i class="fas fa-ticket-alt text-pink-400 mr-2"></i> My Digital Passes</h2>
                                <p class="text-xs text-gray-400">Visible for the next 3 days otherwise download app.</p>
                            </div>
                        </div>
                        <div class="flex gap-4 overflow-x-auto pb-4 snap-x">
                            <div v-for="(pass, index) in myPasses" :key="index" class="min-w-[320px] snap-center bg-midnight border border-pink-500/30 rounded-2xl p-5 shadow-[0_0_15px_rgba(236,72,153,0.15)] flex flex-col justify-between relative">
                                <div class="flex gap-4">
                                    <!-- QR Code Thumbnail -->
                                    <div class="shrink-0">
                                        <div class="bg-white p-1 rounded-xl shadow-lg">
                                            <img :src="`https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=${pass.passCode}`"  width="100" height="100" class="  object-contain" alt="QR Code">
                                        </div>
                                    </div>
                                    <!-- Details -->
                                    <div class="grow">
                                        <!-- <div class="flex justify-between items-start mb-2"> -->
                                            <div>
                                                <div class="text-[10px] text-gray-400 uppercase tracking-wider mb-0.5">Pass Holder</div>
                                                <div class="font-bold text-white text-sm line-clamp-1">@{{ pass?.name || '-' }}</div>
                                            </div>
                                            <!-- <div class="text-right">
                                                <div class="text-[10px] text-pink-400 uppercase tracking-wider mb-0.5 font-bold">Zone</div>
                                                <div class="font-bold text-white text-xs">@{{ pass.tier }}</div>
                                            </div> -->
                                        <!-- </div> -->
                                        <div class="grid grid-cols-3 gap-2 text-xs mt-1">
                                            <div>
                                                <span class="block text-gray-500 text-[10px]">Date</span>
                                                <span class="text-gray-200">@{{ pass?.date || '-'}}</span>
                                            </div>
                                            <div class="text-center">
                                                <span class="block text-gray-500 text-[10px]">Arival</span>
                                                <span class="text-gray-200">@{{ pass?.time || '-' }}</span>
                                            </div>
                                            <div class="text-right">
                                                <span class="block text-gray-500 text-[10px]">Guests</span>
                                                <span class="text-gray-200">@{{ pass?.guests || '-' }}</span>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                
                                <button @click="viewStoredPass(pass)" class="mt-4 w-full py-2.5 rounded-xl bg-gradient-to-r from-pink-500/20 to-purple-500/20 hover:from-pink-500/40 hover:to-purple-500/40 border border-pink-500/50 text-white font-bold text-xs uppercase transition">
                                    <i class="fas fa-expand mr-1"></i> Expand Pass
                                </button>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- WHY CHOOSE THE MIDNIGHT CLUB -->
                <section id="why-us" class="py-24 bg-midnight-surface/80 border-t border-b border-gray-800/80 relative">
                    <div class="max-w-7xl mx-auto px-6">

                        <div class="text-center max-w-3xl mx-auto mb-16">
                            <div class="inline-block bg-purple-900/40 border border-purple-500/30 text-neon-purple px-4 py-1.5 rounded-full text-xs font-bold tracking-widest uppercase mb-4">
                                Exclusivity & Excellence
                            </div>
                            <h2 class=" text-4xl sm:text-5xl font-bold text-white mb-4">
                                Why Choose <span class="gradient-text">The Midnight Club</span>?
                            </h2>
                            <p class="text-gray-400 text-sm sm:text-base ">
                                Great sound, great drinks, and zero chaos. We bring you deep bass, quick table service, and a vibe that keeps going long after the last track.
                            </p>
                        </div>

                        <div class="grid md:grid-cols-3 gap-8">

                            <!-- Advantage 1: VIP & Table Bookings -->
                            <div class="glass-card p-8 rounded-3xl group">
                                <div class="w-14 h-14 bg-purple-500/10 border border-purple-500/20 text-neon-purple rounded-2xl flex items-center justify-center text-2xl mb-6 group-hover:scale-110 group-hover:bg-neon-purple group-hover:text-white transition-all duration-300 shadow-neon-purple">
                                    <i class="fas fa-glass-cheers"></i>
                                </div>
                                <h3 class=" text-2xl font-bold text-white mb-3">VIP & Table Bookings</h3>
                                <p class="text-gray-400 text-sm leading-relaxed mb-6">
                                    Instant reservations for Regular, Premium, or Ultra-VIP lounges without waiting in long cold queues. Dedicated bottle host assigned automatically.
                                </p>
                                <a href="#reserve" class="flex items-center text-xs font-bold text-neon-purple gap-2 group-hover:text-neon-pink transition-colors">
                                    <span>RESERVE INSTANTLY</span>
                                    <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                                </a>
                            </div>

                            <!-- Advantage 2: Discover Nightlife -->
                            <div class="glass-card p-8 rounded-3xl group">
                                <div class="w-14 h-14 bg-pink-500/10 border border-pink-500/20 text-neon-pink rounded-2xl flex items-center justify-center text-2xl mb-6 group-hover:scale-110 group-hover:bg-neon-pink group-hover:text-white transition-all duration-300 shadow-neon-pink">
                                    <i class="fas fa-music"></i>
                                </div>
                                <h3 class=" text-2xl font-bold text-white mb-3">World-Class Sound</h3>
                                <p class="text-gray-400 text-sm leading-relaxed mb-6">
                                    Explore top DJ lineups, live gigs, and festival-grade acoustic power powered by our 360° quadraphonic sound system calibrated for visceral bass.
                                </p>
                                <a href="#gallery" class="flex items-center text-xs font-bold text-neon-pink gap-2 group-hover:text-neon-purple transition-colors">
                                    <span>EXPLORE SETTINGS</span>
                                    <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                                </a>
                            </div>

                            <!-- Advantage 3: Digital Check-In -->
                            <div class="glass-card p-8 rounded-3xl group">
                                <div class="w-14 h-14 bg-purple-500/10 border border-purple-500/20 text-neon-purple rounded-2xl flex items-center justify-center text-2xl mb-6 group-hover:scale-110 group-hover:bg-purple-600 group-hover:text-white transition-all duration-300 shadow-neon-purple">
                                    <i class="fas fa-qrcode"></i>
                                </div>
                                <h3 class=" text-2xl font-bold text-white mb-3">Digital QR Check-In</h3>
                                <p class="text-gray-400 text-sm leading-relaxed mb-6">
                                    Receive instant QR code entry confirmations directly on your smartphone for seamless bouncer verification and one-tap table check-in.
                                </p>
                                <a href="#reserve" class="flex items-center text-xs font-bold text-purple-400 gap-2 group-hover:text-neon-pink transition-colors">
                                    <span>CONTACTLESS ACCESS</span>
                                    <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                                </a>
                            </div>

                        </div>
                    </div>
                </section>

                <!-- EXPERIENCES & PERKS GRID -->
                <section id="features" class="py-24 px-6 max-w-7xl mx-auto">
                    <div class="flex flex-col md:flex-row md:items-end justify-between mb-16 gap-6">
                        <div>
                            <span class="text-xs font-bold tracking-widest text-neon-pink uppercase mb-2 block">Unmatched Energy</span>
                            <h2 class=" text-4xl sm:text-5xl font-bold text-white">Experience The Night Differently</h2>
                        </div>
                        <p class="text-gray-400 text-sm max-w-md">
                            <!-- From bespoke artisan mixology to our kinetic light show ceilings that dance in harmony with every bassline drop. -->
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- Card 1 -->
                        <div class="glass-card rounded-3xl overflow-hidden group">
                            <div class="h-60 overflow-hidden relative">
                                <img src="{{ Vite::asset('resources/images/party_dj_girl.jpg') }}"
                                    alt="DJ Performance"
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                <div class="absolute inset-0 bg-gradient-to-t from-dark-card via-transparent to-transparent"></div>
                                <span class="absolute top-4 left-4 bg-midnight/80 backdrop-blur-md px-3 py-1 rounded-full text-[10px] font-bold text-neon-pink border border-neon-pink/30">HEADLINERS</span>
                            </div>
                            <div class="p-6">
                                <h4 class=" text-xl font-bold text-white mb-2">Global DJ Residencies</h4>
                                <p class="text-gray-400 text-xs leading-relaxed mb-4">Featuring award-winning international selectors, techno icons, and live underground sets every weekend.</p>
                                <div class="text-neon-purple text-xs font-bold flex items-center gap-1.5">
                                    <span>Every Thu - Sun</span> • <span>10:00 PM onwards</span>
                                </div>
                            </div>
                        </div>

                        <!-- Card 2 -->
                        <div class="glass-card rounded-3xl overflow-hidden group">
                            <div class="h-60 overflow-hidden relative">
                                <img src="{{ Vite::asset('resources/images/vip_bottle_service.jpg') }}"
                                    alt="VIP Bottle Service"
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                <div class="absolute inset-0 bg-gradient-to-t from-dark-card via-transparent to-transparent"></div>
                                <span class="absolute top-4 left-4 bg-midnight/80 backdrop-blur-md px-3 py-1 rounded-full text-[10px] font-bold text-neon-purple border border-neon-purple/30">VIP SERVICE</span>
                            </div>
                            <div class="p-6">
                                <h4 class=" text-xl font-bold text-white mb-2">Bespoke Bottle Parades</h4>
                                <p class="text-gray-400 text-xs leading-relaxed mb-4">Champagne sparklers, customized LED nameboards, and direct escort through the backstage VIP tunnel.</p>
                                <div class="text-neon-pink text-xs font-bold flex items-center gap-1.5">
                                    <span>Dom Pérignon & Ace of Spades</span>
                                </div>
                            </div>
                        </div>

                        <!-- Card 3 -->
                        <div class="glass-card rounded-3xl overflow-hidden group">
                            <div class="h-60 overflow-hidden relative">
                                <img src="{{ Vite::asset('resources/images/party_crowd_lights.jpg') }}"
                                    alt="Sensory Kinetic Ceiling"
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                <div class="absolute inset-0 bg-gradient-to-t from-dark-card via-transparent to-transparent"></div>
                                <span class="absolute top-4 left-4 bg-midnight/80 backdrop-blur-md px-3 py-1 rounded-full text-[10px] font-bold text-indigo-400 border border-indigo-400/30">KINETIC ART</span>
                            </div>
                            <div class="p-6">
                                <h4 class=" text-xl font-bold text-white mb-2">3D Kinetic Laser Sphere</h4>
                                <p class="text-gray-400 text-xs leading-relaxed mb-4">Immersive motorized LED spheres descend above the dancefloor in synchronization with the DJ’s drops.</p>
                                <div class="text-indigo-400 text-xs font-bold flex items-center gap-1.5">
                                    <span>Over 1,000 DMX Motors</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

               <!-- INFINITE MARQUEE FILMSTRIP CAROUSEL -->
                <div class="py-6 border-t border-b border-white/10 bg-midnight/90 overflow-hidden relative select-none">
                    <div class="flex w-max animate-marquee hover:[animation-play-state:paused]">
                        
                        <!-- Track 1 -->
                        <div class="flex items-center gap-6 pr-6 shrink-0">
                            <div 
                                v-for="(item, index) in mockGalleryDatabase" 
                                :key="'set1-' + index"
                                class="relative w-48 h-28 rounded-xl overflow-hidden border border-white/10 group cursor-pointer shrink-0"
                            >
                                <img :src="item.image" :alt="item.title" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                <div class="absolute inset-0 bg-midnight/40 group-hover:bg-transparent transition-colors"></div>
                                <span class="absolute bottom-1.5 left-2 text-[9px] font-mono font-bold text-white bg-midnight/80 px-2 py-0.5 rounded backdrop-blur-sm border border-white/10">
                                    @{{ item.title }}
                                </span>
                            </div>
                        </div>

                        <!-- Track 2 (Duplicate for Seamless Loop) -->
                        <div class="flex items-center gap-6 pr-6 shrink-0" aria-hidden="true">
                            <div 
                                v-for="(item, index) in mockGalleryDatabase" f
                                :key="'set2-' + index"
                                class="relative w-48 h-28 rounded-xl overflow-hidden border border-white/10 group cursor-pointer shrink-0"
                            >
                                <img :src="item.image" :alt="item.title" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                <div class="absolute inset-0 bg-midnight/40 group-hover:bg-transparent transition-colors"></div>
                                <span class="absolute bottom-1.5 left-2 text-[9px] font-mono font-bold text-white bg-midnight/80 px-2 py-0.5 rounded backdrop-blur-sm border border-white/10">
                                    @{{ item.title }}
                                </span>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- MULTI-IMAGE GALLERY SYSTEM (API-READY) -->
                <section id="gallery" class="py-20 px-6 max-w-7xl mx-auto border-t border-white/5">
                    <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-6">
                        <div>
                            <span class="text-xs font-bold tracking-widest text-pink-400 uppercase mb-2 block">
                                <i class="fas fa-camera mr-1"></i> Visual Vault
                            </span>
                            <h2 class=" text-3xl sm:text-4xl font-bold text-white">The Midnight Vault</h2>
                            <p class="text-gray-400 text-sm mt-1">Multi-image snapshots of headliners, bottle shows, and nocturnal energy.</p>
                        </div>

                        <!-- API Refresh & Count -->
                        <div class="flex items-center gap-3">
                             
                            <span class="text-xs font-mono text-pink-400 font-semibold px-3 py-1.5 bg-pink-500/10 rounded-xl border border-pink-500/20">
                                @{{ filteredGallery.length }} Moments
                            </span>
                        </div>
                    </div>

                    <!-- Category Filter Tabs -->
                    <div class="flex items-center gap-2.5 overflow-x-auto pb-4 mb-8">
                        <button v-for="cat in categories" :key="cat.key" 
                                @click="activeCategory = cat.key; fetchGalleryApi(null, true)" 
                                :class="activeCategory === cat.key ? 'bg-gradient-to-r from-purple-500 to-pink-500 text-white shadow-lg' : 'bg-dark-card text-gray-300 border border-gray-700 hover:border-purple-500'" 
                                class="px-4 py-2 rounded-full text-xs font-bold whitespace-nowrap transition">
                            @{{ cat.label }}
                        </button>
                    </div>

                    <!-- Gallery Grid -->
                    <div v-if="galleryItems.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div v-for="(img, idx) in visibleGallery" :key="img.id || idx" 
                             @click="openLightbox(idx)" 
                             class="glass-card rounded-3xl overflow-hidden group relative flex flex-col justify-between cursor-pointer">
                            
                            <div class="relative h-64 overflow-hidden">
                                <video v-if="img.isVideo" :src="img.file_url" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" muted loop playsinline preload="none"></video>
                                <img v-else :src="img.file_url || img.image" :alt="img.title" loading="lazy" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                                <div class="absolute inset-0 bg-gradient-to-t from-dark-card via-transparent to-transparent pointer-events-none"></div>
                                <span class="absolute top-4 left-4 bg-midnight/80 backdrop-blur-md px-3 py-1 rounded-full text-[10px] font-bold text-pink-400 uppercase tracking-wider border border-pink-500/30">
                                    @{{ img.category }}
                                </span>
                                <div class="absolute top-4 right-4 w-8 h-8 rounded-full bg-midnight/80 border border-white/20 flex items-center justify-center text-white opacity-0 group-hover:opacity-100 transition pointer-events-none">
                                    <i class="fas fa-expand text-xs"></i>
                                </div>
                                <div v-if="img.isVideo" class="absolute inset-0 flex items-center justify-center pointer-events-none">
                                    <i class="fas fa-play-circle text-4xl text-white/70"></i>
                                </div>
                            </div> 
                        </div>
                    </div>

                    <!-- Skeleton Loader State -->
                    <div v-if="isApiLoading && galleryItems.length === 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div v-for="n in 6" :key="n" class="glass-card rounded-3xl overflow-hidden">
                            <div class="w-full h-64 skeleton"></div>
                        </div>
                    </div>

                    <!-- Empty State -->
                    <div v-else-if="!isApiLoading && galleryItems.length === 0" class="glass-card rounded-3xl p-12 text-center border border-purple-500/20 max-w-2xl mx-auto mt-8">
                        <div class="w-20 h-20 bg-purple-500/10 text-neon-purple rounded-full flex items-center justify-center mx-auto mb-6 text-4xl shadow-[0_0_30px_rgba(168,85,247,0.2)]">
                            <i class="fas fa-image"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-white mb-3">No Moments Captured Yet</h3>
                        <p class="text-gray-400 text-sm mb-6">The night is still young. Be part of the experience and create unforgettable memories.</p>
                        <a href="#reserve" class="inline-flex items-center gap-2 px-6 py-3 rounded-full bg-gradient-to-r from-purple-500 to-pink-500 text-white font-bold text-xs shadow-lg hover:scale-105 transition">
                            Book a Table Now
                        </a>
                    </div>

                    <!-- Load More -->
                    <div v-if="nextPageUrl" class="text-center mt-12">
                        <button @click="fetchGalleryApi(nextPageUrl)" :disabled="isApiLoading" class="px-8 py-3 rounded-2xl bg-dark-card border border-white/10 hover:border-purple-500 text-xs font-bold uppercase text-white transition inline-flex items-center justify-center gap-2">
                            <span v-if="isApiLoading"><i class="fas fa-spinner fa-spin"></i> Loading...</span>
                            <span v-else>Load More Moments <i class="fas fa-arrow-down text-pink-400"></i></span>
                        </button>
                    </div>
                </section>

                <!-- CINEMA LIGHTBOX MODAL (Moved) -->

                <!-- TESTIMONIALS SECTION -->
        <section id="testimonials" class="pt-20 pb-12 px-6 max-w-7xl mx-auto border-t border-white/5">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <div class="inline-block bg-pink-900/30 border border-pink-500/30 text-neon-pink px-4 py-1.5 rounded-full text-xs font-bold tracking-widest uppercase mb-4">
                    Voices from the Sanctuary
                </div>
                <h2 class=" text-3xl sm:text-5xl font-bold text-white mb-4">What Our Customer Say</h2>
                <p class="text-gray-400 text-sm">From resident artists to celebrity patrons, here’s how the midnight experience feels.</p>
            </div>

            <!-- Skeleton Loading State -->
            <div v-if="isTestimonialsLoading" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div v-for="n in 3" :key="'ts'+n" class="glass-card p-8 rounded-3xl relative flex flex-col justify-between h-[280px]">
                    <div class="space-y-4 w-full">
                        <div class="flex gap-1 mb-6">
                            <div v-for="s in 5" :key="s" class="w-4 h-4 bg-gray-700/50 rounded-full animate-pulse"></div>
                        </div>
                        <div class="w-full h-4 bg-gray-700/50 rounded animate-pulse"></div>
                        <div class="w-5/6 h-4 bg-gray-700/50 rounded animate-pulse"></div>
                        <div class="w-4/6 h-4 bg-gray-700/50 rounded animate-pulse"></div>
                    </div>
                    <div class="flex items-center gap-3 pt-4 border-t border-white/10 mt-auto">
                        <div class="w-10 h-10 rounded-full bg-gray-700/50 animate-pulse shrink-0"></div>
                        <div class="w-24 h-4 bg-gray-700/50 rounded animate-pulse"></div>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div v-else-if="!isTestimonialsLoading && testimonials.length === 0" class="glass-card rounded-3xl p-12 text-center border border-pink-500/20 max-w-2xl mx-auto">
                <div class="w-20 h-20 bg-pink-500/10 text-pink-400 rounded-full flex items-center justify-center mx-auto mb-6 text-4xl shadow-[0_0_30px_rgba(236,72,153,0.2)]">
                    <i class="fas fa-comment-slash"></i>
                </div>
                <h3 class="text-2xl font-bold text-white mb-3">Be the First to Share</h3>
                <p class="text-gray-400 text-sm mb-6">Our sanctuary is waiting to hear your voice. Experience the night and leave your mark.</p>
                <a href="#reserve" class="inline-flex items-center gap-2 px-6 py-3 rounded-full bg-gradient-to-r from-purple-500 to-pink-500 text-white font-bold text-xs shadow-lg hover:scale-105 transition">
                    Book a Table Now
                </a>
            </div>

            <!-- Infinite Carousel -->
            <div v-else class="relative overflow-hidden w-full select-none -mx-6 px-6 lg:-mx-0 lg:px-0">
                <div class="flex w-max animate-marquee hover:[animation-play-state:paused]">
                    <!-- Track 1 -->
                    <div class="flex items-center gap-6 pr-6 shrink-0">
                        <div v-for="(review, i) in testimonials" :key="'t1-' + (review.id || i)" 
                             class="w-[320px] md:w-[400px] shrink-0 h-full">
                            <div class="glass-card p-8 rounded-3xl relative flex flex-col justify-between group hover:border-pink-500/50 hover:shadow-[0_0_30px_rgba(236,72,153,0.15)] transition-all duration-300 overflow-hidden h-full min-h-[280px]">
                                <div class="absolute -top-6 -right-6 text-9xl text-white/[0.03] group-hover:text-pink-500/[0.05] transition-colors"><i class="fas fa-quote-right"></i></div>
                                <div class="relative z-10 flex-1 overflow-hidden">
                                    <div class="flex items-center gap-1 text-amber-400 text-sm mb-4">
                                        <i v-for="n in review.rating" :key="'s-'+n" class="fas fa-star"></i>
                                        <i v-for="n in (5 - review.rating)" :key="'e-'+n" class="far fa-star text-gray-600"></i>
                                    </div>
                                    <p class="text-gray-300 text-sm leading-relaxed mb-6 italic whitespace-normal line-clamp-4">
                                        "@{{ review.comment }}"
                                    </p>
                                </div>
                                <div class="flex items-center gap-3 pt-4 border-t border-white/10 relative z-10 shrink-0">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-neon-purple to-neon-pink p-0.5 shadow-lg shrink-0">
                                        <img v-if="review?.client?.avatar" :src="review?.client?.avatar" class="w-full h-full object-cover rounded-full" :alt="review.client ? review.client.name : 'VIP'">
                                        <img v-else src="{{ previewProfileURL() }}" class="w-full h-full object-cover rounded-full" :alt="review.client ? review.client.name : 'VIP'">
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-neon-pink group-hover:text-pink-400 transition-colors line-clamp-1">@{{ review.client ? review.client.name : 'Anonymous' }}</div> 
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Track 2 (Duplicate for Seamless Loop) -->
                    <div class="flex items-center gap-6 pr-6 shrink-0" aria-hidden="true">
                        <div v-for="(review, i) in testimonials" :key="'t2-' + (review.id || i)" 
                             class="w-[320px] md:w-[400px] shrink-0 h-full">
                            <div class="glass-card p-8 rounded-3xl relative flex flex-col justify-between group hover:border-pink-500/50 hover:shadow-[0_0_30px_rgba(236,72,153,0.15)] transition-all duration-300 overflow-hidden h-full min-h-[280px]">
                                <div class="absolute -top-6 -right-6 text-9xl text-white/[0.03] group-hover:text-pink-500/[0.05] transition-colors"><i class="fas fa-quote-right"></i></div>
                                <div class="relative z-10 flex-1 overflow-hidden">
                                    <div class="flex items-center gap-1 text-amber-400 text-sm mb-4">
                                        <i v-for="n in review.rating" :key="'s-'+n" class="fas fa-star"></i>
                                        <i v-for="n in (5 - review.rating)" :key="'e-'+n" class="far fa-star text-gray-600"></i>
                                    </div>
                                    <p class="text-gray-300 text-sm leading-relaxed mb-6 italic whitespace-normal line-clamp-4">
                                        "@{{ review.comment }}"
                                    </p>
                                </div>
                                <div class="flex items-center gap-3 pt-4 border-t border-white/10 relative z-10 shrink-0">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-neon-purple to-neon-pink p-0.5 shadow-lg shrink-0">
                                        <img v-if="review?.client?.avatar" :src="review?.client?.avatar" class="w-full h-full object-cover rounded-full" :alt="review.client ? review.client.name : 'VIP'">
                                        <img v-else src="{{ previewProfileURL() }}" class="w-full h-full object-cover rounded-full" :alt="review.client ? review.client.name : 'VIP'">
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-neon-pink group-hover:text-pink-400 transition-colors line-clamp-1">@{{ review.client ? review.client.name : 'Anonymous' }}</div> 
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

                <!-- RESERVATION ENGINE FORM -->
                <section id="reserve" class="pb-20 pt-10 px-6 max-w-4xl mx-auto">
                    <div class="text-center max-w-3xl mx-auto mb-16">
                            <div class="inline-block bg-purple-900/40 border border-purple-500/30 text-neon-purple px-4 py-1.5 rounded-full text-xs font-bold tracking-widest uppercase mb-4">
                                Instant Reservation
                            </div>
                            <h2 class=" text-4xl sm:text-5xl font-bold text-white mb-4">
                                Request <span class="gradient-text">Table Booking</span>?
                            </h2>
                            <p class="text-gray-400 text-sm sm:text-base ">
                                We Look Forward to Making Your Night Memorable.
                            </p>
                        </div>

                    <div class="bg-dark-card/90 backdrop-blur-2xl p-8 md:p-10 rounded-3xl border border-gray-800 relative shadow-2xl">
                        
                         <div class="text-center max-w-3xl mx-auto mb-16"> 
                            <h2 class=" text-3xl font-bold text-white mb-4">Let's Start <span class="gradient-text">Your Journey</span></h2> 
                        </div> 
                        
                        <form @submit.prevent="submitBooking" class="space-y-4">
                            <div :class="accessToken ? 'sm:grid-cols-1': 'sm:grid-cols-2'" class="grid grid-cols-1 gap-4">
                                <!-- Name -->
                                <div>   
                                    <label class="block text-xs font-semibold text-gray-400 mb-1">Full Name</label>
                                    <input type="text" v-model="form.name" required class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 text-white text-sm focus:outline-none focus:border-purple-500" placeholder="John Doe">
                                    <span v-if="errors.name" class="text-red-500 text-xs mt-1 block">@{{ errors.name[0] }}</span>
                                </div>

                                <!-- Contact info -->
                                <div v-if="! accessToken">
                                    <label class="block text-xs font-semibold text-gray-400 mb-1">Phone</label>
                                    <input type="tel" v-model="form.phone" required class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 text-white text-sm focus:outline-none focus:border-purple-500" placeholder="97XXXXXX62">
                                    <span v-if="errors.phone" class="text-red-500 text-xs mt-1 block">@{{ errors.phone[0] }}</span>
                                </div> 
                            </div>

                            <!-- Date, Preference Time -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-400 mb-1">Date</label>
                                    <input type="date" v-model="form.date" required class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 text-white text-sm focus:outline-none focus:border-purple-500">
                                    <span v-if="errors.booking_date" class="text-red-500 text-xs mt-1 block">@{{ errors.booking_date[0] }}</span>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-400 mb-1">Preference Arival Time <div class="inline-flex items-center gap-2 px-2 py-0.5 rounded-full text-[9px] bg-purple-900/30 border border-purple-500/30 text-purple-300  tracking-wider ">
                                        optional
                                    </div></label>
                                    <input type="time" v-model="form.start_time" class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 text-white text-sm focus:outline-none focus:border-purple-500">
                                    <span v-if="errors.start_time" class="text-red-500 text-xs mt-1 block">@{{ errors.start_time[0] }}</span>
                                </div>
                                
                            </div>

                            <!-- Guests -->
                            <div class="grid grid-cols-1 sm:grid-cols-1 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-400 mb-1">Guests
                                        <div class="inline-flex items-center gap-2 px-2 py-0.5 rounded-full bg-purple-900/30 border border-purple-500/30 text-purple-300   tracking-wider text-[9px]">
                                        optional
                                    </div>
                                    </label>
                                    <input type="number" min="1" v-model="form.guests" class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 text-white text-sm focus:outline-none focus:border-purple-500" placeholder="Number of guests">
                                    <span v-if="errors.guest_count" class="text-red-500 text-xs mt-1 block">@{{ errors.guest_count[0] }}</span>
                                </div>
                            </div>

                            <!-- Special Requests -->
                            <div>
                                <label class="block text-xs font-semibold text-gray-400 mb-1">Special Requests 
                                    <div class="inline-flex items-center gap-2 px-2 py-0.5 rounded-full bg-purple-900/30 border border-purple-500/30 text-purple-300 tracking-wider text-[9px]">
                                        optional
                                    </div>
                                </label>
                                <textarea v-model="form.special_requests" rows="3" class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 text-white text-sm focus:outline-none focus:border-purple-500" placeholder="Any special requests? (e.g. Birthday celebration, allergies)"></textarea>
                            </div>

                            <!-- OTP Field -->
                            <div v-if="showOtpField" class="mt-4 transition-all">
                                <label class="block text-xs font-semibold text-pink-400 mb-1">Enter OTP sent to your phone</label>
                                <input type="text" v-model="form.otp" maxlength="6" :required="showOtpField" class="w-full bg-gray-900/50 border border-pink-500/50 rounded-xl px-4 py-4 text-center text-white text-2xl tracking-[1em] font-mono focus:outline-none focus:border-neon-pink shadow-[0_0_15px_rgba(236,72,153,0.3)] transition" placeholder="------">
                                <span v-if="errors.otp" class="text-red-500 text-xs mt-1 block text-center">@{{ errors.otp[0] }}</span>
                                <div class="text-right mt-2">
                                    <button type="button" @click="resendOtp" :disabled="resendTimer > 0" class="text-xs font-semibold transition-colors" :class="resendTimer > 0 ? 'text-gray-500 cursor-not-allowed' : 'text-pink-400 hover:text-pink-300'">
                                        <span v-if="resendTimer > 0">Resend OTP in @{{ resendTimer }}s</span>
                                        <span v-else>Resend OTP</span>
                                    </button>
                                </div>
                            </div>

                            <!-- Submit -->
                            <button type="submit" :disabled="isLoading" class="w-full bg-gradient-to-r from-pink-500 to-purple-500 py-3.5 rounded-xl  font-bold text-white tracking-wider uppercase mt-4 hover:opacity-90 hover:scale-[1.01] transition flex items-center justify-center gap-2">
                                <span v-if="!isLoading && !showOtpField && accessToken">Verify & Book</span>
                                <span v-else-if="!isLoading && !showOtpField">Process</span>
                                <span v-else-if="!isLoading && showOtpField">Verify & Book</span>
                                <span v-else-if="isOtpSending && !showOtpField"><i class="fas fa-spinner fa-spin mr-1"></i> Sending OTP...</span>
                                <span v-else><i class="fas fa-spinner fa-spin mr-1"></i> Securing Pass...</span>
                            </button>
                        </form>
                    </div>
                </section>

                 

                <!-- FAQ ACCORDION -->
                <section id="faq" class="py-24 px-6 max-w-4xl mx-auto border-t border-white/5">
                    <div class="text-center mb-12">
                        <div class="inline-block bg-purple-900/30 border border-purple-500/30 text-neon-purple px-4 py-1.5 rounded-full text-xs font-bold tracking-widest uppercase mb-4">
                            Protocol & Policies
                        </div>
                        <h2 class=" text-4xl sm:text-5xl font-bold text-white mb-4">Got Questions?</h2>
                        <p class="text-gray-400 text-sm">Everything you need to know before stepping into The Midnight Club.</p>
                    </div>

                    <!-- Category Filter Tabs -->
                    <div class="flex items-center gap-2.5 overflow-x-auto pb-4 mb-8 snap-x sm:justify-center">
                        <button v-for="cat in faqCategories" :key="cat" 
                                @click="activeFaqCategory = cat; faqLimit = 6" 
                                :class="activeFaqCategory === cat ? 'bg-gradient-to-r from-purple-500 to-pink-500 text-white shadow-lg' : 'bg-dark-card text-gray-300 border border-gray-700 hover:border-purple-500'" 
                                class="px-5 py-2.5 rounded-full text-xs font-bold whitespace-nowrap transition snap-center">
                            @{{ cat }}
                        </button>
                    </div>

                    <div class="space-y-4">
                        <div v-for="faq in visibleFaqs" :key="faq.id" class="glass-card rounded-2xl overflow-hidden border border-gray-800 hover:border-pink-500/30 transition-colors shadow-lg">
                            <button @click="toggleFaq(faq.id)" class="w-full p-6 text-left flex items-start sm:items-center justify-between font-bold text-sm sm:text-base text-white hover:text-pink-400 transition gap-4 group">
                                <span class="flex-1">@{{ faq.question }}</span>
                                <div class="w-8 h-8 rounded-full bg-white/5 flex items-center justify-center shrink-0 group-hover:bg-pink-500/20 transition-colors">
                                    <i :class="faq.open ? 'rotate-180 text-pink-400' : 'text-gray-500 group-hover:text-pink-400'" class="fas fa-chevron-down text-xs transition-transform duration-300"></i>
                                </div>
                            </button>
                            <div v-show="faq.open" class="px-6 pb-6 text-sm text-gray-400 leading-relaxed border-t border-white/5 pt-4">
                                @{{ faq.answer }}
                            </div>
                        </div>
                    </div>

                    <div v-if="filteredFaqs.length > faqLimit" class="text-center mt-10">
                        <button @click="loadMoreFaqs" :disabled="faqLoading" class="px-8 py-3 rounded-2xl bg-dark-card border border-white/10 hover:border-purple-500 text-xs font-bold uppercase text-white transition inline-flex items-center justify-center gap-2 shadow-lg hover:shadow-purple-500/20">
                            <span v-if="faqLoading"><i class="fas fa-spinner fa-spin mr-2"></i> Loading...</span>
                            <span v-else>Load More FAQs <i class="fas fa-arrow-down text-pink-400 ml-1"></i></span>
                        </button>
                    </div>
                </section> 
            </main>

            <!-- CINEMA LIGHTBOX MODAL (Moved here for z-index) -->
            <div v-if="lightboxOpen" class="fixed inset-0 z-[60] backdrop-blur-3xl flex flex-col justify-between p-4 sm:p-8">
                <div class="flex items-center justify-between z-10">
                    <div class="flex items-center gap-3">
                        <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase bg-purple-500/20 text-purple-300 border border-purple-500/30">
                            @{{ activeImage.category }}
                        </span>
                        <span class="text-xs font-mono text-gray-400">@{{ activeLightboxIndex + 1 }} / @{{ filteredGallery.length }}</span>
                    </div>
                    <button @click="lightboxOpen = false" class="w-10 h-10 rounded-full bg-dark-card border border-white/10 flex items-center justify-center text-gray-300 hover:text-white">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="relative flex-1 flex items-center justify-center my-4">
                    <button @click="prevLightbox" class="absolute left-2 sm:left-6 z-20 w-12 h-12 rounded-full bg-midnight/70 border border-white/20 text-white flex items-center justify-center hover:scale-110 transition">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <video v-if="activeImage.isVideo" :src="activeImage.file_url" controls autoplay class="max-h-[72vh] max-w-full rounded-2xl shadow-2xl"></video>
                    <img v-else :src="activeImage.file_url || activeImage.image" :alt="activeImage.title" class="max-h-[72vh] max-w-full rounded-2xl object-contain shadow-2xl">
                    <button @click="nextLightbox" class="absolute right-2 sm:right-6 z-20 w-12 h-12 rounded-full bg-midnight/70 border border-white/20 text-white flex items-center justify-center hover:scale-110 transition">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
                <!--<div class="max-w-2xl mx-auto w-full text-center bg-dark-card/60 backdrop-blur-md p-4 rounded-2xl border border-white/10">
                    <h3 class=" text-base font-bold text-white mb-1">@{{ activeImage.title }}</h3>
                    <p class="text-xs text-gray-400">@{{ activeImage.desc }} • Captured by @{{ activeImage.photographer }}</p>
                </div>-->
            </div>

            <!-- TICKET CONFIRMATION PASS MODAL (WITH QR CODE) -->
            <div v-if="ticketModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4  backdrop-blur-2xl">
                <div class="glass-card rounded-3xl max-w-md w-full max-h-[90vh] overflow-y-auto p-6 sm:p-8 border border-white/20 text-center relative shadow-2xl">
                    <button @click="ticketModalOpen = false" class="absolute top-5 right-5 text-gray-400 hover:text-white">
                        <i class="fas fa-times"></i>
                    </button>

                    <div class="w-12 h-12 rounded-full bg-emerald-500/20 text-emerald-400 flex items-center justify-center mx-auto mb-3 text-xl border border-emerald-500/30">
                        <i class="fas fa-check"></i>
                    </div>

                    <h3 class=" text-xl font-bold text-white mb-1">Booking</h3>
                    <p class="text-xs text-gray-400 mb-5">We Look Forward to Making Your Night Memorable</p>

                    <!-- Pass Card -->
                    <div class="bg-midnight p-4 rounded-2xl border border-white/10 text-left mb-5">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <span class="text-[9px] text-pink-400 uppercase tracking-wider block font-bold">Pass Holder</span>
                                <span class="font-bold text-white text-sm">@{{ viewBooking?.name || '-' }}</span>
                            </div>
                            <div class="text-right">
                                <span class="text-[9px] text-gray-400 uppercase tracking-wider block">Guests</span>
                                <span class="font-bold text-xs text-white">@{{ viewBooking?.guests || '-' }}</span>
                            </div>
                        </div>

                        <div class="flex justify-between items-center text-xs text-gray-300 mb-3 pb-3 border-b border-white/10">
                            <div>
                                <span class="text-[9px] text-gray-400 block">Date</span>
                                <span class="font-semibold text-white">@{{ viewBooking?.date || '-' }}</span>
                            </div>
                            <div class="text-center">
                                <span class="text-[9px] text-gray-400 block">Time</span>
                                <span class="font-semibold text-white">@{{ viewBooking?.time || '-' }}</span>
                            </div> 
                        </div>

                        <div v-if="viewBooking?.special_requests" class="mb-3 text-[10px] text-gray-400 bg-gradient-to-r from-purple-900/40 to-pink-900/40 p-2.5 rounded-xl border border-pink-500/20 shadow-inner text-left">
                            <span class="text-pink-400 font-bold flex items-center gap-1.5 mb-1"><i class="fas fa-star text-xs"></i> Special Request</span>
                            <span class="italic">"@{{ viewBooking?.special_requests }}"</span>
                        </div>

                        <!-- Rendered QR -->
                        <div class="flex flex-col items-center justify-center p-3 bg-white rounded-xl">
                            <div id="vue-qrcode-target" class="w-32 h-32 flex items-center justify-center"></div>
                            <span class="text-[9px] text-gray-800 font-mono mt-1 font-bold">@{{ viewBooking?.plainPassCode }}</span>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3">
                        <button @click="downloadPass(viewBooking)" class="w-full py-3 rounded-xl bg-gradient-to-r from-pink-500 to-purple-500 hover:opacity-90 text-white font-bold text-xs uppercase transition shadow-[0_0_15px_rgba(236,72,153,0.4)]">
                            <i class="fas fa-download mr-1"></i> Download Pass
                        </button>
                        <button @click="copyPassInfo(viewBooking)" class="w-full py-3 rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold text-xs uppercase transition">
                            <i class="fas fa-copy mr-1"></i> Copy Info
                        </button>
                    </div>
                    
                    <!-- App Promo Disclaimer -->
                    <div class="mt-5 p-3 rounded-xl bg-purple-900/20 border border-purple-500/20 text-xs text-gray-300 text-left flex gap-3 items-center">
                        <div class="text-pink-400 text-xl"><i class="fab fa-google-play"></i></div>
                        <div>
                            <span class="font-bold text-white block mb-0.5">Enhance Your Experience</span>
                            Download the mobile app for instant notifications, guestlist management, and seamless entry. <a href="https://play.google.com/store/apps/details?id=com.themidnightclub.app" target="_blank" class="text-pink-400 font-bold hover:underline">Download Now</a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </script>

    <script type="module">
        frontendVueApp.component('v-home', {
            template: '#v-home-template',

            data() {
                return {
                    isAudioActive: false,
                    bpmLabel: 'MUTED',
                    isApiLoading: false,
                    isTestimonialsLoading: true,
                    isLoading: false,
                    isOtpSending: false,
                    ticketModalOpen: false,
                    faqLimit: 6,
                    faqLoading: false,
                    lightboxOpen: false,
                    activeLightboxIndex: 0,
                    activeCategory: 'all',
                    nextPageUrl: null,
                    passCode: '',
                    showOtpField: false,
                    accessToken: null,
                    errors: {},
                    resendTimer: 0,
                    timerInterval: null,
                    myPasses: [],
                    cursor: {
                        x: -300,
                        y: -300
                    },

                    form: {
                        name: '',
                        email: '',
                        phone: '',
                        otp: '',
                        date: new Date().toISOString().split('T')[0],
                        start_time: '',
                        guests: '',
                        special_requests: ''
                    },

                    viewBooking: null,

                    categories: [{
                            key: 'all',
                            label: 'All Moments'
                        },
                        {
                            key: 'image',
                            label: 'Images'
                        },
                        {
                            key: 'video',
                            label: 'Videos'
                        }
                    ],

                    galleryItems: [],

                    testimonials: [],

                    faqs: [{
                            id: 1,
                            category: "Timings & Location",
                            question: "What are the operating hours of the club?",
                            answer: "The club is open daily from 7:00 PM to 6:00 AM or 7:00 AM (depending on the night).",
                            open: false
                        },
                        {
                            id: 2,
                            category: "Timings & Location",
                            question: "Is it open on weekdays?",
                            answer: "Yes, the club operates 7 days a week for late-night partying.",
                            open: false
                        },
                        {
                            id: 3,
                            category: "Timings & Location",
                            question: "Where exactly is it located?",
                            answer: "It is located on the 3rd Floor, Plaza Mall, MG Road, Sushant Lok, Sector 28, Gurugram.",
                            open: false
                        },
                        {
                            id: 4,
                            category: "Timings & Location",
                            question: "Is the club open during the daytime?",
                            answer: "No, this is strictly a nightlife venue and only becomes active from 7:00 PM onwards.",
                            open: false
                        },
                        {
                            id: 5,
                            category: "Timings & Location",
                            question: "Do operational hours change on weekends?",
                            answer: "The starting time remains 7:00 PM, but weekend parties (Friday & Saturday) often extend up to 7:00 AM.",
                            open: false
                        },
                        {
                            id: 6,
                            category: "Timings & Location",
                            question: "How does it differ from other clubs in Gurugram?",
                            answer: "It is one of the very few clubs on MG Road permitted to operate legally through the entire night until the early morning hours.",
                            open: false
                        },
                        {
                            id: 7,
                            category: "Entry & Bookings",
                            question: "How much is the entry fee or cover charge?",
                            answer: "The general cover charge ranges between ₹1,000 to ₹2,000 per person, which is typically fully redeemable on food and drinks inside.",
                            open: false
                        },
                        {
                            id: 8,
                            category: "Entry & Bookings",
                            question: "Are stags (single guys) allowed entry?",
                            answer: "Management allow single, couples or mixed groups.",
                            open: false
                        },
                        {
                            id: 9,
                            category: "Entry & Bookings",
                            question: "Is entry free for girls?",
                            answer: "Girls can often get free entry via the guest list on designated nights, or during special promotional events like Ladies' Night.",
                            open: false
                        },
                        {
                            id: 10,
                            category: "Entry & Bookings",
                            question: "How can I reserve a VIP table?",
                            answer: "You can book tables or VIP lounges directly by calling their reservation desk at +91 9899281515.",
                            open: false
                        },
                        {
                            id: 11,
                            category: "Entry & Bookings",
                            question: "Do I need to book a table in advance?",
                            answer: "Prior booking is strongly recommended for Fridays and Saturdays due to high weekend footfall.",
                            open: false
                        },
                        {
                            id: 12,
                            category: "Entry & Bookings",
                            question: "Is there a guest list facility available?",
                            answer: "Yes, couples can get on the guest list through authorized club promoters, usually valid for entry before 11:00 PM.",
                            open: false
                        },
                        {
                            id: 13,
                            category: "Dress Code & Rules",
                            question: "Is there a specific dress code?",
                            answer: "Yes, the dress code is Smart Casuals or Party Wear. Slippers, sandals, shorts, and sportswear for men are strictly prohibited.",
                            open: false
                        },
                        {
                            id: 14,
                            category: "Dress Code & Rules",
                            question: "Can men wear traditional attire like Kurta-Pyjamas?",
                            answer: "No, ethnic wear, heavy sportswear, tracksuits, and open-toed sandals for men do not comply with the club's dress code policy.",
                            open: false
                        },
                        {
                            id: 15,
                            category: "Dress Code & Rules",
                            question: "What is the minimum age requirement for entry?",
                            answer: "Since alcohol is served, guests must meet the legal drinking age requirement (typically 21 to 25 years depending on the specific event night).",
                            open: false
                        },
                        {
                            id: 16,
                            category: "Dress Code & Rules",
                            question: "Is an ID proof mandatory?",
                            answer: "Yes, you must carry a physical or digital copy of a valid government ID (Aadhaar Card, Driving License, or Passport) for age verification.",
                            open: false
                        },
                        {
                            id: 17,
                            category: "Dress Code & Rules",
                            question: "Is smoking allowed inside the club?",
                            answer: "Smoking is strictly prohibited on the main dance floor and seating areas. However, there is a dedicated indoor smoking room available.",
                            open: false
                        },
                        {
                            id: 18,
                            category: "Dress Code & Rules",
                            question: "Am I allowed to bring outside food or beverages?",
                            answer: "No, outside food, drinks, or personal alcohol bottles are strictly prohibited and will be confiscated at the security check.",
                            open: false
                        },
                        {
                            id: 19,
                            category: "Food, Drinks & Vibe",
                            question: "What kind of cuisine do they serve?",
                            answer: "They serve Multi-cuisine food, predominantly focusing on North Indian, Chinese finger foods, and continental appetizers.",
                            open: false
                        },
                        {
                            id: 20,
                            category: "Food, Drinks & Vibe",
                            question: "Is alcohol served at the venue?",
                            answer: "Yes, they feature a fully stocked bar with an extensive menu of domestic and imported spirits, beers, wines, and signature cocktails.",
                            open: false
                        },
                        {
                            id: 21,
                            category: "Food, Drinks & Vibe",
                            question: "What music genre does the DJ play?",
                            answer: "The club features a live DJ playing a high-energy mix of Bollywood remixes, Punjabi hits, Commercial pop, and Hip-Hop tracks.",
                            open: false
                        },
                        {
                            id: 22,
                            category: "Food, Drinks & Vibe",
                            question: "Does the venue have a dedicated dance floor?",
                            answer: "Yes, it features a spacious central dance floor equipped with dynamic laser light shows and a powerful sound system.",
                            open: false
                        },
                        {
                            id: 23,
                            category: "Food, Drinks & Vibe",
                            question: "Can I host a private party or corporate event here?",
                            answer: "Yes, the venue accepts private bookings for birthdays, bachelor parties, or corporate mixers. You can reserve the VIP lounge or the entire hall.",
                            open: false
                        },
                        {
                            id: 24,
                            category: "Food, Drinks & Vibe",
                            question: "Do they host live band performances?",
                            answer: "While most nights feature club DJs, they occasionally host special event nights featuring popular live artists and Punjabi music singers.",
                            open: false
                        },
                        {
                            id: 25,
                            category: "Facilities & Transit",
                            question: "Is parking available at the venue?",
                            answer: "Yes, guests can park inside the Plaza Mall parking lot. Valet parking assistance is also available at the mall's main entrance.",
                            open: false
                        },
                        {
                            id: 26,
                            category: "Facilities & Transit",
                            question: "Which is the nearest metro station?",
                            answer: "The MG Road Metro Station (Yellow Line) is the closest station, located just a short walk or auto-rickshaw ride away from the mall.",
                            open: false
                        },
                        {
                            id: 27,
                            category: "Facilities & Transit",
                            question: "Can I easily find a cab (Uber/Ola) late at night?",
                            answer: "Yes, because it is centrally located on MG Road, Uber, Ola, and local taxis are readily available outside the mall right through the morning.",
                            open: false
                        },
                        {
                            id: 28,
                            category: "Facilities & Transit",
                            question: "Is free Wi-Fi provided inside the club?",
                            answer: "Yes, the club provides high-speed complimentary Wi-Fi for all guests since cellular network reception can get weak inside basement/mall structures.",
                            open: false
                        },
                        {
                            id: 29,
                            category: "Facilities & Transit",
                            question: "What are the security arrangements like?",
                            answer: "The venue has strict security with professional male and female bouncers, mandatory metal detectors, and full CCTV coverage to ensure a safe environment.",
                            open: false
                        },
                        {
                            id: 30,
                            category: "Facilities & Transit",
                            question: "What payment methods are accepted?",
                            answer: "They accept Cash, Credit/Debit cards, and all major UPI options (Google Pay, PhonePe, Paytm).",
                            open: false
                        }
                    ],
                    faqCategories: ['All', 'Timings & Location', 'Entry & Bookings', 'Dress Code & Rules', 'Food, Drinks & Vibe', 'Facilities & Transit'],
                    activeFaqCategory: 'All',

                    mockGalleryDatabase: [{
                            id: 1,
                            category: 'vip',
                            title: 'Sparkler Bottle Parade',
                            desc: 'Vintage Dom Pérignon luminous edition with personal pyrotechnic bottle escort.',
                            image: 'https://images.unsplash.com/photo-1510812431401-41d2bd2722f3?q=80&w=1000&auto=format&fit=crop',
                            date: 'SEP 2026',
                            likes: 428,
                            photographer: 'Alex Neon'
                        },
                        {
                            id: 2,
                            category: 'djs',
                            title: 'Techno Mainstage Euphoria',
                            desc: 'Peak hour 140 BPM driving techno set on the custom quadraphonic soundsystem.',
                            image: 'https://images.unsplash.com/photo-1516450360452-9312f5e86fc7?q=80&w=1000&auto=format&fit=crop',
                            date: 'AUG 2026',
                            likes: 819,
                            photographer: 'Elena Visuals'
                        },
                        {
                            id: 3,
                            category: 'lighting',
                            title: '3D Laser Wave Ceiling',
                            desc: 'Synchronized kinetic lasers slicing through smoke clouds over the main room.',
                            image: 'https://images.unsplash.com/photo-1508700115892-45ecd05ae2ad?q=80&w=1000&auto=format&fit=crop',
                            date: 'AUG 2026',
                            likes: 642,
                            photographer: 'BeamLab'
                        },
                        {
                            id: 4,
                            category: 'crowd',
                            title: 'The Midnight Rush Hour',
                            desc: 'Over 1,200 nocturnal partygoers experiencing sonic euphoria at 2:30 AM.',
                            image: 'https://images.unsplash.com/photo-1492684223066-81342ee5ff30?q=80&w=1000&auto=format&fit=crop',
                            date: 'JUL 2026',
                            likes: 955,
                            photographer: 'K. Mercer'
                        },
                        {
                            id: 5,
                            category: 'vip',
                            title: 'Skybox Private Penthouse',
                            desc: 'VIP guests enjoying panoramic views of the dancefloor with mixologist service.',
                            image: 'https://images.unsplash.com/photo-1566737236500-c8ac43014a67?q=80&w=1000&auto=format&fit=crop',
                            date: 'JUL 2026',
                            likes: 512,
                            photographer: 'Alex Neon'
                        },
                        {
                            id: 6,
                            category: 'djs',
                            title: 'Vinyl & Modular Synth Session',
                            desc: 'Underground minimal and deep house warm-up set on rare rotary mixers.',
                            image: 'https://images.unsplash.com/photo-1470225620780-dba8ba36b745?q=80&w=1000&auto=format&fit=crop',
                            date: 'JUN 2026',
                            likes: 384,
                            photographer: 'Sonic Lens'
                        },
                        {
                            id: 7,
                            category: 'crowd',
                            title: 'Confetti Canon Explosion',
                            desc: 'Midnight countdown countdown celebrated with bio-degradable metallic rain.',
                            image: 'https://images.unsplash.com/photo-1514525253161-7a46d19cd819?q=80&w=1000&auto=format&fit=crop',
                            date: 'JUN 2026',
                            likes: 720,
                            photographer: 'Elena Visuals'
                        },
                        {
                            id: 8,
                            category: 'lighting',
                            title: 'Kinetic Hologram Matrix',
                            desc: 'Ceiling mounted LED clusters moving organically in synchrony with bass oscillations.',
                            image: 'https://images.unsplash.com/photo-1574096079513-d8259312b785?q=80&w=1000&auto=format&fit=crop',
                            date: 'MAY 2026',
                            likes: 603,
                            photographer: 'BeamLab'
                        },
                        {
                            id: 9,
                            category: 'vip',
                            title: 'Ace of Spades Champagne Glow',
                            desc: 'Special edition Armand de Brignac celebration at the Backstage Artist Lounge.',
                            image: 'https://images.unsplash.com/photo-1517457373958-b7bdd4587205?q=80&w=1000&auto=format&fit=crop',
                            date: 'MAY 2026',
                            likes: 890,
                            photographer: 'Alex Neon'
                        },
                        {
                            id: 10,
                            category: 'djs',
                            title: 'International Guest DJ Drops The Bass',
                            desc: 'Electrifying stage performance during our monthly international residency night.',
                            image: 'https://images.unsplash.com/photo-1545128485-c400e7702796?q=80&w=1000&auto=format&fit=crop',
                            date: 'APR 2026',
                            likes: 1120,
                            photographer: 'Sonic Lens'
                        },
                        {
                            id: 11,
                            category: 'crowd',
                            title: 'Dancefloor Trance Hands Up',
                            desc: 'Pure collective ecstasy as the melodic breakdown resolves into the final drop.',
                            image: 'https://images.unsplash.com/photo-1470225620780-dba8ba36b745?q=80&w=1000&auto=format&fit=crop',
                            date: 'APR 2026',
                            likes: 840,
                            photographer: 'K. Mercer'
                        }
                    ],

                    visualizerAnimationId: null
                };
            },

            computed: {
                filteredFaqs() {
                    if (this.activeFaqCategory === 'All') return this.faqs;
                    return this.faqs.filter(faq => faq.category === this.activeFaqCategory);
                },
                visibleFaqs() {
                    return this.filteredFaqs.slice(0, this.faqLimit);
                },
                filteredGallery() {
                    if (this.activeCategory === 'all') return this.galleryItems;
                    return this.galleryItems.filter(item => item.category === this.activeCategory);
                },
                visibleGallery() {
                    return this.filteredGallery;
                },
                activeImage() {
                    return this.filteredGallery[this.activeLightboxIndex] || {};
                }
            },

            mounted() {
                this.initVisualizer();
                this.loadMyPasses();
                window.addEventListener('mousemove', this.handleMouseMove);

                this.handleResize = () => {};
                window.addEventListener('resize', this.handleResize);

                this.fetchTestimonials();
                this.fetchGalleryApi();

                // Get token if has.
                this.accessToken = sessionStorage.getItem('access_token');
            },

            beforeUnmount() {
                if (this.visualizerAnimationId) cancelAnimationFrame(this.visualizerAnimationId);
                window.removeEventListener('mousemove', this.handleMouseMove);
                window.removeEventListener('resize', this.handleResize);
            },

            methods: {

                resetBookingForm() {
                    this.showOtpField = false;

                    this.form = {
                        name: '',
                        email: '',
                        phone: '',
                        otp: '',
                        date: new Date().toISOString().split('T')[0],
                        start_time: '',
                        guests: '',
                        special_requests: ''
                    }
                },
                handleMouseMove(e) {
                    this.cursor.x = e.clientX;
                    this.cursor.y = e.clientY;
                },

                toggleAudioMode() {
                    this.isAudioActive = !this.isAudioActive;
                    this.bpmLabel = this.isAudioActive ? '138 BPM' : 'MUTED';
                    const audio = this.$refs.bgMusic;
                    if (audio) {
                        if (this.isAudioActive) {
                            audio.play().catch(e => console.log('Audio play failed:', e));
                        } else {
                            audio.pause();
                        }
                    }
                },

                loadMoreFaqs() {
                    this.faqLoading = true;
                    setTimeout(() => {
                        this.faqLimit += 6;
                        this.faqLoading = false;
                    }, 500);
                },

                selectTier(tier) {
                    this.form.tier = tier.name;
                    const el = document.getElementById('reserve');
                    if (el) el.scrollIntoView({
                        behavior: 'smooth'
                    });
                },

                toggleFaq(id) {
                    const faq = this.faqs.find(f => f.id === id);
                    if (faq) faq.open = !faq.open;
                },

                openLightbox(index) {
                    this.activeLightboxIndex = index;
                    this.lightboxOpen = true;
                },

                prevLightbox() {
                    this.activeLightboxIndex = (this.activeLightboxIndex - 1 + this.filteredGallery.length) % this.filteredGallery.length;
                },

                nextLightbox() {
                    this.activeLightboxIndex = (this.activeLightboxIndex + 1) % this.filteredGallery.length;
                },



                async fetchTestimonials() {
                    this.isTestimonialsLoading = true;
                    fetch('/frontend/testimonials')
                        .then(res => res.json())
                        .then(data => {
                            this.testimonials = data.data || [];
                        })
                        .catch(err => console.error("Error fetching testimonials", err))
                        .finally(() => {
                            this.isTestimonialsLoading = false;
                        });
                },

                async fetchGalleryApi(url = null, reset = false) {
                    if (this.isApiLoading) return;
                    this.isApiLoading = true;
                    if (reset) {
                        this.galleryItems = [];
                        this.nextPageUrl = null;
                    }
                    let fetchUrl = url || '/frontend/club-assets';

                    if (this.activeCategory !== 'all' && !fetchUrl.includes('file_type=')) {
                        fetchUrl += (fetchUrl.includes('?') ? '&' : '?') + `file_type=${this.activeCategory}`;
                    }

                    fetch(fetchUrl)
                        .then(res => res.json())
                        .then(data => {
                            if (data && data.data && data.data.length > 0) {
                                const newItems = data.data.map((item, index) => {
                                    const isVideo = item.file_url && item.file_url.match(/\.(mp4|webm|ogg|mov)$/i);
                                    return {
                                        id: this.galleryItems.length + index,
                                        file_url: item.file_url,
                                        isVideo: !!isVideo,
                                        category: isVideo ? 'video' : 'image',
                                        title: isVideo ? 'Club Video' : 'Club Image',
                                        desc: 'A moment captured at The Midnight Club.',
                                        photographer: 'Club Lens'
                                    };
                                });
                                this.galleryItems = [...this.galleryItems, ...newItems];
                            }
                            this.nextPageUrl = data.next_page_url || null;
                            this.isApiLoading = false;
                        })
                        .catch(err => {
                            console.error(err);
                            this.isApiLoading = false;
                        });
                },

                loadMyPasses() {
                    const stored = localStorage.getItem('tmc_passes');
                    if (stored) {
                        try {
                            const passes = JSON.parse(stored);
                            const now = new Date();
                            this.myPasses = passes.filter(p => {
                                if (!p.createdAt) return false;
                                const diffTime = Math.abs(now - new Date(p.createdAt));
                                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                                return diffDays <= 3;
                            });
                            localStorage.setItem('tmc_passes', JSON.stringify(this.myPasses));
                        } catch (e) {
                            this.myPasses = [];
                        }
                    }
                },

                viewStoredPass(pass) {
                    this.viewBooking = pass;
                    this.passCode = pass.passCode;
                    this.ticketModalOpen = true;

                    this.$nextTick(() => {
                        const target = document.getElementById('vue-qrcode-target');
                        if (target) {
                            target.innerHTML = '';
                            new QRCode(target, {
                                // text: JSON.stringify({
                                //     qr_code_id: this.passCode,
                                // }),
                                text: this.passCode,
                                width: 120,
                                height: 120,
                                colorDark: "#0b0c10",
                                colorLight: "#ffffff",
                                correctLevel: QRCode.CorrectLevel.H
                            });
                        }
                    });
                },

                downloadPass(data) {
                    const qrcodeTarget = document.getElementById('vue-qrcode-target');
                    const img = qrcodeTarget?.querySelector('img');

                    if (!data?.plainPassCode) {
                        alert('Invalid qr code found.');
                        return;
                    }

                    if (!img?.src) {
                        alert('QR code image not found.');
                        return;
                    }

                    const canvas = document.createElement('canvas');
                    canvas.width = 400;
                    canvas.height = 650;
                    const ctx = canvas.getContext('2d');

                    const gradient = ctx.createLinearGradient(0, 0, 0, canvas.height);
                    gradient.addColorStop(0, '#0b0c10');
                    gradient.addColorStop(1, '#1f293d');
                    ctx.fillStyle = gradient;
                    ctx.fillRect(0, 0, canvas.width, canvas.height);

                    ctx.strokeStyle = '#ec4899';
                    ctx.lineWidth = 2;
                    ctx.strokeRect(20, 20, canvas.width - 40, canvas.height - 40);

                    const logoSrc = "<?php echo logo(); ?>";

                    // Helper function to load images asynchronously
                    const loadImage = (src) => {
                        return new Promise((resolve, reject) => {
                            const image = new Image();
                            image.crossOrigin = 'Anonymous';
                            image.onload = () => resolve(image);
                            image.onerror = (err) => reject(err);
                            image.src = src;
                        });
                    };

                    // Load both images and draw to canvas
                    Promise.all([loadImage(logoSrc), loadImage(img.src)])
                        .then(([logoImg, qrImg]) => {
                            // --- Enlarged Top Header Logo ---
                            // Fits within max dimensions (220x65) while preserving natural aspect ratio
                            const maxLogoWidth = 220;
                            const maxLogoHeight = 65;
                            const scale = Math.min(maxLogoWidth / logoImg.naturalWidth, maxLogoHeight / logoImg.naturalHeight);
                            const logoWidth = logoImg.naturalWidth * scale;
                            const logoHeight = logoImg.naturalHeight * scale;

                            const logoX = (canvas.width - logoWidth) / 2;
                            const logoY = 32; // Top offset
                            ctx.drawImage(logoImg, logoX, logoY, logoWidth, logoHeight);

                            // --- Subtitle: DIGITAL PASS ---
                            ctx.fillStyle = '#ec4899';
                            ctx.font = 'bold 13px Arial';
                            ctx.textAlign = 'center';
                            ctx.fillText('DIGITAL PASS', canvas.width / 2, logoY + logoHeight + 18);

                            // --- QR Code Section ---
                            ctx.fillStyle = '#ffffff';
                            ctx.fillRect(100, 135, 200, 200);
                            ctx.drawImage(qrImg, 110, 145, 180, 180);

                            // --- Passcode ---
                            ctx.textAlign = 'center';
                            ctx.fillStyle = '#a8a8a8';
                            ctx.font = 'bold 12px monospace';
                            ctx.fillText(data?.plainPassCode || '', canvas.width / 2, 355);

                            // --- Pass Holder ---
                            ctx.textAlign = 'left';
                            ctx.fillStyle = '#ec4899';
                            ctx.font = '12px Arial';
                            ctx.fillText('PASS HOLDER', 50, 420);
                            ctx.fillStyle = '#ffffff';
                            ctx.font = 'bold 16px Arial';
                            ctx.fillText(data?.name || '-', 50, 440);

                            // --- Guests ---
                            ctx.textAlign = 'right';
                            ctx.fillStyle = '#a8a8a8';
                            ctx.font = '12px Arial';
                            ctx.fillText('GUESTS', canvas.width - 50, 420);
                            ctx.fillStyle = '#ffffff';
                            ctx.font = 'bold 14px Arial';
                            ctx.fillText(data?.guests || '-', canvas.width - 50, 440);

                            // --- Date ---
                            ctx.textAlign = 'left';
                            ctx.fillStyle = '#a8a8a8';
                            ctx.font = '12px Arial';
                            ctx.fillText('DATE', 50, 500);
                            ctx.fillStyle = '#ffffff';
                            ctx.font = 'bold 14px Arial';
                            ctx.fillText(data?.date || '-', 50, 520);

                            // --- Arrival ---
                            ctx.textAlign = 'right';
                            ctx.fillStyle = '#a8a8a8';
                            ctx.font = '12px Arial';
                            ctx.fillText('ARRIVAL', canvas.width - 50, 500);
                            ctx.fillStyle = '#ffffff';
                            ctx.font = 'bold 14px Arial';
                            ctx.fillText(data?.time || '-', canvas.width - 50, 520);

                            // --- Footer ---
                            ctx.textAlign = 'center';
                            ctx.fillStyle = '#666666';
                            ctx.font = '10px Arial';
                            ctx.fillText('Thank you for booking with The Midnight Club', canvas.width / 2, 600);

                            // --- Trigger Download ---
                            const a = document.createElement('a');
                            a.download = `MidnightClub_Pass_${data?.plainPassCode}.png`;
                            a.href = canvas.toDataURL('image/png');
                            document.body.appendChild(a);
                            a.click();
                            document.body.removeChild(a);
                        })
                        .catch((err) => {
                            console.error('Error loading pass assets:', err);
                        });
                },
                startResendTimer() {
                    this.resendTimer = 60;
                    if (this.timerInterval) clearInterval(this.timerInterval);
                    this.timerInterval = setInterval(() => {
                        if (this.resendTimer > 0) {
                            this.resendTimer--;
                        } else {
                            clearInterval(this.timerInterval);
                        }
                    }, 1000);
                },

                async resendOtp() {
                    if (this.resendTimer > 0) return;

                    this.isLoading = true;
                    try {
                        await this.$axios.post('/api/auth/send-otp', {
                            phone: this.form.phone
                        });
                        this.startResendTimer();
                        alert('OTP resent successfully!');
                    } catch (error) {
                        if (error.response?.status === 422 && error.response?.data?.errors) {
                            this.errors = error.response.data.errors;
                        } else {
                            let msg = 'Failed to resend OTP.';
                            if (error.response?.data?.message) {
                                msg = error.response.data.message;
                            }
                            alert(msg);
                        }
                    } finally {
                        this.isLoading = false;
                    }
                },

                async submitBooking() {
                    this.errors = {};

                    if (!this.form.name) this.errors.name = ['Name is required'];
                    if (!this.form.date) this.errors.booking_date = ['Date is required'];
                    // if (!this.form.guests || this.form.guests <= 0) this.errors.guest_count = ['Valid number of guests is required'];
                    if (this.showOtpField && !this.form.otp) this.errors.otp = ['OTP is required'];

                    if (!this.accessToken) {
                        if (!this.form.phone) {
                            this.errors.phone = ['Phone is required'];
                        } else if (!/^\d{10}$/.test(this.form.phone)) {
                            this.errors.phone = ['Phone number must be exactly 10 digits'];
                        }
                    }

                    if (Object.keys(this.errors).length > 0) {
                        return;
                    }

                    if (!this.showOtpField && !this.accessToken) {
                        this.isOtpSending = true;
                        try {
                            const response = await this.$axios.post('/api/auth/send-otp', {
                                phone: this.form.phone
                            });
                            this.showOtpField = true;
                            this.startResendTimer();
                        } catch (error) {
                            if (error.response?.status === 422 && error.response?.data?.errors) {
                                this.errors = error.response.data.errors;
                            } else {
                                let msg = 'Failed to send OTP.';
                                if (error.response?.data?.message) {
                                    msg = error.response.data.message;
                                }
                                alert(msg);
                            }
                        } finally {
                            this.isOtpSending = false;
                        }
                    } else {
                        this.isLoading = true;

                        try {
                            if (!this.accessToken) {
                                // 1. Verify OTP & Login
                                const loginRes = await this.$axios.post('/api/auth/login-otp', {
                                    phone: this.form.phone,
                                    otp: this.form.otp,
                                    name: this.form.name
                                });

                                this.accessToken = loginRes?.data?.access_token;
                                sessionStorage.setItem('access_token', this.accessToken);
                            }

                            // 2. Submit Booking
                            const bookRes = await this.$axios.post('/api/bookings', {
                                club_id: 1,
                                booking_date: this.form.date,
                                start_time: this.form.start_time,
                                guest_count: parseInt(this.form.guests) || 4,
                                special_requests: this.form.special_requests ?? ''
                            }, {
                                headers: {
                                    'Authorization': `Bearer ${this.accessToken}`
                                }
                            });

                            // 3. Generate Digital Pass
                            let successBooking = bookRes.data.booking;
                            this.passCode = successBooking.qr_code;

                            const newPass = {
                                passCode: this.passCode,
                                plainPassCode: successBooking.plain_qr_code,
                                name: this.form.name,
                                special_requests: successBooking.special_requests,
                                date: this.form.date,
                                time: this.form.start_time,
                                guests: this.form.guests,
                                createdAt: new Date().toISOString()
                            };
                            this.myPasses.unshift(newPass);
                            localStorage.setItem('tmc_passes', JSON.stringify(this.myPasses));

                            // this.ticketModalOpen = true;
                            this.viewStoredPass(newPass);
                            this.resetBookingForm();
                        } catch (error) {
                            if (error.response?.status === 422 && error.response?.data?.errors) {
                                this.errors = error.response.data.errors;
                            } else if (error.response?.status === 401) {
                                this.accessToken = null;
                                this.submitBooking();
                            } else {
                                let msg = 'An error occurred during booking or login.';
                                if (error.response?.data?.message) {
                                    msg = error.response.data.message;
                                }
                                alert(msg);
                            }
                        } finally {
                            this.isLoading = false;
                        }
                    }
                },

                copyPassInfo(data) {
                    const text = `*** THE MIDNIGHT CLUB PASS ***\nCode: ${this.passCode}\nHolder: ${data?.name}\nSpecial Request: ${data?.special_requests}\nDate: ${data?.date}\nTime: ${data?.time}`;
                    navigator.clipboard.writeText(text).then(() => {
                        alert('VIP Digital Pass copied to clipboard!');
                    });
                },

                initVisualizer() {
                    this.$nextTick(() => {
                        const canvas = this.$refs.visualizerCanvas;
                        if (!canvas) return;
                        const ctx = canvas.getContext('2d');
                        let step = 0;

                        const draw = () => {
                            if (canvas.parentElement && (canvas.width !== canvas.parentElement.offsetWidth || canvas.height !== canvas.parentElement.offsetHeight)) {
                                canvas.width = canvas.parentElement.offsetWidth;
                                canvas.height = canvas.parentElement.offsetHeight;
                            }

                            if (canvas.width === 0 || canvas.height === 0) {
                                this.visualizerAnimationId = requestAnimationFrame(draw);
                                return;
                            }

                            ctx.clearRect(0, 0, canvas.width, canvas.height);
                            const w = canvas.width;
                            const h = canvas.height;
                            const bars = Math.floor(w / 8);
                            step += this.isAudioActive ? 0.045 : 0.005;

                            for (let i = 0; i < bars; i++) {
                                const x = i * 8;
                                const freq1 = Math.sin(i * 0.12 + step) * 26;
                                const freq2 = Math.cos(i * 0.06 - step * 1.5) * 20;
                                const barHeight = this.isAudioActive ? Math.max(5, Math.abs(freq1 + freq2) * (h / 65)) : 4;

                                const gradient = ctx.createLinearGradient(0, h, 0, h - barHeight);
                                gradient.addColorStop(0, '#0b0c10');
                                gradient.addColorStop(0.5, '#a855f7');
                                gradient.addColorStop(1, '#ec4899');

                                ctx.fillStyle = gradient;
                                ctx.fillRect(x, h - barHeight, 4, barHeight);
                            }
                            this.visualizerAnimationId = requestAnimationFrame(draw);
                        };
                        draw();
                    });
                }
            }
        });
    </script>
    @endPushOnce
</x-frontend::layouts>