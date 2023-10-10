<?php

namespace Database\Seeders;

use App\Models\Exam;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ExamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Exam::insert([
            'uuid' => Str::uuid(),
            'id' => 100,
            'name' => 'AWS Solutions Architect Professional SAP-C02 - Hard',
            'time_minute' => 180,
            'subject_id' => 1,
        ]);
    }
}
