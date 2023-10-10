<?php

namespace App\Livewire;

use App\Helper\FileHelper;
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

    public int $totalQuestion, $totalCorrectAnswer = 0;
    public string $resultMessage;

    public function mount(
        $exam,
    ) {
        $this->exam = Exam::where('uuid', $exam)->first();

        // shuffle
        if ($this->exam->allow_shuffle) {
            $this->questions = $this->exam->questions->shuffle();
        } else {
            $this->questions = $this->exam->questions;
        }
        $this->totalQuestion = count($this->questions);

        $this->currentIndexQuestion = 0;
        $this->loadQuestion();
    }

    public function finishExam()
    {
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
        $questionId = $this->currentQuestion->id;
        $params = [
            'exam_id' => 100,
            'question_id' => $questionId,
        ];

        $existedExamQuestion = ExamQuestion::where($params)->first();
        if (is_null($existedExamQuestion)) {
            $examQuestion = new ExamQuestion($params);
            FileHelper::write2File(
                "INSERT INTO exam_questions (exam_id, question_id) values (100, $questionId);\n",
            );
            $examQuestion->save();
            session()->flash('background', 'success');
            session()->flash('message', 'OK');
        }
        else {
            session()->flash('background', 'warning');
            session()->flash('message', 'Question has been exsited.');
            error_log('this question has been exsited in exam');
        }
    }

    public function submitAnswer()
    {
        $this->isShowExplaination = true;
        $this->isCorrectAnswer = $this->checkCorrectAnswer();

        if ($this->isCorrectAnswer)
        {
            $this->totalCorrectAnswer ++;
        }
    }

    public function loadQuestion()
    {
        $this->currentQuestion = $this->questions->values()->get($this->currentIndexQuestion);

        // shuffle code, do not combine these lines
        if ($this->exam->allow_shuffle) {
            error_log('enter to shuffle function');
            $this->options = $this->currentQuestion->options->shuffle();
        } else {
            $this->options = $this->currentQuestion->options;
        }

        $this->isShowExplaination = false;
        $this->selectedOptions = [];
    }

    public function previousQuestion()
    {
        if ($this->currentIndexQuestion != 0) {
            $this->currentIndexQuestion--;
            $this->loadQuestion();
        }
    }

    public function nextQuestion()
    {
        $this->currentIndexQuestion++;
        $this->loadQuestion();
    }

    private function checkCorrectAnswer()
    {
        $this->selectedOptions = is_string($this->selectedOptions) ? [(int)$this->selectedOptions] : $this->selectedOptions;

        $options = clone $this->options;
        $options = $options->pluck(null, 'id')->all();

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
