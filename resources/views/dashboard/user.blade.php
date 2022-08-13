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

        @livewire('google-maps-user-circles')
        @livewire('user-location-form')

        @push('scripts')
            <script
                src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCI9uuf8dWyp4voEziXgaXnWveoNUctCTU&callback=initMap&v=weekly&libraries=drawing"
                defer
            ></script>
        @endpush

        {{-- <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                Pagina de utilizador
                <br>
                <br>
                <div>
                    <a class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition" href="{{ route('spotify.auth') }}">Log in with Spotify</a>
                </div>
            </div>
        </div> --}}
    </div>
</x-app-layout>
