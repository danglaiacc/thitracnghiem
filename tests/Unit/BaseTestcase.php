<?php

namespace Tests\Unit;

use App\Models\Exam;
use App\Models\ExamQuestion;
use App\Models\Option;
use App\Models\Question;
use App\Models\Subject;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\CreatesApplication;
use Tests\TestCase;

abstract class BaseTestcase extends TestCase
{
    use CreatesApplication;
    use DatabaseTransactions;
    public Exam $exam;

    public function getRandomNumber()
    {
        return hexdec(uniqid());
    }

    public function createExamAndQuestion()
    {
        $subject = Subject::factory()->create();
        $this->exam = Exam::factory([
            'subject_id' => $subject->id,
            'allow_shuffle' => false,
        ])->create();

        $question = Question::factory([
            'text' => 'question 1, correct answer = [2, 5]. select TWO.',
            'is_multichoice' => true,
        ])->create();

        Option::factory([
            'text' => 'option 2 true test test test',
            'is_correct' => true,
            'question_id' => $question->id,
        ])->create();

        Option::factory(3, [
            'is_correct' => false,
            'question_id' => $question->id,
        ])->create();

        Option::factory([
            'text' => 'option 5 true test test test',
            'is_correct' => true,
            'question_id' => $question->id,
        ])->create();

        ExamQuestion::factory([
            'exam_id' => $this->exam->id,
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
            'exam_id' => $this->exam->id,
            'question_id' => $question->id,
        ])->create();
    }

    public function getQuestionRandom()
    {
        return [
            [
                "id" => 2,
                "text" => "question 2, correct answer = 1",
                "explaination" => "question explaination 1779628854646166",
                "is_multichoice" => 0,
                "is_submit" => false,
                "user_answers" => [],
                "options" => [
                    [
                        "id" => 8,
                        "text" => "Option 2",
                        "is_correct" => 0,
                    ],
                    [
                        "id" => 7,
                        "text" => "Option 1 fake in base test",
                        "is_correct" => 0,
                    ],
                    [
                        "id" => 9,
                        "text" => "Option 3",
                        "is_correct" => 0,
                    ],
                    [
                        "id" => 6,
                        "text" => "option 1 true answer",
                        "is_correct" => 1,
                    ]
                ]
            ],
            [
                "id" => 1,
                "text" => "question 1, correct answer = [2, 5]. select TWO.",
                "explaination" => "question explaination 1779628854220060",
                "is_multichoice" => 1,
                "is_submit" => false,
                "user_answers" => [],
                "options" => [
                    [
                        "id" => 1,
                        "text" => "option 2 true fake in base test",
                        "is_correct" => 1,
                    ],
                    [
                        "id" => 2,
                        "text" => "Option 3",
                        "is_correct" => 0,
                    ],
                    [
                        "id" => 5,
                        "text" => "option 5 true",
                        "is_correct" => 1,
                    ],
                    [
                        "id" => 3,
                        "text" => "Option 1",
                        "is_correct" => 0,
                    ],
                    [
                        "id" => 4,
                        "text" => "Option 2",
                        "is_correct" => 0,
                    ]
                ]
            ]
        ];
    }
}
