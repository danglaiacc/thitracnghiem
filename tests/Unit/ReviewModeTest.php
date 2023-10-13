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
    public $correctAnswerMessage = 'Corrected answers 😁😁',
        $incorrectAnswerMessage = 'Incorrect answers 😢😢';

    public function test_select_incorrect_answer_single_choice()
    {
        $this->createExamAndQuestion();
        Livewire::test(ReviewMode::class, [
            'exam' => $this->exam->uuid
        ])->set('questions', $this->getQuestionRandom())
            ->set('selectedOptions', "7")
            ->call('submitAnswer')
            ->assertSee($this->incorrectAnswerMessage);
    }

    public function test_select_correct_answer_single_choice()
    {
        $this->createExamAndQuestion();
        Livewire::test(ReviewMode::class, [
            'exam' => $this->exam->uuid
        ])->set('questions', $this->getQuestionRandom())
            ->set('selectedOptions', "6")
            ->call('submitAnswer')
            ->assertSee($this->correctAnswerMessage);
    }

    public function test_select_incorrect_answer_multi_choice()
    {
        $this->createExamAndQuestion();
        Livewire::test(ReviewMode::class, [
            'exam' => $this->exam->uuid
        ])->set('questions', $this->getQuestionRandom())
            ->call('loadQuestion', 1)
            ->set('selectedOptions', ["2", "3"])
            ->call('submitAnswer')
            ->assertSee($this->incorrectAnswerMessage);
    }

    public function test_select_incorrect_answer_multi_choice_dont_have_any_option()
    {
        $this->createExamAndQuestion();
        Livewire::test(ReviewMode::class, [
            'exam' => $this->exam->uuid
        ])->set('questions', $this->getQuestionRandom())
            ->call('loadQuestion', 1)
            ->set('selectedOptions', [])
            ->call('submitAnswer')
            ->assertSee($this->incorrectAnswerMessage);
    }

    public function test_select_incorrect_answer_multi_choice_select_all_options()
    {
        $this->createExamAndQuestion();
        Livewire::test(ReviewMode::class, [
            'exam' => $this->exam->uuid
        ])->set('questions', $this->getQuestionRandom())
            ->call('loadQuestion', 1)
            ->set('selectedOptions', ["1", "5", "2", "3", "4"])
            ->call('submitAnswer')
            ->assertSee($this->incorrectAnswerMessage);
    }

    public function test_select_correct_answer_multi_choice()
    {
        $this->createExamAndQuestion();
        Livewire::test(ReviewMode::class, [
            'exam' => $this->exam->uuid
        ])->set('questions', $this->getQuestionRandom())
            ->call('loadQuestion', 1)
            ->set('selectedOptions', ["1", "5"])
            ->call('submitAnswer')
            ->assertSee($this->correctAnswerMessage);
    }
}
