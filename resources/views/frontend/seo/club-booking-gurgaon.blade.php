<x-frontend::layouts title="Easy Club Booking in Gurgaon | The Midnight Club Reservations" description="Avoid the hassle at the door. Get instant club booking in Gurgaon at The Midnight Club. Secure your digital pass and VIP table online." keywords="club booking gurgaon, book club online gurgaon, midnight club reservations">
    <div class="relative bg-[#0b0c10] text-gray-100 min-h-screen font-sans overflow-hidden selection:bg-pink-500 selection:text-white">

        <!-- Ambient Background -->
        <div class="fixed inset-0 pointer-events-none overflow-hidden z-0">
            <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1545128485-c400e7702796?q=80&w=1920&auto=format&fit=crop')] bg-cover bg-center opacity-15"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-[#0b0c10] via-[#0b0c10]/80 to-[#0b0c10]/95"></div>
            <div class="absolute top-1/4 right-1/4 w-[600px] h-[600px] bg-pink-600/10 rounded-full blur-[160px] animate-pulse"></div>
        </div>

        <!-- Hero Section -->
        <div class="relative z-10  pb-20 px-6 max-w-7xl mx-auto text-center">
            <h1 class="text-5xl md:text-7xl font-black text-white mb-6 tracking-tight leading-tight">
                Instant <span class="bg-gradient-to-r from-pink-500 to-purple-500 text-transparent bg-clip-text drop-shadow-[0_0_20px_rgba(236,72,153,0.3)]">Club Booking</span><br>in Gurgaon
            </h1>
            <p class="text-gray-400 max-w-2xl mx-auto text-lg md:text-xl leading-relaxed mb-10">
                Experience the convenience of modern nightlife. The Midnight Club provides instant digital passes and VIP reservations, ensuring a flawless entry.
            </p>
            <div class="flex flex-col sm:flex-row justify-center items-center gap-4">
                <a href="{{ url('/#reserve') }}" class="group relative inline-flex items-center justify-center gap-3 bg-gradient-to-r from-pink-600 to-purple-600 text-white font-bold text-lg px-8 py-4 rounded-full shadow-[0_0_30px_rgba(236,72,153,0.5)] hover:scale-105 transition-all duration-300">
                    <i class="fas fa-qrcode"></i> Get Digital Pass
                </a>
            </div>
        </div>

        <!-- Content Section -->
        <div class="relative z-10 max-w-5xl mx-auto px-6 pb-24">
            <div class="glass-card p-10 md:p-14 rounded-3xl border border-white/10 shadow-2xl backdrop-blur-xl">
                <div class="grid md:grid-cols-2 gap-12 items-center">
                    <div class="relative order-2 md:order-1">
                        <div class="absolute inset-0 bg-gradient-to-r from-pink-500 to-purple-500 rounded-2xl blur-xl opacity-30 animate-pulse"></div>
                        <img src="https://images.unsplash.com/photo-1514525253161-7a46d19cd819?q=80&w=800&auto=format&fit=crop" alt="Club Booking in Gurgaon" class="relative rounded-2xl border border-white/10 shadow-2xl object-cover h-[400px] w-full">
                    </div>
                    <div class="order-1 md:order-2">
                        <h2 class="text-3xl font-bold text-white mb-6">Your Night, Your Rules</h2>
                        <p class="text-gray-400 leading-relaxed mb-6">
                            For top-tier <strong>club booking in Gurgaon</strong>, you want reliability. The Midnight Club's booking system removes the friction of physical tickets and long waits at the door.
                        </p>
                        <p class="text-gray-400 leading-relaxed mb-8">
                            Select your date, choose between general entry or VIP sections, and receive your digital QR pass immediately. It's the smartest way to plan your party.
                        </p>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-white/5 p-4 rounded-xl border border-white/10 text-center">
                                <i class="fas fa-mobile-alt text-2xl text-pink-400 mb-2"></i>
                                <h4 class="text-white font-bold text-sm">QR Code Entry</h4>
                            </div>
                            <div class="bg-white/5 p-4 rounded-xl border border-white/10 text-center">
                                <i class="fas fa-users text-2xl text-purple-400 mb-2"></i>
                                <h4 class="text-white font-bold text-sm">Group Packages</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-frontend::layouts>