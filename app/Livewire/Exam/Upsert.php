<?php

namespace App\Livewire\Exam;

use App\Models\Exam;
use App\Models\Option;
use App\Models\Question;
use Livewire\Component;

class Upsert extends Component
{
    public Exam $exam;
    public $questions;

    private $changeQuestions, $changeOptions, $removedQuestionUuid;

    protected $rules = [
        'questions.*.text' => 'required|string',
        'questions.*.explaination' => 'required|string',
        'questions.*.options.*.text' => 'required|string',
        'questions.*.options.*.is_correct' => 'nullable',
    ];

    public function mount()
    {
        $this->questions = $this->exam->questions;
        $this->changeQuestions = [];
        $this->changeOptions = [];
        $this->removedQuestionUuid = [];
    }

    public function addQuestion()
    {
        $this->questions[] = Question::factory([
            'text' => '',
            'explanation' => '',
            'exam_id' => $this->exam->id,
        ])->make();

        $newQuestionIndex = count($this->questions) - 1;
        $this->addOption($newQuestionIndex);
    }

    public function saveExam()
    {
    }

    public function addOption($questionIndex)
    {
        $this->questions[$questionIndex]['options'][] = Option::factory([
            'text' => '',
            'is_correct' => false,
        ])->make();
    }

    public function removeQuestion(string $questionUuid, int $questionIndex)
    {
        $this->removedQuestionUuid[] = $questionUuid;
        unset($this->questions[$questionIndex]);
    }

    public function render()
    {
        return view('livewire.exam.upsert');
    }
}
