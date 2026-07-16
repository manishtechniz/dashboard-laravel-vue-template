<x-admin::layouts>
    <div class="page-header">
        <h1 class="page-title">Global Settings</h1>
        <div class="page-breadcrumb">Home / Settings</div>
    </div>

    <v-settings :initial-settings='@json($settings)'></v-settings>

    @pushOnce('scripts')
        <script type="text/x-template" id="v-settings-template">
            <div class="card p-6 shadow-sm max-w-3xl">
                <div class="flex items-center gap-2 mb-6 border-b border-gray-200 pb-3">
                    <i class="pi pi-cog text-indigo-500 text-lg"></i>
                    <h2 class="text-base font-bold text-gray-900 dark:text-white">Club Configuration Variables</h2>
                </div>

                <x-admin::form v-slot="{ meta, errors, handleSubmit }" as="div">
                    <form @submit="handleSubmit($event, saveSettings)" class="space-y-4">
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label label="Club Booking System Name" />
                            <x-admin::form.control-group.control
                                type="text"
                                name="system_name"
                                v-model="settings.system_name"
                                placeholder="e.g. Imperial Booking"
                            />
                        </x-admin::form.control-group>

                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label label="Contact Email" />
                            <x-admin::form.control-group.control
                                type="text"
                                name="contact_email"
                                v-model="settings.contact_email"
                                placeholder="e.g. info@imperial.com"
                            />
                        </x-admin::form.control-group>

                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label label="Default Table Reservation Fee ($)" />
                            <x-admin::form.control-group.control
                                type="text"
                                name="reservation_fee"
                                v-model="settings.reservation_fee"
                                placeholder="e.g. 50.00"
                            />
                        </x-admin::form.control-group>

                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label label="Grace Period for Booking (Minutes)" />
                            <x-admin::form.control-group.control
                                type="text"
                                name="booking_grace_period"
                                v-model="settings.booking_grace_period"
                                placeholder="e.g. 15"
                            />
                        </x-admin::form.control-group>

                        <div class="flex items-center gap-2 pt-2">
                            <ToggleSwitch v-model="settings.allow_guest_checkout" inputId="allow_guest_checkout" />
                            <x-admin::form.control-group.label label="Allow Anonymous Guest Bookings" for="allow_guest_checkout" />
                        </div>

                        <div class="pt-6 border-t border-gray-200 flex justify-end">
                            <Button type="submit" label="Save Configurations" :loading="loading" />
                        </div>
                    </form>
                </x-admin::form>
                <Toast />
            </div>
        </script>

        <script type="module">
            adminVueApp.component('v-settings', {
                template: '#v-settings-template',
                props: ['initialSettings'],
                data() {
                    return {
                        loading: false,
                        settings: {
                            system_name: this.initialSettings.system_name || '',
                            contact_email: this.initialSettings.contact_email || '',
                            reservation_fee: this.initialSettings.reservation_fee || '',
                            booking_grace_period: this.initialSettings.booking_grace_period || '',
                            allow_guest_checkout: this.initialSettings.allow_guest_checkout === 'true' || this.initialSettings.allow_guest_checkout === true
                        }
                    };
                },
                methods: {
                    saveSettings(params) {
                        this.loading = true;
                        const payload = {
                            ...params,
                            allow_guest_checkout: this.settings.allow_guest_checkout ? 'true' : 'false'
                        };

                        this.$axios.post("{{ route('admin.settings.store') }}", payload)
                            .then(response => {
                                this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });
                                this.loading = false;
                            })
                            .catch(error => {
                                this.loading = false;
                            });
                    }
                }
            });
        </script>
    @endPushOnce
</x-admin::layouts>
