<x-admin::layouts>
    <div class="page-header flex justify-between items-center">
        <div>
            <h1 class="page-title">Clubs</h1>
            <div class="page-breadcrumb">Home / Clubs</div>
        </div>
        <div class="flex gap-2">
            @if (hasPermission('admin.clubs.store_club'))
            <Button label="Create" icon="pi pi-plus" size="small" outlined @click="$refs.clubsBranches.clubVisible = true" />
            @endif
            <!-- <Button label="Create Branch" icon="pi pi-plus" size="small" @click="$refs.clubsBranches.branchVisible = true" /> -->
        </div>
    </div>

    <v-clubs-branches ref="clubsBranches" :clubs-list='@json($clubs)'></v-clubs-branches>

    @pushOnce('styles')
    <link rel="stylesheet" href="https://unpkg.com/tributejs@5.1.3/dist/tribute.css">
    <style>
        .tribute-container {
            z-index: 9999;
        }
    </style>
    @endPushOnce
    @pushOnce('scripts')
    <script src="https://unpkg.com/tributejs@5.1.3/dist/tribute.min.js"></script>
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
                <Dialog v-model:visible="clubVisible" :header="clubEditMode ? 'Edit Club' : 'Create Club'" :style="{ width: '650px', maxWidth: '95vw' }" modal class="z-[100050]">
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

                            <div class="grid grid-cols-2 gap-2 mb-0!">
                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label label="Opening Time" />
                                    <x-admin::form.control-group.control
                                        type="time"
                                        name="opening_time"
                                        v-model="club.opening_time"
                                        ::rules="{required: ! clubEditMode}"
                                    />  
                                </x-admin::form.control-group>

                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label label="Close Time" />
                                    <x-admin::form.control-group.control
                                        type="time"
                                        name="close_time"
                                        v-model="club.close_time"
                                        ::rules="{required: ! clubEditMode}"
                                    />
                                </x-admin::form.control-group>
                            </div> 

                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label label="Logo" />
                                <x-admin::form.control-group.control
                                    type="file"
                                    name="logo"
                                    v-model="club.logo"
                                    ::rules="{required: !clubEditMode}"
                                />
                            </x-admin::form.control-group>

                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label label="Media Type" /> 
                                <Tabs v-model:value="club.file_type" @update:value="club.file_path = null" class="w-full mt-2">
                                    <TabList>
                                        <Tab value="image">Image File</Tab>
                                        <Tab value="video">Video File</Tab>
                                        <Tab value="image_url">Image URL</Tab>
                                        <Tab value="video_url">Video URL</Tab>
                                    </TabList>
                                    <TabPanels class="!p-0 !pt-2">
                                        <TabPanel value="image"> 
                                            <x-admin::form.control-group.control
                                                type="file"
                                                name="file_path"
                                                v-model="club.file_path"
                                                ::rules="{required: !clubEditMode}"
                                            />
                                        </TabPanel>
                                        <TabPanel value="video">
                                            <x-admin::form.control-group.control
                                                type="file"
                                                name="file_path" 
                                                v-model="club.file_path"
                                                ::rules="{required: !clubEditMode}"
                                            />
                                        </TabPanel>
                                        <TabPanel value="image_url">
                                            <x-admin::form.control-group.control
                                                type="text"
                                                name="file_path"
                                                v-model="club.file_path" 
                                                ::rules="{required: !clubEditMode}"
                                                placeholder="Enter image url"
                                            />
                                        </TabPanel>
                                        <TabPanel value="video_url">
                                            <x-admin::form.control-group.control
                                                type="text"
                                                name="file_path" 
                                                v-model="club.file_path"
                                                ::rules="{required: !clubEditMode}"
                                                placeholder="Enter video url"
                                            />
                                        </TabPanel>
                                    </TabPanels>
                                </Tabs>
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

                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label label="Address" />
                                <x-admin::form.control-group.control
                                    type="textarea"
                                    name="address"
                                    v-model="club.address"
                                    placeholder="Enter address"
                                />
                            </x-admin::form.control-group>

                            <div class="grid grid-cols-2 gap-2 mb-0!">
                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label label="Phone" />
                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="phone"
                                        v-model="club.phone"
                                        placeholder="Enter phone"
                                    />
                                </x-admin::form.control-group>

                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label label="WhatsApp No" />
                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="whatsapp_no"
                                        v-model="club.whatsapp_no"
                                        placeholder="Enter WhatsApp"
                                    />
                                </x-admin::form.control-group>
                            </div>

                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label label="Primary Business WhatsApp" />
                                <x-admin::form.control-group.control
                                    type="text"
                                    name="primary_business_whatsapp"
                                    v-model="club.primary_business_whatsapp"
                                    placeholder="Enter primary business WhatsApp"
                                />
                            </x-admin::form.control-group>

                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label label="Disclaimer (Multiple separated by newline)" />
                                <x-admin::form.control-group.control
                                    type="textarea"
                                    name="disclaimer"
                                    v-model="club.disclaimer"
                                    placeholder="Enter disclaimers"
                                    id="club_disclaimer"
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
                <!-- View Staff Drawer -->
                <x-admin::drawer ref="viewStaffDrawer" width="600px" position="right">
                    <x-slot:header>
                        <div class="flex items-center gap-2">
                            <span class="icon-users text-2xl text-(--accent)"></span>
                            <h3 class="text-lg font-semibold text-(--text-base)">Club Staff <span v-if="viewingClub">(@{{ viewingClub.name }})</span></h3>
                        </div>
                    </x-slot:header>
                    <x-slot:content>
                        <div class="flex flex-col gap-4 py-4 h-full">
                            <div class="flex justify-between items-center pb-2 border-b border-(--border)">
                                <h4 class="text-sm font-semibold text-(--text-base)">Staff Members</h4>
                                <Button label="Add Staff" icon="pi pi-plus" size="small" @click="onAddStaff" />
                            </div>

                            <div v-if="loadingStaff" class="flex justify-center p-8">
                                <span class="icon-spinner text-2xl animate-spin text-(--accent)"></span>
                            </div>
                            <div v-else-if="staffList.length === 0" class="flex flex-col items-center justify-center p-8 text-center text-(--text-muted)">
                                <span class="icon-users text-4xl mb-2 opacity-50"></span>
                                <p>No staff added to this club yet.</p>
                            </div>
                            <div v-else class="flex flex-col gap-3">
                                <div v-for="(staff, index) in staffList" :key="staff.id" class="p-4 border border-(--border) rounded-md bg-(--bg-subtle) relative">
                                    <div class="absolute top-4 right-4 flex gap-2">
                                        <Button icon="pi pi-pencil" text rounded size="small" @click="onEditStaff(staff)" />
                                        <Button icon="pi pi-trash" text rounded size="small" severity="danger" @click="deleteStaff(staff.id)" />
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <div v-if="staff.avatar" class="flex-shrink-0 w-12 h-12 rounded-full overflow-hidden border border-(--border)">
                                            <img :src="staff.avatar_url" class="w-full h-full object-cover" />
                                        </div>
                                        <div v-else class="flex-shrink-0 w-12 h-12 rounded-full bg-(--accent-light) flex items-center justify-center text-(--accent) font-bold text-lg">
                                            @{{ staff.name.charAt(0).toUpperCase() }}
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="text-base font-bold text-(--text-base)">@{{ staff.name }}</span>
                                            <span class="text-xs uppercase font-semibold text-(--accent)">@{{ staff.role }}</span>
                                        </div>
                                    </div>
                                    <div v-if="staff.contact_no || staff.bio || (staff.social_accounts && Object.values(staff.social_accounts).some(v => v))" class="mt-3 pt-3 border-t border-(--border) text-sm text-(--text-muted)">
                                        <div v-if="staff.contact_no" class="mb-1 text-(--text-base)"><i class="pi pi-phone mr-2 text-(--text-muted)"></i>@{{ staff.contact_no }}</div>
                                        <div v-if="staff.bio" class="italic mb-2 text-(--text-muted)">"@{{ staff.bio }}"</div>
                                        <div v-if="staff.social_accounts" class="flex gap-3 text-lg mt-2">
                                            <a v-if="staff.social_accounts.instagram" :href="staff.social_accounts.instagram" target="_blank" class="text-pink-500 hover:opacity-80"><i class="pi pi-instagram"></i></a>
                                            <a v-if="staff.social_accounts.facebook" :href="staff.social_accounts.facebook" target="_blank" class="text-blue-600 hover:opacity-80"><i class="pi pi-facebook"></i></a>
                                            <a v-if="staff.social_accounts.linkedin" :href="staff.social_accounts.linkedin" target="_blank" class="text-blue-800 hover:opacity-80"><i class="pi pi-linkedin"></i></a>
                                            <a v-if="staff.social_accounts.x" :href="staff.social_accounts.x" target="_blank" class="text-gray-800 hover:opacity-80"><i class="pi pi-twitter"></i></a>
                                            <a v-if="staff.social_accounts.others" :href="staff.social_accounts.others" target="_blank" class="text-gray-500 hover:opacity-80"><i class="pi pi-link"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </x-slot:content>
                </x-admin::drawer>

                <!-- Create/Edit Staff Modal -->
                <Dialog v-model:visible="staffVisible" :header="staffEditMode ? 'Edit Staff' : 'Add Staff'" baseZIndex="100050" :style="{ width: '650px', maxWidth: '95vw' }" modal>
                    <x-admin::form v-slot="{ meta, errors, handleSubmit }" as="div">
                        <form @submit="handleSubmit($event, saveStaff)" class="space-y-4 pt-3">
                            <div class="grid grid-cols-2 gap-4">
                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label label="Role" />
                                    <Select
                                        v-model="staff.role"
                                        :options="[{label: 'Owner', value: 'owner'}, {label: 'Manager', value: 'manager'}]"
                                        optionLabel="label"
                                        optionValue="value"
                                        placeholder="Select Role"
                                        class="w-full"
                                    />
                                </x-admin::form.control-group>
                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label label="Name" />
                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="name"
                                        v-model="staff.name"
                                        rules="required"
                                        placeholder="Enter name"
                                    />
                                </x-admin::form.control-group>
                            </div>

                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label label="Avatar" /> 
                                <x-admin::form.control-group.control
                                    type="file"
                                    name="avatar"
                                    @@change="onStaffAvatarChange"
                                    accept="image/*" 
                                />

                            </x-admin::form.control-group>

                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label label="Contact No" />
                                <x-admin::form.control-group.control
                                    type="text"
                                    name="contact_no"
                                    v-model="staff.contact_no"
                                    placeholder="Enter contact number"
                                />
                            </x-admin::form.control-group>

                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label label="Bio" />
                                <x-admin::form.control-group.control
                                    type="textarea"
                                    name="bio"
                                    v-model="staff.bio"
                                    placeholder="Enter short bio"
                                />
                            </x-admin::form.control-group>

                            <div class="border-t border-(--border) pt-4 pb-2 mt-4">
                                <h4 class="text-sm font-semibold text-(--text-base) mb-3">Social Accounts</h4>
                                <div class="grid grid-cols-2 gap-x-4 mb-0">
                                    <x-admin::form.control-group>
                                        <x-admin::form.control-group.label label="Instagram" /> <i class="pi pi-instagram"></i>
                                        <x-admin::form.control-group.control
                                            type="text"
                                            name="social_accounts['instagram']"
                                            v-model="staff.social_accounts.instagram"
                                            placeholder="Profile URL"
                                            rules="url"
                                        />
                                    </x-admin::form.control-group>
                                    
                                    <x-admin::form.control-group>
                                        <x-admin::form.control-group.label label="Facebook" /> 
                                        <span class="p-inputgroup-addon"><i class="pi pi-facebook"></i></span>

                                        <x-admin::form.control-group.control
                                            type="text"
                                            name="social_accounts['facebook']"
                                            v-model="staff.social_accounts.facebook"
                                            placeholder="Profile URL"
                                            rules="url"
                                        /> 
                                    </x-admin::form.control-group>

                                    <x-admin::form.control-group>
                                        <x-admin::form.control-group.label label="LinkedIn" />
                                        <span class="p-inputgroup-addon"><i class="pi pi-linkedin"></i></span>

                                        <x-admin::form.control-group.control
                                            type="text"
                                            name="social_accounts['linkedin']"
                                            v-model="staff.social_accounts.linkedin"
                                            placeholder="Profile URL"
                                            rules="url"
                                        />  
                                    </x-admin::form.control-group>

                                    <x-admin::form.control-group>
                                        <x-admin::form.control-group.label label="X (Twitter)" />
                                        <span class="p-inputgroup-addon"><i class="pi pi-twitter"></i></span>
                                        <x-admin::form.control-group.control
                                            type="text"
                                            name="social_accounts['x']"
                                            v-model="staff.social_accounts.x"
                                            placeholder="Profile URL"
                                            rules="url"
                                        />  
                                    </x-admin::form.control-group>

                                    <x-admin::form.control-group class="col-span-2">
                                        <x-admin::form.control-group.label label="Other (Website, TikTok, etc)" />
                                        <span class="p-inputgroup-addon"><i class="pi pi-link"></i></span>

                                        <x-admin::form.control-group.control
                                            type="text"
                                            name="social_accounts['others']"
                                            v-model="staff.social_accounts.others"
                                            placeholder="Profile URL"
                                            rules="url"
                                        /> 
                                    </x-admin::form.control-group>
                                </div>
                            </div>

                            <div class="flex items-center gap-2 ">
                                <ToggleSwitch v-model="staff.is_active" inputId="staff_active_toggle" />
                                <x-admin::form.control-group.label label="Active Status" for="staff_active_toggle" />
                            </div>

                            <div class="flex justify-end gap-2 pt-4 border-t border-gray-200">
                                <Button type="button" label="Cancel" severity="secondary" text size="small" @click="staffVisible = false" />
                                <Button type="submit" label="Save" size="small" :loading="staffLoading" />
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
                    club: {
                        id: null,
                        name: '',
                        logo: null,
                        description: '',
                        address: '',
                        city: '',
                        phone: '',
                        whatsapp_no: '',
                        primary_business_whatsapp: '',
                        opening_time: null,
                        close_time: null,
                        disclaimer: '',
                        is_active: true,
                        file_type: 'image',
                        file_path: null
                    },
                    logoFile: null,
                    mediaFile: null,

                    branchVisible: false,
                    branchEditMode: false,
                    branchLoading: false,
                    branch: {
                        id: null,
                        club_id: null,
                        name: '',
                        description: '',
                        address: '',
                        phone: '',
                        is_active: true
                    },
                    viewingClub: null,
                    staffList: [],
                    loadingStaff: false,
                    staffVisible: false,
                    staffEditMode: false,
                    staffLoading: false,
                    staffAvatarFile: null,
                    staff: {
                        id: null,
                        role: 'manager',
                        name: '',
                        contact_no: '',
                        bio: '',
                        is_active: true,
                        social_accounts: {
                            instagram: '',
                            facebook: '',
                            linkedin: '',
                            x: '',
                            others: ''
                        }
                    },
                    tributeInstance: null,
                    emitter: null
                };
            },
            watch: {
                clubsList(newVal) {
                    this.localClubsList = [...newVal];
                },
                clubVisible(val) {
                    if (val && !this.clubEditMode) {
                        this.club = {
                            id: null,
                            name: '',
                            logo: null,
                            description: '',
                            address: '',
                            city: '',
                            phone: '',
                            whatsapp_no: '',
                            primary_business_whatsapp: '',
                            opening_time: null,
                            close_time: null,
                            disclaimer: '',
                            is_active: true,
                            file_type: 'image',
                            file_path: null
                        };
                        this.logoFile = null;
                        this.mediaFile = null;
                    } else if (!val) {
                        this.clubEditMode = false;
                        this.logoFile = null;
                        this.mediaFile = null;
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
                        this.branch = {
                            id: null,
                            club_id: null,
                            name: '',
                            description: '',
                            address: '',
                            phone: '',
                            is_active: true
                        };
                    } else if (!val) {
                        this.branchEditMode = false;
                    }
                }
            },
            provide() {
                return {
                    customActions: {
                        edit: this.onEdit,
                        staff: this.onStaff
                    }
                };
            },
            mounted() {},
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
                            logo: '',
                            description: row.description || '',
                            address: row.address || '',
                            city: row.city || '',
                            phone: row.phone || '',
                            whatsapp_no: row.whatsapp_no || '',
                            primary_business_whatsapp: row.primary_business_whatsapp || '',
                            opening_time: row.opening_time || '',
                            close_time: row.close_time || '',
                            disclaimer: row.disclaimer || '',
                            is_active: !!row.is_active,
                            file_type: '',
                            file_path: ''
                        };
                        this.logoFile = null;
                        this.mediaFile = null;
                        this.clubVisible = true;
                    }
                },
                onStaff(row) {
                    this.viewingClub = row;
                    this.staffList = [];
                    this.loadingStaff = true;
                    this.$refs.viewStaffDrawer.open();

                    this.$axios.get(`{{ route('admin.clubs.index') }}/${row.id}/staff`)
                        .then(response => {
                            this.staffList = response.data?.staff ?? [];
                        })
                        .catch(error => {
                            console.error('Failed to fetch staff', error);
                        })
                        .finally(() => {
                            this.loadingStaff = false;
                        });
                },
                onStaffAvatarChange(event) {
                    this.staffAvatarFile = event.target.files[0];
                },
                onAddStaff() {
                    this.staffEditMode = false;
                    this.staffAvatarFile = null;
                    this.staff = {
                        id: null,
                        role: 'manager',
                        name: '',
                        contact_no: '',
                        bio: '',
                        is_active: true,
                        social_accounts: {
                            instagram: '',
                            facebook: '',
                            linkedin: '',
                            x: '',
                            others: ''
                        }
                    };
                    this.staffVisible = true;
                },
                onEditStaff(staff) {
                    this.staffEditMode = true;
                    this.staffAvatarFile = null;
                    this.staff = {
                        id: staff.id,
                        role: staff.role,
                        name: staff.name,
                        contact_no: staff.contact_no || '',
                        bio: staff.bio || '',
                        is_active: !!staff.is_active,
                        social_accounts: staff.social_accounts || {
                            instagram: '',
                            facebook: '',
                            linkedin: '',
                            x: '',
                            others: ''
                        }
                    };
                    this.staffVisible = true;
                },
                saveStaff(params) {
                    this.staffLoading = true;
                    const url = this.staffEditMode ?
                        `{{ route('admin.clubs.index') }}/staff/${this.staff.id}` :
                        `{{ route('admin.clubs.index') }}/${this.viewingClub.id}/staff`;

                    let formData = new FormData();
                    Object.keys(params).forEach(key => {
                        formData.append(key, params[key]);
                    });

                    formData.append('role', this.staff.role);
                    formData.append('is_active', this.staff.is_active ? 1 : 0);
                    formData.append('social_accounts', JSON.stringify(this.staff.social_accounts));

                    if (this.staffAvatarFile) {
                        formData.append('avatar', this.staffAvatarFile);
                    } else {
                        formData.append('avatar', '');
                    }

                    if (this.staffEditMode) {
                        formData.append('_method', 'PUT');
                    }

                    this.$axios.post(url, formData, {
                            headers: {
                                'Content-Type': 'multipart/form-data'
                            }
                        })
                        .then(response => {
                            this.$emitter.emit('add-flash', {
                                type: 'success',
                                message: response.data.message
                            });
                            this.staffVisible = false;
                            this.staffList = response.data.staff;
                            this.staffAvatarFile = null;
                        })
                        .catch(err => {
                            this.$emitter.emit('add-flash', {
                                type: 'error',
                                message: err.response?.data?.message || 'Error saving staff'
                            });
                        })
                        .finally(() => {
                            this.staffLoading = false;
                        });
                },
                deleteStaff(id) {
                    if (confirm('Are you sure you want to delete this staff member?')) {
                        this.$axios.delete(`{{ route('admin.clubs.index') }}/staff/${id}`)
                            .then(response => {
                                this.$emitter.emit('add-flash', {
                                    type: 'success',
                                    message: response.data.message
                                });
                                this.staffList = response.data.staff;
                            });
                    }
                },

                onLogoChange(event) {
                    this.logoFile = event.target.files[0];
                },

                onFileChange(event) {
                    this.mediaFile = event.target.files[0];
                },

                saveClub(params, {
                    resetForm,
                    setErrors
                }) {
                    this.clubLoading = true;
                    const url = this.clubEditMode ?
                        `{{ route('admin.clubs.index') }}/club/${this.club.id}` :
                        `{{ route('admin.clubs.store_club') }}`;

                    let formData = new FormData();
                    Object.keys(params).forEach(key => {
                        formData.append(key, params[key]);
                    });

                    formData.append('is_active', this.club.is_active ? 1 : 0);
                    formData.append('file_type', this.club.file_type || '');

                    // if (params.logo == 'null') {
                    //     formData.append('logo', '');
                    // } else {
                    //     formData.append('logo', params.logo);
                    // }

                    this.$axios.post(url, formData, {
                            headers: {
                                'Content-Type': 'multipart/form-data'
                            }
                        })
                        .then(response => {
                            this.$emitter.emit('add-flash', {
                                type: 'success',
                                message: response.data.message
                            });
                            this.clubVisible = false;
                            this.clubLoading = false;
                            if (response.data.clubs) {
                                this.localClubsList = response.data.clubs;
                            }
                            this.$refs.clubsGrid.get();
                            // this.$refs.branchesGrid.get();
                        })
                        .catch(error => {
                            this.clubLoading = false;

                            this.$helpers.errorControl(error, setErrors);
                        });
                },
                saveBranch(params) {
                    this.branchLoading = true;
                    const url = this.branchEditMode ?
                        `{{ route('admin.clubs.index') }}/branch/${this.branch.id}` :
                        `{{ route('admin.clubs.store_branch') }}`;

                    this.$axios.post(url, {
                            ...params,
                            club_id: this.branch.club_id,
                            is_active: this.branch.is_active ? 1 : 0
                        })
                        .then(response => {
                            this.$emitter.emit('add-flash', {
                                type: 'success',
                                message: response.data.message
                            });
                            this.branchVisible = false;
                            this.branchLoading = false;
                            this.$refs.clubsGrid.get();
                            // this.$refs.branchesGrid.get();
                        })
                        .catch(err => {
                            this.branchLoading = false;
                        });
                }
            }
        });
    </script>
    @endPushOnce
</x-admin::layouts>