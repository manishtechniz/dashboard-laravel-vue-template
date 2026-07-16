<x-admin::layouts>
<div class="page-header">
    <h1 class="page-title">Theme Provider</h1>
    <div class="page-breadcrumb">Home / Theme Provider</div>
</div>

<v-themes></v-themes>

@pushOnce('scripts')
<script type="text/x-template" id="v-themes-template">
<div>
    <div style="display:grid; grid-template-columns: 2fr 1fr; gap:20px; align-items:start;">

        {{-- Theme Cards --}}
        <div>
            <div style="margin-bottom:16px; font-size:13px; font-weight:600; color:var(--text-base);">Select Theme</div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:14px; margin-bottom:24px;">
                <div v-for="theme in themes" :key="theme.key"
                    @click="applyTheme(theme.key)"
                    :style="{
                        border: active === theme.key ? '2px solid var(--accent)' : '2px solid var(--border)',
                        borderRadius:'12px', overflow:'hidden', cursor:'pointer',
                        transition:'all 0.2s', boxShadow: active === theme.key ? '0 0 0 4px var(--accent-light)' : 'none',
                    }"
                >
                    {{-- Preview --}}
                    <div :style="{ background: theme.preview.bg, padding:'16px', minHeight:'120px', position:'relative' }">
                        {{-- Mini sidebar --}}
                        <div :style="{ width:'40px', height:'88px', background:theme.preview.sidebar, borderRadius:'6px', position:'absolute', left:'14px', top:'14px' }"></div>
                        {{-- Mini content --}}
                        <div :style="{ marginLeft:'50px' }">
                            <div :style="{ height:'12px', background:theme.preview.accent, borderRadius:'4px', width:'70%', marginBottom:'8px' }"></div>
                            <div :style="{ height:'8px', background:theme.preview.muted, borderRadius:'4px', width:'90%', marginBottom:'6px' }"></div>
                            <div :style="{ height:'8px', background:theme.preview.muted, borderRadius:'4px', width:'75%' }"></div>
                            {{-- Mini cards --}}
                            <div style="display:flex; gap:6px; margin-top:10px;">
                                <div v-for="n in 3" :key="n"
                                    :style="{ flex:1, height:'28px', background:theme.preview.card, borderRadius:'5px' }">
                                </div>
                            </div>
                        </div>
                        {{-- Active badge --}}
                        <div v-if="active === theme.key"
                            style="position:absolute; top:10px; right:10px; background:#6366f1; color:#fff; border-radius:99px; padding:2px 10px; font-size:11px; font-weight:600;">
                            Active
                        </div>
                    </div>
                    {{-- Label --}}
                    <div :style="{ background:theme.preview.card, padding:'12px 14px', display:'flex', alignItems:'center', gap:'8px', borderTop:`1px solid ${theme.preview.border}` }">
                        <i :class="theme.icon" :style="{ color: theme.preview.accent, fontSize:'14px' }"></i>
                        <div>
                            <div :style="{ fontSize:'13px', fontWeight:'600', color:theme.preview.text }">@{{ theme.label }}</div>
                            <div :style="{ fontSize:'11px', color:theme.preview.muted }">@{{ theme.description }}</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Custom Color Builder --}}
            <div class="card card-body">
                <div style="font-size:14px; font-weight:600; color:var(--text-base); margin-bottom:14px;">
                    <i class="pi pi-palette" style="margin-right:6px; color:var(--accent);"></i>
                    Custom Accent Color
                </div>
                <div style="display:flex; flex-wrap:wrap; gap:10px; margin-bottom:14px;">
                    <div v-for="color in presetColors" :key="color"
                        @click="customAccent = color"
                        :style="{
                            width:'32px', height:'32px', borderRadius:'8px', background:color, cursor:'pointer',
                            border: customAccent === color ? '3px solid var(--text-base)' : '3px solid transparent',
                            transition:'all 0.15s',
                        }"
                    ></div>
                    <div style="position:relative;">
                        <input type="color" v-model="customAccent"
                            style="width:32px; height:32px; border:none; border-radius:8px; cursor:pointer; padding:2px;" />
                    </div>
                </div>
                <div style="font-size:12px; color:var(--text-muted); margin-bottom:10px;">
                    Preview: <strong :style="{ color:customAccent }">@{{ customAccent }}</strong>
                </div>
                <Button label="Apply Custom Color" size="small" @click="applyCustomColor" />
            </div>
        </div>

        {{-- Per-User Overrides --}}
        <div style="display:flex; flex-direction:column; gap:16px;">
            <div class="card card-body">
                <div style="font-size:13px; font-weight:600; color:var(--text-base); margin-bottom:14px;">User Overrides</div>
                <div style="display:flex; flex-direction:column; gap:10px;">
                    <div v-for="u in userOverrides" :key="u.name"
                        style="display:flex; align-items:center; justify-content:space-between; padding:10px 12px; background:var(--bg-subtle); border-radius:8px;">
                        <div style="display:flex; align-items:center; gap:8px;">
                            <div :style="{ width:'28px', height:'28px', borderRadius:'50%', background:u.color, display:'flex', alignItems:'center', justifyContent:'center', color:'#fff', fontSize:'11px', fontWeight:'600' }">
                                @{{ u.initials }}
                            </div>
                            <div>
                                <div style="font-size:12.5px; font-weight:500; color:var(--text-base);">@{{ u.name }}</div>
                                <div style="font-size:11px; color:var(--text-muted);">@{{ u.email }}</div>
                            </div>
                        </div>
                        <Select v-model="u.theme" :options="themeKeys" placeholder="Default"
                            style="width:100px;" size="small" />
                    </div>
                </div>
                <Button label="Save Overrides" size="small" style="margin-top:12px; width:100%;" @click="saveOverrides" />
            </div>

            <div class="card card-body">
                <div style="font-size:13px; font-weight:600; color:var(--text-base); margin-bottom:12px;">Display Preferences</div>
                <div style="display:flex; flex-direction:column; gap:10px;">
                    <div v-for="pref in displayPrefs" :key="pref.key"
                        style="display:flex; align-items:center; justify-content:space-between; font-size:13px;">
                        <span style="color:var(--text-base);">@{{ pref.label }}</span>
                        <ToggleSwitch v-model="pref.value" />
                    </div>
                </div>
            </div>

            <div class="card card-body">
                <div style="font-size:13px; font-weight:600; color:var(--text-base); margin-bottom:12px;">Font Size</div>
                <div style="display:flex; justify-content:space-between; font-size:12px; color:var(--text-muted); margin-bottom:8px;">
                    <span>Small</span><span>@{{ fontSize }}px</span><span>Large</span>
                </div>
                <Slider v-model="fontSize" :min="12" :max="18" :step="1" />
                <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:6px; margin-top:12px;">
                    <Button label="S" size="small" severity="secondary" outlined @click="fontSize=12" />
                    <Button label="M" size="small" severity="secondary" outlined @click="fontSize=14" />
                    <Button label="L" size="small" severity="secondary" outlined @click="fontSize=16" />
                </div>
            </div>
        </div>
    </div>
</div>
</script>

<script type="module">

adminVueApp.component('v-themes', {
    template: '#v-themes-template',

    data() {
        return {
            active: localStorage.getItem('admin-theme') || 'light',

            customAccent: '#6366f1',

            fontSize: 14,

            themes: [
                {
                    key:'light',
                    label:'Light',
                    icon:'pi pi-sun',
                    description:'Clean and minimal',
                    preview:{
                        bg:'#f4f6fb',
                        sidebar:'#0f172a',
                        accent:'#6366f1',
                        muted:'#e2e8f0',
                        card:'#fff',
                        text:'#1e293b',
                        border:'#e2e8f0'
                    }
                },
                {
                    key:'dark',
                    label:'Dark',
                    icon:'pi pi-moon',
                    description:'Easy on the eyes',
                    preview:{
                        bg:'#0b0f1a',
                        sidebar:'#0d1117',
                        accent:'#6366f1',
                        muted:'#1e2d45',
                        card:'#131929',
                        text:'#e2e8f0',
                        border:'#1e2d45'
                    }
                },
                {
                    key:'ocean',
                    label:'Ocean',
                    icon:'pi pi-cloud',
                    description:'Deep blue calm',
                    preview:{
                        bg:'#0a1628',
                        sidebar:'#071020',
                        accent:'#06b6d4',
                        muted:'#1a3a5c',
                        card:'#0f2040',
                        text:'#cce7ff',
                        border:'#1a3a5c'
                    }
                },
                {
                    key:'rose',
                    label:'Rose',
                    icon:'pi pi-heart',
                    description:'Warm and vibrant',
                    preview:{
                        bg:'#fff5f7',
                        sidebar:'#1a0a0f',
                        accent:'#f43f5e',
                        muted:'#fce7eb',
                        card:'#fff',
                        text:'#1e0a0f',
                        border:'#fce7eb'
                    }
                },
            ],

            themeKeys: ['light','dark','ocean','rose','Default'],

            presetColors: [
                '#6366f1',
                '#8b5cf6',
                '#ec4899',
                '#f43f5e',
                '#f97316',
                '#f59e0b',
                '#22c55e',
                '#14b8a6',
                '#06b6d4',
                '#3b82f6'
            ],

            userOverrides: [
                {
                    name:'Arjun Sharma',
                    email:'arjun@ex.com',
                    initials:'AS',
                    color:'#6366f1',
                    theme:'dark'
                },
                {
                    name:'Priya Mehta',
                    email:'priya@ex.com',
                    initials:'PM',
                    color:'#f59e0b',
                    theme:'light'
                },
                {
                    name:'Rahul Gupta',
                    email:'rahul@ex.com',
                    initials:'RG',
                    color:'#3b82f6',
                    theme:null
                },
            ],

            displayPrefs: [
                {
                    key:'animations',
                    label:'Enable Animations',
                    value:true
                },
                {
                    key:'compact',
                    label:'Compact Mode',
                    value:false
                },
                {
                    key:'sidebar_icons',
                    label:'Sidebar Icons Only',
                    value:false
                },
                {
                    key:'breadcrumbs',
                    label:'Show Breadcrumbs',
                    value:true
                },
            ],
        };
    },

    methods: {
        applyTheme(key) {
            this.active = key;

            document.documentElement.className = key;

            localStorage.setItem('admin-theme', key);

            const themeMap = {
                light:'aura-light-blue',
                dark:'aura-dark-blue',
                ocean:'aura-dark-cyan',
                rose:'aura-light-pink'
            };

            const link = document.getElementById('theme-link');

            if (link) {
                link.href = `https://unpkg.com/primevue@4/resources/themes/${themeMap[key]}/theme.css`;
            }
        },

        applyCustomColor() {
            document.documentElement.style.setProperty('--accent', this.customAccent);

            alert(`Custom accent color ${this.customAccent} applied!`);
        },

        saveOverrides() {
            alert('User theme overrides saved!');
        },
    },
});

</script>
@endPushOnce
</x-admin::layouts>
