<x-admin::layouts>
    <div class="page-header flex justify-between items-center">
        <div>
            <h1 class="page-title">Clients</h1>
            <div class="page-breadcrumb">Home / Clients</div>
        </div>


        <div class="flex gap-2">
            @if (hasPermission('admin.clients.store'))
            <Button label="Create" icon="pi pi-plus" outlined size="small" @click="$refs.clinet.visible = true" />
            @endif

            @if (hasPermission('admin.clients.ledgers'))
            <Button
                label="Ledger"
                icon="pi pi-list-check"
                size="small"
                @click="$refs.clinet.viewLedger({id: 0})"
                :pt="{ label: { class: 'hidden md:block' } }" />
            @endif
        </div>

    </div>

    <v-clients ref="clinet"></v-clients>

    @pushOnce('scripts')
    <script type="text/x-template" id="v-clients-template">
        <div>
                <!-- Datagrid -->
                <x-admin::datagrid 
                    ref="clientsGrid"
                    src="{{ route('admin.clients.index') }}"
                />

                <!-- Create/Edit Client Modal -->
                <Dialog v-model:visible="visible" :header="editMode ? 'Edit Client' : 'Create Client'" :style="{ width: '580px', maxWidth: '95vw' }" modal>
                    <x-admin::form v-slot="{ meta, errors, handleSubmit }" as="div">
                        <form @submit="handleSubmit($event, saveClient)" class="space-y-4 pt-3">
                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label label="Client Name" />
                                <x-admin::form.control-group.control
                                    type="text"
                                    name="name"
                                    v-model="client.name"
                                    rules="required"
                                    placeholder="Enter client name"
                                />
                            </x-admin::form.control-group>

                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label label="Role" />
                                <Select
                                    v-model="client.role_id"
                                    :options="roles"
                                    optionLabel="name"
                                    optionValue="id"
                                    placeholder="Select a role"
                                    class="w-full"
                                />
                            </x-admin::form.control-group>

                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label label="Email" />
                                <x-admin::form.control-group.control
                                    type="email"
                                    name="email"
                                    v-model="client.email"
                                    rules="email"
                                    placeholder="Enter email address"
                                />
                            </x-admin::form.control-group>

                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label label="Phone" />
                                <x-admin::form.control-group.control
                                    type="text"
                                    name="phone"
                                    v-model="client.phone"
                                    placeholder="Enter phone number"
                                />
                            </x-admin::form.control-group>

                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label label="Password" />
                                <x-admin::form.control-group.control
                                    type="password"
                                    name="password"
                                    v-model="client.password"
                                    placeholder="Enter password (optional)"
                                />
                            </x-admin::form.control-group>

                            <div class="flex items-center gap-2 pt-2">
                                <ToggleSwitch v-model="client.is_active" inputId="is_active_toggle" />
                                <x-admin::form.control-group.label label="Active Status" for="is_active_toggle" />
                            </div>

                            <div class="flex justify-end gap-2 pt-4 border-t border-gray-200">
                                <Button type="button" label="Cancel" severity="secondary" text size="small" @click="visible = false" />
                                <Button type="submit" label="Save" size="small" :loading="loading" />
                            </div>
                        </form>
                    </x-admin::form>
                </Dialog>

                <!-- Ledger Drawer -->
                <!-- Ledger Drawer -->
                <x-admin::drawer ref="viewLedgerDrawer" width="500px" position="right">
                    <x-slot:header>
                        <div class="flex items-center gap-2">
                            <span class="icon-user text-2xl text-(--accent)"></span>
                            <h3 class="text-lg font-semibold text-(--text-base)">Details</h3>
                        </div>
                    </x-slot:header>
                    <x-slot:content>
                        <div v-if="clientDetails" class="flex flex-col gap-6 py-4 h-full">
                            
                        <template v-if="clientDetails?.id!=null && clientDetails?.id!=0">
                            <!-- Client Details -->
                            <div class="flex flex-col gap-3 pb-4 border-b border-(--border)">
                                <div class="flex justify-between items-center mb-2">
                                    <h4 class="text-sm font-semibold text-(--accent) uppercase tracking-wider">Client Details</h4>
                                    <span v-html="clientDetails.is_active"></span>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="flex flex-col">
                                        <span class="text-xs font-medium text-(--text-muted)">Name</span>
                                        <span class="text-sm text-(--text-base) font-medium">@{{ clientDetails.name }}</span>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-xs font-medium text-(--text-muted)">Role</span>
                                        <span class="text-sm text-(--text-base)" v-html="clientDetails?.role_name || 'N/A'"></span>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-xs font-medium text-(--text-muted)">Email</span>
                                        <span class="text-sm text-(--text-base)">@{{ clientDetails.email || 'N/A' }}</span>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-xs font-medium text-(--text-muted)">Phone</span>
                                        <span class="text-sm text-(--text-base)">@{{ clientDetails.phone || 'N/A' }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Financial Summary -->
                            <div class="flex flex-col gap-3 pb-4 border-b border-(--border)">
                                <h4 class="text-sm font-semibold text-(--accent) uppercase tracking-wider">Financial Summary</h4>
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="flex flex-col p-4 rounded-md border border-(--border) bg-(--bg-subtle)">
                                        <span class="text-xs font-medium text-(--text-muted) mb-1">Total Due</span>
                                        <span class="text-xl font-bold text-(--danger)" v-html="clientDetails.total_due"></span>
                                    </div>
                                    <div class="flex flex-col p-4 rounded-md border border-(--border) bg-(--bg-subtle)">
                                        <span class="text-xs font-medium text-(--text-muted) mb-1">Total Advance</span>
                                        <span class="text-xl font-bold text-(--success)" v-html="clientDetails.total_advance"></span>
                                    </div>
                                </div>
                            </div>
                        </template>

                            <!-- Ledger History -->
                            <div class="flex flex-col gap-3 flex-1 overflow-hidden min-h-[300px]">
                                <div class="flex justify-between items-center">
                                    <h4 class="text-sm font-semibold text-(--accent) uppercase tracking-wider">Ledger History</h4>
                                    <Badge v-if="ledgerTotal > 0" :value="ledgerTotal" severity="secondary"></Badge>
                                </div>
                                
                                <div class="flex-1 overflow-y-auto pr-2 custom-scrollbar" @scroll="onLedgerScroll">
                                    <div v-if="ledgers.length === 0 && !loadingLedger" class="flex flex-col items-center justify-center p-8 text-center text-(--text-muted)">
                                        <span class="icon-folder text-4xl mb-2 opacity-50"></span>
                                        <p>No ledger records found.</p>
                                    </div>

                                    <div v-else class="flex flex-col gap-3">
                                        <div v-for="ledger in ledgers" :key="ledger.id" class="p-4 border border-(--border) rounded-md bg-(--bg-subtle)">
                                            <div class="flex justify-between items-start mb-2">
                                                <span class="text-xs font-medium text-(--text-muted)">
                                                    @{{ new Date(ledger.created_at).toLocaleString('en-IN', {day:'2-digit', month:'short', year:'numeric', hour:'2-digit', minute:'2-digit'}) }}
                                                </span>
                                                <span class="text-sm font-bold" :class="ledger.type === 'credit' ? 'text-(--success)' : 'text-(--danger)'">
                                                    @{{ ledger.type === 'credit' ? '+' : '-' }} ₹@{{ parseFloat(ledger.amount).toFixed(2) }}
                                                </span>
                                            </div>
                                            <p class="text-sm text-(--text-base) mb-3">
                                                @{{ ledger.description || 'No description provided' }}
                                            </p>
                                            <div class="flex justify-between items-center py-2 border-t border-(--border) text-xs">
                                                <span class="font-medium text-(--text-muted)">Client</span>
                                                <span class="font-bold">
                                                    @{{ ledger?.client?.name || '-' }}

                                                    <a 
                                                        v-if="ledger.client" 
                                                        class="cursor-pointer font-bold underline text-(--accent)"
                                                        :href="'{{ route('admin.clients.index') }}' + '?filters[id][0]=' + ledger.client.id"
                                                    >
                                                        #@{{ ledger.client.id }}
                                                    </a>
                                                </span>
                                            </div>
                                            <div class="flex justify-between items-center pt-2 border-t border-(--border) text-xs">
                                                <span class="font-medium text-(--text-muted)">Advance Balance</span>
                                                <span class="font-bold" :class="ledger.advance_after > 0 ? 'text-(--success)' : (ledger.advance_after < 0 ? 'text-(--danger)' : 'text-(--text-muted)')">
                                                    ₹@{{ parseFloat(ledger.advance_after).toFixed(2) }}
                                                </span>
                                            </div>
                                            <div class="flex justify-between items-center pt-2 border-t border-(--border) text-xs">
                                                <span class="font-medium text-(--text-muted)">Due Balance</span>
                                                <span class="font-bold" :class="ledger.due_after > 0 ? 'text-(--success)' : (ledger.due_after < 0 ? 'text-(--danger)' : 'text-(--text-muted)')">
                                                    ₹@{{ parseFloat(ledger.due_after).toFixed(2) }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div v-if="loadingLedger" class="flex justify-center p-4">
                                        <span class="icon-spinner text-2xl animate-spin text-(--accent)"></span>
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
        adminVueApp.component('v-clients', {
            template: '#v-clients-template',
            data() {
                return {
                    roles: @json($roles),
                    visible: false,
                    editMode: false,
                    loading: false,
                    client: {
                        id: null,
                        name: '',
                        email: '',
                        phone: '',
                        password: '',
                        role_id: null,
                        is_active: true
                    },
                    clientDetails: null,
                    ledgerVisible: false,
                    ledgers: [],
                    loadingLedger: false,
                    ledgerPage: 1,
                    ledgerTotal: 0,
                    ledgerLastPage: 1
                };
            },
            watch: {
                visible(val) {
                    if (val && !this.editMode) {
                        this.client = {
                            id: null,
                            name: '',
                            email: '',
                            phone: '',
                            password: '',
                            role_id: null,
                            is_active: true
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
                        viewLedger: this.viewLedger
                    }
                };
            },
            mounted() {},
            methods: {
                onEdit(row) {
                    this.editMode = true;
                    this.client = {
                        id: row.id,
                        name: row.name,
                        email: row.email,
                        phone: row.phone,
                        password: '',
                        role_id: row.role_id,
                        is_active: !!row.is_active
                    };
                    this.visible = true;
                },

                viewLedger(row) {
                    console.log(row);
                    this.clientDetails = row;
                    this.ledgers = [];
                    this.ledgerPage = 1;
                    this.ledgerTotal = 0;
                    this.ledgerLastPage = 1;
                    this.$refs.viewLedgerDrawer.open();
                    this.loadLedgers();
                },

                loadLedgers() {
                    if (this.loadingLedger || (this.ledgerPage > 1 && this.ledgerPage > this.ledgerLastPage)) return;

                    this.loadingLedger = true;
                    this.$axios.get(`{{ url('admin/clients') }}/${this.clientDetails.id}/ledgers?page=${this.ledgerPage}`)
                        .then(response => {
                            const data = response.data;
                            if (this.ledgerPage === 1) {
                                this.ledgers = data.data;
                            } else {
                                this.ledgers = [...this.ledgers, ...data.data];
                            }
                            this.ledgerTotal = data.total;
                            this.ledgerLastPage = data.last_page;
                            this.ledgerPage++;
                            this.loadingLedger = false;
                        })
                        .catch(error => {
                            this.loadingLedger = false;
                            this.$emitter.emit('add-flash', {
                                type: 'error',
                                message: 'Could not load ledger.'
                            });
                        });
                },

                onLedgerScroll(event) {
                    const target = event.target;
                    // Check if scrolled to bottom
                    if (target.scrollTop + target.clientHeight >= target.scrollHeight - 20) {
                        this.loadLedgers();
                    }
                },

                saveClient(params) {
                    this.loading = true;
                    const url = this.editMode ?
                        `{{ route('admin.clients.index') }}/${this.client.id}` :
                        `{{ route('admin.clients.store') }}`;

                    const payload = {
                        ...params,
                        role_id: this.client.role_id,
                        is_active: this.client.is_active ? 1 : 0
                    };

                    this.$axios.post(url, payload)
                        .then(response => {
                            this.$emitter.emit('add-flash', {
                                type: 'success',
                                message: response.data.message
                            });
                            this.visible = false;
                            this.loading = false;
                            this.$refs.clientsGrid.get();
                        })
                        .catch(error => {
                            this.loading = false;
                            if (error.response && error.response.status === 422) {
                                this.$emitter.emit('add-flash', {
                                    type: 'error',
                                    message: 'Validation failed.'
                                });
                            }
                        });
                }
            }
        });
    </script>
    @endPushOnce
</x-admin::layouts>