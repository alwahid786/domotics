<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Ambienti') }}
            </h2>
            <div>
                <a href="{{ route('room.create') }}"
                    class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    + Aggiungi un ambiente
                </a>

            </div>
        </div>
    </x-slot>

    <main class="mt-6">
        <div class="py-12 ">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6 bg-white shadow p-4 rounded">

                <div class="container">
                    <div class="table-responsive">
                        <table class="table-auto w-full">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="px-4 py-2">Nome</th>
                                    <th>Codice</th>
                                    <th>Immagine</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($rooms as $room)
                                    <tr>
                                        <td class="border px-4 py-2">{{ $room->name }}</td>
                                        <td class="border px-4 py-2">{{ $room->code }}</td>
                                        <td class="border px-4 py-2">
                                            @if ($room->image)
                                                <img src="{{ asset('storage/' . $room->image) }}"
                                                    alt="{{ $room->name }}" class="img-thumbnail" width="100">
                                            @endif
                                        </td>
                                        <td class="border px-4 py-2">
                                            <a href="{{ route('room.edit', $room->id) }}"
                                                class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">Modifica</a>
                                            @if ($room->products->count() == 0)
                                                <form action="{{ route('room.destroy', $room->id) }}" method="POST"
                                                    style="display:inline-block;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"
                                                        onclick="return confirm('Are you sure you want to delete this room?')">Cancella</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
</x-app-layout>>
