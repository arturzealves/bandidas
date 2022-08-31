<x-app-layout>
    {{-- <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot> --}}

    <div class="p-0">
        <div id="map" 
            class="min-h-screen"
            style="width: 100%;"
        ></div>

        @livewire('map-markers')

        @push('scripts')
            <script
                src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCI9uuf8dWyp4voEziXgaXnWveoNUctCTU&callback=initMap&v=weekly&libraries=drawing"
                defer
            ></script>
        @endpush

    </div>
</x-app-layout>
