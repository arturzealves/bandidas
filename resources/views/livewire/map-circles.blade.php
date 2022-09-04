<div>
    <script type="text/javascript">
        window.addEventListener('load', function () {
            // console.log('loaded');

            window.GoogleMaps
                .setDrawingModes([google.maps.drawing.OverlayType.CIRCLE])
                .setUser(@js($user))
                .setUserLocation(@js($userLocation))
                .drawCircles(@js($circleLocations))
                .drawMarkers(@js($markerLocations), 'red')
                .drawMarkers(@js($locationsInsideCircles), 'green');
        });
    </script>

    <div id="sideDrawer" class="drop-shadow-md">
        <form wire:submit.prevent="submit">
            @csrf
            <dl>
                <dt>Name</dt>
                <dd>
                    <input type="text" wire:model.lazy="name" id="name" class="w-full disabled:opacity-50" disabled>
                </dd>
                <dt>Circle ID</dt>
                <dd>
                    <input type="text" wire:model.lazy="circle_id" id="circle_id" class="w-full disabled:opacity-50" disabled>
                </dd>
                <dt>Latitude</dt>
                <dd>
                    <input type="text" wire:model.lazy="latitude" id="latitude" class="w-full disabled:opacity-50" disabled>
                </dd>
                <dt>Longitude</dt>
                <dd>
                    <input type="text" wire:model.lazy="longitude" id="longitude" class="w-full disabled:opacity-50" disabled>
                </dd>
                <dt>Radius</dt>
                <dd>
                    <input type="text" wire:model.lazy="radius" id="radius" class="w-full disabled:opacity-50" disabled>
                </dd>
                <dt>Budget</dt>
                <dd>
                    <input type="number" wire:model.lazy="budget" step="1" min="0" class="w-full"/>
                </dd>
            </dl>
            <hr>
            <div>
                <livewire:artist-map-circles-select 
                    wire:model.lazy="artists"
                    circle_id="{{ $circle_id }}"
                    key="{{ $circle_id }}" 
                    budget="{{ $selectedCircleBudget }}"
                />
            </div>
            <button type="submit" id="google-maps-user-circle-form-submit" class="px-4 py-2 mt-4 text-white bg-gray-600 rounded">
                Submit
            </button>
            <button type="submit" id="google-maps-user-circle-form-update" wire:click="update" class="px-4 py-2 mt-4 text-white bg-gray-600 rounded">
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
