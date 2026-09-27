<x-frontend::layouts title="Best Clubs on MG Road Gurgaon | The Midnight Club" description="Experience the pinnacle of nightlife at The Midnight Club, located directly on MG Road. We are the most exclusive and vibrant club on Mehrauli-Gurgaon Road." keywords="clubs on mg road gurgaon, mg road clubs, best club mg road, midnight club mg road">
    <div class="relative bg-[#0b0c10] text-gray-100 min-h-screen font-sans overflow-hidden selection:bg-purple-500 selection:text-white">

        <!-- Ambient Background -->
        <div class="fixed inset-0 pointer-events-none overflow-hidden z-0">
            <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1517457373958-b7bdd4587205?q=80&w=1920&auto=format&fit=crop')] bg-cover bg-center opacity-20"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-[#0b0c10] via-[#0b0c10]/90 to-[#0b0c10]/70"></div>
            <div class="absolute top-1/4 left-1/4 w-[600px] h-[600px] bg-indigo-600/10 rounded-full blur-[160px] animate-pulse"></div>
        </div>

        <!-- Hero Section -->
        <div class="relative z-10  pb-20 px-6 max-w-7xl mx-auto text-center">
            <h1 class="text-5xl md:text-7xl font-black text-white mb-6 tracking-tight leading-tight">
                The Best Club on <span class="bg-gradient-to-r from-purple-400 to-indigo-500 text-transparent bg-clip-text drop-shadow-[0_0_20px_rgba(99,102,241,0.3)]">MG Road</span>
            </h1>
            <p class="text-gray-400 max-w-2xl mx-auto text-lg md:text-xl leading-relaxed mb-10">
                Conveniently located at Plaza Mall on MG Road, The Midnight Club is the undisputed king of Mehrauli-Gurgaon Road's nightlife scene.
            </p>
            <div class="flex flex-col sm:flex-row justify-center items-center gap-4">
                <a href="{{ url('/#reserve') }}" class="group relative inline-flex items-center justify-center gap-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-bold text-lg px-8 py-4 rounded-full shadow-[0_0_30px_rgba(99,102,241,0.5)] hover:scale-105 transition-all duration-300">
                    <i class="fas fa-map-marker-alt"></i> Book Your Visit
                </a>
            </div>
        </div>

        <!-- Content Section -->
        <div class="relative z-10 max-w-5xl mx-auto px-6 pb-24">
            <div class="glass-card p-10 md:p-14 rounded-3xl border border-white/10 shadow-2xl backdrop-blur-xl">
                <div class="grid md:grid-cols-2 gap-12 items-center">
                    <div class="relative order-2 md:order-1">
                        <div class="absolute inset-0 bg-gradient-to-r from-purple-500 to-indigo-500 rounded-2xl blur-xl opacity-30 animate-pulse"></div>
                        <img src="https://images.unsplash.com/photo-1571266028243-cb40fce75737?q=80&w=800&auto=format&fit=crop" alt="Clubs on MG Road" class="relative rounded-2xl border border-white/10 shadow-2xl object-cover h-[400px] w-full">
                    </div>
                    <div class="order-1 md:order-2">
                        <h2 class="text-3xl font-bold text-white mb-6">The Jewel of Plaza Mall</h2>
                        <p class="text-gray-400 leading-relaxed mb-6">
                            When searching for <strong>clubs on MG Road Gurgaon</strong>, The Midnight Club stands out for its sprawling space, premium security, and ultra-luxurious vibe. Located on the 3rd floor of Plaza Mall, we are easily accessible yet perfectly exclusive.
                        </p>
                        <p class="text-gray-400 leading-relaxed mb-8">
                            Skip the crowded bars on the strip and step into a world of elite VIP service, stunning light shows, and the city's best crowd.
                        </p>
                        <ul class="space-y-4">
                            <li class="flex items-center gap-3 text-gray-300"><i class="fas fa-arrow-right text-indigo-500"></i> Easy Access on MG Road</li>
                            <li class="flex items-center gap-3 text-gray-300"><i class="fas fa-arrow-right text-purple-500"></i> Secure Parking Available</li>
                            <li class="flex items-center gap-3 text-gray-300"><i class="fas fa-arrow-right text-indigo-500"></i> Exquisite Nightlife Venue</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-frontend::layouts>