<x-admin::layouts>
    <div class="page-header flex justify-between items-center">
        <div>
            <h1 class="page-title">Event Management</h1>
            <div class="page-breadcrumb">Home / Events</div>
        </div>
        @if (hasPermission('admin.events.store'))
        <Button label="Create Event" icon="pi pi-plus" size="small" @click="$refs.event.visible = true" />
        @endif
    </div>

    <v-events ref="event" :clubs='@json($clubs)'></v-events>

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
                <Dialog v-model:visible="visible" :header="editMode ? 'Edit Event' : 'Create Event'" :style="{ width: '580px', maxWidth: '95vw' }" modal>
                    <x-admin::form v-slot="{ meta, errors, handleSubmit }" as="div">
                        <form @submit="handleSubmit($event, saveEvent)" class="space-y-4 pt-3" ref="eventForm">
                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label label="Club" />
                                <x-admin::form.control-group.control
                                    type="select"
                                    rules="required"
                                    v-model="event.club_id"
                                    ::value="event.club_id"
                                    ::options="clubs"
                                    optionLabel="name"
                                    optionValue="id"
                                    ::name="'club_id'"
                                    placeholder="Select Club" 
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
                            </x-admin::form.control-group>

                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label label="Description" />
                                <x-admin::form.control-group.control
                                    type="textarea"
                                    name="description"
                                    v-model="event.description"
                                    rules="required"
                                    placeholder="Enter event description"
                                />
                            </x-admin::form.control-group>

                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label label="Event Date" />
                                <x-admin::form.control-group.control
                                    type="date"
                                    name="event_date"
                                    v-model="event.event_date"
                                    rules="required"
                                    placeholder="Select event date"
                                />
                            </x-admin::form.control-group>

                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label label="Image URL" />
                                <x-admin::form.control-group.control
                                    type="file"
                                    name="image"
                                    ::rules="{required: !editMode}"
                                    v-model="event.image"
                                    placeholder="Enter image URL"
                                />
                            </x-admin::form.control-group>

                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label label="Featured Image URL" />
                                <x-admin::form.control-group.control
                                    type="file"
                                    ::rules="{required: !editMode}"
                                    name="featured_image"
                                    v-model="event.featured_image"
                                    placeholder="Enter featured image URL"
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
                        event_date: '',
                        image: '',
                        featured_image: ''
                    },
                    emitter: null
                };
            },
            watch: {
                visible(val) {
                    if (val && !this.editMode) {
                        this.event = {
                            id: null,
                            club_id: null,
                            name: '',
                            description: '',
                            event_date: '',
                            image: '',
                            featured_image: ''
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
                    const parentClub = this.clubs.find(c => c.name === row.club_name);
                    this.event = {
                        id: row.id,
                        club_id: parentClub ? parentClub.id : null,
                        name: row.event_name,
                        description: row.description || '',
                        event_date: row.event_date,
                        image: row.image || '',
                        featured_image: row.featured_image || ''
                    };
                    this.visible = true;
                },

                saveEvent(params, {
                    resetForm,
                    setErrors
                }) {
                    console.log(params);
                    this.loading = true;
                    const url = this.editMode ?
                        `{{ route('admin.events.index') }}/${this.event.id}` :
                        `{{ route('admin.events.store') }}`;



                    const form = this.$refs.eventForm;
                    const formData = new FormData(form);
                    formData.append('_method', this.editMode ? 'PUT' : 'POST');
                    formData.append('club_id', params.club_id);

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
                            this.$refs.eventsGrid.get();
                        })
                        .catch(error => {
                            this.loading = false;

                            if (error.response.status === 422) {
                                if (error.response.data.errors?.general) {
                                    this.$emitter.emit('add-flash', {
                                        type: 'error',
                                        message: error.response.data.errors?.general[0]
                                    });
                                }

                                setErrors(error.response.data.errors);
                            }
                        });
                }
            }
        });
    </script>
    @endPushOnce
</x-admin::layouts>