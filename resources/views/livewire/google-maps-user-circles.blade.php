<div>
    <script type="text/javascript">
        window.addEventListener('load', function () {
            // console.log('loaded');

            window.GoogleMaps
                .setDrawingModes([google.maps.drawing.OverlayType.CIRCLE])
                .setUser(@js($user))
                .setUserLocation(@js($userLocation))
                .drawCircles(@js($circleLocations))
                .drawMarkers(@js($markerLocations));
        });
    </script>

    <div id="sideDrawer" class="{{ $isCircleSelected ? 'active' : '' }}">
        <form wire:submit.prevent="submit">
            @csrf
            name:
            <input type="text" wire:model.lazy="name" id="name">
            <br>
            circle_id:
            <input type="text" wire:model.lazy="circle_id" id="circle_id">
            <br>
            latitude:
            <input type="text" wire:model.lazy="latitude" id="latitude">
            <br>
            longitude:
            <input type="text" wire:model.lazy="longitude" id="longitude">
            <br>
            radius:
            <input type="text" wire:model.lazy="radius" id="radius">
            <br>

            Artists:
            <div>
                <livewire:google-maps-user-circles-has-artists-select 
                    wire:model.lazy="artists"
                    circle_id="{{ $circle_id }}"
                    key="{{ $circle_id }}" 
                    budget="{{ $selectedCircleBudget }}"
                />
            </div>

            <br>
            <button type="submit" id="google-maps-user-circle-form-submit" class="px-4 py-2 mt-4 text-white bg-blue-600 rounded">
                Submit
            </button>
            <button type="submit" id="google-maps-user-circle-form-update" wire:click="update" class="px-4 py-2 mt-4 text-white bg-blue-600 rounded">
                Update
            </button>

        </form>
        <br>
        <br>
        <div wire:loading>
            Processing request...
        </div>
    </div>
    
</div>
