<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-800 leading-tight">
            {{ __('Room List') }}
        </h2>


    </x-slot>
    <style>
        /* Default 2-column grid */
        .grid-cols-2 {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        /* Small screens (640px and up) - 2-column layout */
        @media (min-width: 640px) {

            .lg\:grid-cols-4 {
                grid-template-columns: repeat(4, minmax(0, 1fr))
            }

            .sm\:grid-cols-2 {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            /* Large screens (1024px and up) - 4-column layout */
            @media (min-width: 1024px) {
                .lg\:grid-cols-4 {
                    grid-template-columns: repeat(4, minmax(0, 1fr))
                }

            }


            /*
     .lg\:grid-cols-3{grid-template-columns:repeat(3,minmax(0,1fr))}

    .grid-cols-4{grid-template-columns:repeat(4,minmax(0,1fr))}
    .grid-cols-2{grid-template-columns:repeat(2,minmax(0,1fr))}
    */
            .grid {
                display: grid
            }

            .gap-4 {
                grid-gap: 1rem
            }

            .p-4 {
                padding: 1rem
            }

            .rounded {
                border-radius: .375rem
            }

            .bg-white {
                --bg-opacity: 1;
                background-color: #fff;
                background-color: rgba(255, 255, 255, var(--bg-opacity))
            }
    </style>
    <div class="py-12 bg-white">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 bg-white shadow p-4 rounded">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">

                <ul class="grid lg:grid-cols-4 sm:grid-cols-2 gap-4">
                    @foreach ($rooms as $room)
                        <li class="bg-white shadow p-4 rounded">
                            <a href="{{ route('room.products', ['room' => $room->id]) }}">
                                <h2 class="text-2xl font-semibold">{{ $room->name }}</h2>
                            </a>
                            <div class="">
                                <a href="{{ route('room.products', ['room' => $room->id]) }}">
                                    <img src="{{ asset('storage/' . $room->image) }}" alt="{{ $room->name }}"
                                        class="w-full h-64 object-cover">
                                </a>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</x-app-layout>
