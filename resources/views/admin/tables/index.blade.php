<x-admin::layouts>
    <div class="page-header flex justify-between items-center">
        <div>
            <h1 class="page-title">Table Management</h1>
            <div class="page-breadcrumb">Home / Tables</div>
        </div>
        <Button label="Create Table" icon="pi pi-plus" size="small" @click="$refs.table.visible = true;$refs.table.fileRules.required = true" />
    </div>

    <v-tables ref="table" :clubs='@json($clubs)'></v-tables>

    @pushOnce('scripts')
    <script type="text/x-template" id="v-tables-template">
        <div>
                <!-- Datagrid -->
                <x-admin::datagrid
                    :is-multi-row="true"
                    ref="tablesGrid"
                    src="{{ route('admin.tables.index') }}"
                />

                <!-- Create/Edit Table Modal -->
                <Dialog v-model:visible="visible" :header="editMode ? 'Edit Table' : 'Create Table'" :style="{ width: '580px', maxWidth: '95vw' }" modal>
                    <x-admin::form v-slot="{ meta, errors, handleSubmit }" as="div">
                        <form @submit="handleSubmit($event, saveTable)" ref="tableForm" class="space-y-4 pt-3">
                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label label="Club" /> 
                                
                                <x-admin::form.control-group.control
                                    type="select" 
                                    v-model="table.club_id"
                                    ::value="table.club_id" 
                                    name="club_id" 
                                    rules="required"
                                    ::options="clubs"
                                    optionLabel="name"
                                    optionValue="id" 
                                />
                            </x-admin::form.control-group>

                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label label="Table Name / Number" />
                                <x-admin::form.control-group.control
                                    type="text"
                                    name="name"
                                    v-model="table.name"
                                    rules="required"
                                    placeholder="e.g. T1, T2, VIP-1"
                                />
                            </x-admin::form.control-group>

                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label label="Capacity" />
                                <x-admin::form.control-group.control
                                    type="text"
                                    name="capacity"
                                    v-model="table.capacity"
                                    rules="required"
                                    placeholder="e.g. 4, 6, 8"
                                />
                            </x-admin::form.control-group>

                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label label="Total Tables" />
                                <x-admin::form.control-group.control
                                    type="text"
                                    name="total_tables"
                                    v-model="table.total_tables"
                                    rules="required"
                                    placeholder="e.g. 0, 1, 2"
                                />
                            </x-admin::form.control-group>

                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label label="Status" />

                                <x-admin::form.control-group.control 
                                    type="select" 
                                    v-model="table.status"
                                    ::value="table.status" 
                                    name="status" 
                                    rules="required"
                                    ::options="statusOptions"
                                    optionLabel="label"
                                    optionValue="value" 
                                />
                            </x-admin::form.control-group>

                            <!-- File --> 
                            <x-admin::form.control-group>
                                <x-admin::form.control-group.control
                                    type="file"
                                    ::rules="fileRules"
                                    name="image"
                                    label="Image" 
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
        adminVueApp.component('v-tables', {
            template: '#v-tables-template',
            props: ['clubs'],
            data() {
                return {
                    visible: false,
                    editMode: false,
                    loading: false,
                    table: {
                        id: null,
                        club_id: null,
                        name: '',
                        capacity: 4,
                        total_tables: 0,
                        status: 'active',
                        image: ''
                    },
                    statusOptions: [{
                            label: 'Active',
                            value: 'active'
                        },
                        {
                            label: 'Inactive',
                            value: 'inactive'
                        }
                    ],
                    emitter: null,
                    fileRules: {
                        required: true
                    }
                };
            },
            watch: {
                visible(val) {
                    if (val && !this.editMode) {
                        this.table = {
                            id: null,
                            club_id: null,
                            name: null,
                            capacity: null,
                            total_tables: null,
                            status: null,
                            image: null
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
                    console.log(row);
                    this.fileRules.required = false;

                    this.editMode = true;
                    // Clean status tag format

                    this.table = {
                        id: row.id,
                        club_id: row.club_id,
                        name: row.table_name,
                        capacity: row.capacity,
                        total_tables: row.total_tables,
                        status: row.status
                    };

                    this.visible = true;
                },

                saveTable(params, {
                    resetForm,
                    setErrors
                }) {
                    console.log(params);

                    // return;


                    this.loading = true;
                    const url = this.editMode ?
                        `{{ route('admin.tables.update', ['id' => ':id']) }}`.replace(':id', this.table.id) :
                        `{{ route('admin.tables.store') }}`;

                    let formData = new FormData(this.$refs.tableForm);

                    formData.append('club_id', params.club_id);
                    formData.append('status', params.status);
                    formData.append('name', params.name);
                    formData.append('capacity', params.capacity);
                    formData.append('total_tables', params.total_tables);

                    if (this.editMode) {
                        formData.append('_method', 'PUT');
                    }

                    this.$axios.post(url, formData, {
                            headers: {
                                'Content-Type': 'multipart/form-data',
                            }
                        })
                        .then(response => {
                            this.$emitter.emit('add-flash', {
                                type: 'success',
                                message: response.data.message
                            });
                            this.visible = false;
                            this.loading = false;
                            resetForm();
                            this.$refs.tablesGrid.get();
                        })
                        .catch(error => {
                            this.loading = false;

                            if (error.response.status === 422) {
                                setErrors(error.response.data.errors);
                            }

                        });
                }
            }
        });
    </script>
    @endPushOnce
</x-admin::layouts>