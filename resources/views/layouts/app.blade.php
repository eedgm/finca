<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', 'Mi finca')</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Styles -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">

        <!-- Icons -->
        <link href="https://unpkg.com/ionicons@4.5.10-0/dist/css/ionicons.min.css" rel="stylesheet">

        <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])


        @livewireStyles
    </head>
    <body class="h-full overflow-x-hidden font-sans antialiased bg-gray-100">
        <div class="flex bg-gray-300" x-data="{ isSidebarExpanded: $persist(true) }">
            <x-dashboard.sidebar />

            <div class="z-10 flex flex-col flex-1 w-full">
                <x-dashboard.header />
                <main class="flex-1 w-full p-6 scrolling-touch bg-gray-100 xs:overflow-x-auto lg:flex-grow md:px-12">
                    @if (isset($header))
                        <div class="w-full py-4 pl-6 text-xl font-bold bg-white border-b border-gray-300 shadow-sm">
                                <h3 class="text-3xl font-medium text-gray-700">{{ $header }}</h3>
                        </div>
                    @endif
                    {{ $slot }}
                </main>
                <footer>
                    <x-dashboard.footer></x-dashboard.footer>
                </footer>
            </div>
        </div>

        @stack('modals')

        @livewireScripts

        @stack('scripts')

        <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>

        @if (session()->has('success'))
        <script>
            var notyf = new Notyf({dismissible: true})
            notyf.success('{{ session('success') }}')
        </script>
        @endif

        <script>
            /* Simple Alpine Image Viewer */
            document.addEventListener('alpine:init', () => {
                Alpine.data('imageViewer', (src = '') => {
                    return {
                        imageUrl: src,

                        refreshUrl() {
                            this.imageUrl = this.$el.getAttribute("image-url")
                        },

                        fileChosen(event) {
                            this.fileToDataUrl(event, src => this.imageUrl = src)
                        },

                        fileToDataUrl(event, callback) {
                            if (! event.target.files.length) return

                            let file = event.target.files[0],
                                reader = new FileReader()

                            reader.readAsDataURL(file)
                            reader.onload = e => callback(e.target.result)
                        },
                    }
                })
            })
        </script>
    </body>
</html>
