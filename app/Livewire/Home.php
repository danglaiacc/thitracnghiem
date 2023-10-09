<?php

namespace App\Livewire;

use App\Models\Exam;
use Livewire\Component;

class Home extends Component
{
    public $exams;

    public function render()
    {
        $this->exams = Exam::select(['uuid', 'name', 'thumbnail'])->get();
        return view('livewire.home')
            ->extends('layouts.master')
            ->section('content');
    }
}
