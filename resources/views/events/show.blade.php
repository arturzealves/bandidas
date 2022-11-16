<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Event') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">


            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-3">
                <div
                    class="bg-top bg-cover relative p-5"
                    style="background-image:url({{ $images['square1024'] }});"
                >
                    <h1 class="text-2xl text-white mb-5">{{ $event->name }}</h1>
                    <h2 class="text-xl text-white">{{ $event->address->city }}</h2>
                    <h2 class="text-xl text-white mb-5">{{ $event->sessions->first()->start }}</h2>

                    <button class="inline-flex items-center px-4 py-2 bg-gray-800 rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition ml-4 bg-green-500 hover:bg-green-600">
                        Some action
                    </button>

                    <div class="absolute top-8 right-8">
                        <img class="mx-auto border border-white border-2" src="{{ $images['square256'] }}" />
                    </div>
                </div>
                <div class="relative p-5 flex flex-row">
                    <div class="basis-1/4">
                        {{ $event->address->line1 }}<br>
                        {{ $event->address->city }}<br>
                        {{ $event->address->region }}<br>
                        {{ $event->address->country }}<br>
                    </div>
                    <div class="basis-1/4">
                        € {{ $event->prices->first()->price }}<br>
                    </div>
                    <div class="basis-1/4">
                        @foreach ($event->promoters as $promoter)
                            {{ $promoter->name }}<br>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-3 p-5">
                <h2 class="text-lg mb-5">Description</h2>
                <p>{{ $event->description }}</p>
            </div>


            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-5">
                <table class="border">
                    <tr><th>uuid</th><td>{{ $event->uuid }}</td></tr>
                    <tr><th>name</th><td>{{ $event->name }}</td></tr>
                    <tr><th>type</th><td>{{ $event->type }}</td></tr>
                    <tr><th>description</th><td>{{ $event->description }}</td></tr>
                    <tr><th>latitude</th><td>{{ $event->latitude }}</td></tr>
                    <tr><th>longitude</th><td>{{ $event->longitude }}</td></tr>
                    <tr><th>address_uuid</th><td>{{ $event->address_uuid }}</td></tr>
                    <tr><th>address line 1</th><td>{{ $event->address->line1 }}</td></tr>
                    <tr><th>address line 2</th><td>{{ $event->address->line2 }}</td></tr>
                    <tr><th>address line 3</th><td>{{ $event->address->line3 }}</td></tr>
                    <tr><th>address line 4</th><td>{{ $event->address->line4 }}</td></tr>
                    <tr><th>address city</th><td>{{ $event->address->city }}</td></tr>
                    <tr><th>address region</th><td>{{ $event->address->region }}</td></tr>
                    <tr><th>address postal code</th><td>{{ $event->address->postal_code }}</td></tr>
                    <tr><th>address country</th><td>{{ $event->address->country }}</td></tr>
                    <tr><th>min_age</th><td>{{ $event->min_age }}</td></tr>
                    <tr>
                        <th>artists</th>
                        <td>
                            <ul>
                                @foreach ($event->artists as $artist)
                                    <li>
                                        <a class="text-blue-600"
                                            href="{{ route('artists.show', ['artist' => $artist]) }}"
                                        >
                                            {{ $artist->name }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </td>
                    </tr>
                    <tr>
                        <th>sessions</th>
                        <td>
                            <ul>
                                @foreach ($event->sessions as $session)
                                    <li>{{ $session->start }} - {{ $session->end }}</li>
                                @endforeach
                            </ul>
                        </td>
                    </tr>
                    <tr>
                        <th>prices</th>
                        <td>
                            <ul>
                                @foreach ($event->prices as $price)
                                    <li>
                                        <ul>
                                            <li>date: {{ $price->date }}</li>
                                            <li>price: {{ $price->price }}</li>
                                            <li>age: {{ $price->age }}</li>
                                            <li>description: {{ $price->description }}</li>
                                        </ul>
                                    </li>
                                @endforeach
                            </ul>
                        </td>
                    </tr>
                    <tr>
                        <th>promoters</th>
                        <td>
                            <ul>
                                @foreach ($event->promoters as $promoter)
                                    <li>
                                        <a class="text-blue-600"
                                            href="{{ route('promoters.show', ['promoter' => $promoter]) }}"
                                        >
                                            {{ $promoter->name }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
