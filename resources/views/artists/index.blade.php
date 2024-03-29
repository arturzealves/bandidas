<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Artists') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <form method="GET" action="{{ route('spotify.redirect', ['action' => 'getUserFollowedArtists']) }}">
                <div class="block mt-4">
                    <x-jet-button class="ml-4 bg-green-500 hover:bg-green-600">
                        {{ __('Import followed artists') }}
                    </x-jet-button>
                </div>
            </form>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="grid grid-cols-7 gap-4 place-content-center">
                    @foreach ($artists as $artist)
                        <div class="mx-auto text-center">
                            <a href="{{ route('artists.show', ['artist' => $artist]) }}">
                                @if ($artist->smallImage)
                                    <img
                                        class="mx-auto"
                                        alt="{{ $artist->name }}"
                                        src="{{ $artist->smallImage->url }}"
                                        width="{{ $artist->smallImage->width }}"
                                        height="{{ $artist->smallImage->height }}"
                                    />
                                @endif
                                <span>{{ $artist->name }}</span>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
