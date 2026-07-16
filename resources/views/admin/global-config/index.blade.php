<x-admin::layouts>



<div class="page-header">
    <h1 class="page-title">Global Configuration</h1>
    <div class="page-breadcrumb">Home / Configuration</div>
</div>

<v-config></v-config>

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
                    <Button label="Save" size="small" @click="saveConfig" />
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

            {{-- Tenant Settings --}}
            <div v-if="activeGroup === 'tenants'" class="card" style="padding:0; overflow:hidden;">
                <div style="padding:18px 20px; border-bottom:1px solid var(--border); display:flex; align-items:center; justify-content:space-between;">
                    <div style="font-size:14px; font-weight:600; color:var(--text-base);">Tenant Management</div>
                    <Button label="Add Tenant" icon="pi pi-plus" size="small" @click="showTenantDialog = true" />
                </div>
                <DataTable :value="tenants" style="font-size:13px;">
                    <Column field="name" header="Tenant Name">
                        <template #body="{ data }">
                            <div style="font-weight:500; color:var(--text-base);">@{{ data.name }}</div>
                            <div style="font-size:11px; color:var(--text-muted);">@{{ data.domain }}</div>
                        </template>
                    </Column>
                    <Column field="plan" header="Plan">
                        <template #body="{ data }">
                            <Tag :value="data.plan" :severity="data.plan === 'Enterprise' ? 'danger' : data.plan === 'Pro' ? 'warning' : 'info'" style="font-size:11px;" />
                        </template>
                    </Column>
                    <Column field="users" header="Users" />
                    <Column field="status" header="Status">
                        <template #body="{ data }">
                            <Tag :value="data.status" :severity="data.status === 'Active' ? 'success' : 'secondary'" style="font-size:11px;" />
                        </template>
                    </Column>
                    <Column header="Features">
                        <template #body="{ data }">
                            <div style="display:flex; gap:4px; flex-wrap:wrap;">
                                <Tag v-for="f in data.features" :key="f" :value="f" severity="secondary" style="font-size:10px;" />
                            </div>
                        </template>
                    </Column>
                    <Column header="Actions">
                        <template #body>
                            <Button icon="pi pi-cog" size="small" text rounded severity="secondary" />
                        </template>
                    </Column>
                </DataTable>
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
                        <Button label="Save API Settings" @click="saveConfig" />
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
                        <Button label="Save Email Settings" @click="saveConfig" />
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tenant Dialog --}}
    <Dialog v-model:visible="showTenantDialog" header="Add New Tenant" :style="{ width:'460px' }" modal>
        <div style="display:flex; flex-direction:column; gap:14px; padding-top:8px;">
            <div>
                <label style="font-size:12px; font-weight:600; color:var(--text-muted); display:block; margin-bottom:6px;">Tenant Name</label>
                <InputText v-model="newTenant.name" style="width:100%;" placeholder="Acme Corp" />
            </div>
            <div>
                <label style="font-size:12px; font-weight:600; color:var(--text-muted); display:block; margin-bottom:6px;">Domain</label>
                <InputText v-model="newTenant.domain" style="width:100%;" placeholder="acme.example.com" />
            </div>
            <div>
                <label style="font-size:12px; font-weight:600; color:var(--text-muted); display:block; margin-bottom:6px;">Plan</label>
                <Select v-model="newTenant.plan" :options="['Starter','Pro','Enterprise']" style="width:100%;" />
            </div>
        </div>
        <template #footer>
            <Button label="Cancel" severity="secondary" text @click="showTenantDialog = false" />
            <Button label="Create Tenant" @click="createTenant" />
        </template>
    </Dialog>
</div>
</script>

<script type="module">

const { reactive } = window.adminVueApp;

adminVueApp.component('v-config', {
    template: '#v-config-template',

    data() {
        return {
            activeGroup: 'features',

            selectedTenant: null,

            showTenantDialog: false,
            // reactive
            newTenant: ({
                name: '',
                domain: '',
                plan: 'Starter',
            }),

            configGroups: [
                { key:'features', label:'Feature Flags', icon:'pi pi-toggle-on' },
                { key:'tenants', label:'Tenant Management', icon:'pi pi-building' },
                { key:'api', label:'API & Integrations', icon:'pi pi-server' },
                { key:'email', label:'Email Settings', icon:'pi pi-envelope' },
            ],

            tenants: [
                {
                    id:1,
                    name:'Global (Default)',
                    domain:'admin.example.com',
                    plan:'Enterprise',
                    users:1403,
                    status:'Active',
                    features:['All']
                },
                {
                    id:2,
                    name:'Acme Corp',
                    domain:'acme.example.com',
                    plan:'Pro',
                    users:243,
                    status:'Active',
                    features:['Dashboard','Reports']
                },
                {
                    id:3,
                    name:'TechStart',
                    domain:'techstart.example.com',
                    plan:'Starter',
                    users:18,
                    status:'Active',
                    features:['Dashboard']
                },
                {
                    id:4,
                    name:'Beta Inc',
                    domain:'beta.example.com',
                    plan:'Pro',
                    users:91,
                    status:'Inactive',
                    features:['Dashboard','Roles']
                },
            ],

            // reactive
            featureFlags: ([
                {
                    key:'advanced_reports',
                    label:'Advanced Reports',
                    description:'Enable detailed analytics and custom report builder',
                    enabled:true,
                    availableFor:['Pro','Enterprise']
                },
                {
                    key:'multi_currency',
                    label:'Multi-Currency Support',
                    description:'Allow transactions in multiple currencies',
                    enabled:false,
                    availableFor:['Enterprise']
                },
                {
                    key:'api_access',
                    label:'API Access',
                    description:'Enable REST/GraphQL API for tenant integrations',
                    enabled:true,
                    availableFor:['Pro','Enterprise']
                },
                {
                    key:'audit_log',
                    label:'Audit Logging',
                    description:'Full audit trail for all user actions',
                    enabled:true,
                    availableFor:['Enterprise']
                },
                {
                    key:'sso',
                    label:'Single Sign-On (SSO)',
                    description:'SAML/OAuth SSO integration',
                    enabled:false,
                    availableFor:['Enterprise']
                },
                {
                    key:'white_label',
                    label:'White Labelling',
                    description:'Custom branding and domain configuration',
                    enabled:true,
                    availableFor:['Enterprise']
                },
                {
                    key:'import_export',
                    label:'Data Import/Export',
                    description:'Bulk CSV/Excel import and export',
                    enabled:true,
                    availableFor:['Starter','Pro','Enterprise']
                },
                {
                    key:'webhooks',
                    label:'Webhooks',
                    description:'Send events to external URLs',
                    enabled:false,
                    availableFor:['Pro','Enterprise']
                },
            ]),

            // reactive
            apiSettings: ([
                {
                    key:'api_key',
                    label:'Master API Key',
                    value:'sk-admin-xxxxxxxxxxxx',
                    secret:true,
                    description:'Used for server-to-server communication'
                },
                {
                    key:'webhook_secret',
                    label:'Webhook Secret',
                    value:'wh_secret_xxxxxxxxx',
                    secret:true,
                    description:'Used to verify incoming webhook payloads'
                },
                {
                    key:'rate_limit',
                    label:'Rate Limit (req/min)',
                    value:'1000',
                    secret:false,
                    description:'API rate limit per tenant per minute'
                },
            ]),
                // reactive
            email: ({
                host:'smtp.mailgun.org',
                port:'587',
                username:'admin@example.com',
                password:'',
            }),
        };
    },

    methods: {
        saveConfig() {
            alert('Configuration saved!');
        },

        createTenant() {
            this.tenants.push({
                id: Date.now(),
                ...this.newTenant,
                users: 0,
                status: 'Active',
                features: ['Dashboard'],
            });

            this.showTenantDialog = false;
        },
    },
});
</script>
@endPushOnce
</x-admin::layouts>
