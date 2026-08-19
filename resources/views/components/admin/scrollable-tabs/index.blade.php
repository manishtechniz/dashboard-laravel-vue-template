<div {{ $attributes->merge(['class' => '']) }}>
    <v-scrollable-tabs>
        {{ $slot }}
    </v-scrollable-tabs>
</div>

@pushOnce('scripts')
<script
    type="text/x-template"
    id="v-scrollable-tabs-template">
    <div class="relative flex w-full items-center group">
            <!-- Left Scroll Arrow -->
            <transition 
                enter-active-class="transition-opacity duration-300" 
                enter-from-class="opacity-0" 
                leave-active-class="transition-opacity duration-300" 
                leave-to-class="opacity-0"
            >
                <div 
                    v-show="showLeft" 
                    class="absolute left-0 z-10 flex h-full items-center bg-gradient-to-r from-(--bg-subtle) via-(--bg-subtle) to-transparent pl-1 pr-4"
                >
                    <button
                        type="button"
                        class="flex h-7 w-7 items-center justify-center rounded-full bg-(--bg-surface) shadow-sm border border-(--border) text-(--text-muted) hover:text-(--text-base) transition-all"
                        @click="scroll(-150)"
                    >
                        <i class="pi pi-chevron-left text-[10px]"></i>
                    </button>
                </div>
            </transition>

            <!-- Scrollable Container -->
            <div 
                ref="scrollContainer"
                @scroll="checkScroll"
                class="flex w-full flex-nowrap items-center overflow-x-auto bg-(--bg-subtle) p-1 rounded-xl border border-(--border) [&::-webkit-scrollbar]:hidden [-ms-overflow-style:none] [scrollbar-width:none]"
            >
                <slot></slot>
            </div>

            <!-- Right Scroll Arrow -->
            <transition 
                enter-active-class="transition-opacity duration-300" 
                enter-from-class="opacity-0" 
                leave-active-class="transition-opacity duration-300" 
                leave-to-class="opacity-0"
            >
                <div 
                    v-show="showRight" 
                    class="absolute right-0 z-10 flex h-full items-center bg-gradient-to-l from-(--bg-subtle) via-(--bg-subtle) to-transparent pr-1 pl-4"
                >
                    <button
                        type="button"
                        class="flex h-7 w-7 items-center justify-center rounded-full bg-(--bg-surface) shadow-sm border border-(--border) text-(--text-muted) hover:text-(--text-base) transition-all"
                        @click="scroll(150)"
                    >
                        <i class="pi pi-chevron-right text-[10px]"></i>
                    </button>
                </div>
            </transition>
        </div>
    </script>

<script type="module">
    adminVueApp.component('v-scrollable-tabs', {
        template: '#v-scrollable-tabs-template',

        data() {
            return {
                showLeft: false,
                showRight: false,
                observer: null,
            };
        },

        mounted() {
            // Check on initial load (timeout ensures DOM and slots are fully rendered)
            this.$nextTick(() => {
                setTimeout(this.checkScroll, 150);
            });

            // ResizeObserver detects if the inner buttons change size/length dynamically
            this.observer = new ResizeObserver(() => this.checkScroll());

            if (this.$refs.scrollContainer) {
                this.observer.observe(this.$refs.scrollContainer);
            }
        },

        beforeUnmount() {
            if (this.observer && this.$refs.scrollContainer) {
                this.observer.unobserve(this.$refs.scrollContainer);
            }
        },

        methods: {
            checkScroll() {
                const el = this.$refs.scrollContainer;
                if (!el) return;

                // Show left arrow if scrolled away from start
                this.showLeft = el.scrollLeft > 0;

                // Show right arrow if content width is greater than visible width
                // Added 2px buffer to handle browser rounding decimals
                this.showRight = Math.ceil(el.scrollLeft + el.clientWidth) < (el.scrollWidth - 2);
            },

            scroll(amount) {
                if (this.$refs.scrollContainer) {
                    this.$refs.scrollContainer.scrollBy({
                        left: amount,
                        behavior: 'smooth'
                    });
                }
            },
        },
    });
</script>
@endPushOnce