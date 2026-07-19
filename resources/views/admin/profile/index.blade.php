<x-admin::layouts>

    <div class="page-header">
        <h1 class="page-title">My Profile</h1>
        <div class="page-breadcrumb">Home / Profile</div>
    </div>

    <v-profile></v-profile>

    @pushOnce('scripts')
        <script type="text/x-template" id="v-profile-template">
            <div style="display:grid; grid-template-columns: 300px 1fr; gap:20px; align-items:start;"
                 class="grid grid-cols-1 md:grid-cols-[300px_1fr] gap-5 items-start">

                {{-- Left: Avatar & Quick Info --}}
                <div style="display:flex; flex-direction:column; gap:16px;" class=" flex-reverse">
                    <div class="card" style="padding:28px; text-align:center;">
                        <div style="position:relative; display:inline-block; margin-bottom:16px;">
                            <img 
                                onerror="this.src='{{$admin->avatar_preview_url}}'"
                                :src="avatarPreview" :style="{
                                width:'96px', height:'96px', borderRadius:'50%',
                                background: 'var(--accent)',
                                display:'flex', alignItems:'center', justifyContent:'center',
                                  
                                color:'#fff', fontSize:'32px', fontWeight:'700', margin:'0 auto',
                            }"/> 
                            <button @click="triggerAvatarUpload"
                                    style="position:absolute; bottom:0; right:0; width:28px; height:28px; border-radius:50%; background:var(--bg-surface); border:2px solid var(--border); cursor:pointer; display:flex; align-items:center; justify-content:center;">
                                <i class="pi pi-camera" style="font-size:12px; color:var(--text-muted);"></i>
                            </button>
                        </div>
                        <div style="font-size:18px; font-weight:700; color:var(--text-base);">@{{ form.name }}</div>
                        <div style="font-size:12px; color:var(--text-muted); margin-top:2px;">@{{ form.email }}</div>
                        <Tag :value="form.role" severity="info" style="margin-top:10px;" />

                        <div style="margin-top:20px; border-top:1px solid var(--border); padding-top:16px; text-align:left;">
                            <div v-for="info in quickInfo" :key="info.label"
                                 style="display:flex; justify-content:space-between; margin-bottom:10px; font-size:12.5px;">
                                <span style="color:var(--text-muted);">@{{ info.label }}</span>
                                <span style="color:var(--text-base); font-weight:500;">@{{ info.value }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Theme Preferences --}}
                    {{-- <div class="card" style="padding:20px;">
                        <div style="font-size:13px; font-weight:600; color:var(--text-base); margin-bottom:14px;">
                            <i class="pi pi-palette" style="margin-right:6px; color:var(--accent);"></i>
                            Theme Preference
                        </div>
                        <div style="display:grid; grid-template-columns:1fr 1fr; gap:8px;">
                            <div v-for="t in themes" :key="t.key"
                                 @click="selectTheme(t.key)"
                                 :style="{
                                     padding:'12px 10px', borderRadius:'8px', cursor:'pointer',
                                     border: currentTheme === t.key ? '2px solid var(--accent)' : '2px solid var(--border)',
                                     background: currentTheme === t.key ? 'var(--accent-light)' : 'var(--bg-subtle)',
                                     transition:'all 0.15s',
                                     textAlign:'center',
                                 }"
                            >
                                <div :style="{ width:'28px', height:'28px', borderRadius:'6px', background:t.preview, margin:'0 auto 6px' }"></div>
                                <div style="font-size:11px; font-weight:600; color:var(--text-base);">@{{ t.label }}</div>
                            </div>
                        </div>
                    </div> --}}

                    {{-- Notification Preferences --}}
                    {{-- <div class="card" style="padding:20px;">
                        <div style="font-size:13px; font-weight:600; color:var(--text-base); margin-bottom:14px;">
                            <i class="pi pi-bell" style="margin-right:6px; color:var(--accent);"></i>
                            Notifications
                        </div>

                        <div v-for="notif in notifPrefs" :key="notif.key"
                             style="display:flex; align-items:center; justify-content:space-between; margin-bottom:10px;">
                            <span style="font-size:12.5px; color:var(--text-base);">@{{ notif.label }}</span>
                            <ToggleSwitch v-model="notif.enabled" />
                        </div>
                    </div> --}}
                </div>

                {{-- Right: Form Tabs --}}
                <div class="card" style="padding:0; overflow:hidden;">
                        <x-admin::form v-slot="{ meta, errors, handleSubmit }" as="div">
                            <form @submit="handleSubmit($event, update)" ref="updateProfileForm" >
                                {{-- Personal Info Tab --}}
                                 <div style="padding:24px; display:flex; flex-direction:column; gap:18px;">
                                        {{-- <input type="file" class="hidden" name="avatar" id="avatar"/> --}}

                                        {{-- <x-admin::form.control-group.control
                                            type="file"
                                            id="avatar"
                                            name="avatar" 
                                            class="hidden" 
                                            accept="image/*"
                                            @change="handleImagePreview"
                                        />  --}}

                                        <input 
                                            type="file"
                                            id="avatar"
                                            name="avatar" 
                                            class="hidden" 
                                            accept="image/*"
                                            @change="handleImagePreview"
                                        />    

                                        <x-admin::form.control-group>
                                            <x-admin::form.control-group.control
                                                type="text"
                                                id="name"
                                                name="name"
                                                rules="required"
                                                label="Name"
                                                :placeholder="'Enter your name'" 
                                                value="{{$admin->name ?? ''}}"
                                                v-model="form.name" 
                                            />
                                        </x-admin::form.control-group> 

                                    <x-admin::form.control-group>
                                        <x-admin::form.control-group.control
                                            type="email"
                                            id="email"
                                            name="email"
                                            rules="required|email"
                                            label="Email Address"
                                            :placeholder="'Enter your email'" 
                                            value="{{$admin->email ?? ''}}"
                                            v-model="form.email" 
                                        />
                                    </x-admin::form.control-group>

                                    <x-admin::form.control-group>
                                        <x-admin::form.control-group.control
                                            type="text"
                                            id="phone"
                                            name="phone"
                                            label="Phone"
                                            :placeholder="'+91 00000 00000'" 
                                            value="{{$admin->phone ?? ''}}"
                                            v-model="form.phone" 
                                        />
                                    </x-admin::form.control-group>
                                    
                                    @php
                                        $roles = [
                                            ['code' => 1, 'name' => 'All'], 
                                        ];
                                    @endphp

                                    <x-admin::form.control-group>
                                        <x-admin::form.control-group.control
                                            type="select"
                                            ::options="{{json_encode($roles)}}"
                                            optionLabel="name"
                                            rules="required"
                                            optionValue="code"
                                            name="role_id"
                                            placeholder="Select"
                                            label="Role" 
                                            ::value="{{ $admin->role_id ?? '' }}"
                                        />
                                    </x-admin::form.control-group>  


                                    <x-admin::form.control-group>
                                        <x-admin::form.control-group.control
                                            type="password"
                                            id="current_password"
                                            name="current_password"
                                            label="Current Password"
                                            v-model="form.current_password" 
                                        />
                                    </x-admin::form.control-group>

                                    <x-admin::form.control-group>
                                        <x-admin::form.control-group.control
                                            type="password"
                                            id="password"
                                            name="password"
                                            rules="min:8"
                                            label="New Password"
                                            v-model="form.password" 
                                        />
                                    </x-admin::form.control-group>

                                    <x-admin::form.control-group>
                                        <x-admin::form.control-group.control
                                            type="password"
                                            id="password_confirmation"
                                            name="password_confirmation"
                                            rules="confirmed:@password"
                                            label="Confirm New Password"
                                            v-model="form.password_confirmation"
                                        />
                                    </x-admin::form.control-group>

                                    <div style="padding:14px; background:var(--bg-subtle); border-radius:8px; border:1px solid var(--border);">
                                        <div style="font-size:12px; font-weight:600; color:var(--text-base); margin-bottom:8px;">Strong Password Format</div>
                                        <div v-for="req in passRequirements" :key="req.label" style="display:flex; align-items:center; gap:6px; margin-bottom:5px;">
                                            <i :class="['pi', req.met ? 'pi-check-circle' : 'pi-times-circle']"
                                                :style="{ color: req.met ? 'var(--success)' : 'var(--text-muted)', fontSize:'12px' }"></i>
                                            <span style="font-size:12px; color:var(--text-muted);">@{{ req.label }}</span>
                                        </div>
                                    </div>


                                    {{-- <x-admin::form.control-group>
                                        <div style="display:flex; align-items:center; justify-content:space-between; padding:14px; background:var(--bg-subtle); border-radius:8px; border:1px solid var(--border);">
                                            <div>
                                                <div style="font-size:13px; font-weight:500; color:var(--text-base);">Authenticator App (TOTP)</div>
                                                <div style="font-size:12px; color:var(--text-muted);">Use Google Authenticator or similar</div>
                                            </div>
                                            <x-admin::form.control-group.control
                                                type="switch"
                                                name="twoFA"
                                                inputId="twoFA"
                                                for="twoFA"
                                                value="enabled"
                                                label=""
                                                ::checked="security.twoFA"
                                            />
                                        </div>
                                    </x-admin::form.control-group> --}}
                                    
                                </div> 

                                <div style="display:flex; justify-content:flex-end; gap:10px;">
                                    <Button label="Discard" severity="secondary" text type="button" />
                                    <Button type="submit" label="Save Changes" icon="pi pi-check" :loading="isLoading" />
                                </div>

                                {{-- Activity Log --}}
                                <div style="padding:24px;">
                                    <Timeline :value="activityLog">
                                        <template #content="{ item }">
                                            <div style="padding-bottom:16px;">
                                                <div style="font-size:13px; font-weight:500; color:var(--text-base);">@{{ item.action }}</div>
                                                <div style="font-size:11px; color:var(--text-muted); margin-top:2px;">@{{ item.time }} · @{{ item.ip }}</div>
                                            </div>
                                        </template>
                                        <template #marker="{ item }">
                                            <span :style="{
                                                width:'10px', height:'10px', borderRadius:'50%',
                                                background: item.type === 'success' ? 'var(--success)' : item.type === 'warning' ? 'var(--warning)' : 'var(--accent)',
                                                display:'inline-block'
                                            }"></span>
                                        </template>
                                    </Timeline>
                                </div> 
                            </form>
                        </x-admin::form>
                </div>
            </div>
        </script>

        <style>
            .form-label {
                font-size: 12px;
                font-weight: 600;
                color: var(--text-muted);
                display: block;
                margin-bottom: 6px;
            }
        </style>

        <script type="module">
            adminVueApp.component('v-profile', {
                template: '#v-profile-template',

                data() {
                    return {
                        isLoading: false,
                        isPassLoading: false,
                        avatarPreview: "{{ $admin->avatar_url }}",

                        form: { 
                            name: "{{$admin->name ?? ''}}",
                            phone: "{{$admin->phone ?? ''}}",
                            email: "{{$admin->email ?? ''}}",
                            current_password: '',
                            password: '',
                            password_confirmation: ''
                        }, 

                        currentTheme: document.documentElement.className || 'dark',

                        themes: [
                            
                        ],

                        notifPrefs: [ 
                        ],

                        activityLog: [
                            { action: 'Logged in from Chrome / Windows', time: 'Today 9:12 AM', ip: '192.168.1.14', type: 'success' },
                            { action: 'Updated user role: Priya Mehta → Manager', time: 'Yesterday 4:30 PM', ip: '192.168.1.14', type: 'success' },
                            { action: 'Failed login attempt', time: 'Yesterday 8:11 AM', ip: '203.0.113.9', type: 'warning' },
                            { action: 'Created new role: Content Editor', time: 'Apr 28, 2:15 PM', ip: '192.168.1.14', type: 'accent' },
                            { action: 'Password changed', time: 'Apr 20, 11:00 AM', ip: '192.168.1.14', type: 'success' },
                        ],
                    };
                },

                computed: { 
                    quickInfo() {
                        return [
                            { label: 'Member Since', value: 'Jan 2023' },
                            { label: 'Last Login', value: 'Today 9:12 AM' },
                            { label: 'Sessions', value: '3 active' },
                            { label: 'Timezone', value: 'IST (UTC+5:30)' },
                        ];
                    },

                    passRequirements() {
                        return [
                            { label: 'At least 8 characters', met: this.form.password.length >= 8 },
                            { label: 'One uppercase letter', met: /[A-Z]/.test(this.form.password) },
                            { label: 'One number', met: /\d/.test(this.form.password) },
                            { label: 'One special character', met: /[^a-zA-Z0-9]/.test(this.form.password) },
                        ];
                    },
                },

                methods: { 
                    triggerAvatarUpload() {
                        document.getElementById('avatar').click();
                    },

                    handleImagePreview(event) { 
                        const file = event.target.files[0];
                        
                        if (file) {  
                            this.avatarPreview = URL.createObjectURL(file); 
                        }
                    },

                    update(params, { resetForm, setErrors }) {
                        console.log(params);
                        this.isLoading = true;

                        let formData = new FormData(this.$refs.updateProfileForm);

                        formData.append('_method', 'PUT');
                        
                        /* Uncomment for real HTTP request */
                        this.$axios.post("{{ route('admin.profile.update') }}", formData, {
                                headers: {
                                    'Content-Type': 'multipart/form-data',
                                }
                            })
                            .then((response) => {
                                this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });

                                window.location.reload();
                            })
                            .catch(error => {
                                if (error.response.status === 422) {
                                    if (error.response.data.errors?.avatar) {
                                        this.$emitter.emit('add-flash', { type: 'error', message: error.response.data.errors?.avatar[0] });
                                    }

                                    setErrors(error.response.data.errors);
                                }
                            }).then(() => {
                                this.isLoading = false;
                            });                        
                    }
                },
            });
        </script>
    @endPushOnce
</x-admin::layouts>