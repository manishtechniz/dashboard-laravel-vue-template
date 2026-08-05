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

        // dd($permissionGroups)
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
            <Button label="Create" size="small" outlined icon="pi pi-plus" @click="showRoleDialog = true" />
        </div>
    @endif

    <Tabs v-model:value="activeRoleId">
        <div class="flex flex-col lg:grid lg:grid-cols-[300px_1fr] gap-4">
            
            {{-- Roles List (Desktop) --}}
            <div class="card p-0 hidden lg:flex flex-col overflow-hidden">
                <div class="px-5 py-4 border-b border-[var(--border)] text-[14px] font-semibold text-[var(--text-base)] flex justify-between items-center">
                    <span>All Roles</span>
                    <Tag :value="`${roles.length} roles`" severity="secondary" style="font-size:11px;" />
                </div>
                <div class="flex-1 overflow-y-auto">
                    <div
                        v-for="role in roles" :key="role.id"
                        @click="selectRole(role)"
                        :class="['px-5 py-3.5 cursor-pointer border-b border-[var(--border)] transition-all',
                            activeRoleId === role.id ? 'bg-[var(--accent-light)] border-l-[3px] border-l-[var(--accent)]' : 'bg-transparent border-l-[3px] border-l-transparent hover:bg-gray-50'
                        ]"
                    >
                        <div style="display:flex; align-items:center; justify-content:space-between; gap:8px;">
                            <div style="flex:1;">
                                <div style="font-weight:500; font-size:13.5px; color:var(--text-base);">@{{ role.name }}</div>
                                <div style="font-size:12px; color:var(--text-muted); margin-top:2px;">
                                    @{{ role.description || (role.permissions && role.permissions.includes('*') ? 'All permissions' : (role.permissions ? role.permissions.length : 0) + ' permissions') }}
                                </div>
                            </div>
                            <div style="display:flex; align-items:center; gap:6px;">
                                <Tag :value="role.type || 'custom'" :severity="role.type === 'system' ? 'warning' : 'info'" style="font-size:10px;" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Mobile Tabs List --}}
            <div class="block lg:hidden">
                <TabList>
                    <Tab v-for="role in roles" :key="role.id" :value="role.id" @click="selectRole(role)" class="flex flex-col items-start gap-1!">
                        <div class="font-medium">@{{ role.name }}</div>
                        <div class="text-[10px] opacity-70 font-normal">
                            @{{ role.type || 'custom' }} role
                        </div>
                    </Tab>
                </TabList>
            </div>

            {{-- Permissions Matrix --}}
            <TabPanels class="!p-0 !bg-transparent">
                <TabPanel v-for="role in roles" :key="role.id" :value="role.id" class="p-0">
                    <div class="card p-0 overflow-hidden">
                        <div class="px-5 py-4 border-b border-[var(--border)] flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 flex-wrap">
                            <div class="text-[14px] font-semibold text-[var(--text-base)] flex items-center gap-2">
                                <span>Permissions - @{{ role.name }}</span>
                                <template v-if="role.type !== 'system'">
                                    <Button icon="pi pi-pencil" severity="secondary" text rounded class="w-6 h-6 p-0" title="Rename Role" @click="openEditRoleDialog(role)" />
                                    <Button icon="pi pi-trash" severity="danger" text rounded class="w-6 h-6 p-0" title="Delete Role" @click="confirmDeleteRole(role)" />
                                </template>
                            </div>
                            @if (hasPermission('admin.roles.update'))
                                <div class="flex items-center gap-2.5 w-full sm:w-auto">
                                    <InputText v-model="permissionSearch" placeholder="Search permissions..." size="small" class="w-full sm:w-44" />
                                    <Button 
                                        label="Save Changes" 
                                        size="small" 
                                        :disabled="role.type === 'system'" 
                                        @click="savePermissions"  
                                    />
                                </div>
                            @endif
                        </div>

                        <div class="p-4 sm:p-5">
                            <div v-if="role.type === 'system'" class="px-3.5 py-2.5 bg-[var(--bg-subtle)] rounded-lg border border-[var(--border)] mb-5 text-[12px] text-[var(--text-muted)] flex items-center gap-2">
                                <i class="pi pi-lock text-[14px] text-[var(--accent)]"></i>
                                This is a system role. System role permissions and role details cannot be edited or deleted.
                            </div>

                            <div v-for="group in filteredPermissionGroups" :key="group.name" class="mb-6">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="text-[11px] font-bold tracking-wide uppercase text-[var(--text-muted)] flex items-center gap-2">
                                        <span>@{{ group.name }}</span>
                                        <span class="font-medium text-[var(--text-muted)]">
                                            (@{{ getGroupSelectedCount(group) }}/@{{ group.permissions.length }})
                                        </span>
                                    </div>
                                    <Button 
                                        v-if="role.type !== 'system'"
                                        :label="isGroupAllSelected(group) ? 'Deselect All' : 'Select All'" 
                                        size="small" 
                                        text 
                                        class="text-[11px] px-2 py-0.5 h-auto text-[var(--accent)]!"
                                        @click="toggleGroup(group)"
                                    />
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-3">
                                    <label 
                                        v-for="perm in group.permissions" :key="perm.key"
                                        :class="['flex items-start sm:items-center gap-2.5 p-3 rounded-lg border cursor-pointer transition-colors',
                                            selectedPerms[perm.key] ? 'border-[var(--accent)] bg-[var(--accent-light)]' : 'border-[var(--border)] bg-[var(--bg-subtle)] hover:border-gray-300'
                                        ]"
                                    >
                                        <Checkbox 
                                            :binary="true" 
                                            v-model="selectedPerms[perm.key]"
                                            :disabled="role.type === 'system'" 
                                            class="mt-0.5 sm:mt-0"
                                        />
                                        <div class="flex-1 min-w-0">
                                            <div class="text-[12.5px] font-medium text-[var(--text-base)] truncate">@{{ perm.name }}</div>
                                            <div class="text-[11px] text-[var(--text-muted)] font-mono mt-0.5 truncate">
                                                @{{ perm?.description || perm?.key }}
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <div v-if="filteredPermissionGroups.length === 0" class="p-8 text-center text-[var(--text-muted)] text-[13px]">
                                No permissions found matching "@{{ permissionSearch }}"
                            </div>
                        </div>
                    </div>
                </TabPanel>
                
                <div v-if="roles.length === 0" class="card p-12 text-center text-[var(--text-muted)]">
                    <i class="pi pi-shield text-[24px] block mb-2"></i>
                    No roles found
                </div>
            </TabPanels>
        </div>
    </Tabs>

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
            <Button label="Cancel" size="small" severity="secondary" text @click="showRoleDialog = false" />
            <Button label="Save" size="small" @click="createRole" :disabled="!newRole.name.trim()" />
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
            <Button label="Cancel" severity="secondary" size="small" text @click="showEditRoleDialog = false" />
            <Button label="Save Changes" size="small" @click="updateRoleDetails" :disabled="!editRoleData.name.trim()" />
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
                        activeRoleId: null,
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

                watch: {
                    activeRoleId(newId) {
                        if (this.selectedRole?.id !== newId) {
                            const role = this.roles.find(r => r.id === newId);
                            if (role) {
                                this.selectRole(role);
                            }
                        }
                    }
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
                        this.activeRoleId = role.id;
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