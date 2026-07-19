<x-admin::layouts>
    <div class="page-header flex justify-between items-center">
        <div>
            <h1 class="page-title">Payments & Transactions</h1>
            <div class="page-breadcrumb">Home / Payments</div>
        </div>
        <Button label="Record Payment" icon="pi pi-plus" size="small" @click="$refs.payment.visible = true" />
    </div>

    <v-payments ref="payment" :bookings='@json($bookings)'></v-payments>

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
                                <x-admin::form.control-group.label label="Booking ID" />
                                <Select
                                    v-model="payment.booking_id"
                                    :options="bookings"
                                    optionLabel="id"
                                    optionValue="id"
                                    placeholder="Select Booking"
                                    class="w-full"
                                />
                            </x-admin::form.control-group>

                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label label="Amount ($)" />
                                <x-admin::form.control-group.control
                                    type="text"
                                    name="amount"
                                    v-model="payment.amount"
                                    rules="required"
                                    placeholder="e.g. 50.00"
                                />
                            </x-admin::form.control-group>

                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label label="Payment Method" />
                                <x-admin::form.control-group.control
                                    type="text"
                                    name="payment_method"
                                    v-model="payment.payment_method"
                                    rules="required"
                                    placeholder="e.g. Card, Cash, Stripe"
                                />
                            </x-admin::form.control-group>

                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label label="Status" />
                                <Select
                                    v-model="payment.status"
                                    :options="statusOptions"
                                    optionLabel="label"
                                    optionValue="value"
                                    placeholder="Select Status"
                                    class="w-full"
                                />
                            </x-admin::form.control-group>

                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label label="Transaction Reference (Optional)" />
                                <x-admin::form.control-group.control
                                    type="text"
                                    name="transaction_reference"
                                    v-model="payment.transaction_reference"
                                    placeholder="e.g. txn_123456"
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
            adminVueApp.component('v-payments', {
                template: '#v-payments-template',
                props: ['bookings'],
                data() {
                    return {
                        visible: false,
                        editMode: false,
                        loading: false,
                        payment: {
                            id: null,
                            booking_id: null,
                            amount: 0.00,
                            payment_method: '',
                            status: 'pending',
                            transaction_reference: ''
                        },
                        statusOptions: [
                            { label: 'Pending', value: 'pending' },
                            { label: 'Completed', value: 'completed' },
                            { label: 'Failed', value: 'failed' },
                            { label: 'Refunded', value: 'refunded' }
                        ],
                        emitter: null
                    };
                },
                watch: {
                    visible(val) {
                        if (val && !this.editMode) {
                            this.payment = { id: null, booking_id: null, amount: 0.00, payment_method: '', status: 'pending', transaction_reference: '' };
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
                        let rawStatus = row.status.replace(/<[^>]*>/g, '').toLowerCase().trim();
                        this.payment = {
                            id: row.id,
                            booking_id: row.booking_id || null,
                            amount: row.amount,
                            payment_method: row.payment_method,
                            status: ['pending', 'completed', 'failed', 'refunded'].includes(rawStatus) ? rawStatus : 'pending',
                            transaction_reference: row.transaction_reference || ''
                        };
                        this.visible = true;
                    },
                    savePayment(params) {
                        this.loading = true;
                        const url = this.editMode 
                            ? `{{ route('admin.payments.index') }}/${this.payment.id}`
                            : `{{ route('admin.payments.store') }}`;

                        const payload = {
                            ...params,
                            booking_id: this.payment.booking_id,
                            status: this.payment.status,
                            transaction_reference: this.payment.transaction_reference
                        };

                        this.$axios.post(url, payload)
                            .then(response => {
                                this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });
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
