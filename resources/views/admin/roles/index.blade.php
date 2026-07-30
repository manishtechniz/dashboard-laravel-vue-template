<x-admin::layouts>
    <div class="page-header">
        <h1 class="page-title">Roles & Permissions</h1>
        <div class="page-breadcrumb">Home / Roles & Permissions</div>
    </div>

    @php
    $rawAcl = config('acl', []);
    $permissionGroups = [];

    foreach ($rawAcl as $groupKey => $subGroups) {
    $groupName = ucwords(str_replace(['_', '-'], ' ', $groupKey));
    $permissions = [];

    if (is_array($subGroups)) {
    foreach ($subGroups as $item) {
    if (is_array($item) && isset($item['key'])) {
    $routeVal = $item['route'];
    $allRoutes = is_array($routeVal) ? $routeVal : [$routeVal];

    $permissions[] = [
    'key' => $item['key'],
    'name' => $item['name'],
    'sort' => $item['sort'] ?? 999,
    'description' => $item['description'] ?? '',
    'route' => $routeVal,
    'all_routes' => $allRoutes,
    ];
    }
    }
    }

    usort($permissions, fn($a, $b) => ($a['sort'] ?? 0) <=> ($b['sort'] ?? 0));

        if (!empty($permissions)) {
        $permissionGroups[] = [
        'name' => $groupName,
        'permissions' => $permissions,
        ];
        }
        }
        @endphp

        <v-roles
            :initial-permission-groups="{{ json_encode($permissionGroups) }}"
            :initial-roles="{{ json_encode($roles ?? []) }}"></v-roles>

        @pushOnce('scripts')
        <script type="text/x-template" id="v-roles-template">
            <div>
                
    {{-- Header actions --}}
    @if (hasPermission('admin.roles.store'))
        <div style="display:flex; justify-content:flex-end; margin-bottom:16px; gap:10px;">
            <Button label="Create Role" icon="pi pi-plus" @click="showRoleDialog = true" />
        </div>
    @endif

    <div style="display:grid; grid-template-columns: 1fr 2fr; gap:16px;">
        {{-- Roles List --}}
        <div class="card" style="padding:0; overflow:hidden;">
            <div style="padding:16px 20px; border-bottom:1px solid var(--border); font-size:14px; font-weight:600; color:var(--text-base); display:flex; justify-content:space-between; align-items:center;">
                <span>All Roles</span>
                <Tag :value="`${roles.length} roles`" severity="secondary" style="font-size:11px;" />
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
                <div style="display:flex; align-items:center; justify-content:space-between; gap:8px;">
                    <div style="flex:1;">
                        <div style="font-weight:500; font-size:13.5px; color:var(--text-base);">@{{ role.name }}</div>
                        <div style="font-size:12px; color:var(--text-muted); margin-top:2px;">
                            @{{ role.description || (role.permissions && role.permissions.includes('*') ? 'All permissions' : (role.permissions ? role.permissions.length : 0) + ' permissions') }}
                        </div>
                    </div>
                    <div style="display:flex; align-items:center; gap:6px;">
                        <!-- <template v-if="role.type !== 'system'">
                            <Button icon="pi pi-pencil" severity="secondary" text rounded style="width:26px; height:26px; padding:0;" title="Rename Role" @click.stop="openEditRoleDialog(role)" />
                            <Button icon="pi pi-trash" severity="danger" text rounded style="width:26px; height:26px; padding:0;" title="Delete Role" @click.stop="confirmDeleteRole(role)" />
                        </template> -->
                        <Tag :value="role.type || 'custom'" :severity="role.type === 'system' ? 'warning' : 'info'" style="font-size:10px;" />
                    </div>
                </div>
            </div>
        </div>

        {{-- Permissions Matrix --}}
        <div class="card" style="padding:0; overflow:hidden;">
            <div style="padding:16px 20px; border-bottom:1px solid var(--border); display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap;">
                <div style="font-size:14px; font-weight:600; color:var(--text-base); display:flex; align-items:center; gap:8px;">
                    <span>Permissions - @{{ selectedRole?.name || 'Select a role' }}</span>
                    <template v-if="selectedRole && selectedRole.type !== 'system'">
                        <Button icon="pi pi-pencil" severity="secondary" text rounded style="width:24px; height:24px; padding:0;" title="Rename Role" @click="openEditRoleDialog(selectedRole)" />
                        <Button icon="pi pi-trash" severity="danger" text rounded style="width:24px; height:24px; padding:0;" title="Delete Role" @click="confirmDeleteRole(selectedRole)" />
                    </template>
                </div>
                @if (hasPermission('admin.roles.update'))
                    <div v-if="selectedRole" style="display:flex; align-items:center; gap:10px;">
                        <InputText v-model="permissionSearch" placeholder="Search permissions..." size="small" style="width:180px;" />
                        <Button 
                            label="Save Changes" 
                            size="small" 
                            :disabled="selectedRole.type === 'system'" 
                            @click="savePermissions" 
                        />
                    </div>
                @endif
            </div>

            <div v-if="selectedRole" style="padding:16px;">
                <div v-if="selectedRole.type === 'system'" style="padding:10px 14px; background:var(--bg-subtle); border-radius:8px; border:1px solid var(--border); margin-bottom:16px; font-size:12px; color:var(--text-muted); display:flex; align-items:center; gap:8px;">
                    <i class="pi pi-lock" style="font-size:14px; color:var(--accent);"></i>
                    This is a system role. System role permissions and role details cannot be edited or deleted.
                </div>

                <div v-for="group in filteredPermissionGroups" :key="group.name" style="margin-bottom:24px;">
                    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:10px;">
                        <div style="font-size:11px; font-weight:700; letter-spacing:0.5px; text-transform:uppercase; color:var(--text-muted); display:flex; align-items:center; gap:8px;">
                            <span>@{{ group.name }}</span>
                            <span style="font-size:11px; font-weight:500; color:var(--text-muted);">
                                (@{{ getGroupSelectedCount(group) }}/@{{ group.permissions.length }})
                            </span>
                        </div>
                        <Button 
                            v-if="selectedRole.type !== 'system'"
                            :label="isGroupAllSelected(group) ? 'Deselect All' : 'Select All'" 
                            size="small" 
                            text 
                            style="font-size:11px; padding:2px 8px;"
                            @click="toggleGroup(group)"
                        />
                    </div>
                    <div style="display:grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap:10px;">
                        <label 
                            v-for="perm in group.permissions" :key="perm.key"
                            style="display:flex; align-items:center; gap:10px; padding:10px 12px; background:var(--bg-subtle); border-radius:8px; border:1px solid var(--border); cursor:pointer; transition:border-color 0.15s ease;"
                            :style="{ borderColor: selectedPerms[perm.key] ? 'var(--accent)' : 'var(--border)' }"
                        >
                            <Checkbox 
                                :binary="true" 
                                v-model="selectedPerms[perm.key]"
                                :disabled="selectedRole.type === 'system'" 
                            />
                            <div style="flex:1;">
                                <div style="font-size:12.5px; font-weight:500; color:var(--text-base);">@{{ perm.name }}</div>
                                <div style="font-size:11px; color:var(--text-muted); font-family:monospace; margin-top:2px;">
                                    @{{ perm?.description || perm?.key }}
                                </div>
                            </div>
                        </label>
                    </div>
                </div>

                <div v-if="filteredPermissionGroups.length === 0" style="padding:32px; text-align:center; color:var(--text-muted); font-size:13px;">
                    No permissions found matching "@{{ permissionSearch }}"
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
                <InputText v-model="newRole.name" placeholder="e.g. Content Manager" style="width:100%;" />
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
            <Button label="Create Role" @click="createRole" :disabled="!newRole.name.trim()" />
        </template>
    </Dialog>

    {{-- Edit / Rename Role Dialog --}}
    <Dialog v-model:visible="showEditRoleDialog" header="Rename & Edit Role" :style="{ width: '580px', maxWidth: '95vw' }" modal>
        <div style="display:flex; flex-direction:column; gap:16px; padding-top:8px;">
            <div>
                <label style="font-size:12px; font-weight:600; color:var(--text-muted); display:block; margin-bottom:6px;">Role Name</label>
                <InputText v-model="editRoleData.name" placeholder="e.g. Operations Manager" style="width:100%;" />
            </div>
            <div>
                <label style="font-size:12px; font-weight:600; color:var(--text-muted); display:block; margin-bottom:6px;">Description</label>
                <Textarea v-model="editRoleData.description" placeholder="Describe this role..." rows="3" style="width:100%;" />
            </div>
        </div>
        <template #footer>
            <Button label="Cancel" severity="secondary" text @click="showEditRoleDialog = false" />
            <Button label="Save Changes" @click="updateRoleDetails" :disabled="!editRoleData.name.trim()" />
        </template>
    </Dialog>
</div>
</script>

        <script type="module">
            adminVueApp.component('v-roles', {
                template: '#v-roles-template',

                props: {
                    initialPermissionGroups: {
                        type: Array,
                        default: () => []
                    },
                    initialRoles: {
                        type: Array,
                        default: () => []
                    }
                },

                data() {
                    const defaultRoles = [];

                    return {
                        showRoleDialog: false,
                        showEditRoleDialog: false,
                        selectedRole: null,
                        selectedPerms: {},
                        permissionSearch: '',
                        permissionGroups: this.initialPermissionGroups,
                        newRole: {
                            name: '',
                            description: '',
                            copyFrom: null
                        },
                        editRoleData: {
                            id: null,
                            name: '',
                            description: ''
                        },
                        roles: this.initialRoles && this.initialRoles.length > 0 ? this.initialRoles : defaultRoles
                    };
                },

                computed: {
                    filteredPermissionGroups() {
                        if (!this.permissionSearch.trim()) {
                            return this.permissionGroups;
                        }
                        const query = this.permissionSearch.toLowerCase().trim();
                        return this.permissionGroups.map(group => {
                            const filteredPerms = group.permissions.filter(p =>
                                p.name.toLowerCase().includes(query) ||
                                p.key.toLowerCase().includes(query) ||
                                (p.all_routes && p.all_routes.some(r => r.toLowerCase().includes(query)))
                            );
                            return {
                                name: group.name,
                                permissions: filteredPerms
                            };
                        }).filter(group => group.permissions.length > 0);
                    }
                },

                mounted() {
                    if (this.roles.length > 0) {
                        this.selectRole(this.roles[0]);
                    }
                },

                methods: {
                    selectRole(role) {
                        this.selectedRole = role;
                        const newSelectedPerms = {};

                        const rolePermissions = Array.isArray(role.permissions) ? role.permissions : [];

                        this.permissionGroups.forEach(group => {
                            group.permissions.forEach(p => {
                                const isChecked = rolePermissions.includes('*') ||
                                    rolePermissions.includes(p.key) ||
                                    (p.all_routes && p.all_routes.some(r => rolePermissions.includes(r))) ||
                                    rolePermissions.some(r => typeof r === 'string' && r.endsWith('.*') && p.key.startsWith(r.split('.')[0]));
                                newSelectedPerms[p.key] = isChecked;
                            });
                        });

                        this.selectedPerms = newSelectedPerms;
                    },

                    isGroupAllSelected(group) {
                        if (!group.permissions || group.permissions.length === 0) return false;
                        return group.permissions.every(p => this.selectedPerms[p.key]);
                    },

                    getGroupSelectedCount(group) {
                        if (!group.permissions) return 0;
                        return group.permissions.filter(p => this.selectedPerms[p.key]).length;
                    },

                    toggleGroup(group) {
                        if (this.selectedRole?.type === 'system') return;
                        const allSelected = this.isGroupAllSelected(group);
                        group.permissions.forEach(p => {
                            this.selectedPerms[p.key] = !allSelected;
                        });
                    },

                    savePermissions() {
                        if (!this.selectedRole) return;
                        if (this.selectedRole.type === 'system') {
                            alert('System roles cannot be modified.');
                            return;
                        }

                        const activePermissions = [];
                        this.permissionGroups.forEach(group => {
                            group.permissions.forEach(p => {
                                if (this.selectedPerms[p.key]) {
                                    if (p.all_routes && p.all_routes.length > 0) {
                                        p.all_routes.forEach(r => {
                                            if (!activePermissions.includes(r)) {
                                                activePermissions.push(r);
                                            }
                                        });
                                    } else if (!activePermissions.includes(p.key)) {
                                        activePermissions.push(p.key);
                                    }
                                }
                            });
                        });

                        this.selectedRole.permissions = activePermissions;

                        const updateUrl = "{{ route('admin.roles.update', ':id') }}".replace(':id', this.selectedRole.id);

                        if (this.$axios) {
                            this.$axios.post(updateUrl, {
                                permissions: activePermissions
                            }).then(response => {
                                const msg = response?.data?.message || `Permissions updated for "${this.selectedRole.name}"`;
                                if (this.$emitter) {
                                    this.$emitter.emit('add-flash', {
                                        type: 'success',
                                        message: msg
                                    });
                                }
                            }).catch(error => {
                                console.error(error);
                                const errorMsg = error?.response?.data?.message || 'Failed to update role permissions.';
                                if (this.$emitter) {
                                    this.$emitter.emit('add-flash', {
                                        type: 'error',
                                        message: errorMsg
                                    });
                                }
                            });
                        } else {
                            if (this.$emitter) {
                                this.$emitter.emit('add-flash', {
                                    type: 'success',
                                    message: `Permissions updated for "${this.selectedRole.name}"`
                                });
                            }
                        }
                    },

                    createRole() {
                        if (!this.newRole.name.trim()) return;

                        let initialPermissions = [];
                        if (this.newRole.copyFrom) {
                            const sourceRole = this.roles.find(r => r.id === this.newRole.copyFrom);
                            if (sourceRole) {
                                initialPermissions = [...(sourceRole.permissions || [])];
                            }
                        }

                        const payload = {
                            name: this.newRole.name.trim(),
                            description: this.newRole.description,
                            type: 'custom',
                            permissions: initialPermissions
                        };

                        const storeUrl = "{{ route('admin.roles.store') }}";

                        if (this.$axios) {
                            this.$axios.post(storeUrl, payload).then(response => {
                                const createdRole = response?.data?.data || {
                                    id: Date.now(),
                                    ...payload,
                                    userCount: 0
                                };

                                this.roles.push(createdRole);
                                this.showRoleDialog = false;
                                this.newRole.name = '';
                                this.newRole.description = '';
                                this.newRole.copyFrom = null;

                                this.selectRole(createdRole);

                                const msg = response?.data?.message || `Role "${createdRole.name}" created successfully!`;
                                if (this.$emitter) {
                                    this.$emitter.emit('add-flash', {
                                        type: 'success',
                                        message: msg
                                    });
                                }
                            }).catch(error => {
                                console.error(error);
                                const errorMsg = error?.response?.data?.message || 'Failed to create role.';
                                if (this.$emitter) {
                                    this.$emitter.emit('add-flash', {
                                        type: 'error',
                                        message: errorMsg
                                    });
                                }
                            });
                        } else {
                            const createdRole = {
                                id: Date.now(),
                                ...payload,
                                userCount: 0
                            };

                            this.roles.push(createdRole);
                            this.showRoleDialog = false;
                            this.newRole.name = '';
                            this.newRole.description = '';
                            this.newRole.copyFrom = null;

                            this.selectRole(createdRole);

                            if (this.$emitter) {
                                this.$emitter.emit('add-flash', {
                                    type: 'success',
                                    message: `Role "${createdRole.name}" created successfully!`
                                });
                            }
                        }
                    },

                    openEditRoleDialog(role) {
                        if (role.type === 'system') {
                            alert('System roles cannot be renamed or modified.');
                            return;
                        }
                        this.editRoleData = {
                            id: role.id,
                            name: role.name,
                            description: role.description || ''
                        };
                        this.showEditRoleDialog = true;
                    },

                    updateRoleDetails() {
                        if (!this.editRoleData.name.trim()) return;

                        const updateUrl = "{{ route('admin.roles.update', ':id') }}".replace(':id', this.editRoleData.id);
                        const payload = {
                            name: this.editRoleData.name.trim(),
                            description: this.editRoleData.description
                        };

                        if (this.$axios) {
                            this.$axios.post(updateUrl, payload).then(response => {
                                const updatedRole = this.roles.find(r => r.id === this.editRoleData.id);
                                if (updatedRole) {
                                    updatedRole.name = payload.name;
                                    updatedRole.description = payload.description;
                                }
                                this.showEditRoleDialog = false;
                                const msg = response?.data?.message || `Role updated successfully!`;
                                if (this.$emitter) {
                                    this.$emitter.emit('add-flash', {
                                        type: 'success',
                                        message: msg
                                    });
                                }
                            }).catch(error => {
                                console.error(error);
                                const errorMsg = error?.response?.data?.message || 'Failed to update role name.';
                                if (this.$emitter) {
                                    this.$emitter.emit('add-flash', {
                                        type: 'error',
                                        message: errorMsg
                                    });
                                }
                            });
                        } else {
                            const updatedRole = this.roles.find(r => r.id === this.editRoleData.id);
                            if (updatedRole) {
                                updatedRole.name = payload.name;
                                updatedRole.description = payload.description;
                            }
                            this.showEditRoleDialog = false;
                            if (this.$emitter) {
                                this.$emitter.emit('add-flash', {
                                    type: 'success',
                                    message: `Role updated successfully!`
                                });
                            }
                        }
                    },

                    confirmDeleteRole(role) {
                        if (role.type === 'system') {
                            alert('System roles cannot be deleted.');
                            return;
                        }

                        if (!confirm(`Are you sure you want to delete the role "${role.name}"?`)) {
                            return;
                        }

                        const deleteUrl = "{{ route('admin.roles.delete', ':id') }}".replace(':id', role.id);

                        const performDelete = () => {
                            const index = this.roles.findIndex(r => r.id === role.id);
                            if (index !== -1) {
                                this.roles.splice(index, 1);
                            }
                            if (this.selectedRole?.id === role.id) {
                                this.selectedRole = this.roles.length > 0 ? this.roles[0] : null;
                                if (this.selectedRole) {
                                    this.selectRole(this.selectedRole);
                                }
                            }
                        };

                        if (this.$axios) {
                            this.$axios.delete(deleteUrl).then(response => {
                                performDelete();
                                const msg = response?.data?.message || `Role "${role.name}" deleted successfully.`;
                                if (this.$emitter) {
                                    this.$emitter.emit('add-flash', {
                                        type: 'success',
                                        message: msg
                                    });
                                }
                            }).catch(error => {
                                console.error(error);
                                const errorMsg = error?.response?.data?.message || 'Failed to delete role.';
                                if (this.$emitter) {
                                    this.$emitter.emit('add-flash', {
                                        type: 'error',
                                        message: errorMsg
                                    });
                                }
                            });
                        } else {
                            performDelete();
                            if (this.$emitter) {
                                this.$emitter.emit('add-flash', {
                                    type: 'success',
                                    message: `Role "${role.name}" deleted successfully.`
                                });
                            }
                        }
                    }
                }
            });
        </script>
        @endPushOnce
</x-admin::layouts>