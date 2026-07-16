<x-admin::layouts>
    <div class="page-header flex justify-between items-center">
        <div>
            <h1 class="page-title">Table Management</h1>
            <div class="page-breadcrumb">Home / Tables</div>
        </div>
        <Button label="Create Table" icon="pi pi-plus" size="small" @click="emitter.emit('open-table-modal')" />
    </div>

    <v-tables :floors='@json($floors)'></v-tables>

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
                <Dialog v-model:visible="visible" :header="editMode ? 'Edit Table' : 'Create Table'" :style="{ width: '450px' }" modal>
                    <x-admin::form v-slot="{ meta, errors, handleSubmit }" as="div">
                        <form @submit="handleSubmit($event, saveTable)" class="space-y-4 pt-3">
                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label label="Floor" />
                                <Select
                                    v-model="table.floor_id"
                                    :options="floors"
                                    optionLabel="name"
                                    optionValue="id"
                                    placeholder="Select Floor"
                                    class="w-full"
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
                                <x-admin::form.control-group.error name="name" />
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
                                <x-admin::form.control-group.error name="capacity" />
                            </x-admin::form.control-group>

                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label label="Status" />
                                <Select
                                    v-model="table.status"
                                    :options="statusOptions"
                                    optionLabel="label"
                                    optionValue="value"
                                    placeholder="Select Status"
                                    class="w-full"
                                />
                            </x-admin::form.control-group>

                            <div class="grid grid-cols-2 gap-4">
                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label label="Position X" />
                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="x_position"
                                        v-model="table.x_position"
                                        placeholder="X coordinate"
                                    />
                                </x-admin::form.control-group>

                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label label="Position Y" />
                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="y_position"
                                        v-model="table.y_position"
                                        placeholder="Y coordinate"
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
            adminVueApp.component('v-tables', {
                template: '#v-tables-template',
                props: ['floors'],
                data() {
                    return {
                        visible: false,
                        editMode: false,
                        loading: false,
                        table: {
                            id: null,
                            floor_id: null,
                            name: '',
                            capacity: 4,
                            status: 'available',
                            x_position: null,
                            y_position: null
                        },
                        statusOptions: [
                            { label: 'Available', value: 'available' },
                            { label: 'Reserved', value: 'reserved' },
                            { label: 'Occupied', value: 'occupied' },
                            { label: 'Maintenance', value: 'maintenance' }
                        ],
                        emitter: null
                    };
                },
                provide() {
                    return {
                        customActions: {
                            edit: this.onEdit,
                            delete: this.onDelete
                        }
                    };
                },
                mounted() {
                    this.emitter = this.$emitter;
                    this.emitter.on('open-table-modal', () => {
                        this.editMode = false;
                        this.table = { id: null, floor_id: null, name: '', capacity: 4, status: 'available', x_position: null, y_position: null };
                        this.visible = true;
                    });
                },
                methods: {
                    onEdit(row) {
                        this.editMode = true;
                        const parentFloor = this.floors.find(f => f.name === row.floor_name);
                        // Clean status tag format
                        let rawStatus = row.status.replace(/<[^>]*>/g, '').toLowerCase().trim();
                        this.table = {
                            id: row.id,
                            floor_id: parentFloor ? parentFloor.id : null,
                            name: row.table_name,
                            capacity: row.capacity,
                            status: ['available', 'reserved', 'occupied', 'maintenance'].includes(rawStatus) ? rawStatus : 'available',
                            x_position: row.x_position || null,
                            y_position: row.y_position || null
                        };
                        this.visible = true;
                    },
                    onDelete(row) {
                        if (confirm('Are you sure you want to delete this table?')) {
                            this.$axios.delete(`{{ route('admin.tables.index') }}/${row.id}`)
                                .then(response => {
                                    this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });
                                    window.location.reload();
                                });
                        }
                    },
                    saveTable(params) {
                        this.loading = true;
                        const url = this.editMode 
                            ? `{{ route('admin.tables.index') }}/${this.table.id}`
                            : `{{ route('admin.tables.store') }}`;

                        const payload = {
                            ...params,
                            floor_id: this.table.floor_id,
                            status: this.table.status,
                            x_position: this.table.x_position,
                            y_position: this.table.y_position
                        };

                        this.$axios.post(url, payload)
                            .then(response => {
                                this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });
                                this.visible = false;
                                this.loading = false;
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
