<?php

namespace Database\Factories;

use App\Enums\ExamMode;
use App\Models\Exam;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserExam>
 */
class UserExamFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'exam_id' => Exam::inRandomOrder()->first()->id,
            'user_id' => User::inRandomOrder()->first()->id,
            'exam_mode' => ExamMode::getRandomValue(),
            'score' => rand(1, 100),
            'time_remain' => rand(1, 10),
            'is_finish' => rand(0, 1),
            'record' => json_encode(['true',]),
        ];
    }
}
