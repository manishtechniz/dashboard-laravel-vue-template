<x-frontend::layouts title="Best Nightclub in Gurgaon | Midnight Club" description="Discover the best nightclub in Gurgaon. The Midnight Club provides an exclusive VIP experience, unparalleled DJ nights, and premium table service." keywords="best nightclub in gurgaon, midnight club gurgaon, gurgaon clubs, top nightclubs">
    <div class="relative bg-[#0b0c10] text-gray-100 min-h-screen font-sans overflow-hidden selection:bg-pink-500 selection:text-white">

        <!-- Ambient Background -->
        <div class="fixed inset-0 pointer-events-none overflow-hidden z-0">
            <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1545128485-c400e7702796?q=80&w=1920&auto=format&fit=crop')] bg-cover bg-center opacity-15"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-[#0b0c10] via-[#0b0c10]/90 to-[#0b0c10]/70"></div>
            <div class="absolute bottom-0 right-0 w-[700px] h-[700px] bg-pink-600/10 rounded-full blur-[200px] animate-pulse"></div>
        </div>

        <!-- Hero Section -->
        <div class="relative z-10  pb-20 px-6 max-w-7xl mx-auto text-center">
            <div class="inline-block bg-purple-900/30 border border-purple-500/30 text-purple-400 px-5 py-2 rounded-full text-xs font-bold tracking-widest uppercase mb-6 shadow-[0_0_15px_rgba(168,85,247,0.2)]">
                Unrivaled Nightlife
            </div>
            <h1 class="text-5xl md:text-7xl font-black text-white mb-6 tracking-tight leading-tight">
                The <span class="bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 text-transparent bg-clip-text drop-shadow-[0_0_20px_rgba(168,85,247,0.4)]">Best Nightclub</span><br>in Gurgaon
            </h1>
            <p class="text-gray-400 max-w-2xl mx-auto text-lg md:text-xl leading-relaxed mb-10">
                Welcome to the pinnacle of luxury and entertainment. As the best nightclub in Gurgaon, The Midnight Club guarantees an unforgettable night out with world-class hospitality and breathtaking aesthetics.
            </p>
            <a href="{{ url('/#reserve') }}" class="group relative inline-flex items-center justify-center gap-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-bold text-lg px-10 py-4 rounded-full shadow-[0_0_30px_rgba(99,102,241,0.5)] hover:scale-105 transition-all duration-300">
                <i class="fas fa-crown"></i> Reserve VIP Table
            </a>
        </div>

        <!-- Content Section -->
        <div class="relative z-10 max-w-5xl mx-auto px-6 pb-24">
            <div class="glass-card p-10 md:p-14 rounded-3xl border border-white/10 shadow-2xl backdrop-blur-xl">
                <div class="grid md:grid-cols-2 gap-12 items-center">
                    <div>
                        <h2 class="text-3xl font-bold text-white mb-6">Why Choose The Midnight Club?</h2>
                        <p class="text-gray-400 leading-relaxed mb-6">
                            When locals and visitors ask for the <strong>best nightclub in Gurgaon</strong>, the answer is always The Midnight Club. Our venue combines opulent design with state-of-the-art acoustics to create an electrifying environment.
                        </p>
                        <p class="text-gray-400 leading-relaxed mb-8">
                            Enjoy personalized bottle service, exclusive entry, and a carefully curated guest list. Whether you're planning a massive celebration or an intimate VIP gathering, we provide the ultimate backdrop for your night out.
                        </p>
                        <div class="flex items-center gap-4 bg-white/5 p-4 rounded-xl border border-white/10">
                            <div class="w-12 h-12 rounded-full bg-purple-500/20 flex items-center justify-center">
                                <i class="fas fa-star text-purple-400"></i>
                            </div>
                            <div>
                                <h4 class="text-white font-bold">Premium Experience</h4>
                                <p class="text-xs text-gray-400">Top-tier hospitality guaranteed</p>
                            </div>
                        </div>
                    </div>
                    <div class="relative">
                        <div class="absolute inset-0 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-2xl blur-xl opacity-30 animate-pulse"></div>
                        <img src="https://images.unsplash.com/photo-1571266028243-cb40fce75737?q=80&w=800&auto=format&fit=crop" alt="Best Nightclub in Gurgaon" class="relative rounded-2xl border border-white/10 shadow-2xl object-cover h-[400px] w-full">
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-frontend::layouts>