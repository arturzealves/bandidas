<div>
    <script type="text/javascript">
        window.addEventListener('load', function () {
            // console.log('loaded');
            let locations = @js($locations);

            window.GoogleMaps.drawCircles(locations);
        });
    </script>

    <form wire:submit.prevent="submit">
        @csrf
        <div>
            {{-- <input type="hidden" wire:model.lazy="circle_id" id="circle_id">
            <input type="hidden" wire:model.lazy="latitude" id="latitude">
            <input type="hidden" wire:model.lazy="longitude" id="longitude">
            <input type="hidden" wire:model.lazy="radius" id="radius"> --}}
        </div>
        
    </form>

    <div id="sideDrawer" class="active">
        
        <input type="text" wire:model.lazy="name" id="name">
        <input type="text" wire:model.lazy="circle_id" id="circle_id">
        <input type="text" wire:model.lazy="latitude" id="latitude">
        <input type="text" wire:model.lazy="longitude" id="longitude">
        <input type="text" wire:model.lazy="radius" id="radius">

        <br>
        <button type="submit" id="google-maps-user-circle-form-submit" class="px-4 py-2 mt-4 text-white bg-blue-600 rounded">
            Submit
        </button>
        <button type="submit" id="google-maps-user-circle-form-update" wire:click="update" class="px-4 py-2 mt-4 text-white bg-blue-600 rounded">
            Update
        </button>
        <br>
        <br>
        <div wire:loading>
            Processing request...
        </div>
    </div>
    
</div>