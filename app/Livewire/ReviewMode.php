<?php

namespace App\Livewire;

use App\Models\Exam;
use App\Models\Question;
use Livewire\Component;

class ReviewMode extends Component
{
    public $exam;
    public $questions;

    public function mount(
        $exam,
    ){
        $this->exam = Exam::where('uuid', $exam)->first();
        $this->questions = $this->exam->questions;
        // dd($this->questions);
        // $questions = Question::where('exam_id', $)
    }

    public function render()
    {
        return view('livewire.review-mode')
            ->extends('layouts.master')
            ->section('content');
    }
}
