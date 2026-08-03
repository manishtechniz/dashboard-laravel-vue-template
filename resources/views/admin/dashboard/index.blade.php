<x-admin::layouts>
    <v-dashboard
        analytics-src="{{ route('admin.dashboard.analytics') }}"
        bookings-src="{{ route('admin.dashboard') }}"></v-dashboard>

    @pushOnce('styles')
    <style>
        .dash-card {
            background: var(--bg-surface);
            border: 1px solid var(--border);
            box-shadow: var(--shadow);
            border-radius: var(--radius, 16px);
        }
        .dash-subtle-box {
            background: var(--bg-subtle);
            border: 1px solid var(--border);
        }
        .dash-border {
            border-color: var(--border) !important;
        }
        .dash-border-b {
            border-bottom: 1px solid var(--border) !important;
        }
        .dash-border-t {
            border-top: 1px solid var(--border) !important;
        }
        .dash-text-base {
            color: var(--text-base) !important;
        }
        .dash-text-muted {
            color: var(--text-muted) !important;
        }
        .dash-accent-text {
            color: var(--accent) !important;
        }
        .dash-accent-bg {
            background: var(--accent-light) !important;
            color: var(--accent) !important;
        }
        .dash-success-bg {
            background: color-mix(in srgb, var(--success) 12%, transparent) !important;
            color: var(--success) !important;
        }
        .dash-warning-bg {
            background: color-mix(in srgb, var(--warning) 12%, transparent) !important;
            color: var(--warning) !important;
        }
        .dash-info-bg {
            background: color-mix(in srgb, var(--info) 12%, transparent) !important;
            color: var(--info) !important;
        }
        .dash-danger-bg {
            background: color-mix(in srgb, var(--danger) 12%, transparent) !important;
            color: var(--danger) !important;
        }
        .dash-row-hover:hover {
            background: var(--bg-subtle) !important;
        }
        .dash-progress-track {
            background: var(--bg-subtle) !important;
        }
    </style>
    @endPushOnce

    @pushOnce('scripts')
    <script type="text/x-template" id="v-dashboard-template">
        <div class="space-y-6 pb-12">
            {{-- Top Header Action Bar --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold dash-text-base tracking-tight flex items-center gap-3">
                        <span>Dashboard</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold dash-accent-bg dash-border border">
                            Live Operations
                        </span>
                    </h1>
                    <div class="text-sm dash-text-muted mt-1">Overview of venues, reservations, guests, user roles, and revenue analytics.</div>
                </div>
                <div class="flex items-center gap-3">
                    <Button
                        label="Refresh"
                        icon="pi pi-refresh"
                        :loading="isLoading"
                        class="p-button-outlined"
                        size="small"
                        @click="refreshDashboard"
                    />

                    @if (hasPermission('admin.bookings.index'))
                        <a href="{{ route('admin.bookings.index') }}">
                            <Button
                                label="Manage Bookings"
                                icon="pi pi-calendar"
                                size="small"
                            />
                        </a>
                    @endif
                </div>
            </div>

            {{-- SKELETON / SHIMMER LOADING STATE --}}
            <template v-if="isLoading">
                {{-- 1. Operational Quick Status Bar Shimmer --}}
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-3.5">
                    <div v-for="n in 4" :key="'op-shimmer-' + n" class="flex items-center gap-3 p-3.5 rounded-xl dash-card">
                        <div class="w-9 h-9 rounded-lg shimmer flex-shrink-0"></div>
                        <div class="flex-1 space-y-1.5 min-w-0">
                            <div class="h-3 w-20 rounded shimmer"></div>
                            <div class="h-4 w-12 rounded shimmer"></div>
                        </div>
                    </div>
                </div>

                {{-- 2. Core KPI Stat Cards Shimmer --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 xl:grid-cols-4 gap-6">
                    <div v-for="n in 4" :key="'kpi-shimmer-' + n" class="dash-card p-6 space-y-4">
                        <div class="flex justify-between items-start">
                            <div class="space-y-2 flex-1">
                                <div class="h-3 w-24 rounded shimmer"></div>
                                <div class="h-8 w-32 rounded shimmer"></div>
                            </div>
                            <div class="w-12 h-12 rounded-xl shimmer flex-shrink-0"></div>
                        </div>
                        <div class="pt-3 dash-border-t flex justify-between items-center">
                            <div class="h-4 w-16 rounded shimmer"></div>
                            <div class="h-3 w-20 rounded shimmer"></div>
                        </div>
                    </div>
                </div>

                {{-- 3. Analytics Charts Grid Shimmer --}}
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-2 dash-card p-6 space-y-4">
                        <div class="flex justify-between items-center pb-4 dash-border-b">
                            <div class="space-y-1.5">
                                <div class="h-5 w-48 rounded shimmer"></div>
                                <div class="h-3 w-32 rounded shimmer"></div>
                            </div>
                            <div class="flex gap-2">
                                <div class="h-8 w-24 rounded-lg shimmer"></div>
                                <div class="h-8 w-32 rounded-lg shimmer"></div>
                            </div>
                        </div>
                        <div class="h-[280px] w-full rounded-xl shimmer"></div>
                        <div class="grid grid-cols-3 gap-4 pt-4 dash-border-t">
                            <div v-for="n in 3" :key="'chart-foot-shimmer-' + n" class="space-y-1">
                                <div class="h-3 w-16 rounded shimmer"></div>
                                <div class="h-5 w-24 rounded shimmer"></div>
                            </div>
                        </div>
                    </div>

                    <div class="dash-card p-6 space-y-4">
                        <div class="space-y-1.5 pb-3 dash-border-b">
                            <div class="h-5 w-36 rounded shimmer"></div>
                            <div class="h-3 w-28 rounded shimmer"></div>
                        </div>
                        <div class="flex justify-center py-4">
                            <div class="w-44 h-44 rounded-full shimmer"></div>
                        </div>
                        <div class="space-y-2.5 pt-2 dash-border-t">
                            <div v-for="n in 4" :key="'status-shimmer-' + n" class="flex justify-between items-center">
                                <div class="h-3 w-20 rounded shimmer"></div>
                                <div class="h-3 w-12 rounded shimmer"></div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 4. Top Performing Venues, Payment Methods & Roles Shimmer --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div v-for="col in 3" :key="'col-shimmer-' + col" class="dash-card p-6 space-y-4">
                        <div class="flex justify-between items-center pb-3 dash-border-b">
                            <div class="space-y-1.5">
                                <div class="h-5 w-36 rounded shimmer"></div>
                                <div class="h-3 w-24 rounded shimmer"></div>
                            </div>
                            <div class="h-4 w-12 rounded shimmer"></div>
                        </div>
                        <div class="space-y-3">
                            <div v-for="n in 3" :key="'row-shimmer-' + col + '-' + n" class="p-3 rounded-xl dash-border border flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl shimmer flex-shrink-0"></div>
                                    <div class="space-y-1.5">
                                        <div class="h-4 w-28 rounded shimmer"></div>
                                        <div class="h-3 w-16 rounded shimmer"></div>
                                    </div>
                                </div>
                                <div class="space-y-1.5 text-right">
                                    <div class="h-4 w-16 rounded shimmer"></div>
                                    <div class="h-3 w-12 rounded shimmer"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>

            {{-- ACTUAL LOADED DASHBOARD VIEW --}}
            <template v-else>
                {{-- 1. Operational Quick Status Bar --}}
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-3.5">
                    <div class="flex items-center gap-3 p-3.5 rounded-xl dash-card">
                        <div class="w-9 h-9 rounded-lg dash-success-bg flex items-center justify-center flex-shrink-0">
                            <i class="pi pi-calendar-plus text-base" style="color: var(--success)"></i>
                        </div>
                        <div class="min-w-0">
                            <div class="text-xs font-medium dash-text-muted truncate">Today's Bookings</div>
                            <div class="text-base font-bold dash-text-base">@{{ stats.today_bookings || 0 }}</div>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 p-3.5 rounded-xl dash-card">
                        <div class="w-9 h-9 rounded-lg dash-warning-bg flex items-center justify-center flex-shrink-0">
                            <i class="pi pi-clock text-base" style="color: var(--warning)"></i>
                        </div>
                        <div class="min-w-0">
                            <div class="text-xs font-medium dash-text-muted truncate">Pending Confirmation</div>
                            <div class="text-base font-bold dash-text-base flex items-center gap-1.5">
                                @{{ stats.pending_bookings || 0 }}
                                <span v-if="stats.pending_bookings > 0" class="w-2 h-2 rounded-full animate-pulse" style="background-color: var(--warning)"></span>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 p-3.5 rounded-xl dash-card">
                        <div class="w-9 h-9 rounded-lg dash-info-bg flex items-center justify-center flex-shrink-0">
                            <i class="pi pi-check-circle text-base" style="color: var(--info)"></i>
                        </div>
                        <div class="min-w-0">
                            <div class="text-xs font-medium dash-text-muted truncate">Checked In</div>
                            <div class="text-base font-bold dash-text-base">@{{ stats.checked_in_bookings || 0 }}</div>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 p-3.5 rounded-xl dash-card">
                        <div class="w-9 h-9 rounded-lg dash-accent-bg flex items-center justify-center flex-shrink-0">
                            <i class="pi pi-user text-base" style="color: var(--accent)"></i>
                        </div>
                        <div class="min-w-0">
                            <div class="text-xs font-medium dash-text-muted truncate">Active Clients</div>
                            <div class="text-base font-bold dash-text-base">@{{ stats.active_clients || 0 }}</div>
                        </div>
                    </div>
                </div>

                {{-- 2. Core KPI Stat Cards --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 xl:grid-cols-4 gap-6">
                    {{-- Card 1: Total Revenue (in Rupees with K, M, B formatting) --}}
                    <div class="dash-card p-6 flex flex-col hover:shadow-lg transition-all duration-300 relative overflow-hidden group">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <p class="text-xs font-bold uppercase tracking-wider dash-text-muted mb-1">Total Revenue</p>
                                <h3 class="text-3xl font-extrabold dash-text-base tracking-tight">@{{ formatRupee(stats.total_revenue) }}</h3>
                            </div>
                            <div class="w-12 h-12 rounded-xl flex items-center justify-center shadow-sm dash-success-bg">
                                <span class="text-xl font-extrabold">₹</span>
                            </div>
                        </div>
                        <div class="flex items-center justify-between text-xs mt-auto pt-3 dash-border-t">
                            <span :class="stats.revenue_trend === 'up' ? 'dash-success-bg' : 'dash-danger-bg'" class="px-2 py-0.5 rounded-md font-semibold flex items-center gap-1">
                                <i :class="stats.revenue_trend === 'up' ? 'pi pi-arrow-up' : 'pi pi-arrow-down'" style="font-size: 0.65rem"></i>
                                @{{ stats.revenue_growth }}% vs last mo.
                            </span>
                            <span class="dash-text-muted font-medium">This Mo: @{{ formatRupee(stats.this_month_revenue) }}</span>
                        </div>
                    </div>

                    {{-- Card 2: Total Bookings --}}
                    <div class="dash-card p-6 flex flex-col hover:shadow-lg transition-all duration-300 relative overflow-hidden group">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <p class="text-xs font-bold uppercase tracking-wider dash-text-muted mb-1">Total Bookings</p>
                                <h3 class="text-3xl font-extrabold dash-text-base tracking-tight">@{{ formatCompact(stats.total_bookings) }}</h3>
                            </div>
                            <div class="w-12 h-12 rounded-xl flex items-center justify-center shadow-sm dash-accent-bg">
                                <i class="pi pi-bookmark text-xl" style="color: var(--accent)"></i>
                            </div>
                        </div>
                        <div class="flex items-center justify-between text-xs mt-auto pt-3 dash-border-t">
                            <span :class="stats.bookings_trend === 'up' ? 'dash-success-bg' : 'dash-danger-bg'" class="px-2 py-0.5 rounded-md font-semibold flex items-center gap-1">
                                <i :class="stats.bookings_trend === 'up' ? 'pi pi-arrow-up' : 'pi pi-arrow-down'" style="font-size: 0.65rem"></i>
                                @{{ stats.bookings_growth }}% vs last mo.
                            </span>
                            <span class="dash-text-muted font-medium">This Mo: @{{ formatCompact(stats.this_month_bookings) }}</span>
                        </div>
                    </div>

                    {{-- Card 3: Guests Footfall & ABV --}}
                    <div class="dash-card p-6 flex flex-col hover:shadow-lg transition-all duration-300 relative overflow-hidden group">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <p class="text-xs font-bold uppercase tracking-wider dash-text-muted mb-1">Guests Hosted</p>
                                <h3 class="text-3xl font-extrabold dash-text-base tracking-tight">@{{ formatCompact(stats.total_guests) }}</h3>
                            </div>
                            <div class="w-12 h-12 rounded-xl flex items-center justify-center shadow-sm dash-warning-bg">
                                <i class="pi pi-users text-xl" style="color: var(--warning)"></i>
                            </div>
                        </div>
                        <div class="flex items-center justify-between text-xs mt-auto pt-3 dash-border-t">
                            <span class="dash-accent-bg px-2 py-0.5 rounded-md font-semibold">
                                ABV: @{{ formatRupee(stats.avg_booking_value) }}
                            </span>
                            <span class="dash-text-muted font-medium">Clients: @{{ formatCompact(stats.total_clients) }}</span>
                        </div>
                    </div>

                    {{-- Card 4: Clubs & Capacity --}}
                    <div class="dash-card p-6 flex flex-col hover:shadow-lg transition-all duration-300 relative overflow-hidden group">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <p class="text-xs font-bold uppercase tracking-wider dash-text-muted mb-1">Clubs</p>
                                <h3 class="text-3xl font-extrabold dash-text-base tracking-tight">@{{ formatInt(stats.active_clubs) }} <span class="text-base font-normal dash-text-muted">/ @{{ formatInt(stats.total_clubs) }}</span></h3>
                            </div>
                            <div class="w-12 h-12 rounded-xl flex items-center justify-center shadow-sm dash-info-bg">
                                <i class="pi pi-building text-xl" style="color: var(--info)"></i>
                            </div>
                        </div>
                        <div class="flex items-center justify-between text-xs mt-auto pt-3 dash-border-t">
                            <span class="dash-warning-bg px-2 py-0.5 rounded-md font-semibold flex items-center gap-1">
                                <i class="pi pi-star-fill text-[10px]" style="color: var(--warning)"></i>
                                @{{ stats.avg_rating }} Rating
                            </span>
                            <span class="dash-text-muted font-medium">@{{ formatCompact(stats.total_capacity) }} Seats</span>
                        </div>
                    </div>
                </div>

                {{-- 3. Analytics Charts Grid --}}
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    {{-- Left Chart (2 Columns): Activity Trends --}}
                    <div class="lg:col-span-2 dash-card p-6 flex flex-col justify-between">
                        <div>
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 dash-border-b gap-4">
                                <div>
                                    <h3 class="text-lg font-bold dash-text-base flex items-center gap-2">
                                        <span>Operational Performance</span>
                                        <Tag :value="activeMetric === 'revenue' ? 'Revenue Analytics' : 'Booking Volume'" :severity="activeMetric === 'revenue' ? 'success' : 'info'" rounded />
                                    </h3>
                                    <p class="text-xs dash-text-muted mt-0.5">Timeline trends of revenue flow and client reservations</p>
                                </div>
                                <div class="flex flex-wrap items-center gap-2">
                                    {{-- Metric Toggle --}}
                                    <div class="inline-flex rounded-lg p-1 dash-subtle-box text-xs">
                                        <button
                                            type="button"
                                            @click="activeMetric = 'revenue'"
                                            :style="activeMetric === 'revenue' ? 'background: var(--bg-surface); color: var(--success); box-shadow: var(--shadow);' : 'color: var(--text-muted);'"
                                            class="px-2.5 py-1 rounded-md transition-all flex items-center gap-1.5 font-bold"
                                        >
                                            <span>₹</span>
                                            Revenue
                                        </button>
                                        <button
                                            type="button"
                                            @click="activeMetric = 'bookings'"
                                            :style="activeMetric === 'bookings' ? 'background: var(--bg-surface); color: var(--accent); box-shadow: var(--shadow);' : 'color: var(--text-muted);'"
                                            class="px-2.5 py-1 rounded-md transition-all flex items-center gap-1.5 font-bold"
                                        >
                                            <i class="pi pi-chart-line text-[11px]"></i>
                                            Bookings
                                        </button>
                                    </div>

                                    {{-- Time Range Tabs --}}
                                    <div class="inline-flex rounded-lg p-1 dash-subtle-box text-xs">
                                        <button
                                            v-for="range in ['7d', '30d', '12m']"
                                            :key="range"
                                            type="button"
                                            @click="switchRange(range)"
                                            :style="activeRange === range ? 'background: var(--bg-surface); color: var(--text-base); box-shadow: var(--shadow);' : 'color: var(--text-muted);'"
                                            class="px-2.5 py-1 rounded-md transition-all uppercase font-bold"
                                        >
                                            @{{ range }}
                                        </button>
                                    </div>
                                </div>
                            </div>

                            {{-- Canvas Interactive Chart --}}
                            <div class="relative w-full h-[280px] mt-4">
                                <canvas ref="activityChart" class="w-full h-full"></canvas>
                            </div>
                        </div>

                        {{-- Period Summary Footer --}}
                        <div class="grid grid-cols-3 gap-4 pt-4 mt-2 dash-border-t text-center">
                            <div class="p-2 rounded-xl dash-subtle-box">
                                <span class="text-[11px] dash-text-muted">Period Total</span>
                                <div class="text-sm font-bold dash-text-base mt-0.5">
                                    @{{ activeMetric === 'revenue' ? formatRupee(periodTotal) : formatCompact(periodTotal) }}
                                </div>
                            </div>
                            <div class="p-2 rounded-xl dash-subtle-box">
                                <span class="text-[11px] dash-text-muted">Period Peak</span>
                                <div class="text-sm font-bold mt-0.5" style="color: var(--success)">
                                    @{{ activeMetric === 'revenue' ? formatRupee(periodPeak) : formatCompact(periodPeak) }}
                                </div>
                            </div>
                            <div class="p-2 rounded-xl dash-subtle-box">
                                <span class="text-[11px] dash-text-muted">Daily Average</span>
                                <div class="text-sm font-bold mt-0.5" style="color: var(--accent)">
                                    @{{ activeMetric === 'revenue' ? formatRupee(periodAverage) : formatNumber(periodAverage, 1) }}
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Right Chart (1 Column): Status Distribution Donut --}}
                    <div class="dash-card p-6 flex flex-col justify-between">
                        <div>
                            <div class="pb-3 dash-border-b">
                                <h3 class="text-lg font-bold dash-text-base">Booking Status Breakdown</h3>
                                <p class="text-xs dash-text-muted mt-0.5">Distribution of all reservation statuses</p>
                            </div>

                            <div class="relative flex items-center justify-center my-4">
                                <canvas ref="statusChart" class="w-44 h-44"></canvas>
                            </div>
                        </div>

                        {{-- Status Legend List --}}
                        <div class="space-y-2.5 pt-3 dash-border-t">
                            <div
                                v-for="item in statusBreakdown"
                                :key="item.label"
                                class="flex items-center justify-between text-xs"
                            >
                                <div class="flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-full" :style="{ backgroundColor: item.color }"></span>
                                    <span class="font-medium dash-text-base">@{{ item.label }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="font-bold dash-text-base">@{{ formatInt(item.count) }}</span>
                                    <span class="dash-text-muted w-9 text-right font-mono">(@{{ calculatePercent(item.count, stats.total_bookings) }}%)</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 4. Top Performing Venues, Payment Gateways & Role Distribution (3 Columns) --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    {{-- 1. Top Venues Leaderboard --}}
                    <div class="dash-card p-6 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between pb-3 dash-border-b mb-4">
                                <div>
                                    <h3 class="text-lg font-bold dash-text-base">Top Venues</h3>
                                    <p class="text-xs dash-text-muted mt-0.5">Ranked by revenue generation</p>
                                </div>
                                <a href="{{ route('admin.clubs.index') }}" class="text-xs font-semibold dash-accent-text flex items-center gap-1">
                                    View All <i class="pi pi-arrow-right text-[10px]" style="color: var(--accent)"></i>
                                </a>
                            </div>

                            <div class="space-y-3">
                                <div
                                    v-for="(club, idx) in topClubs"
                                    :key="club.id"
                                    class="flex items-center justify-between p-3 rounded-xl dash-border border dash-row-hover transition-colors"
                                >
                                    <div class="flex items-center gap-3 min-w-0">
                                        <span class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0"
                                            :class="idx === 0 ? 'dash-warning-bg' : (idx === 1 ? 'dash-subtle-box dash-text-base' : 'dash-accent-bg')">
                                            @{{ idx + 1 }}
                                        </span>
                                        <div class="min-w-0">
                                            <div class="font-bold text-sm dash-text-base truncate">@{{ club.name }}</div>
                                            <div class="text-xs dash-text-muted flex items-center gap-1.5 mt-0.5">
                                                <span class="truncate">@{{ club.city || 'Location' }}</span>
                                                <span>•</span>
                                                <span class="flex items-center gap-0.5" style="color: var(--warning)"><i class="pi pi-star-fill text-[10px]" style="color: var(--warning)"></i> @{{ club.average_rating || '5.0' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-right flex-shrink-0 pl-2">
                                        <div class="font-extrabold text-sm" style="color: var(--success)">@{{ formatRupee(club.total_revenue) }}</div>
                                        <div class="text-xs dash-text-muted mt-0.5">@{{ formatCompact(club.total_bookings) }} Bookings</div>
                                    </div>
                                </div>

                                <div v-if="topClubs.length === 0" class="text-center py-6 text-xs dash-text-muted">
                                    No venue performance data recorded yet.
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 2. Payment Gateways Split --}}
                    <div class="dash-card p-6 flex flex-col justify-between">
                        <div>
                            <div class="pb-3 dash-border-b mb-4">
                                <h3 class="text-lg font-bold dash-text-base">Payment Methods</h3>
                                <p class="text-xs dash-text-muted mt-0.5">Volume by payment gateways</p>
                            </div>

                            <div class="space-y-4">
                                <div
                                    v-for="pm in paymentMethods"
                                    :key="pm.method"
                                    class="space-y-1.5"
                                >
                                    <div class="flex items-center justify-between text-xs">
                                        <span class="font-semibold dash-text-base capitalize">@{{ pm.method }}</span>
                                        <span class="font-bold dash-text-base">@{{ formatRupee(pm.total_amount) }}</span>
                                    </div>
                                    <div class="w-full dash-progress-track rounded-full h-2 overflow-hidden">
                                        <div
                                            class="h-2 rounded-full transition-all duration-500"
                                            :style="{ width: calculatePercent(pm.total_amount, stats.total_revenue) + '%', backgroundColor: 'var(--accent)' }"
                                        ></div>
                                    </div>
                                    <div class="flex justify-between text-[11px] dash-text-muted">
                                        <span>@{{ formatCompact(pm.count) }} txns</span>
                                        <span>@{{ calculatePercent(pm.total_amount, stats.total_revenue) }}%</span>
                                    </div>
                                </div>

                                <div v-if="paymentMethods.length === 0" class="text-center py-6 text-xs dash-text-muted">
                                    No payment transaction data recorded yet.
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 3. Roles & User Adoption Distribution --}}
                    <div class="dash-card p-6 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between pb-3 dash-border-b mb-4">
                                <div>
                                    <h3 class="text-lg font-bold dash-text-base">Roles & Users</h3>
                                    <p class="text-xs dash-text-muted mt-0.5">Active users by role percentage</p>
                                </div>
                                <a href="{{ route('admin.roles.index') }}" class="text-xs font-semibold dash-accent-text flex items-center gap-1">
                                    Roles <i class="pi pi-arrow-right text-[10px]" style="color: var(--accent)"></i>
                                </a>
                            </div>

                            <div class="space-y-4">
                                <div
                                    v-for="role in rolesBreakdown"
                                    :key="role.id"
                                    class="space-y-1.5"
                                >
                                    <div class="flex items-center justify-between text-xs">
                                        <div class="flex items-center gap-2 min-w-0">
                                            <span class="w-2.5 h-2.5 rounded-full flex-shrink-0" :style="{ backgroundColor: role.color }"></span>
                                            <span class="font-semibold dash-text-base truncate">@{{ role.name }}</span>
                                            <span class="px-1.5 py-0.5 rounded text-[10px] uppercase font-bold tracking-wider flex-shrink-0"
                                                :class="role.type === 'system' ? 'dash-accent-bg' : 'dash-subtle-box dash-text-muted'">
                                                @{{ role.type }}
                                            </span>
                                        </div>
                                        <div class="flex items-center gap-1.5 flex-shrink-0 pl-2">
                                            <span class="font-medium dash-text-muted">@{{ role.count }} @{{ role.count === 1 ? 'user' : 'users' }}</span>
                                            <span class="font-bold px-1.5 py-0.5 rounded text-[11px]" :style="{ color: role.color, backgroundColor: role.color + '18' }">
                                                @{{ role.percentage }}%
                                            </span>
                                        </div>
                                    </div>
                                    <div class="w-full dash-progress-track rounded-full h-2 overflow-hidden">
                                        <div
                                            class="h-2 rounded-full transition-all duration-500"
                                            :style="{ width: (role.percentage || 0) + '%', backgroundColor: role.color }"
                                        ></div>
                                    </div>
                                </div>

                                <div v-if="rolesBreakdown.length === 0" class="text-center py-6 text-xs dash-text-muted">
                                    No role allocation data recorded.
                                </div>
                            </div>
                        </div>

                        <div class="pt-3 mt-4 dash-border-t flex items-center justify-between text-xs dash-text-muted">
                            <span>Total System Users</span>
                            <a href="{{ route('admin.users.index') }}" class="font-bold dash-text-base hover:opacity-80 transition-opacity">
                                @{{ totalUsers }} Users
                            </a>
                        </div>
                    </div>
                </div>
            </template>

            {{-- 5. Live DataGrid Section: Recent Bookings --}}
            <div class="dash-card">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 dash-border-b gap-3 pt-3 px-6 -mb-4">
                    <div>
                        <h3 class="text-lg font-bold dash-text-base">Recent Bookings & Reservations</h3>
                        <p class="text-xs dash-text-muted mt-0.5">Real-time datagrid stream powered by server queries</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <Button
                            label="Refresh"
                            icon="pi pi-sync"
                            text
                            size="small"
                            @click="refreshDataGrid"
                        />
                        @if (hasPermission('admin.bookings.index'))
                            <a href="{{ route('admin.bookings.index') }}">
                                <Button
                                    label="View All"
                                    icon="pi pi-arrow-up-right"
                                    iconPos="right"
                                    size="small"
                                    severity="secondary"
                                />
                            </a>
                        @endif
                    </div>
                </div>

                <div>
                    <x-admin::datagrid
                        :is-multi-row="false"
                        :isToolbar=false
                        ref="dashboardGrid"
                        src="{{ route('admin.dashboard') }}"
                    />
                </div>
            </div>

            <Toast />
        </div>
    </script>

    <script type="module">
        adminVueApp.component('v-dashboard', {
            template: '#v-dashboard-template',

            props: {
                analyticsSrc: {
                    type: String,
                    default: '{{ route("admin.dashboard.analytics") }}'
                },
                bookingsSrc: {
                    type: String,
                    default: '{{ route("admin.dashboard") }}'
                }
            },

            provide() {
                return {
                    customActions: {}
                };
            },

            data() {
                return {
                    isLoading: true,
                    stats: {},
                    charts: {},
                    statusBreakdown: [],
                    topClubs: [],
                    paymentMethods: [],
                    rolesBreakdown: [],
                    totalUsers: 0,

                    activeRange: '30d',
                    activeMetric: 'revenue', // 'revenue' or 'bookings'
                    activityChart: null,
                    statusChart: null,
                    selectedBooking: {},
                    themeObserver: null
                };
            },

            computed: {
                currentChartData() {
                    const rangeData = this.charts[this.activeRange] || {
                        labels: [],
                        revenue: [],
                        bookings: []
                    };
                    return {
                        labels: rangeData.labels || [],
                        values: this.activeMetric === 'revenue' ? (rangeData.revenue || []) : (rangeData.bookings || [])
                    };
                },

                periodTotal() {
                    const vals = this.currentChartData.values;
                    return vals.reduce((a, b) => a + Number(b || 0), 0);
                },

                periodPeak() {
                    const vals = this.currentChartData.values;
                    return vals.length ? Math.max(...vals) : 0;
                },

                periodAverage() {
                    const vals = this.currentChartData.values;
                    return vals.length ? (this.periodTotal / vals.length) : 0;
                }
            },

            watch: {
                activeMetric() {
                    this.renderActivityChart();
                }
            },

            mounted() {
                this.fetchAnalytics();
                window.addEventListener('resize', this.initCharts);

                // Observe html class changes (e.g. switching between light, dark, ocean, rose)
                this.themeObserver = new MutationObserver(() => {
                    this.initCharts();
                });
                this.themeObserver.observe(document.documentElement, {
                    attributes: true,
                    attributeFilter: ['class', 'style', 'data-theme']
                });
            },

            unmounted() {
                window.removeEventListener('resize', this.initCharts);
                if (this.themeObserver) {
                    this.themeObserver.disconnect();
                }
            },

            methods: {
                getThemeVars() {
                    const cs = getComputedStyle(document.documentElement);
                    return {
                        bgBase: cs.getPropertyValue('--bg-base').trim() || '#f4f6fb',
                        bgSurface: cs.getPropertyValue('--bg-surface').trim() || '#ffffff',
                        bgSubtle: cs.getPropertyValue('--bg-subtle').trim() || '#eef1f8',
                        border: cs.getPropertyValue('--border').trim() || '#e2e8f0',
                        textBase: cs.getPropertyValue('--text-base').trim() || '#1e293b',
                        textMuted: cs.getPropertyValue('--text-muted').trim() || '#64748b',
                        accent: cs.getPropertyValue('--accent').trim() || '#6366f1',
                        accentLight: cs.getPropertyValue('--accent-light').trim() || '#eef2ff',
                        success: cs.getPropertyValue('--success').trim() || '#22c55e',
                        warning: cs.getPropertyValue('--warning').trim() || '#f59e0b',
                        danger: cs.getPropertyValue('--danger').trim() || '#ef4444',
                        info: cs.getPropertyValue('--info').trim() || '#3b82f6',
                        isDark: document.documentElement.classList.contains('dark') || document.documentElement.classList.contains('ocean')
                    };
                },

                fetchAnalytics() {
                    this.isLoading = true;
                    const url = this.analyticsSrc;

                    const request = this.$axios ?
                        this.$axios.get(url) :
                        fetch(url, {
                            credentials: 'same-origin',
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        }).then(r => r.json());

                    Promise.resolve(request)
                        .then(response => {
                            const res = response?.data || response || {};
                            if (res.success || res.stats) {
                                this.stats = res.stats || {};
                                this.charts = res.charts || {};
                                this.statusBreakdown = res.statusBreakdown || [];
                                this.topClubs = res.topClubs || [];
                                this.paymentMethods = res.paymentMethods || [];
                                this.rolesBreakdown = res.rolesBreakdown || [];
                                this.totalUsers = res.totalUsers || 0;
                            }
                        })
                        .catch(err => {
                            console.error('Failed to load dashboard analytics:', err);
                        })
                        .finally(() => {
                            this.isLoading = false;
                            this.$nextTick(() => {
                                this.initCharts();
                            });
                        });
                },

                formatRupee(val, decimals = 1) {
                    const num = Math.abs(Number(val)) || 0;
                    const sign = Number(val) < 0 ? '-' : '';
                    if (num >= 1000000000) {
                        const b = (num / 1000000000).toFixed(decimals);
                        return `${sign}₹${b.endsWith('.0') ? b.slice(0, -2) : b}B`;
                    }
                    if (num >= 1000000) {
                        const m = (num / 1000000).toFixed(decimals);
                        return `${sign}₹${m.endsWith('.0') ? m.slice(0, -2) : m}M`;
                    }
                    if (num >= 1000) {
                        const k = (num / 1000).toFixed(decimals);
                        return `${sign}₹${k.endsWith('.0') ? k.slice(0, -2) : k}K`;
                    }
                    return `${sign}₹${num.toLocaleString('en-IN')}`;
                },

                formatCompact(val) {
                    const num = Math.abs(Number(val)) || 0;
                    const sign = Number(val) < 0 ? '-' : '';
                    if (num >= 1000000000) return `${sign}${(num / 1000000000).toFixed(1).replace(/\.0$/, '')}B`;
                    if (num >= 1000000) return `${sign}${(num / 1000000).toFixed(1).replace(/\.0$/, '')}M`;
                    if (num >= 1000) return `${sign}${(num / 1000).toFixed(1).replace(/\.0$/, '')}K`;
                    return `${sign}${num.toLocaleString('en-IN')}`;
                },

                formatNumber(val, decimals = 2) {
                    const num = Number(val) || 0;
                    return num.toLocaleString('en-IN', {
                        minimumFractionDigits: decimals,
                        maximumFractionDigits: decimals
                    });
                },

                formatInt(val) {
                    const num = Number(val) || 0;
                    return num.toLocaleString('en-IN');
                },

                calculatePercent(val, total) {
                    const n = Number(val) || 0;
                    const t = Number(total) || 0;
                    if (t <= 0) return '0.0';
                    return ((n / t) * 100).toFixed(1);
                },

                getSeverity(status) {
                    switch (String(status).toLowerCase()) {
                        case 'confirmed':
                            return 'success';
                        case 'checked_in':
                            return 'info';
                        case 'pending':
                            return 'warn';
                        case 'cancelled':
                            return 'danger';
                        default:
                            return 'secondary';
                    }
                },

                switchRange(rangeKey) {
                    this.activeRange = rangeKey;
                    this.renderActivityChart();
                },

                refreshDashboard() {
                    this.fetchAnalytics();
                },

                refreshDataGrid() {
                    if (this.$refs.dashboardGrid && typeof this.$refs.dashboardGrid.get === 'function') {
                        this.$refs.dashboardGrid.get();
                    }
                },

                openBookingDetails(record) {
                    this.selectedBooking = record || {};
                },

                initCharts() {
                    setTimeout(() => {
                        this.renderActivityChart();
                        this.renderStatusChart();
                    }, 50);
                },

                renderActivityChart() {
                    const canvas = this.$refs.activityChart;
                    if (!canvas) return;
                    const ctx = canvas.getContext('2d');
                    if (!ctx) return;

                    const tv = this.getThemeVars();
                    const container = canvas.parentElement;
                    const W = container.clientWidth || 600;
                    const H = container.clientHeight || 280;

                    const dpr = window.devicePixelRatio || 1;
                    canvas.width = W * dpr;
                    canvas.height = H * dpr;
                    canvas.style.width = `${W}px`;
                    canvas.style.height = `${H}px`;
                    ctx.resetTransform?.() || ctx.setTransform(1, 0, 0, 1, 0, 0);
                    ctx.scale(dpr, dpr);

                    const {
                        labels,
                        values
                    } = this.currentChartData;
                    const data = values.length > 0 ? values : [0];
                    const xLabels = labels.length > 0 ? labels : ['No Data'];

                    const pad = {
                        t: 20,
                        r: 25,
                        b: 35,
                        l: 60
                    };
                    const cW = Math.max(10, W - pad.l - pad.r);
                    const cH = Math.max(10, H - pad.t - pad.b);
                    const maxVal = Math.max(...data, 10);
                    const max = Math.ceil(maxVal * 1.15);

                    ctx.clearRect(0, 0, W, H);

                    // Draw Grid & Y-Axis using theme border and text colors
                    ctx.strokeStyle = tv.border;
                    ctx.lineWidth = 1;
                    ctx.setLineDash([4, 4]);

                    for (let i = 0; i <= 4; i++) {
                        const y = pad.t + (cH / 4) * i;
                        ctx.beginPath();
                        ctx.moveTo(pad.l, y);
                        ctx.lineTo(pad.l + cW, y);
                        ctx.stroke();

                        const val = Math.round(max - (max / 4) * i);
                        ctx.fillStyle = tv.textMuted;
                        ctx.font = '11px Inter, sans-serif';
                        ctx.textAlign = 'right';
                        ctx.textBaseline = 'middle';
                        ctx.fillText(this.activeMetric === 'revenue' ? this.formatRupee(val) : this.formatCompact(val), pad.l - 10, y);
                    }

                    ctx.setLineDash([]); // Reset line dash

                    // Draw X-Axis Labels
                    ctx.fillStyle = tv.textMuted;
                    ctx.font = '11px Inter, sans-serif';
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'top';

                    const step = data.length > 15 ? Math.ceil(data.length / 8) : (data.length > 8 ? 2 : 1);

                    xLabels.forEach((lbl, i) => {
                        if (i % step === 0 || i === data.length - 1) {
                            const x = pad.l + (cW / Math.max(1, data.length - 1)) * i;
                            ctx.fillText(lbl, x, pad.t + cH + 12);
                        }
                    });

                    // Curve & Gradient based on Active Theme Color
                    const themeColor = this.activeMetric === 'revenue' ? tv.success : tv.accent;

                    const points = data.map((v, i) => {
                        const x = pad.l + (cW / Math.max(1, data.length - 1)) * i;
                        const y = pad.t + cH - (v / max) * cH;
                        return {
                            x,
                            y
                        };
                    });

                    // 1. Fill Area
                    const fillPath = new Path2D();
                    fillPath.moveTo(points[0].x, points[0].y);

                    for (let i = 1; i < points.length; i++) {
                        const prev = points[i - 1];
                        const curr = points[i];
                        const cp1x = prev.x + (curr.x - prev.x) / 2;
                        const cp1y = prev.y;
                        const cp2x = prev.x + (curr.x - prev.x) / 2;
                        const cp2y = curr.y;
                        fillPath.bezierCurveTo(cp1x, cp1y, cp2x, cp2y, curr.x, curr.y);
                    }

                    fillPath.lineTo(points[points.length - 1].x, pad.t + cH);
                    fillPath.lineTo(points[0].x, pad.t + cH);
                    fillPath.closePath();

                    const grad = ctx.createLinearGradient(0, pad.t, 0, pad.t + cH);
                    grad.addColorStop(0, `color-mix(in srgb, ${themeColor} 30%, transparent)`);
                    grad.addColorStop(1, `color-mix(in srgb, ${themeColor} 0%, transparent)`);
                    ctx.fillStyle = grad;
                    ctx.fill(fillPath);

                    // 2. Stroke Line
                    ctx.beginPath();
                    ctx.moveTo(points[0].x, points[0].y);

                    for (let i = 1; i < points.length; i++) {
                        const prev = points[i - 1];
                        const curr = points[i];
                        const cp1x = prev.x + (curr.x - prev.x) / 2;
                        const cp1y = prev.y;
                        const cp2x = prev.x + (curr.x - prev.x) / 2;
                        const cp2y = curr.y;
                        ctx.bezierCurveTo(cp1x, cp1y, cp2x, cp2y, curr.x, curr.y);
                    }

                    ctx.strokeStyle = themeColor;
                    ctx.lineWidth = 3;
                    ctx.lineJoin = 'round';
                    ctx.lineCap = 'round';
                    ctx.stroke();

                    // 3. Draw Points
                    points.forEach((pt, i) => {
                        if (data.length <= 15 || i % step === 0 || i === data.length - 1) {
                            ctx.beginPath();
                            ctx.arc(pt.x, pt.y, 4, 0, Math.PI * 2);
                            ctx.fillStyle = tv.bgSurface;
                            ctx.fill();
                            ctx.lineWidth = 2.5;
                            ctx.strokeStyle = themeColor;
                            ctx.stroke();
                        }
                    });
                },

                renderStatusChart() {
                    const canvas = this.$refs.statusChart;
                    if (!canvas) return;
                    const ctx = canvas.getContext('2d');
                    if (!ctx) return;

                    const tv = this.getThemeVars();
                    const container = canvas.parentElement;
                    const size = Math.min(container.clientWidth, container.clientHeight) || 176;

                    const dpr = window.devicePixelRatio || 1;
                    canvas.width = size * dpr;
                    canvas.height = size * dpr;
                    canvas.style.width = `${size}px`;
                    canvas.style.height = `${size}px`;
                    ctx.resetTransform?.() || ctx.setTransform(1, 0, 0, 1, 0, 0);
                    ctx.scale(dpr, dpr);

                    const center = size / 2;
                    const radius = (size / 2) - 8;
                    const innerRadius = radius - 22;

                    const total = this.statusBreakdown.reduce((a, b) => a + Number(b.count || 0), 0);

                    ctx.clearRect(0, 0, size, size);

                    if (total === 0) {
                        // Empty Donut Placeholder
                        ctx.beginPath();
                        ctx.arc(center, center, radius, 0, Math.PI * 2);
                        ctx.fillStyle = tv.bgSubtle;
                        ctx.fill();

                        ctx.beginPath();
                        ctx.arc(center, center, innerRadius, 0, Math.PI * 2);
                        ctx.fillStyle = tv.bgSurface;
                        ctx.fill();

                        ctx.fillStyle = tv.textMuted;
                        ctx.font = 'bold 12px Inter, sans-serif';
                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'middle';
                        ctx.fillText('No Data', center, center);
                        return;
                    }

                    // Map status colors dynamically using theme variables
                    const statusThemeColors = {
                        'Confirmed': tv.success,
                        'Checked In': tv.info,
                        'Pending': tv.warning,
                        'Cancelled': tv.danger
                    };

                    let start = -Math.PI / 2;

                    this.statusBreakdown.forEach(item => {
                        const count = Number(item.count || 0);
                        if (count <= 0) return;

                        const slice = (count / total) * 2 * Math.PI;

                        ctx.beginPath();
                        ctx.moveTo(center, center);
                        ctx.arc(center, center, radius, start, start + slice);
                        ctx.fillStyle = statusThemeColors[item.label] || item.color;
                        ctx.fill();

                        start += slice;
                    });

                    // Donut Center Hole
                    ctx.beginPath();
                    ctx.arc(center, center, innerRadius, 0, 2 * Math.PI);
                    ctx.fillStyle = tv.bgSurface;
                    ctx.fill();

                    // Donut Center Text
                    ctx.fillStyle = tv.textBase;
                    ctx.font = 'bold 22px Inter, sans-serif';
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'middle';
                    ctx.fillText(this.formatCompact(total), center, center - 8);

                    ctx.font = '10px Inter, sans-serif';
                    ctx.fillStyle = tv.textMuted;
                    ctx.fillText('Bookings', center, center + 14);
                },
            }
        });
    </script>
    @endPushOnce
</x-admin::layouts>