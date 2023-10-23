<?php

namespace Database\Seeders;

use App\Helper\DateTimeHelper;
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
            [
                'uuid' => Str::uuid(),
                'id' => 100,
                'name' => 'SAP-C02 - Hard',
                'time' => 180,
                'subject_id' => 1,
                'thumbnail' => 'images/aws-sap-3.png',
                'allow_shuffle' => 0,
                'created_at' => DateTimeHelper::now(),
            ],
            [
                'uuid' => Str::uuid(),
                'id' => 101,
                'name' => 'VPC',
                'time' => 180,
                'subject_id' => 1,
                'thumbnail' => 'images/aws-sap-3.png',
                'allow_shuffle' => 0,
                'created_at' => DateTimeHelper::now(),
            ],
            [
                'uuid' => Str::uuid(),
                'id' => 102,
                'name' => 'Active directory',
                'time' => 180,
                'subject_id' => 1,
                'thumbnail' => 'images/aws-sap-3.png',
                'allow_shuffle' => 0,
                'created_at' => DateTimeHelper::now(),
            ],
        ]);
    }
}
