<x-frontend::layouts title="Club Booking in Gurugram | VIP Table Reservations at Midnight Club" description="Looking for seamless club booking in Gurugram? Reserve your VIP table or digital pass instantly at The Midnight Club for a hassle-free party experience." keywords="club booking gurugram, reserve club table gurugram, midnight club booking, vip club booking">
    <div class="relative bg-[#0b0c10] text-gray-100 min-h-screen font-sans overflow-hidden selection:bg-pink-500 selection:text-white">

        <!-- Ambient Background -->
        <div class="fixed inset-0 pointer-events-none overflow-hidden z-0">
            <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1516450360452-9312f5e86fc7?q=80&w=1920&auto=format&fit=crop')] bg-cover bg-center opacity-15"></div>
            <div class="absolute inset-0 bg-gradient-to-b from-[#0b0c10]/90 via-[#0b0c10]/95 to-[#0b0c10]"></div>
            <div class="absolute bottom-1/4 left-1/4 w-[500px] h-[500px] bg-indigo-600/10 rounded-full blur-[150px] animate-pulse"></div>
        </div>

        <!-- Hero Section -->
        <div class="relative z-10  pb-20 px-6 max-w-7xl mx-auto text-center">
            <h1 class="text-5xl md:text-7xl font-black text-white mb-6 tracking-tight leading-tight">
                Seamless <span class="bg-gradient-to-r from-indigo-500 to-purple-500 text-transparent bg-clip-text drop-shadow-[0_0_20px_rgba(99,102,241,0.3)]">Club Booking</span><br>in Gurugram
            </h1>
            <p class="text-gray-400 max-w-2xl mx-auto text-lg md:text-xl leading-relaxed mb-10">
                Don't leave your night to chance. Guarantee your entry and secure premium seating with our streamlined digital reservation system at The Midnight Club.
            </p>
            <a href="{{ url('/#reserve') }}" class="group relative inline-flex items-center justify-center gap-3 bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-bold text-lg px-10 py-4 rounded-full shadow-[0_0_30px_rgba(99,102,241,0.5)] hover:scale-105 transition-all duration-300">
                <i class="fas fa-calendar-check"></i> Make a Reservation
            </a>
        </div>

        <!-- Content Section -->
        <div class="relative z-10 max-w-5xl mx-auto px-6 pb-24">
            <div class="glass-card p-10 md:p-14 rounded-3xl border border-white/10 shadow-2xl backdrop-blur-xl">
                <div class="grid md:grid-cols-2 gap-12 items-center">
                    <div>
                        <h2 class="text-3xl font-bold text-white mb-6">Skip the Lines. Secure the Night.</h2>
                        <p class="text-gray-400 leading-relaxed mb-6">
                            Efficient <strong>club booking in Gurugram</strong> is essential for a stress-free night out, especially on busy weekends. The Midnight Club offers a state-of-the-art digital booking platform that lets you secure your spot in seconds.
                        </p>
                        <p class="text-gray-400 leading-relaxed mb-8">
                            Whether you need a simple digital pass or a full VIP bottle service setup for a large group, our reservation system handles it all. Book online and walk right in with a dedicated host waiting for you.
                        </p>
                        <ul class="space-y-4">
                            <li class="flex items-center gap-3 text-gray-300"><i class="fas fa-bolt text-yellow-500"></i> Instant Digital Passes</li>
                            <li class="flex items-center gap-3 text-gray-300"><i class="fas fa-couch text-indigo-500"></i> VIP Table Seating</li>
                            <li class="flex items-center gap-3 text-gray-300"><i class="fas fa-glass-martini-alt text-purple-500"></i> Pre-ordered Bottle Service</li>
                        </ul>
                    </div>
                    <div class="relative">
                        <div class="absolute inset-0 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-2xl blur-xl opacity-30 animate-pulse"></div>
                        <img src="https://images.unsplash.com/photo-1559336197-ded8aaa244bc?q=80&w=800&auto=format&fit=crop" alt="Club Booking Gurugram" class="relative rounded-2xl border border-white/10 shadow-2xl object-cover h-[400px] w-full">
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-frontend::layouts>