<x-admin::layouts>
    <div class="page-header flex justify-between items-center">
        <div>
            <h1 class="page-title">Payments</h1>
            <div class="page-breadcrumb">Home / Payments</div>
        </div>

        <div class="flex gap-2">
            <Button label="Create" icon="pi pi-plus" size="small" @click="$refs.payment.visible = true" />

            <Button
                label="Transactions"
                icon="pi pi-list-check"
                size="small"
                @click="$refs.payment.onTransactions({}, 'all')"
                :pt="{ label: { class: 'hidden md:block' } }" />
        </div>
    </div>

    <v-payments ref="payment" :clients='@json($clients)'></v-payments>

    @pushOnce('scripts')
    <script type="text/x-template" id="v-payments-template">
        <div>
                <!-- Datagrid -->
                <x-admin::datagrid
                    :is-multi-row="true"
                    ref="paymentsGrid"
                    src="{{ route('admin.payments.index') }}"
                />

                <!-- Record Payment Modal -->
                <Dialog v-model:visible="visible" :header="editMode ? 'Edit Payment Status' : 'Record Payment'" :style="{ width: '580px', maxWidth: '95vw' }" modal>
                    <x-admin::form v-slot="{ meta, errors, handleSubmit }" as="div">
                        <form @submit="handleSubmit($event, savePayment)" class="space-y-4 pt-3">
                            <x-admin::form.control-group v-if="!editMode">
                                <x-admin::form.control-group.label label="Payment Type" />
                                <x-admin::form.control-group.control
                                    type="select"
                                    v-model="payment.payment_type"
                                    ::value="payment.payment_type"
                                    ::options="paymentTypes"
                                    name="payment_type"
                                    optionLabel="label"
                                    optionValue="value"
                                    placeholder="Select Type"
                                    class="w-full"
                                />
                            </x-admin::form.control-group>

                            <x-admin::form.control-group v-if="!editMode">
                                <x-admin::form.control-group.label label="Client" />
                                <x-admin::form.control-group.control
                                    type="select"
                                    v-model="payment.client_id"
                                    ::value="payment.client_id"
                                    ::options="clients"
                                    optionLabel="name"
                                    optionValue="id"
                                    name="client_id"
                                    placeholder="Select Client"
                                    @change="fetchBookingViaClient()"
                                    class="w-full"      
                                />
                            </x-admin::form.control-group>

                            <x-admin::form.control-group v-if="!editMode && payment.payment_type === 'due_clearance'">
                                <x-admin::form.control-group.label label="Booking ID" />
                                <x-admin::form.control-group.control
                                    type="custom"
                                    v-model="payment.booking_id"
                                    name="booking_id"
                                    rules="required"
                                >
                                    <Select
                                        v-model="payment.booking_id"
                                        :options="bookings"
                                        optionLabel="id"
                                        optionValue="id"
                                        placeholder="Select Booking"
                                        class="w-full text-(--text-muted)"
                                        filter
                                        :loading="loadingBookings"
                                        @filter="onBookingFilter"
                                        :virtualScrollerOptions="{ lazy: true, onLazyLoad: onBookingLazyLoad, itemSize: 64, delay: 250 }"
                                    >
                                        <template #value="slotProps">
                                            <div v-if="slotProps.value" class="flex items-center">
                                                <div class="font-medium text-(--p-select-color)">Booking #@{{ slotProps.value }}</div> 
                                            </div>
                                            <span v-else>
                                                @{{ slotProps.placeholder }}
                                            </span>
                                        </template>
                                        <template #option="slotProps">
                                            <div class="flex flex-col w-full py-2 border-b border-gray-100 last:border-0">
                                                <div class="flex justify-between items-center mb-1">
                                                    <div class="font-semibold text-gray-800 flex items-center gap-2">
                                                        <span class="bg-blue-100 text-blue-700 text-xs px-2 py-0.5 rounded-full">#@{{ slotProps.option.id }}</span>
                                                        <span class="text-sm text-(--p-select-color)">@{{ slotProps.option.client_name || 'No Name' }}</span>
                                                    </div>
                                                    <div class="text-xs font-bold text-gray-700 bg-gray-100 px-2 py-0.5 rounded-md">
                                                        ₹@{{ slotProps.option.total_amount_incl_tax }}
                                                    </div>
                                                </div>
                                                <div class="flex items-center gap-3 text-xs mt-1">
                                                    <div class="flex items-center gap-1.5 text-green-700 bg-green-50 px-2 py-1 rounded">
                                                        <i class="pi pi-check-circle text-[10px]"></i>
                                                        <span class="font-medium">Paid: ₹@{{ slotProps.option.paid_amount }}</span>
                                                    </div>
                                                    <div class="flex items-center gap-1.5 text-red-700 bg-red-50 px-2 py-1 rounded">
                                                        <i class="pi pi-clock text-[10px]"></i>
                                                        <span class="font-medium">Due: ₹@{{ slotProps.option.due_amount }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </template>
                                    </Select>
                                </x-admin::form.control-group.control>
                            </x-admin::form.control-group> 

                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label label="Amount (₹)" />
                                <x-admin::form.control-group.control
                                    type="text"
                                    name="amount"
                                    v-model="payment.amount"
                                    rules="required"
                                    placeholder="e.g. 50.00"
                                />
                            </x-admin::form.control-group>

                            <x-admin::form.control-group v-if="!editMode">
                                <x-admin::form.control-group.label label="Payment Method" />
                                <x-admin::form.control-group.control
                                    type="select"
                                    name="payment_method"
                                    v-model="payment.payment_method"
                                    ::value="payment.payment_method"
                                    ::options="filteredPaymentMethods"
                                    optionLabel="label"
                                    optionValue="value"
                                    rules="required"
                                    placeholder="e.g. Wallet, Cash"
                                />
                            </x-admin::form.control-group>

                            <!-- <x-admin::form.control-group>
                                <x-admin::form.control-group.label label="Status" />
                                <x-admin::form.control-group.control
                                    type="select"
                                    v-model="payment.status"
                                    ::options="{{ json_encode($paymentStatusTypes) }}"
                                    name="status"
                                    optionLabel="label"
                                    optionValue="value"
                                    placeholder="Select Status"
                                    class="w-full"
                                />
                            </x-admin::form.control-group> -->

                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label label="Transaction Reference (Optional)" />
                                <x-admin::form.control-group.control
                                    type="text"
                                    name="transaction_reference"
                                    v-model="payment.transaction_reference"
                                    placeholder="e.g. txn_123456"
                                />
                            </x-admin::form.control-group>

                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label label="Notes (Optional)" />
                                <x-admin::form.control-group.control
                                    type="text"
                                    name="notes"
                                    v-model="payment.notes"
                                    placeholder="Any additional notes"
                                />
                            </x-admin::form.control-group>

                            <div class="flex justify-end gap-2 pt-4 border-t border-gray-200">
                                <Button type="button" label="Cancel" severity="secondary" text size="small" @click="visible = false" />
                                <Button type="submit" label="Save" size="small" :loading="loading" />
                            </div>
                        </form>
                    </x-admin::form>
                </Dialog>
                <!-- View Transactions Drawer -->
                <x-admin::drawer ref="viewTransactionsDrawer" width="500px" position="right">
                    <x-slot:header>
                        <div class="flex flex-col gap-2 w-full pr-4">
                            <div class="flex items-center gap-2">
                                <span class="icon-list text-2xl text-(--accent)"></span>
                                <h3 class="text-lg font-semibold text-(--text-base)">Transaction History <span v-if="viewingPayment && transactionFilter === 'individual'">#@{{ viewingPayment.id }}</span></h3>
                            </div> 
                        </div>
                    </x-slot:header>
                    <x-slot:content>
                        <div class="flex flex-col gap-4 py-4 h-full overflow-y-auto" @scroll="onTransactionsScroll">
                            <div v-if="transactions.length === 0 && !loadingTransactions" class="flex flex-col items-center justify-center p-8 text-center text-(--text-muted)">
                                <span class="icon-list text-4xl mb-2 opacity-50"></span>
                                <p>No transactions found.</p>
                            </div>
                            <div v-else class="flex flex-col gap-3">
                                <div v-for="txn in transactions" :key="txn.id" class="p-4 border border-(--border) rounded-md bg-(--bg-subtle)">
                                    <div class="flex justify-between items-start mb-2">
                                        <div class="flex flex-col gap-2">
                                            <div class="flex items-center gap-2">
                                                <span class="text-sm font-bold text-(--text-base)">@{{ txn.payment && txn.payment.payment_type ? txn.payment.payment_type.replace('_', ' ').toUpperCase() : (txn.type ? txn.type.toUpperCase() : 'PAYMENT') }}</span> 
                                                <span v-if= "txn.status" class="badge badge-info">@{{ txn.status }}</span>
                                            </div>
                                            <span class="text-xs text-(--text-muted)">@{{ new Date(txn.created_at).toLocaleString() }}</span>
                                        </div>
                                        <span class="text-sm font-bold" :class="{'text-green-600': txn.status === 'success', 'text-red-600': txn.status === 'failed', 'text-yellow-600': txn.status === 'pending'}">₹ @{{ txn.amount }}</span>
                                    </div>
                                    <div class="grid grid-cols-2 gap-2 mt-3 pt-3 border-t border-(--border) text-xs">
                                        <div class="flex flex-col">
                                            <span class="font-medium text-(--text-muted)">Payment Id</span>
                                            <span class="text-(--text-base) capitalize">
                                                <a 
                                                    v-if="txn.payment_id" 
                                                    class="cursor-pointer font-bold underline text-(--accent)"
                                                    :href="'{{ route('admin.payments.index') }}' + '?filters[id][0]=' + txn.payment_id"
                                                >
                                                    #@{{ txn.payment_id }}
                                                </a>
                                                <span v-else>
                                                    N/A
                                                </span>
                                            </span>
                                        </div>

                                        <div class="flex flex-col">
                                            <span class="font-medium text-(--text-muted)">Method</span>
                                            <span class="text-(--text-base) capitalize">@{{ txn.payment_method || 'N/A' }}</span>
                                        </div> 

                                        <div class="flex flex-col">
                                            <span class="font-medium text-(--text-muted)">Client</span>
                                            <span class="text-(--text-base) capitalize" v-if="txn.payment && txn.payment.client">@{{ txn.payment.client.name }} (#@{{ txn.payment.client_id }})</span>
                                            <span class="text-(--text-base) capitalize" v-else>N/A</span>
                                        </div>

                                        <div class="flex flex-col">
                                            <span class="font-medium text-(--text-muted)">Recorded By</span>
                                            <span class="text-(--text-base) capitalize">@{{ txn.admin ? txn.admin.name : 'N/A' }}</span>
                                        </div>

                                        <div class="flex flex-col col-span-2" v-if="txn.reference">
                                            <span class="font-medium text-(--text-muted)">Reference</span>
                                            <span class="text-(--text-base)">@{{ txn.reference }}</span>
                                        </div>
                                        <div class="flex flex-col col-span-2" v-if="txn.notes">
                                            <span class="font-medium text-(--text-muted)">Notes</span>
                                            <span class="text-(--text-base)">@{{ txn.notes }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div v-if="loadingTransactions" class="flex justify-center p-4">
                                <span class="icon-spinner text-2xl animate-spin text-(--accent)"></span>
                            </div>
                        </div>
                    </x-slot:content>
                </x-admin::drawer>
                <Toast />
            </div>
        </script>

    <script type="module">
        adminVueApp.component('v-payments', {
            template: '#v-payments-template',
            props: ['clients'],
            data() {
                return {
                    visible: false,
                    editMode: false,
                    loading: false,
                    payment: {
                        id: null,
                        payment_type: 'booking',
                        booking_id: null,
                        client_id: null,
                        amount: 0.00,
                        payment_method: '',
                        status: 'pending',
                        transaction_reference: '',
                        notes: ''
                    },
                    paymentTypes: [{
                            label: 'Due Clearance',
                            value: 'due_clearance'
                        },
                        {
                            label: 'Advance Payment',
                            value: 'advance'
                        },
                        {
                            label: 'Withdrawal Advance',
                            value: 'withdrawal_advance'
                        }
                    ],
                    paymentMethods: @json($paymentMethodTypes),
                    allowedValues: [],

                    statusOptions: [{
                            label: 'Pending',
                            value: 'pending'
                        },
                        {
                            label: 'Completed',
                            value: 'completed'
                        },
                        {
                            label: 'Failed',
                            value: 'failed'
                        },
                        {
                            label: 'Refunded',
                            value: 'refunded'
                        }
                    ],
                    emitter: null,
                    viewingPayment: null,
                    transactions: [],
                    transactionsPage: 1,
                    bookings: [],
                    bookingPage: 1,
                    loadingBookings: false,
                    hasMoreBookings: true,
                    bookingSearchQuery: '',
                    loadingTransactions: false,
                    hasMoreTransactions: true,
                    transactionFilter: 'all'
                };
            },
            watch: {
                visible(val) {
                    if (val && !this.editMode) {
                        this.payment = {
                            id: null,
                            payment_type: 'due_clearance',
                            booking_id: null,
                            client_id: null,
                            amount: 0.00,
                            payment_method: '',
                            status: 'pending',
                            transaction_reference: '',
                            notes: ''
                        };
                    } else if (!val) {
                        this.editMode = false;
                    }
                }
            },
            computed: {
                filteredPaymentMethods() {
                    let paymentType = this.payment.payment_type;
                    let allowedValues = []; // empty means "allow all"

                    if (paymentType === 'advance') {
                        allowedValues = [
                            "{{ \App\Enums\PaymentMethod::CASH->value }}",
                            "{{ \App\Enums\PaymentMethod::ONLINE->value }}"
                        ];
                    } else if (paymentType === 'withdrawal_advance') {
                        allowedValues = [
                            "{{ \App\Enums\PaymentMethod::WALLET->value }}"
                        ];
                    }

                    if (allowedValues.length === 0) {
                        return this.paymentMethods;
                    }

                    return this.paymentMethods.filter(type =>
                        allowedValues.includes(type.value)
                    );
                }
            },
            provide() {
                return {
                    customActions: {
                        edit: this.onEdit,
                        transactions: this.onTransactions
                    }
                };
            },
            mounted() {},
            methods: {
                onTransactions(row, transactionFilter = null) {
                    this.viewingPayment = row;
                    this.transactionFilter = !!transactionFilter ? 'all' : 'individual';
                    this.transactions = [];
                    this.transactionsPage = 1;
                    this.hasMoreTransactions = true;
                    this.$refs.viewTransactionsDrawer.open();
                    this.fetchTransactions();
                },
                onFilterChange() {
                    this.transactions = [];
                    this.transactionsPage = 1;
                    this.hasMoreTransactions = true;
                    this.fetchTransactions();
                },

                onBookingFilter(event) {
                    this.bookingSearchQuery = event.value;
                    this.bookingPage = 1;
                    this.hasMoreBookings = true;
                    this.fetchBookingViaClient();
                },
                onBookingLazyLoad(event) {
                    console.log(1);
                    if (!this.hasMoreBookings) {
                        this.loadingBookings = false;
                        return;
                    }

                    console.log(2);
                    const last = event.last;
                    const count = this.bookings.length;

                    if (last >= count - 2) {
                        console.log(3);
                        this.bookingPage++;
                        this.fetchBookingViaClient(true);
                    }
                },
                fetchBookingViaClient(append = false) {
                    console.log(4);
                    if (!append) {
                        this.bookingPage = 1;
                        this.hasMoreBookings = true;
                    }

                    console.log(5);

                    this.$nextTick(() => {
                        if (!this.payment.client_id) {
                            return;
                        }

                        this.loadingBookings = true;


                        this.$axios.get(`{{ route('admin.bookings.view.by_client', ['id' => ':id']) }}`.replace(':id', this.payment.client_id), {
                                params: {
                                    page: this.bookingPage,
                                    search: this.bookingSearchQuery
                                }
                            })
                            .then(response => {
                                const data = response.data;

                                if (append) {
                                    this.bookings = [...this.bookings, ...(data?.bookings ?? [])];
                                } else {
                                    this.bookings = data?.bookings ?? [];
                                }

                                if (data.current_page >= data.last_page || !data.bookings || data.bookings.length === 0) {
                                    this.hasMoreBookings = false;
                                }
                            })
                            .catch(error => {
                                console.error('Failed to fetch bookings', error);
                            })
                            .finally(() => {
                                this.loadingBookings = false;
                            });
                    });
                },
                fetchTransactions(transactionFilter = null) {

                    if (this.loadingTransactions || !this.hasMoreTransactions) return;

                    if (!!transactionFilter) {
                        this.transactionFilter = null;
                    }

                    this.loadingTransactions = true;
                    let paymentId = this.transactionFilter === 'individual' && this.viewingPayment ? this.viewingPayment.id : 'all';

                    this.$axios.get(`{{ route('admin.payments.transactions') }}`, {
                            params: {
                                payment_id: paymentId,
                                page: this.transactionsPage
                            }
                        })
                        .then(response => {
                            const newTransactions = response.data.data;
                            this.transactions = [...this.transactions, ...newTransactions];

                            if (response.data.current_page >= response.data.last_page) {
                                this.hasMoreTransactions = false;
                            } else {
                                this.transactionsPage++;
                            }
                        })
                        .catch(error => {
                            console.error('Failed to fetch transactions', error);
                        })
                        .finally(() => {
                            this.loadingTransactions = false;
                        });
                },
                onTransactionsScroll(event) {
                    const target = event.target;
                    if (target.scrollTop + target.clientHeight >= target.scrollHeight - 20) {
                        this.fetchTransactions();
                    }
                },
                onEdit(row) {
                    this.editMode = true;
                    let rawStatus = row.status.replace(/<[^>]*>/g, '').toLowerCase().trim();
                    this.payment = {
                        id: row.id,
                        payment_type: row.payment_type || 'booking',
                        booking_id: row.booking_id || null,
                        client_id: row.client_id || null,
                        amount: row.amount,
                        payment_method: row.payment_method,
                        status: ['pending', 'completed', 'failed', 'refunded'].includes(rawStatus) ? rawStatus : 'pending',
                        transaction_reference: row.transaction_reference || '',
                        notes: row.notes || ''
                    };
                    this.visible = true;
                },
                savePayment(params) {
                    this.loading = true;
                    const url = this.editMode ?
                        `{{ route('admin.payments.index') }}/${this.payment.id}` :
                        `{{ route('admin.payments.store') }}`;

                    const payload = {
                        ...params,
                        payment_type: this.payment.payment_type,
                        booking_id: this.payment.booking_id,
                        client_id: this.payment.client_id,
                        status: this.payment.status,
                        transaction_reference: this.payment.transaction_reference,
                        notes: this.payment.notes
                    };

                    this.$axios.post(url, payload)
                        .then(response => {
                            this.$emitter.emit('add-flash', {
                                type: 'success',
                                message: response.data.message
                            });
                            this.visible = false;
                            this.loading = false;
                            this.$refs.paymentsGrid.get();
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