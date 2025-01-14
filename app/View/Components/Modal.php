<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Modal extends Component
{
    public $id;
    public $type;
    public $title;
    public $showSaveButton;

    public function __construct($id, $type = 'info', $title = '', $showSaveButton = false)
    {
        $this->id = $id;
        $this->type = $type;
        $this->title = $title;
        $this->showSaveButton = $showSaveButton;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.modal');
    }
}
