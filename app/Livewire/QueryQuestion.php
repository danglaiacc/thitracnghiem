<?php

namespace App\Livewire;

use App\Models\Question;
use Livewire\Component;

class QueryQuestion extends Component
{
    public $questionIds = '';
    public $questions = [];

    public function render()
    {
        return view('livewire.query-question');
    }

    public function submit()
    {
        $this->questions = Question::whereIn(
            'id',
            explode(',', $this->questionIds)
        )->select(['id', 'text', 'explanation'])
            ->with([
                'options:text,is_correct,question_id'
            ])
            ->get();

    }
}
