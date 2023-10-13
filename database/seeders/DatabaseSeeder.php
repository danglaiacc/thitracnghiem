<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Exam;
use App\Models\ExamQuestion;
use App\Models\Option;
use App\Models\Question;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name' => 'laii',
            'email' => 'admin@admin.com',
            'password' => '$2y$10$y73/YlP/23EB/I3uc6xqt.r6BYUnTv4FXc9N6eN4gU8x48kC4tR3S' // admin
        ]);
        $this->call(SubjectSeeder::class);
        if (env('IS_PROD_DB', true))
            $this->call(ExamSeeder::class);
        else {
            $exam = Exam::factory([
                'subject_id' => 1,
                'allow_shuffle' => false,
            ])->create();
            $this->generateQuestionOption($exam->id, true);
            $this->generateQuestionOption($exam->id, false);
        }
    }

    private function generateQuestionOption(int $examId, bool $isMultiChoice)
    {
        $question = Question::factory([
            'is_multichoice' => $isMultiChoice,
        ])->create();

        $options = Option::factory(5, [
            'question_id' => $question->id,
        ])->create();

        ExamQuestion::factory([
            'exam_id' => $examId,
            'question_id' => $question->id,
        ])->create();
    }
}
