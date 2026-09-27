<x-admin::layouts>
    <div class="page-header flex justify-between items-center">
        <div>
            <h1 class="page-title">Testimonials</h1>
            <div class="page-breadcrumb">Home / Testimonials</div>
        </div>
        @if (hasPermission('admin.testimonials.store'))
        <Button label="Create" outlined icon="pi pi-plus" size="small" @click="$refs.testimonial.visible = true; $refs.testimonial.editMode = false;" />
        @endif
    </div>

    <v-testimonials ref="testimonial" :clients='@json($clients)' :clubs='@json($clubs)'></v-testimonials>

    @pushOnce('scripts')
    <script type="text/x-template" id="v-testimonials-template">
        <div>
                <!-- Datagrid -->
                <x-admin::datagrid
                    :is-multi-row="true"
                    ref="testimonialsGrid"
                    src="{{ route('admin.testimonials.index') }}"
                />

                <!-- Edit Testimonial Modal -->
                <Dialog v-model:visible="visible" :header="editMode ? 'Edit Testimonial' : 'Create Testimonial'" :style="{ width: '650px', maxWidth: '95vw' }" modal>
                    <x-admin::form v-slot="{ meta, errors, handleSubmit }" as="div">
                        <form @submit="handleSubmit($event, saveTestimonial)" class="space-y-4 pt-3">
                            <div class="grid grid-cols-2 gap-4">
                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label label="Club" />
                                    <x-admin::form.control-group.control
                                        v-model="testimonial.club_id"
                                        ::value="testimonial.club_id"
                                        ::options="clubs"
                                        optionLabel="name"
                                        optionValue="id"
                                        placeholder="Select Club"
                                        name="club_id"
                                        type="select"
                                    />
                                </x-admin::form.control-group>

                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label label="Client" />
                                    <x-admin::form.control-group.control
                                        v-model="testimonial.client_id"
                                        ::value="testimonial.client_id"
                                        ::options="clients"
                                        optionLabel="name"
                                        optionValue="id"
                                        placeholder="Select Client"
                                        rules="required"
                                        name="client_id"
                                        type="select"
                                    />
                                </x-admin::form.control-group>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label label="Title (Optional)" />
                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="title"
                                        v-model="testimonial.title"
                                        placeholder="e.g. Great experience!"
                                    />
                                </x-admin::form.control-group>

                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label label="Rating (1-5)" />
                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="rating"
                                        v-model="testimonial.rating"
                                        rules="required|min_value:1|max_value:5"
                                        placeholder="e.g. 5"
                                    />
                                </x-admin::form.control-group>
                            </div>

                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label label="Comment" />
                                <x-admin::form.control-group.control
                                    type="textarea"
                                    name="comment"
                                    rules="required"
                                    v-model="testimonial.comment"
                                    placeholder="Enter testimonial content..."
                                />
                            </x-admin::form.control-group>

                            <div class="flex items-center gap-6 pt-2">
                                <div class="flex items-center gap-2">
                                    <ToggleSwitch v-model="testimonial.is_published" inputId="is_published_toggle" />
                                    <x-admin::form.control-group.label label="Published" for="is_published_toggle" />
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
        adminVueApp.component('v-testimonials', {
            template: '#v-testimonials-template',
            props: ['clients', 'clubs'],
            data() {
                return {
                    visible: false,
                    editMode: false,
                    loading: false,
                    testimonial: {
                        id: null,
                        client_id: null,
                        club_id: null,
                        title: '',
                        rating: 5,
                        comment: '',
                        is_published: true,
                    }
                };
            },
            watch: {
                visible(val) {
                    if (val && !this.editMode) {
                        this.testimonial = {
                            id: null,
                            client_id: null,
                            club_id: null,
                            title: '',
                            rating: 5,
                            comment: '',
                            is_published: true,
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
                    this.testimonial = {
                        id: row.id,
                        client_id: row.client_id || null,
                        club_id: row.club_id || null,
                        title: row.title || '',
                        rating: row.rating || 5,
                        comment: row.comment || '',
                        is_published: !!row.is_published,
                    };
                    this.visible = true;
                },
                saveTestimonial(params, {
                    resetForm,
                    setErrors
                }) {
                    this.loading = true;
                    const url = this.editMode ?
                        `{{ route('admin.testimonials.index') }}/${this.testimonial.id}` :
                        `{{ route('admin.testimonials.store') }}`;

                    const payload = {
                        ...params,
                        client_id: this.testimonial.client_id,
                        club_id: this.testimonial.club_id,
                        title: this.testimonial.title,
                        rating: this.testimonial.rating,
                        comment: this.testimonial.comment,
                        is_published: this.testimonial.is_published ? 1 : 0,
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
                            this.$refs.testimonialsGrid.get();
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
