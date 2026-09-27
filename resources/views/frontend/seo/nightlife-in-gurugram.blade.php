<x-frontend::layouts title="Nightlife in Gurugram | Experience The Midnight Club" description="Discover the ultimate nightlife in Gurugram. From pulsating dance floors to elite VIP lounges, The Midnight Club sets the standard for after-dark entertainment." keywords="nightlife in gurugram, gurugram nightlife, midnight club nightlife, party scene gurugram">
    <div class="relative bg-[#0b0c10] text-gray-100 min-h-screen font-sans overflow-hidden selection:bg-purple-500 selection:text-white">

        <!-- Ambient Background -->
        <div class="fixed inset-0 pointer-events-none overflow-hidden z-0">
            <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1470225620780-dba8ba36b745?q=80&w=1920&auto=format&fit=crop')] bg-cover bg-center opacity-20"></div>
            <div class="absolute inset-0 bg-gradient-to-b from-[#0b0c10]/80 via-[#0b0c10]/95 to-[#0b0c10]"></div>
            <div class="absolute top-0 right-1/4 w-[500px] h-[500px] bg-purple-600/15 rounded-full blur-[150px] animate-pulse"></div>
        </div>

        <!-- Hero Section -->
        <div class="relative z-10  pb-20 px-6 max-w-7xl mx-auto text-center">
            <h1 class="text-5xl md:text-7xl font-black text-white mb-6 tracking-tight leading-tight">
                The Pinnacle of <span class="bg-gradient-to-r from-purple-500 to-indigo-500 text-transparent bg-clip-text drop-shadow-[0_0_20px_rgba(168,85,247,0.3)]">Nightlife</span><br>in Gurugram
            </h1>
            <p class="text-gray-400 max-w-2xl mx-auto text-lg md:text-xl leading-relaxed mb-10">
                Embrace the vibrant energy of the city after sunset. The Midnight Club is where Gurugram's trendsetters and music lovers converge for unparalleled nightlife experiences.
            </p>
            <a href="{{ url('/#reserve') }}" class="group relative inline-flex items-center justify-center gap-3 bg-gradient-to-r from-purple-500 to-indigo-600 text-white font-bold text-lg px-10 py-4 rounded-full shadow-[0_0_30px_rgba(168,85,247,0.5)] hover:scale-105 transition-all duration-300">
                <i class="fas fa-music"></i> Join the Party
            </a>
        </div>

        <!-- Content Section -->
        <div class="relative z-10 max-w-5xl mx-auto px-6 pb-24">
            <div class="glass-card p-10 md:p-14 rounded-3xl border border-white/10 shadow-2xl backdrop-blur-xl">
                <div class="grid md:grid-cols-2 gap-12 items-center">
                    <div>
                        <h2 class="text-3xl font-bold text-white mb-6">A Culture of Celebration</h2>
                        <p class="text-gray-400 leading-relaxed mb-6">
                            The true essence of <strong>nightlife in Gurugram</strong> is captured within the walls of The Midnight Club. It's more than just a venue; it's a lifestyle destination where every weekend is a major event.
                        </p>
                        <p class="text-gray-400 leading-relaxed mb-8">
                            Lose yourself in immersive visual effects and crystal-clear sound systems. We cater to those who demand a sophisticated environment infused with the raw energy of a world-class festival.
                        </p>
                        <ul class="space-y-4">
                            <li class="flex items-center gap-3 text-gray-300"><i class="fas fa-play text-purple-500"></i> Breathtaking Light Shows</li>
                            <li class="flex items-center gap-3 text-gray-300"><i class="fas fa-play text-indigo-500"></i> Elite Networking</li>
                            <li class="flex items-center gap-3 text-gray-300"><i class="fas fa-play text-purple-500"></i> Extended Hours</li>
                        </ul>
                    </div>
                    <div class="relative">
                        <div class="absolute inset-0 bg-gradient-to-r from-purple-500 to-indigo-500 rounded-2xl blur-xl opacity-30 animate-pulse"></div>
                        <img src="https://images.unsplash.com/photo-1516450360452-9312f5e86fc7?q=80&w=800&auto=format&fit=crop" alt="Nightlife in Gurugram" class="relative rounded-2xl border border-white/10 shadow-2xl object-cover h-[400px] w-full">
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-frontend::layouts>