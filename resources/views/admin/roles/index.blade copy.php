<x-admin::layouts>
<div class="page-header">
    <h1 class="page-title">Roles & Permissions</h1>
    <div class="page-breadcrumb">Home / Roles & Permissions</div>
</div>

<v-roles></v-roles>

@pushOnce('scripts')
<script type="text/x-template" id="v-roles-template">
<div>
    {{-- Header actions --}}
    <div style="display:flex; justify-content:flex-end; margin-bottom:16px; gap:10px;">
        <Button label="Create Role" icon="pi pi-plus" @click="showRoleDialog = true" />
    </div>

    <div style="display:grid; grid-template-columns: 1fr 2fr; gap:16px;">
        {{-- Roles List --}}
         {{-- <Skeleton name="role-list" :loading="true"> --}}
            <div class="card" style="padding:0; overflow:hidden;">
                <div style="padding:16px 20px; border-bottom:1px solid var(--border); font-size:14px; font-weight:600; color:var(--text-base);">
                    All Roles
                </div>
                <div
                    v-for="role in roles" :key="role.id"
                    @click="selectRole(role)"
                    :style="{
                        padding:'14px 20px', cursor:'pointer', borderBottom:'1px solid var(--border)',
                        background: selectedRole?.id === role.id ? 'var(--accent-light)' : 'transparent',
                        borderLeft: selectedRole?.id === role.id ? '3px solid var(--accent)' : '3px solid transparent',
                        transition:'all 0.15s'
                    }"
                >
                    <div style="display:flex; align-items:center; justify-content:space-between;">
                        <div>
                            <div style="font-weight:500; font-size:13.5px; color:var(--text-base);">@{{ role.name }}</div>
                            <div style="font-size:12px; color:var(--text-muted); margin-top:2px;">@{{ role.userCount }} users</div>
                        </div>
                        <Tag :value="role.type" :severity="role.type === 'system' ? 'warning' : 'info'" style="font-size:10px;" />
                    </div>
                </div>
            </div>
        {{-- </Skeleton> --}}

        {{-- Permissions Matrix --}}
        <div class="card" style="padding:0; overflow:hidden;">
            <div style="padding:16px 20px; border-bottom:1px solid var(--border); display:flex; align-items:center; justify-content:space-between;">
                <div style="font-size:14px; font-weight:600; color:var(--text-base);">
                    Permissions — @{{ selectedRole?.name || 'Select a role' }}
                </div>
                <Button v-if="selectedRole" label="Save Changes" size="small" @click="savePermissions" />
            </div>
            <div v-if="selectedRole" style="padding:16px;">
                <div v-for="group in permissionGroups" :key="group.name" style="margin-bottom:20px;">
                    <div style="font-size:11px; font-weight:700; letter-spacing:1px; text-transform:uppercase; color:var(--text-muted); margin-bottom:10px;">
                        @{{ group.name }}
                    </div>
                    <div style="display:grid; grid-template-columns: repeat(auto-fill, minmax(200px,1fr)); gap:8px;">
                        <div v-for="perm in group.permissions" :key="perm.key"
                            style="display:flex; align-items:center; gap:8px; padding:10px 12px; background:var(--bg-subtle); border-radius:8px; border:1px solid var(--border);">
                            <Checkbox :binary="true" v-model="selectedPerms[perm.key]"
                                :disabled="selectedRole.type === 'system'" />
                            <div>
                                <div style="font-size:12.5px; font-weight:500; color:var(--text-base);">@{{ perm.label }}</div>
                                <div style="font-size:11px; color:var(--text-muted);">@{{ perm.description }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div v-else style="padding:48px; text-align:center; color:var(--text-muted);">
                <i class="pi pi-arrow-left" style="font-size:24px; display:block; margin-bottom:8px;"></i>
                Select a role to manage permissions
            </div>
        </div>
    </div>

    {{-- Create Role Dialog --}}
    <Dialog v-model:visible="showRoleDialog" header="Create New Role" :style="{ width: '580px', maxWidth: '95vw' }" modal>
        <div style="display:flex; flex-direction:column; gap:16px; padding-top:8px;">
            <div>
                <label style="font-size:12px; font-weight:600; color:var(--text-muted); display:block; margin-bottom:6px;">Role Name</label>
                <InputText v-model="newRole.name" placeholder="e.g. Content Editor" style="width:100%;" />
            </div>
            <div>
                <label style="font-size:12px; font-weight:600; color:var(--text-muted); display:block; margin-bottom:6px;">Description</label>
                <Textarea v-model="newRole.description" placeholder="Describe this role..." rows="3" style="width:100%;" />
            </div>
            <div>
                <label style="font-size:12px; font-weight:600; color:var(--text-muted); display:block; margin-bottom:6px;">Copy Permissions From</label>
                <Select v-model="newRole.copyFrom" :options="roles" optionLabel="name" optionValue="id"
                    placeholder="Select a role (optional)" style="width:100%;" />
            </div>
        </div>
        <template #footer>
            <Button label="Cancel" severity="secondary" text @click="showRoleDialog = false" />
            <Button label="Create Role" @click="createRole" />
        </template>
    </Dialog>
</div>
</script>

<script type="module">
adminVueApp.component('v-roles', {
    template: '#v-roles-template',

    data() {
        return {
            showRoleDialog: false,
            selectedRole: null,
            selectedPerms: {},
            newRole: {
                name: '',
                description: '',
                copyFrom: null
            },

            roles: [
                { id:1, name:'Super Admin', type:'system', userCount:2, permissions:['*'] },
                { id:2, name:'Admin', type:'system', userCount:12, permissions:['users.*','roles.*'] },
                { id:3, name:'Manager', type:'custom', userCount:87, permissions:['users.view','users.edit'] },
                { id:4, name:'Editor', type:'custom', userCount:213, permissions:['content.*'] },
                { id:5, name:'Viewer', type:'custom', userCount:1089, permissions:['*.view'] },
            ],

            permissionGroups: [
                {
                    name: 'Users',
                    permissions: [
                        { key:'users.view', label:'View Users', description:'List & read user data' },
                        { key:'users.create', label:'Create Users', description:'Add new users' },
                        { key:'users.edit', label:'Edit Users', description:'Modify user records' },
                        { key:'users.delete', label:'Delete Users', description:'Remove users' },
                    ]
                },
                {
                    name: 'Roles & Permissions',
                    permissions: [
                        { key:'roles.view', label:'View Roles', description:'List roles' },
                        { key:'roles.create', label:'Create Roles', description:'Add new roles' },
                        { key:'roles.edit', label:'Edit Roles', description:'Modify role permissions' },
                        { key:'roles.delete', label:'Delete Roles', description:'Remove custom roles' },
                    ]
                },
                {
                    name: 'Configuration',
                    permissions: [
                        { key:'config.view', label:'View Config', description:'Read global settings' },
                        { key:'config.edit', label:'Edit Config', description:'Modify global settings' },
                        { key:'themes.manage', label:'Manage Themes', description:'Theme provider access' },
                        { key:'tenants.manage', label:'Manage Tenants', description:'Multi-tenant config' },
                    ]
                },
            ]
        };
    },

    methods: {
        selectRole(role) {
            this.selectedRole = role;

            // Reset permissions
            this.permissionGroups.forEach(g => {
                g.permissions.forEach(p => {
                    this.selectedPerms[p.key] =
                        role.permissions.includes('*') ||
                        role.permissions.includes(p.key) ||
                        role.permissions.some(r =>
                            r.endsWith('.*') && p.key.startsWith(r.split('.')[0])
                        );
                });
            });
        },

        savePermissions() {
            alert('Permissions saved! (wire to backend)');
        },

        createRole() {
            this.roles.push({
                id: Date.now(),
                name: this.newRole.name,
                type: 'custom',
                userCount: 0,
                permissions: []
            });

            this.showRoleDialog = false;

            this.newRole.name = '';
            this.newRole.description = '';
            this.newRole.copyFrom = null;
        }
    }
});
</script>
@endPushOnce
</x-admin::layouts>
