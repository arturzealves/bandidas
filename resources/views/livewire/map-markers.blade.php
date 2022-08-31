<div>
    <script type="text/javascript">
        window.addEventListener('load', function () {

            window.GoogleMaps
                .setDrawingModes([google.maps.drawing.OverlayType.MARKER])
                .setUser(@js($user))
                .drawMarkers(@js($locations), 'red');
        });
    </script>

    <div id="sideDrawer">
        <form wire:submit.prevent="submit">
            @csrf
            latitude:
            <input type="text" wire:model.lazy="latitude" id="latitude">
            <br>
            longitude:
            <input type="text" wire:model.lazy="longitude" id="longitude">
            <br>
            <br>
            <button type="submit" id="map-markers-form-submit" class="px-4 py-2 mt-4 text-white bg-blue-600 rounded">
                Submit
            </button>
        </form>
        <br>
        <br>
        <div wire:loading>
            Processing request...
        </div>
    </div>
    
</div>
