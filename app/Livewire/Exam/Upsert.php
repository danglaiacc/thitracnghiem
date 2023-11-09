<?php

namespace App\Livewire\Exam;

use Livewire\Component;

class Upsert extends Component
{
    public function render()
    {
        return view('livewire.exam.upsert')
            ->extends('layouts.master')
            ->section('content');
    }
}
