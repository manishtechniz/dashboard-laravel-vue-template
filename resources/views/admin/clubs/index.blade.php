<x-admin::layouts>
    <div class="page-header flex justify-between items-center">
        <div>
            <h1 class="page-title">Clubs</h1>
            <div class="page-breadcrumb">Home / Clubs</div>
        </div>
        <div class="flex gap-2">
            @if (hasPermission('admin.clubs.store_club'))
            <Button label="Create Club" icon="pi pi-plus" size="small" outlined @click="$refs.clubsBranches.clubVisible = true" />
            @endif
            <!-- <Button label="Create Branch" icon="pi pi-plus" size="small" @click="$refs.clubsBranches.branchVisible = true" /> -->
        </div>
    </div>

    <v-clubs-branches ref="clubsBranches" :clubs-list='@json($clubs)'></v-clubs-branches>

    @pushOnce('scripts')
        <script type="text/x-template" id="v-clubs-branches-template">
            <div>
                <!-- <TabViews value="0">
                    <TabList class="mb-4">
                        <Tab value="0">Clubs List</Tab>
                        <Tab value="1">Branches List</Tab>
                    </TabList>
                    <TabPanels>
                        <TabPanel value="0">
                            <x-admin::datagrid
                                :is-multi-row="true"
                                ref="clubsGrid"
                                src="{{ route('admin.clubs.index') }}"
                            />
                        </TabPanel>
                        <TabPanel value="1">
                            <x-admin::datagrid
                                :is-multi-row="true"
                                ref="branchesGrid"
                                src="{{ route('admin.clubs.index') }}?branches=1"
                            />
                        </TabPanel>
                    </TabPanels>
                </TabViews> -->

                <x-admin::datagrid
                    :is-multi-row="true"
                    ref="clubsGrid"
                    src="{{ route('admin.clubs.index') }}"
                />

                <!-- Create/Edit Club Modal -->
                <Dialog v-model:visible="clubVisible" :header="clubEditMode ? 'Edit Club' : 'Create Club'" :style="{ width: '580px', maxWidth: '95vw' }" modal>
                    <x-admin::form v-slot="{ meta, errors, handleSubmit }" as="div">
                        <form @submit="handleSubmit($event, saveClub)" class="space-y-4 pt-3">
                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label label="Club Name" />
                                <x-admin::form.control-group.control
                                    type="text"
                                    name="name"
                                    v-model="club.name"
                                    rules="required"
                                    placeholder="Enter club name"
                                />
                            </x-admin::form.control-group>

                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label label="Description" />
                                <x-admin::form.control-group.control
                                    type="textarea"
                                    name="description"
                                    v-model="club.description"
                                    placeholder="Enter description"
                                />
                            </x-admin::form.control-group>

                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label label="Address" />
                                <x-admin::form.control-group.control
                                    type="text"
                                    name="address"
                                    v-model="club.address"
                                    placeholder="Enter address"
                                />
                            </x-admin::form.control-group>

                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label label="City" />
                                <x-admin::form.control-group.control
                                    type="text"
                                    name="city"
                                    v-model="club.city"
                                    placeholder="Enter city"
                                />
                            </x-admin::form.control-group>

                            <div class="flex items-center gap-2 pt-2">
                                <ToggleSwitch v-model="club.is_active" inputId="club_active_toggle" />
                                <x-admin::form.control-group.label label="Active Status" for="club_active_toggle" />
                            </div>

                            <div class="flex justify-end gap-2 pt-4 border-t border-gray-200">
                                <Button type="button" label="Cancel" severity="secondary" text size="small" @click="clubVisible = false" />
                                <Button type="submit" label="Save" size="small" :loading="clubLoading" />
                            </div>
                        </form>
                    </x-admin::form>
                </Dialog>

                <!-- Create/Edit Branch Modal -->
                <Dialog v-model:visible="branchVisible" :header="branchEditMode ? 'Edit Branch' : 'Create Branch'" :style="{ width: '580px', maxWidth: '95vw' }" modal>
                    <x-admin::form v-slot="{ meta, errors, handleSubmit }" as="div">
                        <form @submit="handleSubmit($event, saveBranch)" class="space-y-4 pt-3">
                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label label="Club" />
                                <Select
                                    v-model="branch.club_id"
                                    :options="localClubsList"
                                    optionLabel="name"
                                    optionValue="id"
                                    placeholder="Select Club"
                                    class="w-full"
                                />
                            </x-admin::form.control-group>

                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label label="Branch Name" />
                                <x-admin::form.control-group.control
                                    type="text"
                                    name="name"
                                    v-model="branch.name"
                                    rules="required"
                                    placeholder="Enter branch name"
                                />
                            </x-admin::form.control-group>

                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label label="Description" />
                                <x-admin::form.control-group.control
                                    type="textarea"
                                    name="description"
                                    v-model="branch.description"
                                    placeholder="Enter description"
                                />
                            </x-admin::form.control-group>

                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label label="Address" />
                                <x-admin::form.control-group.control
                                    type="text"
                                    name="address"
                                    v-model="branch.address"
                                    placeholder="Enter address"
                                />
                            </x-admin::form.control-group>

                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label label="Phone" />
                                <x-admin::form.control-group.control
                                    type="text"
                                    name="phone"
                                    v-model="branch.phone"
                                    placeholder="Enter phone number"
                                />
                            </x-admin::form.control-group>

                            <div class="flex items-center gap-2 pt-2">
                                <ToggleSwitch v-model="branch.is_active" inputId="branch_active_toggle" />
                                <x-admin::form.control-group.label label="Active Status" for="branch_active_toggle" />
                            </div>

                            <div class="flex justify-end gap-2 pt-4 border-t border-gray-200">
                                <Button type="button" label="Cancel" severity="secondary" text size="small" @click="branchVisible = false" />
                                <Button type="submit" label="Save" size="small" :loading="branchLoading" />
                            </div>
                        </form>
                    </x-admin::form>
                </Dialog>
                <Toast />
            </div>
        </script>

        <script type="module">
            adminVueApp.component('v-clubs-branches', {
                template: '#v-clubs-branches-template',
                props: ['clubsList'],
                data() {
                    return {
                        localClubsList: [...this.clubsList],
                        clubVisible: false,
                        clubEditMode: false,
                        clubLoading: false,
                        club: { id: null, name: '', description: '', address: '', city: '', is_active: true },

                        branchVisible: false,
                        branchEditMode: false,
                        branchLoading: false,
                        branch: { id: null, club_id: null, name: '', description: '', address: '', phone: '', is_active: true },

                        emitter: null
                    };
                },
                watch: {
                    clubsList(newVal) {
                        this.localClubsList = [...newVal];
                    },
                    clubVisible(val) {
                        if (val && !this.clubEditMode) {
                            this.club = { id: null, name: '', description: '', address: '', city: '', is_active: true };
                        } else if (!val) {
                            this.clubEditMode = false;
                        }
                    },
                    branchVisible(val) {
                        if (val) {
                            this.$axios.get("{{ route('admin.clubs.index') }}?list=1")
                                .then(response => {
                                    this.localClubsList = response.data;
                                });
                        }
                        if (val && !this.branchEditMode) {
                            this.branch = { id: null, club_id: null, name: '', description: '', address: '', phone: '', is_active: true };
                        } else if (!val) {
                            this.branchEditMode = false;
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
                mounted() {
                },
                methods: {
                    onEdit(row) {
                        // Distinguish edit row between Club and Branch based on properties
                        if (row.branch_name !== undefined) {
                            this.branchEditMode = true;
                            // find club id from row or default first
                            const parentClub = this.clubsList.find(c => c.name === row.club_name);
                            this.branch = {
                                id: row.id,
                                club_id: parentClub ? parentClub.id : null,
                                name: row.branch_name,
                                description: row.description || '',
                                address: row.address || '',
                                phone: row.phone || '',
                                is_active: !!row.is_active
                            };
                            this.branchVisible = true;
                        } else {
                            this.clubEditMode = true;
                            this.club = {
                                id: row.id,
                                name: row.name,
                                description: row.description || '',
                                address: row.address || '',
                                city: row.city || '',
                                is_active: !!row.is_active
                            };
                            this.clubVisible = true;
                        }
                    },

                    saveClub(params) {
                        this.clubLoading = true;
                        const url = this.clubEditMode
                            ? `{{ route('admin.clubs.index') }}/club/${this.club.id}`
                            : `{{ route('admin.clubs.store_club') }}`;

                        this.$axios.post(url, { ...params, is_active: this.club.is_active ? 1 : 0 })
                            .then(response => {
                                this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });
                                this.clubVisible = false;
                                this.clubLoading = false;
                                if (response.data.clubs) {
                                    this.localClubsList = response.data.clubs;
                                }
                                this.$refs.clubsGrid.get();
                                this.$refs.branchesGrid.get();
                            })
                            .catch(err => { this.clubLoading = false; });
                    },
                    saveBranch(params) {
                        this.branchLoading = true;
                        const url = this.branchEditMode
                            ? `{{ route('admin.clubs.index') }}/branch/${this.branch.id}`
                            : `{{ route('admin.clubs.store_branch') }}`;

                        this.$axios.post(url, { 
                            ...params, 
                            club_id: this.branch.club_id,
                            is_active: this.branch.is_active ? 1 : 0 
                        })
                            .then(response => {
                                this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });
                                this.branchVisible = false;
                                this.branchLoading = false;
                                this.$refs.clubsGrid.get();
                                this.$refs.branchesGrid.get();
                            })
                            .catch(err => { this.branchLoading = false; });
                    }
                }
            });
        </script>
    @endPushOnce
</x-admin::layouts>
