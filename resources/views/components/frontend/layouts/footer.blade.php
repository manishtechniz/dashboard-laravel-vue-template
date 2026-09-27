<!-- FOOTER -->
<footer class="py-20 px-6 max-w-7xl mx-auto border-t z-50 relative border-white/5">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-12">

        <div class="md:col-span-2">
            <div class="flex items-center gap-3 mb-4">
                <a href="{{ route('frontend.home') }}">
                    <img src="{{ Vite::asset('resources/images/logo.png') }}" style="width: 120px;" alt="logo" class="rounded-full object-cover shadow-sm">
                </a>
            </div>
            <p class="text-gray-400 text-xs sm:text-sm max-w-md mb-6 leading-relaxed">
                Reserve your spot at Gurugram’s most exclusive clubs, high-energy dance floors, and rooftop lounges in under 60 seconds
            </p>
            <div class="flex items-center gap-3 text-gray-400">
                <a href="#" class="w-10 h-10 rounded-xl bg-dark-card border border-white/10 flex items-center justify-center hover:text-neon-pink hover:border-neon-pink transition"><i class="fab fa-instagram"></i></a>
                <!-- <a href="#" class="w-10 h-10 rounded-xl bg-dark-card border border-white/10 flex items-center justify-center hover:text-neon-purple hover:border-neon-purple transition"><i class="fab fa-spotify"></i></a>
                <a href="#" class="w-10 h-10 rounded-xl bg-dark-card border border-white/10 flex items-center justify-center hover:text-neon-pink hover:border-neon-pink transition"><i class="fab fa-tiktok"></i></a>
                <a href="#" class="w-10 h-10 rounded-xl bg-dark-card border border-white/10 flex items-center justify-center hover:text-neon-purple hover:border-neon-purple transition"><i class="fab fa-soundcloud"></i></a> -->
            </div>
        </div>

        <!-- <div>
            <h4 class="font-syne text-xs font-bold uppercase tracking-wider text-white mb-4">Club Hours</h4>
            <ul class="text-xs text-gray-400 space-y-2.5">
                <li><span class="text-gray-200 font-semibold">Thursday:</span> 10:00 PM – 04:00 AM</li>
                <li><span class="text-gray-200 font-semibold">Friday:</span> 10:00 PM – 06:00 AM</li>
                <li><span class="text-gray-200 font-semibold">Saturday:</span> 10:00 PM – 07:00 AM</li>
                <li><span class="text-gray-200 font-semibold">Sunday:</span> 09:00 PM – 03:00 AM</li>
            </ul>
        </div> -->

        <div>
            <h4 class="text-xs font-bold uppercase tracking-wider text-white mb-4">Address</h4>
            <p class="text-xs text-gray-400 mb-2">3rd floor, Plaza Mall, R-02, Mehrauli-Gurgaon Rd, Indian Airlines Pilots Society, Sushant Lok Phase I, Gurugram, Haryana 122002

            </p>
            <p class="text-xs text-neon-purple font-semibold mb-3"><i class="fas fa-phone mr-1"></i> +91-9899281515</p>
        </div>
    </div>

    <!-- Footer Legal Bar -->
    <div class="pt-8 border-t border-white/5 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-gray-500">
        <span>© 2026 The Midnight Club. All rights reserved.</span>
        <div class="flex flex-wrap justify-center items-center gap-4 sm:gap-6">
            <a href="{{ route('frontend.about') }}" class="hover:text-pink-400 transition">About Us</a>
            <a href="{{ route('frontend.privacy') }}" class="hover:text-pink-400 transition">Privacy Policy</a>
            <a href="{{ route('frontend.disclaimer') }}" class="hover:text-pink-400 transition">Disclaimer</a>
            <a href="{{ route('frontend.sitemap') }}" class="hover:text-pink-400 transition">Sitemap</a>
        </div>
    </div>
</footer>