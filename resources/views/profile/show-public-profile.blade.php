<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('User') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-5">
                <h1 class="text-2xl mb-5">{{ $user->name }}</h1>
                
                <div class="">
                    @if (!empty($user->profile_photo_path))
                        <img 
                            class="float-left inline-block mr-10 w-64"
                            alt="{{ $user->name }}"
                            src="{{ asset('/storage/' . $user->profile_photo_path) }}"
                        />
                    @endif

                    Level: {{ $user->level() }}<br>
                    XP: {{ $user->reputation }}<br>
                    <br>

                    <h3> {{ __('Badges') }} </h3>
                    <div class="grid grid-cols-3 gap-4 place-content-center">
                    @foreach ($user->badges as $badge)
                        <div class="mx-auto text-center rounded border-2 p-5 drop-shadow-md">
                            Name: {{ $badge->name }}<br>
                            Description: {{ $badge->description}}<br>
                            Level: {{ $badge->level }}<br>
                            Earned on: {{ $badge->created_at }}<br>
                        </div>
                    @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
