<aside
    class="sticky top-0 z-50 flex flex-col h-screen text-gray-300 transition-all duration-300 ease-in-out bg-green-800 group"
    :class="isSidebarExpanded ? 'w-52 md:inline' : 'w-12 hidden md:inline md:w-16 hover:w-52'"
    >
    <a
        href="{{ route('dashboard') }}"
        class="flex items-center h-20 px-4 overflow-hidden bg-green-900 hover:text-gray-100 hover:bg-opacity-50 focus:outline-none focus:text-gray-100 focus:bg-opacity-50"
        >
        <i class='text-2xl bx bxs-landscape' ></i>
        <span class="ml-2 text-lg font-medium duration-300 ease-in-out" :class="isSidebarExpanded ? 'opacity-100' : 'opacity-0 group-hover:opacity-100'">Mi Finca</span>
    </a>
    <nav
        class="p-2 space-y-2 font-medium z-1"
        >
        <x-dashboard.sidebarmenu />
    </nav>
</aside>
