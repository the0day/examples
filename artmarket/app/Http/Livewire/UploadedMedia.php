<?php

namespace App\Http\Livewire;

use App\Models\Media;
use Arr;
use Auth;
use Livewire\Component;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;

class UploadedMedia extends Component
{
    public MediaCollection $items;

    public function mount(MediaCollection $items)
    {
        $this->items = $items;
    }

    public function render()
    {
        return view('livewire.uploaded-media', [
            'items' => $this->items
        ]);
    }

    public function remove(string $uuid)
    {
        $media = $this->items->where('uuid', '=', $uuid)->first();

        if (!$media) {
            return;
        }

        if ($media->user_id != Auth::user()->id) {
            abort(404);
        }

        $media->delete();
        $this->items = $this->items->fresh();
        $q1 = 2;
    }

    public function order(array $data)
    {
        $dataIds = Arr::pluck($data, 'value');

        $ids = $this->items
            ->whereIn('id', $dataIds)
            ->pluck('id');

        if (count($dataIds) != count($ids)) {
            return;
        }

        Media::setNewOrder($dataIds);
    }
}
