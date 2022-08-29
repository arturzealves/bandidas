<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                Pagina de promotor
                <br>
                <br>
                <span>Número de utilizadores: {{ $userCount }} </span><br>
                <span>Número de artistas: {{ $spotifyArtistCount }} </span><br>
                <span>Número de artistas registados: {{ $artistCount }} </span><br>

                <a href="{{ route('map') }}">Mapa</a>
            </div>
        </div>
    </div>
</x-app-layout>
