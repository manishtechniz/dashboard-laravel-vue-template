<x-frontend::layouts title="Clubs Near Cyber Hub | Experience The Midnight Club" description="Looking for elite clubs near Cyber Hub? Head over to The Midnight Club on MG Road for the ultimate late-night party, premium drinks, and live DJs." keywords="clubs near cyber hub, cyber hub clubs, nightlife near cyber hub, parties near dlf cyber city">
    <div class="relative bg-[#0b0c10] text-gray-100 min-h-screen font-sans overflow-hidden selection:bg-pink-500 selection:text-white">

        <!-- Ambient Background -->
        <div class="fixed inset-0 pointer-events-none overflow-hidden z-0">
            <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1514525253161-7a46d19cd819?q=80&w=1920&auto=format&fit=crop')] bg-cover bg-center opacity-15"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-[#0b0c10] via-[#0b0c10]/80 to-[#0b0c10]/95"></div>
            <div class="absolute bottom-1/4 right-1/4 w-[600px] h-[600px] bg-pink-600/10 rounded-full blur-[160px] animate-pulse"></div>
        </div>

        <!-- Hero Section -->
        <div class="relative z-10  pb-20 px-6 max-w-7xl mx-auto text-center">
            <h1 class="text-5xl md:text-7xl font-black text-white mb-6 tracking-tight leading-tight">
                Nightlife Near <span class="bg-gradient-to-r from-pink-500 to-purple-500 text-transparent bg-clip-text drop-shadow-[0_0_20px_rgba(236,72,153,0.3)]">Cyber Hub</span>
            </h1>
            <p class="text-gray-400 max-w-2xl mx-auto text-lg md:text-xl leading-relaxed mb-10">
                After dining in Cyber Hub, keep the night alive. The Midnight Club offers the most exclusive late-night party experience, just a short drive down the road.
            </p>
            <div class="flex flex-col sm:flex-row justify-center items-center gap-4">
                <a href="{{ url('/#reserve') }}" class="group relative inline-flex items-center justify-center gap-3 bg-gradient-to-r from-pink-600 to-purple-600 text-white font-bold text-lg px-8 py-4 rounded-full shadow-[0_0_30px_rgba(236,72,153,0.5)] hover:scale-105 transition-all duration-300">
                    <i class="fas fa-glass-cheers"></i> Book Your Table
                </a>
            </div>
        </div>

        <!-- Content Section -->
        <div class="relative z-10 max-w-5xl mx-auto px-6 pb-24">
            <div class="glass-card p-10 md:p-14 rounded-3xl border border-white/10 shadow-2xl backdrop-blur-xl">
                <div class="grid md:grid-cols-2 gap-12 items-center">
                    <div>
                        <h2 class="text-3xl font-bold text-white mb-6">The Perfect After-Party</h2>
                        <p class="text-gray-400 leading-relaxed mb-6">
                            Searching for <strong>clubs near Cyber Hub</strong> often leads to crowded bars, but The Midnight Club provides the spacious, high-energy environment you crave after 11 PM.
                        </p>
                        <p class="text-gray-400 leading-relaxed mb-8">
                            We cater to the corporate elite and weekend partygoers looking for a seamless transition from dinner to dancing. Enjoy world-class hospitality, exclusive VIP sections, and a sound system that will blow you away.
                        </p>
                        <ul class="space-y-4">
                            <li class="flex items-center gap-3 text-gray-300"><i class="fas fa-arrow-right text-pink-500"></i> Open Until 7 AM</li>
                            <li class="flex items-center gap-3 text-gray-300"><i class="fas fa-arrow-right text-purple-500"></i> Corporate VIP Packages</li>
                            <li class="flex items-center gap-3 text-gray-300"><i class="fas fa-arrow-right text-pink-500"></i> Unrivaled DJ Lineups</li>
                        </ul>
                    </div>
                    <div class="relative">
                        <div class="absolute inset-0 bg-gradient-to-r from-pink-500 to-purple-500 rounded-2xl blur-xl opacity-30 animate-pulse"></div>
                        <img src="https://images.unsplash.com/photo-1559336197-ded8aaa244bc?q=80&w=800&auto=format&fit=crop" alt="Clubs Near Cyber Hub" class="relative rounded-2xl border border-white/10 shadow-2xl object-cover h-[400px] w-full">
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-frontend::layouts>