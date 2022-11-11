<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Promoter') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-3">
                <div
                    class="bg-top bg-cover relative p-5"
                    @if ($promoter->spotify)
                    style="background-image:url({{ $promoter->largeImage->url }});"
                    @endif
                >
                    <h1 class="text-2xl mb-5 no-filter">{{ $promoter->name }}</h1>
                    {{--
                    <h2 class="text-xl text-white">{{ $promoter->address->city }}</h2>
                    <h2 class="text-xl text-white mb-5">{{ $promoter->sessions->first()->start }}</h2>
                    --}}

                    <div class="absolute top-8 right-8 w-80">
                        @if ($promoter->spotify)
                            <img class="mx-auto border border-white border-2" src="{{ $promoter->mediumImage->url }}" />
                        @endif
                    </div>
                </div>
                <div class="relative p-5 flex flex-row">
                    <div class="basis-1/4">
                        info 1
                    </div>
                    <div class="basis-1/4">
                       info 2
                    </div>
                    <div class="basis-1/4">
                        info 3
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-3 p-5">
                <h2 class="text-lg mb-5">Events</h2>
                <ul>
                    @foreach ($promoter->eventsPromoted as $event)
                        <li>
                            <a class="text-blue-600"
                                href="{{ route('events.show', ['event' => $event]) }}"
                            >
                                {{ $event->name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-3 p-5">
                <h2 class="text-lg mb-5">Artists</h2>
                <ul>
                    @foreach ($promoter->eventsPromoted as $event)
                        @foreach ($event->artists as $artist)
                            <li>
                                <a class="text-blue-600"
                                    href="{{ route('artists.show', ['artist' => $artist]) }}"
                                >
                                    {{ $artist->name }}
                                </a>
                            </li>
                        @endforeach
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</x-app-layout>
