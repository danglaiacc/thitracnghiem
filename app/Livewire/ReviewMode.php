<?php

namespace App\Livewire;

use App\Enums\ExamMode;
use App\Helper\ArrayHelper;
use App\Helper\FileHelper;
use App\Models\Exam;
use App\Models\ExamQuestion;
use App\Models\Option;
use App\Models\Question;
use App\Models\UserExam;
use Livewire\Component;

class ReviewMode extends Component
{
    public $exam;
    public $questions;

    public $currentQuestion;
    public $currentQuestionIndex;
    public $selectedOptions = [];

    public $isShowExplaination;

    public int $totalQuestion, $totalCorrectAnswer = 0;
    public string $resultMessage;

    // fake user id for user exam table
    public int $userId = 1;

    public bool $isReviewMode = true;
    public $userExam;

    public function mount(
        $exam,
    ) {
        $this->exam = Exam::where('uuid', $exam)->first();
        $this->userExam = UserExam::where([
            'user_id' => $this->userId,
            'exam_id' => $this->exam->id,
            'is_finish' => 0,
        ])->latest()->first();

        if (is_null($this->userExam)) {
            $this->userExam = new UserExam([
                'id' => PHP_INT_MAX,
            ]);
            $this->createNewExam(examUuid: $exam);
        } else {
            $this->loadQuestionFromExamHistory($this->userExam->record);
        }

        $this->totalQuestion = count($this->questions);
        $this->currentQuestionIndex = 0;
        $this->loadQuestion(0);
    }

    /**
     * from user_exams.record columns
     * get question_id, option_ids of each question
     * query to questions, options table to get text and other fields
     * mapping again for matching with questions format
     */
    private function loadQuestionFromExamHistory($record)
    {
        $record = json_decode($record, true);
        $questionIds = array_map(fn ($r) => $r['question_id'], $record);
        $optionIds = array_reduce(
            $record,
            fn ($record, $a) => array_merge($record, $a['option_ids']),
            []
        );

        $questions = Question::whereIn('id', $questionIds)->select(['id', 'text', 'explaination', 'is_multichoice'])->get();
        $options = Option::whereIn('id', $optionIds)->select(['id', 'text', 'is_correct'])->get();
        $keyIdQuestions = ArrayHelper::transformCollectionsWithIdAsKey($questions, 'id');
        $keyIdOptions = ArrayHelper::transformCollectionsWithIdAsKey($options, 'id');

        $this->questions = array_map(
            fn ($q) => [
                'id' => $q['question_id'],
                'text' => $keyIdQuestions[$q['question_id']]->text,
                'explaination' => $keyIdQuestions[$q['question_id']]->explaination,
                'is_multichoice' => $keyIdQuestions[$q['question_id']]->is_multichoice,
                'is_submit' => count($q['user_answers']) > 0,
                'is_review' => $q['is_review'] ?? false,
                'is_submit_correct' => !empty($q['user_answers']) && empty(array_diff(
                    $q['user_answers'],
                    array_filter($q['option_ids'], fn ($optionId) => $keyIdOptions[$optionId]->is_correct),
                )),
                'user_answers' => $q['user_answers'],
                'options' => array_map(fn ($optionId) => [
                    'id' => $keyIdOptions[$optionId]->id,
                    'text' => $keyIdOptions[$optionId]->text,
                    'is_correct' => $keyIdOptions[$optionId]->is_correct,
                ], $q['option_ids'])
            ],
            $record
        );
    }

    private function createNewExam($examUuid)
    {
        $this->exam = Exam::with([
            'questions:id,text,explaination,is_multichoice',
            'questions.options:id,text,question_id,is_correct',
        ])
            ->where('uuid', $examUuid)
            ->select(['id', 'name', 'thumbnail', 'time'])
            ->first();
        $this->shuffleQuestionAndAnswer();

        $this->saveExamResult();
    }

    /**
     * transform array question (question_id, options_ids, user_answers)
     * to store in user exam in db
     */
    private function transformQuestionToStoreInUserExam(array $questions)
    {
        return array_map(
            fn ($question) => [
                'question_id' => $question['id'],
                'option_ids' => array_map(fn ($option) => $option['id'], $question['options']),
                'user_answers' => $question['is_submit'] ? $question['user_answers'] : [],
                'is_review' => $question['is_review'] ?? false,
            ],
            $questions
        );
    }

    /**
     * [▼
        "id",
        "text",
        "explaination",
        "is_multichoice",
        "is_submit",
        "options" , // [
            'text',
            'is_correct',
            'id',
        ]
        "user_answers" , // string|array
     * ]
     * 
     * convert question from collections to array
     * shuffle question and options in each question
     * save question order in UserExam table
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
                $question['is_submit_correct'] = false;
                $question['is_review'] = false;
                return $question;
            },
            $arrayQuestionOptions
        );
    }

    public function setReview($isReview)
    {
        $this->questions[$this->currentQuestionIndex]['is_review'] = $isReview;
        $this->currentQuestion['is_review'] = $isReview;
        $this->saveExamResult();
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
        $questionId = $this->currentQuestion->id ?? $this->currentQuestion['id'];
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
        $isCorrectAnswer = $this->checkCorrectAnswer();
        $this->questions[$this->currentQuestionIndex]['is_submit_correct'] = $isCorrectAnswer;
        $this->questions[$this->currentQuestionIndex]['user_answers'] = $this->selectedOptions;
        $this->questions[$this->currentQuestionIndex]['is_submit'] = true;

        if ($isCorrectAnswer) {
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
        // validate question index
        if (0 > $questionIndex || $questionIndex >= $this->totalQuestion)
            return;

        $this->currentQuestionIndex = $questionIndex;
        $this->currentQuestion = $this->questions[$this->currentQuestionIndex];
        $this->isShowExplaination = $this->checkShowExpalination();

        // validate option from submit button (next/previous question)
        // or from history in user history
        if ($this->currentQuestion['is_submit']) {
            $this->selectedOptions = $this->currentQuestion['user_answers'];
        } else {
            $this->questions[$this->currentQuestionIndex]['user_answers'] = $this->selectedOptions;
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

    public function saveExamResult($isFinish = false)
    {
        // get use exam record and store to db
        $userExamRecord = json_encode($this->transformQuestionToStoreInUserExam(
            $this->questions,
        ));

        $totalScore = 0;
        if ($isFinish) {
            $totalScore = $this->totalCorrectAnswer / $this->totalQuestion;
        }

        $this->userExam = $this->userExam->updateOrCreate([
            'id' => $this->userExam->id,
        ], [
            'user_id' => $this->userId,
            'exam_id' => $this->exam->id,
            'exam_mode' => ExamMode::REVIEW_MODE,
            'score' => $totalScore,
            'time_remain' => $this->exam->time,
            'is_finish' => $isFinish,
            'record' => $userExamRecord,
        ]);
    }
}
