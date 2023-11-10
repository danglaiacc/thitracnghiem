<?php

namespace App\Livewire\Exam;

use App\Enums\DbStatus;
use App\Helper\QuestionHelper;
use App\Models\Exam;
use App\Models\ExamQuestion;
use App\Models\Option;
use App\Models\Question;
use Livewire\Component;
use Illuminate\Support\Str;

class Upsert extends Component
{
    public Exam $exam;
    public $questions, $originalQuestions, $removeQuestionUuids;

    protected $rules = [
        'questions.*.text' => 'required|string',
        'questions.*.explaination' => 'required|string',
        'questions.*.options.*.text' => 'required|string',
        'questions.*.options.*.is_correct' => 'nullable',
    ];

    public function mount()
    {
        $this->questions = $this->originalQuestions = QuestionHelper::transformQuestions($this->exam->questions);
        $this->removeQuestionUuids = [];
    }

    public function addQuestionClick()
    {
        $this->questions[] = [
            'text' => '',
            'explaination' => '',
            'exam_id' => $this->exam->id,
            'db_status' => DbStatus::CREATE,
            'uuid' => 'uuid',
        ];

        $newQuestionIndex = count($this->questions) - 1;
        $this->addOptionClick($newQuestionIndex);
    }

    public function addOptionClick($questionIndex)
    {
        $this->questions[$questionIndex]['options'][] = [
            'text' => '',
            'is_correct' => false,
            'db_status' => DbStatus::CREATE,
        ];
    }

    public function removeQuestionClick(string $questionUuid, int $questionIndex)
    {
        $this->removeQuestionUuids[] = $questionUuid;
        $this->questions[$questionIndex]['db_status'] = DbStatus::DELETE;
    }

    public function render()
    {
        return view('livewire.exam.upsert');
    }

    /**
     * compare 
     */
    public function saveExam()
    {
        $numberDeleteQuestion = count($this->removeQuestionUuids);
        $numberAddQuestion = $numberUpdateQuestion = 0;
        foreach ($this->questions as $questionIndex => $question) {
            if ($question['db_status'] == DbStatus::CREATE) {
                $numberAddQuestion++;
                $this->insertQuestion($question);
                continue;
            }
            $originalQuestion = $this->originalQuestions[$questionIndex];
            $question['text'] = trim($question['text']);
            $question['explaination'] = trim($question['explaination']);

            if (
                $question['text'] != $originalQuestion['text'] ||
                $question['explaination'] != $originalQuestion['explaination']
            ) {
                $numberUpdateQuestion++;
                Question::where('id', $question['id'])
                    ->update([
                        'text' => $question['text'],
                        'explaination' => $question['explaination'],
                    ]);
            }

            foreach ($question['options'] as $optionIndex => $option) {
                $option['text'] = trim($option['text']);
                $originalOption = $originalQuestion['options'][$optionIndex];
                if (
                    $option['text'] != $originalOption['text'] ||
                    $option['is_correct'] != $originalOption['is_correct']
                ){
                    Option::where('id', $originalOption['id'])
                        ->update([
                            'text' => $option['text'],
                            'is_correct' => $option['is_correct'],
                        ]);
                }
            }
        }

        if ($numberDeleteQuestion > 0)
            Question::whereIn('uuid', $this->removeQuestionUuids)->delete();

        session()->flash('updateExamMessage', "Add $numberAddQuestion question, update $numberUpdateQuestion question, delete=$numberDeleteQuestion");
    }

    private function insertQuestion($question)
    {
        $numberCorrectAnswer = count(
            array_filter($question['options'], fn ($option) => $option['is_correct'] == 1)
        );
        $createdQuestion = Question::create([
            'uuid' => Str::uuid(),
            'text' => trim($question['text']),
            'is_multichoice' => $numberCorrectAnswer > 1,
            'explaination' => trim($question['explaination']),
        ]);
        ExamQuestion::create([
            'exam_id' => $this->exam->id,
            'question_id' => $createdQuestion->id,
        ]);

        foreach ($question['options'] as $option) {
            Option::create([
                'text' => trim($option['text']),
                'is_correct' => $option['is_correct'],
                'question_id' => $createdQuestion->id,
            ]);
        }
    }
}
