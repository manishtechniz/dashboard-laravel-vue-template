<x-frontend::layouts title="About Us | The Midnight Club Gurgaon" description="Discover the history, premium services, and exclusive VIP experience at The Midnight Club. The gold standard for nightlife in Gurgaon." keywords="about midnight club, midnight club history, premium club services, gurgaon club security, club cloak room">
    @pushOnce('schema_scripts') 
    <script type="application/ld+json">
        {!! json_encode([
                '@context' => 'https://schema.org',
                '@type' => 'AboutPage',
                'name' => 'About The Midnight Club Gurgaon',
                'url' => url()->current(),
                'description' => "Step into The Midnight Club, Gurgaon's most exclusive and electrifying nightlife destination.",
                'mainEntity' => [
                    '@type' => 'NightClub',
                    'name' => 'The Midnight Club',
                    'image' => logo(),
                    '@id' => url('/'),
                    'url' => url('/'),
                    'telephone' => '+91-9899281515',
                    'address' => [
                        '@type' => 'PostalAddress',
                        'streetAddress' => '3rd floor, Plaza Mall, R-02, Mehrauli-Gurgaon Rd, Indian Airlines Pilots Society, Sushant Lok Phase I, Gurugram, Haryana 122002',
                        'addressLocality' => 'Gurugram',
                        'addressRegion' => 'HR',
                        'postalCode' => '122002',
                        'addressCountry' => 'IN',
                    ],
                ],
            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
    </script>
    @endPushOnce

    <div class="relative bg-[#0b0c10] text-gray-100 min-h-screen  px-6 font-sans overflow-hidden selection:bg-pink-500 selection:text-white">

        <!-- Background Ambient Glows & Lasers -->
        <div class="fixed inset-0 pointer-events-none overflow-hidden z-0">
            <!-- Club Party Background Image (Bearer/Bottle Service Look) -->
            <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1517457373958-b7bdd4587205?q=80&w=1920&auto=format&fit=crop')] bg-cover bg-center bg-no-repeat opacity-15"></div>
            <!-- Gradient Overlay -->
            <div class="absolute inset-0 bg-gradient-to-b from-[#0b0c10]/80 via-[#0b0c10]/90 to-[#0b0c10]"></div>

            <div class="absolute -top-32 left-1/4 w-[650px] h-[650px] bg-purple-600/15 rounded-full blur-[160px] animate-pulse"></div>
            <div class="absolute top-1/3 -right-24 w-[600px] h-[600px] bg-pink-500/15 rounded-full blur-[170px] animate-pulse" style="animation-delay: 1.5s;"></div>
            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-4/5 h-[420px] bg-gradient-to-b from-purple-500/20 via-pink-500/10 to-transparent blur-3xl opacity-40 animate-laser origin-top"></div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto">
            <div class="text-center mb-16">
                <div class="inline-block bg-purple-900/30 border border-purple-500/30 text-neon-purple px-4 py-1.5 rounded-full text-xs font-bold tracking-widest uppercase mb-4">
                    About Us
                </div>
                <h1 class="text-4xl md:text-6xl font-bold text-white mb-6">
                    <span class="bg-gradient-to-r from-pink-500 via-purple-500 to-indigo-500 text-transparent bg-clip-text drop-shadow-[0_0_15px_rgba(236,72,153,0.3)]">The Midnight Club</span> Gurgaon
                </h1>
                <p class="text-gray-400 max-w-3xl mx-auto text-lg leading-relaxed mb-6">
                    Step into <strong class="text-white">The Midnight Club</strong>, Gurgaon's most exclusive and electrifying nightlife destination. Designed for the city's elite, our venue redefines the after-hours experience, keeping the energy pulsating until 6 AM. From the moment you walk through our doors, you are transported into a world of sensory perfection—where world-class acoustics meet state-of-the-art visual light shows, creating an atmosphere that is nothing short of legendary.
                </p>
                <p class="text-gray-400 max-w-3xl mx-auto text-lg leading-relaxed mb-6">
                    Whether you are commanding the sprawling, high-energy dance floor or seeking the intimate luxury of our premium VIP lounges, we cater to your every desire. Our curated musical journeys—steered by renowned resident and international guest DJs—blend the very best of EDM, Techno, Commercial, and Bollywood beats, ensuring that the rhythm never drops and the vibe is always immaculate.
                </p>
                <p class="text-gray-400 max-w-3xl mx-auto text-lg leading-relaxed mb-8">
                    Our signature nights on Thursdays, Fridays, and Saturdays are the undisputed heartbeat of Gurgaon’s party scene. We pride ourselves on delivering an uncompromising standard of hospitality, pairing an exquisite menu of gourmet bites with top-shelf, artisanal beverages crafted by our master mixologists. If you are searching for the ultimate, unapologetic clubbing experience—where every night becomes a story to tell—you have finally found your sanctuary.
                </p>
                <div class="mt-8 flex justify-center">
                    <a href="{{ url('/#reserve') }}" class="group relative inline-flex items-center justify-center gap-3 bg-gradient-to-r from-pink-500 to-purple-600 text-white font-bold text-lg px-10 py-5 rounded-full shadow-[0_0_30px_rgba(236,72,153,0.5)] hover:shadow-[0_0_50px_rgba(236,72,153,0.7)] hover:scale-105 transition-all duration-300 overflow-hidden">
                        <span class="absolute inset-0 w-full h-full -mt-1 rounded-lg opacity-30 bg-gradient-to-b from-transparent via-transparent to-black"></span>
                        <span class="relative flex items-center gap-2"><i class="fas fa-ticket-alt"></i> Book Table Now</span>
                        <div class="absolute inset-0 h-full w-full bg-white opacity-0 group-hover:opacity-20 transition-opacity duration-300"></div>
                    </a>
                </div>
            </div>

            <!-- Features -->
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-20 text-center">
                <div class="glass-card group rounded-2xl p-5 hover:scale-105 transition-transform duration-300">
                    <div class="w-12 h-12 mx-auto bg-pink-500/10 rounded-xl flex items-center justify-center mb-4 group-hover:bg-pink-500/20 transition-colors shadow-[0_0_15px_rgba(236,72,153,0.2)]">
                        <i class="fas fa-history text-xl text-pink-400 animate-float"></i>
                    </div>
                    <h3 class="text-xs font-bold text-white uppercase tracking-wider">History Since 1993</h3>
                </div>
                <div class="glass-card group rounded-2xl p-5 hover:scale-105 transition-transform duration-300">
                    <div class="w-12 h-12 mx-auto bg-purple-500/10 rounded-xl flex items-center justify-center mb-4 group-hover:bg-purple-500/20 transition-colors shadow-[0_0_15px_rgba(168,85,247,0.2)]">
                        <i class="fas fa-shield-alt text-xl text-purple-400 animate-float" style="animation-delay: 0.2s"></i>
                    </div>
                    <h3 class="text-xs font-bold text-white uppercase tracking-wider">25 Bodyguards</h3>
                </div>
                <div class="glass-card group rounded-2xl p-5 hover:scale-105 transition-transform duration-300">
                    <div class="w-12 h-12 mx-auto bg-pink-500/10 rounded-xl flex items-center justify-center mb-4 group-hover:bg-pink-500/20 transition-colors shadow-[0_0_15px_rgba(236,72,153,0.2)]">
                        <i class="fas fa-video text-xl text-pink-400 animate-float" style="animation-delay: 0.4s"></i>
                    </div>
                    <h3 class="text-xs font-bold text-white uppercase tracking-wider">Best CCTV</h3>
                </div>
                <div class="glass-card group rounded-2xl p-5 hover:scale-105 transition-transform duration-300">
                    <div class="w-12 h-12 mx-auto bg-purple-500/10 rounded-xl flex items-center justify-center mb-4 group-hover:bg-purple-500/20 transition-colors shadow-[0_0_15px_rgba(168,85,247,0.2)]">
                        <i class="fas fa-dog text-xl text-purple-400 animate-float" style="animation-delay: 0.6s"></i>
                    </div>
                    <h3 class="text-xs font-bold text-white uppercase tracking-wider">Dog Security</h3>
                </div>
                <div class="glass-card group rounded-2xl p-5 hover:scale-105 transition-transform duration-300">
                    <div class="w-12 h-12 mx-auto bg-pink-500/10 rounded-xl flex items-center justify-center mb-4 group-hover:bg-pink-500/20 transition-colors shadow-[0_0_15px_rgba(236,72,153,0.2)]">
                        <i class="fas fa-glass-martini-alt text-xl text-pink-400 animate-float" style="animation-delay: 0.8s"></i>
                    </div>
                    <h3 class="text-xs font-bold text-white uppercase tracking-wider">Celebrations</h3>
                </div>
                <div class="glass-card group rounded-2xl p-5 hover:scale-105 transition-transform duration-300">
                    <div class="w-12 h-12 mx-auto bg-purple-500/10 rounded-xl flex items-center justify-center mb-4 group-hover:bg-purple-500/20 transition-colors shadow-[0_0_15px_rgba(168,85,247,0.2)]">
                        <i class="fas fa-music text-xl text-purple-400 animate-float" style="animation-delay: 1s"></i>
                    </div>
                    <h3 class="text-xs font-bold text-white uppercase tracking-wider">DJ & Floors</h3>
                </div>
            </div>

            <!-- What We Offer -->
            <div class="mb-20">
                <div class="text-center mb-12 relative">
                    <h2 class="text-3xl font-bold text-white mb-4"><span class="bg-gradient-to-r from-purple-400 to-pink-500 text-transparent bg-clip-text">What We Offer</span></h2>
                    <p class="text-gray-400 text-sm max-w-2xl mx-auto">Discover a world of unparalleled entertainment, premium services, and unforgettable experiences tailored exclusively for our guests.</p>
                </div>

                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 relative z-10">

                    <div class="glass-card group rounded-3xl overflow-hidden relative min-h-[320px] flex flex-col justify-end cursor-pointer shadow-[0_0_20px_rgba(0,0,0,0.5)]">
                        <img src="https://images.unsplash.com/photo-1514525253161-7a46d19cd819?q=80&w=800&auto=format&fit=crop" class="absolute inset-0 w-full h-full object-cover opacity-50 group-hover:opacity-80 group-hover:scale-110 transition-all duration-700" alt="Drinks">
                        <div class="absolute inset-0 bg-gradient-to-t from-[#0b0c10] via-[#0b0c10]/70 to-transparent"></div>
                        <div class="relative z-10 p-8 transform group-hover:-translate-y-2 transition-transform duration-500">
                            <h4 class="text-2xl font-bold text-pink-400 mb-2 flex items-center gap-2 drop-shadow-md"><i class="fas fa-glass-cheers"></i> Drinks All Night</h4>
                            <p class="text-sm text-gray-300 leading-relaxed opacity-80 group-hover:opacity-100 transition-opacity">Sip on expertly crafted cocktails and premium spirits from our award-winning mixologists. Our expansive bar ensures your glass is never empty as you party until dawn.</p>
                        </div>
                    </div>

                    <div class="glass-card group rounded-3xl overflow-hidden relative min-h-[320px] flex flex-col justify-end cursor-pointer shadow-[0_0_20px_rgba(0,0,0,0.5)]">
                        <img src="https://images.unsplash.com/photo-1517457373958-b7bdd4587205?q=80&w=800&auto=format&fit=crop" class="absolute inset-0 w-full h-full object-cover opacity-50 group-hover:opacity-80 group-hover:scale-110 transition-all duration-700" alt="Bottle Service">
                        <div class="absolute inset-0 bg-gradient-to-t from-[#0b0c10] via-[#0b0c10]/70 to-transparent"></div>
                        <div class="relative z-10 p-8 transform group-hover:-translate-y-2 transition-transform duration-500">
                            <h4 class="text-2xl font-bold text-purple-400 mb-2 flex items-center gap-2 drop-shadow-md"><i class="fas fa-wine-bottle"></i> Bottle Service</h4>
                            <p class="text-sm text-gray-300 leading-relaxed opacity-80 group-hover:opacity-100 transition-opacity">Elevate your night with exclusive VIP bottle service. Enjoy private table seating, personal hosts, and a curated selection of the world's finest champagnes and liquors.</p>
                        </div>
                    </div>

                    <div class="glass-card group rounded-3xl overflow-hidden relative min-h-[320px] flex flex-col justify-end cursor-pointer shadow-[0_0_20px_rgba(0,0,0,0.5)]">
                        <img src="https://images.unsplash.com/photo-1545128485-c400e7702796?q=80&w=800&auto=format&fit=crop" class="absolute inset-0 w-full h-full object-cover opacity-50 group-hover:opacity-80 group-hover:scale-110 transition-all duration-700" alt="Top DJs">
                        <div class="absolute inset-0 bg-gradient-to-t from-[#0b0c10] via-[#0b0c10]/70 to-transparent"></div>
                        <div class="relative z-10 p-8 transform group-hover:-translate-y-2 transition-transform duration-500">
                            <h4 class="text-2xl font-bold text-pink-400 mb-2 flex items-center gap-2 drop-shadow-md"><i class="fas fa-headphones"></i> Top DJ's Night</h4>
                            <p class="text-sm text-gray-300 leading-relaxed opacity-80 group-hover:opacity-100 transition-opacity">Experience the pulse of Gurgaon with sets from renowned international and local DJs. Our state-of-the-art sound system delivers an immersive journey across all genres.</p>
                        </div>
                    </div>

                    <div class="glass-card group rounded-3xl overflow-hidden relative min-h-[320px] flex flex-col justify-end cursor-pointer shadow-[0_0_20px_rgba(0,0,0,0.5)]">
                        <img src="https://images.unsplash.com/photo-1516450360452-9312f5e86fc7?q=80&w=800&auto=format&fit=crop" class="absolute inset-0 w-full h-full object-cover opacity-50 group-hover:opacity-80 group-hover:scale-110 transition-all duration-700" alt="Dances">
                        <div class="absolute inset-0 bg-gradient-to-t from-[#0b0c10] via-[#0b0c10]/70 to-transparent"></div>
                        <div class="relative z-10 p-8 transform group-hover:-translate-y-2 transition-transform duration-500">
                            <h4 class="text-2xl font-bold text-purple-400 mb-2 flex items-center gap-2 drop-shadow-md"><i class="fas fa-fire"></i> Sexy Girls Dances</h4>
                            <p class="text-sm text-gray-300 leading-relaxed opacity-80 group-hover:opacity-100 transition-opacity">Immerse yourself in our high-energy atmosphere featuring professional dance performers and breathtaking stage shows that keep the energy peaking all night long.</p>
                        </div>
                    </div>

                    <div class="glass-card group rounded-3xl overflow-hidden relative min-h-[320px] flex flex-col justify-end cursor-pointer shadow-[0_0_20px_rgba(0,0,0,0.5)]">
                        <img src="https://images.unsplash.com/photo-1470225620780-dba8ba36b745?q=80&w=800&auto=format&fit=crop" class="absolute inset-0 w-full h-full object-cover opacity-50 group-hover:opacity-80 group-hover:scale-110 transition-all duration-700" alt="Song Requests">
                        <div class="absolute inset-0 bg-gradient-to-t from-[#0b0c10] via-[#0b0c10]/70 to-transparent"></div>
                        <div class="relative z-10 p-8 transform group-hover:-translate-y-2 transition-transform duration-500">
                            <h4 class="text-2xl font-bold text-pink-400 mb-2 flex items-center gap-2 drop-shadow-md"><i class="fas fa-music"></i> Song Request's</h4>
                            <p class="text-sm text-gray-300 leading-relaxed opacity-80 group-hover:opacity-100 transition-opacity">Take control of the vibe. Our interactive DJ setups allow for personalized song requests, ensuring the soundtrack of the night perfectly matches your party's energy.</p>
                        </div>
                    </div>

                    <div class="glass-card group rounded-3xl overflow-hidden relative min-h-[320px] flex flex-col justify-end cursor-pointer shadow-[0_0_20px_rgba(0,0,0,0.5)]">
                        <img src="https://images.unsplash.com/photo-1559336197-ded8aaa244bc?q=80&w=800&auto=format&fit=crop" class="absolute inset-0 w-full h-full object-cover opacity-50 group-hover:opacity-80 group-hover:scale-110 transition-all duration-700" alt="Clock Room">
                        <div class="absolute inset-0 bg-gradient-to-t from-[#0b0c10] via-[#0b0c10]/70 to-transparent"></div>
                        <div class="relative z-10 p-8 transform group-hover:-translate-y-2 transition-transform duration-500">
                            <h4 class="text-2xl font-bold text-purple-400 mb-2 flex items-center gap-2 drop-shadow-md"><i class="fas fa-suitcase"></i> Cloak Room</h4>
                            <p class="text-sm text-gray-300 leading-relaxed opacity-80 group-hover:opacity-100 transition-opacity">Party with complete peace of mind. Our secure, fully staffed cloakroom ensures your personal belongings and jackets are safely stored while you dominate the dance floor.</p>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Location Map -->
            <div class="relative z-10">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-white mb-4">Our Location</h2>
                    <p class="text-gray-400 text-sm max-w-2xl mx-auto">3rd floor, Plaza Mall, R-02, Mehrauli-Gurgaon Rd, Indian Airlines Pilots Society, Sushant Lok Phase I, Gurugram, Haryana 122002</p>
                </div>
                <div class="glass-card p-2 rounded-3xl overflow-hidden shadow-[0_0_40px_rgba(236,72,153,0.15)]">
                    <div class="rounded-2xl overflow-hidden h-[450px]">
                        <iframe src="https://maps.google.com/maps?q=28.4779126,77.0731839&hl=en&z=15&output=embed" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-frontend::layouts>