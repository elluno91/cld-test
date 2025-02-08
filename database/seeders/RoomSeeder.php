<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('rooms')->insert([
            'floor' => 1,
            'number' => 101,
            'pax_count' => 2,
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => 'system',
            'is_deleted' => false,
        ]);
        DB::table('rooms')->insert([
            'floor' => 1,
            'number' => 102,
            'pax_count' => 3,
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => 'system',
            'is_deleted' => false,
        ]);
        DB::table('rooms')->insert([
            'floor' => 1,
            'number' => 103,
            'pax_count' => 4,
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => 'system',
            'is_deleted' => false,
        ]);
    }
}
