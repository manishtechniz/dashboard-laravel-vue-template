<x-frontend::layouts title="Night Clubs in Gurugram | Party at The Midnight Club" description="Looking for night clubs in Gurugram? The Midnight Club is your premier destination for high-energy parties, late-night dancing, and VIP treatment." keywords="night clubs in gurugram, midnight club gurugram, gurugram party venues, late night clubs gurugram">
    <div class="relative bg-[#0b0c10] text-gray-100 min-h-screen font-sans overflow-hidden selection:bg-pink-500 selection:text-white">

        <!-- Ambient Background -->
        <div class="fixed inset-0 pointer-events-none overflow-hidden z-0">
            <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1566737236500-c8ac43014a67?q=80&w=1920&auto=format&fit=crop')] bg-cover bg-center opacity-10"></div>
            <div class="absolute inset-0 bg-gradient-to-b from-[#0b0c10]/80 via-[#0b0c10]/95 to-[#0b0c10]"></div>
            <div class="absolute top-0 right-1/4 w-[500px] h-[500px] bg-pink-600/10 rounded-full blur-[150px] animate-pulse"></div>
        </div>

        <!-- Hero Section -->
        <div class="relative z-10  pb-20 px-6 max-w-7xl mx-auto text-center">
            <h1 class="text-5xl md:text-7xl font-black text-white mb-6 tracking-tight leading-tight">
                Discover <span class="bg-gradient-to-r from-pink-500 to-purple-500 text-transparent bg-clip-text drop-shadow-[0_0_20px_rgba(236,72,153,0.3)]">Night Clubs</span><br>in Gurugram
            </h1>
            <p class="text-gray-400 max-w-2xl mx-auto text-lg md:text-xl leading-relaxed mb-10">
                Gurugram comes alive after dark. For those seeking the ultimate nocturnal escape, The Midnight Club offers a high-voltage atmosphere that keeps the city dancing until dawn.
            </p>
            <a href="{{ url('/#reserve') }}" class="group relative inline-flex items-center justify-center gap-3 bg-gradient-to-r from-pink-500 to-purple-600 text-white font-bold text-lg px-10 py-4 rounded-full shadow-[0_0_30px_rgba(236,72,153,0.5)] hover:scale-105 transition-all duration-300">
                <i class="fas fa-ticket-alt"></i> Buy Passes Now
            </a>
        </div>

        <!-- Content Section -->
        <div class="relative z-10 max-w-5xl mx-auto px-6 pb-24">
            <div class="glass-card p-10 md:p-14 rounded-3xl border border-white/10 shadow-2xl backdrop-blur-xl">
                <div class="grid md:grid-cols-2 gap-12 items-center">
                    <div>
                        <h2 class="text-3xl font-bold text-white mb-6">The Heartbeat of Gurugram</h2>
                        <p class="text-gray-400 leading-relaxed mb-6">
                            When it comes to <strong>night clubs in Gurugram</strong>, the scene is competitive. However, The Midnight Club distinguishes itself with a relentless commitment to entertainment and luxury.
                        </p>
                        <p class="text-gray-400 leading-relaxed mb-8">
                            Our venue features an immersive light show, booming basslines, and a spacious layout designed for both dancing and socializing. From Thursday night kick-offs to Sunday wind-downs, we are the epicenter of the city's nightlife.
                        </p>
                        <div class="flex flex-wrap gap-3">
                            <span class="bg-pink-500/10 text-pink-400 border border-pink-500/20 px-4 py-2 rounded-full text-sm font-semibold">Late Night Parties</span>
                            <span class="bg-purple-500/10 text-purple-400 border border-purple-500/20 px-4 py-2 rounded-full text-sm font-semibold">Live Events</span>
                            <span class="bg-pink-500/10 text-pink-400 border border-pink-500/20 px-4 py-2 rounded-full text-sm font-semibold">VIP Tables</span>
                        </div>
                    </div>
                    <div class="relative">
                        <div class="absolute inset-0 bg-gradient-to-r from-pink-500 to-purple-500 rounded-2xl blur-xl opacity-30 animate-pulse"></div>
                        <img src="https://images.unsplash.com/photo-1545128485-c400e7702796?q=80&w=800&auto=format&fit=crop" alt="Night Clubs in Gurugram" class="relative rounded-2xl border border-white/10 shadow-2xl object-cover h-[400px] w-full">
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-frontend::layouts>