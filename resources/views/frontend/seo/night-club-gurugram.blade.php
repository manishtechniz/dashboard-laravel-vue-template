<x-frontend::layouts title="Night Club Gurugram | The Midnight Club" description="Looking for the premier night club in Gurugram? The Midnight Club offers unforgettable nights with top DJs, live events, and exclusive VIP tables." keywords="night club gurugram, midnight club gurugram, gurugram nightlife, party in gurugram">
    <div class="relative bg-[#0b0c10] text-gray-100 min-h-screen font-sans overflow-hidden selection:bg-purple-500 selection:text-white">

        <!-- Ambient Background -->
        <div class="fixed inset-0 pointer-events-none overflow-hidden z-0">
            <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1574169208507-84376144848b?q=80&w=1920&auto=format&fit=crop')] bg-cover bg-center opacity-10"></div>
            <div class="absolute inset-0 bg-gradient-to-b from-[#0b0c10]/80 via-[#0b0c10]/95 to-[#0b0c10]"></div>
            <div class="absolute top-1/4 left-1/4 w-[600px] h-[600px] bg-pink-600/10 rounded-full blur-[160px] animate-pulse"></div>
        </div>

        <!-- Hero Section -->
        <div class="relative z-10  pb-20 px-6 max-w-7xl mx-auto text-center">
            <h1 class="text-5xl md:text-7xl font-black text-white mb-6 tracking-tight leading-tight">
                The Ultimate <span class="bg-gradient-to-r from-purple-400 to-pink-500 text-transparent bg-clip-text drop-shadow-[0_0_20px_rgba(168,85,247,0.3)]">Night Club</span><br>in Gurugram
            </h1>
            <p class="text-gray-400 max-w-2xl mx-auto text-lg md:text-xl leading-relaxed mb-10">
                Step into a world where the music never stops. As the leading night club in Gurugram, The Midnight Club brings you an electrifying atmosphere, chart-topping DJs, and a vibrant crowd every single night.
            </p>
            <div class="flex flex-col sm:flex-row justify-center items-center gap-4">
                <a href="{{ url('/#reserve') }}" class="group relative inline-flex items-center justify-center gap-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white font-bold text-lg px-8 py-4 rounded-full shadow-[0_0_30px_rgba(168,85,247,0.5)] hover:scale-105 transition-all duration-300">
                    <i class="fas fa-ticket-alt"></i> Get Digital Passes
                </a>
            </div>
        </div>

        <!-- Content Section -->
        <div class="relative z-10 max-w-5xl mx-auto px-6 pb-24">
            <div class="glass-card p-10 md:p-14 rounded-3xl border border-white/10 shadow-2xl backdrop-blur-xl">
                <div class="grid md:grid-cols-2 gap-12 items-center">
                    <div class="relative order-2 md:order-1">
                        <div class="absolute inset-0 bg-gradient-to-r from-purple-500 to-pink-500 rounded-2xl blur-xl opacity-30 animate-pulse"></div>
                        <img src="https://images.unsplash.com/photo-1492684223066-81342ee5ff30?q=80&w=800&auto=format&fit=crop" alt="Night Club Gurugram" class="relative rounded-2xl border border-white/10 shadow-2xl object-cover h-[400px] w-full">
                    </div>
                    <div class="order-1 md:order-2">
                        <h2 class="text-3xl font-bold text-white mb-6">Experience True Nightlife</h2>
                        <p class="text-gray-400 leading-relaxed mb-6">
                            Finding the perfect <strong>night club in Gurugram</strong> means looking for unparalleled energy. The Midnight Club is meticulously designed to immerse you in sound, light, and luxury.
                        </p>
                        <p class="text-gray-400 leading-relaxed mb-8">
                            Join us for our signature weekend parties, highly anticipated DJ sets, and exclusive live events. From the moment you walk through our doors, you are treated to a premium nightlife experience unlike any other in the city.
                        </p>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-white/5 p-4 rounded-xl border border-white/10 text-center">
                                <i class="fas fa-music text-2xl text-purple-400 mb-2"></i>
                                <h4 class="text-white font-bold text-sm">Live DJs</h4>
                            </div>
                            <div class="bg-white/5 p-4 rounded-xl border border-white/10 text-center">
                                <i class="fas fa-cocktail text-2xl text-pink-400 mb-2"></i>
                                <h4 class="text-white font-bold text-sm">Craft Drinks</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-frontend::layouts>