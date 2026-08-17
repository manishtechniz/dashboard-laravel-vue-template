<x-admin::layouts>

    <div class="page-header">
        <h1 class="page-title">Settings</h1>
        <div class="page-breadcrumb">Home / Settings</div>
    </div>

    <v-config :initial-settings="{{ json_encode($settings ?? (object)[]) }}"></v-config>

    @pushOnce('scripts')
    <script type="text/x-template" id="v-config-template">
        <div>

    <Tabs v-model:value="activeGroup">
        <div class="flex flex-col md:grid md:grid-cols-[240px_1fr] gap-5">
            {{-- Config Groups Sidebar (Desktop) --}}
            <div class="card p-3 hidden md:block">
                <div v-for="group in configGroups" :key="group.key"
                    @click="activeGroup = group.key"
                    :class="['px-3 py-2.5 rounded-lg cursor-pointer mb-1 flex items-center gap-2 text-[13px] transition-colors',
                        activeGroup === group.key 
                            ? 'bg-[var(--accent-light)] text-[var(--accent)] font-semibold' 
                            : 'bg-transparent text-[var(--text-base)] font-normal hover:bg-gray-100'
                    ]"
                >
                    <i :class="[group.icon, 'text-[14px]']"></i>
                    @{{ group.label }}
                </div>
            </div>

            {{-- Mobile Tabs List --}}
            <div class="block md:hidden">
                <TabList>
                    <Tab v-for="group in configGroups" :key="group.key" :value="group.key" class="flex items-center gap-2!">
                        <i :class="[group.icon, 'text-[14px]']"></i>
                        @{{ group.label }}
                    </Tab>
                </TabList>
            </div>

            {{-- Config Panel --}}
            <TabPanels class="!p-0 !bg-transparent">
                {{-- Feature Flags --}}
                <TabPanel value="features" class="p-0">
                    <div class="card p-0 overflow-hidden">
                <x-admin::form v-slot="{ meta, errors, handleSubmit }" as="div">
                    <form @submit="handleSubmit($event, submitForm)">
                        <div class="px-5 py-4 border-b border-[var(--border)] flex flex-col sm:flex-row sm:items-center justify-between gap-4 sm:gap-0">
                            <div class="text-[14px] font-semibold text-[var(--text-base)]">Feature Flags</div>
                            <Button label="Save Feature Flags" size="small" type="submit" :loading="isSaving" class="w-full sm:w-auto" />
                        </div>
                        <div class="p-5 flex flex-col gap-0.5">
                            <div v-for="feat in featureFlags" :key="feat.key"
                                class="flex flex-col sm:flex-row sm:items-center justify-between py-3.5 border-b border-[var(--border)] gap-4 sm:gap-0">
                                <div>
                                    <div class="text-[13.5px] font-medium text-[var(--text-base)]">@{{ feat.label }}</div>
                                    <div class="text-[12px] text-[var(--text-muted)] mt-0.5">@{{ feat.description }}</div>
                                    <div class="mt-1.5 flex gap-1.5 flex-wrap">
                                        <Tag v-for="tenant in feat.availableFor" :key="tenant"
                                            :value="tenant" severity="secondary" class="text-[10px]" />
                                    </div>
                                </div>
                                <div class="flex flex-col items-start sm:items-center gap-1 shrink-0">
                                    <x-admin::form.control-group class="mb-0">
                                        <x-admin::form.control-group.control
                                            type="switch"
                                            ::name="'feat_' + feat.key"
                                            ::inputId="'feat_' + feat.key"
                                            ::for="'feat_' + feat.key"
                                            v-model="feat.enabled"
                                        />
                                    </x-admin::form.control-group>
                                    <span class="text-[10px] text-[var(--text-muted)] -mt-1">@{{ feat.enabled ? 'ON' : 'OFF' }}</span>
                                </div>
                            </div>
                        </div>
                        </form>
                    </x-admin::form>
                </div>
            </TabPanel>

            {{-- API Settings --}}
            <TabPanel value="api" class="p-0">
                <div class="card p-5">
                <x-admin::form v-slot="{ meta, errors, handleSubmit }" as="div">
                    <form @submit="handleSubmit($event, submitForm)">
                        <div class="text-[14px] font-semibold text-[var(--text-base)] mb-4">API & Integration Settings</div>
                        <div class="flex flex-col gap-6 mt-2">
                            <div v-for="setting in apiSettings" :key="setting.key">
                                <x-admin::form.control-group class="mb-0">
                                    <x-admin::form.control-group.control
                                        ::type="setting.secret ? 'password' : 'text'"
                                        ::name="'api_' + setting.key"
                                        v-model="setting.value"
                                        ::label="setting.label"
                                    />
                                </x-admin::form.control-group>
                                <div class="text-[11px] text-[var(--text-muted)] mt-1">@{{ setting.description }}</div>
                            </div>
                            <div class="flex justify-end mt-2">
                                <Button label="Save API Settings" type="submit" :loading="isSaving" class="w-full sm:w-auto" />
                            </div>
                        </div>
                        </form>
                    </x-admin::form>
                </div>
            </TabPanel>

            {{-- Email Settings --}}
            <TabPanel value="email" class="p-0">
                <div class="card p-5">
                <x-admin::form v-slot="{ meta, errors, handleSubmit }" as="div">
                    <form @submit="handleSubmit($event, submitForm)">
                        <div class="text-[14px] font-semibold text-[var(--text-base)] mb-4">Email Configuration</div>
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 gap-y-6 mt-2">
                            <x-admin::form.control-group class="mb-0">
                                <x-admin::form.control-group.control
                                    type="text"
                                    name="smtp_host"
                                    v-model="email.host"
                                    label="SMTP Host"
                                    placeholder="smtp.gmail.com"
                                />
                            </x-admin::form.control-group>
                            <x-admin::form.control-group class="mb-0">
                                <x-admin::form.control-group.control
                                    type="text"
                                    name="smtp_port"
                                    v-model="email.port"
                                    label="SMTP Port"
                                    placeholder="587"
                                />
                            </x-admin::form.control-group>
                            <x-admin::form.control-group class="mb-0">
                                <x-admin::form.control-group.control
                                    type="text"
                                    name="smtp_username"
                                    v-model="email.username"
                                    label="Username"
                                />
                            </x-admin::form.control-group>
                            <x-admin::form.control-group class="mb-0">
                                <x-admin::form.control-group.control
                                    type="password"
                                    name="smtp_password"
                                    v-model="email.password"
                                    label="Password"
                                />
                            </x-admin::form.control-group> 
                        </div>

                        <div class="flex gap-2 justify-end">
                            <Button label="Test" type="button" severity="secondary" icon="pi pi-send" class="w-full sm:w-auto" />
                            <Button label="Save" type="submit" :loading="isSaving" class="w-full sm:w-auto" />
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
                }
            },

            data() {
                return {
                    activeGroup: 'features',
                    isSaving: false,

                    showTenantDialog: false,
                    newTenant: {
                        name: '',
                        domain: '',
                        plan: 'Starter',
                    },

                    configGroups: [{
                            key: 'features',
                            label: 'Feature Flags',
                            icon: 'pi pi-flag'
                        },
                        {
                            key: 'api',
                            label: 'API & Integrations',
                            icon: 'pi pi-server'
                        },
                        {
                            key: 'email',
                            label: 'Email Settings',
                            icon: 'pi pi-envelope'
                        },
                    ],

                    featureFlags: [{
                        key: 'advanced_reports',
                        label: 'Advanced Reports',
                        description: 'Enable detailed analytics and custom report builder',
                        enabled: true,
                        availableFor: ['Pro', 'Enterprise']
                    }, ],

                    apiSettings: [{
                            key: 'api_key',
                            label: 'Master API Key',
                            value: 'sk-admin-xxxxxxxxxxxx',
                            secret: true,
                            description: 'Used for server-to-server communication'
                        },
                        {
                            key: 'webhook_secret',
                            label: 'Webhook Secret',
                            value: 'wh_secret_xxxxxxxxx',
                            secret: true,
                            description: 'Used to verify incoming webhook payloads'
                        },
                        {
                            key: 'rate_limit',
                            label: 'Rate Limit (req/min)',
                            value: '1000',
                            secret: false,
                            description: 'API rate limit per tenant per minute'
                        },
                    ],

                    email: {
                        host: 'smtp.mailgun.org',
                        port: '587',
                        username: 'admin@example.com',
                        password: '',
                    },
                };
            },

            mounted() {
                if (this.initialSettings && Object.keys(this.initialSettings).length > 0) {
                    // Hydrate Feature Flags
                    this.featureFlags.forEach(f => {
                        const dbKey = 'feature.' + f.key;
                        if (this.initialSettings[dbKey] !== undefined) {
                            const val = this.initialSettings[dbKey];
                            f.enabled = val === '1' || val === 1 || val === true || val === 'true';
                        }
                    });

                    // Hydrate API Settings
                    this.apiSettings.forEach(s => {
                        if (this.initialSettings['api.' + s.key] !== undefined) {
                            s.value = this.initialSettings['api.' + s.key];
                        }
                    });

                    // Hydrate Email Settings
                    if (this.initialSettings['email.smtp_host'] !== undefined) this.email.host = this.initialSettings['email.smtp_host'];
                    if (this.initialSettings['email.smtp_port'] !== undefined) this.email.port = this.initialSettings['email.smtp_port'];
                    if (this.initialSettings['email.smtp_username'] !== undefined) this.email.username = this.initialSettings['email.smtp_username'];
                    if (this.initialSettings['email.smtp_password'] !== undefined) this.email.password = this.initialSettings['email.smtp_password'];
                }
            },

            methods: {
                submitForm(values, actions) {
                    this.saveConfig();
                },

                saveConfig(customPayload = null) {
                    let payload = customPayload;

                    if (!payload) {
                        payload = {};

                        // Feature flags
                        this.featureFlags.forEach(f => {
                            payload['feature.' + f.key] = f.enabled ? '1' : '0';
                        });

                        // API settings
                        this.apiSettings.forEach(s => {
                            payload['api.' + s.key] = s.value;
                        });

                        // Email settings
                        payload['email.' + 'smtp_host'] = this.email.host;
                        payload['email.' + 'smtp_port'] = this.email.port;
                        payload['email.' + 'smtp_username'] = this.email.username;
                        payload['email.' + 'smtp_password'] = this.email.password;
                    }

                    this.isSaving = true;

                    const storeUrl = "{{ route('admin.settings.store') }}";

                    if (this.$axios) {
                        this.$axios.post(storeUrl, payload)
                            .then(response => {
                                this.isSaving = false;
                                const msg = response?.data?.message || 'Configuration saved successfully!';
                                if (this.$emitter) {
                                    this.$emitter.emit('add-flash', {
                                        type: 'success',
                                        message: msg
                                    });
                                } else {
                                    alert(msg);
                                }
                            })
                            .catch(error => {
                                this.isSaving = false;
                                console.error(error);
                                const msg = error?.response?.data?.message || 'Failed to save configuration.';
                                if (this.$emitter) {
                                    this.$emitter.emit('add-flash', {
                                        type: 'error',
                                        message: msg
                                    });
                                } else {
                                    alert(msg);
                                }
                            });
                    } else {
                        this.isSaving = false;
                        alert('Configuration saved!');
                    }
                },

                createTenant() {
                    if (!this.newTenant.name.trim()) return;

                    this.tenants.push({
                        id: Date.now(),
                        name: this.newTenant.name.trim(),
                        domain: this.newTenant.domain || `${this.newTenant.name.toLowerCase().replace(/\s+/g, '')}.example.com`,
                        plan: this.newTenant.plan || 'Starter',
                        users: 1,
                        status: 'Active',
                        features: ['Dashboard'],
                    });

                    this.showTenantDialog = false;
                    this.newTenant.name = '';
                    this.newTenant.domain = '';
                    this.newTenant.plan = 'Starter';

                    this.saveConfig();
                },

                deleteTenant(tenantId) {
                    if (confirm('Are you sure you want to delete this tenant?')) {
                        this.tenants = this.tenants.filter(t => t.id !== tenantId);
                        this.saveConfig();
                    }
                }
            },
        });
    </script>
    @endPushOnce
</x-admin::layouts>