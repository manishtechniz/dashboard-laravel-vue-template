<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Midnight Club | Ultra-Motion Nightlife Experience & VIP Bookings</title>

    <!-- Google Fonts: Syne & Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Syne:wght@700;800;900&display=swap" rel="stylesheet">

    <!-- FontAwesome 6 Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- QRCode Generator CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

    <!-- Tailwind Configuration matching theme specifications -->
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        midnight: '#0b0c10',
                        'neon-purple': '#a855f7',
                        'neon-pink': '#ec4899',
                        'dark-card': '#1f293d',
                        'dark-card-hover': '#283752',
                        'surface-glass': 'rgba(31, 41, 61, 0.55)',
                    },
                    fontFamily: {
                        syne: ['Syne', 'sans-serif'],
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    boxShadow: {
                        'neon-purple': '0 0 35px -5px rgba(168, 85, 247, 0.55)',
                        'neon-pink': '0 0 35px -5px rgba(236, 72, 153, 0.55)',
                        'neon-combo': '0 0 45px -8px rgba(236, 72, 153, 0.4), 0 0 40px -8px rgba(168, 85, 247, 0.4)',
                        'notch': '0 20px 40px -10px rgba(0, 0, 0, 0.85), 0 0 25px rgba(168, 85, 247, 0.3)',
                    },
                    animation: {
                        'pulse-glow': 'pulseGlow 3.5s ease-in-out infinite alternate',
                        'float': 'float 5s ease-in-out infinite',
                        'float-delayed': 'float 6s ease-in-out 1.5s infinite',
                        'laser-sweep': 'laserSweep 7s ease-in-out infinite',
                        'marquee': 'marquee 30s linear infinite',
                        'shimmer': 'shimmer 1.8s infinite',
                    },
                    keyframes: {
                        pulseGlow: {
                            '0%': {
                                opacity: '0.25',
                                transform: 'scale(1)'
                            },
                            '100%': {
                                opacity: '0.65',
                                transform: 'scale(1.15)'
                            },
                        },
                        float: {
                            '0%, 100%': {
                                transform: 'translateY(0px)'
                            },
                            '50%': {
                                transform: 'translateY(-10px)'
                            },
                        },
                        laserSweep: {
                            '0%': {
                                transform: 'rotate(-30deg) scaleX(0.7)',
                                opacity: '0.15'
                            },
                            '50%': {
                                transform: 'rotate(30deg) scaleX(1.3)',
                                opacity: '0.6'
                            },
                            '100%': {
                                transform: 'rotate(-30deg) scaleX(0.7)',
                                opacity: '0.15'
                            },
                        },
                        marquee: {
                            '0%': {
                                transform: 'translateX(0%)'
                            },
                            '100%': {
                                transform: 'translateX(-50%)'
                            },
                        },
                        shimmer: {
                            '100%': {
                                transform: 'translateX(100%)'
                            },
                        }
                    }
                }
            }
        }
    </script>

    <!-- Custom Micro-interactions & Visual Enhancements -->
    <style>
        body {
            background-color: #0b0c10;
            color: #f3f4f6;
            overflow-x: hidden;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .gradient-text {
            background: linear-gradient(135deg, #ec4899 0%, #d946ef 45%, #a855f7 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* Notchbuddy Dynamic Island Pill */
        .notch-capsule {
            background: rgba(11, 12, 16, 0.82);
            backdrop-filter: blur(28px);
            -webkit-backdrop-filter: blur(28px);
            border: 1px solid rgba(255, 255, 255, 0.12);
            box-shadow: 0 16px 40px -10px rgba(0, 0, 0, 0.9), 0 0 20px rgba(168, 85, 247, 0.25);
            transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
        }

        /* Ambient Cursor Glow Follower */
        #cursor-glow {
            pointer-events: none;
            position: fixed;
            width: 650px;
            height: 650px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(168, 85, 247, 0.16) 0%, rgba(236, 72, 153, 0.08) 35%, transparent 70%);
            transform: translate(-50%, -50%);
            z-index: 1;
            transition: opacity 0.3s ease;
        }

        /* Futuristic Noise Pattern */
        .noise-bg {
            background-image: radial-gradient(rgba(255, 255, 255, 0.04) 1px, transparent 0);
            background-size: 26px 26px;
        }

        /* 3D Glass Cards */
        .glass-card {
            background: rgba(31, 41, 61, 0.45);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(168, 85, 247, 0.22);
            transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1), border-color 0.4s ease, box-shadow 0.4s ease;
        }

        .glass-card:hover {
            border-color: rgba(236, 72, 153, 0.7);
            box-shadow: 0 15px 40px -10px rgba(168, 85, 247, 0.35);
        }

        /* Equalizer Animation Bars */
        .eq-bar {
            width: 3px;
            border-radius: 3px;
            background: linear-gradient(to top, #a855f7, #ec4899);
            animation: eqDance 0.85s ease-in-out infinite alternate;
        }

        @keyframes eqDance {
            0% {
                height: 4px;
            }

            50% {
                height: 18px;
            }

            100% {
                height: 26px;
            }
        }

        /* Image Shimmer Skeleton */
        .skeleton {
            background: linear-gradient(90deg, #1f293d 25%, #2a3750 50%, #1f293d 75%);
            background-size: 200% 100%;
            animation: shimmer 1.5s infinite;
        }
    </style>
</head>

<body class="relative min-h-screen selection:bg-neon-pink selection:text-white">

    <!-- Cursor Light Glow -->
    <div id="cursor-glow" class="hidden md:block"></div>

    <!-- Background Laser Beams & Lights -->
    <div class="fixed inset-0 pointer-events-none overflow-hidden z-0">
        <div class="absolute -top-32 left-1/4 w-[750px] h-[750px] bg-neon-purple/15 rounded-full blur-[170px] animate-pulse-glow"></div>
        <div class="absolute top-1/3 -right-24 w-[650px] h-[650px] bg-neon-pink/15 rounded-full blur-[180px] animate-pulse-glow" style="animation-delay: 1.5s;"></div>
        <div class="absolute bottom-10 left-10 w-[600px] h-[600px] bg-indigo-900/20 rounded-full blur-[180px]"></div>

        <!-- Cyber Laser Cone Simulation -->
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-4/5 h-[460px] bg-gradient-to-b from-neon-purple/20 via-neon-pink/10 to-transparent blur-3xl opacity-40 animate-laser-sweep origin-top"></div>
    </div>

    <!-- NOTCHBUDDY STYLE FLOATING ISLAND NAVIGATION -->
    <header class="fixed top-4 inset-x-0 z-50 flex justify-center px-4">
        <div id="notch-bar" class="notch-capsule rounded-full px-5 py-2.5 flex items-center justify-between gap-5 max-w-5xl w-full">

            <!-- Club Logo Brand -->
            <a href="#" class="flex items-center gap-3 group">
                <div class="relative w-9 h-9 rounded-full bg-gradient-to-tr from-neon-pink via-purple-600 to-neon-purple p-[1.5px] shadow-neon-pink">
                    <div class="w-full h-full bg-midnight rounded-full flex items-center justify-center">
                        <i class="fas fa-compact-disc text-sm text-neon-pink group-hover:rotate-180 transition-transform duration-700"></i>
                    </div>
                </div>
                <div class="flex flex-col">
                    <span class="font-syne text-sm font-extrabold tracking-wider uppercase text-white flex items-center gap-1">
                        The Midnight <span class="text-neon-pink">Club</span>
                    </span>
                    <span class="text-[9px] text-gray-400 uppercase tracking-widest hidden sm:block">Nightlife Redefined</span>
                </div>
            </a>

            <!-- Dynamic Notch Interactive Island (Live Club Stage & Audio Vibe) -->
            <div id="notch-live-pill" class="flex items-center gap-2.5 bg-white/5 border border-white/10 hover:border-neon-purple/50 px-3.5 py-1.5 rounded-full text-xs transition cursor-pointer" onclick="toggleAudioVibe()" title="Click to toggle Sound Mode">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                </span>

                <div class="flex flex-col text-left">
                    <div class="flex items-center gap-1.5">
                        <span class="font-bold text-[10px] tracking-widest uppercase text-emerald-400">STAGE LIVE</span>
                        <span class="text-gray-500">•</span>
                        <span id="bpm-indicator" class="text-[10px] text-purple-300 font-mono font-semibold">138 BPM</span>
                    </div>
                </div>

                <!-- Live Sound Equalizer Bars -->
                <div id="notch-eq" class="flex items-end gap-[3px] h-4 ml-1">
                    <div class="eq-bar" style="animation-delay: 0.1s;"></div>
                    <div class="eq-bar" style="animation-delay: 0.4s;"></div>
                    <div class="eq-bar" style="animation-delay: 0.2s;"></div>
                    <div class="eq-bar" style="animation-delay: 0.6s;"></div>
                    <div class="eq-bar" style="animation-delay: 0.3s;"></div>
                </div>

                <i id="sound-icon" class="fas fa-volume-high text-[11px] text-neon-pink ml-1"></i>
            </div>

            <!-- Desktop Navigation Links -->
            <nav class="hidden lg:flex items-center gap-6 text-xs font-bold tracking-wider text-gray-300">
                <a href="#why-us" class="hover:text-neon-pink transition">WHY US</a>
                <a href="#features" class="hover:text-neon-purple transition">PERKS</a>
                <a href="#gallery" class="hover:text-neon-pink transition flex items-center gap-1">
                    <span>GALLERY</span>
                    <span class="px-1.5 py-0.2 text-[9px] bg-neon-pink/20 text-neon-pink rounded-full border border-neon-pink/30">API</span>
                </a>
                <a href="#tables" class="hover:text-neon-purple transition">VIP TABLES</a>
                <a href="#testimonials" class="hover:text-neon-pink transition">REVIEWS</a>
                <a href="#faq" class="hover:text-neon-purple transition">FAQ</a>
            </nav>

            <!-- Quick Action CTA -->
            <div class="flex items-center gap-3">
                <a href="#reserve" class="px-5 py-2 rounded-full bg-gradient-to-r from-neon-purple to-neon-pink text-xs font-extrabold text-white shadow-sm hover:shadow-neon-pink hover:scale-105 active:scale-95 transition-all duration-300 flex items-center gap-2">
                    <i class="fas fa-ticket text-[11px]"></i>
                    <span class="hidden sm:inline">BOOK VIP</span>
                    <span class="sm:hidden">BOOK</span>
                </a>

                <!-- Mobile Menu Toggle Button -->
                <button id="mobile-toggle" aria-label="Toggle Navigation Menu" class="lg:hidden text-gray-300 hover:text-white p-1">
                    <i class="fas fa-bars-staggered text-lg"></i>
                </button>
            </div>
        </div>
    </header>

    <!-- Mobile Drawer Navigation -->
    <div id="mobile-menu" class="hidden fixed inset-0 z-50 bg-midnight/95 backdrop-blur-2xl p-8 flex flex-col justify-center items-center text-center gap-7">
        <button id="close-mobile-menu" aria-label="Close Mobile Navigation" class="absolute top-6 right-6 text-gray-400 hover:text-white text-2xl">
            <i class="fas fa-times"></i>
        </button>
        <div class="w-14 h-14 rounded-full bg-gradient-to-tr from-neon-pink to-neon-purple p-0.5 mb-2">
            <div class="w-full h-full bg-midnight rounded-full flex items-center justify-center">
                <i class="fas fa-moon text-xl text-neon-pink"></i>
            </div>
        </div>
        <span class="font-syne text-2xl font-black text-white tracking-widest">THE MIDNIGHT CLUB</span>
        <a href="#why-us" class="mobile-link text-lg font-bold text-gray-300 hover:text-neon-pink">Why Choose Us</a>
        <a href="#features" class="mobile-link text-lg font-bold text-gray-300 hover:text-neon-purple">Experiences & Perks</a>
        <a href="#gallery" class="mobile-link text-lg font-bold text-gray-300 hover:text-neon-pink">Multi-Image Gallery</a>
        <a href="#tables" class="mobile-link text-lg font-bold text-gray-300 hover:text-neon-purple">VIP Lounge Zones</a>
        <a href="#testimonials" class="mobile-link text-lg font-bold text-gray-300 hover:text-neon-pink">Reviews</a>
        <a href="#faq" class="mobile-link text-lg font-bold text-gray-300 hover:text-neon-purple">FAQ</a>
        <a href="#reserve" class="mobile-link px-8 py-3.5 rounded-full bg-gradient-to-r from-neon-purple to-neon-pink text-white font-bold text-sm shadow-neon-pink">
            Instant VIP Reservation
        </a>
    </div>

    <main class="relative z-10 noise-bg pt-20">

        <!-- TOP MOTION AUDIO REACTIVE SOUNDWAVE VISUALIZER -->
        <div class="w-full overflow-hidden relative border-b border-white/5 bg-gradient-to-b from-midnight via-dark-card/20 to-midnight">
            <canvas id="motion-visualizer" class="w-full h-24 sm:h-32 opacity-90 block"></canvas>

            <div class="absolute inset-0 bg-gradient-to-t from-midnight via-transparent to-transparent pointer-events-none"></div>

            <!-- Real-time audio frequency indicator overlay -->
            <div class="absolute bottom-2 inset-x-0 flex justify-center items-center gap-6 text-[10px] font-mono text-gray-400 tracking-wider">
                <span class="flex items-center gap-1.5"><span class="w-1.5 h-1.5 rounded-full bg-neon-purple"></span>SUB-BASS: 32Hz ACTIVE</span>
                <span class="hidden sm:flex items-center gap-1.5"><span class="w-1.5 h-1.5 rounded-full bg-neon-pink"></span>HI-RES SYNTH: 96kHz</span>
                <span class="flex items-center gap-1.5"><span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>IMMERSIVE 360° DOLBY CLUB AUDIO</span>
            </div>
        </div>

        <!-- HERO SECTION -->
        <section class="relative pt-12 pb-20 px-6 text-center max-w-6xl mx-auto overflow-hidden">

            <!-- Ambient Floating Pill -->
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/[0.04] border border-purple-500/30 text-purple-300 text-xs font-bold tracking-wider uppercase mb-8 shadow-sm backdrop-blur-md animate-float">
                <span class="w-2 h-2 rounded-full bg-neon-pink animate-ping"></span>
                <span>The Ultimate Nightlife Experience</span>
                <span class="bg-neon-purple/30 text-white px-2 py-0.5 rounded-full text-[10px]">NEW SEASON 2026</span>
            </div>

            <!-- Main Headline -->
            <h1 class="font-syne text-5xl sm:text-6xl md:text-8xl font-black tracking-tight leading-[1.04] mb-6 text-white">
                Elevate Your Nightlife. <br>
                <span class="gradient-text drop-shadow-lg">Book VIP Tables & Events.</span>
            </h1>

            <p class="text-gray-400 text-lg md:text-xl max-w-2xl mx-auto mb-10 leading-relaxed">
                Discover trending clubs, reserve premium tables instantly, and access exclusive VIP deals in your city with <b class="text-white">The Midnight Club</b>.
            </p>

            <!-- Hero CTA Buttons -->
            <div class="flex flex-col sm:flex-row justify-center items-center gap-4 mb-16">
                <!-- App Store Google Play Button -->
                <a href="https://play.google.com/store/apps/details?id=com.themidnightclub.app" target="_blank"
                    class="w-full sm:w-auto flex items-center justify-center bg-dark-card border border-gray-700/80 hover:border-neon-purple px-7 py-4 rounded-2xl shadow-lg hover:shadow-neon-purple transition-all duration-300 group">
                    <i class="fab fa-google-play text-2xl mr-3 text-neon-pink group-hover:scale-110 transition-transform"></i>
                    <div class="text-left">
                        <div class="text-[10px] uppercase font-bold text-gray-400 tracking-wider">GET IT ON</div>
                        <div class="text-sm font-extrabold text-white">Google Play</div>
                    </div>
                </a>

                <!-- Book Table CTA -->
                <a href="#reserve"
                    class="w-full sm:w-auto flex items-center justify-center gap-2.5 bg-gradient-to-r from-neon-purple to-neon-pink px-9 py-4 rounded-2xl font-bold text-white shadow-neon-pink hover:scale-105 active:scale-95 transition-all duration-300 text-base">
                    <i class="fas fa-champagne-glasses text-sm"></i>
                    <span>Book Table Online</span>
                </a>
            </div>

            <!-- Club Hero Graphic Frame with Live Overlay -->
            <div class="relative rounded-3xl overflow-hidden border border-white/10 p-2 bg-gradient-to-b from-white/10 to-white/0 shadow-2xl mb-14">
                <div class="relative h-72 sm:h-96 md:h-[440px] w-full rounded-2xl overflow-hidden group">
                    <img src="https://images.unsplash.com/photo-1516450360452-9312f5e86fc7?q=80&w=1600&auto=format&fit=crop"
                        alt="The Midnight Club Euphoria"
                        class="w-full h-full object-cover object-center filter brightness-90 group-hover:scale-105 transition-transform duration-700">

                    <div class="absolute inset-0 bg-gradient-to-t from-midnight via-midnight/30 to-transparent"></div>

                    <!-- Floating In-Image Club Badges -->
                    <div class="absolute bottom-6 left-6 right-6 flex flex-wrap items-center justify-between gap-4">
                        <div class="flex items-center gap-3 bg-midnight/85 backdrop-blur-md px-4 py-2 rounded-2xl border border-white/10">
                            <div class="w-3 h-3 rounded-full bg-neon-pink animate-ping"></div>
                            <span class="text-xs font-syne font-bold text-white tracking-wide">TONIGHT: AMELIE LENS & CHARLOTTE LIVE</span>
                        </div>
                        <div class="flex items-center gap-2 bg-white/10 backdrop-blur-md px-3 py-1.5 rounded-xl border border-white/20 text-xs font-mono text-gray-200">
                            <i class="fas fa-users text-neon-purple"></i> 890 Clubbers Inside
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Ribbon Strip -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 max-w-4xl mx-auto">
                <div class="p-4 rounded-2xl bg-dark-card/60 border border-white/5 text-center">
                    <div class="font-syne text-3xl font-black text-white mb-1">4.95 <span class="text-neon-pink text-lg">★</span></div>
                    <div class="text-xs text-gray-400 font-medium">Guest Rating</div>
                </div>
                <div class="p-4 rounded-2xl bg-dark-card/60 border border-white/5 text-center">
                    <div class="font-syne text-3xl font-black text-white mb-1">65K+</div>
                    <div class="text-xs text-gray-400 font-medium">VIP Nightclubbers</div>
                </div>
                <div class="p-4 rounded-2xl bg-dark-card/60 border border-white/5 text-center">
                    <div class="font-syne text-3xl font-black text-neon-purple mb-1">100%</div>
                    <div class="text-xs text-gray-400 font-medium">Guaranteed Fast-Track</div>
                </div>
                <div class="p-4 rounded-2xl bg-dark-card/60 border border-white/5 text-center">
                    <div class="font-syne text-3xl font-black text-neon-pink mb-1">0 sec</div>
                    <div class="text-xs text-gray-400 font-medium">Instant QR Validation</div>
                </div>
            </div>

        </section>

        <!-- WHY CHOOSE THE MIDNIGHT CLUB -->
        <section id="why-us" class="py-24 bg-midnight-surface/80 border-t border-b border-gray-800/80 relative">
            <div class="max-w-7xl mx-auto px-6">

                <div class="text-center max-w-3xl mx-auto mb-16">
                    <div class="inline-block bg-purple-900/40 border border-purple-500/30 text-neon-purple px-4 py-1.5 rounded-full text-xs font-extrabold tracking-widest uppercase mb-4">
                        Exclusivity & Excellence
                    </div>
                    <h2 class="font-syne text-4xl sm:text-5xl font-extrabold text-white mb-4">
                        Why Choose <span class="gradient-text">The Midnight Club</span>?
                    </h2>
                    <p class="text-gray-400 text-sm sm:text-base">
                        We blend cutting-edge audio engineering with world-class hospitality to construct nocturnal moments you’ll never forget.
                    </p>
                </div>

                <div class="grid md:grid-cols-3 gap-8">

                    <!-- Advantage 1: VIP & Table Bookings -->
                    <div class="glass-card p-8 rounded-3xl group">
                        <div class="w-14 h-14 bg-purple-500/10 border border-purple-500/20 text-neon-purple rounded-2xl flex items-center justify-center text-2xl mb-6 group-hover:scale-110 group-hover:bg-neon-purple group-hover:text-white transition-all duration-300 shadow-neon-purple">
                            <i class="fas fa-glass-cheers"></i>
                        </div>
                        <h3 class="font-syne text-2xl font-bold text-white mb-3">VIP & Table Bookings</h3>
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
                        <h3 class="font-syne text-2xl font-bold text-white mb-3">World-Class Sound</h3>
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
                        <h3 class="font-syne text-2xl font-bold text-white mb-3">Digital QR Check-In</h3>
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
                    <h2 class="font-syne text-4xl sm:text-5xl font-extrabold text-white">Experience The Night Differently</h2>
                </div>
                <p class="text-gray-400 text-sm max-w-md">
                    From bespoke artisan mixology to our kinetic light show ceilings that dance in harmony with every bassline drop.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Card 1 -->
                <div class="glass-card rounded-3xl overflow-hidden group">
                    <div class="h-60 overflow-hidden relative">
                        <img src="https://images.unsplash.com/photo-1545128485-c400e7702796?q=80&w=800&auto=format&fit=crop"
                            alt="DJ Performance"
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-dark-card via-transparent to-transparent"></div>
                        <span class="absolute top-4 left-4 bg-midnight/80 backdrop-blur-md px-3 py-1 rounded-full text-[10px] font-bold text-neon-pink border border-neon-pink/30">HEADLINERS</span>
                    </div>
                    <div class="p-6">
                        <h4 class="font-syne text-xl font-bold text-white mb-2">Global DJ Residencies</h4>
                        <p class="text-gray-400 text-xs leading-relaxed mb-4">Featuring award-winning international selectors, techno icons, and live underground sets every weekend.</p>
                        <div class="text-neon-purple text-xs font-bold flex items-center gap-1.5">
                            <span>Every Thu - Sun</span> • <span>10:00 PM onwards</span>
                        </div>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="glass-card rounded-3xl overflow-hidden group">
                    <div class="h-60 overflow-hidden relative">
                        <img src="https://images.unsplash.com/photo-1510812431401-41d2bd2722f3?q=80&w=800&auto=format&fit=crop"
                            alt="VIP Bottle Service"
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-dark-card via-transparent to-transparent"></div>
                        <span class="absolute top-4 left-4 bg-midnight/80 backdrop-blur-md px-3 py-1 rounded-full text-[10px] font-bold text-neon-purple border border-neon-purple/30">VIP SERVICE</span>
                    </div>
                    <div class="p-6">
                        <h4 class="font-syne text-xl font-bold text-white mb-2">Bespoke Bottle Parades</h4>
                        <p class="text-gray-400 text-xs leading-relaxed mb-4">Champagne sparklers, customized LED nameboards, and direct escort through the backstage VIP tunnel.</p>
                        <div class="text-neon-pink text-xs font-bold flex items-center gap-1.5">
                            <span>Dom Pérignon & Ace of Spades</span>
                        </div>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="glass-card rounded-3xl overflow-hidden group">
                    <div class="h-60 overflow-hidden relative">
                        <img src="https://images.unsplash.com/photo-1574096079513-d8259312b785?q=80&w=800&auto=format&fit=crop"
                            alt="Sensory Kinetic Ceiling"
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-dark-card via-transparent to-transparent"></div>
                        <span class="absolute top-4 left-4 bg-midnight/80 backdrop-blur-md px-3 py-1 rounded-full text-[10px] font-bold text-indigo-400 border border-indigo-400/30">KINETIC ART</span>
                    </div>
                    <div class="p-6">
                        <h4 class="font-syne text-xl font-bold text-white mb-2">3D Kinetic Laser Sphere</h4>
                        <p class="text-gray-400 text-xs leading-relaxed mb-4">Immersive motorized LED spheres descend above the dancefloor in synchronization with the DJ’s drops.</p>
                        <div class="text-indigo-400 text-xs font-bold flex items-center gap-1.5">
                            <span>Over 1,000 DMX Motors</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- INFINITE MARQUEE FILMSTRIP CAROUSEL -->
        <div class="py-6 border-t border-b border-white/10 bg-midnight/90 overflow-hidden relative">
            <div class="flex items-center gap-6 animate-marquee whitespace-nowrap" id="filmstrip-marquee">
                <!-- Javascript will duplicate dynamically for seamless loop -->
            </div>
        </div>

        <!-- HIGH-OCTANE MULTI-IMAGE GALLERY SYSTEM (API-SIMULATED) -->
        <section id="gallery" class="py-24 px-6 max-w-7xl mx-auto">
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-6">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-pink-900/30 border border-pink-500/30 text-neon-pink text-xs font-bold uppercase tracking-wider mb-3">
                        <i class="fas fa-camera"></i> Live Visual Stream
                    </div>
                    <h2 class="font-syne text-4xl sm:text-5xl font-extrabold text-white">The Midnight Vault</h2>
                    <p class="text-gray-400 text-sm mt-2">Captured nocturnal frequencies, VIP celebrations, and headliner performances.</p>
                </div>

                <!-- Live Gallery API Simulation Controls -->
                <div class="flex items-center gap-3">
                    <button onclick="refreshGalleryFromAPI()" class="px-4 py-2 rounded-xl bg-dark-card border border-gray-700 hover:border-neon-purple text-xs font-semibold text-gray-300 hover:text-white flex items-center gap-2 transition shadow-sm">
                        <i id="api-refresh-icon" class="fas fa-rotate text-neon-purple"></i>
                        <span>Fetch API Data</span>
                    </button>
                    <span id="gallery-counter" class="text-xs font-mono text-neon-pink font-semibold px-3 py-1.5 bg-neon-pink/10 rounded-xl border border-neon-pink/20">
                        12 Photos
                    </span>
                </div>
            </div>

            <!-- Dynamic Category Filter Tabs -->
            <div class="flex items-center gap-2.5 overflow-x-auto pb-4 mb-8 no-scrollbar" id="gallery-tabs">
                <button onclick="filterGallery('all', this)" class="gallery-tab active px-5 py-2.5 rounded-full text-xs font-bold bg-gradient-to-r from-neon-purple to-neon-pink text-white shadow-neon-pink whitespace-nowrap transition">
                    All Moments
                </button>
                <button onclick="filterGallery('vip', this)" class="gallery-tab px-5 py-2.5 rounded-full text-xs font-bold bg-dark-card text-gray-300 hover:text-white border border-gray-700 hover:border-neon-purple whitespace-nowrap transition">
                    VIP & Champagne
                </button>
                <button onclick="filterGallery('djs', this)" class="gallery-tab px-5 py-2.5 rounded-full text-xs font-bold bg-dark-card text-gray-300 hover:text-white border border-gray-700 hover:border-neon-purple whitespace-nowrap transition">
                    Headliner DJs
                </button>
                <button onclick="filterGallery('crowd', this)" class="gallery-tab px-5 py-2.5 rounded-full text-xs font-bold bg-dark-card text-gray-300 hover:text-white border border-gray-700 hover:border-neon-purple whitespace-nowrap transition">
                    Crowd Energy
                </button>
                <button onclick="filterGallery('lighting', this)" class="gallery-tab px-5 py-2.5 rounded-full text-xs font-bold bg-dark-card text-gray-300 hover:text-white border border-gray-700 hover:border-neon-purple whitespace-nowrap transition">
                    Lasers & Atmosphere
                </button>
            </div>

            <!-- Bento/Masonry Grid for Multiple Images -->
            <div id="gallery-container" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Dynamically populated via JavaScript Gallery Array / Simulated API -->
            </div>

            <!-- Load More Simulation Button -->
            <div class="text-center mt-12">
                <button id="load-more-btn" onclick="loadMoreGalleryImages()" class="px-8 py-3.5 rounded-2xl bg-dark-card border border-white/10 hover:border-neon-purple text-xs font-extrabold uppercase tracking-wider text-white hover:shadow-neon-purple transition-all duration-300 inline-flex items-center gap-3">
                    <span>Load More Memories</span>
                    <i class="fas fa-arrow-down text-neon-pink"></i>
                </button>
            </div>
        </section>

        <!-- FULLSCREEN CINEMA LIGHTBOX MODAL FOR MULTIPLE IMAGES -->
        <div id="lightbox-modal" class="hidden fixed inset-0 z-50 bg-midnight/95 backdrop-blur-3xl flex flex-col justify-between p-4 sm:p-8">
            <!-- Header Bar -->
            <div class="flex items-center justify-between z-10">
                <div class="flex items-center gap-3">
                    <span id="lb-category" class="px-3 py-1 rounded-full text-[10px] font-extrabold uppercase bg-neon-purple/20 text-neon-purple border border-neon-purple/30">VIP LOUNGE</span>
                    <span id="lb-counter" class="text-xs font-mono text-gray-400">1 / 12</span>
                </div>
                <div class="flex items-center gap-3">
                    <button onclick="shareActiveImage()" class="w-10 h-10 rounded-full bg-dark-card border border-white/10 flex items-center justify-center text-gray-300 hover:text-neon-pink hover:border-neon-pink transition" title="Copy Link">
                        <i class="fas fa-share-nodes text-xs"></i>
                    </button>
                    <button onclick="closeLightbox()" class="w-10 h-10 rounded-full bg-dark-card border border-white/10 flex items-center justify-center text-gray-300 hover:text-white hover:border-white transition" title="Close">
                        <i class="fas fa-times text-sm"></i>
                    </button>
                </div>
            </div>

            <!-- Main Interactive Image Frame -->
            <div class="relative flex-1 flex items-center justify-center my-4 overflow-hidden">
                <button onclick="prevLightboxImage()" class="absolute left-2 sm:left-6 z-20 w-12 h-12 rounded-full bg-midnight/70 border border-white/20 hover:border-neon-pink text-white flex items-center justify-center transition hover:scale-110">
                    <i class="fas fa-chevron-left"></i>
                </button>

                <img id="lb-image" src="" alt="The Midnight Club Moment" class="max-h-[75vh] max-w-full rounded-2xl object-contain shadow-2xl transition-all duration-300 select-none">

                <button onclick="nextLightboxImage()" class="absolute right-2 sm:right-6 z-20 w-12 h-12 rounded-full bg-midnight/70 border border-white/20 hover:border-neon-pink text-white flex items-center justify-center transition hover:scale-110">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>

            <!-- Footer Caption Bar -->
            <div class="max-w-3xl mx-auto w-full text-center z-10 bg-dark-card/60 backdrop-blur-md p-4 rounded-2xl border border-white/10">
                <h3 id="lb-title" class="font-syne text-lg font-bold text-white mb-1">Ultra VIP Lounge Sparkler Celebration</h3>
                <p id="lb-desc" class="text-xs text-gray-400">Dom Pérignon luminous parade captured at the VIP Stage Mezzanine.</p>
            </div>
        </div>

        <!-- VIP TABLE & ZONE SELECTOR -->
        <section id="tables" class="py-24 px-6 max-w-7xl mx-auto border-t border-white/5">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <div class="inline-block bg-purple-900/40 border border-purple-500/30 text-neon-purple px-4 py-1.5 rounded-full text-xs font-extrabold tracking-widest uppercase mb-4">
                    Exclusive Hospitality
                </div>
                <h2 class="font-syne text-4xl sm:text-5xl font-extrabold text-white mb-4">
                    Select Your VIP Sanctuary
                </h2>
                <p class="text-gray-400 text-sm sm:text-base">
                    Choose from elevated center-stage tables, intimate skyboxes, or high-octane dancefloor booths with dedicated concierge.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

                <!-- Tier 1 -->
                <div class="glass-card rounded-3xl p-6 flex flex-col justify-between group hover:scale-[1.02] transition">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-xs font-mono text-neon-purple font-bold">ZONE A</span>
                            <span class="text-[10px] bg-white/10 px-2 py-0.5 rounded-full text-gray-300">4 Guests</span>
                        </div>
                        <h4 class="font-syne text-xl font-bold text-white mb-1">Dancefloor Lounge</h4>
                        <div class="font-syne text-2xl font-black text-neon-pink mb-4">$450 <span class="text-xs font-normal text-gray-400">min spend</span></div>
                        <ul class="text-xs text-gray-400 space-y-2.5 mb-6">
                            <li><i class="fas fa-check text-neon-purple mr-2"></i>Priority Fast-Track Entry</li>
                            <li><i class="fas fa-check text-neon-purple mr-2"></i>Direct Dancefloor Access</li>
                            <li><i class="fas fa-check text-neon-purple mr-2"></i>1 Premium Spirit Included</li>
                        </ul>
                    </div>
                    <button onclick="selectZoneAndScroll('Dancefloor Lounge', '$450', '4 People')" class="w-full py-2.5 rounded-xl bg-white/10 group-hover:bg-neon-purple text-white text-xs font-bold transition">
                        Select Zone
                    </button>
                </div>

                <!-- Tier 2 (Popular) -->
                <div class="glass-card rounded-3xl p-6 flex flex-col justify-between relative border-neon-pink/60 shadow-neon-pink group hover:scale-[1.03] transition">
                    <div class="absolute -top-3 left-1/2 -translate-x-1/2 bg-gradient-to-r from-neon-pink to-neon-purple px-3 py-0.5 rounded-full text-[10px] font-black uppercase text-white tracking-widest">
                        MOST POPULAR
                    </div>
                    <div>
                        <div class="flex items-center justify-between mb-4 mt-2">
                            <span class="text-xs font-mono text-neon-pink font-bold">ZONE B</span>
                            <span class="text-[10px] bg-white/10 px-2 py-0.5 rounded-full text-gray-300">6-8 Guests</span>
                        </div>
                        <h4 class="font-syne text-xl font-bold text-white mb-1">Stage VIP Booth</h4>
                        <div class="font-syne text-2xl font-black text-white mb-4">$750 <span class="text-xs font-normal text-gray-400">min spend</span></div>
                        <ul class="text-xs text-gray-400 space-y-2.5 mb-6">
                            <li><i class="fas fa-check text-neon-pink mr-2"></i>Beside Headliner DJ Booth</li>
                            <li><i class="fas fa-check text-neon-pink mr-2"></i>Dedicated Concierge Hostess</li>
                            <li><i class="fas fa-check text-neon-pink mr-2"></i>Full Bottle Presentation Sparklers</li>
                        </ul>
                    </div>
                    <button onclick="selectZoneAndScroll('Stage VIP Booth', '$750', '6 People')" class="w-full py-2.5 rounded-xl bg-gradient-to-r from-neon-pink to-neon-purple text-white text-xs font-bold shadow-sm transition">
                        Select Zone
                    </button>
                </div>

                <!-- Tier 3 -->
                <div class="glass-card rounded-3xl p-6 flex flex-col justify-between group hover:scale-[1.02] transition">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-xs font-mono text-purple-400 font-bold">ZONE C</span>
                            <span class="text-[10px] bg-white/10 px-2 py-0.5 rounded-full text-gray-300">10 Guests</span>
                        </div>
                        <h4 class="font-syne text-xl font-bold text-white mb-1">Skybox Penthouse</h4>
                        <div class="font-syne text-2xl font-black text-neon-purple mb-4">$1,500 <span class="text-xs font-normal text-gray-400">min spend</span></div>
                        <ul class="text-xs text-gray-400 space-y-2.5 mb-6">
                            <li><i class="fas fa-check text-neon-purple mr-2"></i>Panoramic Elevated Venue View</li>
                            <li><i class="fas fa-check text-neon-purple mr-2"></i>Private Restrooms & Bar</li>
                            <li><i class="fas fa-check text-neon-purple mr-2"></i>Custom LED Shoutout Message</li>
                        </ul>
                    </div>
                    <button onclick="selectZoneAndScroll('Skybox Penthouse', '$1,500', '8+ VIP Lounge')" class="w-full py-2.5 rounded-xl bg-white/10 group-hover:bg-neon-purple text-white text-xs font-bold transition">
                        Select Zone
                    </button>
                </div>

                <!-- Tier 4 -->
                <div class="glass-card rounded-3xl p-6 flex flex-col justify-between group hover:scale-[1.02] transition">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-xs font-mono text-emerald-400 font-bold">ZONE D</span>
                            <span class="text-[10px] bg-white/10 px-2 py-0.5 rounded-full text-gray-300">12 Guests</span>
                        </div>
                        <h4 class="font-syne text-xl font-bold text-white mb-1">Backstage Artist Deck</h4>
                        <div class="font-syne text-2xl font-black text-emerald-400 mb-4">$2,200 <span class="text-xs font-normal text-gray-400">min spend</span></div>
                        <ul class="text-xs text-gray-400 space-y-2.5 mb-6">
                            <li><i class="fas fa-check text-neon-purple mr-2"></i>Full Backstage Artist Pass</li>
                            <li><i class="fas fa-check text-neon-purple mr-2"></i>Private Security & Greenroom Entry</li>
                            <li><i class="fas fa-check text-neon-purple mr-2"></i>Unlimited Vintage Champagne</li>
                        </ul>
                    </div>
                    <button onclick="selectZoneAndScroll('Backstage Artist Deck', '$2,200', '8+ VIP Lounge')" class="w-full py-2.5 rounded-xl bg-white/10 group-hover:bg-emerald-500 text-white text-xs font-bold transition">
                        Select Zone
                    </button>
                </div>

            </div>
        </section>

        <!-- RESERVATION ENGINE FORM DEMO -->
        <section id="reserve" class="py-24 px-6 max-w-4xl mx-auto">
            <div class="bg-dark-card/90 backdrop-blur-2xl p-8 md:p-12 rounded-3xl border border-gray-800 relative shadow-notch">

                <!-- Ambient glow corners -->
                <div class="absolute -top-20 -right-20 w-60 h-60 bg-neon-pink/20 rounded-full blur-3xl pointer-events-none"></div>
                <div class="absolute -bottom-20 -left-20 w-60 h-60 bg-neon-purple/20 rounded-full blur-3xl pointer-events-none"></div>

                <div class="relative z-10">
                    <div class="text-center mb-10">
                        <div class="inline-flex items-center gap-2 px-4 py-1 rounded-full bg-purple-900/30 border border-purple-500/30 text-purple-300 text-xs font-bold uppercase tracking-wider mb-3">
                            <i class="fas fa-calendar-check text-neon-pink"></i> Instant VIP Reservation
                        </div>
                        <h2 class="font-syne text-3xl sm:text-4xl font-extrabold text-white mb-2">Request Table Booking</h2>
                        <p class="text-gray-400 text-sm">Prefer to reserve online? Fill in your details below for instant digital ticket generation.</p>
                    </div>

                    <form id="reservation-form" onsubmit="processBooking(event)" class="space-y-5">

                        <!-- Lounge Tier Selector Pill Badges -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-300 uppercase tracking-wider mb-2">Selected VIP Zone</label>
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5" id="tier-selector">
                                <button type="button" onclick="setZone(this, 'Stage VIP Booth', '$750')" class="zone-btn py-2.5 px-3 rounded-xl border border-neon-purple bg-purple-950/40 text-white text-xs font-bold text-center transition">
                                    Stage VIP
                                    <span class="block text-[10px] text-neon-pink font-normal">$750 min</span>
                                </button>
                                <button type="button" onclick="setZone(this, 'Dancefloor Lounge', '$450')" class="zone-btn py-2.5 px-3 rounded-xl border border-gray-700 bg-gray-900/60 text-gray-300 text-xs font-bold text-center hover:border-gray-500 transition">
                                    Dancefloor
                                    <span class="block text-[10px] text-gray-400 font-normal">$450 min</span>
                                </button>
                                <button type="button" onclick="setZone(this, 'Skybox Penthouse', '$1,500')" class="zone-btn py-2.5 px-3 rounded-xl border border-gray-700 bg-gray-900/60 text-gray-300 text-xs font-bold text-center hover:border-gray-500 transition">
                                    Skybox Suite
                                    <span class="block text-[10px] text-gray-400 font-normal">$1,500 min</span>
                                </button>
                                <button type="button" onclick="setZone(this, 'Backstage Artist Deck', '$2,200')" class="zone-btn py-2.5 px-3 rounded-xl border border-gray-700 bg-gray-900/60 text-gray-300 text-xs font-bold text-center hover:border-gray-500 transition">
                                    Backstage
                                    <span class="block text-[10px] text-gray-400 font-normal">$2,200 min</span>
                                </button>
                            </div>
                        </div>

                        <!-- Full Name -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-400 mb-1">Full Name</label>
                            <input type="text" id="res-name" required class="w-full bg-gray-800/90 border border-gray-700 rounded-xl px-4 py-3.5 text-white placeholder-gray-500 focus:outline-none focus:border-neon-purple focus:ring-1 focus:ring-neon-purple transition text-sm" placeholder="John Doe">
                        </div>

                        <!-- Email & Phone -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-400 mb-1">Email Address</label>
                                <input type="email" id="res-email" required class="w-full bg-gray-800/90 border border-gray-700 rounded-xl px-4 py-3.5 text-white placeholder-gray-500 focus:outline-none focus:border-neon-purple transition text-sm" placeholder="john@example.com">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-400 mb-1">Phone Number</label>
                                <input type="tel" id="res-phone" required class="w-full bg-gray-800/90 border border-gray-700 rounded-xl px-4 py-3.5 text-white placeholder-gray-500 focus:outline-none focus:border-neon-purple transition text-sm" placeholder="+1 (555) 019-2834">
                            </div>
                        </div>

                        <!-- Date, Arrival Time & Guests -->
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-400 mb-1">Date</label>
                                <input type="date" id="res-date" required class="w-full bg-gray-800/90 border border-gray-700 rounded-xl px-4 py-3.5 text-white focus:outline-none focus:border-neon-purple transition text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-400 mb-1">Arrival Time</label>
                                <select id="res-time" class="w-full bg-gray-800/90 border border-gray-700 rounded-xl px-4 py-3.5 text-white focus:outline-none focus:border-neon-purple transition text-sm">
                                    <option value="10:30 PM">10:30 PM</option>
                                    <option value="11:30 PM" selected>11:30 PM (Peak Entry)</option>
                                    <option value="12:30 AM">12:30 AM</option>
                                    <option value="01:30 AM">01:30 AM</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-400 mb-1">Guests</label>
                                <select id="res-guests" class="w-full bg-gray-800/90 border border-gray-700 rounded-xl px-4 py-3.5 text-white focus:outline-none focus:border-neon-purple transition text-sm">
                                    <option value="2 People">2 People</option>
                                    <option value="4 People">4 People</option>
                                    <option value="6 People" selected>6 People</option>
                                    <option value="8+ VIP Lounge">8+ VIP Lounge</option>
                                </select>
                            </div>
                        </div>

                        <!-- Special Bottle Request -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-400 mb-1">Special Bottle or Occasion Requests</label>
                            <input type="text" id="res-notes" placeholder="Birthday fireworks package, Champagne brand preference..." class="w-full bg-gray-800/90 border border-gray-700 rounded-xl px-4 py-3 text-white placeholder-gray-500 focus:outline-none focus:border-neon-purple transition text-sm">
                        </div>

                        <!-- Submit CTA Button -->
                        <button type="submit" class="w-full bg-gradient-to-r from-neon-pink to-neon-purple py-4 rounded-xl font-syne font-bold text-white tracking-wider uppercase mt-4 hover:opacity-90 hover:shadow-neon-pink hover:scale-[1.01] active:scale-[0.99] transition duration-300 flex items-center justify-center gap-2">
                            <span>Reserve Now & Generate Pass</span>
                            <i class="fas fa-sparkles text-sm"></i>
                        </button>
                    </form>
                </div>
            </div>
        </section>

        <!-- TESTIMONIALS SECTION -->
        <section id="testimonials" class="py-24 px-6 max-w-7xl mx-auto border-t border-white/5">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <div class="inline-block bg-pink-900/30 border border-pink-500/30 text-neon-pink px-4 py-1.5 rounded-full text-xs font-extrabold tracking-widest uppercase mb-4">
                    Voices from the Sanctuary
                </div>
                <h2 class="font-syne text-3xl sm:text-5xl font-extrabold text-white mb-4">What Our VIPs Say</h2>
                <p class="text-gray-400 text-sm">From resident artists to celebrity patrons, here’s how the midnight experience feels.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Review 1 -->
                <div class="glass-card p-8 rounded-3xl relative flex flex-col justify-between">
                    <div>
                        <div class="flex items-center gap-1 text-amber-400 text-sm mb-4">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                        </div>
                        <p class="text-gray-300 text-sm leading-relaxed mb-6 italic">
                            "The stage VIP booth was completely next-level. From the bottle presentation with sparklers to zero wait time at the door, The Midnight Club sets a new standard for luxury nightlife."
                        </p>
                    </div>
                    <div class="flex items-center gap-3 pt-4 border-t border-white/10">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-neon-purple to-neon-pink p-0.5">
                            <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=200&auto=format&fit=crop" class="w-full h-full object-cover rounded-full" alt="Reviewer">
                        </div>
                        <div>
                            <div class="font-syne text-sm font-bold text-white">Elena Rostova</div>
                            <div class="text-[11px] text-neon-pink">VIP Member • Saturday Regular</div>
                        </div>
                    </div>
                </div>

                <!-- Review 2 -->
                <div class="glass-card p-8 rounded-3xl relative flex flex-col justify-between">
                    <div>
                        <div class="flex items-center gap-1 text-amber-400 text-sm mb-4">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                        </div>
                        <p class="text-gray-300 text-sm leading-relaxed mb-6 italic">
                            "As an electronic music producer, the acoustic calibration in the main room blew me away. Crystal clear highs, chest-shaking sub-bass without ear fatigue. Unreal atmosphere!"
                        </p>
                    </div>
                    <div class="flex items-center gap-3 pt-4 border-t border-white/10">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-neon-purple to-indigo-500 p-0.5">
                            <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=200&auto=format&fit=crop" class="w-full h-full object-cover rounded-full" alt="Reviewer">
                        </div>
                        <div>
                            <div class="font-syne text-sm font-bold text-white">Marcus Vance</div>
                            <div class="text-[11px] text-neon-purple">Audio Engineer & Guest DJ</div>
                        </div>
                    </div>
                </div>

                <!-- Review 3 -->
                <div class="glass-card p-8 rounded-3xl relative flex flex-col justify-between">
                    <div>
                        <div class="flex items-center gap-1 text-amber-400 text-sm mb-4">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                        </div>
                        <p class="text-gray-300 text-sm leading-relaxed mb-6 italic">
                            "Booking via the web portal took 30 seconds and generated a sleek digital pass on my phone. When we arrived, security scanned it instantly. World-class hospitality!"
                        </p>
                    </div>
                    <div class="flex items-center gap-3 pt-4 border-t border-white/10">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-neon-pink to-purple-500 p-0.5">
                            <img src="https://images.unsplash.com/photo-1517841905240-472988babdf9?q=80&w=200&auto=format&fit=crop" class="w-full h-full object-cover rounded-full" alt="Reviewer">
                        </div>
                        <div>
                            <div class="font-syne text-sm font-bold text-white">Chloé Delacroix</div>
                            <div class="text-[11px] text-neon-pink">Private Event Host</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- FAQ ACCORDION -->
        <section id="faq" class="py-24 px-6 max-w-4xl mx-auto">
            <div class="text-center mb-14">
                <span class="text-xs font-bold tracking-widest text-neon-purple uppercase mb-2 block">Information & Protocol</span>
                <h2 class="font-syne text-3xl sm:text-4xl font-extrabold text-white mb-3">Frequently Asked Questions</h2>
                <p class="text-gray-400 text-sm">Everything you need to know prior to your arrival.</p>
            </div>

            <div class="space-y-4" id="faq-accordion">
                <!-- Item 1 -->
                <div class="glass-card rounded-2xl overflow-hidden border border-gray-800">
                    <button onclick="toggleFaq(this)" class="w-full p-5 text-left flex items-center justify-between font-syne font-bold text-sm text-white hover:text-neon-pink transition">
                        <span>What is the dress code for The Midnight Club?</span>
                        <i class="fas fa-chevron-down text-xs text-gray-400 transition-transform duration-300"></i>
                    </button>
                    <div class="faq-content max-h-0 overflow-hidden transition-all duration-300 px-5 text-xs text-gray-400 leading-relaxed">
                        <p class="pb-5">We maintain an upscale, cyber-glam, and nightfall-chic dress code. Athletic wear, excessively baggy clothes, flip flops, and beachwear are strictly prohibited. Smart sneakers and tailored black attire are celebrated.</p>
                    </div>
                </div>

                <!-- Item 2 -->
                <div class="glass-card rounded-2xl overflow-hidden border border-gray-800">
                    <button onclick="toggleFaq(this)" class="w-full p-5 text-left flex items-center justify-between font-syne font-bold text-sm text-white hover:text-neon-pink transition">
                        <span>What are the age restrictions and entry ID requirements?</span>
                        <i class="fas fa-chevron-down text-xs text-gray-400 transition-transform duration-300"></i>
                    </button>
                    <div class="faq-content max-h-0 overflow-hidden transition-all duration-300 px-5 text-xs text-gray-400 leading-relaxed">
                        <p class="pb-5">All guests must be 21+ with a valid government-issued physical photo ID or passport. Digital screenshots of IDs are not accepted by state security guidelines.</p>
                    </div>
                </div>

                <!-- Item 3 -->
                <div class="glass-card rounded-2xl overflow-hidden border border-gray-800">
                    <button onclick="toggleFaq(this)" class="w-full p-5 text-left flex items-center justify-between font-syne font-bold text-sm text-white hover:text-neon-pink transition">
                        <span>How does table minimum spend work?</span>
                        <i class="fas fa-chevron-down text-xs text-gray-400 transition-transform duration-300"></i>
                    </button>
                    <div class="faq-content max-h-0 overflow-hidden transition-all duration-300 px-5 text-xs text-gray-400 leading-relaxed">
                        <p class="pb-5">The minimum spend is the prepaid threshold dedicated entirely to your beverage orders, champagne bottles, and food at your table. It covers entry for your guest allocation without additional door cover charges.</p>
                    </div>
                </div>

                <!-- Item 4 -->
                <div class="glass-card rounded-2xl overflow-hidden border border-gray-800">
                    <button onclick="toggleFaq(this)" class="w-full p-5 text-left flex items-center justify-between font-syne font-bold text-sm text-white hover:text-neon-pink transition">
                        <span>Can I cancel or reschedule my table booking?</span>
                        <i class="fas fa-chevron-down text-xs text-gray-400 transition-transform duration-300"></i>
                    </button>
                    <div class="faq-content max-h-0 overflow-hidden transition-all duration-300 px-5 text-xs text-gray-400 leading-relaxed">
                        <p class="pb-5">Cancellations or transfers made up to 24 hours before your reservation date receive a full credit transfer to any subsequent event. Simply reach out to your designated concierge hostess.</p>
                    </div>
                </div>
            </div>
        </section>



    </main>

    <!-- BOOKING CONFIRMATION MODAL WITH DIGITAL FAST-PASS QR -->
    <div id="booking-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-midnight/90 backdrop-blur-2xl">
        <div class="glass-card rounded-3xl max-w-md w-full p-8 border border-white/20 text-center relative shadow-2xl animate-float">

            <button onclick="closeModal()" aria-label="Close modal" class="absolute top-5 right-5 text-gray-400 hover:text-white text-xl">
                <i class="fas fa-times"></i>
            </button>

            <div class="w-12 h-12 rounded-full bg-emerald-500/20 text-emerald-400 flex items-center justify-center mx-auto mb-4 text-xl border border-emerald-500/30 shadow-sm">
                <i class="fas fa-check"></i>
            </div>

            <h3 class="font-syne text-2xl font-bold text-white mb-1">VIP Reservation Confirmed</h3>
            <p class="text-xs text-gray-400 mb-6">Your table is locked. Present this encrypted digital fast-pass at priority Gate A.</p>

            <!-- Digital Ticket Pass -->
            <div class="bg-midnight p-5 rounded-2xl border border-white/10 text-left mb-6 relative overflow-hidden">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <span class="text-[10px] text-gray-400 uppercase tracking-wider block">Pass Holder</span>
                        <span id="ticket-holder" class="font-bold text-white text-sm">John Doe</span>
                    </div>
                    <div class="text-right">
                        <span class="text-[10px] text-neon-pink uppercase tracking-wider block font-bold">Zone</span>
                        <span id="ticket-tier" class="font-bold text-xs text-white">Stage VIP Booth</span>
                    </div>
                </div>

                <div class="flex justify-between items-center text-xs text-gray-300 mb-4 pb-4 border-b border-white/10">
                    <div>
                        <span class="text-[10px] text-gray-400 block">Date</span>
                        <span id="ticket-date" class="font-semibold text-white">Tomorrow</span>
                    </div>
                    <div>
                        <span class="text-[10px] text-gray-400 block">Arrival</span>
                        <span id="ticket-time" class="font-semibold text-white">11:30 PM</span>
                    </div>
                    <div>
                        <span class="text-[10px] text-gray-400 block">Guests</span>
                        <span id="ticket-guests" class="font-semibold text-white">6 People</span>
                    </div>
                </div>

                <!-- Dynamic QR Code Container -->
                <div class="flex flex-col items-center justify-center p-3.5 bg-white rounded-xl">
                    <div id="qrcode" class="w-32 h-32 flex items-center justify-center"></div>
                    <span class="text-[9px] text-gray-800 font-mono mt-2 font-bold tracking-widest" id="ticket-code">TMC-9941-VIP</span>
                </div>
            </div>

            <button onclick="downloadPass()" class="w-full py-3.5 rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold text-xs uppercase tracking-wider transition mb-2 flex items-center justify-center gap-2">
                <i class="fas fa-copy"></i> Copy Digital Pass Info
            </button>
            <button onclick="closeModal()" class="text-xs text-gray-400 hover:text-gray-200">
                Close
            </button>
        </div>
    </div>

    <script>
        /* ==========================================================
           1. GALLERY DATA SOURCE & API SIMULATOR
           ========================================================== */
        const mockGalleryDatabase = [{
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
                image: 'https://images.unsplash.com/photo-1571266028243-3716f02d2d2e?q=80&w=1000&auto=format&fit=crop',
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
            },
            {
                id: 12,
                category: 'lighting',
                title: 'Prism Laser Beams in Smoke',
                desc: 'Volumetric geometric beam shaping slicing through cold atmospheric haze.',
                image: 'https://images.unsplash.com/photo-1520523839898-50712213d941?q=80&w=1000&auto=format&fit=crop',
                date: 'MAR 2026',
                likes: 730,
                photographer: 'BeamLab'
            }
        ];

        let activeCategory = 'all';
        let displayedCount = 6;
        let activeLightboxIndex = 0;
        let filteredImages = [...mockGalleryDatabase];

        // Populate Infinite Marquee Carousel Filmstrip
        function initFilmstrip() {
            const marquee = document.getElementById('filmstrip-marquee');
            if (!marquee) return;
            const doubleList = [...mockGalleryDatabase, ...mockGalleryDatabase];
            marquee.innerHTML = doubleList.map(item => `
                <div class="inline-block relative w-48 h-28 rounded-xl overflow-hidden border border-white/10 group cursor-pointer flex-shrink-0" onclick="openLightboxById(${item.id})">
                    <img src="${item.image}" alt="${item.title}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    <div class="absolute inset-0 bg-midnight/40 group-hover:bg-transparent transition-colors"></div>
                    <span class="absolute bottom-1.5 left-2 text-[9px] font-mono font-bold text-white bg-midnight/80 px-2 py-0.5 rounded backdrop-blur-sm border border-white/10">${item.title}</span>
                </div>
            `).join('');
        }
        initFilmstrip();

        // Render Multi-Image Masonry/Bento Cards
        function renderGallery() {
            const container = document.getElementById('gallery-container');
            const counter = document.getElementById('gallery-counter');
            if (!container) return;

            filteredImages = activeCategory === 'all' ?
                mockGalleryDatabase :
                mockGalleryDatabase.filter(img => img.category === activeCategory);

            counter.textContent = `${filteredImages.length} Photos`;

            const visible = filteredImages.slice(0, displayedCount);

            container.innerHTML = visible.map((img, idx) => `
                <div class="glass-card rounded-3xl overflow-hidden group relative flex flex-col justify-between cursor-pointer border border-white/10 hover:border-neon-pink/70 transition-all duration-300" onclick="openLightboxByIndex(${idx})">
                    
                    <!-- Top Image Frame -->
                    <div class="relative h-64 overflow-hidden">
                        <img src="${img.image}" alt="${img.title}" loading="lazy" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                        <div class="absolute inset-0 bg-gradient-to-t from-dark-card via-transparent to-transparent"></div>
                        
                        <!-- Badges -->
                        <div class="absolute top-4 left-4 flex items-center gap-2">
                            <span class="bg-midnight/85 backdrop-blur-md px-3 py-1 rounded-full text-[10px] font-bold text-neon-pink border border-neon-pink/30 uppercase tracking-wider">
                                ${img.category}
                            </span>
                            <span class="bg-midnight/70 backdrop-blur-md px-2.5 py-1 rounded-full text-[10px] font-mono text-gray-300 border border-white/10">
                                ${img.date}
                            </span>
                        </div>

                        <!-- Quick Hover Zoom Icon -->
                        <div class="absolute top-4 right-4 w-9 h-9 rounded-full bg-midnight/80 border border-white/20 flex items-center justify-center text-white opacity-0 group-hover:opacity-100 group-hover:scale-110 transition-all duration-300">
                            <i class="fas fa-expand text-xs"></i>
                        </div>
                    </div>

                    <!-- Bottom Details -->
                    <div class="p-6">
                        <h4 class="font-syne text-lg font-bold text-white mb-1.5 group-hover:text-neon-pink transition-colors">
                            ${img.title}
                        </h4>
                        <p class="text-gray-400 text-xs leading-relaxed mb-4 line-clamp-2">
                            ${img.desc}
                        </p>
                        
                        <div class="flex items-center justify-between text-xs text-gray-400 pt-3 border-t border-white/10">
                            <span class="flex items-center gap-1.5 text-[11px]">
                                <i class="fas fa-camera-retro text-neon-purple"></i>
                                <span>${img.photographer}</span>
                            </span>
                            <span class="flex items-center gap-1 text-[11px] text-neon-pink">
                                <i class="fas fa-heart"></i>
                                <span>${img.likes}</span>
                            </span>
                        </div>
                    </div>
                </div>
            `).join('');

            // Toggle load more button visibility
            const loadBtn = document.getElementById('load-more-btn');
            if (loadBtn) {
                if (displayedCount >= filteredImages.length) {
                    loadBtn.classList.add('hidden');
                } else {
                    loadBtn.classList.remove('hidden');
                }
            }
        }
        renderGallery();

        // Filter Function
        function filterGallery(category, btn) {
            activeCategory = category;
            displayedCount = 6;

            document.querySelectorAll('.gallery-tab').forEach(b => {
                b.className = 'gallery-tab px-5 py-2.5 rounded-full text-xs font-bold bg-dark-card text-gray-300 hover:text-white border border-gray-700 hover:border-neon-purple whitespace-nowrap transition';
            });
            btn.className = 'gallery-tab active px-5 py-2.5 rounded-full text-xs font-bold bg-gradient-to-r from-neon-purple to-neon-pink text-white shadow-neon-pink whitespace-nowrap transition';

            renderGallery();
        }

        // Load More Handler
        function loadMoreGalleryImages() {
            displayedCount += 6;
            renderGallery();
        }

        // Simulated API Refresh with skeleton shimmer effect
        function refreshGalleryFromAPI() {
            const container = document.getElementById('gallery-container');
            const icon = document.getElementById('api-refresh-icon');
            if (icon) icon.classList.add('fa-spin');

            // Render 6 skeleton cards
            container.innerHTML = Array(6).fill(0).map(() => `
                <div class="glass-card rounded-3xl overflow-hidden p-4">
                    <div class="w-full h-56 rounded-2xl skeleton mb-4"></div>
                    <div class="w-3/4 h-5 rounded-lg skeleton mb-2"></div>
                    <div class="w-1/2 h-4 rounded-lg skeleton"></div>
                </div>
            `).join('');

            setTimeout(() => {
                if (icon) icon.classList.remove('fa-spin');
                renderGallery();
            }, 800);
        }

        /* ==========================================================
           2. LIGHTBOX / CINEMA MODAL INTERACTION
           ========================================================== */
        const lightbox = document.getElementById('lightbox-modal');
        const lbImg = document.getElementById('lb-image');
        const lbTitle = document.getElementById('lb-title');
        const lbDesc = document.getElementById('lb-desc');
        const lbCat = document.getElementById('lb-category');
        const lbCounter = document.getElementById('lb-counter');

        function updateLightboxView() {
            const item = filteredImages[activeLightboxIndex];
            if (!item) return;

            lbImg.src = item.image;
            lbTitle.textContent = item.title;
            lbDesc.textContent = `${item.desc} • Captured by ${item.photographer}`;
            lbCat.textContent = item.category;
            lbCounter.textContent = `${activeLightboxIndex + 1} / ${filteredImages.length}`;
        }

        function openLightboxByIndex(idx) {
            activeLightboxIndex = idx;
            updateLightboxView();
            lightbox.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function openLightboxById(id) {
            const idx = filteredImages.findIndex(i => i.id === id);
            if (idx !== -1) openLightboxByIndex(idx);
            else {
                activeLightboxIndex = 0;
                updateLightboxView();
                lightbox.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }
        }

        function closeLightbox() {
            lightbox.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        function prevLightboxImage() {
            activeLightboxIndex = (activeLightboxIndex - 1 + filteredImages.length) % filteredImages.length;
            updateLightboxView();
        }

        function nextLightboxImage() {
            activeLightboxIndex = (activeLightboxIndex + 1) % filteredImages.length;
            updateLightboxView();
        }

        function shareActiveImage() {
            const item = filteredImages[activeLightboxIndex];
            const text = `Check out this VIP night moment: ${item.title} - The Midnight Club (${window.location.href})`;
            const ta = document.createElement('textarea');
            ta.value = text;
            document.body.appendChild(ta);
            ta.select();
            document.execCommand('copy');
            document.body.removeChild(ta);

            alert("Image moment link copied to clipboard!");
        }

        // Keyboard arrows for Lightbox
        window.addEventListener('keydown', (e) => {
            if (!lightbox.classList.contains('hidden')) {
                if (e.key === 'ArrowLeft') prevLightboxImage();
                if (e.key === 'ArrowRight') nextLightboxImage();
                if (e.key === 'Escape') closeLightbox();
            }
        });

        /* ==========================================================
           3. VIP ZONE SELECTOR & PREFILL RESERVATION
           ========================================================== */
        let currentZone = {
            name: 'Stage VIP Booth',
            minSpend: '$750'
        };

        function setZone(btn, name, price) {
            document.querySelectorAll('.zone-btn').forEach(b => {
                b.className = 'zone-btn py-2.5 px-3 rounded-xl border border-gray-700 bg-gray-900/60 text-gray-300 text-xs font-bold text-center hover:border-gray-500 transition';
                const sub = b.querySelector('span');
                if (sub) sub.className = 'block text-[10px] text-gray-400 font-normal';
            });

            btn.className = 'zone-btn py-2.5 px-3 rounded-xl border border-neon-purple bg-purple-950/40 text-white text-xs font-bold text-center transition';
            const activeSub = btn.querySelector('span');
            if (activeSub) activeSub.className = 'block text-[10px] text-neon-pink font-normal';

            currentZone = {
                name,
                minSpend: price
            };
        }

        function selectZoneAndScroll(zoneName, price, defaultGuests) {
            const guestSelect = document.getElementById('res-guests');
            if (guestSelect) guestSelect.value = defaultGuests;

            document.querySelectorAll('.zone-btn').forEach(b => {
                if (b.textContent.includes(zoneName.split(' ')[0])) {
                    setZone(b, zoneName, price);
                }
            });

            const reserveSec = document.getElementById('reserve');
            if (reserveSec) {
                reserveSec.scrollIntoView({
                    behavior: 'smooth'
                });
            }
        }

        /* ==========================================================
           4. RESERVATION FORM & DYNAMIC QR CODE GENERATOR
           ========================================================== */
        const dateInput = document.getElementById('res-date');
        if (dateInput) {
            const today = new Date().toISOString().split('T')[0];
            dateInput.value = today;
            dateInput.min = today;
        }

        function processBooking(e) {
            e.preventDefault();

            const name = document.getElementById('res-name').value;
            const email = document.getElementById('res-email').value;
            const date = document.getElementById('res-date').value;
            const time = document.getElementById('res-time').value;
            const guests = document.getElementById('res-guests').value;
            const randomCode = 'TMC-' + Math.floor(1000 + Math.random() * 9000) + '-VIP';

            // Populate Modal Fields
            document.getElementById('ticket-holder').textContent = name;
            document.getElementById('ticket-tier').textContent = currentZone.name;
            document.getElementById('ticket-date').textContent = date;
            document.getElementById('ticket-time').textContent = time;
            document.getElementById('ticket-guests').textContent = guests;
            document.getElementById('ticket-code').textContent = randomCode;

            // Generate Encrypted QR Code
            const qrContainer = document.getElementById('qrcode');
            qrContainer.innerHTML = '';
            new QRCode(qrContainer, {
                text: JSON.stringify({
                    club: 'The Midnight Club',
                    code: randomCode,
                    holder: name,
                    email: email,
                    zone: currentZone.name,
                    date: date,
                    time: time,
                    guests: guests,
                    verified: true
                }),
                width: 120,
                height: 120,
                colorDark: "#0b0c10",
                colorLight: "#ffffff",
                correctLevel: QRCode.CorrectLevel.H
            });

            document.getElementById('booking-modal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('booking-modal').classList.add('hidden');
        }

        function downloadPass() {
            const code = document.getElementById('ticket-code').textContent;
            const text = `*** THE MIDNIGHT CLUB - DIGITAL VIP PASS ***\nCode: ${code}\nHolder: ${document.getElementById('ticket-holder').textContent}\nZone: ${document.getElementById('ticket-tier').textContent}\nDate: ${document.getElementById('ticket-date').textContent}\nArrival: ${document.getElementById('ticket-time').textContent}\nGuests: ${document.getElementById('ticket-guests').textContent}\nEntry Gate: Priority VIP Gate A (Scan QR)`;

            const textarea = document.createElement("textarea");
            document.body.appendChild(textarea);
            textarea.value = text;
            textarea.select();
            document.execCommand("copy");
            document.body.removeChild(textarea);

            const btn = event.currentTarget;
            const original = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-check text-emerald-400 mr-1"></i> Pass Info Copied to Clipboard!';
            setTimeout(() => {
                btn.innerHTML = original;
            }, 2200);
        }

        /* ==========================================================
           5. FAQ ACCORDION HANDLER
           ========================================================== */
        function toggleFaq(btn) {
            const content = btn.nextElementSibling;
            const icon = btn.querySelector('i');
            const isOpen = content.style.maxHeight && content.style.maxHeight !== '0px';

            document.querySelectorAll('.faq-content').forEach(c => c.style.maxHeight = '0px');
            document.querySelectorAll('#faq-accordion i').forEach(ic => ic.classList.remove('rotate-180'));

            if (!isOpen) {
                content.style.maxHeight = content.scrollHeight + 'px';
                icon.classList.add('rotate-180');
            }
        }

        /* ==========================================================
           6. AUDIO VISUALIZER & DYNAMIC NOTCH MOTION
           ========================================================== */
        const canvas = document.getElementById('motion-visualizer');
        const ctx = canvas.getContext('2d');
        let isAudioVibeActive = true;
        let step = 0;

        function resizeCanvas() {
            canvas.width = canvas.parentElement.offsetWidth;
            canvas.height = canvas.parentElement.offsetHeight;
        }
        window.addEventListener('resize', resizeCanvas);
        resizeCanvas();

        function drawVisualizer() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            const w = canvas.width;
            const h = canvas.height;
            const bars = Math.floor(w / 8);
            step += isAudioVibeActive ? 0.045 : 0.006;

            for (let i = 0; i < bars; i++) {
                const x = i * 8;
                const freq1 = Math.sin(i * 0.12 + step) * 28;
                const freq2 = Math.cos(i * 0.06 - step * 1.5) * 22;
                const barHeight = isAudioVibeActive ?
                    Math.max(6, Math.abs(freq1 + freq2) * (h / 65)) :
                    4;

                const gradient = ctx.createLinearGradient(0, h, 0, h - barHeight);
                gradient.addColorStop(0, '#0b0c10');
                gradient.addColorStop(0.5, '#a855f7');
                gradient.addColorStop(1, '#ec4899');

                ctx.fillStyle = gradient;
                ctx.fillRect(x, h - barHeight, 4, barHeight);
            }

            requestAnimationFrame(drawVisualizer);
        }
        drawVisualizer();

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

        // Ambient Cursor Follower
        const glow = document.getElementById('cursor-glow');
        if (glow) {
            window.addEventListener('mousemove', (e) => {
                glow.style.left = e.clientX + 'px';
                glow.style.top = e.clientY + 'px';
            });
        }

        // Mobile Menu Drawer
        const mobileToggle = document.getElementById('mobile-toggle');
        const mobileMenu = document.getElementById('mobile-menu');
        const closeMobileMenu = document.getElementById('close-mobile-menu');
        const mobileLinks = document.querySelectorAll('.mobile-link');

        if (mobileToggle && mobileMenu) {
            mobileToggle.addEventListener('click', () => mobileMenu.classList.remove('hidden'));
        }
        if (closeMobileMenu && mobileMenu) {
            closeMobileMenu.addEventListener('click', () => mobileMenu.classList.add('hidden'));
        }
        mobileLinks.forEach(link => {
            link.addEventListener('click', () => mobileMenu.classList.add('hidden'));
        });
    </script>
</body>

</html>