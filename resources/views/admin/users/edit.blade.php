<x-admin::layouts>
    <v-edit-user></v-edit-user>

    @pushOnce('scripts')
            <script type="text/x-template" id="v-edit-user-template">
                <template v-if="Object.keys(responseData).length === 0">
                    <x-admin::shimmer.dynamic-page />
                </template>
        <div class="space-y-6" v-else >
            <!-- Page Header -->
            <div class="flex flex-wrap items-center justify-between gap-4 pb-4 border-b border-gray-200 ">
                <div>
                    <h1 class="page-title">Edit User Profile</h1>
                    <p class="page-breadcrumb">Home / Users / Edit</p>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.users.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-200 dark:border-gray-700 dark:hover:bg-gray-700 transition">
                        Cancel
                    </a>

                    <Button
                        label="Save Changes"
                        icon="pi pi-check"
                        form="update-form"
                        class="p-button-primary"
                        />
                </div>
            </div>
            Start

            <Button
                label="Add Dynamic Column"
                icon="pi pi-plus"
                class="p-button-outlined"
                @click="$refs.quickTable.open()"
                /> 

            END

            <!-- Main Grid Layout -->
            <div>
                <x-admin::form
                    v-slot="{ meta, errors, handleSubmit }"
                    as="div"
                >
                    <form @submit="handleSubmit($event, update)"  id="update-form">
                         <Button
                         type='submit'
                        label="Save Changes"
                        icon="pi pi-check"
                        form="update-form"
                        class="p-button-primary" />

                        <!-- Section -->
                        <template v-for="(section, section_idx) in design_config?.sections ?? []">
                            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 ">
                                <!-- Left Side:  -->
                                <div class="lg:col-span-2 space-y-6">
                                    <template v-for="(left_sides, left_idx) in section.left">
                                        <!-- Left Secions -->
                                        <div class="card p-6 shadow-sm " >
                                            <div class="flex items-center gap-2 mb-6 border-b border-(--border)  pb-3">
                                                @{{ left_sides?.icons ?? '<i class="pi pi-user text-(--accent) text-lg"></i>' }}

                                                <h2 class="text-base font-bold text-(--text-base)">@{{ left_sides?.label }}</h2>
                                            </div>
                                            <!-- Fields -->
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                <template v-for="(f, field_idx) in left_sides?.fields ?? []" :key="f.code">
                                                    <!-- Init  -->
                                                    @{{ $root.dynamicFieldInitilize(f) }}

                                                <x-admin::form.dynamic-field ::f="f" ::responseData="responseData" />
                                                </template>
                                            </div>
                                        </div>
                                    </template>

                                    <!-- Permissions Card -->
                                    <div class="card p-6 shadow-sm border border-(]--border)">
                                        <div class="flex items-center justify-between mb-6 border-b border-(--border)  pb-3">
                                            <div class="flex items-center gap-2">
                                                <i class="pi pi-shield text-(--accent) text-lg"></i>
                                                <h2 class="text-base font-bold text-(--text-base)">Permissions</h2>
                                            </div>
                                        </div>

                                        <!-- Choose Role Tab -->
                                        <div>
                                            <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase mb-2">Pick permissions from Roles</label>
                                            <MultiSelect @change="roleChanged($event.value)" :options="roleOptions" optionLabel="name" optionValue="permissions" placeholder="Select Roles" class="w-full" display="chip" />
                                        </div>

                                        @{{ roles }}

                                        <!-- Permissions Tab -->
                                        <div class="space-y-4 mt-3">
                                            <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase mb-2">Search Permissions</label>

                                            <!-- Search Input -->
                                            <div class="relative mb-4">
                                                <i class="pi pi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500"></i>
                                                <InputText v-model="permissionSearch" placeholder="Search permissions..." class="w-full pl-9 text-sm" />
                                            </div>

                                            <!-- Permissions Scroll Area -->
                                            <div class="space-y-6 max-h-[300px] overflow-y-auto pr-1">
                                                <div v-for="(group, group_idx) in filteredPermissionGroups" :key="group_idx + group.label" class="space-y-3">
                                                    <span class="block text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">@{{ group.label }}</span>
                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                                        <label v-for="permission in group.permissions" :key="permission.key" :for="'perm_' + permission.key"
                                                            :class="permissions.includes(permission.key)
                                                                ? 'border-indigo-500 bg-indigo-50/10 dark:bg-indigo-950/10'
                                                                : 'border-gray-200  hover:bg-gray-50 dark:hover:bg-gray-800/40'"
                                                            class="flex items-start gap-3 p-3 border rounded-lg cursor-pointer transition-all duration-200 select-none">

                                                            <div class="flex items-center h-5 mt-0.5">
                                                                <Checkbox v-model="permissions" :value="permission.key" :inputId="'perm_' + permission.key" />
                                                            </div>

                                                            <div class="flex-1">
                                                                <span class="block text-xs font-bold text-gray-900 dark:text-white">
                                                                    @{{ permission.name }}
                                                                </span>
                                                                <span class="block text-[10px] text-gray-500 dark:text-gray-400 mt-0.5">@{{ permission.desc }}</span>
                                                            </div>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div v-if="filteredPermissionGroups.length === 0" class="text-center py-8 text-gray-400 dark:text-gray-500">
                                                    <i class="pi pi-search text-2xl mb-2 block"></i>
                                                    No permissions match "@{{ permissionSearch }}"
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Right Side:  -->
                                <div class="space-y-6" v-if="section.right?.length > 0">
                                    <template v-for="(right_sides, right_idx) in section.right">
                                        <div class="card py-3  px-3 shadow-sm border border-gray-200  rounded-xl bg-white ">
                                            <!-- Header -->
                                            <div class="flex items-center justify-between mb-6 border-b border-(--border)  pb-3">
                                                <div class="flex items-center gap-2">
                                                    <i class="pi pi-list text-indigo-500 text-lg"></i>
                                                    <h2 class="text-base font-bold text-gray-900 dark:text-white">@{{ right_sides?.label }}</h2>
                                                </div>
                                            </div>

                                            <!-- Fields List -->
                                            <div class="space-y-5">


                                                <!-- Dynamic from Server -->
                                                <template v-for="(f, field_idx) in right_sides?.fields ?? []" :key="f.code">
                                                    <!-- Init  -->
                                                    @{{ $root.dynamicFieldInitilize(f) }}

                                                    <!-- Value Control Input Renderer -->
                                                    <x-admin::form.dynamic-field ::f="f" ::responseData="responseData" />
                                                </template>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </template>
                    </form>


                </x-admin::form>
            </div>

            {{-- <!-- Add Dynamic Column Dialog -->
            <Dialog v-model:visible="showAddFieldDialog" header="Add Dynamic Column" :style="{ width: '420px' }" modal>
                <div class="space-y-4 pt-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase mb-1">Column Label (Display Name)</label>
                        <InputText v-model="newField.label" class="w-full" placeholder="e.g. Phone Number, Department" @input="suggestKey" />
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase mb-1">Column Key (Database identifier)</label>
                        <InputText v-model="newField.key" class="w-full" placeholder="e.g. phone_number, dept_id" />
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase mb-1">Input Field Type</label>
                        <Select v-model="newField.type" :options="fieldTypes" optionLabel="label" optionValue="value" class="w-full" />
                    </div>

                    <!-- Show options inputs if type is select -->
                    <div v-if="newField.type === 'select'">
                        <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase mb-1">Options (Comma separated)</label>
                        <InputText v-model="newField.optionsString" class="w-full" placeholder="e.g. Engineering, Sales, HR" />
                    </div>
                </div>

                <template #footer>
                    <Button label="Cancel" severity="secondary" text @click="showAddFieldDialog = false" />
                    <Button label="Add Field" @click="addCustomColumn" :disabled="!newField.label || !newField.key" />
                </template>
            </Dialog> --}}

            <!-- Success Toast Container -->
            <Toast />
        </div>
        </script>

            <script type="module">
                adminVueApp.component('v-edit-user', {
                    template: '#v-edit-user-template',

                    data() {
                        return {
                            visible: false,
                            roles: null,
                            user: {},
                            permissions: {},
                            isLoading: false,

                            activeRoleAndPermissionTab: 'role',
                            permissionSearch: '',

                            testlabel: 'test label',
                            design_config: [],
                            responseData: {},

                            isSaving: false,
                            showAddFieldDialog: false,

                            // Add Custom Column state
                            newField: {
                                label: '',
                                key: '',
                                type: 'text',
                                optionsString: ''
                            },

                            // Role configurations
                            roleOptions: [],

                            // Permissions list grouped by category
                            permissionGroups: [],
                        };
                    },

                    computed: {
                        filteredPermissionGroups() {
                            if (!this.permissionSearch) {
                                return this.permissionGroups;
                            }
                            const search = this.permissionSearch.toLowerCase();
                            return this.permissionGroups.map(group => {
                                const filtered = group.permissions.filter(p =>
                                    p.name.toLowerCase().includes(search) ||
                                    p.desc.toLowerCase().includes(search) ||
                                    p.key.toLowerCase().includes(search)
                                );
                                return {
                                    label: group.label,
                                    permissions: filtered
                                };
                            }).filter(group => group.permissions.length > 0);
                        }
                    },

                    mounted() {
                        this.$axios.get('{{ config('api.users.edit') }}'.replace(':id', '{{ $id }}'))
                            .then((response) => {
                                console.log(response);

                                this.responseData = response?.data?.data; // from  server
                                // this.responseData = response?.data; // static

                                console.log("responseData", this.responseData, this.responseData?.genders);
                                this.design_config = this.responseData?.desgin_config; // from  server
                                // this.design_config = response.data.desgin_config; // from static


                                this.roleOptions = this.responseData?.roles ?? [];
                                this.permissionGroups = this.responseData?.acl_config ?? [];

                                this.user = this.responseData?.user ?? {};
                                this.permissions = this.user.permissions ?? [];

                                console.log(this.design_config);

                            })
                            .catch((error) => {
                                console.log(error);
                            });

                    },

                    methods: {
                        roleChanged(selectedRoles) {
                            this.permissions= selectedRoles.flat();
                        },

                        customerOnFilter() {

                        },

                        update(params, { resetForm, setErrors }) {

                            params.permissions = this.permissions;

                            // console.log(params);
                            // return;
                            this.isLoading = true;

                            this.$axios.post('{{ config('api.users.update') }}'.replace(':id', '{{ $id }}'), params)
                                .then((response) => {
                                    this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });

                                    // resetForm();

                                    this.isLoading = false;
                                })
                                .catch(error => {
                                    console.log(error);
                                    this.isLoading = false;

                                    if (error.response.status == 422) {
                                        setErrors(error.response.data.errors);
                                    }
                                });
                        }
                    }
                });
            </script>
    @endPushOnce
</x-admin::layouts>
