<x-guest-layout>
    <section class="flex items-top justify-center bg-gray-100 dark:bg-gray-900 sm:items-center sm:pt-0">
        <div class="max-w-screen-2xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 gap-5 my-5 p-5">
                <div class="px-20">
                    <h1 class="text-xl"></h1>
                    <br>
                    <ul class="list-disc">
                        <li>Import your favorite bands from Spotify</li>
                        <li>Tell event promoters how much you would pay to see your favorite artists</li>
                        <li>Connect with your favorite artists</li>
                        <li>See what public transports you can take on your way to an event</li>
                        <li>Review artists, events and event promoters</li>
                        <li>Save the upcoming events you would like to go</li>
                    </ul>
                    <br>
                    <a href="{{ route('register') }}">Register</a>
                </div>
            </div>
        </div>
    </section>

    <section class="flex items-top justify-center bg-gray-100 dark:bg-gray-900 sm:items-center sm:pt-0">
        <div class="max-w-screen-2xl mx-auto sm:px-6 lg:px-8">
            <h1 class="text-xl mt-7">Upcoming events</h1>
            <div class="mt-3 mb-10 bg-white dark:bg-gray-500 overflow-hidden shadow">
                <div class="flex flex-row overflow-x-scroll">
                    @foreach ($events as $event)
                        <a class="text-center m-5 w-256 flex-none"
                            href="{{ route('events.show', ['event' => $event]) }}"
                        >
                            <div>
                                <img class="mx-auto" src="{{ $event->images['square256'] }}" />
                                <p class="text-sm">{{ Carbon\Carbon::parse($event->start)->format('d-m-Y H:i') }}</p>
                                <ul class="text-lg">
                                    @foreach ($event->artist_name as $artist)
                                    <li>{{ $artist }}</li>
                                    @endforeach
                                </ul>
                                <p class="text-sm">{{ $event->name }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <section class="flex items-top justify-center bg-gray-100 dark:bg-gray-900 sm:items-center sm:pt-0">
        <div class="max-w-screen-2xl mx-auto sm:px-6 lg:px-8">
            <h1 class="text-xl mt-7">Featured Artists</h1>
            <div class="mt-3 mb-10 bg-white dark:bg-gray-500 overflow-hidden shadow">
                <div class="flex flex-row overflow-x-scroll">
                    @foreach ($artists as $artist)
                        <a class="text-center m-5 w-256 flex-none"
                            href="{{ route('artists.show', ['artist' => $artist->artist_uuid]) }}"
                        >
                            <div>
                                <img class="mx-auto max-h-64" src="{{ $artist->mediumImage->url }}" />
                                <p class="text-lg">{{ $artist->name }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <section class="items-top justify-center bg-gray-300">
        <div class="max-w-screen-2xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-5 p-5">
                <div></div>
                <div>
                    <h1 class="text-xl">Are you an event promoter?</h1>
                    <br>
                    <ul class="list-disc">
                        <li>Create events for free</li>
                        <li>See where are the interested public for your next events</li>
                        <li>See what artists are more desired on your area</li>
                        <li>Discover and connect with new artists</li>
                        <li>Get event reviews and build your reputation</li>
                    </ul>
                    <br>
                    <a href="#">Register</a>
                </div>
                <div></div>
                <div>
                    <h1 class="text-xl">Are you an artist?</h1>
                    <br>
                    <ul class="list-disc">
                        <li>Connect with your fans</li>
                        <li>See where are your best audience</li>
                        <li>Connect with event promoters and get more shows</li>
                        <li>Get event reviews and build your reputation</li>
                    </ul>
                    <br>
                    <a href="#">Register</a>
                </div>
                <div></div>
            </div>
        </div>
    </section>
</x-guest-layout>
