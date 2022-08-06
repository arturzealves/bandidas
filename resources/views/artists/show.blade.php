<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Artists') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-5">
                <h1 class="text-2xl mb-5">{{ $artist->name }}</h1>
                
                <div class="">
                    <img 
                        class="float-left inline-block mr-10"
                        alt="{{ $artist->name }}"
                        src="{{ $artist->mediumImage->url }}" 
                        width="{{ $artist->mediumImage->width }}" 
                        height="{{ $artist->mediumImage->height }}"
                    />

                    Followers: {{ $artist->spotify->followers }}<br>
                    Popularity: {{ $artist->spotify->popularity }}<br>

                    <br>
                    <a href="{{ $artist->spotify->url }}">Open on Spotify</a>
                    <br>
                    <a href="{{ $artist->spotify->uri }}">Play on Spotify</a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
