<?php

namespace App\Livewire\Exam;

use App\Models\Exam;
use Livewire\Component;

class Upsert extends Component
{
    public Exam $exam;
    public $questions;

    protected $rules = [
        'questions.*.text' => 'required|string',
        'questions.*.options.*.text' => 'required|string',
    ];

    public function mount()
    {
        $this->questions = $this->exam->questions;
    }

    public function addQuestion()
    {
        $this->questions[] = [
            'title' => '',
            'options' => [],
        ];
    }

    public function addOption($questionIndex)
    {
        $this->questions[$questionIndex]['options'][] = [
            'text' => '',
            'is_correct' => false,
        ];
    }

    public function render()
    {
        return view('livewire.exam.upsert')
            ->extends('layouts.master')
            ->section('content');
    }
}
