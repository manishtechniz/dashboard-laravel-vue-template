<x-admin::layouts>
    <div class="page-header flex justify-between items-center">
        <div>
            <h1 class="page-title">Booking Management</h1>
            <div class="page-breadcrumb">Home / Bookings</div>
        </div>
        @if (hasPermission('admin.bookings.store'))
        <Button label="Create" outlined icon="pi pi-plus" size="small" @click="$refs.booking.visible = true" />
        @endif
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

                <!-- View Booking Drawer -->
                <x-admin::drawer ref="viewBookingDrawer" width="500px" position="right">
                    <x-slot:header>
                        <div class="flex items-center gap-2">
                            <span class="icon-ticket text-2xl text-(--accent)"></span>
                            <h3 class="text-lg font-semibold text-(--text-base)">Booking Details</h3>
                        </div>
                    </x-slot:header>
                    <x-slot:content>
                        <div v-if="viewingBooking" class="flex flex-col gap-6 py-4">
                            <!-- Basic Info -->
                            <div class="flex flex-col gap-3 pb-4 border-b border-(--border)">
                                <h4 class="text-sm font-semibold text-(--accent) uppercase tracking-wider">General Information</h4>
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="flex flex-col">
                                        <span class="text-xs font-medium text-(--text-muted)">Booking ID</span>
                                        <span class="text-sm text-(--text-base) font-medium">#@{{ viewingBooking.id }}</span>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-xs font-medium text-(--text-muted)">Status</span>
                                        <div v-html="viewingBooking.status_html || N/A">
                                            <!-- <span class="text-xs font-semibold px-2 py-1 rounded-full" :class="{
                                                'bg-yellow-100 text-yellow-800': viewingBooking.status === 'pending',
                                                'bg-green-100 text-green-800': viewingBooking.status === 'confirmed',
                                                'bg-red-100 text-red-800': viewingBooking.status === 'cancelled',
                                                'bg-blue-100 text-blue-800': viewingBooking.status === 'checked_in'
                                            }">@{{ viewingBooking.status.replace('_', ' ').toUpperCase() }}</span> -->
                                        </div>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-xs font-medium text-(--text-muted)">Date</span>
                                        <span class="text-sm text-(--text-base)">@{{ viewingBooking.booking_date }}</span>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-xs font-medium text-(--text-muted)">Time</span>
                                        <span class="text-sm text-(--text-base)">@{{ viewingBooking.start_time || 'N/A' }} <template v-if="viewingBooking.end_time">- @{{ viewingBooking.end_time }}</template></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Customer Info -->
                            <div class="flex flex-col gap-3 pb-4 border-b border-(--border)">
                                <h4 class="text-sm font-semibold text-(--accent) uppercase tracking-wider">Customer Details</h4>
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="flex flex-col">
                                        <span class="text-xs font-medium text-(--text-muted)">Name</span>
                                        <span class="text-sm text-(--text-base)">@{{ viewingBooking.guest_name || viewingBooking.client_name || 'N/A' }}</span>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-xs font-medium text-(--text-muted)">Guests</span>
                                        <span class="text-sm text-(--text-base)">@{{ viewingBooking.guest_count }} People

                                            <span class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center pi pi-users" @click="onGuests({id: viewingBooking.id})"></span>
                                        </span>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-xs font-medium text-(--text-muted)">Email</span>
                                        <span class="text-sm text-(--text-base)">@{{ viewingBooking.client_email || 'N/A' }}</span>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-xs font-medium text-(--text-muted)">Phone</span>
                                        <span class="text-sm text-(--text-base)">@{{ viewingBooking.client_phone || 'N/A' }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Placement Info -->
                            <div class="flex flex-col gap-3 pb-4 border-b border-(--border)">
                                <h4 class="text-sm font-semibold text-(--accent) uppercase tracking-wider">Placement</h4>
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="flex flex-col">
                                        <span class="text-xs font-medium text-(--text-muted)">Club</span>
                                        <span class="text-sm text-(--text-base)">@{{ viewingBooking.club_name || 'N/A' }}</span>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-xs font-medium text-(--text-muted)">Table</span>
                                        <span class="text-sm text-(--text-base)">@{{ viewingBooking.table_name || 'N/A' }}</span>
                                    </div>
                                    <div class="flex flex-col col-span-2">
                                        <span class="text-xs font-medium text-(--text-muted)">Event</span>
                                        <span class="text-sm text-(--text-base)">@{{ viewingBooking.event_name || 'N/A' }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Financial Info -->
                            <div class="flex flex-col gap-3 pb-4 border-b border-(--border)">
                                <h4 class="text-sm font-semibold text-(--accent) uppercase tracking-wider">Financials</h4>
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="flex flex-col">
                                        <span class="text-xs font-medium text-(--text-muted)">Base Price</span>
                                        <span class="text-sm text-(--text-base)">₹ @{{ viewingBooking.base_price || '0.00' }}</span>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-xs font-medium text-(--text-muted)">Discount</span>
                                        <span class="text-sm text-(--text-base)">₹ @{{ viewingBooking.discount_amount || '0.00' }} <template v-if="viewingBooking.discount_type">(@{{ viewingBooking.discount_type }})</template></span>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-xs font-medium text-(--text-muted)">Tax</span>
                                        <span class="text-sm text-(--text-base)">₹ @{{ viewingBooking.tax_amount || '0.00' }}</span>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-xs font-medium text-(--text-muted)">Total Amount</span>
                                        <span class="text-sm text-(--text-base) font-bold text-green-600">₹ @{{ viewingBooking.total_amount_incl_tax || '0.00' }}</span>
                                    </div>
                                    <div class="flex flex-col col-span-2">
                                        <span class="text-xs font-medium text-(--text-muted)">Payment Status</span>
                                        <span class="text-sm text-(--text-base)">@{{ viewingBooking.payment_status || 'N/A' }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Extras -->
                            <div class="flex flex-col gap-3">
                                <h4 class="text-sm font-semibold text-(--accent) uppercase tracking-wider">Additional Information</h4>
                                <div class="flex flex-col">
                                    <span class="text-xs font-medium text-(--text-muted)">Special Requests</span>
                                    <p class="text-sm text-(--text-base) bg-(--bg-subtle) p-3 rounded-md mt-1 min-h-[60px]">
                                        @{{ viewingBooking.special_requests || 'None' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </x-slot:content>
                </x-admin::drawer>

                <!-- View Guests Drawer -->
                <x-admin::drawer ref="viewGuestsDrawer" width="500px" position="right">
                    <x-slot:header>
                        <div class="flex items-center gap-2">
                            <span class="icon-users text-2xl text-(--accent)"></span>
                            <h3 class="text-lg font-semibold text-(--text-base)">Booking Guests <span v-if="viewingBooking">#@{{ viewingBooking.id }}</span></h3>
                        </div>
                    </x-slot:header>
                    <x-slot:content>
                        <div class="flex flex-col gap-4 py-4">
                            <div v-if="loadingGuests" class="flex justify-center p-8">
                                <span class="icon-spinner text-2xl animate-spin text-(--accent)"></span>
                            </div>
                            <div v-else-if="bookingGuests.length === 0" class="flex flex-col items-center justify-center p-8 text-center text-(--text-muted)">
                                <span class="icon-user text-4xl mb-2 opacity-50"></span>
                                <p>No guests attached to this booking.</p>
                            </div>
                            <div v-else class="flex flex-col gap-3">
                                <div v-for="(guest, index) in bookingGuests" :key="guest.id" class="p-4 border border-(--border) rounded-md bg-(--bg-subtle)">
                                    <div class="flex items-center gap-3">
                                        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-(--accent-light) flex items-center justify-center text-(--accent) font-bold">
                                            @{{ guest.name.charAt(0).toUpperCase() }}
                                        </div>
                                        <div class="flex flex-col flex-1">
                                            <span class="text-sm font-bold text-(--text-base)">@{{ guest.name }}</span>
                                            <div class="flex items-center gap-2 mt-1 text-xs text-(--text-muted)">
                                                <span v-if="guest.email"><i class="pi pi-envelope mr-1"></i>@{{ guest.email }}</span>
                                                <span v-if="guest.phone"><i class="pi pi-phone mr-1"></i>@{{ guest.phone }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-2 mt-3 pt-3 border-t border-(--border) text-xs">
                                        <div class="flex flex-col">
                                            <span class="font-medium text-(--text-muted)">Age</span>
                                            <span class="text-(--text-base)">@{{ guest.age || 'N/A' }}</span>
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="font-medium text-(--text-muted)">Gender</span>
                                            <span class="text-(--text-base) capitalize">@{{ guest.gender || 'N/A' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </x-slot:content>
                </x-admin::drawer>

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
                    statusOptions: [{
                            label: 'Pending',
                            value: 'pending'
                        },
                        {
                            label: 'Confirmed',
                            value: 'confirmed'
                        },
                        {
                            label: 'Cancelled',
                            value: 'cancelled'
                        },
                        {
                            label: 'Checked In',
                            value: 'checked_in'
                        }
                    ],
                    emitter: null,
                    viewingBooking: null,
                    bookingGuests: [],
                    loadingGuests: false
                };
            },
            watch: {
                visible(val) {
                    if (val && !this.editMode) {
                        this.booking = {
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
                        };
                    } else if (!val) {
                        this.editMode = false;
                    }
                }
            },
            provide() {
                return {
                    customActions: {
                        edit: this.onEdit,
                        view: this.onView,
                        guests: this.onGuests
                    }
                };
            },
            mounted() {},
            methods: {
                onGuests(row) {
                    // this.viewingBooking = {
                    //     ...row
                    // };
                    this.bookingGuests = [];
                    this.loadingGuests = true;
                    this.$refs.viewGuestsDrawer.open();

                    this.$axios.get(`{{ route('admin.bookings.index') }}/${row.id}/guests`)
                        .then(response => {
                            this.bookingGuests = response.data?.guests ?? [];
                        })
                        .catch(error => {
                            console.error('Failed to fetch guests', error);
                        })
                        .finally(() => {
                            this.loadingGuests = false;
                        });
                },
                onView(row) {
                    this.viewingBooking = {
                        ...row
                    };

                    this.$refs.viewBookingDrawer.open();
                },
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

                saveBooking(params, {
                    resetForm,
                    setErrors
                }) {
                    this.loading = true;
                    const url = this.editMode ?
                        `{{ route('admin.bookings.index') }}/${this.booking.id}` :
                        `{{ route('admin.bookings.store') }}`;

                    const payload = {
                        ...params,
                        client_id: this.booking.client_id,
                        table_id: this.booking.table_id,
                        event_id: this.booking.event_id,
                        status: this.booking.status
                    };

                    this.$axios.post(url, payload)
                        .then(response => {
                            this.$emitter.emit('add-flash', {
                                type: 'success',
                                message: response.data.message
                            });
                            this.visible = false;
                            this.loading = false;
                            resetForm();
                            this.$refs.bookingsGrid.get();
                        })
                        .catch(error => {
                            this.loading = false;

                            if (error.response.status === 422) {
                                if (error.response.data.errors?.general) {
                                    this.$emitter.emit('add-flash', {
                                        type: 'error',
                                        message: error.response.data.errors?.general[0]
                                    });
                                }

                                setErrors(error.response.data.errors);
                            }
                        });
                }
            }
        });
    </script>
    @endPushOnce
</x-admin::layouts>