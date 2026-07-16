<x-admin::layouts :has-header="false" title="Login">

    <v-login></v-login>

    @pushOnce('scripts')

    <!-- Vue Template -->
    <script type="text/x-template" id="v-login-template">

        <div class="min-h-screen flex items-center justify-center px-4">

            <div class="w-full max-w-sm">

                <!-- Header -->
                <div class="text-center mb-8">
                    <h1 class="text-2xl font-bold text-white">
                        {{ config('app.name', 'AdminPanel') }}
                    </h1>
                    <p class="text-sm text-gray-400">Sign in to your account</p>
                </div>

                <!-- Card -->
                <div class="login-card p-8">



                </div>
            </div>
        </div>
    </script>

    <!-- Vue Component -->
    <script type="module">
        adminVueApp.component('v-login', {
            template: '#v-login-template',

            data() {
                return {
                    showPass: false,
                    isLoading: false,
                };
            },

            methods: {
                login(params, { setErrors }) {
                    this.isLoading = true;

                    this.$axios.post("{{ config('api.login.verify') }}", params)
                        .then((response) => {
                            // window.location.href = '{{ resolveApi(":api") }}'.replace(':api', response.data.redirectTo);
                            window.location.href = resolveApi(response?.data?.redirectTo ?? 'admin/dashboard');
                        })
                        .catch((error) => {
                            this.isLoading = false;

                            if (error.response?.status === 422) {
                                setErrors(error.response.data.errors);
                            }
                        });
                }
            }
        });
    </script>

    @endPushOnce

</x-admin::layouts>
