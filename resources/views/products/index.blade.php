<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Prodotti') }}
            </h2>
            <div>
                <a href="{{ route('products.create') }}"
                    class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    + Aggiungi un nuovo prodotto
                </a>
            </div>
        </div>
    </x-slot>



    <!-- Search and Filter -->

    <main class="mt-6">
        <div class="py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6 bg-white shadow p-4 rounded">
                <!-- Products Table -->
                <div class="table-responsive">
                    <table class="min-w-full border border-gray-200 divide-y divide-gray-200">
                        <thead class="bg-gray-100 text-gray-700 text-left">
                            <tr>
                                <th class="px-4 py-3 text-sm font-semibold">Immagine</th>
                                <th class="px-4 py-3 text-sm font-semibold">Nome</th>
                                <th class="px-4 py-3 text-sm font-semibold">Codice</th>
                                <th class="px-4 py-3 text-sm font-semibold">Stato</th>
                                <th class="px-4 py-3 text-sm font-semibold">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100 text-sm text-gray-700">
                            @forelse($products as $product)
                                <tr>
                                    <td class="px-4 py-2">
                                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                            class="w-16 h-16 object-cover rounded">
                                    </td>
                                    <td class="px-4 py-2">{{ $product->name }}</td>
                                    <td class="px-4 py-2">{{ $product->code }}</td>
                                    <td class="px-4 py-2">
                                        {{-- Optional status display --}}
                                        <span
                                            class="inline-block px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded">
                                            Disponibile
                                        </span>
                                    </td>
                                    <td class="px-4 py-2 space-x-2">
                                        <div class="flex flex-wrap gap-2">
                                            <a href="{{ route('products.edit', $product->id) }}"
                                                class="inline-block px-3 py-1 bg-indigo-600 hover:bg-indigo-700 text-white text-xs rounded">
                                                Modifica
                                            </a>
                                            <a href="{{ route('products.destroy', $product->id) }}"
                                                onclick="event.preventDefault(); if(confirm('Are you sure you want to delete this product?')) document.getElementById('delete-form-{{ $product->id }}').submit();"
                                                class="inline-flex items-center px-3 py-2 bg-red-600 hover:bg-red-700 text-white border border-transparent rounded-md font-semibold text-xs uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition">
                                                Cancella
                                            </a>
                                            <form id="delete-form-{{ $product->id }}"
                                                action="{{ route('products.destroy', $product->id) }}" method="POST"
                                                class="hidden">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="px-4 py-3 text-center text-gray-500" colspan="5">Nessun prodotto</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </main>

</x-app-layout>
