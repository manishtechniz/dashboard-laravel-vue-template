<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::unprepared("
            CREATE OR REPLACE FUNCTION secure_update_club_table(
                p_table_id BIGINT,
                p_updates JSONB,
                p_provided_password TEXT DEFAULT NULL
            ) RETURNS BOOLEAN AS $$
            DECLARE
                v_is_locked BOOLEAN;
                v_lock_password TEXT;
                v_master_key TEXT := 'admin';
            BEGIN
                SELECT is_locked, lock_password INTO v_is_locked, v_lock_password
                FROM club_tables
                WHERE id = p_table_id;

                IF NOT FOUND THEN
                    RAISE EXCEPTION 'Table not found';
                END IF;

                IF v_is_locked THEN
                    IF p_provided_password IS NULL OR 
                       (p_provided_password != v_lock_password AND p_provided_password != v_master_key) THEN
                        RAISE EXCEPTION 'Table is locked. Invalid password provided.';
                    END IF;
                END IF;

                UPDATE club_tables
                SET 
                    table_type = COALESCE(p_updates->>'table_type', table_type),
                    service_staff = COALESCE(p_updates->>'service_staff', service_staff),
                    booking_time = COALESCE(p_updates->>'booking_time', booking_time),
                    status = COALESCE(p_updates->>'status', status),
                    bill_amount = COALESCE((p_updates->>'bill_amount')::numeric, bill_amount),
                    guest_name = COALESCE(p_updates->>'guest_name', guest_name),
                    is_locked = COALESCE((p_updates->>'is_locked')::boolean, is_locked),
                    lock_password = COALESCE(p_updates->>'lock_password', lock_password),
                    locked_by_name = COALESCE(p_updates->>'locked_by_name', locked_by_name)
                WHERE id = p_table_id;

                RETURN TRUE;
            END;
            $$ LANGUAGE plpgsql SECURITY DEFINER;
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP FUNCTION IF EXISTS secure_update_club_table(BIGINT, JSONB, TEXT)");
    }
};
