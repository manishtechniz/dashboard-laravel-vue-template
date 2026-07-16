<!-- SHIMMER EFFECT -->

<!-- PAGE SHIMMER -->

<div class="space-y-6">

    <!-- Header -->
    <div class="flex items-center justify-between">

        <div>
            <div class="shimmer h-7 w-56 rounded mb-2"></div>
            <div class="shimmer h-3 w-32 rounded"></div>
        </div>

        <div class="flex gap-3">
            <div class="shimmer h-10 w-24 rounded-lg"></div>
            <div class="shimmer h-10 w-36 rounded-lg"></div>
        </div>

    </div>

    <!-- Main -->
    <div class="grid grid-cols-12 gap-4">

        <!-- LEFT -->
        <div class="col-span-12 xl:col-span-8 space-y-4">

            <!-- Personal Details -->
            <div class="border rounded-2xl p-5">

                <div class="shimmer h-5 w-40 rounded mb-6"></div>

                <div class="grid grid-cols-2 gap-4">

                    <div v-for="n in 8">

                        <div class="shimmer h-3 w-20 rounded mb-2"></div>

                        <div class="shimmer h-11 rounded-lg"></div>

                    </div>

                </div>

                <!-- Gender -->
                <div class="mt-6">

                    <div class="shimmer h-3 w-16 rounded mb-3"></div>

                    <div class="flex gap-6">

                        <div class="flex items-center gap-2">
                            <div class="shimmer h-4 w-4 rounded-full"></div>
                            <div class="shimmer h-3 w-12 rounded"></div>
                        </div>

                        <div class="flex items-center gap-2">
                            <div class="shimmer h-4 w-4 rounded-full"></div>
                            <div class="shimmer h-3 w-16 rounded"></div>
                        </div>

                    </div>

                </div>

                <!-- Address -->
                <div class="mt-6">

                    <div class="shimmer h-3 w-20 rounded mb-2"></div>

                    <div class="shimmer h-28 rounded-xl"></div>

                </div>

                <!-- Upload -->
                <div class="mt-6">

                    <div class="shimmer h-3 w-16 rounded mb-2"></div>

                    <div class="shimmer h-10 w-96 rounded-lg"></div>

                </div>

            </div>

            <!-- Permissions -->
            <div class="border rounded-2xl p-5">

                <div class="shimmer h-5 w-36 rounded mb-6"></div>

                <div class="space-y-5">

                    <div>
                        <div class="shimmer h-3 w-44 rounded mb-2"></div>
                        <div class="shimmer h-11 rounded-lg"></div>
                    </div>

                    <div>
                        <div class="shimmer h-3 w-36 rounded mb-2"></div>
                        <div class="shimmer h-11 rounded-lg"></div>
                    </div>

                </div>

                <!-- Permission Cards -->
                <div class="grid grid-cols-2 gap-4 mt-8">

                    <div
                        v-for="n in 4"
                        class="border rounded-xl p-4"
                    >

                        <div class="flex items-start gap-3">

                            <div class="shimmer h-5 w-5 rounded"></div>

                            <div class="flex-1">

                                <div class="shimmer h-4 w-32 rounded mb-2"></div>

                                <div class="shimmer h-3 w-24 rounded"></div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <!-- RIGHT -->
        <div class="col-span-12 xl:col-span-4 space-y-4">

            <!-- Status -->
            <div class="border rounded-2xl p-5">

                <div class="shimmer h-5 w-32 rounded mb-6"></div>

                <div class="shimmer h-11 rounded-lg mb-6"></div>

                <div class="border rounded-xl p-4">

                    <div class="flex items-center justify-between">

                        <div>
                            <div class="shimmer h-4 w-16 rounded mb-2"></div>
                            <div class="shimmer h-3 w-40 rounded"></div>
                        </div>

                        <div class="shimmer h-6 w-14 rounded-full"></div>

                    </div>

                </div>

            </div>

            <!-- Other Details -->
            <div class="border rounded-2xl p-5">

                <div class="shimmer h-5 w-32 rounded mb-6"></div>

                <div
                    v-for="n in 5"
                    class="mb-4"
                >

                    <div class="shimmer h-3 w-20 rounded mb-2"></div>

                    <div class="shimmer h-11 rounded-lg"></div>

                </div>

            </div>

        </div>

    </div>

</div>
