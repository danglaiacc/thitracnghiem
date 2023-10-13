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
    public $currentQuestionIndex;
    public $selectedOptions;
    public $isCorrectAnswer;

    public $isShowExplaination;

    public int $totalQuestion, $totalCorrectAnswer = 0;
    public string $resultMessage;

    public function mount(
        $exam,
    ) {
        $this->exam = Exam::with([
            'questions' => fn ($q) => $q->select(['questions.id', 'text', 'explaination', 'is_multichoice']),
            'questions.options' => fn ($q) => $q->select(['options.id', 'text', 'question_id', 'is_correct']),
        ])
            ->where('uuid', $exam)
            ->select(['id', 'name', 'thumbnail'])
            ->first();
        $this->shuffleQuestionAndAnswer();

        $this->totalQuestion = count($this->questions);
        $this->currentQuestionIndex = 0;
        $this->loadQuestion();
    }

    /**
     * [▼
        "id",
        "text",
        "explaination",
        "is_multichoice",
        "options" , // []
        "user_answers" , // string|array
     * ]
     */
    private function shuffleQuestionAndAnswer()
    {
        // transform question to array and shuffle it
        $arrayQuestionOptions = $this->exam->questions->toArray();
        shuffle($arrayQuestionOptions);

        // remove pivot key and shuffle options
        $this->questions = array_map(
            function ($question) {
                unset($question['pivot']);
                shuffle($question['options']);
                $question['user_answers'] = [];
                return $question;
            },
            $arrayQuestionOptions
        );
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
        } else {
            session()->flash('background', 'warning');
            session()->flash('message', 'Question has been exsited.');
            error_log('this question has been exsited in exam');
        }
    }

    private function saveUserAnswer()
    {
        // convert selected option id from string to int value
        $this->questions[$this->currentQuestionIndex]['user_answers'] = $this->selectedOptions;
    }

    public function submitAnswer()
    {
        $this->isShowExplaination = true;
        $this->isCorrectAnswer = $this->checkCorrectAnswer();
        $this->saveUserAnswer();

        if ($this->isCorrectAnswer) {
            $this->totalCorrectAnswer++;
        }
    }

    public function loadQuestion()
    {
        $this->currentQuestion = $this->questions[$this->currentQuestionIndex];
    }

    public function previousQuestion()
    {
        $this->saveUserAnswer();
        if ($this->currentQuestionIndex != 0) {
            $this->currentQuestionIndex--;
            $this->loadQuestion();
        }
    }

    public function nextQuestion()
    {
        $this->saveUserAnswer();
        $this->currentQuestionIndex++;
        $this->loadQuestion();
        $this->selectedOptions = $this->questions[$this->currentQuestionIndex]['user_answers'];
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
