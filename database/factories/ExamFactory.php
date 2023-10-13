<?php

namespace Database\Factories;

use App\Models\Subject;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Exam>
 */
class ExamFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
            'uuid' => Str::uuid(),
            'name' => 'exam name ' . hexdec(uniqid()),
            'thumbnail' => 'images/aws-sap-3.png',
            'time' => 10800, // 180 mins
            'pass_score' => 75,
            'allow_shuffle' =>rand(0,1),
            'subject_id' => Subject::inRandomOrder()->first()->id,
        ];
    }
}
