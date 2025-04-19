<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                @if (Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Manager'))
                    {{ __('Preventivo di') }} {{ $quotation->user->name ?? $quotation->user->email }}
                    - {{ $quotation->created_at->format('d/m/Y') }} - <a
                        href="{{ route('quotation.titlechange',$quotation->id) }}">{{ $quotation->title}}</a>

                @else
                    {{ __('Il tuo preventivo') }}
                @endif
            </h2>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div>
                <a href="{{ route('quotation.exportPdf', $quotation->id) }}"
                   class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">Esporta
                    PDF</a>
                <form action="{{ route('quotations.send-email', $quotation->id) }}" method="POST"
                      style="display:inline-block;">
                    @csrf
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                        Invia via email
                    </button>
                </form>
            </div>
        </div>
    </x-slot>
    <style>
        .quote {
            width: 200px;
            max-height: 200px;
        }
    </style>
    <main class="mt-6">
        <div class="py-12 ">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="grid gap-6 lg:grid-cols-2 lg:gap-8 bg-white shadow p-4 rounded">
                    <div class="pt-3 sm:pt-5 mb-2 p-4" id="listaProdottiInPreventivo">
                        <!-- Search and Filter -->


                        @if ($quotation && $quotation->products->count() > 0)
                            <form action="{{ route('quotation.update') }}" method="POST" class="pt-4">
                                <input type="hidden" name="quotation_id" value="{{ $quotation->id }}">
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

                                    <h2 class="text-xl font-semibold text-black dark:text-white">
                                        Ambiente: {{ $room ? $room->name : 'Unknown Room' }} - (totale prodotti: €{{ $products->sum(fn($product) => $product->pivot->quantity * $product->pivot->price) }})</h2>
                                    <ul class="list-group mb-4">

                                        @foreach ($products as $product)
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <div>
                                                    <table>
                                                        <tr style="padding-top: 10px;display: grid;">
                                                            <td colspan="2">
                                                                <strong>{{ $product->name }}
                                                                    - {{ $product->code }}</strong>
                                                                <span class="badge badge-primary badge-pill"
                                                                      style="float:right;color:red;">
                                                                        <a href="{{ route('quotation.remove', [$product->id, $quotation, $roomId]) }}"
                                                                            class="btn btn-danger font-extrabold"
                                                                            onclick="return confirm('Sei sicuro di voler cancellare {{$product->name}} da {{$room->name}}?');">
                                                                            <i class="fas fa-trash-alt mr-2"></i>
                                                                        </a>
                                                                </span>
                                                            </td>
                                                        </tr>
                                                        <tr style="border-bottom:1px dotted #ccc; padding-bottom: 20px;display: inline;">
                                                            <td>
                                                                <img src="{{ asset('storage/' . $product->image) }}"
                                                                     alt="Product Image"
                                                                     class="w-24 h-24 mt-2 quote">
                                                            </td>
                                                            <td style="padding: 5px;">
                                                                Quantità:
                                                                <input type="number"
                                                                       name="quantities[{{ $product->id }}][{{ $product->pivot->product_room_id }}]"
                                                                       value="{{ $product->pivot->quantity }}" min="1"
                                                                       class="form-control d-inline-block"
                                                                       style="width: 70px;">
                                                                ×
                                                                @if (Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Manager'))
                                                                    <input type="number"
                                                                           name="prices[{{ $product->id }}][{{ $product->pivot->product_room_id }}]"
                                                                           value="{{ $product->pivot->price }}"
                                                                           step="0.01"
                                                                           class="form-control d-inline-block"
                                                                           style="width: 100px;">

                                                                @else
                                                                    € {{ $product->pivot->price }}
                                                                    <input type="hidden"
                                                                           name="prices[{{ $product->id }}][{{ $product->pivot->product_room_id }}]"
                                                                           value="{{ $product->pivot->price }}"
                                                                           step="0.01"
                                                                           class="form-control d-inline-block"
                                                                           style="width: 100px;">
                                                                @endif
                                                                <span class="badge badge-primary badge-pill">
                                                        = €{{ $product->pivot->quantity * $product->pivot->price }}
                                                    </span>
                                                                <span class="badge badge-primary badge-pill">

                                                                    <br>
                                                                     (da listino: €{{$product->priceByRole($quotation->user)}})
                                                    </span>
                                                                <br><br><label for="note">Note</label>
                                                                <input type="text"
                                                                       name="note[{{ $product->id }}][{{ $product->pivot->product_room_id }}]"
                                                                       id="note[{{ $product->id }}][{{ $product->pivot->product_room_id }}]"
                                                                       value="{{ $product->pivot->note }}">

                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>

                                            </li>
                                        @endforeach
                                    </ul>

                                @endforeach

                                @if (Auth::user()->hasRole('Admin'))
                                    <br/>
                                    <br/>
                                    <div class="form-group">
                                        <label for="discount">Sconto (%)</label>

                                        <input type="number" name="discount" id="discount"
                                               value="{{ old('discount', $quotation->discount?$quotation->discount:0) }}" step="0.01"
                                               class="form-control" size="5">
                                    </div>
                                @endif

                                <h3 class="mt-3 p-4 font-bold text-xl text-green-600 dark:text-green-400">Totale:
                                    €{{ $quotation->products->sum(fn($product) => $product->pivot->quantity * $product->pivot->price)-($quotation->products->sum(fn($product) => $product->pivot->quantity * $product->pivot->price)*$quotation->discount/100) }}</h3>
                                <br/>
                                <br/>
                                <button type="submit"
                                        class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                    Aggiorna preventivo
                                </button>
                                @if($quotation->status=='confirmed'&& Auth::user()->hasRole('Admin'))
                                    <a href="{{route('quotations.complete',$quotation->id)}}"
                                       class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">Completa
                                        preventivo</a>
                                @else
                                    <a href="{{route('quotations.confirm',$quotation->id)}}"
                                       class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">Conferma
                                        preventivo</a>
                                @endif
                            </form>

                            <br/>
                            <br/>
                            <h4 class="text-xl font-semibold text-black dark:text-white">Una volta confermato il preventivo potrai, se necessario, aggiungere una piantina in pdf dalla sezione Preventivi</h4>
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
                                <p>Prodotto: {{ $product['name'] }}, Quantità: {{ $product['quantity'] }}, Totale:
                                    €{{ $product['total_price'] }}</p>
                            @endforeach
                        @else
                            <p>Your quotation is empty.</p>
                        @endif

                    </div>

                    <div class="pt-3 sm:pt-5 mb-2 p-5">
                        <h2 class="text-xl font-semibold text-black dark:text-white">Perchè scegliere MyDomotics</h2>

                        <p class="mt-4 text-sm/relaxed">
                            Da oggi e disponibile in Italia presso MyDomotics questo incredibile nuovo strumento
                            per coloro
                            che si accingono a ristrutturare casa.
                        </p>

                        <h2 class="text-xl font-semibold text-black dark:text-white">Aggiungi un prodotto al
                            preventivo</h2>


                        <div class="form-group mb-4">
                            <label for="product_id">Ambiente</label>

                            <select id="room-select" class="form-control">
                                <option value="">Scegli un ambiente</option>
                                @foreach ($rooms as $room)
                                    <option value="{{ $room->id }}">{{ $room->name }} - {{ $room->code }}</option>
                                @endforeach
                            </select>

                            <h6>Prodotti</h6>
                            <div id="product-list"></div>

                            <script>
                                document.getElementById('room-select').addEventListener('change', function () {
                                    var roomId = this.value;

                                    if (roomId) {
                                        fetch(`/products/search?room_id=${roomId}`)
                                            .then(response => response.json())
                                            .then(data => {
                                                var productList = document.getElementById('product-list');
                                                productList.innerHTML = '';

                                                data.forEach(product => {
                                                    var productItem = document.createElement('div');
                                                    productItem.textContent = product.name;

                                                    var addButton = document.createElement('button');
                                                    addButton.textContent = 'Aggiungi al preventivo';
                                                    addButton.classList.add('btn', 'dark:bg-gray-200');
                                                    addButton.classList.add('btn', 'border');
                                                    addButton.classList.add('btn', 'inline-flex');
                                                    addButton.classList.add('btn', 'bg-gray-800');
                                                    addButton.classList.add('btn', 'text-white');
                                                    addButton.classList.add('btn', 'text-xs');
                                                    addButton.addEventListener('click', function () {
                                                        fetch(`/quotation/add-product`, {
                                                            method: 'POST',
                                                            headers: {
                                                                'Content-Type': 'application/json',
                                                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                                            },
                                                            body: JSON.stringify({
                                                                product_id: product.id,
                                                                quotation_id: {{ $quotation->id }},
                                                                roomId: roomId
                                                            })
                                                        })
                                                            .then(response => response.json())
                                                            .then(data => {
                                                                if (data.success) {
                                                                    alert('Product added to quotation successfully');
                                                                    refreshQuotationProducts();
                                                                } else {
                                                                    alert('Failed to add product to quotation');
                                                                }
                                                            })
                                                            .catch(error => {
                                                                console.error('Error:', error);
                                                                alert('Failed to add product to quotation');
                                                            });
                                                    });

                                                    productItem.appendChild(addButton);
                                                    productList.appendChild(productItem);
                                                });
                                            });
                                    } else {
                                        document.getElementById('product-list').innerHTML = '';
                                    }
                                });

                                function refreshQuotationProducts() {
                                    window.location.reload();
                                }
                            </script>
                        </div>


                    </div>


                </div>
            </div>
        </div>
    </main>
</x-app-layout>
