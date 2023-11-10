<?php

namespace App\Helper;

use App\Enums\DbStatus;

class QuestionHelper
{
    public static function transformQuestions($questions)
    {
        $transformedQuestions = [];
        foreach ($questions as $question) {

            $options = [];
            foreach ($question->options as $option) {
                $options[] = [
                    'text' => $option->text,
                    'id' => $option->id,
                    'question_id' => $option->question_id,
                    'is_correct' => $option->is_correct,
                ];
            }
            $transformedQuestions[] = [
                'id' => $question->id,
                'uuid' => $question->uuid,
                'text' => $question->text,
                'explaination' => $question->explaination,
                'is_multichoice' => $question->is_multichoice,
                'exam_id' => $question->exam_id,
                'options' => $options,
                'db_status' => DbStatus::NO_CHANGE,
            ];
        }
        return $transformedQuestions;
    }
}
