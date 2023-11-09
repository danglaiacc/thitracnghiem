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

    public Question $question1, $question2;
    public Option $option11,
        $option12,
        $option13,
        $option14,
        $option15,
        $option21,
        $option22,
        $option23,
        $option24;

    public function getRandomNumber()
    {
        return hexdec(uniqid());
    }

    public function createExamAndQuestion($allowShuffle = false)
    {
        $subject = Subject::factory()->create();
        $this->exam = Exam::factory([
            'subject_id' => $subject->id,
            'allow_shuffle' => $allowShuffle,
        ])->create();

        $this->question1 = Question::factory([
            'text' => 'question 1',
            'is_multichoice' => true,
        ])->create();

        $this->option11 = Option::factory([
            'text' => 'question 1 option 1 true',
            'is_correct' => true,
            'question_id' => $this->question1->id,
        ])->create();

        $this->option12 = Option::factory([
            'text' => 'question 1 option 2 false',
            'is_correct' => false,
            'question_id' => $this->question1->id,
        ])->create();

        $this->option13 = Option::factory([
            'text' => 'question 1 option 3 false',
            'is_correct' => false,
            'question_id' => $this->question1->id,
        ])->create();

        $this->option14 = Option::factory([
            'text' => 'question 1 option 4 true',
            'is_correct' => true,
            'question_id' => $this->question1->id,
        ])->create();

        $this->option15 = Option::factory([
            'text' => 'question 1 option 5 false',
            'is_correct' => false,
            'question_id' => $this->question1->id,
        ])->create();
        ExamQuestion::factory([
            'exam_id' => $this->exam->id,
            'question_id' => $this->question1->id,
        ])->create();

        $this->question2 = Question::factory([
            'text' => 'question 2, some thing content haha',
            'is_multichoice' => false,
        ])->create();

        $this->option21 = Option::factory([
            'text' => 'question 2 option 1 false',
            'is_correct' => false,
            'question_id' => $this->question2->id,
        ])->create();

        $this->option22 = Option::factory([
            'text' => 'question 2 option 2 true',
            'is_correct' => true,
            'question_id' => $this->question2->id,
        ])->create();

        $this->option23 = Option::factory([
            'text' => 'question 2 option 3 false',
            'is_correct' => false,
            'question_id' => $this->question2->id,
        ])->create();

        $this->option24 = Option::factory([
            'text' => 'question 2 option 4 false',
            'is_correct' => false,
            'question_id' => $this->question2->id,
        ])->create();
        ExamQuestion::factory([
            'exam_id' => $this->exam->id,
            'question_id' => $this->question2->id,
        ])->create();
    }
}
