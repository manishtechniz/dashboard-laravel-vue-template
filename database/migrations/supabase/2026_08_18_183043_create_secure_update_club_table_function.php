<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared("
            CREATE EXTENSION IF NOT EXISTS pgcrypto;

            CREATE OR REPLACE FUNCTION secure_update_club_table(
                p_table_id BIGINT,
                p_updates JSONB,
                p_provided_password TEXT DEFAULT NULL
            )
            RETURNS BOOLEAN
            AS $$
            DECLARE
                v_is_locked BOOLEAN;
                v_lock_password TEXT;
                v_master_key_hash TEXT := '$2a$12$7BM2e4jvl.0bvZHY2SN4ZeMRIdQAEOoqll80phiToH9GEH74ZJZgu'; 
                v_pepper TEXT := 'K9x!vR7#mQ2@Lp8Zw4^Hs6&Ny3*Df1'; 
                v_lock_password_valid BOOLEAN := FALSE;
                v_master_key_valid BOOLEAN := FALSE; 
            BEGIN

                SELECT
                    is_locked,
                    lock_password
                INTO
                    v_is_locked,
                    v_lock_password
                FROM club_tables
                WHERE id = p_table_id;

                IF NOT FOUND THEN
                    RAISE EXCEPTION 'Table not found';
                END IF;


                /*
                |--------------------------------------------------------------------------
                | Verify password if table is locked
                |--------------------------------------------------------------------------
                */

                IF v_is_locked THEN

                    IF p_provided_password IS NULL THEN
                        RAISE EXCEPTION 'Password is required';
                    END IF;


                    /*
                    |--------------------------------------------------------------------------
                    | Check table-specific password
                    |--------------------------------------------------------------------------
                    */

                    IF v_lock_password IS NOT NULL THEN

                        v_lock_password_valid :=
                            crypt(
                                p_provided_password || v_pepper,
                                v_lock_password
                            ) = v_lock_password;

                    END IF;


                    /*
                    |--------------------------------------------------------------------------
                    | Check master key
                    |--------------------------------------------------------------------------
                    */

                    v_master_key_valid :=
                        crypt(
                            p_provided_password || v_pepper,
                            v_master_key_hash
                        ) = v_master_key_hash;


                    /*
                    |--------------------------------------------------------------------------
                    | Reject if both passwords are invalid
                    |--------------------------------------------------------------------------
                    */

                    IF NOT v_lock_password_valid
                       AND NOT v_master_key_valid
                    THEN
                        RAISE EXCEPTION
                            'Table is locked. Invalid password provided.';
                    END IF;

                END IF;


                /*
                |--------------------------------------------------------------------------
                | Update table
                |--------------------------------------------------------------------------
                */

                UPDATE club_tables
                SET

                    table_type =
                        COALESCE(
                            p_updates->>'table_type',
                            table_type
                        ),

                    service_staff =
                        COALESCE(
                            p_updates->>'service_staff',
                            service_staff
                        ),

                    booking_time =
                        COALESCE(
                            p_updates->>'booking_time',
                            booking_time
                        ),

                    status =
                        COALESCE(
                            p_updates->>'status',
                            status
                        ),

                    bill_amount =
                        COALESCE(
                            (p_updates->>'bill_amount')::numeric,
                            bill_amount
                        ),

                    guest_name =
                        COALESCE(
                            p_updates->>'guest_name',
                            guest_name
                        ),

                    is_locked =
                        COALESCE(
                            (p_updates->>'is_locked')::boolean,
                            is_locked
                        ),

                    lock_password =
                        CASE
                            WHEN p_updates ? 'lock_password'
                            THEN crypt(
                                (p_updates->>'lock_password') || v_pepper,
                                gen_salt('bf', 12)
                            )
                            ELSE lock_password
                        END,

                    locked_by_name =
                        COALESCE(
                            p_updates->>'locked_by_name',
                            locked_by_name
                        )

                WHERE id = p_table_id;

                RETURN TRUE;

            END;
            $$
            LANGUAGE plpgsql
            SECURITY DEFINER;
        ");
    }

    public function down(): void
    {
        DB::unprepared("
            DROP FUNCTION IF EXISTS
            secure_update_club_table(BIGINT, JSONB, TEXT);
        ");
    }
};
