<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Question>
 */
class QuestionFactory extends Factory
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
            'uuid' => Str::uuid()->toString(),
            'text' => 'question text ' . hexdec(uniqid()),
            'explanation' => 'question explanation ' . hexdec(uniqid()),
            'note' => 'question note' . hexdec(uniqid()),
            'is_multichoice' => rand(0, 1),
        ];
    }
}
