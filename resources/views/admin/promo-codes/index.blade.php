<x-admin::layouts>
    <div class="page-header flex justify-between items-center">
        <div>
            <h1 class="page-title">Promo Codes</h1>
            <div class="page-breadcrumb">Home / Promo Codes</div>
        </div>
        <Button label="Create Promo Code" icon="pi pi-plus" size="small" @click="$refs.promo.visible = true" />
    </div>

    <v-promo-codes ref="promo"></v-promo-codes>

    @pushOnce('scripts')
        <script type="text/x-template" id="v-promo-codes-template">
            <div>
                <!-- Datagrid -->
                <x-admin::datagrid
                    :is-multi-row="true"
                    ref="promosGrid"
                    src="{{ route('admin.promo_codes.index') }}"
                />

                <!-- Create/Edit Promo Modal -->
                <Dialog v-model:visible="visible" :header="editMode ? 'Edit Promo Code' : 'Create Promo Code'" :style="{ width: '450px' }" modal>
                    <x-admin::form v-slot="{ meta, errors, handleSubmit }" as="div">
                        <form @submit="handleSubmit($event, savePromo)" class="space-y-4 pt-3 max-h-[500px] overflow-y-auto pr-1">
                            <x-admin::form.control-group>
                                <div class="flex gap-2 items-end">
                                    <div class="flex-1">
                                        <x-admin::form.control-group.label label="Promo Code" />
                                        <x-admin::form.control-group.control
                                            type="text"
                                            name="code"
                                            v-model="promo.code"
                                            rules="required"
                                            placeholder="e.g. SUMMER50"
                                        />
                                    </div>
                                    <Button type="button" label="Generate" icon="pi pi-refresh" severity="secondary" @click="generateCode" />
                                </div>
                            </x-admin::form.control-group>

                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label label="Discount Type" />
                                <Select
                                    v-model="promo.type"
                                    :options="typeOptions"
                                    optionLabel="label"
                                    optionValue="value"
                                    placeholder="Select Type"
                                    class="w-full"
                                />
                            </x-admin::form.control-group>

                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label label="Discount Value" />
                                <x-admin::form.control-group.control
                                    type="text"
                                    name="value"
                                    v-model="promo.value"
                                    rules="required"
                                    placeholder="e.g. 10.00 or 15"
                                />
                            </x-admin::form.control-group>

                            <div class="grid grid-cols-2 gap-4">
                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label label="Start Date" />
                                    <x-admin::form.control-group.control
                                        type="date"
                                        name="start_date"
                                        v-model="promo.start_date"
                                        placeholder="YYYY-MM-DD"
                                    />
                                </x-admin::form.control-group>

                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label label="End Date" />
                                    <x-admin::form.control-group.control
                                        type="date"
                                        name="end_date"
                                        v-model="promo.end_date"
                                        placeholder="YYYY-MM-DD"
                                    />
                                </x-admin::form.control-group>
                            </div>

                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label label="Usage Limit" />
                                <x-admin::form.control-group.control
                                    type="text"
                                    name="usage_limit"
                                    v-model="promo.usage_limit"
                                    placeholder="e.g. 100"
                                />
                            </x-admin::form.control-group>

                            <div class="flex items-center gap-2 pt-2">
                                <ToggleSwitch v-model="promo.is_active" inputId="is_active_toggle" />
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
            adminVueApp.component('v-promo-codes', {
                template: '#v-promo-codes-template',
                data() {
                    return {
                        visible: false,
                        editMode: false,
                        loading: false,
                        promo: {
                            id: null,
                            code: '',
                            type: 'fixed',
                            value: 0.00,
                            start_date: '',
                            end_date: '',
                            usage_limit: null,
                            is_active: true
                        },
                        typeOptions: [
                            { label: 'Fixed ($)', value: 'fixed' },
                            { label: 'Percentage (%)', value: 'percentage' }
                        ],
                        emitter: null
                    };
                },
                watch: {
                    visible(val) {
                        if (val && !this.editMode) {
                            this.promo = { id: null, code: '', type: 'fixed', value: 0.00, start_date: '', end_date: '', usage_limit: null, is_active: true };
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
                    generateCode() {
                        const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
                        let code = '';
                        for (let i = 0; i < 8; i++) {
                            code += chars.charAt(Math.floor(Math.random() * chars.length));
                        }
                        this.promo.code = code;
                    },
                    onEdit(row) {
                        this.editMode = true;
                        this.promo = {
                            id: row.id,
                            code: row.code,
                            type: row.type,
                            value: row.value,
                            start_date: row.start_date || '',
                            end_date: row.end_date || '',
                            usage_limit: row.usage_limit || null,
                            is_active: !!row.is_active
                        };
                        this.visible = true;
                    },
                    onDelete(row) {
                        if (confirm('Are you sure you want to delete this promo code?')) {
                            this.$axios.delete(`{{ route('admin.promo_codes.index') }}/${row.id}`)
                                .then(response => {
                                    this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });
                                    window.location.reload();
                                });
                        }
                    },
                    savePromo(params) {
                        this.loading = true;
                        const url = this.editMode 
                            ? `{{ route('admin.promo_codes.index') }}/${this.promo.id}`
                            : `{{ route('admin.promo_codes.store') }}`;

                        const payload = {
                            ...params,
                            type: this.promo.type,
                            start_date: this.promo.start_date,
                            end_date: this.promo.end_date,
                            usage_limit: this.promo.usage_limit,
                            is_active: this.promo.is_active ? 1 : 0
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
