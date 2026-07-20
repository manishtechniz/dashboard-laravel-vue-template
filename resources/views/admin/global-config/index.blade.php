<x-admin::layouts>

<div class="page-header">
    <h1 class="page-title">Settings</h1>
    <div class="page-breadcrumb">Home / Settings</div>
</div>

<v-config :initial-settings="{{ json_encode($settings ?? (object)[]) }}"></v-config>

@pushOnce('scripts')
<script type="text/x-template" id="v-config-template">
<div>

    <div style="display:grid; grid-template-columns:240px 1fr; gap:20px;">
        {{-- Config Groups Sidebar --}}
        <div class="card" style="padding:10px;">
            <div v-for="group in configGroups" :key="group.key"
                @click="activeGroup = group.key"
                :style="{
                    padding:'10px 12px', borderRadius:'8px', cursor:'pointer', marginBottom:'2px',
                    background: activeGroup === group.key ? 'var(--accent-light)' : 'transparent',
                    color: activeGroup === group.key ? 'var(--accent)' : 'var(--text-base)',
                    fontWeight: activeGroup === group.key ? '600' : '400',
                    fontSize:'13px', display:'flex', alignItems:'center', gap:'8px',
                }"
            >
                <i :class="group.icon" style="font-size:14px;"></i>
                @{{ group.label }}
            </div>
        </div>

        {{-- Config Panel --}}
        <div>
            {{-- Feature Flags --}}
            <div v-if="activeGroup === 'features'" class="card" style="padding:0; overflow:hidden;">
                <div style="padding:18px 20px; border-bottom:1px solid var(--border); display:flex; align-items:center; justify-content:space-between;">
                    <div style="font-size:14px; font-weight:600; color:var(--text-base);">Feature Flags</div>
                    <Button label="Save Feature Flags" size="small" @click="saveConfig()" :loading="isSaving" />
                </div>
                <div style="padding:20px; display:flex; flex-direction:column; gap:2px;">
                    <div v-for="feat in featureFlags" :key="feat.key"
                        style="display:flex; align-items:center; justify-content:space-between; padding:14px 0; border-bottom:1px solid var(--border);">
                        <div>
                            <div style="font-size:13.5px; font-weight:500; color:var(--text-base);">@{{ feat.label }}</div>
                            <div style="font-size:12px; color:var(--text-muted); margin-top:2px;">@{{ feat.description }}</div>
                            <div style="margin-top:6px; display:flex; gap:6px; flex-wrap:wrap;">
                                <Tag v-for="tenant in feat.availableFor" :key="tenant"
                                    :value="tenant" severity="secondary" style="font-size:10px;" />
                            </div>
                        </div>
                        <div style="display:flex; flex-direction:column; align-items:center; gap:4px; flex-shrink:0;">
                            <ToggleSwitch v-model="feat.enabled" />
                            <span style="font-size:10px; color:var(--text-muted);">@{{ feat.enabled ? 'ON' : 'OFF' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- API Settings --}}
            <div v-if="activeGroup === 'api'" class="card" style="padding:20px;">
                <div style="font-size:14px; font-weight:600; color:var(--text-base); margin-bottom:18px;">API & Integration Settings</div>
                <div style="display:flex; flex-direction:column; gap:16px;">
                    <div v-for="setting in apiSettings" :key="setting.key">
                        <label style="font-size:12px; font-weight:600; color:var(--text-muted); display:block; margin-bottom:6px;">@{{ setting.label }}</label>
                        <InputText v-model="setting.value" style="width:100%;" :type="setting.secret ? 'password' : 'text'" />
                        <div style="font-size:11px; color:var(--text-muted); margin-top:4px;">@{{ setting.description }}</div>
                    </div>
                    <div style="display:flex; justify-content:flex-end;">
                        <Button label="Save API Settings" @click="saveConfig()" :loading="isSaving" />
                    </div>
                </div>
            </div>

            {{-- Email Settings --}}
            <div v-if="activeGroup === 'email'" class="card" style="padding:20px;">
                <div style="font-size:14px; font-weight:600; color:var(--text-base); margin-bottom:18px;">Email Configuration</div>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                    <div>
                        <label style="font-size:12px; font-weight:600; color:var(--text-muted); display:block; margin-bottom:6px;">SMTP Host</label>
                        <InputText v-model="email.host" style="width:100%;" placeholder="smtp.gmail.com" />
                    </div>
                    <div>
                        <label style="font-size:12px; font-weight:600; color:var(--text-muted); display:block; margin-bottom:6px;">SMTP Port</label>
                        <InputText v-model="email.port" style="width:100%;" placeholder="587" />
                    </div>
                    <div>
                        <label style="font-size:12px; font-weight:600; color:var(--text-muted); display:block; margin-bottom:6px;">Username</label>
                        <InputText v-model="email.username" style="width:100%;" />
                    </div>
                    <div>
                        <label style="font-size:12px; font-weight:600; color:var(--text-muted); display:block; margin-bottom:6px;">Password</label>
                        <Password v-model="email.password" style="width:100%;" :feedback="false" toggleMask />
                    </div>
                    <div style="grid-column:1/-1; display:flex; justify-content:space-between; align-items:center;">
                        <Button label="Send Test Email" severity="secondary" outlined icon="pi pi-send" />
                        <Button label="Save Email Settings" @click="saveConfig()" :loading="isSaving" />
                    </div>
                </div>
            </div>
        </div>
    </div> 
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

            configGroups: [
                { key:'features', label:'Feature Flags', icon:'pi pi-flag' }, 
                { key:'api', label:'API & Integrations', icon:'pi pi-server' },
                { key:'email', label:'Email Settings', icon:'pi pi-envelope' },
            ], 

            featureFlags: [
                {
                    key: 'advanced_reports',
                    label: 'Advanced Reports',
                    description: 'Enable detailed analytics and custom report builder',
                    enabled: true,
                    availableFor: ['Pro','Enterprise']
                }, 
            ],

            apiSettings: [
                {
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
                if (this.initialSettings['api.'+s.key] !== undefined) {
                    s.value = this.initialSettings['api.'+s.key];
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
                            this.$emitter.emit('add-flash', { type: 'success', message: msg });
                        } else {
                            alert(msg);
                        }
                    })
                    .catch(error => {
                        this.isSaving = false;
                        console.error(error);
                        const msg = error?.response?.data?.message || 'Failed to save configuration.';
                        if (this.$emitter) {
                            this.$emitter.emit('add-flash', { type: 'error', message: msg });
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

