<?php

namespace Database\Seeders\Supabase;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ClubTablesSeeder extends Seeder
{
    /**
     * Run the database seeds for the Supabase connection.
     *
     * @return void
     */
    public function run()
    {
        $jsonData = '[{"table_number":"1","table_type":"Empty Table","service_staff":"None","booking_time":"00:00 AM","status":"Empty","bill_amount":0,"guest_name":"","is_locked":false},{"table_number":"2","table_type":"Empty Table","service_staff":"None","booking_time":"00:00 AM","status":"Empty","bill_amount":0,"guest_name":"","is_locked":false},{"table_number":"3","table_type":"Empty Table","service_staff":"None","booking_time":"00:00 AM","status":"Empty","bill_amount":0,"guest_name":"","is_locked":false},{"table_number":"4","table_type":"Empty Table","service_staff":"None","booking_time":"00:00 AM","status":"Empty","bill_amount":0,"guest_name":"","is_locked":false},{"table_number":"5","table_type":"Empty Table","service_staff":"None","booking_time":"00:00 AM","status":"Empty","bill_amount":0,"guest_name":"","is_locked":false},{"table_number":"6","table_type":"Empty Table","service_staff":"None","booking_time":"00:00 AM","status":"Empty","bill_amount":0,"guest_name":"","is_locked":false},{"table_number":"7","table_type":"Empty Table","service_staff":"None","booking_time":"00:00 AM","status":"Empty","bill_amount":0,"guest_name":"","is_locked":false},{"table_number":"8","table_type":"Empty Table","service_staff":"None","booking_time":"00:00 AM","status":"Empty","bill_amount":0,"guest_name":"","is_locked":false},{"table_number":"9","table_type":"Empty Table","service_staff":"None","booking_time":"00:00 AM","status":"Empty","bill_amount":0,"guest_name":"","is_locked":false},{"table_number":"S1","table_type":"Empty Table","service_staff":"None","booking_time":"00:00 AM","status":"Empty","bill_amount":0,"guest_name":"","is_locked":false},{"table_number":"S2","table_type":"Empty Table","service_staff":"None","booking_time":"00:00 AM","status":"Empty","bill_amount":0,"guest_name":"","is_locked":false},{"table_number":"S3","table_type":"Empty Table","service_staff":"None","booking_time":"00:00 AM","status":"Empty","bill_amount":0,"guest_name":"","is_locked":false},{"table_number":"S4","table_type":"Empty Table","service_staff":"None","booking_time":"00:00 AM","status":"Empty","bill_amount":0,"guest_name":"","is_locked":false},{"table_number":"S5","table_type":"Empty Table","service_staff":"None","booking_time":"00:00 AM","status":"Empty","bill_amount":0,"guest_name":"","is_locked":false},{"table_number":"S6","table_type":"Empty Table","service_staff":"None","booking_time":"00:00 AM","status":"Empty","bill_amount":0,"guest_name":"","is_locked":false},{"table_number":"S7","table_type":"Empty Table","service_staff":"None","booking_time":"00:00 AM","status":"Empty","bill_amount":0,"guest_name":"","is_locked":false},{"table_number":"S8","table_type":"Empty Table","service_staff":"None","booking_time":"00:00 AM","status":"Empty","bill_amount":0,"guest_name":"","is_locked":false},{"table_number":"S9","table_type":"Empty Table","service_staff":"None","booking_time":"00:00 AM","status":"Empty","bill_amount":0,"guest_name":"","is_locked":false}]';

        $tables = json_decode($jsonData, true);
        $now = Carbon::now();

        $insertData = array_map(function ($table) use ($now) {
            return [
                'table_number'  => $table['table_number'],
                'table_type'    => $table['table_type'],
                'service_staff' => $table['service_staff'],
                'booking_time'  => $table['booking_time'],
                'status'        => $table['status'],
                'bill_amount'   => $table['bill_amount'],
                'guest_name'    => $table['guest_name'] === '' ? null : $table['guest_name'],
                'is_locked'     => $table['is_locked'],
                'created_at'    => $now,
                'updated_at'    => $now,
            ];
        }, $tables);

        // Ensure 'supabase' matches the connection name in your config/database.php
        DB::connection('supabase')->table('club_tables')->insert($insertData);
    }
}
