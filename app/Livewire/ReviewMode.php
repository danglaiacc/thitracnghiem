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

    public $currentQuestion;
    public $currentQuestionIndex;
    public $selectedOptions = [];
    public $isCorrectAnswer;

    public $isShowExplaination;

    public int $totalQuestion, $totalCorrectAnswer = 0;
    public string $resultMessage;

    public bool $isReviewMode = true;

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
        $this->loadQuestion(0);
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
                $question['is_submit'] = false;
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

    /**
     * check current question is correct or not
     * save user_answer to question table
     */
    public function submitAnswer()
    {
        $this->isShowExplaination = true;
        $this->isCorrectAnswer = $this->checkCorrectAnswer();
        $this->questions[$this->currentQuestionIndex]['user_answers'] = $this->selectedOptions;
        $this->questions[$this->currentQuestionIndex]['is_submit'] = true;

        if ($this->isCorrectAnswer) {
            $this->totalCorrectAnswer++;
        }
    }

    /**
     * update new user answer to question
     * check question index is available
     * reload current question
     * reload user answer has been selected
     */
    public function loadQuestion(int $questionIndex)
    {
        $this->questions[$this->currentQuestionIndex]['user_answers'] = $this->selectedOptions;
        if (-1 < $questionIndex && $questionIndex < $this->totalQuestion) {
            $this->currentQuestionIndex = $questionIndex;
            $this->currentQuestion = $this->questions[$this->currentQuestionIndex];
            $this->isShowExplaination = $this->checkShowExpalination();
            $this->selectedOptions = $this->questions[$this->currentQuestionIndex]['user_answers'];
        }
    }

    public function checkShowExpalination()
    {
        return $this->isReviewMode && $this->currentQuestion['is_submit'];
    }

    private function checkCorrectAnswer()
    {
        $this->selectedOptions = is_string($this->selectedOptions) ? [(int)$this->selectedOptions] : $this->selectedOptions;

        foreach ($this->questions[$this->currentQuestionIndex]['options'] as $option) {
            if (
                // select in correct answer
                (in_array($option['id'], $this->selectedOptions) && $option['is_correct'] == 0) ||

                // not select correct answer
                (!in_array($option['id'], $this->selectedOptions) && $option['is_correct'] == 1)
            )
                return false;
        }
        return true;
    }
}
