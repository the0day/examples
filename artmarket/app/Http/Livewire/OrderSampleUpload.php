<?php

namespace App\Http\Livewire;

class OrderSampleUpload extends UploadComponent
{
    public $photo;
    public $files = [];

    protected $rules = [
        'w'       => 'required',
        'files.*' => 'required'
    ];


    public function render()
    {
        return view('livewire.order-sample-upload');
    }

    public function updatedFiles()
    {
        $this->validate([
            'photo' => 'image|max:1024',
        ]);
    }

    public function save()
    {
        $this->validate([
            'photos.*' => 'image|max:1024'
        ]);

        foreach ($this->photos as $photo) {
            $photo->store('photos');
        }
    }

    public function finishUpload($name, $tmpPath, $isMultiple)
    {

        $this->validate([
            'files.*' => 'required|image|min:1024'
        ]);

        parent::finishUpload($name, $tmpPath, $isMultiple);
    }
}
