<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Artist') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-3">
                <div
                    class="bg-top bg-cover relative p-5"
                    @if ($artist->spotify)
                    style="background-image:url({{ $artist->largeImage->url }});"
                    @endif
                >
                    <h1 class="text-2xl text-white mb-5 no-filter">{{ $artist->name }}</h1>
                    {{--
                    <h2 class="text-xl text-white">{{ $artist->address->city }}</h2>
                    <h2 class="text-xl text-white mb-5">{{ $artist->sessions->first()->start }}</h2>
                    --}}

                    <button class="no-filter inline-flex items-center px-4 py-2 bg-gray-800 rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition ml-4 bg-green-500 hover:bg-green-600">
                        Some action
                    </button>

                    <div class="absolute top-8 right-8 w-80">
                        @if ($artist->spotify)
                            <img class="mx-auto border border-white border-2" src="{{ $artist->mediumImage->url }}" />
                        @endif
                    </div>
                </div>
                <div class="relative p-5 flex flex-row">
                    <div class="basis-1/4">
                        @if ($artist->spotify)
                            Followers: {{ $artist->spotify->followers }}<br>
                            Popularity: {{ $artist->spotify->popularity }}
                        @endif
                    </div>
                    <div class="basis-1/4">
                        @if ($artist->spotify)
                            <a target="_blank" href="{{ $artist->spotify->url }}">Open on Spotify</a>
                            <br>
                            <a target="_blank" href="{{ $artist->spotify->uri }}">Play on Spotify</a>
                        @endif
                    </div>
                    <div class="basis-1/4">
                        <ul>
                            @foreach ($artist->genres as $genre)
                                <li>
                                    {{ $genre->name }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-3 p-5">
                <h2 class="text-lg mb-5">Events</h2>
                <ul>
                    @foreach ($artist->events as $event)
                        @foreach ($event->sessions as $session)
                        <li>
                            <a href="{{ route('events.show', ['event' => $event]) }}" class="text-blue-600">
                                {{ Carbon\Carbon::parse($session->start)->format('d-m-Y H:i') }}
                                {{ $event->name }}
                            </a>
                        </li>
                        @endforeach
                    @endforeach
                </ul>
            </div>

            {{--
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-3 p-5">
                <h2 class="text-lg mb-5">Description</h2>
                <p>{{ $artist->description }}</p>
            </div>
            --}}
        </div>
    </div>
</x-app-layout>
