<x-admin::layouts>
    <div class="page-header flex justify-between items-center">
        <div>
            <h1 class="page-title">Event Management</h1>
            <div class="page-breadcrumb">Home / Events</div>
        </div>
        <Button label="Create Event" icon="pi pi-plus" size="small" @click="emitter.emit('open-event-modal')" />
    </div>

    <v-events :clubs='@json($clubs)'></v-events>

    @pushOnce('scripts')
        <script type="text/x-template" id="v-events-template">
            <div>
                <!-- Datagrid -->
                <x-admin::datagrid
                    :is-multi-row="true"
                    ref="eventsGrid"
                    src="{{ route('admin.events.index') }}"
                />

                <!-- Create/Edit Event Modal -->
                <Dialog v-model:visible="visible" :header="editMode ? 'Edit Event' : 'Create Event'" :style="{ width: '450px' }" modal>
                    <x-admin::form v-slot="{ meta, errors, handleSubmit }" as="div">
                        <form @submit="handleSubmit($event, saveEvent)" class="space-y-4 pt-3">
                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label label="Club" />
                                <Select
                                    v-model="event.club_id"
                                    :options="clubs"
                                    optionLabel="name"
                                    optionValue="id"
                                    placeholder="Select Club"
                                    class="w-full"
                                />
                            </x-admin::form.control-group>

                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label label="Event Name" />
                                <x-admin::form.control-group.control
                                    type="text"
                                    name="name"
                                    v-model="event.name"
                                    rules="required"
                                    placeholder="Enter event name"
                                />
                                <x-admin::form.control-group.error name="name" />
                            </x-admin::form.control-group>

                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label label="Description" />
                                <x-admin::form.control-group.control
                                    type="textarea"
                                    name="description"
                                    v-model="event.description"
                                    placeholder="Enter event description"
                                />
                            </x-admin::form.control-group>

                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label label="Start Time" />
                                <x-admin::form.control-group.control
                                    type="date"
                                    name="start_time"
                                    v-model="event.start_time"
                                    rules="required"
                                    placeholder="Select start date & time"
                                />
                                <x-admin::form.control-group.error name="start_time" />
                            </x-admin::form.control-group>

                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label label="End Time" />
                                <x-admin::form.control-group.control
                                    type="date"
                                    name="end_time"
                                    v-model="event.end_time"
                                    rules="required"
                                    placeholder="Select end date & time"
                                />
                                <x-admin::form.control-group.error name="end_time" />
                            </x-admin::form.control-group>

                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label label="Cover Charge ($)" />
                                <x-admin::form.control-group.control
                                    type="text"
                                    name="cover_charge"
                                    v-model="event.cover_charge"
                                    rules="required"
                                    placeholder="e.g. 20.00"
                                />
                                <x-admin::form.control-group.error name="cover_charge" />
                            </x-admin::form.control-group>

                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label label="Capacity" />
                                <x-admin::form.control-group.control
                                    type="text"
                                    name="capacity"
                                    v-model="event.capacity"
                                    placeholder="Maximum guests count"
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
            adminVueApp.component('v-events', {
                template: '#v-events-template',
                props: ['clubs'],
                data() {
                    return {
                        visible: false,
                        editMode: false,
                        loading: false,
                        event: {
                            id: null,
                            club_id: null,
                            name: '',
                            description: '',
                            start_time: '',
                            end_time: '',
                            cover_charge: 0.00,
                            capacity: null
                        },
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
                    this.emitter.on('open-event-modal', () => {
                        this.editMode = false;
                        this.event = { id: null, club_id: null, name: '', description: '', start_time: '', end_time: '', cover_charge: 0.00, capacity: null };
                        this.visible = true;
                    });
                },
                methods: {
                    onEdit(row) {
                        this.editMode = true;
                        const parentClub = this.clubs.find(c => c.name === row.club_name);
                        this.event = {
                            id: row.id,
                            club_id: parentClub ? parentClub.id : null,
                            name: row.event_name,
                            description: row.description || '',
                            start_time: row.start_time,
                            end_time: row.end_time,
                            cover_charge: row.cover_charge,
                            capacity: row.capacity
                        };
                        this.visible = true;
                    },
                    onDelete(row) {
                        if (confirm('Are you sure you want to delete this event?')) {
                            this.$axios.delete(`{{ route('admin.events.index') }}/${row.id}`)
                                .then(response => {
                                    this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });
                                    window.location.reload();
                                });
                        }
                    },
                    saveEvent(params) {
                        this.loading = true;
                        const url = this.editMode 
                            ? `{{ route('admin.events.index') }}/${this.event.id}`
                            : `{{ route('admin.events.store') }}`;

                        const payload = {
                            ...params,
                            club_id: this.event.club_id
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
