<x-admin::layouts>
    <div class="page-header flex justify-between items-center">
        <div>
            <h1 class="page-title">Flyers</h1>
            <div class="page-breadcrumb">Home / Flyers</div>
        </div>
        @if (hasPermission('admin.flyers.store'))
        <Button label="Create" outlined icon="pi pi-plus" size="small" @click="$refs.flyer.visible = true" />
        @endif
    </div>

    <v-flyers ref="flyer"></v-flyers>

    @pushOnce('scripts')
    <script type="text/x-template" id="v-flyers-template">
        <div>
            <!-- Datagrid -->
            <x-admin::datagrid
                :is-multi-row="true"
                ref="flyersGrid"
                src="{{ route('admin.flyers.index') }}"
            />

            <!-- Create/Edit Flyer Modal -->
            <Dialog v-model:visible="visible" :header="editMode ? 'Edit Flyer' : 'Create Flyer'" :style="{ width: '580px', maxWidth: '95vw' }" modal>
                <x-admin::form v-slot="{ meta, errors, handleSubmit }" as="div">
                    <form @submit="handleSubmit($event, saveFlyer)" class="space-y-4 pt-3" ref="flyerForm">
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label label="Title" />
                            <x-admin::form.control-group.control
                                type="text"
                                name="title"
                                v-model="flyer.title"
                                placeholder="Enter flyer title (optional)"
                            />
                        </x-admin::form.control-group>

                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label label="Description" />
                            <x-admin::form.control-group.control
                                type="textarea"
                                name="description"
                                v-model="flyer.description"
                                placeholder="Enter flyer description (optional)"
                            />
                        </x-admin::form.control-group>

                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label label="File Type" />
                            <div class="flex gap-4 items-center h-[42px]">
                                <div class="flex items-center gap-2">
                                    <input type="radio" id="type-image" name="file_type" value="image" v-model="flyer.file_type" class="cursor-pointer" />
                                    <label for="type-image" class="cursor-pointer text-sm">Image</label>
                                </div>
                                <div class="flex items-center gap-2">
                                    <input type="radio" id="type-video" name="file_type" value="video" v-model="flyer.file_type" class="cursor-pointer" />
                                    <label for="type-video" class="cursor-pointer text-sm">Video</label>
                                </div>
                            </div>
                        </x-admin::form.control-group>

                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label label="Media File (Image/Video)" />
                            <x-admin::form.control-group.control
                                type="file"
                                name="file"
                                ::rules="{required: !editMode}"
                                ::accept="flyer.file_type === 'image' ? 'image/*' : 'video/mp4,video/x-m4v,video/*'"
                                placeholder="Upload media file"
                            />
                        </x-admin::form.control-group>

                        <x-admin::form.control-group v-if="flyer.file_type === 'image'">
                            <x-admin::form.control-group.label label="Audio Attachment (Optional)" />
                            <x-admin::form.control-group.control
                                type="file"
                                name="audio"
                                accept="audio/*"
                                placeholder="Upload background audio"
                            />
                            <div class="text-xs text-gray-500 mt-1">Audio will play while viewing this image flyer.</div>
                        </x-admin::form.control-group>

                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label label="Status" />
                            <div class="flex items-center h-[42px]">
                                <label class="flex items-center cursor-pointer">
                                    <div class="relative">
                                        <input type="checkbox" name="is_active" class="sr-only" v-model="flyer.is_active" value="1">
                                        <div class="block bg-gray-200 w-10 h-6 rounded-full" :class="flyer.is_active ? 'bg-indigo-500' : ''"></div>
                                        <div class="dot absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition" :class="flyer.is_active ? 'transform translate-x-4' : ''"></div>
                                    </div>
                                    <div class="ml-3 text-sm font-medium text-gray-700">
                                        @{{ flyer.is_active ? 'Active' : 'Inactive' }}
                                    </div>
                                </label>
                            </div>
                        </x-admin::form.control-group>

                        <div class="flex justify-end gap-2 pt-4 border-t border-gray-200">
                            <Button type="button" label="Cancel" severity="secondary" text size="small" @click="visible = false" />
                            <Button type="submit" label="Save Flyer" size="small" :loading="loading" />
                        </div>
                    </form>
                </x-admin::form>
            </Dialog>
            <Toast />
        </div>
    </script>

    <script type="module">
        adminVueApp.component('v-flyers', {
            template: '#v-flyers-template',
            data() {
                return {
                    visible: false,
                    editMode: false,
                    loading: false,
                    flyer: {
                        id: null,
                        title: '',
                        description: '',
                        file_type: 'image',
                        is_active: true
                    },
                    emitter: null
                };
            },
            watch: {
                visible(val) {
                    if (val && !this.editMode) {
                        this.flyer = {
                            id: null,
                            title: '',
                            description: '',
                            file_type: 'image',
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
                    this.flyer = {
                        id: row.id,
                        title: row.title || '',
                        description: row.description || '',
                        file_type: row.file_type || 'image',
                        is_active: row.is_active == 1
                    };
                    this.visible = true;
                },

                saveFlyer(params, {
                    resetForm,
                    setErrors
                }) {
                    this.loading = true;
                    const url = this.editMode ?
                        `{{ route('admin.flyers.index') }}/${this.flyer.id}` :
                        `{{ route('admin.flyers.store') }}`;

                    const form = this.$refs.flyerForm;
                    const formData = new FormData(form);
                    formData.append('_method', this.editMode ? 'PUT' : 'POST');

                    if (this.flyer.is_active === false || this.flyer.is_active === 0) {
                        formData.set('is_active', 0);
                    } else {
                        formData.set('is_active', 1);
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
                            this.$refs.flyersGrid.get();
                        })
                        .catch(error => {
                            this.loading = false;
                            if (error.response?.status === 422) {
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