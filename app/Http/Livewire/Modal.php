<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Modal extends Component
{
    public $show = false;
    public $title;
    public $content;

    protected $listeners = ['showModal', 'hideModal'];

    public function showModal($title, $content)
    {
        $this->show = true;
        $this->title = $title;
        $this->content = $content;
    }

    public function hideModal()
    {
        $this->show = false;
    }

    public function render()
    {
        return view('livewire.modal');
    }
}
