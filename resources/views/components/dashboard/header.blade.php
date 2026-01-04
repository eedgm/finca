<header class="sticky top-0 z-50 grid w-full grid-cols-2 pl-6 bg-green-800 h-14">
    <div class="flex items-center">

        <button class="p-2 mr-2 -ml-2" @click="isSidebarExpanded = !isSidebarExpanded">
        <i class="w-6 h-6 text-3xl text-white transform bx bx-menu-alt-left" :class="isSidebarExpanded ? 'rotate-0' : 'rotate-90'"></i>
        </button>
        {{-- <div class="block">
            <!-- Search -->
            <form action="#" class="app-search" method="GET">
                <div class="relative group ">
                    <input type="text" name="search"
                        class="form-input rounded-md bg-gray-700 text-sm text-gray-300 pl-3 md:pl-12 focus:pl-8 py-1.5 ml-1 md:ml-5 border-transparent border-none outline-none focus:ring-0 focus:text-white transition-all duration-300 ease-in-out focus:w-28 w-24 md:focus:w-60 md:w-48"
                        placeholder="Search..." autocomplete="off">
                    <span
                        class="absolute text-gray-400 transition-all duration-300 ease-in-out left-20 md:left-44 bottom-2 group-focus-within:left-3 md:group-focus-within:left-8">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </span>
                </div>
            </form>
        </div> --}}

        <div class="flex items-center">
            <div class="block md:hidden">
                <a
                    href="{{ route('dashboard') }}"
                    class="flex items-center px-4 overflow-hidden text-white hover:text-gray-100 hover:bg-opacity-50"
                    >
                    <img src="{{ asset('mi-finca.webp') }}" alt="Mi Finca" class="w-12 h-auto">
                    <span class="ml-2 text-lg font-medium duration-300 ease-in-out">Mi Finca</span>
                </a>
            </div>
            {{-- <a class="m-3 text-2xl text-white" href="{{ route('board') }}"><i class="bx bx-detail"></i></a>
            <livewire:new-ticket /> --}}
        </div>
    </div>

    <div class="flex justify-end">
        <!-- Profile Menu DT -->
        <div class="flex ml-0 md:ml-4">
            {{-- <div class="relative flex items-center justify-center mr-4">
                <div class="block p-1 text-gray-400 bg-gray-700 rounded-full hover:text-white">
                    <span class="sr-only">View notifications</span>
                    <i class="bx bx-bell-minus"></i>
                </div>
            </div> --}}

            {{-- <livewire:choose-year /> --}}

            <!-- Profile dropdown -->
            <div class="relative px-1 text-sm text-white bg-green-700 cursor-pointer hover:text-white"
                x-data="{open: false}">
                <div class="flex items-center min-h-full" @click="open = !open">
                    @if(Auth::check())
                        <div class="flex flex-col ml-1">
                            {{ Auth::user()->name }}
                        </div>
                    @endif
                </div>

                <div x-show="open" @click.away="open = false"
                    class="absolute right-0 min-w-full py-1 mt-0 origin-top-right bg-white shadow rounded-b-md ring-1 ring-black ring-opacity-5 focus:outline-none"
                    x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="transform opacity-0 scale-95"
                    x-transition:enter-end="transform opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="transform opacity-100 scale-100"
                    x-transition:leave-end="transform opacity-0 scale-95" role="menu"
                    aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1">
                    <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                        role="menuitem" tabindex="-1" id="user-menu-item-0">Profile</a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-dropdown-link href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                        this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-dropdown-link>
                    </form>
                </div>
            </div>

        </div>
    </div>
</header>
