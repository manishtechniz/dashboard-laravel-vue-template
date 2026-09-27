<x-frontend::layouts title="Night Clubs in Gurgaon | The Midnight Club Nightlife" description="Explore the best night clubs in Gurgaon. Join us at The Midnight Club for incredible music, fantastic drinks, and the most vibrant party crowd in the city." keywords="night clubs in gurgaon, midnight club gurgaon, gurgaon parties, clubbing in gurgaon">
    <div class="relative bg-[#0b0c10] text-gray-100 min-h-screen font-sans overflow-hidden selection:bg-purple-500 selection:text-white">

        <!-- Ambient Background -->
        <div class="fixed inset-0 pointer-events-none overflow-hidden z-0">
            <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1517457373958-b7bdd4587205?q=80&w=1920&auto=format&fit=crop')] bg-cover bg-center opacity-15"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-[#0b0c10] via-[#0b0c10]/90 to-[#0b0c10]/70"></div>
            <div class="absolute bottom-0 right-0 w-[700px] h-[700px] bg-purple-600/10 rounded-full blur-[200px] animate-pulse"></div>
        </div>

        <!-- Hero Section -->
        <div class="relative z-10  pb-20 px-6 max-w-7xl mx-auto text-center">
            <h1 class="text-5xl md:text-7xl font-black text-white mb-6 tracking-tight leading-tight">
                Top <span class="bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 text-transparent bg-clip-text drop-shadow-[0_0_20px_rgba(168,85,247,0.4)]">Night Clubs</span><br>in Gurgaon
            </h1>
            <p class="text-gray-400 max-w-2xl mx-auto text-lg md:text-xl leading-relaxed mb-10">
                Experience the magic of Gurgaon's after-hours scene. At The Midnight Club, we provide a sophisticated yet wild environment where every night is a celebration.
            </p>
            <a href="{{ url('/#reserve') }}" class="group relative inline-flex items-center justify-center gap-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-bold text-lg px-10 py-4 rounded-full shadow-[0_0_30px_rgba(99,102,241,0.5)] hover:scale-105 transition-all duration-300">
                <i class="fas fa-crown"></i> Reserve Your Spot
            </a>
        </div>

        <!-- Content Section -->
        <div class="relative z-10 max-w-5xl mx-auto px-6 pb-24">
            <div class="glass-card p-10 md:p-14 rounded-3xl border border-white/10 shadow-2xl backdrop-blur-xl">
                <div class="grid md:grid-cols-2 gap-12 items-center">
                    <div class="relative order-2 md:order-1">
                        <div class="absolute inset-0 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-2xl blur-xl opacity-30 animate-pulse"></div>
                        <img src="https://images.unsplash.com/photo-1571266028243-cb40fce75737?q=80&w=800&auto=format&fit=crop" alt="Night Clubs in Gurgaon" class="relative rounded-2xl border border-white/10 shadow-2xl object-cover h-[400px] w-full">
                    </div>
                    <div class="order-1 md:order-2">
                        <h2 class="text-3xl font-bold text-white mb-6">A New Standard in Clubbing</h2>
                        <p class="text-gray-400 leading-relaxed mb-6">
                            Finding premium <strong>night clubs in Gurgaon</strong> should be effortless. We take the guesswork out of your weekend plans by offering a consistently flawless experience.
                        </p>
                        <p class="text-gray-400 leading-relaxed mb-8">
                            Enjoy an eclectic mix of music genres, from pulsating house beats to popular commercial tracks, curated by our expert DJs. Pair that with our extensive selection of spirits and you have the recipe for an extraordinary night.
                        </p>
                        <div class="flex items-center gap-4 bg-white/5 p-4 rounded-xl border border-white/10">
                            <div class="w-12 h-12 rounded-full bg-purple-500/20 flex items-center justify-center">
                                <i class="fas fa-glass-cheers text-purple-400"></i>
                            </div>
                            <div>
                                <h4 class="text-white font-bold">Unforgettable Memories</h4>
                                <p class="text-xs text-gray-400">Party till the early hours</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-frontend::layouts>