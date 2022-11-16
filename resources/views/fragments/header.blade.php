<header class="bg-white sticky top-0 pt-5 pb-2 shadow">
    <div class="mx-auto sm:px-6 lg:px-8">
        <div class="flex">
            <div class="flex-none">
                <h3 class="text-xl">
                    <a href="{{ route('homepage') }}">
                        | logotipo |
                    </a>
                </h3>
            </div>
            <div class="grow mx-20">
                <input type="text" name="search" class="min-w-full"
                    placeholder="Search for artists, events and promoters"/>
            </div>

            <div class="flex-none">
                @if (Route::has('login'))
                <div class="hidden top-0 right-0 px-6 py-4 sm:block">
                    @auth
                    <a href="{{ url('/dashboard') }}" class="text-sm text-gray-700 dark:text-gray-500 underline">
                        Dashboard
                    </a>
                    @else
                    <a href="{{ route('login') }}" class="text-sm text-gray-700 dark:text-gray-500 underline">Log in</a>
                    
                    @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="ml-4 text-sm text-gray-700 dark:text-gray-500 underline">
                        Register
                    </a>
                    @endif
                    @endauth
                </div>
                @endif
            </div>
        </div>
    </div>
</header>
