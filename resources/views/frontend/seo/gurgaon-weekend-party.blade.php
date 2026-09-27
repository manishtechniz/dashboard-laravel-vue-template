<x-frontend::layouts title="Gurgaon Weekend Party | Best Friday & Saturday Nights at Midnight Club" description="Planning a Gurgaon weekend party? The Midnight Club throws the biggest Thursday, Friday, and Saturday night parties with live DJs and premium bottle service." keywords="gurgaon weekend party, weekend party gurgaon, friday night party gurgaon, saturday night party gurgaon">
    <div class="relative bg-[#0b0c10] text-gray-100 min-h-screen font-sans overflow-hidden selection:bg-pink-500 selection:text-white">

        <!-- Ambient Background -->
        <div class="fixed inset-0 pointer-events-none overflow-hidden z-0">
            <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1492684223066-81342ee5ff30?q=80&w=1920&auto=format&fit=crop')] bg-cover bg-center opacity-15"></div>
            <div class="absolute inset-0 bg-gradient-to-b from-[#0b0c10]/80 via-[#0b0c10]/95 to-[#0b0c10]"></div>
            <div class="absolute bottom-1/4 right-1/4 w-[500px] h-[500px] bg-pink-600/10 rounded-full blur-[150px] animate-pulse"></div>
        </div>

        <!-- Hero Section -->
        <div class="relative z-10  pb-20 px-6 max-w-7xl mx-auto text-center">
            <h1 class="text-5xl md:text-7xl font-black text-white mb-6 tracking-tight leading-tight">
                The Ultimate <span class="bg-gradient-to-r from-pink-500 to-purple-500 text-transparent bg-clip-text drop-shadow-[0_0_20px_rgba(236,72,153,0.3)]">Weekend Party</span><br>in Gurgaon
            </h1>
            <p class="text-gray-400 max-w-2xl mx-auto text-lg md:text-xl leading-relaxed mb-10">
                Don't waste your weekend. Join the biggest Thursday, Friday, and Saturday night celebrations at The Midnight Club, where the music never stops.
            </p>
            <a href="{{ url('/#reserve') }}" class="group relative inline-flex items-center justify-center gap-3 bg-gradient-to-r from-pink-500 to-purple-600 text-white font-bold text-lg px-10 py-4 rounded-full shadow-[0_0_30px_rgba(236,72,153,0.5)] hover:scale-105 transition-all duration-300">
                <i class="fas fa-glass-cheers"></i> Secure Weekend Tickets
            </a>
        </div>

        <!-- Content Section -->
        <div class="relative z-10 max-w-5xl mx-auto px-6 pb-24">
            <div class="glass-card p-10 md:p-14 rounded-3xl border border-white/10 shadow-2xl backdrop-blur-xl">
                <div class="grid md:grid-cols-2 gap-12 items-center">
                    <div>
                        <h2 class="text-3xl font-bold text-white mb-6">Own The Weekend</h2>
                        <p class="text-gray-400 leading-relaxed mb-6">
                            When looking for the best <strong>Gurgaon weekend party</strong>, the city's elite head straight to The Midnight Club. Our weekend nights are legendary, featuring packed dance floors, electrifying energy, and the best DJ sets in town.
                        </p>
                        <p class="text-gray-400 leading-relaxed mb-8">
                            Make your Saturday night unforgettable with our exclusive VIP tables. With fast-track entry and dedicated hosts, we ensure your weekend celebration is flawless from start to finish.
                        </p>
                        <ul class="space-y-4">
                            <li class="flex items-center gap-3 text-gray-300"><i class="fas fa-bolt text-yellow-500"></i> Massive Friday & Saturday Events</li>
                            <li class="flex items-center gap-3 text-gray-300"><i class="fas fa-couch text-indigo-500"></i> Pre-booked VIP Tables</li>
                            <li class="flex items-center gap-3 text-gray-300"><i class="fas fa-glass-martini-alt text-purple-500"></i> Open Until 7 AM</li>
                        </ul>
                    </div>
                    <div class="relative">
                        <div class="absolute inset-0 bg-gradient-to-r from-pink-500 to-purple-500 rounded-2xl blur-xl opacity-30 animate-pulse"></div>
                        <img src="https://images.unsplash.com/photo-1514525253161-7a46d19cd819?q=80&w=800&auto=format&fit=crop" alt="Weekend Party Gurgaon" class="relative rounded-2xl border border-white/10 shadow-2xl object-cover h-[400px] w-full">
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-frontend::layouts>