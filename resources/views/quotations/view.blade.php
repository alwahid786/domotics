<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                @if (Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Manager'))
                    {{ __('Preventivo di') }} {{ $quotation->user->name ?? $quotation->user->email }} - {{ $quotation->created_at->format('d/m/Y') }}

                @else
                    {{ __('Il tuo preventivo') }} - {{ $quotation->created_at->format('d/m/Y') }} - {{ $quotation->title }}
                @endif
            </h2>
            <div>
                <a href="{{ route('quotation.exportPdf', $quotation->id) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">Esporta PDF</a>
                <form action="{{ route('quotations.send-email', $quotation->id) }}" method="POST"
                      style="display:inline-block;">
                    @csrf
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">Invia via Email</button>
                </form>
            </div>
        </div>
    </x-slot>

    <main class="mt-6">
        <div class="py-12 ">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="grid gap-6 lg:grid-cols-2 lg:gap-8 bg-white shadow p-4 rounded">
            <div class="pt-3 sm:pt-5 mb-2 p-4">
                <!-- Search and Filter -->



                            @if ($quotation && $quotation->products->count() > 0)
                                <form action="#" method="POST" class="pt-4">
                                    @csrf
                                    @method('PUT')

                                    {{-- Group the products by their product_room_id --}}
                                    @php
                                        $productsGroupedByRoom = $quotation->products->groupBy('pivot.product_room_id');
                                    @endphp

                                    @foreach ($productsGroupedByRoom as $roomId => $products)
                                        @php
                                            $room = \App\Models\Room::find($roomId);
                                        @endphp

                                        <h2 class="text-xl font-semibold text-black dark:text-white">Ambiente: {{ $room ? $room->name : 'Unknown Room' }} - {{ $room ? $room->code : 'no code' }}</h2>
                                        <ul class="list-group mb-4">
                                            @foreach ($products as $product)
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <strong>{{ $product->name }} - {{ $product->code }}</strong>
                                                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-16 h-16 object-cover">
                                                        <br>
                                                        Quantità:
                                                        <input type="number"
                                                               name="quantities[{{ $product->id }}][{{ $product->pivot->product_room_id }}]"
                                                               value="{{ $product->pivot->quantity }}" min="1"
                                                               class="form-control d-inline-block" style="width: 70px;" readonly>
                                                        ×
                                                        @if (Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Manager'))
                                                            <input type="number"
                                                                   name="prices[{{ $product->id }}][{{ $product->pivot->product_room_id }}]"
                                                                   value="{{ $product->pivot->price }}" step="0.01"
                                                                   class="form-control d-inline-block"
                                                                   style="width: 100px;" readonly> (da listino: €{{$product->priceByRole($quotation->user)}})


                                                        @else
                                                            € {{ $product->pivot->price }}
                                                        @endif
                                                        <span class="badge badge-primary badge-pill">
                        = €{{ $product->pivot->quantity * $product->pivot->price }}
                    </span>
                                                    </div>

                                                </li>
                                            @endforeach
                                        </ul>
                                    @endforeach

                                    <h3 class="mt-3 p-4 font-bold text-xl text-green-600 dark:text-green-400">Totale:
                                        €{{ $quotation->products->sum(fn($product) => $product->pivot->quantity * $product->pivot->price)-($quotation->products->sum(fn($product) => $product->pivot->quantity * $product->pivot->price)*$quotation->discount/100) }}</h3>

                                    <br/>
                                    <br/>
                                </form>

                    <br/>
                    <br/>
                    <h2 class="text-xl font-semibold text-black dark:text-white">Complessivo preventivo</h2>

                    @php
$aggregatedProducts = $quotation->products->groupBy('id')->map(function ($group) {
    $quantity = $group->sum('pivot.quantity');
    $price = $group->sum(function ($product) {
        return $product->pivot->price * $product->pivot->quantity;
    });

    return [
        'name' => $group->first()->name,
        'quantity' => $quantity,
        'total_price' => $price,
    ];
});
@endphp

@foreach ($aggregatedProducts as $productId => $product)
    <p>Prodotto: {{ $product['name'] }}, Quantità: {{ $product['quantity'] }},  Totale: €{{ $product['total_price'] }}</p>
@endforeach
                            @else
                                <p>Your quotation is empty.</p>
                            @endif

            </div>

            <div class="pt-3 sm:pt-5 mb-2 p-5">
                <h2 class="text-xl font-semibold text-black dark:text-white">Perchè scegliere MyDomotics</h2>

                <p class="mt-4 text-sm/relaxed">
                    Da oggi e disponibile in Italia presso MyDomotics questo incredibile nuovo strumento per coloro
                    che si accingono a ristrutturare casa.
                </p>
            </div>


        </div>
        </div>
    </div>
    </main>
</x-app-layout>
