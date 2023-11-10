<?php

namespace Tests\Unit\Exam;

use App\Enums\DbStatus;
use App\Helper\QuestionHelper;
use App\Livewire\Exam\Upsert;
use App\Models\Option;
use App\Models\Question;
use Livewire\Livewire;
use Tests\Unit\BaseTestcase;

class UpsertTest extends BaseTestcase
{
    public function test_upsert_exam_create_question()
    {
        // create exam and 2 questions
        $this->createExamAndQuestion();

        // create another 2 questions
        $question3 = Question::factory([
            'text' => 'question 3 text',
            'explaination' => '333',
        ])->make();
        $option31 = Option::factory([
            'text' => 'option31',
            'is_correct' => 0,
        ])->make();
        $option32 = Option::factory([
            'text' => 'option32',
            'is_correct' => 0,
        ])->make();
        $option33 = Option::factory([
            'text' => 'option33',
            'is_correct' => 1,
        ])->make();

        $question4 = Question::factory([
            'text' => 'question 4 text',
            'explaination' => '444',
        ])->make();
        $option41 = Option::factory([
            'text' => 'option41',
            'is_correct' => 0,
        ])->make();
        $option42 = Option::factory([
            'text' => 'option42',
            'is_correct' => 0,
        ])->make();
        $option43 = Option::factory([
            'text' => 'option43',
            'is_correct' => 0,
        ])->make();
        $option44 = Option::factory([
            'text' => 'option44',
            'is_correct' => 1,
        ])->make();
        $option45 = Option::factory([
            'text' => 'option45',
            'is_correct' => 1,
        ])->make();

        $fakeQuestion = QuestionHelper::transformQuestions([
                $this->question1,
                $this->question2,
                $question3,
                $question4,
        ]);

        $fakeQuestion[2]['db_status'] = $fakeQuestion[3]['db_status'] = DbStatus::CREATE;
        $fakeQuestion[2]['options'] = [
            $option31->toArray(),
            $option32->toArray(),
            $option33->toArray(),
        ];
        $fakeQuestion[3]['options'] = [
            $option41->toArray(),
            $option42->toArray(),
            $option43->toArray(),
            $option44->toArray(),
            $option45->toArray(),
        ];
        // dd($fakeQuestion);

        Livewire::test(Upsert::class, [
            'exam' => $this->exam
        ])
            ->set('questions', $fakeQuestion)
            ->call('saveExam');

        $this->assertDatabaseHas(
            'questions',
            [
                'text' => $question3->text,
                'explaination' => $question3->explaination,
                'is_multichoice' => 0,
            ],
        );
        $this->assertDatabaseHas(
            'questions',
            [
                'text' => $question4->text,
                'explaination' => $question4->explaination,
                'is_multichoice' => 1,
            ],
        );
    }
}
