<x-admin::layouts>
    <div class="page-header flex justify-between items-center">
        <div>
            <h1 class="page-title">Client Management</h1>
            <div class="page-breadcrumb">Home / Clients</div>
        </div>
        <Button label="Create Client" icon="pi pi-plus" size="small" @click="$refs.clinet.visible = true" />
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
                <Toast />
            </div>
        </script>

    <script type="module">
        adminVueApp.component('v-clients', {
            template: '#v-clients-template',
            data() {
                return {
                    visible: false,
                    editMode: false,
                    loading: false,
                    client: {
                        id: null,
                        name: '',
                        email: '',
                        phone: '',
                        password: '',
                        is_active: true
                    }
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
                        edit: this.onEdit
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
                        is_active: !!row.is_active
                    };
                    this.visible = true;
                },

                saveClient(params) {
                    this.loading = true;
                    const url = this.editMode ?
                        `{{ route('admin.clients.index') }}/${this.client.id}` :
                        `{{ route('admin.clients.store') }}`;

                    const payload = {
                        ...params,
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