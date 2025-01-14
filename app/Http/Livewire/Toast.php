<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Toast extends Component
{
    public $message = '';
    public $type = 'info';
    public $duration = 3000;

    protected $listeners = ['showToast', 'hideToast'];

    public function showToast($message, $type = 'info', $duration = 3000)
    {
        $this->message = $message;
        $this->type = $type;
        $this->duration = $duration;

        $this->emit('toast-shown', $this->duration);
    }

    public function hideToast()
    {
        $this->message = '';
        $this->type = 'info';
    }

    public function render()
    {
        return view('livewire.toast');
    }


    private function getTypeClasses()
    {
        return [
                'info' => 'bg-blue-500',
                'success' => 'bg-green-500',
                'warning' => 'bg-yellow-500',
                'error' => 'bg-red-500',
            ][$this->type] ?? 'bg-blue-500';
    }

    private function getTypeIcon()
    {
        return [
                'info' => '<svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
                'success' => '<svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
                'warning' => '<svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>',
                'error' => '<svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>',
            ][$this->type] ?? '';
    }

}
