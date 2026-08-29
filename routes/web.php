<?php

use App\Model\Booking;
use App\Model\Client;
use App\Model\ClubTable as ModelClubTable;
use App\Model\MobileAppRole;
use App\Models\Supabase\ClubTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;

Route::withoutMiddleware(['auth'])->group(function () {
    Route::get('/', function (Request $request) {
        return "Bro, Yaha kuch nhi hai. Aage 100 kilometer jaker left lene, Happy journey.";
        // $data = ClubTable::all();
        // $data = DB::connection('supabase')->table('club_tables')->get();
        // $data = Booking::all();

        // return 1;

        // $data = Http::withHeaders([
        //     'apikey' => "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6IndzbWJ2cmVyZ3F3eXh6ZnFjcXJjIiwicm9sZSI6InNlcnZpY2Vfcm9sZSIsImlhdCI6MTc4NjkyODU0MCwiZXhwIjoyMTAyNTA0NTQwfQ.MXYrOWT-mzZlmZiqN3RiZzO-1XGGWZzGiaVayl4MBdg",
        //     'Authorization' => 'Bearer ' . "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6IndzbWJ2cmVyZ3F3eXh6ZnFjcXJjIiwicm9sZSI6InNlcnZpY2Vfcm9sZSIsImlhdCI6MTc4NjkyODU0MCwiZXhwIjoyMTAyNTA0NTQwfQ.MXYrOWT-mzZlmZiqN3RiZzO-1XGGWZzGiaVayl4MBdg",
        //     'Content-Type' => 'application/json',
        //     'Prefer' => 'return=representation', // Returns created/updated rows
        // ])->get('https://wsmbvrergqwyxzfqcqrc.supabase.co/rest/v1/club_tables');

        // $data = json_decode($data->body());

        return $data;
    });
});
