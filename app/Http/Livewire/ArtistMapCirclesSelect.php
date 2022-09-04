<?php

namespace App\Http\Livewire;

use App\Models\Artist;
use App\Models\ArtistMapCircle;
use Livewire\Component;

class ArtistMapCirclesSelect extends Component
{
    public $circle_id;
    public $artists;
    public $selected;
    public $budget;

    public function mount()
    {
        $this->artists = Artist::pluck('name', 'id')->toArray();
        $this->selected = ArtistMapCircle::where('map_circle_id', $this->circle_id)
            ->get()
            ->pluck('artist_id');

        $this->budget = optional(ArtistMapCircle::where('map_circle_id', $this->circle_id)->first())->budget;
    }

    public function update($element)
    {
        $artistId = $element['value'];
        
        if (!in_array($element['value'], $this->selected)) {
            ArtistMapCircle::where('map_circle_id', $this->circle_id)
                ->where('artist_id', $artistId)
                ->delete();
        } else {
            ArtistMapCircle::create([
                'map_circle_id' => $this->circle_id,
                'artist_id' => $artistId,
                'budget' => $this->budget,
            ]);
        }
    }

    public function updateBudget()
    {
        $record = ArtistMapCircle::where('map_circle_id', $this->circle_id)
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
