<x-frontend::layouts title="Nightlife in Gurgaon | Premium Clubbing at The Midnight Club" description="Dive into the best nightlife in Gurgaon. The Midnight Club offers a spectacular mix of music, drinks, and incredible crowds for a memorable night out." keywords="nightlife in gurgaon, gurgaon nightlife, midnight club gurgaon nightlife">
    <div class="relative bg-[#0b0c10] text-gray-100 min-h-screen font-sans overflow-hidden selection:bg-pink-500 selection:text-white">

        <!-- Ambient Background -->
        <div class="fixed inset-0 pointer-events-none overflow-hidden z-0">
            <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1545128485-c400e7702796?q=80&w=1920&auto=format&fit=crop')] bg-cover bg-center opacity-15"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-[#0b0c10] via-[#0b0c10]/90 to-[#0b0c10]/70"></div>
            <div class="absolute bottom-0 right-0 w-[700px] h-[700px] bg-pink-600/10 rounded-full blur-[200px] animate-pulse"></div>
        </div>

        <!-- Hero Section -->
        <div class="relative z-10  pb-20 px-6 max-w-7xl mx-auto text-center">
            <h1 class="text-5xl md:text-7xl font-black text-white mb-6 tracking-tight leading-tight">
                Redefining <span class="bg-gradient-to-r from-pink-500 to-purple-500 text-transparent bg-clip-text drop-shadow-[0_0_20px_rgba(236,72,153,0.4)]">Nightlife</span><br>in Gurgaon
            </h1>
            <p class="text-gray-400 max-w-2xl mx-auto text-lg md:text-xl leading-relaxed mb-10">
                Experience Gurgaon's premier late-night destination. The Midnight Club is the beating heart of the city's party scene, delivering unforgettable moments every single night.
            </p>
            <a href="{{ url('/#reserve') }}" class="group relative inline-flex items-center justify-center gap-3 bg-gradient-to-r from-pink-600 to-purple-600 text-white font-bold text-lg px-10 py-4 rounded-full shadow-[0_0_30px_rgba(236,72,153,0.5)] hover:scale-105 transition-all duration-300">
                <i class="fas fa-fire"></i> Feel the Vibe
            </a>
        </div>

        <!-- Content Section -->
        <div class="relative z-10 max-w-5xl mx-auto px-6 pb-24">
            <div class="glass-card p-10 md:p-14 rounded-3xl border border-white/10 shadow-2xl backdrop-blur-xl">
                <div class="grid md:grid-cols-2 gap-12 items-center">
                    <div class="relative order-2 md:order-1">
                        <div class="absolute inset-0 bg-gradient-to-r from-pink-500 to-purple-500 rounded-2xl blur-xl opacity-30 animate-pulse"></div>
                        <img src="https://images.unsplash.com/photo-1571266028243-cb40fce75737?q=80&w=800&auto=format&fit=crop" alt="Nightlife in Gurgaon" class="relative rounded-2xl border border-white/10 shadow-2xl object-cover h-[400px] w-full">
                    </div>
                    <div class="order-1 md:order-2">
                        <h2 class="text-3xl font-bold text-white mb-6">A Dynamic Party Atmosphere</h2>
                        <p class="text-gray-400 leading-relaxed mb-6">
                            When exploring <strong>nightlife in Gurgaon</strong>, you want an experience that excites all your senses. The Midnight Club is engineered to provide an exhilarating escape from the everyday.
                        </p>
                        <p class="text-gray-400 leading-relaxed mb-8">
                            From signature cocktails crafted by expert bartenders to VIP areas offering a private retreat from the pulsating dance floor, we have perfected the art of the night out.
                        </p>
                        <div class="flex items-center gap-4 bg-white/5 p-4 rounded-xl border border-white/10">
                            <div class="w-12 h-12 rounded-full bg-pink-500/20 flex items-center justify-center">
                                <i class="fas fa-cocktail text-pink-400"></i>
                            </div>
                            <div>
                                <h4 class="text-white font-bold">Impeccable Service</h4>
                                <p class="text-xs text-gray-400">Premium hospitality all night</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-frontend::layouts>