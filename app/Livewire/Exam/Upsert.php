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
        'questions.*.explanation' => 'required|string',
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
            'explanation' => '',
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

        if ($numberDeleteQuestion > 0)
            Question::whereIn('uuid', $this->removeQuestionUuids)->delete();

        $fileContent = [];
        foreach ($this->questions as $questionIndex => $question) {
            $question['text'] = trim($question['text']);
            $question['explanation'] = trim($question['explanation']);

            if ($question['db_status'] == DbStatus::CREATE) {
                $numberAddQuestion++;
                $this->insertQuestion($question);
                continue;
            }
            $originalQuestion = $this->originalQuestions[$questionIndex];

            if (
                $question['text'] != $originalQuestion['text'] ||
                $question['explanation'] != $originalQuestion['explanation']
            ) {
                $numberUpdateQuestion++;
                Question::where('id', $question['id'])
                    ->update([
                        'text' => $question['text'],
                        'explanation' => $question['explanation'],
                    ]);
            }

            $numberCorrectAnswer = 0;
            $fileContentAnswers = [];
            $correctAnswers = [];
            foreach ($question['options'] as $optionIndex => $option) {
                $option['text'] = trim($option['text']);
                $fileContentAnswers[] = $option['text'];

                if ($option['is_correct']) {
                    $numberCorrectAnswer++;
                    $correctAnswers[] = chr(97 + $optionIndex);
                }

                // insert option
                if ($option['db_status'] == DbStatus::CREATE) {
                    Option::create([
                        'text' => $option['text'],
                        'is_correct' => $option['is_correct'],
                        'question_id' => $question['id'],
                    ]);
                    continue;
                }

                // update option
                $originalOption = $originalQuestion['options'][$optionIndex];
                if (
                    $option['text'] != $originalOption['text'] ||
                    $option['is_correct'] != $originalOption['is_correct']
                ) {
                    Option::where('id', $originalOption['id'])
                        ->update([
                            'text' => $option['text'],
                            'is_correct' => $option['is_correct'],
                        ]);
                }
            }

            if ($numberCorrectAnswer > 1 && $question['is_multichoice'] == 0) {
                Question::where('id', $question['id'])->update(['is_multichoice' => 1]);
            }
            if ($numberCorrectAnswer == 1 && $question['is_multichoice'] == 1) {
                Question::where('id', $question['id'])->update(['is_multichoice' => 0]);
            }

            $fileContent[] = [
                'prompt' => [
                    'question' => $question['text'],
                    'explanation' => $question['explanation'],
                    'answers' => $fileContentAnswers,
                ],
                'correct_response' => $correctAnswers,
            ];
        }

        session()->flash('updateExamMessage', "Add $numberAddQuestion question, update $numberUpdateQuestion question, delete=$numberDeleteQuestion");

        $filePath = '/Users/lai/Desktop/thi-trac-nghiem/raw-data/lai-aws-dea.data';
        // write to file
        file_put_contents($filePath, hexdec(uniqid()) . PHP_EOL . '~~~' . PHP_EOL . json_encode([
            'results' => $fileContent,
        ]));
    }

    private function insertQuestion($question)
    {
        $numberCorrectAnswer = count(
            array_filter($question['options'], fn ($option) => $option['is_correct'] == 1)
        );
        $createdQuestion = Question::create([
            'uuid' => Str::uuid(),
            'text' => $question['text'],
            'is_multichoice' => $numberCorrectAnswer > 1,
            'explanation' => $question['explanation'],
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
