<?php

namespace App\Livewire;

use Illuminate\View\View;
use Livewire\Component;

class Welcome extends Component
{
    public function render(): view
    {
        return view('livewire.welcome');
    }
}
