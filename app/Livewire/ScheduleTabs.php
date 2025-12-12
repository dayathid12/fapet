<?php

namespace App\Livewire;

use Livewire\Component;

class ScheduleTabs extends Component
{
    public $activeTab = 'kendaraan';

    public function render()
    {
        return view('livewire.schedule-tabs')
            ->layout('components.layouts.app');
    }
}
