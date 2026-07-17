<x-admin::layouts>
    <div class="page-header flex justify-between items-center">
        <div>
            <h1 class="page-title">Floor Management</h1>
            <div class="page-breadcrumb">Home / Floors</div>
        </div>
        <Button label="Create Floor" icon="pi pi-plus" size="small" @click="$refs.floor.visible = true" />
    </div>

    <v-floors ref="floor" :branches='@json($branches)'></v-floors>

    @pushOnce('scripts')
        <script type="text/x-template" id="v-floors-template">
            <div>
                <!-- Datagrid -->
                <x-admin::datagrid
                    :is-multi-row="true"
                    ref="floorsGrid"
                    src="{{ route('admin.floors.index') }}"
                />

                <!-- Create/Edit Floor Modal -->
                <Dialog v-model:visible="visible" :header="editMode ? 'Edit Floor' : 'Create Floor'" :style="{ width: '450px' }" modal>
                    <x-admin::form v-slot="{ meta, errors, handleSubmit }" as="div">
                        <form @submit="handleSubmit($event, saveFloor)" class="space-y-4 pt-3">
                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label label="Branch" />
                                <Select
                                    v-model="floor.branch_id"
                                    :options="branches"
                                    optionLabel="name"
                                    optionValue="id"
                                    placeholder="Select Branch"
                                    class="w-full"
                                />
                            </x-admin::form.control-group>

                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label label="Floor Name" />
                                <x-admin::form.control-group.control
                                    type="text"
                                    name="name"
                                    v-model="floor.name"
                                    rules="required"
                                    placeholder="e.g. Ground Floor, Rooftop"
                                />
                            </x-admin::form.control-group>

                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label label="Floor Level" />
                                <x-admin::form.control-group.control
                                    type="text"
                                    name="level"
                                    v-model="floor.level"
                                    rules="required"
                                    placeholder="e.g. 0, 1, 2"
                                />
                            </x-admin::form.control-group>

                            <div class="flex items-center gap-2 pt-2">
                                <ToggleSwitch v-model="floor.is_active" inputId="is_active_toggle" />
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
            adminVueApp.component('v-floors', {
                template: '#v-floors-template',
                props: ['branches'],
                data() {
                    return {
                        visible: false,
                        editMode: false,
                        loading: false,
                        floor: {
                            id: null,
                            branch_id: null,
                            name: '',
                            level: 0,
                            is_active: true
                        },
                        emitter: null
                    };
                },
                watch: {
                    visible(val) {
                        if (val && !this.editMode) {
                            this.floor = { id: null, branch_id: null, name: '', level: 0, is_active: true };
                        } else if (!val) {
                            this.editMode = false;
                        }
                    }
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
                },
                methods: {
                    onEdit(row) {
                        this.editMode = true;
                        const parentBranch = this.branches.find(b => b.name === row.branch_name);
                        this.floor = {
                            id: row.id,
                            branch_id: parentBranch ? parentBranch.id : null,
                            name: row.floor_name,
                            level: row.level,
                            is_active: !!row.is_active
                        };
                        this.visible = true;
                    },
                    onDelete(row) {
                        if (confirm('Are you sure you want to delete this floor?')) {
                            this.$axios.delete(`{{ route('admin.floors.index') }}/${row.id}`)
                                .then(response => {
                                    this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });
                                    window.location.reload();
                                });
                        }
                    },
                    saveFloor(params) {
                        this.loading = true;
                        const url = this.editMode 
                            ? `{{ route('admin.floors.index') }}/${this.floor.id}`
                            : `{{ route('admin.floors.store') }}`;

                        const payload = {
                            ...params,
                            branch_id: this.floor.branch_id,
                            is_active: this.floor.is_active ? 1 : 0
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
