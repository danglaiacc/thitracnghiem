<?php

namespace App\Livewire;

use App\Models\Exam;
use App\Models\ExamQuestion;
use Livewire\Component;

class ReviewMode extends Component
{
    public $exam;
    public $questions;
    public $options;

    public $currentQuestion;
    public $currentIndexQuestion;
    public $selectedOptions;
    public $isCorrectAnswer;

    public $isShowExplaination;

    public function mount(
        $exam,
    ) {
        $this->exam = Exam::where('uuid', $exam)->first();
        $this->questions = $this->exam->questions;

        // shuffle
        if ($this->exam->allow_shuffle) {
            $this->questions = $this->questions->shuffle();
        }

        $this->currentIndexQuestion = 0;
        $this->updatedCurrentIndexQuestion();
    }

    public function render()
    {
        return view('livewire.review-mode')
            ->extends('layouts.master')
            ->section('content');
    }

    /**
     * add question to hard exam
     */
    public function addToHardQuestion()
    {
        ExamQuestion::firstOrCreate([
            'exam_id' => 100,
            'question_id' => $this->currentQuestion->id,
        ]);
    }

    public function checkAnswer()
    {
        $this->isShowExplaination = true;
        $this->isCorrectAnswer = $this->checkCorrectAnswer();
    }

    public function updatedCurrentIndexQuestion()
    {
        $this->currentQuestion = $this->questions->values()->get($this->currentIndexQuestion);

        $this->options = $this->currentQuestion->options;
        $this->exam->allow_shuffle && $this->options = $this->options->shuffle();

        $this->isShowExplaination = false;
        $this->selectedOptions = [];
    }

    public function previousQuestion()
    {
        if ($this->currentIndexQuestion != 0) {
            $this->currentIndexQuestion--;
            $this->updatedCurrentIndexQuestion();
        }
    }

    public function nextQuestion()
    {
        $this->currentIndexQuestion++;
        $this->updatedCurrentIndexQuestion();
    }

    private function checkCorrectAnswer()
    {
        $this->selectedOptions = is_string($this->selectedOptions) ? [(int)$this->selectedOptions] : $this->selectedOptions;

        $options = $this->options->pluck(null, 'id')->all();

        // if select an item false
        foreach ($this->selectedOptions as $selectedOption) {
            if ($options[$selectedOption]->is_correct === 0)
                return false;
        }

        // not select true item
        foreach ($options as $optionId => $option) {
            if ($option->is_correct === 1 && !in_array($optionId, $this->selectedOptions)) {
                return false;
            }
        }

        return true;
    }
}
