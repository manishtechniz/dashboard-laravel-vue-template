<x-admin::layouts>
    <div class="page-header flex justify-between items-center">
        <div>
            <h1 class="page-title">Feature Requests</h1>
            <div class="page-breadcrumb">Home / Feature Requests</div>
        </div>
        @if (hasPermission('admin.feature_requests.store'))
        <Button label="Create Request" icon="pi pi-plus" size="small" @click="$refs.featureRequest.visible = true; $refs.featureRequest.editMode = false;" />
        @endif
    </div>

    <v-feature-requests ref="featureRequest" :clients='@json($clients)'></v-feature-requests>

    @pushOnce('scripts')
    <script type="text/x-template" id="v-feature-requests-template">
        <div>
                <!-- Datagrid -->
                <x-admin::datagrid
                    :is-multi-row="true"
                    ref="featureRequestsGrid"
                    src="{{ route('admin.feature_requests.index') }}"
                />

                <!-- Edit Feature Request Modal -->
                <Dialog v-model:visible="visible" :header="editMode ? 'Edit Feature Request' : 'Create Feature Request'" :style="{ width: '650px', maxWidth: '95vw' }" modal>
                    <x-admin::form v-slot="{ meta, errors, handleSubmit }" as="div">
                        <form @submit="handleSubmit($event, saveFeatureRequest)" class="space-y-4 pt-3">
                            <div class="grid grid-cols-1 gap-4">
                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label label="Client (Optional)" />
                                    <x-admin::form.control-group.control
                                        v-model="requestData.client_id"
                                        ::value="requestData.client_id"
                                        ::options="clients"
                                        optionLabel="name"
                                        optionValue="id"
                                        placeholder="Select Client"
                                        name="client_id"
                                        type="select"
                                    />
                                </x-admin::form.control-group>
                            </div>

                            <div class="grid grid-cols-1 gap-4">
                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label label="Title" />
                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="title"
                                        rules="required"
                                        v-model="requestData.title"
                                        placeholder="Enter feature request title..."
                                    />
                                </x-admin::form.control-group>
                            </div>

                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label label="Description" />
                                <x-admin::form.control-group.control
                                    type="textarea"
                                    name="description"
                                    rules="required"
                                    v-model="requestData.description"
                                    placeholder="Enter full description..."
                                />
                            </x-admin::form.control-group>

                            <div class="grid grid-cols-2 gap-4">
                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label label="Status" />
                                    <x-admin::form.control-group.control
                                        v-model="requestData.status"
                                        ::value="requestData.status"
                                        ::options="[{id: 'pending', name: 'Pending'}, {id: 'reviewing', name: 'Reviewing'}, {id: 'planned', name: 'Planned'}, {id: 'in_progress', name: 'In Progress'}, {id: 'completed', name: 'Completed'}, {id: 'rejected', name: 'Rejected'}]"
                                        optionLabel="name"
                                        optionValue="id"
                                        placeholder="Select Status"
                                        rules="required"
                                        name="status"
                                        type="select"
                                    />
                                </x-admin::form.control-group>

                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label label="Priority" />
                                    <x-admin::form.control-group.control
                                        v-model="requestData.priority"
                                        ::value="requestData.priority"
                                        ::options="[{id: 'low', name: 'Low'}, {id: 'medium', name: 'Medium'}, {id: 'high', name: 'High'}]"
                                        optionLabel="name"
                                        optionValue="id"
                                        placeholder="Select Priority"
                                        rules="required"
                                        name="priority"
                                        type="select"
                                    />
                                </x-admin::form.control-group>
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
        adminVueApp.component('v-feature-requests', {
            template: '#v-feature-requests-template',
            props: ['clients'],
            data() {
                return {
                    visible: false,
                    editMode: false,
                    loading: false,
                    requestData: {
                        id: null,
                        client_id: null,
                        title: '',
                        description: '',
                        status: 'pending',
                        priority: 'medium'
                    }
                };
            },
            watch: {
                visible(val) {
                    if (val && !this.editMode) {
                        this.requestData = {
                            id: null,
                            client_id: null,
                            title: '',
                            description: '',
                            status: 'pending',
                            priority: 'medium'
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
            methods: {
                onEdit(row) {
                    this.editMode = true;
                    this.requestData = {
                        id: row.id,
                        client_id: row.client_id || null,
                        title: row.title || '',
                        description: row.description || '',
                        status: row.status || 'pending',
                        priority: row.priority || 'medium'
                    };
                    this.visible = true;
                },
                saveFeatureRequest(params, {
                    resetForm,
                    setErrors
                }) {
                    this.loading = true;
                    const url = this.editMode ?
                        `{{ route('admin.feature_requests.index') }}/${this.requestData.id}` :
                        `{{ route('admin.feature_requests.store') }}`;

                    const payload = {
                        ...params,
                        client_id: this.requestData.client_id,
                        title: this.requestData.title,
                        description: this.requestData.description,
                        status: this.requestData.status,
                        priority: this.requestData.priority
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
                            this.$refs.featureRequestsGrid.get();
                        })
                        .catch(error => {
                            this.loading = false;

                            if (error.response && error.response.status === 422) {
                                setErrors(error.response.data.errors);
                            }
                        });
                }
            }
        });
    </script>
    @endPushOnce
</x-admin::layouts>
