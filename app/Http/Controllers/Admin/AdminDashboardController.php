<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\DataGrids\DashboardBookingDataGrid;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDO;

class AdminDashboardController extends Controller
{
    /**
     * Display the Admin Dashboard view.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\JsonResponse
     */
    public function index()
    {
        if (request()->ajax()) {
            return datagrid(DashboardBookingDataGrid::class)->process();
        }

        return view('admin::dashboard.index');
    }

    /**
     * Fetch real-time dashboard analytics via MySQL Stored Procedure.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function analytics()
    {
        $now = Carbon::now();
        $today = $now->toDateString();
        $startOfMonth = $now->copy()->startOfMonth()->toDateTimeString();
        $startOfLastMonth = $now->copy()->subMonth()->startOfMonth()->toDateTimeString();
        $endOfLastMonth = $now->copy()->subMonth()->endOfMonth()->toDateTimeString();
        $start30d = $now->copy()->subDays(29)->toDateString();
        $start12m = $now->copy()->subMonths(11)->startOfMonth()->toDateString();

        // Fetch All Dashboard Data in a Single Call via MySQL Stored Procedure
        $data = $this->fetchDataViaStoredProcedure(
            $startOfMonth,
            $startOfLastMonth,
            $endOfLastMonth,
            $today,
            $start30d,
            $start12m
        );

        $bookingAgg = $data['stats'];
        $dailyRecords = $data['daily'];
        $monthlyRecords = $data['monthly'];
        $topClubs = $data['top_clubs'];
        $paymentMethods = $data['payment_methods'];
        $rolesRaw = $data['roles'];

        // Percentage Growth calculations (Safe from division by zero)
        $revenueGrowth = ($bookingAgg->last_month_revenue ?? 0) > 0
            ? round((($bookingAgg->this_month_revenue - $bookingAgg->last_month_revenue) / $bookingAgg->last_month_revenue) * 100, 1)
            : (($bookingAgg->this_month_revenue ?? 0) > 0 ? 100 : 0);

        $bookingsGrowth = ($bookingAgg->last_month_bookings ?? 0) > 0
            ? round((($bookingAgg->this_month_bookings - $bookingAgg->last_month_bookings) / $bookingAgg->last_month_bookings) * 100, 1)
            : (($bookingAgg->this_month_bookings ?? 0) > 0 ? 100 : 0);

        $clientsGrowth = ($bookingAgg->last_month_clients ?? 0) > 0
            ? round((($bookingAgg->this_month_clients - $bookingAgg->last_month_clients) / $bookingAgg->last_month_clients) * 100, 1)
            : (($bookingAgg->this_month_clients ?? 0) > 0 ? 100 : 0);

        // Build 7D, 30D, and 12M Chart Series
        $charts = $this->formatChartSeries($now, $dailyRecords, $monthlyRecords);

        // Booking Status Donut Breakdown
        $statusBreakdown = [
            [
                'label' => 'Confirmed',
                'count' => (int) ($bookingAgg->confirmed_bookings ?? 0),
                'color' => '#10b981', // Emerald
            ],
            [
                'label' => 'Checked In',
                'count' => (int) ($bookingAgg->checked_in_bookings ?? 0),
                'color' => '#3b82f6', // Blue
            ],
            [
                'label' => 'Pending',
                'count' => (int) ($bookingAgg->pending_bookings ?? 0),
                'color' => '#f59e0b', // Amber
            ],
            [
                'label' => 'Cancelled',
                'count' => (int) ($bookingAgg->cancelled_bookings ?? 0),
                'color' => '#ef4444', // Rose
            ],
        ];

        // Format Roles & User Adoption Distribution
        $totalUsers = !empty($rolesRaw) ? (int) ($rolesRaw[0]->total_users ?? 0) : 0;
        if ($totalUsers === 0) {
            $totalUsers = (int) DB::table('users')->count();
        }

        $roleColors = ['#6366f1', '#10b981', '#f59e0b', '#8b5cf6', '#06b6d4', '#ec4899'];
        $rolesBreakdown = collect($rolesRaw)->map(function ($role, $index) use ($totalUsers, $roleColors) {
            $count = (int) ($role->users_count ?? 0);
            $percent = $totalUsers > 0 ? round(($count / $totalUsers) * 100, 1) : 0;
            return [
                'id'         => $role->id,
                'name'       => $role->name,
                'type'       => $role->type ?? 'custom',
                'count'      => $count,
                'percentage' => $percent,
                'color'      => $roleColors[$index % count($roleColors)],
            ];
        })->toArray();

        // Structure Statistics Payload
        $stats = [
            'total_revenue' => (float) ($bookingAgg->total_revenue ?? 0),
            'this_month_revenue' => (float) ($bookingAgg->this_month_revenue ?? 0),
            'today_revenue' => (float) ($bookingAgg->today_revenue ?? 0),
            'revenue_growth' => $revenueGrowth,
            'revenue_trend' => $revenueGrowth >= 0 ? 'up' : 'down',

            'total_bookings' => (int) ($bookingAgg->total_bookings ?? 0),
            'this_month_bookings' => (int) ($bookingAgg->this_month_bookings ?? 0),
            'today_bookings' => (int) ($bookingAgg->today_bookings ?? 0),
            'bookings_growth' => $bookingsGrowth,
            'bookings_trend' => $bookingsGrowth >= 0 ? 'up' : 'down',

            'total_guests' => (int) ($bookingAgg->total_guests ?? 0),
            'avg_booking_value' => (float) ($bookingAgg->avg_booking_value ?? 0),

            'total_clients' => (int) ($bookingAgg->total_clients ?? 0),
            'this_month_clients' => (int) ($bookingAgg->this_month_clients ?? 0),
            'active_clients' => (int) ($bookingAgg->active_clients ?? 0),
            'clients_growth' => $clientsGrowth,
            'clients_trend' => $clientsGrowth >= 0 ? 'up' : 'down',

            'total_clubs' => (int) ($bookingAgg->total_clubs ?? 0),
            'active_clubs' => (int) ($bookingAgg->active_clubs ?? 0),
            'avg_rating' => round((float) ($bookingAgg->avg_rating ?? 0), 1),

            'total_tables' => (int) ($bookingAgg->total_tables ?? 0),
            'total_capacity' => (int) ($bookingAgg->total_capacity ?? 0),
            'active_tables' => (int) ($bookingAgg->active_tables ?? 0),

            'pending_bookings' => (int) ($bookingAgg->pending_bookings ?? 0),
            'confirmed_bookings' => (int) ($bookingAgg->confirmed_bookings ?? 0),
            'checked_in_bookings' => (int) ($bookingAgg->checked_in_bookings ?? 0),
            'cancelled_bookings' => (int) ($bookingAgg->cancelled_bookings ?? 0),
        ];

        return response()->json([
            'success'         => true,
            'stats'           => $stats,
            'charts'          => $charts,
            'statusBreakdown' => $statusBreakdown,
            'topClubs'        => $topClubs,
            'paymentMethods'  => $paymentMethods,
            'rolesBreakdown'  => $rolesBreakdown,
            'totalUsers'      => $totalUsers,
        ]);
    }

    /**
     * Execute Stored Procedure and fetch all 6 result sets in a single database call.
     *
     * @param string $startOfMonth
     * @param string $startOfLastMonth
     * @param string $endOfLastMonth
     * @param string $today
     * @param string $start30d
     * @param string $start12m
     * @return array
     */
    protected function fetchDataViaStoredProcedure(
        string $startOfMonth,
        string $startOfLastMonth,
        string $endOfLastMonth,
        string $today,
        string $start30d,
        string $start12m
    ): array {
        try {
            return $this->runStoredProcedure($startOfMonth, $startOfLastMonth, $endOfLastMonth, $today, $start30d, $start12m);
        } catch (\Throwable $e) {
            // If stored procedure doesn't exist or is outdated, recreate it and retry
            $this->ensureStoredProcedureExists();
            return $this->runStoredProcedure($startOfMonth, $startOfLastMonth, $endOfLastMonth, $today, $start30d, $start12m);
        }
    }

    /**
     * Run the MySQL Stored Procedure using PDO.
     *
     * @param string $startOfMonth
     * @param string $startOfLastMonth
     * @param string $endOfLastMonth
     * @param string $today
     * @param string $start30d
     * @param string $start12m
     * @return array
     */
    protected function runStoredProcedure(
        string $startOfMonth,
        string $startOfLastMonth,
        string $endOfLastMonth,
        string $today,
        string $start30d,
        string $start12m
    ): array {
        $pdo = DB::connection()->getPdo();
        $stmt = $pdo->prepare("CALL sp_get_dashboard_statistics(?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $startOfMonth,
            $startOfLastMonth,
            $endOfLastMonth,
            $today,
            $start30d,
            $start12m
        ]);

        // 1. Stats Row
        $stats = $stmt->fetch(PDO::FETCH_OBJ) ?: (object)[];

        // 2. Daily Trends
        $stmt->nextRowset();
        $daily = collect($stmt->fetchAll(PDO::FETCH_OBJ) ?: [])->keyBy('date');

        // 3. Monthly Trends
        $stmt->nextRowset();
        $monthly = collect($stmt->fetchAll(PDO::FETCH_OBJ) ?: [])->keyBy('ym');

        // 4. Top Clubs
        $stmt->nextRowset();
        $topClubs = $stmt->fetchAll(PDO::FETCH_OBJ) ?: [];

        // 5. Payment Methods
        $stmt->nextRowset();
        $paymentMethods = $stmt->fetchAll(PDO::FETCH_OBJ) ?: [];

        // 6. Roles & User Percentage
        $hasNext = $stmt->nextRowset();
        $roles = $hasNext ? ($stmt->fetchAll(PDO::FETCH_OBJ) ?: []) : [];

        $stmt->closeCursor();

        if (empty($roles)) {
            $roles = DB::table('roles as r')
                ->leftJoin('users as u', 'r.id', '=', 'u.role_id')
                ->select('r.id', 'r.name', 'r.type', DB::raw('COUNT(u.id) as users_count'), DB::raw('(SELECT COUNT(*) FROM users) as total_users'))
                ->groupBy('r.id', 'r.name', 'r.type')
                ->orderByDesc('users_count')
                ->get()
                ->toArray();
            try {
                $this->ensureStoredProcedureExists();
            } catch (\Throwable $e) {}
        }

        return [
            'stats' => $stats,
            'daily' => $daily,
            'monthly' => $monthly,
            'top_clubs' => $topClubs,
            'payment_methods' => $paymentMethods,
            'roles' => $roles,
        ];
    }

    /**
     * Create or update the Stored Procedure in MySQL.
     *
     * @return void
     */
    protected function ensureStoredProcedureExists(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_get_dashboard_statistics;");
        DB::unprepared("
            CREATE PROCEDURE sp_get_dashboard_statistics(
                IN p_start_of_month DATETIME,
                IN p_start_of_last_month DATETIME,
                IN p_end_of_last_month DATETIME,
                IN p_today DATE,
                IN p_start_30d DATE,
                IN p_start_12m DATE
            )
            BEGIN
                -- 1. Consolidated Core Metrics Row
                SELECT 
                    COUNT(b.id) AS total_bookings,
                    COALESCE(SUM(b.total_amount_incl_tax), 0) AS total_revenue,
                    COALESCE(SUM(b.guest_count), 0) AS total_guests,
                    COALESCE(AVG(b.total_amount_incl_tax), 0) AS avg_booking_value,
                    
                    SUM(CASE WHEN b.created_at >= p_start_of_month THEN 1 ELSE 0 END) AS this_month_bookings,
                    SUM(CASE WHEN b.created_at >= p_start_of_last_month AND b.created_at <= p_end_of_last_month THEN 1 ELSE 0 END) AS last_month_bookings,
                    
                    COALESCE(SUM(CASE WHEN b.created_at >= p_start_of_month THEN b.total_amount_incl_tax ELSE 0 END), 0) AS this_month_revenue,
                    COALESCE(SUM(CASE WHEN b.created_at >= p_start_of_last_month AND b.created_at <= p_end_of_last_month THEN b.total_amount_incl_tax ELSE 0 END), 0) AS last_month_revenue,
                    
                    SUM(CASE WHEN b.booking_date = p_today THEN 1 ELSE 0 END) AS today_bookings,
                    COALESCE(SUM(CASE WHEN b.booking_date = p_today THEN b.total_amount_incl_tax ELSE 0 END), 0) AS today_revenue,
                    
                    SUM(CASE WHEN b.status = 'pending' THEN 1 ELSE 0 END) AS pending_bookings,
                    SUM(CASE WHEN b.status = 'confirmed' THEN 1 ELSE 0 END) AS confirmed_bookings,
                    SUM(CASE WHEN b.status = 'checked_in' THEN 1 ELSE 0 END) AS checked_in_bookings,
                    SUM(CASE WHEN b.status = 'cancelled' THEN 1 ELSE 0 END) AS cancelled_bookings,

                    (SELECT COUNT(*) FROM clients) AS total_clients,
                    (SELECT COUNT(*) FROM clients WHERE created_at >= p_start_of_month) AS this_month_clients,
                    (SELECT COUNT(*) FROM clients WHERE created_at >= p_start_of_last_month AND created_at <= p_end_of_last_month) AS last_month_clients,
                    (SELECT COUNT(*) FROM clients WHERE is_active = 1) AS active_clients,

                    (SELECT COUNT(*) FROM clubs) AS total_clubs,
                    (SELECT COUNT(*) FROM clubs WHERE is_active = 1) AS active_clubs,
                    (SELECT COALESCE(AVG(average_rating), 0) FROM clubs) AS avg_rating,

                    (SELECT COUNT(*) FROM tables) AS total_tables,
                    (SELECT COALESCE(SUM(capacity), 0) FROM tables) AS total_capacity,
                    (SELECT COUNT(*) FROM tables WHERE status = 'active') AS active_tables
                FROM bookings b;

                -- 2. Daily Trends for Charts (Last 30 Days)
                SELECT 
                    DATE(booking_date) AS `date`,
                    COUNT(id) AS bookings_count,
                    COALESCE(SUM(total_amount_incl_tax), 0) AS revenue
                FROM bookings
                WHERE booking_date >= p_start_30d
                GROUP BY DATE(booking_date)
                ORDER BY `date` ASC;

                -- 3. Monthly Trends for Charts (Last 12 Months)
                SELECT 
                    DATE_FORMAT(booking_date, '%Y-%m') AS ym,
                    COUNT(id) AS bookings_count,
                    COALESCE(SUM(total_amount_incl_tax), 0) AS revenue
                FROM bookings
                WHERE booking_date >= p_start_12m
                GROUP BY DATE_FORMAT(booking_date, '%Y-%m')
                ORDER BY ym ASC;

                -- 4. Top 5 Performing Clubs
                SELECT 
                    c.id,
                    c.name,
                    c.city,
                    c.average_rating,
                    c.logo,
                    c.is_active,
                    COUNT(b.id) AS total_bookings,
                    COALESCE(SUM(b.total_amount_incl_tax), 0) AS total_revenue
                FROM clubs c
                LEFT JOIN bookings b ON c.id = b.club_id
                GROUP BY c.id, c.name, c.city, c.average_rating, c.logo, c.is_active
                ORDER BY total_revenue DESC
                LIMIT 5;

                -- 5. Payment Methods Breakdown
                SELECT 
                    COALESCE(NULLIF(payment_method, ''), 'Unspecified') AS method,
                    COUNT(*) AS `count`,
                    COALESCE(SUM(total_amount_incl_tax), 0) AS total_amount
                FROM bookings
                GROUP BY method
                ORDER BY `count` DESC;

                -- 6. Role Distribution & User Percentage
                SELECT 
                    r.id,
                    r.name,
                    r.type,
                    COUNT(u.id) AS users_count,
                    (SELECT COUNT(*) FROM users) AS total_users
                FROM roles r
                LEFT JOIN users u ON r.id = u.role_id
                GROUP BY r.id, r.name, r.type
                ORDER BY users_count DESC;
            END;
        ");
    }

    /**
     * Format Chart Datasets.
     *
     * @param Carbon $now
     * @param \Illuminate\Support\Collection $dailyRecords
     * @param \Illuminate\Support\Collection $monthlyRecords
     * @return array
     */
    protected function formatChartSeries(Carbon $now, $dailyRecords, $monthlyRecords): array
    {
        // 7D Dataset
        $labels7d = [];
        $revenue7d = [];
        $bookings7d = [];
        for ($i = 6; $i >= 0; $i--) {
            $dt = $now->copy()->subDays($i);
            $dateKey = $dt->toDateString();
            $labels7d[] = $dt->format('D, M d');
            $row = $dailyRecords->get($dateKey);
            $revenue7d[] = $row ? (float) $row->revenue : 0;
            $bookings7d[] = $row ? (int) $row->bookings_count : 0;
        }

        // 30D Dataset
        $labels30d = [];
        $revenue30d = [];
        $bookings30d = [];
        for ($i = 29; $i >= 0; $i--) {
            $dt = $now->copy()->subDays($i);
            $dateKey = $dt->toDateString();
            $labels30d[] = $dt->format('M d');
            $row = $dailyRecords->get($dateKey);
            $revenue30d[] = $row ? (float) $row->revenue : 0;
            $bookings30d[] = $row ? (int) $row->bookings_count : 0;
        }

        // 12M Dataset
        $labels12m = [];
        $revenue12m = [];
        $bookings12m = [];
        for ($i = 11; $i >= 0; $i--) {
            $dt = $now->copy()->subMonths($i);
            $ymKey = $dt->format('Y-m');
            $labels12m[] = $dt->format('M Y');
            $row = $monthlyRecords->get($ymKey);
            $revenue12m[] = $row ? (float) $row->revenue : 0;
            $bookings12m[] = $row ? (int) $row->bookings_count : 0;
        }

        return [
            '7d' => [
                'labels'   => $labels7d,
                'revenue'  => $revenue7d,
                'bookings' => $bookings7d,
            ],
            '30d' => [
                'labels'   => $labels30d,
                'revenue'  => $revenue30d,
                'bookings' => $bookings30d,
            ],
            '12m' => [
                'labels'   => $labels12m,
                'revenue'  => $revenue12m,
                'bookings' => $bookings12m,
            ],
        ];
    }
}
