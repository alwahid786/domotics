<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ $room->name }}
    </h2>
            <a href="{{ route('dashboard') }}" class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                < Torna a tutti gli ambienti
            </a>
    <div>
        @if (($quotation?->products->count()>0))
            <a href="{{ route('quotation.current.view') }}" class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Vai al carrello
            </a>
        @endif


    </div>
</div>
    </x-slot>



    <!-- Search and Filter -->
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6 bg-white shadow p-4 rounded">
            <!-- Products Table -->
            <div class="overflow-x-auto">
                <table class="table-auto w-full">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-4 py-2">#Prodotto</th>
                            <th class="px-4 py-2">Codice</th>
                            <th class="px-4 py-2">Nome</th>
                            <th class="px-4 py-2">Prezzo</th>
                            <th class="px-4 py-2">Ambiente</th>
                            <th class="px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                            <tr>
                                <td class="border px-4 py-2"><a href="{{ route('products.show', [$product->id, $room]) }}"> {{ $product->id }}</a></td>
                                <td class="border px-4 py-2"><a href="{{ route('products.show', [$product->id, $room]) }}"> {{ $product->code }}</a></td>

                                <td class="border px-4 py-2"><a href="{{ route('products.show', [$product->id, $room]) }}"><img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-16 h-16 object-cover">{{ $product->name }}</a></td>
                                <td class="border px-4 py-2">€{{$product->priceByRole(Auth::user())}}</a></td>
                                <td class="border px-4 py-2">{{$room->name}}</td>
                                <td class="border px-4 py-2">
                                    <form action="{{ route('quotation.add', [$product->id, $room]) }}" method="POST" style="display:inline">
                                        @csrf
                                        @method('POST')
                                        <input type="text" value="1" required name="quantity" id="quantity" class="w-60" size="6">
                                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">Aggiungi al preventivo</button>
                                    </form>
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
        </div>
    </div>
</x-app-layout>
