<div>
    <script type="text/javascript">
        window.addEventListener('load', function () {
            // console.log('loaded');

            window.GoogleMaps
                .setUser(@js($user))
                .setUserLocation(@js($userLocation))
                .drawCircles(@js($locations));
        });
    </script>

    <form wire:submit.prevent="submit">
        @csrf
            <input type="hidden" wire:model.lazy="circle_id" id="circle_id">
            <input type="hidden" wire:model.lazy="latitude" id="latitude">
            <input type="hidden" wire:model.lazy="longitude" id="longitude">
            <input type="hidden" wire:model.lazy="radius" id="radius">
            <button type="submit" id="google-maps-user-circle-form-submit" class="px-4 py-2 mt-4 text-white bg-blue-600 rounded">
                Submit
            </button>
            <button type="submit" id="google-maps-user-circle-form-update" wire:click="update" class="px-4 py-2 mt-4 text-white bg-blue-600 rounded">
                Update
            </button>
    </form>

    <div id="sideDrawer">
        {{-- <input type="text" wire:model.lazy="name" id="name">
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
        </button> --}}
        <br>
        <br>
        <div wire:loading>
            Processing request...
        </div>
    </div>
    
</div>
