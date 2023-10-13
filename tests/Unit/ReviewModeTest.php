<?php

namespace Tests\Unit;

use App\Livewire\ReviewMode;
use App\Models\Exam;
use App\Models\ExamQuestion;
use App\Models\Option;
use App\Models\Question;
use App\Models\Subject;
use Livewire\Livewire;

class ReviewModeTest extends BaseTestcase
{
    /**
     * A basic test example.
     */
    public function test_option_not_shuffle_after_submit_answer()
    {
        $subject = Subject::factory()->create();
        $exam = Exam::factory([
            'subject_id' => $subject->id,
            'allow_shuffle' => false,
        ])->create();

        $this->generateQuestionOption($exam->id, true);
        $this->generateQuestionOption($exam->id, false);

        // $shuffledOptions = $options->shuffle();

        // $component = Livewire::test(ReviewMode::class, ['exam' => $exam->uuid])
        //     ->set('options', $shuffledOptions)
        //     ->call('submitAnswer');

        // $component->assertSeeHtmlInOrder(
        //     $shuffledOptions->pluck('text')->toArray()
        // );
    }

    private function generateQuestionOption(int $examId, bool $isMultiChoice){
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
