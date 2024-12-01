<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class WebsiteTrendsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [];

        for ($i = 0; $i < 1000; $i++) {
            $randomDate = Carbon::now()
                ->subMonths(rand(0, 5)) // Ambil bulan acak dalam 6 bulan terakhir
                ->subDays(rand(0, 30))  // Ambil hari acak dalam bulan tersebut
                ->setTime(rand(0, 23), rand(0, 59), rand(0, 59)); // Waktu acak (jam, menit, detik)

            $data[] = [
                'website_id' => rand(1, 5),
                'user_id' => rand(1, 100),
                'created_at' => $randomDate,
                'updated_at' => $randomDate->copy()->addDays(rand(0, 10)), // Tambahkan 0-10 hari ke updated_at
            ];
        }

        DB::table('website_trends')->insert($data);
    }
}
