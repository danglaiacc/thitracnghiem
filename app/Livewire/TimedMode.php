<?php

namespace App\Livewire;

use Livewire\Component;

class TimedMode extends Component
{
    public function render()
    {
        return view('livewire.timed-mode')
            ->extends('layouts.master')
            ->section('content');
    }
}
