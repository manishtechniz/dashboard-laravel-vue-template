<x-admin::layouts>

    <div class="page-header">
        <h1 class="page-title">Settings</h1>
        <div class="page-breadcrumb">Home / Settings</div>
    </div>

    <v-config :initial-settings="{{ json_encode($settings ?? (object)[]) }}" :configurations="{{ json_encode($configurations ?? (object)[]) }}"></v-config>

    @pushOnce('scripts')
    <script type="text/x-template" id="v-config-template">
        <div>
            <Tabs v-model:value="activeGroup">
                <div class="flex flex-col md:grid md:grid-cols-[240px_1fr] gap-5">
                    {{-- Config Groups Sidebar (Desktop) --}}
                    <div class="card p-3 hidden md:block">
                        <div v-for="(group, key) in configurations" :key="key"
                            @click="activeGroup = key"
                            :class="['px-3 py-2.5 rounded-lg cursor-pointer mb-1 flex items-center gap-2 text-[13px] transition-colors',
                                activeGroup === key 
                                    ? 'bg-[var(--accent-light)] text-[var(--accent)] font-semibold' 
                                    : 'bg-transparent text-[var(--text-base)] font-normal hover:bg-gray-100'
                            ]"
                        >
                            <i :class="group.icon || 'pi pi-cog'" class="text-[14px]"></i>
                            @{{ group.title }}
                        </div>
                    </div>

                    {{-- Mobile Tabs List --}}
                    <div class="block md:hidden">
                        <TabList>
                            <Tab v-for="(group, key) in configurations" :key="key" :value="key" class="flex items-center gap-2!">
                                <i :class="group.icon || 'pi pi-cog'" class="text-[14px]"></i>
                                @{{ group.title }}
                            </Tab>
                        </TabList>
                    </div>

                    {{-- Config Panel --}}
                    <TabPanels class="!p-0 !bg-transparent">
                        <TabPanel v-for="(group, key) in configurations" :key="key" :value="key" class="p-0">
                            <div class="card p-0 overflow-hidden">
                                <x-admin::form v-slot="{ meta, errors, handleSubmit }" as="div">
                                    <form @submit="handleSubmit($event, () => {submitForm(group);})">
                                        <div class="px-5 py-4 border-b border-[var(--border)] flex flex-col sm:flex-row sm:items-center justify-between gap-4 sm:gap-0">
                                            <div>   
                                                <div class="text-[14px] font-semibold text-[var(--text-base)]">@{{ group.title }}</div>
                                                <div v-if="group.info" class="text-[11px] text-[var(--text-muted)] mt-0.5">@{{ group.info }}</div>
                                            </div>
                                            <Button label="Save Settings" size="small" type="submit" :loading="isSaving" class="w-full sm:w-auto" />
                                        </div>
                                        <div class="p-5 flex flex-col gap-6">
                                            <div v-for="section in group.sections" :key="section.name" class="flex flex-col rounded-lg border border-[var(--border)]/60 shadow-sm overflow-hidden mb-2">
                                                <div class="flex items-center gap-3 bg-[var(--surface-50)] p-3 border-b border-[var(--border)]/60">
                                                    <div class="w-8 h-8 rounded-md bg-[var(--surface-0)] border border-[var(--border)] text-[var(--accent)] flex items-center justify-center flex-shrink-0 shadow-sm">
                                                        <i :class="section.icon || 'pi pi-folder'" class="text-[14px]"></i>
                                                    </div>
                                                    <div>
                                                        <div v-if="section.title" class="text-[13px] font-bold text-[var(--text-base)]">@{{ section.title }}</div>
                                                        <div v-if="section.info" class="text-[11px] text-[var(--text-muted)] mt-0.5">@{{ section.info }}</div>
                                                    </div>
                                                </div>
                                                
                                                <div class="p-4 flex flex-col gap-4 bg-[var(--surface-0)]">
                                                    <div v-for="field in section.fields" :key="field.name" class="flex flex-col gap-1">
                                                    <template v-if="field.type === 'boolean'">
                                                        <div class="flex items-center justify-between py-2">
                                                            <div>
                                                                <div class="text-[13px] font-medium text-[var(--text-base)]">@{{ field.title }}</div>
                                                                <div class="text-[11px] text-[var(--text-muted)] mt-0.5" v-if="field.info">@{{ field.info }}</div>
                                                            </div>
                                                            <x-admin::form.control-group>
                                                                <x-admin::form.control-group.control
                                                                    type="switch"
                                                                    ::name="field.name"
                                                                    ::inputId="'switch_' + field.name.replace(/\./g, '_')"
                                                                    v-model="formData[field.name]"
                                                                />
                                                            </x-admin::form.control-group>
                                                        </div>
                                                    </template>
                                                    <template v-else-if="['audio', 'image', 'file'].includes(field.type)">
                                                        <div class="mb-2">
                                                            <label class="block text-[13px] font-medium text-[var(--text-base)] mb-1">@{{ field.title }}</label>

                                                            <x-admin::form.control-group> 
                                                                <x-admin::form.control-group.control
                                                                    type="file"
                                                                    ::name="field.name" 
                                                                    class="pl-2"
                                                                    ::rules="field?.validation" 
                                                                    ::accept="field?.accept"
                                                                    ::id="'file_' + field.name.replace(/\./g, '_')"
                                                                />
                                                            </x-admin::form.control-group> 
                                                            <div class="text-[11px] text-[var(--text-muted)] mb-2" v-if="field.info">@{{ field.info }}</div>

                                                            <div v-if="initialSettings[field.name]" class="mt-2">
                                                                <audio v-if="field.type === 'audio'" controls class="h-10">
                                                                    <source :src="'/storage/' + initialSettings[field.name]">
                                                                </audio>

                                                                <img v-else-if="field.type === 'image'" :src="'/storage/' + initialSettings[field.name]" class="h-20 rounded shadow-sm border border-gray-200">
                                                                
                                                                <a v-else :href="'/storage/' + initialSettings[field.name]" target="_blank" class="text-sm font-medium text-blue-600 hover:underline">View Current File</a>
                                                            </div>
                                                        </div>
                                                    </template>
                                                    <template v-else>
                                                        <div v-if="field.title" class="text-[11px] text-[var(--text-muted)] mt-1">@{{ field.title }}</div> 

                                                        <x-admin::form.control-group class="mb-0">
                                                            <x-admin::form.control-group.control
                                                                type="text"
                                                                ::name="field.name"
                                                                v-model="formData[field.name]"
                                                                ::label="field.title"
                                                            />
                                                        </x-admin::form.control-group>
                                                    </template>
                                                </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </x-admin::form>
                            </div>
                        </TabPanel>
                    </TabPanels>
                </div>
            </Tabs>
        </div>
    </script>

    <script type="module">
        adminVueApp.component('v-config', {
            template: '#v-config-template',

            props: {
                initialSettings: {
                    type: Object,
                    default: () => ({})
                },
                configurations: {
                    type: Object,
                    default: () => ({})
                },
            },

            data() {
                let firstKey = "{{ $activeGroup }}";

                console.log('firstKey', firstKey);

                if (!!!firstKey) {
                    firstKey = Object.keys(this.configurations)[0] || '';
                }

                return {
                    activeGroup: firstKey,
                    isSaving: false,
                    formData: {}
                };
            },

            mounted() {
                for (const groupKey in this.configurations) {
                    const group = this.configurations[groupKey];
                    if (group.sections) {
                        group.sections.forEach(section => {
                            if (section.fields) {
                                section.fields.forEach(field => {
                                    let val = this.initialSettings[field.name];
                                    if (val === undefined) {
                                        val = field.default !== undefined ? field.default : null;
                                    }
                                    if (field.type === 'boolean') {
                                        val = (val === '1' || val === 1 || val === true || val === 'true');
                                    }
                                    this.formData[field.name] = val;
                                });
                            }
                        });
                    }
                }
            },

            methods: {
                submitForm(group) {
                    this.saveConfig(group);
                },

                async loadConfigData() {
                    window.location.href = "{{ route('admin.settings.index') }}?active_group=" + this.activeGroup;

                    // this.$axios.get("{{ route('admin.settings.index') }}").then(response => {
                    //     this.initialSettings = response.data.data;
                    // }).catch(error => {
                    //     console.log(error);
                    //     // window.location.reload();
                    // });
                },

                saveConfig(group) {
                    let formData = new FormData();

                    if (group && group.sections) {
                        group.sections.forEach(section => {
                            if (section.fields) {
                                section.fields.forEach(field => {
                                    if (['audio', 'image', 'file'].includes(field.type)) {
                                        const fileInput = document.getElementById('file_' + field.name.replace(/\./g, '_'));
                                        if (fileInput && fileInput.files && fileInput.files[0]) {
                                            formData.append('settings[' + field.name + ']', fileInput.files[0]);
                                        }
                                    } else {
                                        let val = this.formData[field.name];
                                        if (field.type === 'boolean') {
                                            val = val ? 1 : 0;
                                        }
                                        if (val !== null && val !== undefined) {
                                            formData.append('settings[' + field.name + ']', val);
                                        }
                                    }
                                });
                            }
                        });
                    }

                    this.isSaving = true;

                    const storeUrl = "{{ route('admin.settings.store') }}";
                    this.$axios.post(storeUrl, formData, {
                            headers: {
                                'Content-Type': 'multipart/form-data'
                            }
                        })
                        .then(response => {
                            this.isSaving = false;
                            const msg = response?.data?.message || 'Configuration saved successfully!';

                            this.$emitter.emit('add-flash', {
                                type: 'success',
                                message: msg
                            });
                        })
                        .catch(error => {
                            this.$helpers.errorControl(error);
                        }).finally(() => {
                            this.isSaving = false;

                            this.loadConfigData();
                        });
                }
            },
        });
    </script>
    @endPushOnce
</x-admin::layouts>