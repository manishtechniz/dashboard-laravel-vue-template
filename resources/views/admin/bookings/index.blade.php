<x-admin::layouts>
    <div class="page-header flex justify-between items-center">
        <div>
            <h1 class="page-title">Booking Management</h1>
            <div class="page-breadcrumb">Home / Bookings</div>
        </div>
        <Button label="Create Booking" icon="pi pi-plus" size="small" @click="$refs.booking.visible = true" />
    </div>

    <v-bookings ref="booking" :clients='@json($clients)' :tables='@json($tables)' :events='@json($events)'></v-bookings>

    @pushOnce('scripts')
        <script type="text/x-template" id="v-bookings-template">
            <div>
                <!-- Datagrid -->
                <x-admin::datagrid
                    :is-multi-row="true"
                    ref="bookingsGrid"
                    src="{{ route('admin.bookings.index') }}"
                />

                <!-- Create/Edit Booking Modal -->
                <Dialog v-model:visible="visible" :header="editMode ? 'Edit Booking' : 'Create Booking'" :style="{ width: '580px', maxWidth: '95vw' }" modal>
                    <x-admin::form v-slot="{ meta, errors, handleSubmit }" as="div">
                        <form @submit="handleSubmit($event, saveBooking)" class="space-y-4 pt-3">
                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label label="Client" />
                                <Select
                                    v-model="booking.client_id"
                                    :options="clients"
                                    optionLabel="name"
                                    optionValue="id"
                                    placeholder="Select Client"
                                    class="w-full"
                                />
                            </x-admin::form.control-group>

                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label label="Table (Optional)" />
                                <Select
                                    v-model="booking.table_id"
                                    :options="tables"
                                    optionLabel="name"
                                    optionValue="id"
                                    placeholder="Select Table"
                                    class="w-full"
                                />
                            </x-admin::form.control-group>

                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label label="Event (Optional)" />
                                <Select
                                    v-model="booking.event_id"
                                    :options="events"
                                    optionLabel="name"
                                    optionValue="id"
                                    placeholder="Select Event"
                                    class="w-full"
                                />
                            </x-admin::form.control-group>

                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label label="Booking Date" />
                                <x-admin::form.control-group.control
                                    type="date"
                                    name="booking_date"
                                    v-model="booking.booking_date"
                                    rules="required"
                                    placeholder="Select date"
                                />
                            </x-admin::form.control-group>

                            <div class="grid grid-cols-2 gap-4">
                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label label="Start Time" />
                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="start_time"
                                        v-model="booking.start_time"
                                        rules="required"
                                        placeholder="e.g. 20:00"
                                    />
                                </x-admin::form.control-group>

                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label label="End Time" />
                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="end_time"
                                        v-model="booking.end_time"
                                        rules="required"
                                        placeholder="e.g. 02:00"
                                    />
                                </x-admin::form.control-group>
                            </div>

                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label label="Guest Count" />
                                <x-admin::form.control-group.control
                                    type="text"
                                    name="guest_count"
                                    v-model="booking.guest_count"
                                    rules="required"
                                    placeholder="e.g. 4"
                                />
                            </x-admin::form.control-group>

                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label label="Status" />
                                <Select
                                    v-model="booking.status"
                                    :options="statusOptions"
                                    optionLabel="label"
                                    optionValue="value"
                                    placeholder="Select Status"
                                    class="w-full"
                                />
                            </x-admin::form.control-group>

                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label label="Special Requests" />
                                <x-admin::form.control-group.control
                                    type="textarea"
                                    name="special_requests"
                                    v-model="booking.special_requests"
                                    placeholder="Any special details"
                                />
                            </x-admin::form.control-group>

                            <div class="flex justify-end gap-2 pt-4 border-t border-gray-200">
                                <Button type="button" label="Cancel" severity="secondary" text size="small" @click="visible = false" />
                                <Button type="submit" label="Save" size="small" :loading="loading" />
                            </div>
                        </form>
                    </x-admin::form>
                </Dialog>
                <Toast />
            </div>
        </script>

        <script type="module">
            adminVueApp.component('v-bookings', {
                template: '#v-bookings-template',
                props: ['clients', 'tables', 'events'],
                data() {
                    return {
                        visible: false,
                        editMode: false,
                        loading: false,
                        booking: {
                            id: null,
                            client_id: null,
                            table_id: null,
                            event_id: null,
                            booking_date: '',
                            start_time: '',
                            end_time: '',
                            guest_count: 2,
                            status: 'pending',
                            special_requests: ''
                        },
                        statusOptions: [
                            { label: 'Pending', value: 'pending' },
                            { label: 'Confirmed', value: 'confirmed' },
                            { label: 'Cancelled', value: 'cancelled' },
                            { label: 'Checked In', value: 'checked_in' }
                        ],
                        emitter: null
                    };
                },
                watch: {
                    visible(val) {
                        if (val && !this.editMode) {
                            this.booking = { id: null, client_id: null, table_id: null, event_id: null, booking_date: '', start_time: '', end_time: '', guest_count: 2, status: 'pending', special_requests: '' };
                        } else if (!val) {
                            this.editMode = false;
                        }
                    }
                },
                provide() {
                    return {
                        customActions: {
                            edit: this.onEdit
                        }
                    };
                },
                mounted() {
                },
                methods: {
                    onEdit(row) {
                        this.editMode = true;
                        const parentClient = this.clients.find(c => c.name === row.client_name);
                        const parentTable = this.tables.find(t => t.name === row.table_name);
                        const parentEvent = this.events.find(e => e.name === row.event_name);
                        let rawStatus = row.status.replace(/<[^>]*>/g, '').toLowerCase().trim();
                        if (rawStatus === 'checked in') rawStatus = 'checked_in';

                        this.booking = {
                            id: row.id,
                            client_id: parentClient ? parentClient.id : null,
                            table_id: parentTable ? parentTable.id : null,
                            event_id: parentEvent ? parentEvent.id : null,
                            booking_date: row.booking_date,
                            start_time: row.start_time || '20:00',
                            end_time: row.end_time || '02:00',
                            guest_count: row.guest_count,
                            status: ['pending', 'confirmed', 'cancelled', 'checked_in'].includes(rawStatus) ? rawStatus : 'pending',
                            special_requests: row.special_requests || ''
                        };
                        this.visible = true;
                    },

                    saveBooking(params) {
                        this.loading = true;
                        const url = this.editMode 
                            ? `{{ route('admin.bookings.index') }}/${this.booking.id}`
                            : `{{ route('admin.bookings.store') }}`;

                        const payload = {
                            ...params,
                            client_id: this.booking.client_id,
                            table_id: this.booking.table_id,
                            event_id: this.booking.event_id,
                            status: this.booking.status
                        };

                        this.$axios.post(url, payload)
                            .then(response => {
                                this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });
                                this.visible = false;
                                this.loading = false;
                                this.$refs.bookingsGrid.get();
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
