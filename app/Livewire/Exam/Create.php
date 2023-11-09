<?php

namespace App\Livewire\Exam;

use Livewire\Component;

class Create extends Component
{
    public function render()
    {
        return view('livewire.exam.create')
            ->extends('layouts.master')
            ->section('content');
    }
}
