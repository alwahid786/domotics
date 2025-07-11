<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Stime') }}
            </h2>
            <div>
                <a href="{{ route('estimations.create') }}"
                    class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    + Aggiungi  nuovo preventivo
                </a>
            </div>
        </div>
    </x-slot>



    <!-- Search and Filter -->
    <main class="mt-6">
        <div class="py-12 ">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6 bg-white shadow p-4 rounded">
                <!-- Products Table -->
                <div class="table-responsive">
                    <table class="table-auto w-full">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="px-4 py-2">Immagine</th>
                                <th class="px-4 py-2">Nome del Floor</th>
                                @if ($roleId == 1 || $roleId == 2)
                                    <th class="px-4 py-2">utente nome</th>
                                @endif
                                <th class="px-4 py-2">Totale</th>
                                <th class="px-4 py-2">Azioni</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($estimations as $estimation)
                                <tr>
                                    <td class="border px-4 py-2"><img src="{{ asset($estimation->image) }}"
                                            alt="{{ $estimation->image }}" class="w-16 h-16 object-cover"></td>
                                    <td class="border px-4 py-2">{{ $estimation->floor_name }}</td>
                                    @if ($roleId == 1 || $roleId == 2)
                                        <td class="border px-4 py-2">{{ $estimation->user_name }}</td>
                                    @endif
                                    <td class="border px-4 py-2">€{{ $estimation->total }}</td>
                                    <td class="border px-4 py-2">
                                        <div class="flex flex-wrap gap-2">
                                            <a href="{{ route('estimations.show', $estimation->id) }}"
                                                class="inline-flex items-center px-3 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition">
                                                Visualizza
                                            </a>

                                            <a href="{{ route('estimations.edit', $estimation->id) }}"
                                                class="inline-flex items-center px-3 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition">
                                                Modifica
                                            </a>

                                            @if ($roleId == 1 || $roleId == 2)
                                                <a href="{{ route('estimations.destroy', $estimation->id) }}"
                                                    class="inline-flex items-center px-3 py-2 bg-red-600 hover:bg-red-700 text-white border border-transparent rounded-md font-semibold text-xs uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition"
                                                    onclick="event.preventDefault(); if(confirm('Are you sure you want to delete this estimation?')) { document.getElementById('delete-form-{{ $estimation->id }}').submit(); }">
                                                    Cancella
                                                </a>

                                                <form id="delete-form-{{ $estimation->id }}"
                                                    action="{{ route('estimations.destroy', $estimation->id) }}"
                                                    method="POST" class="hidden">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            @endif
                                        </div>
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td class="border px-4 py-2" colspan="5">Nessun prodotto</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $estimations->links() }}
                </div>
            </div>
        </div>
    </main>
</x-app-layout>
