<x-admin::layouts>
    <div class="page-header">
        <h1 class="page-title">Dashboard</h1>
        <div class="page-breadcrumb">Home / Dashboard</div>
    </div>

    <v-dashboard></v-dashboard>

    @pushOnce('scripts')
        <script type="text/x-template" id="v-dashboard-template">
                <div>
                    {{-- Stats Row --}}
                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 16px; margin-bottom: 24px;">
                        <div class="stat-card" v-for="stat in stats" :key="stat.label">
                            <div class="stat-icon" :style="{ background: stat.iconBg, color: stat.iconColor }">
                                <i :class="stat.icon"></i>
                            </div>
                            <div class="stat-value">@{{ stat.value }}</div>
                            <div class="stat-label">@{{ stat.label }}</div>
                            <div :class="['stat-delta', stat.trend]">
                                <i :class="stat.trend === 'up' ? 'pi pi-arrow-up' : 'pi pi-arrow-down'"></i>
                                @{{ stat.delta }} vs last month
                            </div>
                        </div>
                    </div>

                    {{-- Charts Row --}}
                    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 16px; margin-bottom: 24px;">
                        {{-- Activity Chart --}}
                        <div class="card">
                            <div class="card-header" style="justify-content: space-between;">
                                <span class="card-title">User Activity</span>
                                <div style="display:flex; gap:6px;">
                                    <button
                                        v-for="r in ['7d','30d','90d']"
                                        :key="r"
                                        @click="activeRange = r"
                                        :style="activeRange === r
                                            ? 'background: var(--accent); color:#fff; border-color: var(--accent);'
                                            : 'background: transparent; color: var(--text-muted); border-color: var(--border);'"
                                        style="border: 1px solid; border-radius:6px; padding:4px 10px; font-size:11px; cursor:pointer; font-family: inherit;"
                                    >@{{ r }}</button>
                                </div>
                            </div>
                            <div class="card-body">
                                <canvas ref="activityChart" height="180"></canvas>
                            </div>
                        </div>

                        {{-- Donut --}}
                        <div class="card">
                            <div class="card-header">
                                <span class="card-title">User Roles</span>
                            </div>
                            <div class="card-body" style="display:flex; flex-direction:column; align-items:center;">
                                <canvas ref="rolesChart" width="180" height="180"></canvas>
                                <div style="margin-top:16px; width:100%;">
                                    <div v-for="role in roleData" :key="role.label"
                                        style="display:flex; align-items:center; justify-content:space-between; margin-bottom:8px; font-size:13px;">
                                        <div style="display:flex; align-items:center; gap:8px;">
                                            <span :style="{ width:'10px', height:'10px', borderRadius:'3px', background: role.color, display:'inline-block' }"></span>
                                            <span style="color: var(--text-muted);">@{{ role . label }}</span>
                                        </div>
                                        <strong style="color: var(--text-base);">@{{ role . count }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Recent Users --}}
                    <div class="card">
                        <div class="card-header" style="justify-content: space-between;">
                            <span class="card-title">Recent Users</span>
                            <a href="/admin/users" style="font-size:12px; color: var(--accent); text-decoration:none;">View all →</a>
                        </div>
                        <div class="card-body" style="padding-top: 12px;">
                            <DataTable :value="recentUsers" :rows="5" stripedRows
                                style="font-size:13px;"
                                :pt="{ root: { style: 'background: transparent; border: none;' } }"
                            >
                                <Column field="name" header="Name">
                                    <template #body="{ data }">
                                        <div style="display:flex; align-items:center; gap:10px;">
                                            <div :style="{
                                                width:'32px', height:'32px', borderRadius:'50%',
                                                background: data.avatarColor,
                                                display:'flex', alignItems:'center', justifyContent:'center',
                                                color:'#fff', fontSize:'12px', fontWeight:'600', flexShrink:0
                                            }">@{{ data . initials }}</div>
                                            <div>
                                                <div style="font-weight:500; color: var(--text-base);">@{{ data . name }}</div>
                                                <div style="font-size:11px; color: var(--text-muted);">@{{ data . email }}</div>
                                            </div>
                                        </div>
                                    </template>
                                </Column>
                                <Column field="role" header="Role">
                                    <template #body="{ data }">
                                        <Tag :severity="data.role === 'Admin' ? 'danger' : data.role === 'Manager' ? 'warning' : 'info'"
                                            :value="data.role" style="font-size:11px;" />
                                    </template>
                                </Column>
                                <Column field="status" header="Status">
                                    <template #body="{ data }">
                                        <Tag :severity="data.status === 'Active' ? 'success' : 'secondary'"
                                            :value="data.status" style="font-size:11px;" />
                                    </template>
                                </Column>
                                <Column field="joined" header="Joined" />
                                <Column header="Actions">
                                    <template #body>
                                        <div style="display:flex; gap:6px;">
                                            <Button icon="pi pi-pencil" size="small" severity="secondary" text rounded />
                                            <Button icon="pi pi-trash" size="small" severity="danger" text rounded />
                                        </div>
                                    </template>
                                </Column>
                            </DataTable>
                        </div>
                    </div>
                </div>
                </script>

        <script type="module">
            adminVueApp.component('v-dashboard', {
                template: '#v-dashboard-template',

                data() {
                    return {
                        activeRange: '30d',
                        activityChart: null,
                        rolesChart: null,

                        stats: [
                            {
                                icon: 'pi pi-users', label: 'Total Users', value: '12,481', delta: '+8.4%', trend: 'up',
                                iconBg: 'rgba(99,102,241,.12)', iconColor: '#6366f1'
                            },
                            {
                                icon: 'pi pi-shield', label: 'Active Roles', value: '24', delta: '+2', trend: 'up',
                                iconBg: 'rgba(34,197,94,.12)', iconColor: '#22c55e'
                            },
                            {
                                icon: 'pi pi-building', label: 'Tenants', value: '7', delta: '+1', trend: 'up',
                                iconBg: 'rgba(245,158,11,.12)', iconColor: '#f59e0b'
                            },
                            {
                                icon: 'pi pi-server', label: 'API Requests', value: '2.4M', delta: '-3.1%', trend: 'down',
                                iconBg: 'rgba(59,130,246,.12)', iconColor: '#3b82f6'
                            },
                        ],

                        roleData: [
                            { label: 'Admin', count: 14, color: '#6366f1' },
                            { label: 'Manager', count: 87, color: '#f59e0b' },
                            { label: 'Editor', count: 213, color: '#3b82f6' },
                            { label: 'Viewer', count: 1089, color: '#22c55e' },
                        ],

                        recentUsers: [
                            { name: 'Arjun Sharma', email: 'arjun@example.com', role: 'Admin', status: 'Active', joined: 'Apr 28, 2025', initials: 'AS', avatarColor: '#6366f1' },
                            { name: 'Priya Mehta', email: 'priya@example.com', role: 'Manager', status: 'Active', joined: 'Apr 22, 2025', initials: 'PM', avatarColor: '#f59e0b' },
                            { name: 'Rahul Gupta', email: 'rahul@example.com', role: 'Editor', status: 'Inactive', joined: 'Apr 15, 2025', initials: 'RG', avatarColor: '#3b82f6' },
                            { name: 'Sneha Patel', email: 'sneha@example.com', role: 'Viewer', status: 'Active', joined: 'Apr 10, 2025', initials: 'SP', avatarColor: '#22c55e' },
                            { name: 'Vikram Singh', email: 'vikram@example.com', role: 'Manager', status: 'Active', joined: 'Apr 3, 2025', initials: 'VS', avatarColor: '#ef4444' },
                        ],
                    };
                },

                mounted() {
                    const ctx = this.$refs.activityChart?.getContext('2d');
                    if (ctx) this.drawLineChart(ctx);

                    const ctx2 = this.$refs.rolesChart?.getContext('2d');
                    if (ctx2) this.drawDonut(ctx2); 
                },

                methods: {
                    drawLineChart(ctx) {
                        const W = ctx.canvas.offsetWidth || 600;
                        ctx.canvas.width = W;
                        ctx.canvas.height = 180;

                        const data = [42, 68, 55, 90, 77, 110, 96, 130, 112, 145, 128, 162];
                        const data2 = [30, 50, 45, 70, 60, 85, 72, 100, 88, 115, 104, 138];
                        const labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

                        const pad = { t: 10, r: 20, b: 30, l: 40 };
                        const cW = W - pad.l - pad.r;
                        const cH = 140;
                        const max = Math.max(...data, ...data2);

                        const style = getComputedStyle(document.documentElement);
                        const textColor = style.getPropertyValue('--text-muted').trim();
                        const borderColor = style.getPropertyValue('--border').trim();

                        ctx.clearRect(0, 0, W, 180);

                        // grid
                        ctx.strokeStyle = borderColor;
                        ctx.lineWidth = 0.5;
                        for (let i = 0; i <= 4; i++) {
                            const y = pad.t + (cH / 4) * i;
                            ctx.beginPath();
                            ctx.moveTo(pad.l, y);
                            ctx.lineTo(pad.l + cW, y);
                            ctx.stroke();
                        }

                        // labels
                        ctx.fillStyle = textColor;
                        ctx.font = '11px DM Sans, sans-serif';
                        ctx.textAlign = 'center';

                        labels.forEach((l, i) => {
                            const x = pad.l + (cW / (data.length - 1)) * i;
                            ctx.fillText(l, x, pad.t + cH + 18);
                        });

                        const drawLine = (d, color, fill) => {
                            ctx.beginPath();

                            d.forEach((v, i) => {
                                const x = pad.l + (cW / (d.length - 1)) * i;
                                const y = pad.t + cH - (v / max) * cH;
                                i === 0 ? ctx.moveTo(x, y) : ctx.lineTo(x, y);
                            });

                            ctx.strokeStyle = color;
                            ctx.lineWidth = 2.5;
                            ctx.lineJoin = 'round';
                            ctx.stroke();

                            ctx.lineTo(pad.l + cW, pad.t + cH);
                            ctx.lineTo(pad.l, pad.t + cH);
                            ctx.closePath();

                            const gr = ctx.createLinearGradient(0, pad.t, 0, pad.t + cH);
                            gr.addColorStop(0, color.replace(')', ',0.2)').replace('rgb', 'rgba'));
                            gr.addColorStop(1, color.replace(')', ',0)').replace('rgb', 'rgba'));

                            ctx.fillStyle = fill;
                            ctx.fill();
                        };

                        drawLine(data, '#6366f1', 'rgba(99,102,241,0.1)');
                        drawLine(data2, '#22c55e', 'rgba(34,197,94,0.08)');
                    },

                    drawDonut(ctx) {
                        const size = 180;
                        ctx.canvas.width = size;
                        ctx.canvas.height = size;

                        const total = this.roleData.reduce((a, r) => a + r.count, 0);
                        let start = -Math.PI / 2;

                        this.roleData.forEach(role => {
                            const slice = (role.count / total) * 2 * Math.PI;

                            ctx.beginPath();
                            ctx.moveTo(90, 90);
                            ctx.arc(90, 90, 80, start, start + slice);
                            ctx.fillStyle = role.color;
                            ctx.fill();

                            start += slice;
                        });

                        // hole
                        ctx.beginPath();
                        ctx.arc(90, 90, 50, 0, 2 * Math.PI);

                        const style = getComputedStyle(document.documentElement);
                        ctx.fillStyle = style.getPropertyValue('--bg-surface').trim() || '#fff';
                        ctx.fill();

                        // text
                        ctx.fillStyle = style.getPropertyValue('--text-base').trim() || '#1e293b';
                        ctx.font = 'bold 22px DM Sans, sans-serif';
                        ctx.textAlign = 'center';
                        ctx.fillText(total, 90, 96);

                        ctx.font = '11px DM Sans, sans-serif';
                        ctx.fillStyle = style.getPropertyValue('--text-muted').trim() || '#64748b';
                        ctx.fillText('users', 90, 112);
                    }
                }
            });
        </script>
    @endPushOnce
</x-admin::layouts>
