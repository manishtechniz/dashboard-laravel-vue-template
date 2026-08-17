<x-admin::layouts>
    <div class="page-header flex justify-between items-center">
        <div>
            <h1 class="page-title">Complaints</h1>
            <div class="page-breadcrumb">Home / Complaints</div>
        </div>
        @if (hasPermission('admin.complaints.store'))
        <Button label="Create" outlined icon="pi pi-plus" size="small" @click="$refs.complaint.visible = true; $refs.complaint.editMode = false;" />
        @endif
    </div>

    <v-complaints ref="complaint" :clients='@json($clients)' :clubs='@json($clubs)'></v-complaints>

    @pushOnce('scripts')
    <script type="text/x-template" id="v-complaints-template">
        <div>
                <!-- Datagrid -->
                <x-admin::datagrid
                    :is-multi-row="true"
                    ref="complaintsGrid"
                    src="{{ route('admin.complaints.index') }}"
                />

                <!-- Edit Complaint Modal -->
                <Dialog v-model:visible="visible" :header="editMode ? 'Edit Complaint' : 'Create Complaint'" :style="{ width: '650px', maxWidth: '95vw' }" modal>
                    <x-admin::form v-slot="{ meta, errors, handleSubmit }" as="div">
                        <form @submit="handleSubmit($event, saveComplaint)" class="space-y-4 pt-3">
                            <div class="grid grid-cols-2 gap-4">
                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label label="Client" />
                                    <x-admin::form.control-group.control
                                        v-model="complaint.client_id"
                                        ::value="complaint.client_id"
                                        ::options="clients"
                                        optionLabel="name"
                                        optionValue="id"
                                        placeholder="Select Client"
                                        rules="required"
                                        name="client_id"
                                        type="select"
                                    />
                                </x-admin::form.control-group>
                                
                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label label="Club" />
                                    <x-admin::form.control-group.control
                                        v-model="complaint.club_id"
                                        ::value="complaint.club_id"
                                        ::options="clubs"
                                        optionLabel="name"
                                        optionValue="id"
                                        placeholder="Select Club"
                                        name="club_id"
                                        rules="required"
                                        type="select"
                                    />
                                </x-admin::form.control-group>
                            </div>

                            <div class="grid grid-cols-1 gap-4">
                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label label="Booking ID (Optional)" />
                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="booking_id"
                                        v-model="complaint.booking_id"
                                        placeholder="e.g. 1024"
                                    />
                                </x-admin::form.control-group>
                            </div>

                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label label="Message" />
                                <x-admin::form.control-group.control
                                    type="textarea"
                                    name="message"
                                    rules="required"
                                    v-model="complaint.message"
                                    placeholder="Enter complaint message..."
                                />
                            </x-admin::form.control-group>

                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label label="Admin Remark (Optional)" />
                                <x-admin::form.control-group.control
                                    type="textarea"
                                    name="remark"
                                    v-model="complaint.remark"
                                    placeholder="Enter internal remark or reply..."
                                />
                            </x-admin::form.control-group>

                            <div class="flex items-center gap-6 pt-2">
                                <div class="flex items-center gap-2">
                                    <ToggleSwitch v-model="complaint.is_active" inputId="is_active_toggle" />
                                    <x-admin::form.control-group.label label="Active Status" for="is_active_toggle" />
                                </div>
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
        adminVueApp.component('v-complaints', {
            template: '#v-complaints-template',
            props: ['clients', 'clubs'],
            data() {
                return {
                    visible: false,
                    editMode: false,
                    loading: false,
                    complaint: {
                        id: null,
                        client_id: null,
                        club_id: null,
                        booking_id: null,
                        message: '',
                        remark: '',
                        is_active: true
                    }
                };
            },
            watch: {
                visible(val) {
                    if (val && !this.editMode) {
                        this.complaint = {
                            id: null,
                            client_id: null,
                            club_id: null,
                            booking_id: null,
                            message: '',
                            remark: '',
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
            methods: {
                onEdit(row) {
                    this.editMode = true;
                    this.complaint = {
                        id: row.id,
                        client_id: row.client_id || null,
                        club_id: row.club_id || null,
                        booking_id: row.booking_id || null,
                        message: row.message || '',
                        remark: row.remark || '',
                        is_active: !!row.is_active
                    };
                    this.visible = true;
                },
                saveComplaint(params, {
                    resetForm,
                    setErrors
                }) {
                    this.loading = true;
                    const url = this.editMode ?
                        `{{ route('admin.complaints.index') }}/${this.complaint.id}` :
                        `{{ route('admin.complaints.store') }}`;

                    const payload = {
                        ...params,
                        client_id: this.complaint.client_id,
                        club_id: this.complaint.club_id,
                        booking_id: this.complaint.booking_id,
                        message: this.complaint.message,
                        remark: this.complaint.remark,
                        is_active: this.complaint.is_active ? 1 : 0
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
                            this.$refs.complaintsGrid.get();
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