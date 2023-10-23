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
        if (env('IS_PROD_DB', true))
            return;

        $this->call(SubjectSeeder::class);
        if (env('IS_PROD_DB', true))
            $this->call(ExamSeeder::class);
        else {
            $exam = Exam::factory([
                'subject_id' => 1,
                'allow_shuffle' => false,
            ])->create();

            $question = Question::factory([
                'text' => 'question 1, correct answer = [2, 5]. select TWO.',
                'is_multichoice' => true,
            ])->create();

            Option::factory([
                'text' => 'option 2 true',
                'is_correct' => true,
                'question_id' => $question->id,
            ])->create();

            Option::factory([
                'text' => 'option 5 true',
                'is_correct' => true,
                'question_id' => $question->id,
            ])->create();

            Option::factory(3, [
                'is_correct' => false,
                'question_id' => $question->id,
            ])->create();
            ExamQuestion::factory([
                'exam_id' => $exam->id,
                'question_id' => $question->id,
            ])->create();

            $question = Question::factory([
                'text' => 'question 2, correct answer = 1',
                'is_multichoice' => false,
            ])->create();

            Option::factory([
                'text' => 'option 1 true answer',
                'is_correct' => true,
                'question_id' => $question->id,
            ])->create();

            Option::factory(3, [
                'is_correct' => false,
                'question_id' => $question->id,
            ])->create();
            ExamQuestion::factory([
                'exam_id' => $exam->id,
                'question_id' => $question->id,
            ])->create();
        }
    }

    private function generateQuestionOption(int $examId, bool $isMultiChoice)
    {
    }
}
