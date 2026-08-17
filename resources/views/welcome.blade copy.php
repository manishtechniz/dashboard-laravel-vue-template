<x-admin::layouts>

<v-create-customer-form>

</v-create-customer-form>


    <script type="text/x-template" id="v-create-customer-form-template">
<x-admin::form
            v-slot="{ meta, errors, handleSubmit }"
            as="div"
        >
            <form @submit="handleSubmit($event, create)">
                <!-- Customer Create Modal -->
                <x-admin::modal ref="customerCreateModal">
                    <!-- Modal Header -->
                    <x-slot:header>
                        <p class="text-lg font-bold text-gray-800 dark:text-white">
                            @lang('admin::app.customers.customers.index.create.title')
                        </p>
                    </x-slot>

                    <!-- Modal Content -->
                    <x-slot:content>
                        {!! view_render_event('bagisto.admin.customers.create.before') !!}

                        <div class="flex gap-4 max-sm:flex-wrap">
                            <!-- First Name -->
                            <x-admin::form.control-group class="mb-2.5 w-full">
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.customers.customers.index.create.first-name')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="text"
                                    id="first_name"
                                    name="first_name"
                                    rules="required"
                                    :label="trans('admin::app.customers.customers.index.create.first-name')"
                                    :placeholder="trans('admin::app.customers.customers.index.create.first-name')"
                                />

                                <x-admin::form.control-group.error control-name="first_name" />
                            </x-admin::form.control-group>

                            <!-- Last Name -->
                            <x-admin::form.control-group class="mb-2.5 w-full">
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.customers.customers.index.create.last-name')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="text"
                                    id="last_name"
                                    name="last_name"
                                    rules="required"
                                    :label="trans('admin::app.customers.customers.index.create.last-name')"
                                    :placeholder="trans('admin::app.customers.customers.index.create.last-name')"
                                />

                                <x-admin::form.control-group.error control-name="last_name" />
                            </x-admin::form.control-group>
                        </div>

                        <!-- Email -->
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.customers.customers.index.create.email')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="email"
                                id="email"
                                name="email"
                                rules="required|email"
                                :label="trans('admin::app.customers.customers.index.create.email')"
                                placeholder="email@example.com"
                            />

                            <x-admin::form.control-group.error control-name="email" />
                        </x-admin::form.control-group>
                        </div>
                    </x-slot>

                    <!-- Modal Footer -->
                    <x-slot:footer>
                        <Button type="submit" label="Save" :loading="isLoading"/>
                    </x-slot>
                </x-admin::modal>
            </form>
        </x-admin::form>

        <script>

        <script type="module">
        app.component('v-create-customer-form', {
            template: '#v-create-customer-form-template',

            data() {
                return {
                    groups: @json($groups),

                    channels: @json($channels),

                    isLoading: false,
                };
            },

            methods: {
                openModal() {
                    this.$refs.customerCreateModal.open();
                },

                create(params, { resetForm, setErrors }) {
                    this.isLoading = true;

                    this.$axios.post("{{ route('admin.customers.customers.store') }}", params)
                        .then((response) => {
                            this.$refs.customerCreateModal.close();

                            this.$emit('customer-created', response.data.data);

                            this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });

                            resetForm();

                            this.isLoading = false;
                        })
                        .catch(error => {
                            this.isLoading = false;

                            if (error.response.status == 422) {
                                setErrors(error.response.data.errors);
                            }
                        });
                }
            }
        })
    </script>

</x-admin::layouts>
