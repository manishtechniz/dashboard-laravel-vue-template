<x-admin::layouts>
    <div class="page-header">
        <h1 class="page-title">Notification Management</h1>
        <div class="page-breadcrumb">Home / Notifications</div>
    </div>

    <v-notifications
        :clients='@json($clients)'
        :notifications='@json($notifications)'
        :device-tokens='@json($deviceTokens)'
    ></v-notifications>

    @pushOnce('scripts')
        <script type="text/x-template" id="v-notifications-template">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Send Notification Panel -->
                <div class="card p-6 shadow-sm h-fit">
                    <div class="flex items-center gap-2 mb-6 border-b border-gray-200 pb-3">
                        <i class="pi pi-send text-indigo-500 text-lg"></i>
                        <h2 class="text-base font-bold text-gray-900 dark:text-white">Compose Message</h2>
                    </div>

                    <x-admin::form v-slot="{ meta, errors, handleSubmit }" as="div">
                        <form @submit="handleSubmit($event, sendNotification)" class="space-y-4">
                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label label="Target Client" />
                                <Select
                                    v-model="notification.client_id"
                                    :options="clientsOptions"
                                    optionLabel="name"
                                    optionValue="id"
                                    placeholder="All Clients"
                                    class="w-full"
                                />
                            </x-admin::form.control-group>

                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label label="Title" />
                                <x-admin::form.control-group.control
                                    type="text"
                                    name="title"
                                    v-model="notification.title"
                                    rules="required"
                                    placeholder="Enter notification title"
                                />
                            </x-admin::form.control-group>

                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label label="Body" />
                                <x-admin::form.control-group.control
                                    type="textarea"
                                    name="body"
                                    v-model="notification.body"
                                    rules="required"
                                    placeholder="Enter message body"
                                />
                            </x-admin::form.control-group>

                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label label="Type" />
                                <Select
                                    v-model="notification.type"
                                    :options="typeOptions"
                                    optionLabel="label"
                                    optionValue="value"
                                    placeholder="Select Type"
                                    class="w-full"
                                />
                            </x-admin::form.control-group>

                            <div class="pt-4">
                                <Button type="submit" label="Send Broadcast" class="w-full" :loading="loading" />
                            </div>
                        </form>
                    </x-admin::form>
                </div>

                <!-- History and Device Tokens List -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- History -->
                    <div class="card p-6 shadow-sm">
                        <div class="flex items-center gap-2 mb-6 border-b border-gray-200 pb-3">
                            <i class="pi pi-list text-indigo-500 text-lg"></i>
                            <h2 class="text-base font-bold text-gray-900 dark:text-white">Sent Notifications</h2>
                        </div>
                        <div class="space-y-4 max-h-[300px] overflow-y-auto pr-1">
                            <div v-for="notif in notifications" :key="notif.id" class="p-3 bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-100 dark:border-gray-700 flex justify-between items-start gap-4">
                                <div class="flex-1">
                                    <div class="flex gap-2 items-center">
                                        <h4 class="font-bold text-sm text-gray-900 dark:text-white">@{{ notif.title }}</h4>
                                        <Tag :value="notif.type" severity="info" class="text-[10px] py-0 px-2" />
                                    </div>
                                    <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">@{{ notif.body }}</p>
                                    <span class="text-[10px] text-gray-400 mt-2 block">To: @{{ notif.client ? notif.client.name : 'All Clients' }}</span>
                                </div>
                                <span class="text-[10px] text-gray-400">@{{ formatTime(notif.created_at) }}</span>
                            </div>
                            <div v-if="notifications.length === 0" class="text-center py-6 text-gray-400">
                                No notifications sent yet.
                            </div>
                        </div>
                    </div>

                    <!-- Device Tokens -->
                    <div class="card p-6 shadow-sm">
                        <div class="flex items-center gap-2 mb-6 border-b border-gray-200 pb-3">
                            <i class="pi pi-mobile text-indigo-500 text-lg"></i>
                            <h2 class="text-base font-bold text-gray-900 dark:text-white">Device Tokens (FCM/APNS)</h2>
                        </div>
                        <div class="space-y-4 max-h-[250px] overflow-y-auto pr-1">
                            <div v-for="device in deviceTokens" :key="device.id" class="p-3 bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-100 dark:border-gray-700 flex justify-between items-center">
                                <div>
                                    <h4 class="font-bold text-xs text-gray-900 dark:text-white">@{{ device.client ? device.client.name : 'Unknown' }}</h4>
                                    <code class="text-[9px] text-gray-500 dark:text-gray-400 block mt-1">@{{ truncateToken(device.token) }}</code>
                                </div>
                                <Tag :value="device.device_type" severity="warning" style="font-size:10px;" />
                            </div>
                            <div v-if="deviceTokens.length === 0" class="text-center py-6 text-gray-400">
                                No registered client devices found.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </script>

        <script type="module">
            adminVueApp.component('v-notifications', {
                template: '#v-notifications-template',
                props: ['clients', 'notifications', 'deviceTokens'],
                data() {
                    return {
                        loading: false,
                        notification: {
                            client_id: null,
                            title: '',
                            body: '',
                            type: 'promo'
                        },
                        typeOptions: [
                            { label: 'Promo Code Alerts', value: 'promo' },
                            { label: 'Booking Updates', value: 'booking_status' },
                            { label: 'Event Alerts', value: 'event_alert' }
                        ],
                        clientsOptions: [{ id: null, name: 'All Clients' }, ...this.clients]
                    };
                },
                methods: {
                    truncateToken(token) {
                        return token.substring(0, 30) + '...';
                    },
                    formatTime(timeStr) {
                        const date = new Date(timeStr);
                        return date.toLocaleString();
                    },
                    sendNotification(params) {
                        this.loading = true;
                        const payload = {
                            ...params,
                            client_id: this.notification.client_id,
                            type: this.notification.type
                        };

                        this.$axios.post("{{ route('admin.notifications.store') }}", payload)
                            .then(response => {
                                this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });
                                this.loading = false;
                                this.notification = { client_id: null, title: '', body: '', type: 'promo' };
                                setTimeout(() => window.location.reload(), 1000);
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
