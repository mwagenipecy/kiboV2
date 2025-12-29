<?php

namespace App\Livewire\Components;

use Livewire\Component;

class Modal extends Component
{
    public $show = false;
    public $title = '';
    public $size = 'lg'; // sm, md, lg, xl, full

    protected $listeners = ['openModal', 'closeModal'];

    public function openModal($title = '', $size = 'lg')
    {
        $this->show = true;
        $this->title = $title;
        $this->size = $size;
    }

    public function closeModal()
    {
        $this->show = false;
        $this->dispatch('modalClosed');
    }

    public function render()
    {
        return view('livewire.components.modal');
    }
}
