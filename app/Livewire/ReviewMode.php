<?php

namespace App\Livewire;

use App\Models\Exam;
use Livewire\Component;

class ReviewMode extends Component
{
    public $exam;
    public $questions;
    public $options;

    public $currentQuestion;
    public $selectedOptions;
    public $isCorrectAnswer;

    public $isShowExplaination;

    public function mount(
        $exam,
    ) {
        $this->exam = Exam::where('uuid', $exam)->first();
        $this->questions = $this->exam->questions;
        $this->currentQuestion = $this->questions->values()->get(0);
        $this->options = $this->currentQuestion->options;
    }

    public function render()
    {
        return view('livewire.review-mode')
            ->extends('layouts.master')
            ->section('content');
    }

    public function checkAnswer()
    {
        $this->isShowExplaination = true;
        $this->isCorrectAnswer = $this->checkCorrectAnswer();
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
