<?php

namespace App\Http\Livewire;

use App\Models\Artist;
use App\Models\ArtistMapCircle;
use Livewire\Component;

class ArtistMapCirclesSelect extends Component
{
    public $circle_uuid;
    public $artists;
    public $selected;
    public $budget;

    public function mount()
    {
        $this->artists = Artist::pluck('name', 'uuid')->toArray();
        $this->selected = ArtistMapCircle::where('map_circle_uuid', $this->circle_uuid)
            ->get()
            ->pluck('artist_uuid');

        $this->budget = optional(ArtistMapCircle::where('map_circle_uuid', $this->circle_uuid)->first())->budget;
    }

    public function update($element)
    {
        $artistId = $element['value'];
        
        if (!in_array($element['value'], $this->selected)) {
            ArtistMapCircle::where('map_circle_uuid', $this->circle_uuid)
                ->where('artist_uuid', $artistId)
                ->delete();
        } else {
            ArtistMapCircle::create([
                'map_circle_uuid' => $this->circle_uuid,
                'artist_uuid' => $artistId,
                'budget' => $this->budget,
            ]);
        }
    }

    public function updateBudget()
    {
        $record = ArtistMapCircle::where('map_circle_uuid', $this->circle_uuid)
            ->first();

        if (null !== $record) {
            $record->budget = $this->budget;
            $record->save();
        }
    }
    
    public function render()
    {
        return view('livewire.artist-map-circles-select');
    }
}
