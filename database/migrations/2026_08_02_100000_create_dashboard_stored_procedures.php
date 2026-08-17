<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Add performance indexes if not already present
        $existingIndexes = collect(DB::select("SHOW INDEX FROM bookings"))->pluck('Key_name')->unique()->toArray();

        if (!in_array('bookings_booking_date_idx', $existingIndexes)) {
            DB::statement("CREATE INDEX bookings_booking_date_idx ON bookings (booking_date)");
        }
        if (!in_array('bookings_status_idx', $existingIndexes)) {
            DB::statement("CREATE INDEX bookings_status_idx ON bookings (status)");
        }
        if (!in_array('bookings_created_at_idx', $existingIndexes)) {
            DB::statement("CREATE INDEX bookings_created_at_idx ON bookings (created_at)");
        }
        if (!in_array('bookings_club_id_idx', $existingIndexes)) {
            DB::statement("CREATE INDEX bookings_club_id_idx ON bookings (club_id)");
        }

        // 2. Create Stored Procedure for Dashboard
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
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_get_dashboard_statistics;");
    }
};
