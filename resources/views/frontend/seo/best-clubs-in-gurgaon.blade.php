<x-frontend::layouts title="Best Clubs in Gurgaon | Top Nightlife Experience at The Midnight Club" description="Find out why The Midnight Club is consistently rated among the best clubs in Gurgaon. Enjoy our exclusive table service, top DJs, and unmatched party vibe." keywords="best clubs in gurgaon, midnight club gurgaon, gurgaon nightlife, premium clubs in gurgaon">
    <div class="relative bg-[#0b0c10] text-gray-100 min-h-screen font-sans overflow-hidden selection:bg-purple-500 selection:text-white">

        <!-- Ambient Background -->
        <div class="fixed inset-0 pointer-events-none overflow-hidden z-0">
            <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1574169208507-84376144848b?q=80&w=1920&auto=format&fit=crop')] bg-cover bg-center opacity-15"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-[#0b0c10] via-[#0b0c10]/80 to-[#0b0c10]/95"></div>
            <div class="absolute top-1/4 left-1/4 w-[600px] h-[600px] bg-indigo-600/10 rounded-full blur-[160px] animate-pulse"></div>
        </div>

        <!-- Hero Section -->
        <div class="relative z-10  pb-20 px-6 max-w-7xl mx-auto text-center">
            <h1 class="text-5xl md:text-7xl font-black text-white mb-6 tracking-tight leading-tight">
                Explore the <span class="bg-gradient-to-r from-purple-400 to-indigo-500 text-transparent bg-clip-text drop-shadow-[0_0_20px_rgba(99,102,241,0.3)]">Best Clubs</span><br>in Gurgaon
            </h1>
            <p class="text-gray-400 max-w-2xl mx-auto text-lg md:text-xl leading-relaxed mb-10">
                Gurgaon's nightlife is legendary, and at the heart of it all is The Midnight Club. Renowned for its electrifying ambiance and premium services, we stand out as the definitive destination for partygoers.
            </p>
            <div class="flex flex-col sm:flex-row justify-center items-center gap-4">
                <a href="{{ url('/#reserve') }}" class="group relative inline-flex items-center justify-center gap-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-bold text-lg px-8 py-4 rounded-full shadow-[0_0_30px_rgba(99,102,241,0.5)] hover:scale-105 transition-all duration-300">
                    <i class="fas fa-glass-martini-alt"></i> Reserve a Table
                </a>
            </div>
        </div>

        <!-- Content Section -->
        <div class="relative z-10 max-w-5xl mx-auto px-6 pb-24">
            <div class="glass-card p-10 md:p-14 rounded-3xl border border-white/10 shadow-2xl backdrop-blur-xl">
                <div class="grid md:grid-cols-2 gap-12 items-center">
                    <div class="relative order-2 md:order-1">
                        <div class="absolute inset-0 bg-gradient-to-r from-purple-500 to-indigo-500 rounded-2xl blur-xl opacity-30 animate-pulse"></div>
                        <img src="https://images.unsplash.com/photo-1492684223066-81342ee5ff30?q=80&w=800&auto=format&fit=crop" alt="Best Clubs in Gurgaon" class="relative rounded-2xl border border-white/10 shadow-2xl object-cover h-[400px] w-full">
                    </div>
                    <div class="order-1 md:order-2">
                        <h2 class="text-3xl font-bold text-white mb-6">A Night to Remember</h2>
                        <p class="text-gray-400 leading-relaxed mb-6">
                            When comparing the <strong>best clubs in Gurgaon</strong>, The Midnight Club sets itself apart through a meticulous dedication to the guest experience. From our hand-crafted cocktails to our VIP bottle service, every detail is refined.
                        </p>
                        <p class="text-gray-400 leading-relaxed mb-8">
                            Step onto our massive dance floor and lose yourself in the music. With resident DJs playing the hottest tracks and a lighting system that dazzles the senses, you are guaranteed a night of pure exhilaration.
                        </p>
                        <ul class="space-y-4">
                            <li class="flex items-center gap-3 text-gray-300"><i class="fas fa-arrow-right text-indigo-500"></i> Expansive Dance Floors</li>
                            <li class="flex items-center gap-3 text-gray-300"><i class="fas fa-arrow-right text-purple-500"></i> Premium Mixology</li>
                            <li class="flex items-center gap-3 text-gray-300"><i class="fas fa-arrow-right text-indigo-500"></i> Elite Crowd</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-frontend::layouts>