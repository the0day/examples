<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;

class ImageUpload extends Component
{
    use WithFileUploads;

    public $photo;

    public function render()
    {
        return view('livewire.image-upload');
    }

    public function updatedFiles()
    {
        // You can do whatever you want to do with $this->files here
    }

    public function save()
    {

        $this->photo->store('photos');
    }
}
