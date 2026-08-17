<x-admin::layouts>
<div class="page-header">
    <h1 class="page-title">Theme Provider</h1>
    <div class="page-breadcrumb">Home / Theme Provider</div>
</div>

<v-themes></v-themes>

@pushOnce('scripts')
<script type="text/x-template" id="v-themes-template">
<div class="tp-root">

    {{-- ── TOP STATUS BAR ────────────────────────────────── --}}
    <div class="tp-topbar">
        <div class="tp-topbar-left">
            <div class="tp-status-dot" :style="{ background: activeTheme.accent }"></div>
            <span class="tp-status-text">
                Active: <strong>@{{ activeTheme.label }}</strong> theme
            </span>
            <code class="tp-status-chip" :style="{ background: activeTheme.accentLight, color: activeTheme.accent }">@{{ activeTheme.className }}</code>
        </div>
        <div style="display:flex; gap:8px;">
            <Button label="Export CSS" icon="pi pi-download" size="small" severity="secondary" outlined @click="showExportDialog = true; exportTheme = currentKey" />
            <Button label="Save Changes" icon="pi pi-check" size="small" @click="saveTheme" />
        </div>
    </div>

    {{-- ── MAIN LAYOUT ────────────────────────────────────── --}}
    <div class="grid grid-cols-1 gap-5 items-start lg:grid-cols-[1fr_1.45fr]">

        {{-- ════ LEFT COLUMN ════ --}}
        <div class="flex flex-col gap-4">

            {{-- Theme Selector Cards --}}
            <div class="tp-panel">
                <div class="tp-panel-header">
                    <span class="tp-panel-title"><i class="pi pi-palette"></i> Select Theme</span>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2">
                    <div
                        v-for="t in themes" :key="t.key"
                        class="tp-theme-card"
                        :class="{ 'is-active': currentKey === t.key }"
                        @click="applyTheme(t.key)"
                    >
                        {{-- Preview render --}}
                        <div class="tp-preview-wrap" :style="{ background: t.bg }">
                            <div class="tp-preview-sidebar" :style="{ background: t.sidebar }">
                                <div class="tp-preview-logo" :style="{ background: t.accent }"></div>
                                <div v-for="(w, i) in [80,60,75,55,70]" :key="i"
                                    class="tp-preview-nav-item"
                                    :style="{
                                        width: w+'%',
                                        background: i === 0 ? t.accent : t.sidebarText,
                                        opacity: i === 0 ? 0.9 : 0.28,
                                    }"
                                ></div>
                            </div>
                            <div class="tp-preview-main">
                                <div class="tp-preview-header-bar" :style="{ background: t.surface, borderColor: t.border }"></div>
                                <div class="tp-preview-body-area">
                                    <div class="tp-preview-stat-row">
                                        <div v-for="color in [t.accent, t.success, t.warning, t.info]" :key="color"
                                            class="tp-preview-stat-card"
                                            :style="{ background: t.surface, borderColor: t.border }"
                                        >
                                            <div class="tp-preview-stat-pip" :style="{ background: color }"></div>
                                        </div>
                                    </div>
                                    <div class="tp-preview-table-mock" :style="{ background: t.surface, borderColor: t.border }">
                                        <div v-for="r in 3" :key="r" class="tp-preview-table-row"
                                            :style="{ background: r % 2 !== 0 ? t.subtle : 'transparent' }">
                                            <div class="tp-preview-table-cell" :style="{ background: t.textMuted, opacity: .35 }"></div>
                                            <div class="tp-preview-table-cell wide" :style="{ background: t.textMuted, opacity: .2 }"></div>
                                            <div class="tp-preview-table-cell pill"
                                                :style="{
                                                    background: r===1 ? t.success : r===2 ? t.warning : t.accent,
                                                    opacity: 0.75
                                                }"
                                            ></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <transition name="badge-pop">
                                <div v-if="currentKey === t.key" class="tp-active-badge" :style="{ background: t.accent }">
                                    <i class="pi pi-check"></i>
                                </div>
                            </transition>
                        </div>
                        <div class="tp-card-meta">
                            <div class="tp-card-label">@{{ t.label }}</div>
                            <div class="tp-card-desc">@{{ t.desc }}</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Accent Color Picker --}}
            <div class="tp-panel">
                <div class="tp-panel-header">
                    <span class="tp-panel-title"><i class="pi pi-circle-fill" style="font-size:10px;"></i> Custom Accent</span>
                    <code class="tp-accent-current" :style="{ color: customAccent }">@{{ customAccent }}</code>
                </div>
                <div class="tp-accent-swatches">
                    <div
                        v-for="c in presetColors" :key="c"
                        class="tp-swatch"
                        :class="{ selected: customAccent === c }"
                        :style="{ background: c }"
                        @click="customAccent = c; doApplyAccent()"
                        :title="c"
                    ></div>
                    <label class="tp-swatch tp-swatch-picker" title="Custom colour">
                        <i class="pi pi-pencil"></i>
                        <input type="color" v-model="customAccent" @input="doApplyAccent" class="tp-color-input" />
                    </label>
                </div>
                <div class="tp-accent-demo" :style="{ borderColor: hexAlpha(customAccent, .2) }">
                    <div class="tp-accent-demo-bar" :style="{ background: customAccent }"></div>
                    <div style="flex:1;">
                        <div class="tp-accent-demo-chips">
                            <span :style="{ background: hexAlpha(customAccent, .12), color: customAccent, fontSize:'11px', padding:'3px 9px', borderRadius:'99px', fontWeight:'600' }">Active bg</span>
                            <span :style="{ background: hexAlpha(customAccent, .08), color: customAccent, fontSize:'11px', padding:'3px 9px', borderRadius:'99px', fontWeight:'600' }">Light fill</span>
                        </div>
                        <div style="font-size:11px; color:var(--text-muted); margin-top:4px;">Applies <code style="color:var(--text-base)">--accent</code> and <code style="color:var(--text-base)">--active</code> live</div>
                    </div>
                </div>
            </div>

            {{-- Display Preferences --}}
            <div class="tp-panel">
                <div class="tp-panel-header">
                    <span class="tp-panel-title"><i class="pi pi-sliders-h"></i> Display Preferences</span>
                </div>
                <div>
                    <div v-for="pref in displayPrefs" :key="pref.key" class="tp-pref-item">
                        <div style="display:flex; align-items:flex-start; gap:10px; flex:1;">
                            <i :class="['pi', pref.icon]" style="font-size:15px; color:var(--accent); margin-top:1px; flex-shrink:0;"></i>
                            <div>
                                <div style="font-size:13px; font-weight:500; color:var(--text-base);">@{{ pref.label }}</div>
                                <div style="font-size:11.5px; color:var(--text-muted); margin-top:1px;">@{{ pref.description }}</div>
                            </div>
                        </div>
                        <ToggleSwitch v-model="pref.value" />
                    </div>
                </div>
            </div>

            {{-- Font Size --}}
            <div class="tp-panel">
                <div class="tp-panel-header">
                    <span class="tp-panel-title"><i class="pi pi-text"></i> Font Size</span>
                    <span class="tp-size-badge">@{{ fontSize }}px</span>
                </div>
                <div style="padding:14px 18px 4px;">
                    <div style="display:flex; align-items:center; gap:12px; margin-bottom:10px;">
                        <span style="font-size:11px; color:var(--text-muted); font-weight:600;">Aa</span>
                        <div style="flex:1;">
                            <Slider v-model="fontSize" :min="12" :max="18" :step="1" @change="doApplyFont" />
                        </div>
                        <span style="font-size:17px; color:var(--text-muted); font-weight:600;">Aa</span>
                    </div>
                    <div style="display:flex; gap:6px; margin-bottom:12px;">
                        <button v-for="sz in [12,13,14,15,16]" :key="sz"
                            @click="fontSize = sz; doApplyFont()"
                            :style="{
                                padding:'4px 10px', borderRadius:'6px', border:'1px solid',
                                borderColor: fontSize === sz ? 'var(--accent)' : 'var(--border)',
                                background: fontSize === sz ? 'var(--accent)' : 'transparent',
                                color: fontSize === sz ? '#fff' : 'var(--text-muted)',
                                fontSize:'11.5px', fontWeight:'500', cursor:'pointer', fontFamily:'inherit',
                            }"
                        >@{{ sz }}px</button>
                    </div>
                    <div :style="{
                        fontSize: fontSize+'px',
                        padding:'10px 12px', borderRadius:'8px',
                        background:'var(--bg-subtle)', border:'1px solid var(--border)',
                        color:'var(--text-muted)', fontStyle:'italic', marginBottom:'14px',
                        transition:'font-size .2s'
                    }">
                        The quick brown fox jumps over the lazy dog
                    </div>
                </div>
            </div>

        </div>{{-- /col-left --}}

        {{-- ════ RIGHT COLUMN ════ --}}
        <div class="flex flex-col gap-4">

            {{-- Token Explorer --}}
            <div class="tp-panel">
                <div class="tp-panel-header" style="flex-wrap:wrap; gap:8px;">
                    <span class="tp-panel-title"><i class="pi pi-code"></i> CSS Token Explorer</span>
                    <div class="tp-tab-group">
                        <button v-for="tab in tokenTabs" :key="tab.key"
                            class="tp-tab"
                            :class="{ active: activeTokenTab === tab.key }"
                            @click="activeTokenTab = tab.key"
                        >@{{ tab.label }}</button>
                    </div>
                </div>
                <div class="tp-token-list">
                    <div v-for="token in filteredTokens" :key="token.name"
                        class="tp-token-row"
                        @click="copyToken(token.name)"
                        :title="'Click to copy var('+token.name+')'"
                    >
                        <div class="tp-token-swatch" :style="{ background: token.value }"></div>
                        <div style="flex:1; min-width:0;">
                            <code class="tp-token-varname">@{{ token.name }}</code>
                            <span class="tp-token-rawval">@{{ token.value }}</span>
                        </div>
                        <span class="tp-token-copy-hint">
                            <i class="pi pi-copy" style="font-size:11px;"></i>
                        </span>
                    </div>
                </div>
                <div v-if="copyFeedback" class="tp-copy-toast">
                    <i class="pi pi-check-circle"></i> Copied to clipboard
                </div>
            </div>

            {{-- Live Dashboard Preview --}}
            <div class="tp-panel">
                <div class="tp-panel-header">
                    <span class="tp-panel-title"><i class="pi pi-desktop"></i> Live Dashboard Preview</span>
                    <Tag :value="activeTheme.label + ' theme'" style="font-size:10px;" />
                </div>
                <div class="tp-live-outer">
                    <div class="tp-live-frame" :style="{ background: activeTheme.bg }">

                        {{-- Sidebar --}}
                        <div class="tp-lp-sidebar" :style="{ background: activeTheme.sidebar }">
                            <div class="tp-lp-logo-mark" :style="{ background: activeTheme.accent }">A</div>
                            <div v-for="(nav, i) in previewNav" :key="i"
                                class="tp-lp-nav-row"
                                :style="{
                                    background: i===0 ? activeTheme.sidebarActiveBg : 'transparent',
                                    color: i===0 ? activeTheme.sidebarActiveText : activeTheme.sidebarText,
                                }"
                            >
                                <i :class="nav.icon" class="tp-lp-nav-icon"></i>
                                <span>@{{ nav.label }}</span>
                            </div>
                        </div>

                        {{-- Main --}}
                        <div style="flex:1; display:flex; flex-direction:column; overflow:hidden;">
                            {{-- Header --}}
                            <div class="tp-lp-header" :style="{ background: activeTheme.surface, borderColor: activeTheme.border }">
                                <div class="tp-lp-search-bar" :style="{ background: activeTheme.subtle, borderColor: activeTheme.border, color: activeTheme.textMuted }">
                                    <i class="pi pi-search" style="font-size:9px; margin-right:4px;"></i> Search…
                                </div>
                                <div class="tp-lp-header-actions">
                                    <div class="tp-lp-icon-pill" :style="{ borderColor: activeTheme.border }">
                                        <i class="pi pi-bell" style="font-size:9px;"></i>
                                    </div>
                                    <div class="tp-lp-user-dot" :style="{ background: activeTheme.accent }">U</div>
                                </div>
                            </div>

                            {{-- Stats --}}
                            <div class="tp-lp-stats-row">
                                <div v-for="(stat,i) in previewStats" :key="i"
                                    class="tp-lp-stat-card"
                                    :style="{ background: activeTheme.surface, borderColor: activeTheme.border }"
                                >
                                    <div class="tp-lp-stat-icon-wrap"
                                        :style="{ background: hexAlpha(stat.color, .12), color: stat.color }">
                                        <i :class="stat.icon" style="font-size:9px;"></i>
                                    </div>
                                    <div class="tp-lp-stat-val" :style="{ color: activeTheme.text }">@{{ stat.value }}</div>
                                    <div class="tp-lp-stat-lbl" :style="{ color: activeTheme.textMuted }">@{{ stat.label }}</div>
                                </div>
                            </div>

                            {{-- Table --}}
                            <div class="tp-lp-table-wrap"
                                :style="{ background: activeTheme.surface, borderColor: activeTheme.border }">
                                <div class="tp-lp-thead" :style="{ background: activeTheme.subtle, borderColor: activeTheme.border }">
                                    <span v-for="h in ['#','Name','Role','Status']" :key="h"
                                        class="tp-lp-th"
                                        :style="{ color: activeTheme.textMuted }"
                                    >@{{ h }}</span>
                                </div>
                                <div v-for="(row,ri) in previewRows" :key="ri"
                                    class="tp-lp-trow"
                                    :style="{
                                        borderColor: activeTheme.border,
                                        background: ri%2!==0 ? activeTheme.subtle : 'transparent'
                                    }"
                                >
                                    <span class="tp-lp-td" :style="{ color: activeTheme.textMuted }">@{{ row.id }}</span>
                                    <span class="tp-lp-td" style="display:flex;align-items:center;gap:4px;">
                                        <span class="tp-lp-av" :style="{ background: row.color }">@{{ row.init }}</span>
                                        <span :style="{ color: activeTheme.text, fontWeight:'500', fontSize:'9px' }">@{{ row.name }}</span>
                                    </span>
                                    <span class="tp-lp-td" :style="{ color: activeTheme.textMuted }">@{{ row.role }}</span>
                                    <span class="tp-lp-td">
                                        <span class="tp-lp-status-badge"
                                            :style="{
                                                background: hexAlpha(row.active ? activeTheme.success : activeTheme.textMuted, .12),
                                                color: row.active ? activeTheme.success : activeTheme.textMuted
                                            }"
                                        >@{{ row.active ? 'Active' : 'Off' }}</span>
                                    </span>
                                </div>
                            </div>

                        </div>{{-- /main --}}
                    </div>{{-- /tp-live-frame --}}
                </div>{{-- /tp-live-outer --}}
            </div>

            {{-- Token shimmer preview --}}
            <div class="tp-panel">
                <div class="tp-panel-header">
                    <span class="tp-panel-title"><i class="pi pi-sparkles"></i> Shimmer / Skeleton</span>
                    <span style="font-size:11px; color:var(--text-muted);">uses --shimmer1 & --shimmer2</span>
                </div>
                <div style="padding:14px 18px;">
                    <div class="tp-shimmer-demo">
                        <div v-for="n in 3" :key="n" class="tp-shimmer-row">
                            <div class="tp-shimmer-circle shimmer-anim"
                                :style="{ '--sh1': activeTheme.shimmer1, '--sh2': activeTheme.shimmer2 }"></div>
                            <div style="flex:1; display:flex; flex-direction:column; gap:5px;">
                                <div class="tp-shimmer-bar shimmer-anim"
                                    :style="{ width: [75,60,85][n-1]+'%', '--sh1': activeTheme.shimmer1, '--sh2': activeTheme.shimmer2 }"></div>
                                <div class="tp-shimmer-bar shimmer-anim"
                                    :style="{ width: [50,40,60][n-1]+'%', '--sh1': activeTheme.shimmer1, '--sh2': activeTheme.shimmer2 }"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Per-User Overrides --}}
            <div class="tp-panel">
                <div class="tp-panel-header">
                    <span class="tp-panel-title"><i class="pi pi-users"></i> Per-User Theme Overrides</span>
                    <Button label="Save All" size="small" @click="saveUserOverrides" />
                </div>
                <div>
                    <div v-for="user in userOverrides" :key="user.id" class="tp-user-row">
                        <div class="tp-user-av" :style="{ background: user.color }">@{{ user.initials }}</div>
                        <div style="flex:1; min-width:0;">
                            <div style="font-size:13px; font-weight:500; color:var(--text-base);">@{{ user.name }}</div>
                            <div style="font-size:11px; color:var(--text-muted); overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">@{{ user.email }}</div>
                        </div>
                        <Select
                            v-model="user.theme"
                            :options="themeOpts"
                            optionLabel="label"
                            optionValue="key"
                            placeholder="Default"
                            style="width:116px; font-size:12px;"
                        >
                            <template #option="{ option }">
                                <div style="display:flex; align-items:center; gap:7px; font-size:12px;">
                                    <div :style="{ width:'9px', height:'9px', borderRadius:'3px', background: themeAccentMap[option.key], flexShrink:0 }"></div>
                                    @{{ option.label }}
                                </div>
                            </template>
                        </Select>
                        <transition name="fade-tag">
                            <Tag v-if="user.theme" :value="user.theme" severity="secondary" style="font-size:10px; height:22px;" />
                        </transition>
                    </div>
                </div>
            </div>

        </div>{{-- /col-right --}}
    </div>{{-- /tp-layout --}}

    {{-- Export CSS Dialog --}}
    <Dialog v-model:visible="showExportDialog" header="Export CSS Variables" :style="{ width: '580px', maxWidth: '95vw' }" modal>
        <div style="padding-top:10px;">
            <div style="display:flex; gap:6px; margin-bottom:14px; flex-wrap:wrap;">
                <Button v-for="k in ['light','dark','ocean','rose']" :key="k"
                    :label="k.charAt(0).toUpperCase()+k.slice(1)"
                    size="small"
                    :severity="exportTheme === k ? 'primary' : 'secondary'"
                    :outlined="exportTheme !== k"
                    @click="exportTheme = k"
                />
            </div>
            <pre class="tp-export-pre">@{{ buildExportCSS(exportTheme) }}</pre>
        </div>
        <template #footer>
            <Button label="Copy CSS" icon="pi pi-copy" @click="copyExportedCSS" />
            <Button label="Close" severity="secondary" text @click="showExportDialog = false" />
        </template>
    </Dialog>

</div>
</script>

<style>
/* ── Root & Layout ─────────────────────────────── */
.tp-root { display:flex; flex-direction:column; gap:0; }

.tp-topbar {
    display:flex; align-items:center; justify-content:space-between;
    padding:12px 20px; background:var(--bg-surface); border:1px solid var(--border);
    border-radius:var(--radius); margin-bottom:20px; box-shadow:var(--shadow);
    flex-wrap:wrap; gap:10px;
}
.tp-topbar-left { display:flex; align-items:center; gap:10px; }
.tp-status-dot { width:8px; height:8px; border-radius:50%; flex-shrink:0; transition:background .3s; }
.tp-status-text { font-size:13px; color:var(--text-muted); }
.tp-status-text strong { color:var(--text-base); }
.tp-status-chip {
    font-size:11px; font-weight:600; padding:3px 10px; border-radius:99px;
    font-family:monospace; transition:all .3s;
}

/* ── Panel ─────────────────────────────────────── */
.tp-panel {
    background:var(--bg-surface); border:1px solid var(--border);
    border-radius:var(--radius); box-shadow:var(--shadow); overflow:hidden;
}
.tp-panel-header {
    display:flex; align-items:center; justify-content:space-between;
    padding:13px 18px; border-bottom:1px solid var(--border);
    background:var(--bg-subtle);
}
.tp-panel-title {
    font-size:13px; font-weight:600; color:var(--text-base);
    display:flex; align-items:center; gap:7px;
}
.tp-panel-title i { color:var(--accent); }

/* ── Theme Cards ───────────────────────────────── */
.tp-theme-card {
    cursor:pointer; position:relative; transition:background .15s;
    border-bottom:1px solid var(--border);
}
@media(min-width: 640px) {
    .tp-theme-card { border-right:1px solid var(--border); }
    .tp-theme-card:nth-child(2n) { border-right:none; }
    .tp-theme-card:nth-last-child(-n+2):nth-child(odd),
    .tp-theme-card:nth-last-child(-n+2):nth-child(odd) ~ .tp-theme-card,
    .tp-theme-card:last-child { border-bottom:none; }
}
@media(max-width: 639px) {
    .tp-theme-card:last-child { border-bottom:none; }
}
.tp-theme-card:hover { background:var(--bg-subtle); }
.tp-theme-card.is-active { background:var(--accent-light); }
.tp-theme-card.is-active::after {
    content:''; position:absolute; inset:0; border:2px solid var(--accent);
    pointer-events:none; z-index:2;
}

.tp-preview-wrap {
    height:106px; display:flex; position:relative; overflow:hidden;
    transition:background .3s;
}
.tp-preview-sidebar {
    width:34px; flex-shrink:0; padding:7px 5px;
    display:flex; flex-direction:column; gap:5px; transition:background .3s;
}
.tp-preview-logo {
    width:18px; height:18px; border-radius:5px; margin-bottom:6px;
    transition:background .3s;
}
.tp-preview-nav-item { height:4px; border-radius:2px; transition:all .3s; }
.tp-preview-main { flex:1; display:flex; flex-direction:column; overflow:hidden; }
.tp-preview-header-bar {
    height:13px; flex-shrink:0;
    border-bottom-width:.5px; border-bottom-style:solid; transition:all .3s;
}
.tp-preview-body-area { padding:5px 6px; flex:1; }
.tp-preview-stat-row { display:flex; gap:3px; margin-bottom:4px; }
.tp-preview-stat-card {
    flex:1; height:20px; border-radius:3px; border-width:.5px; border-style:solid;
    display:flex; align-items:center; justify-content:center; transition:all .3s;
}
.tp-preview-stat-pip { width:5px; height:5px; border-radius:50%; }
.tp-preview-table-mock {
    border-radius:3px; border-width:.5px; border-style:solid;
    overflow:hidden; transition:all .3s;
}
.tp-preview-table-row {
    display:flex; align-items:center; gap:4px; padding:2.5px 4px; transition:background .3s;
}
.tp-preview-table-cell { height:4.5px; border-radius:2px; flex:1; }
.tp-preview-table-cell.wide { flex:2; }
.tp-preview-table-cell.pill { border-radius:99px !important; }

.tp-active-badge {
    position:absolute; bottom:6px; right:6px; width:18px; height:18px;
    border-radius:50%; display:flex; align-items:center; justify-content:center;
    color:#fff; font-size:9px; transition:background .3s;
}
.badge-pop-enter-active { animation: badgeIn .2s cubic-bezier(.34,1.56,.64,1) both; }
@keyframes badgeIn { from { transform:scale(0); opacity:0; } to { transform:scale(1); opacity:1; } }

.tp-card-meta { padding:10px 14px; }
.tp-card-label { font-size:12.5px; font-weight:600; color:var(--text-base); }
.tp-card-desc { font-size:11px; color:var(--text-muted); margin-top:1px; }

/* ── Accent ─────────────────────────────────────── */
.tp-accent-current { font-size:11px; font-family:monospace; }
.tp-accent-swatches {
    display:flex; flex-wrap:wrap; gap:7px; padding:14px 18px;
    border-bottom:1px solid var(--border);
}
.tp-swatch {
    width:27px; height:27px; border-radius:7px; cursor:pointer;
    border:2px solid transparent; transition:transform .15s, border-color .15s, box-shadow .15s;
    flex-shrink:0;
}
.tp-swatch:hover { transform:scale(1.1); }
.tp-swatch.selected {
    border-color:var(--text-base);
    box-shadow:0 0 0 2px var(--bg-surface);
    transform:scale(1.05);
}
.tp-swatch-picker {
    display:flex; align-items:center; justify-content:center;
    background:var(--bg-subtle); border-color:var(--border) !important;
    position:relative; overflow:hidden; cursor:pointer;
    font-size:11px; color:var(--text-muted);
}
.tp-color-input {
    position:absolute; inset:-5px; opacity:0;
    width:calc(100% + 10px); height:calc(100% + 10px); cursor:pointer; border:none;
}
.tp-accent-demo {
    display:flex; align-items:flex-start; gap:12px;
    padding:12px 18px 14px; border-top:1px solid var(--border);
}
.tp-accent-demo-bar { width:4px; height:36px; border-radius:2px; flex-shrink:0; transition:background .3s; }
.tp-accent-demo-chips { display:flex; gap:6px; flex-wrap:wrap; margin-bottom:5px; }

/* ── Prefs ──────────────────────────────────────── */
.tp-pref-item {
    display:flex; align-items:center; justify-content:space-between;
    padding:11px 18px; transition:background .15s; gap:12px;
}
.tp-pref-item:hover { background:var(--bg-subtle); }
.tp-pref-item + .tp-pref-item { border-top:1px solid var(--border); }
.tp-size-badge {
    font-size:12px; font-weight:700; color:var(--accent);
    background:var(--accent-light); padding:2px 10px; border-radius:99px;
}

/* ── Token Explorer ─────────────────────────────── */
.tp-tab-group { display:flex; }
.tp-tab {
    padding:4px 11px; border:1px solid var(--border); background:transparent;
    font-size:11.5px; font-weight:500; color:var(--text-muted); cursor:pointer;
    font-family:inherit; transition:all .15s; margin-left:-1px;
}
.tp-tab:first-child { border-radius:6px 0 0 6px; margin-left:0; }
.tp-tab:last-child  { border-radius:0 6px 6px 0; }
.tp-tab.active { background:var(--accent); color:#fff; border-color:var(--accent); z-index:1; }

.tp-token-list { max-height:280px; overflow-y:auto; }
.tp-token-row {
    display:flex; align-items:center; gap:10px; padding:8px 18px;
    cursor:pointer; transition:background .12s;
}
.tp-token-row:hover { background:var(--bg-subtle); }
.tp-token-swatch {
    width:26px; height:26px; border-radius:6px; flex-shrink:0;
    border:1px solid rgba(0,0,0,.08);
}
.tp-token-varname {
    font-size:12px; color:var(--accent); display:block; font-family:monospace;
}
.tp-token-rawval {
    font-size:11px; color:var(--text-muted); display:block; font-family:monospace;
    margin-top:1px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; max-width:200px;
}
.tp-token-copy-hint {
    color:var(--text-muted); opacity:0; transition:opacity .15s; font-size:11px;
}
.tp-token-row:hover .tp-token-copy-hint { opacity:1; }
.tp-copy-toast {
    margin:8px 18px; padding:8px 12px; border-radius:8px;
    background:rgba(34,197,94,.1); border:1px solid rgba(34,197,94,.2);
    color:var(--success); font-size:12px; display:flex; align-items:center; gap:6px;
    animation: toastIn .2s ease both;
}
@keyframes toastIn { from{opacity:0;transform:translateY(4px)} to{opacity:1;transform:none} }

/* ── Live Preview ────────────────────────────────── */
.tp-live-outer { padding:14px 18px; }
.tp-live-frame {
    border-radius:10px; overflow:hidden;
    border:.5px solid rgba(0,0,0,.08);
    display:flex; height:252px; transition:background .3s;
}
.tp-lp-sidebar {
    width:84px; flex-shrink:0; padding:10px 7px;
    display:flex; flex-direction:column; gap:2px; transition:background .3s;
}
.tp-lp-logo-mark {
    width:22px; height:22px; border-radius:6px;
    display:flex; align-items:center; justify-content:center;
    color:#fff; font-size:9px; font-weight:700;
    margin-bottom:10px; margin-left:2px; transition:background .3s;
}
.tp-lp-nav-row {
    display:flex; align-items:center; gap:5px; padding:5px 5px;
    border-radius:5px; font-size:8.5px; font-weight:500;
    transition:all .3s;
}
.tp-lp-nav-icon { font-size:9px; flex-shrink:0; }
.tp-lp-header {
    height:34px; flex-shrink:0; display:flex; align-items:center;
    padding:0 10px; gap:8px;
    border-bottom-width:.5px; border-bottom-style:solid; transition:all .3s;
}
.tp-lp-search-bar {
    display:flex; align-items:center; height:20px; border-radius:5px;
    border-width:.5px; border-style:solid; padding:0 7px;
    font-size:8.5px; width:110px; transition:all .3s;
}
.tp-lp-header-actions { display:flex; gap:5px; align-items:center; margin-left:auto; }
.tp-lp-icon-pill {
    width:20px; height:20px; border-radius:4px;
    border-width:.5px; border-style:solid;
    display:flex; align-items:center; justify-content:center;
}
.tp-lp-user-dot {
    width:20px; height:20px; border-radius:50%;
    display:flex; align-items:center; justify-content:center;
    color:#fff; font-size:7.5px; font-weight:700; transition:background .3s;
}
.tp-lp-stats-row { display:flex; gap:6px; padding:7px 10px; flex-shrink:0; }
.tp-lp-stat-card {
    flex:1; padding:5px 7px; border-radius:5px;
    border-width:.5px; border-style:solid; transition:all .3s;
}
.tp-lp-stat-icon-wrap {
    width:16px; height:16px; border-radius:4px;
    display:flex; align-items:center; justify-content:center;
    margin-bottom:3px; transition:all .3s;
}
.tp-lp-stat-val { font-size:11px; font-weight:700; transition:color .3s; }
.tp-lp-stat-lbl { font-size:8px; margin-top:1px; transition:color .3s; }
.tp-lp-table-wrap {
    margin:0 8px 8px; border-radius:6px;
    border-width:.5px; border-style:solid; overflow:hidden; transition:all .3s;
}
.tp-lp-thead {
    display:flex; padding:4px 8px;
    border-bottom-width:.5px; border-bottom-style:solid; transition:all .3s;
}
.tp-lp-th { flex:1; font-size:7.5px; font-weight:700; text-transform:uppercase; letter-spacing:.4px; transition:color .3s; }
.tp-lp-trow { display:flex; align-items:center; padding:4px 8px; border-bottom-width:.5px; border-bottom-style:solid; transition:all .3s; }
.tp-lp-trow:last-child { border-bottom:none !important; }
.tp-lp-td { flex:1; font-size:8.5px; display:flex; align-items:center; gap:4px; transition:color .3s; }
.tp-lp-av {
    width:13px; height:13px; border-radius:50%; flex-shrink:0;
    display:flex; align-items:center; justify-content:center;
    color:#fff; font-size:6.5px; font-weight:700;
}
.tp-lp-status-badge { font-size:7.5px; font-weight:600; padding:1.5px 5px; border-radius:99px; }

/* ── Shimmer ────────────────────────────────────── */
.tp-shimmer-demo { display:flex; flex-direction:column; gap:12px; }
.tp-shimmer-row { display:flex; align-items:center; gap:10px; }
.tp-shimmer-circle {
    width:34px; height:34px; border-radius:50%; flex-shrink:0;
}
.tp-shimmer-bar { height:10px; border-radius:5px; }
.shimmer-anim {
    background: linear-gradient(90deg, var(--sh1) 25%, var(--sh2) 50%, var(--sh1) 75%);
    background-size: 200% 100%;
    animation: shimmer 1.4s infinite linear;
}
@keyframes shimmer { to { background-position: -200% 0; } }

/* ── User Overrides ─────────────────────────────── */
.tp-user-row {
    display:flex; align-items:center; gap:10px; padding:10px 18px;
    transition:background .15s;
}
.tp-user-row + .tp-user-row { border-top:1px solid var(--border); }
.tp-user-row:hover { background:var(--bg-subtle); }
.tp-user-av {
    width:32px; height:32px; border-radius:50%; flex-shrink:0;
    display:flex; align-items:center; justify-content:center;
    color:#fff; font-size:11px; font-weight:700;
}
.fade-tag-enter-active { transition:opacity .2s, transform .2s; }
.fade-tag-enter-from { opacity:0; transform:scale(.8); }

/* ── Export ─────────────────────────────────────── */
.tp-export-pre {
    background:var(--bg-subtle); border:1px solid var(--border);
    border-radius:8px; padding:14px; font-size:11.5px;
    font-family:monospace; line-height:1.75; color:var(--text-base);
    overflow:auto; max-height:320px; white-space:pre;
}
</style>

<script type="module">
adminVueApp.component('v-themes', {
    template: '#v-themes-template',

    data() {
        return {
            themes: [
                {
                    key:'light',
                    label:'Light',
                    desc:'Clean & minimal',
                    className:'html.light',
                    bg:'#f4f6fb',
                    surface:'#ffffff',
                    subtle:'#eef1f8',
                    sidebar:'#0f172a',
                    border:'#e2e8f0',
                    text:'#1e293b',
                    textMuted:'#64748b',
                    accent:'#6366f1',
                    accentHover:'#4f46e5',
                    accentLight:'#eef2ff',
                    active:'#6366f1',
                    sidebarText:'#94a3b8',
                    sidebarActiveText:'#818cf8',
                    sidebarActiveBg:'rgba(99,102,241,.15)',
                    danger:'#ef4444',
                    success:'#22c55e',
                    warning:'#f59e0b',
                    info:'#3b82f6',
                    shimmer1:'#fbfbfb',
                    shimmer2:'#e9e3e3',
                    pvTheme:'aura-light-blue',
                },

                {
                    key:'dark',
                    label:'Dark',
                    desc:'Easy on the eyes',
                    className:'html.dark',
                    bg:'#0b0f1a',
                    surface:'#131929',
                    subtle:'#1a2235',
                    sidebar:'#0d1117',
                    border:'#1e2d45',
                    text:'#e2e8f0',
                    textMuted:'#64748b',
                    accent:'#6366f1',
                    accentHover:'#818cf8',
                    accentLight:'rgba(99,102,241,.15)',
                    active:'#6366f1',
                    sidebarText:'#64748b',
                    sidebarActiveText:'#818cf8',
                    sidebarActiveBg:'rgba(99,102,241,.2)',
                    danger:'#f87171',
                    success:'#4ade80',
                    warning:'#fbbf24',
                    info:'#60a5fa',
                    shimmer1:'#252f47',
                    shimmer2:'#303c5a',
                    pvTheme:'aura-dark-blue',
                },
            ],

            currentKey: localStorage.getItem('admin-theme') || 'light',

            customAccent: '#6366f1',

            fontSize: 14,

            copyFeedback: false,

            showExportDialog: false,

            exportTheme: 'light',

            presetColors: [
                '#6366f1','#8b5cf6','#a855f7','#ec4899',
                '#f43f5e','#f97316','#f59e0b','#eab308',
                '#22c55e','#10b981','#06b6d4','#3b82f6',
            ],

            displayPrefs: [
                {
                    key:'animations',
                    icon:'pi-play',
                    label:'Enable Animations',
                    description:'Transitions and micro-interactions',
                    value:true
                },

                {
                    key:'compact',
                    icon:'pi-compress',
                    label:'Compact Mode',
                    description:'Less spacing, more content density',
                    value:false
                },
            ],

            tokenTabs: [
                { key:'all', label:'All' },
                { key:'bg', label:'Backgrounds' },
                { key:'text', label:'Typography' },
                { key:'accent', label:'Accent' },
                { key:'status', label:'Status & FX' },
            ],

            activeTokenTab: 'all',

            previewNav: [
                { icon:'pi pi-home', label:'Dashboard' },
                { icon:'pi pi-users', label:'Users' },
            ],

            previewRows: [
                {
                    id:'1',
                    name:'Arjun S.',
                    role:'Admin',
                    active:true,
                    init:'AS',
                    color:'#6366f1'
                },
            ],

            userOverrides: [
                {
                    id:1,
                    name:'Arjun Sharma',
                    email:'arjun@example.com',
                    initials:'AS',
                    color:'#6366f1',
                    theme:'dark'
                },
            ],
        };
    },

    computed: {
        activeTheme() {
            return this.themes.find(
                theme => theme.key === this.currentKey
            ) || this.themes[0];
        },

        themeOpts() {
            return this.themes.map(theme => ({
                key: theme.key,
                label: theme.label
            }));
        },

        themeAccentMap() {
            return Object.fromEntries(
                this.themes.map(theme => [
                    theme.key,
                    theme.accent
                ])
            );
        },

        allTokens() {
            const t = this.activeTheme;

            return [
                { g:'bg', name:'--bg-base', value:t.bg },
                { g:'bg', name:'--bg-surface', value:t.surface },
                { g:'bg', name:'--bg-subtle', value:t.subtle },
                { g:'text', name:'--text-base', value:t.text },
                { g:'text', name:'--text-muted', value:t.textMuted },
                { g:'accent', name:'--accent', value:t.accent },
                { g:'accent', name:'--accent-hover', value:t.accentHover },
                { g:'status', name:'--danger', value:t.danger },
            ];
        },

        filteredTokens() {
            if (this.activeTokenTab === 'all') {
                return this.allTokens;
            }

            return this.allTokens.filter(token => {
                return token.g === this.activeTokenTab;
            });
        },

        previewStats() {
            const t = this.activeTheme;

            return [
                {
                    icon:'pi pi-users',
                    label:'Users',
                    value:'12.4k',
                    color:t.accent
                },

                {
                    icon:'pi pi-shield',
                    label:'Roles',
                    value:'24',
                    color:t.success
                },
            ];
        },
    },

    methods: {
        hexAlpha(hex, alpha) {
            try {
                const r = parseInt(hex.slice(1,3),16);
                const g = parseInt(hex.slice(3,5),16);
                const b = parseInt(hex.slice(5,7),16);

                if (isNaN(r) || isNaN(g) || isNaN(b)) {
                    return hex;
                }

                return `rgba(${r},${g},${b},${alpha})`;

            } catch (e) {
                return hex;
            }
        },

        applyTheme(key) {
            this.currentKey = key;

            const theme = this.themes.find(
                th => th.key === key
            );

            if (! theme) {
                return;
            }

            document.documentElement.className = key;

            localStorage.setItem('admin-theme', key);

            this.customAccent = theme.accent;

            document.documentElement.style.removeProperty('--accent');

            document.documentElement.style.removeProperty('--active');

            const link = document.getElementById('theme-link');

            if (link) {
                link.href = `https://unpkg.com/primevue@4/resources/themes/${theme.pvTheme}/theme.css`;
            }

            fetch('/admin/theme', {
                method:'POST',

                headers:{
                    'Content-Type':'application/json',

                    'X-CSRF-TOKEN':
                        document.querySelector(
                            'meta[name="csrf-token"]'
                        )?.content
                },

                body: JSON.stringify({
                    theme: key
                }),
            }).catch(() => {});
        },

        doApplyAccent() {
            document.documentElement.style.setProperty(
                '--accent',
                this.customAccent
            );

            document.documentElement.style.setProperty(
                '--active',
                this.customAccent
            );

            document.documentElement.style.setProperty(
                '--accent-hover',
                this.customAccent
            );
        },

        doApplyFont() {
            document.documentElement.style.setProperty(
                'font-size',
                this.fontSize + 'px'
            );
        },

        copyToken(name) {
            navigator.clipboard.writeText(
                `var(${name})`
            ).then(() => {

                this.copyFeedback = true;

                setTimeout(() => {
                    this.copyFeedback = false;
                }, 2000);
            });
        },

        buildExportCSS(key) {
            const theme = this.themes.find(
                th => th.key === key
            );

            if (! theme) {
                return '';
            }

            return `
html.${theme.key} {
    --bg-base: ${theme.bg};
    --bg-surface: ${theme.surface};
    --accent: ${theme.accent};
}
            `;
        },

        copyExportedCSS() {
            navigator.clipboard.writeText(
                this.buildExportCSS(this.exportTheme)
            ).then(() => {
                alert('CSS copied!');
            });
        },

        saveTheme() {
            fetch('/admin/themes', {
                method:'POST',

                headers:{
                    'Content-Type':'application/json',

                    'X-CSRF-TOKEN':
                        document.querySelector(
                            'meta[name="csrf-token"]'
                        )?.content
                },

                body: JSON.stringify({
                    theme: this.currentKey,
                    font_size: this.fontSize,
                    preferences: Object.fromEntries(
                        this.displayPrefs.map(pref => [
                            pref.key,
                            pref.value
                        ])
                    ),
                }),
            })
            .then(() => {
                alert('Theme saved!');
            })
            .catch(() => {
                alert('Saved locally.');
            });
        },

        saveUserOverrides() {
            fetch('/admin/themes/user-overrides', {
                method:'POST',

                headers:{
                    'Content-Type':'application/json',

                    'X-CSRF-TOKEN':
                        document.querySelector(
                            'meta[name="csrf-token"]'
                        )?.content
                },

                body: JSON.stringify({
                    overrides: this.userOverrides.map(user => ({
                        user_id: user.id,
                        theme: user.theme
                    }))
                }),
            })
            .then(() => {
                alert('Overrides saved!');
            })
            .catch(() => {
                alert('Saved locally.');
            });
        },
    },

    mounted() {
        const saved =
            localStorage.getItem('admin-theme')
            || 'light';

        if (saved !== this.currentKey) {
            this.applyTheme(saved);
        }

        this.customAccent = this.activeTheme.accent;
    }
});
</script>
@endPushOnce
</x-admin::layouts>
