<x-admin::layouts>
    <div class="page-header flex justify-between items-center">
        <div>
            <h1 class="page-title">Reviews & Feedback</h1>
            <div class="page-breadcrumb">Home / Reviews</div>
        </div>
        @if (hasPermission('admin.reviews.store'))
        <Button label="Create" outlined icon="pi pi-plus" size="small" @click="$refs.review.visible = true; $refs.review.editMode = false;" />
        @endif
    </div>

    <v-reviews ref="review" :clients='@json($clients)' :clubs='@json($clubs)'></v-reviews>

    @pushOnce('scripts')
    <script type="text/x-template" id="v-reviews-template">
        <div>
                <!-- Datagrid -->
                <x-admin::datagrid
                    :is-multi-row="true"
                    ref="reviewsGrid"
                    src="{{ route('admin.reviews.index') }}"
                />

                <!-- Edit Review Modal -->
                <Dialog v-model:visible="visible" :header="editMode ? 'Edit Review' : 'Create Review'" :style="{ width: '650px', maxWidth: '95vw' }" modal>
                    <x-admin::form v-slot="{ meta, errors, handleSubmit }" as="div">
                        <form @submit="handleSubmit($event, saveReview)" class="space-y-4 pt-3">
                            <div class="grid grid-cols-2 gap-4">
                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label label="Club" />
                                    <x-admin::form.control-group.control
                                        v-model="review.club_id"
                                        ::value="review.club_id"
                                        ::options="clubs"
                                        optionLabel="name"
                                        optionValue="id"
                                        placeholder="Select Club"
                                        name="club_id"
                                        rules="required"
                                        type="select"
                                    />
                                </x-admin::form.control-group>

                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label label="Client" />
                                    <x-admin::form.control-group.control
                                        v-model="review.client_id"
                                        ::value="review.client_id"
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
                                    <x-admin::form.control-group.label label="Booking ID (Optional)" />
                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="booking_id"
                                        v-model="review.booking_id"
                                        placeholder="e.g. 1024"
                                    />
                                </x-admin::form.control-group>

                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label label="Rating (1-5)" />
                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="rating"
                                        v-model="review.rating"
                                        rules="required|min_value:1|max_value:5"
                                        placeholder="e.g. 5"
                                    />
                                </x-admin::form.control-group>
                            </div>

                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label label="User Comment" />
                                <x-admin::form.control-group.control
                                    type="textarea"
                                    name="comment"
                                    rules="required"
                                    v-model="review.comment"
                                    placeholder="Enter user comment..."
                                />
                            </x-admin::form.control-group>

                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label label="Admin Remark (Optional)" />
                                <x-admin::form.control-group.control
                                    type="textarea"
                                    name="remark"
                                    v-model="review.remark"
                                    placeholder="Enter internal remark or reply..."
                                />
                            </x-admin::form.control-group>

                            <div class="flex items-center gap-6 pt-2">
                                <div class="flex items-center gap-2">
                                    <ToggleSwitch v-model="review.is_active" inputId="is_active_toggle" />
                                    <x-admin::form.control-group.label label="Active Status" for="is_active_toggle" />
                                </div>
                                <div class="flex items-center gap-2">
                                    <ToggleSwitch v-model="review.is_anonymous" inputId="is_anonymous_toggle" />
                                    <x-admin::form.control-group.label label="Anonymous" for="is_anonymous_toggle" />
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
        adminVueApp.component('v-reviews', {
            template: '#v-reviews-template',
            props: ['clients', 'clubs'],
            data() {
                return {
                    visible: false,
                    editMode: false,
                    loading: false,
                    review: {
                        id: null,
                        client_id: null,
                        club_id: null,
                        booking_id: null,
                        rating: 5,
                        comment: '',
                        remark: '',
                        is_active: true,
                        is_anonymous: false
                    }
                };
            },
            watch: {
                visible(val) {
                    if (val && !this.editMode) {
                        this.review = {
                            id: null,
                            client_id: null,
                            club_id: null,
                            booking_id: null,
                            rating: 5,
                            comment: '',
                            remark: '',
                            is_active: true,
                            is_anonymous: false
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
                    this.review = {
                        id: row.id,
                        client_id: row.client_id || null,
                        club_id: row.club_id || null,
                        booking_id: row.booking_id || null,
                        rating: row.rating || 5,
                        comment: row.comment || '',
                        remark: row.remark || '',
                        is_active: !!row.is_active,
                        is_anonymous: !!row.is_anonymous
                    };
                    this.visible = true;
                },
                saveReview(params, {
                    resetForm,
                    setErrors
                }) {
                    this.loading = true;
                    const url = this.editMode ?
                        `{{ route('admin.reviews.index') }}/${this.review.id}` :
                        `{{ route('admin.reviews.store') }}`;

                    const payload = {
                        ...params,
                        client_id: this.review.client_id,
                        club_id: this.review.club_id,
                        booking_id: this.review.booking_id,
                        rating: this.review.rating,
                        comment: this.review.comment,
                        remark: this.review.remark,
                        is_active: this.review.is_active ? 1 : 0,
                        is_anonymous: this.review.is_anonymous ? 1 : 0
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
                            this.$refs.reviewsGrid.get();
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