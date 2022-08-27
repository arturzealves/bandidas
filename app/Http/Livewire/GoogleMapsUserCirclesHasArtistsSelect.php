<?php

namespace App\Http\Livewire;

use App\Models\Artist;
use App\Models\GoogleMapsUserCirclesHasArtist;
use Livewire\Component;

class GoogleMapsUserCirclesHasArtistsSelect extends Component
{
    public $circle_id;
    public $artists;
    public $selected;
    public $budget;

    public function mount()
    {
        $this->artists = Artist::pluck('name', 'id')->toArray();
        $this->selected = GoogleMapsUserCirclesHasArtist::where('google_maps_user_circle_id', $this->circle_id)
            ->get()
            ->pluck('artist_id');
    }

    public function update($element)
    {
        $artistId = $element['value'];
        
        if (!in_array($element['value'], $this->selected)) {
            GoogleMapsUserCirclesHasArtist::where('google_maps_user_circle_id', $this->circle_id)
                ->where('artist_id', $artistId)
                ->delete();
        } else {
            GoogleMapsUserCirclesHasArtist::create([
                'google_maps_user_circle_id' => $this->circle_id,
                'artist_id' => $artistId,
                'budget' => $this->budget,
            ]);
        }
    }

    public function updateBudget()
    {
        $record = GoogleMapsUserCirclesHasArtist::where('google_maps_user_circle_id', $this->circle_id)
            ->first();

        if (null !== $record) {
            $record->budget = $this->budget;
            $record->save();
        }
    }
    
    public function render()
    {
        return view('livewire.google-maps-user-circles-has-artists-select');
    }
}
