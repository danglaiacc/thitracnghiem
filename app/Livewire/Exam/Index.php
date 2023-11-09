<?php

namespace App\Livewire\Exam;

use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        return view('livewire.exam.index')
            ->extends('layouts.master')
            ->section('content');
    }
}
