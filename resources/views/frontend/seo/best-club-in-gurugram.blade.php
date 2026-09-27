<x-frontend::layouts title="Best Club in Gurugram | The Midnight Club" description="Looking for the best club in Gurugram? Experience unrivaled nightlife, premium bottle service, and international DJs at The Midnight Club." keywords="best club in gurugram, midnight club gurugram, top clubs gurugram, premium nightlife">
    <div class="relative bg-[#0b0c10] text-gray-100 min-h-screen font-sans overflow-hidden selection:bg-pink-500 selection:text-white">

        <!-- Ambient Background -->
        <div class="fixed inset-0 pointer-events-none overflow-hidden z-0">
            <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1566737236500-c8ac43014a67?q=80&w=1920&auto=format&fit=crop')] bg-cover bg-center opacity-10"></div>
            <div class="absolute inset-0 bg-gradient-to-b from-[#0b0c10]/80 via-[#0b0c10]/95 to-[#0b0c10]"></div>
            <div class="absolute top-0 right-1/4 w-[500px] h-[500px] bg-purple-600/10 rounded-full blur-[150px] animate-pulse"></div>
        </div>

        <!-- Hero Section -->
        <div class="relative z-10 pb-20 px-6 max-w-7xl mx-auto text-center">
            <div class="inline-block bg-pink-900/30 border border-pink-500/30 text-pink-400 px-4 py-1.5 rounded-full text-xs font-bold tracking-widest uppercase mb-6 shadow-[0_0_15px_rgba(236,72,153,0.2)]">
                Rated #1 in Gurugram
            </div>
            <h1 class="text-5xl md:text-7xl font-black text-white mb-6 tracking-tight leading-tight">
                Discover the <span class="bg-gradient-to-r from-pink-500 to-purple-500 text-transparent bg-clip-text drop-shadow-[0_0_20px_rgba(236,72,153,0.3)]">Best Club</span><br>in Gurugram
            </h1>
            <p class="text-gray-400 max-w-2xl mx-auto text-lg md:text-xl leading-relaxed mb-10">
                Elevate your nightlife experience at The Midnight Club. From world-class acoustics to exclusive VIP table services, we define what it means to party in Gurugram's elite circles.
            </p>
            <a href="{{ url('/#reserve') }}" class="group relative inline-flex items-center justify-center gap-3 bg-gradient-to-r from-pink-500 to-purple-600 text-white font-bold text-lg px-10 py-4 rounded-full shadow-[0_0_30px_rgba(236,72,153,0.5)] hover:scale-105 transition-all duration-300">
                <i class="fas fa-glass-cheers"></i> Reserve Your VIP Table
            </a>
        </div>

        <!-- Content Section -->
        <div class="relative z-10 max-w-5xl mx-auto px-6 pb-24">
            <div class="glass-card p-10 md:p-14 rounded-3xl border border-white/10 shadow-2xl backdrop-blur-xl">
                <div class="grid md:grid-cols-2 gap-12 items-center">
                    <div>
                        <h2 class="text-3xl font-bold text-white mb-6">Why We Are Gurugram's Top Destination</h2>
                        <p class="text-gray-400 leading-relaxed mb-6">
                            When searching for the <strong>best club in Gurugram</strong>, connoisseurs of nightlife demand perfection. At The Midnight Club, we deliver an intoxicating blend of high-energy ambiance, international DJ acts, and impeccable hospitality.
                        </p>
                        <p class="text-gray-400 leading-relaxed mb-8">
                            Whether you're celebrating a milestone, hosting a corporate after-party, or simply seeking an unforgettable weekend, our expansive dance floors and private VIP lounges cater to every desire. Our signature cocktails and premium bottle service ensure your night is nothing short of legendary.
                        </p>
                        <ul class="space-y-4">
                            <li class="flex items-center gap-3 text-gray-300"><i class="fas fa-check-circle text-pink-500"></i> International Guest DJs</li>
                            <li class="flex items-center gap-3 text-gray-300"><i class="fas fa-check-circle text-purple-500"></i> Elite VIP Bottle Service</li>
                            <li class="flex items-center gap-3 text-gray-300"><i class="fas fa-check-circle text-pink-500"></i> State-of-the-Art Sound & Lighting</li>
                        </ul>
                    </div>
                    <div class="relative">
                        <div class="absolute inset-0 bg-gradient-to-r from-pink-500 to-purple-500 rounded-2xl blur-xl opacity-30 animate-pulse"></div>
                        <img src="https://images.unsplash.com/photo-1545128485-c400e7702796?q=80&w=800&auto=format&fit=crop" alt="Best Club in Gurugram" class="relative rounded-2xl border border-white/10 shadow-2xl object-cover h-[400px] w-full">
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-frontend::layouts>