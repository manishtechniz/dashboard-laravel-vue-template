<x-frontend::layouts>
    <v-home></v-home>

    @pushOnce('styles')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet">
    <style>
        :root {
            --font-instrument-serif: "Instrument Serif", "Instrument Serif Fallback", ui-serif, Georgia, serif;
        }

        .font-serif-club {
            font-family: var(--font-instrument-serif);
            font-style: italic;
            font-weight: 400;
        }

        @keyframes tmc-drift1 {

            0%,
            100% {
                transform: translate(0, 0) scale(1)
            }

            50% {
                transform: translate(60px, -40px) scale(1.15)
            }
        }

        @keyframes tmc-drift2 {

            0%,
            100% {
                transform: translate(0, 0) scale(1)
            }

            50% {
                transform: translate(-50px, 50px) scale(1.1)
            }
        }

        .tmc-orb-a {
            animation: tmc-drift1 14s ease-in-out infinite
        }

        .tmc-orb-b {
            animation: tmc-drift2 18s ease-in-out infinite
        }

        @keyframes tmc-glow {

            0%,
            100% {
                text-shadow: 0 0 30px rgba(168, 85, 247, .55), 0 0 70px rgba(236, 72, 153, .25)
            }

            50% {
                text-shadow: 0 0 55px rgba(236, 72, 153, .75), 0 0 110px rgba(168, 85, 247, .4)
            }
        }

        .tmc-glow-title {
            animation: tmc-glow 4.5s ease-in-out infinite
        }

        @keyframes tmc-spin {
            from {
                transform: rotate(0deg)
            }

            to {
                transform: rotate(360deg)
            }
        }

        .tmc-vinyl {
            animation: tmc-spin 6s linear infinite;
            transform-origin: 160px 160px
        }

        @keyframes tmc-arm {

            0%,
            100% {
                transform: rotate(-8deg)
            }

            50% {
                transform: rotate(2deg)
            }
        }

        .tmc-tonearm {
            animation: tmc-arm 8s ease-in-out infinite;
            transform-origin: 88% 18%
        }

        @keyframes tmc-eq {

            0%,
            100% {
                height: 15%
            }

            50% {
                height: 100%
            }
        }

        .tmc-eq-bar {
            animation: tmc-eq 1s ease-in-out infinite
        }

        @keyframes tmc-sweep {
            0% {
                transform: rotate(-25deg) translateX(-10%);
                opacity: .15
            }

            50% {
                opacity: .4
            }

            100% {
                transform: rotate(25deg) translateX(10%);
                opacity: .15
            }
        }

        .tmc-laser {
            animation: tmc-sweep 7s ease-in-out infinite alternate;
            transform-origin: top center
        }

        @keyframes tmc-glint {

            0%,
            100% {
                opacity: .15
            }

            50% {
                opacity: .9
            }
        }

        .tmc-glint {
            animation: tmc-glint 2.4s ease-in-out infinite
        }

        @keyframes tmc-bounce {

            0%,
            100% {
                transform: translateY(0)
            }

            50% {
                transform: translateY(-10px)
            }
        }

        .tmc-crowd-silhouette path {
            animation: tmc-bounce 2.4s ease-in-out infinite
        }

        @keyframes tmc-float {

            0%,
            100% {
                transform: translateY(0)
            }

            50% {
                transform: translateY(-12px)
            }
        }

        .tmc-float {
            animation: tmc-float 6s ease-in-out infinite
        }

        @keyframes tmc-pulse-dot {
            0% {
                box-shadow: 0 0 0 0 rgba(236, 72, 153, .6)
            }

            70% {
                box-shadow: 0 0 0 10px rgba(236, 72, 153, 0)
            }

            100% {
                box-shadow: 0 0 0 0 rgba(236, 72, 153, 0)
            }
        }

        .tmc-live-dot {
            animation: tmc-pulse-dot 2s infinite
        }

        @keyframes tmc-marquee {
            from {
                transform: translateX(0)
            }

            to {
                transform: translateX(-50%)
            }
        }

        .tmc-marquee-track {
            animation: tmc-marquee 30s linear infinite
        }

        .tmc-reveal {
            opacity: 0;
            transform: translateY(28px);
            transition: opacity .7s cubic-bezier(.16, 1, .3, 1), transform .7s cubic-bezier(.16, 1, .3, 1)
        }

        .tmc-reveal.in {
            opacity: 1;
            transform: translateY(0)
        }

        .tmc-grad-border {
            position: relative
        }

        .tmc-grad-border::before {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: inherit;
            padding: 1px;
            background: linear-gradient(135deg, rgba(168, 85, 247, .6), rgba(236, 72, 153, .15), transparent 60%);
            -webkit-mask: linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            pointer-events: none
        }

        .tmc-accordion-content {
            max-height: 0;
            overflow: hidden;
            transition: max-height .4s ease
        }

        .tmc-accordion.open .tmc-accordion-content {
            max-height: 220px
        }

        .tmc-accordion .tmc-chev {
            transition: transform .3s ease
        }

        .tmc-accordion.open .tmc-chev {
            transform: rotate(45deg)
        }

        @media (prefers-reduced-motion: reduce) {

            .tmc-orb-a,
            .tmc-orb-b,
            .tmc-glow-title,
            .tmc-vinyl,
            .tmc-tonearm,
            .tmc-eq-bar,
            .tmc-laser,
            .tmc-glint,
            .tmc-crowd-silhouette path,
            .tmc-float,
            .tmc-live-dot,
            .tmc-marquee-track {
                animation: none !important
            }

            .tmc-reveal {
                opacity: 1;
                transform: none;
                transition: none
            }
        }
    </style>
    @endPushOnce

    @pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-home-template">

        <!-- Hero Section -->
        <section class="relative pt-32 pb-24 px-6 overflow-hidden">
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
                    <h1 class="tmc-glow-title text-5xl md:text-7xl font-extrabold tracking-tight mb-5 leading-tight">
                        Elevate your nightlife.<br>
                        <span class="gradient-text">Book VIP tables &amp; events.</span>
                    </h1>
                    <p class="font-serif-club text-2xl md:text-3xl text-white/70 mb-6">the city's tables, your name on the list.</p>
                    <p class="text-gray-400 text-lg max-w-xl mx-auto lg:mx-0 mb-10">
                        Reserve premium tables instantly, catch the DJ lineup before anyone else, and walk into <b>The Midnight Club</b> already on the guest list.
                    </p>

                    <div class="flex flex-col sm:flex-row justify-center lg:justify-start gap-4">
                        <a href="https://play.google.com/store/apps/details?id=com.themidnightclub.app" target="_blank"
                            class="flex items-center justify-center bg-gray-900 border border-gray-700 hover:border-purple-500 px-6 py-3.5 rounded-xl glow transition">
                            <i class="fab fa-google-play text-xl mr-3 text-neonPink"></i>
                            <div class="text-left">
                                <div class="text-xs text-gray-400">GET IT ON</div>
                                <div class="text-sm font-semibold text-white">Google Play</div>
                            </div>
                        </a>
                        <a href="#reserve"
                            class="flex items-center justify-center bg-gradient-to-r from-neonPurple to-neonPink px-8 py-3.5 rounded-xl font-semibold text-white hover:opacity-90 hover:scale-105 transition">
                            Book Table Online
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

                    <div class="absolute -bottom-4 -left-4 sm:-left-8 tmc-grad-border rounded-xl bg-gray-900 px-4 py-3 shadow-xl flex items-center gap-3 border border-gray-800">
                        <div class="flex items-end gap-1 h-6">
                            <span class="tmc-eq-bar w-1.5 bg-neonPurple rounded-sm" style="animation-delay:0s"></span>
                            <span class="tmc-eq-bar w-1.5 bg-neonPink rounded-sm" style="animation-delay:.15s"></span>
                            <span class="tmc-eq-bar w-1.5 bg-neonPurple rounded-sm" style="animation-delay:.3s"></span>
                            <span class="tmc-eq-bar w-1.5 bg-neonPink rounded-sm" style="animation-delay:.45s"></span>
                        </div>
                        <p class="text-xs text-white/60">Live now · DJ Kaayan</p>
                    </div>
                    <span class="tmc-glint absolute top-4 right-6 w-2 h-2 rounded-full bg-white"></span>
                    <span class="tmc-glint absolute top-16 right-0 w-1.5 h-1.5 rounded-full bg-white" style="animation-delay:.8s"></span>
                    <span class="tmc-glint absolute bottom-14 right-10 w-1.5 h-1.5 rounded-full bg-white" style="animation-delay:1.4s"></span>
                </div>
            </div>
        </section>

        <!-- Marquee -->
        <div class="border-y border-gray-800 py-4 overflow-hidden">
            <div class="flex tmc-marquee-track whitespace-nowrap text-white/25 font-extrabold text-2xl tracking-tight gap-10">
                <span>LIVE DJ SETS</span><span>•</span><span>VIP TABLES</span><span>•</span><span>GUEST LISTS</span><span>•</span><span>SECTOR 29 · CYBER HUB</span><span>•</span>
                <span>LIVE DJ SETS</span><span>•</span><span>VIP TABLES</span><span>•</span><span>GUEST LISTS</span><span>•</span><span>SECTOR 29 · CYBER HUB</span><span>•</span>
            </div>
        </div>

        <!-- Features Section -->
        <section id="features" class="py-20 bg-gray-950 border-b border-gray-800">
            <div class="max-w-7xl mx-auto px-6">
                <div class="text-center mb-16 tmc-reveal" ref="revealFeatures">
                    <p class="font-serif-club text-2xl text-neonPink mb-2">everything, handled</p>
                    <h2 class="text-3xl font-bold">Why Choose <span class="gradient-text">The Midnight Club</span>?</h2>
                </div>
                <div class="grid md:grid-cols-3 gap-8">
                    <div class="tmc-reveal bg-gray-900/50 p-8 rounded-2xl border border-gray-800 hover:border-purple-500/50 hover:-translate-y-1 transition">
                        <div class="w-12 h-12 bg-purple-500/10 text-neonPurple rounded-xl flex items-center justify-center text-2xl mb-6">
                            <i class="fas fa-glass-cheers"></i>
                        </div>
                        <h3 class="text-xl font-bold mb-3">VIP &amp; Table Bookings</h3>
                        <p class="text-gray-400 text-sm">Instant reservations for Regular, Premium, or VIP tables and lounges — no waiting in line.</p>
                    </div>
                    <div class="tmc-reveal bg-gray-900/50 p-8 rounded-2xl border border-gray-800 hover:border-purple-500/50 hover:-translate-y-1 transition">
                        <div class="w-12 h-12 bg-pink-500/10 text-neonPink rounded-xl flex items-center justify-center text-2xl mb-6">
                            <i class="fas fa-music"></i>
                        </div>
                        <h3 class="text-xl font-bold mb-3">Discover Nightlife</h3>
                        <p class="text-gray-400 text-sm">Top DJ lineups, live gigs, and exclusive party events across Gurugram, updated live.</p>
                    </div>
                    <div class="tmc-reveal bg-gray-900/50 p-8 rounded-2xl border border-gray-800 hover:border-purple-500/50 hover:-translate-y-1 transition">
                        <div class="w-12 h-12 bg-purple-500/10 text-neonPurple rounded-xl flex items-center justify-center text-2xl mb-6">
                            <i class="fas fa-qrcode"></i>
                        </div>
                        <h3 class="text-xl font-bold mb-3">Digital Check-In</h3>
                        <p class="text-gray-400 text-sm">Instant QR entry passes on your phone for seamless, no-print venue verification.</p>
                    </div>
                    <div class="tmc-reveal bg-gray-900/50 p-8 rounded-2xl border border-gray-800 hover:border-purple-500/50 hover:-translate-y-1 transition">
                        <div class="w-12 h-12 bg-pink-500/10 text-neonPink rounded-xl flex items-center justify-center text-2xl mb-6">
                            <i class="fas fa-user-friends"></i>
                        </div>
                        <h3 class="text-xl font-bold mb-3">Guest List Links</h3>
                        <p class="text-gray-400 text-sm">Share one link — friends add themselves and the door already has every name.</p>
                    </div>
                    <div class="tmc-reveal bg-gray-900/50 p-8 rounded-2xl border border-gray-800 hover:border-purple-500/50 hover:-translate-y-1 transition">
                        <div class="w-12 h-12 bg-purple-500/10 text-neonPurple rounded-xl flex items-center justify-center text-2xl mb-6">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h3 class="text-xl font-bold mb-3">Verified &amp; Secure Entry</h3>
                        <p class="text-gray-400 text-sm">A visible security team and checked IDs — a safe floor, every single night.</p>
                    </div>
                    <div class="tmc-reveal bg-gray-900/50 p-8 rounded-2xl border border-gray-800 hover:border-purple-500/50 hover:-translate-y-1 transition">
                        <div class="w-12 h-12 bg-pink-500/10 text-neonPink rounded-xl flex items-center justify-center text-2xl mb-6">
                            <i class="fas fa-bolt"></i>
                        </div>
                        <h3 class="text-xl font-bold mb-3">Real-Time Capacity</h3>
                        <p class="text-gray-400 text-sm">Every event shows live spots left, so you know exactly when to move fast.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Why Choose Us / Stats -->
        <section id="why" class="py-20 px-6 max-w-7xl mx-auto">
            <div class="grid md:grid-cols-2 gap-14 items-center">
                <div class="tmc-reveal">
                    <p class="font-serif-club text-2xl text-neonPurple mb-2">why us</p>
                    <h2 class="text-3xl md:text-4xl font-bold mb-6">Not just a venue. The night, done right.</h2>
                    <p class="text-gray-400 mb-8">Three years of Saturdays in the heart of Gurugram, a resident sound system built for the room, and a floor team that treats your table like it's the only one that matters.</p>
                    <ul class="space-y-4">
                        <li class="flex gap-3">
                            <span class="w-1.5 h-1.5 rounded-full bg-neonPink mt-2 shrink-0"></span>
                            <span class="text-gray-300 text-sm"><strong class="text-white">Resident sound system</strong> — tuned for the room, not borrowed for the night.</span>
                        </li>
                        <li class="flex gap-3">
                            <span class="w-1.5 h-1.5 rounded-full bg-neonPurple mt-2 shrink-0"></span>
                            <span class="text-gray-300 text-sm"><strong class="text-white">Real security</strong> — visible team, checked IDs, safe till close.</span>
                        </li>
                        <li class="flex gap-3">
                            <span class="w-1.5 h-1.5 rounded-full bg-neonPink mt-2 shrink-0"></span>
                            <span class="text-gray-300 text-sm"><strong class="text-white">No surprise wait</strong> — your table is yours the moment you book it.</span>
                        </li>
                    </ul>
                </div>
                <div class="tmc-reveal grid grid-cols-2 gap-4">
                    <div class="tmc-grad-border rounded-2xl bg-gray-900/60 border border-gray-800 p-6 text-center">
                        <p class="text-4xl font-extrabold text-neonPink tmc-counter" data-target="3">0</p>
                        <p class="text-gray-400 text-sm mt-1">Years running</p>
                    </div>
                    <div class="tmc-grad-border rounded-2xl bg-gray-900/60 border border-gray-800 p-6 text-center">
                        <p class="text-4xl font-extrabold text-neonPurple tmc-counter" data-target="180">0</p>
                        <p class="text-gray-400 text-sm mt-1">Nights hosted</p>
                    </div>
                    <div class="tmc-grad-border rounded-2xl bg-gray-900/60 border border-gray-800 p-6 text-center">
                        <p class="text-4xl font-extrabold text-neonPink tmc-counter" data-target="42">0</p>
                        <p class="text-gray-400 text-sm mt-1">Resident &amp; guest DJs</p>
                    </div>
                    <div class="tmc-grad-border rounded-2xl bg-gray-900/60 border border-gray-800 p-6 text-center">
                        <p class="text-4xl font-extrabold text-neonPurple tmc-counter" data-target="12">0</p>
                        <p class="text-gray-400 text-sm mt-1">Thousand guests</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Quick Booking Form -->
        <section id="reserve" class="py-20 px-6 max-w-3xl mx-auto">
            <div class="text-center mb-8 tmc-reveal">
                <p class="font-serif-club text-2xl text-neonPink mb-2">your table's waiting</p>
                <h2 class="text-2xl font-bold">Request Table Booking</h2>
            </div>
            <div class="tmc-reveal tmc-grad-border bg-gray-900 p-8 md:p-10 rounded-3xl border border-gray-800">
                <p class="text-gray-400 text-center text-sm mb-8">Prefer the app? Download it above — or reserve online below.</p>
                <form @submit.prevent="submitBooking" class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 mb-1">Full Name</label>
                        <input type="text" v-model="form.name" required class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-neonPurple" placeholder="John Doe">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 mb-1">Phone Number</label>
                        <input type="tel" v-model="form.phone" required class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-neonPurple" placeholder="+91 98765 43210">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-400 mb-1">Date</label>
                            <input type="date" v-model="form.date" required class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-neonPurple">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-400 mb-1">Guests</label>
                            <select v-model="form.guests" class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-neonPurple">
                                <option>2 People</option>
                                <option>4 People</option>
                                <option>6+ VIP Lounge</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 mb-1">Table Tier</label>
                        <select v-model="form.tier" class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-neonPurple">
                            <option>Regular</option>
                            <option>Premium</option>
                            <option>VIP</option>
                        </select>
                    </div>
                    <button type="submit" class="w-full bg-gradient-to-r from-neonPink to-neonPurple py-3.5 rounded-lg font-semibold text-white mt-4 hover:opacity-90 hover:scale-[1.02] transition">
                        Reserve Now
                    </button>
                </form>
            </div>
        </section>

        <!-- Testimonials -->
        <section id="testimonials" class="py-20 px-6 max-w-7xl mx-auto">
            <div class="text-center mb-14 tmc-reveal">
                <p class="font-serif-club text-2xl text-neonPurple mb-2">from our regulars</p>
                <h2 class="text-3xl font-bold">People keep coming back</h2>
            </div>
            <div class="grid md:grid-cols-3 gap-6">
                <div class="tmc-reveal bg-gray-900/50 rounded-2xl p-6 border border-gray-800">
                    <p class="text-neonPink mb-3 text-sm">★★★★★</p>
                    <p class="text-gray-300 text-sm mb-6">"Walked straight to our table, no line, no calling ahead the day of. Exactly how a Saturday in Gurugram should start."</p>
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-gradient-to-br from-neonPurple to-neonPink"></div>
                        <p class="text-xs text-gray-500">Meera K. · Regular</p>
                    </div>
                </div>
                <div class="tmc-reveal bg-gray-900/50 rounded-2xl p-6 border border-gray-800">
                    <p class="text-neonPink mb-3 text-sm">★★★★★</p>
                    <p class="text-gray-300 text-sm mb-6">"Best sound system in Cyber Hub. Booked a VIP table for eight in two minutes flat on the app."</p>
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-gradient-to-br from-neonPink to-neonPurple"></div>
                        <p class="text-xs text-gray-500">Kabir R. · Regular</p>
                    </div>
                </div>
                <div class="tmc-reveal bg-gray-900/50 rounded-2xl p-6 border border-gray-800">
                    <p class="text-neonPink mb-3 text-sm">★★★★★</p>
                    <p class="text-gray-300 text-sm mb-6">"Security actually checks IDs and the floor team remembers our table every week. Never felt unsafe."</p>
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-gradient-to-br from-neonPurple to-neonPink"></div>
                        <p class="text-xs text-gray-500">Simran D. · Regular</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- FAQ -->
        <section id="faq" class="py-20 px-6 max-w-3xl mx-auto">
            <div class="text-center mb-12 tmc-reveal">
                <p class="font-serif-club text-2xl text-neonPink mb-2">before you book</p>
                <h2 class="text-3xl font-bold">Frequently asked</h2>
            </div>
            <div class="space-y-3">
                <div class="tmc-accordion tmc-reveal border border-gray-800 rounded-xl px-6 py-5 cursor-pointer" @click="toggleFaq($event)">
                    <div class="flex items-center justify-between">
                        <p class="font-semibold">Do I need to pay in full to book?</p>
                        <span class="tmc-chev text-xl text-gray-500">+</span>
                    </div>
                    <div class="tmc-accordion-content text-gray-400 text-sm pt-3">Just a small deposit to hold your table — the rest is settled at the club however you like to pay.</div>
                </div>
                <div class="tmc-accordion tmc-reveal border border-gray-800 rounded-xl px-6 py-5 cursor-pointer" @click="toggleFaq($event)">
                    <div class="flex items-center justify-between">
                        <p class="font-semibold">Can I cancel or change my reservation?</p>
                        <span class="tmc-chev text-xl text-gray-500">+</span>
                    </div>
                    <div class="tmc-accordion-content text-gray-400 text-sm pt-3">Yes, free of charge up to 24 hours before your slot. After that, the deposit isn't refundable.</div>
                </div>
                <div class="tmc-accordion tmc-reveal border border-gray-800 rounded-xl px-6 py-5 cursor-pointer" @click="toggleFaq($event)">
                    <div class="flex items-center justify-between">
                        <p class="font-semibold">Is there an age or dress code?</p>
                        <span class="tmc-chev text-xl text-gray-500">+</span>
                    </div>
                    <div class="tmc-accordion-content text-gray-400 text-sm pt-3">21+ after 9PM, smart casual. We'll always flag ahead if a night calls for more.</div>
                </div>
                <div class="tmc-accordion tmc-reveal border border-gray-800 rounded-xl px-6 py-5 cursor-pointer" @click="toggleFaq($event)">
                    <div class="flex items-center justify-between">
                        <p class="font-semibold">Can I add guests after booking?</p>
                        <span class="tmc-chev text-xl text-gray-500">+</span>
                    </div>
                    <div class="tmc-accordion-content text-gray-400 text-sm pt-3">Yes — you'll get a shareable link right after confirming, add anyone up until doors open.</div>
                </div>
            </div>
        </section>

        <!-- Find Us -->
        <section class="relative py-24 px-6 text-center max-w-4xl mx-auto">
            <h2 class="tmc-reveal text-4xl font-extrabold mb-4">Find us tonight.</h2>
            <p class="tmc-reveal font-serif-club text-2xl text-gray-400 mb-8">Cyber Hub, Gurugram · doors 9PM · Fri–Sun</p>
            <div class="tmc-reveal flex flex-wrap justify-center gap-4 text-sm">
                <a href="#reserve" class="px-8 py-3.5 rounded-full bg-gradient-to-r from-neonPurple to-neonPink font-semibold text-white hover:scale-105 transition">Reserve a table</a>
                <a href="tel:+910000000000" class="px-8 py-3.5 rounded-full border border-gray-700 hover:border-gray-500 transition">Call the club</a>
                <a href="https://wa.me/910000000000" class="px-8 py-3.5 rounded-full border border-gray-700 hover:border-gray-500 transition">WhatsApp us</a>
            </div>
        </section>

    </script>

    <script type="module">
        frontendVueApp.component('v-home', {
            template: '#v-home-template',

            data() {
                return {
                    form: {
                        name: '',
                        phone: '',
                        date: '',
                        guests: '2 People',
                        tier: 'Regular',
                    },
                    isLoading: false,
                    observer: null,
                };
            },

            mounted() {
                this.observer = new IntersectionObserver((entries) => {
                    entries.forEach((entry) => {
                        if (entry.isIntersecting) entry.target.classList.add('in');
                    });
                }, {
                    threshold: 0.15
                });

                document.querySelectorAll('.tmc-reveal').forEach((el) => this.observer.observe(el));

                const counterObserver = new IntersectionObserver((entries) => {
                    entries.forEach((entry) => {
                        if (entry.isIntersecting) {
                            document.querySelectorAll('.tmc-counter').forEach((el) => this.animateCounter(el, parseInt(el.dataset.target)));
                            counterObserver.disconnect();
                        }
                    });
                }, {
                    threshold: 0.3
                });

                const firstCounter = document.querySelector('.tmc-counter');
                if (firstCounter) counterObserver.observe(firstCounter);
            },

            beforeUnmount() {
                if (this.observer) this.observer.disconnect();
            },

            methods: {
                animateCounter(el, target, duration = 1400) {
                    let startTime = null;
                    const step = (ts) => {
                        if (!startTime) startTime = ts;
                        const progress = Math.min((ts - startTime) / duration, 1);
                        el.textContent = Math.floor(progress * target);
                        if (progress < 1) requestAnimationFrame(step);
                    };
                    requestAnimationFrame(step);
                },

                toggleFaq(event) {
                    event.currentTarget.classList.toggle('open');
                },

                submitBooking() {
                    this.isLoading = true;

                    // Wire this up to a real Laravel route, e.g.:
                    // this.$axios.post(route('bookings.store'), this.form)
                    //     .then((response) => { /* show success, reset form */ })
                    //     .catch((error) => {
                    //         this.isLoading = false;
                    //         if (error.response?.status === 422) { /* setErrors(error.response.data.errors) */ }
                    //     });

                    alert('Booking request received! We will confirm by SMS shortly.');
                    this.isLoading = false;
                }
            }
        });
    </script>
    @endPushOnce
</x-frontend::layouts>