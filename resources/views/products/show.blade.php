<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $product->name }} - {{ $room->name }}
            </h2>
            <div>
                <a href="javascript:history.back(-1)" class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    Torna indietro
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6 bg-white shadow p-4 rounded">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="overflow-x-auto">
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-60 h-60 object-cover">
                {{ $product->name }} - {{ $product->code }} -  {{ $room->name }}<br/><br/>
                {{ $product->description }}<br/><br/>
                €{{ $product->priceByRole(Auth::user()) }}



            </div>

    <form action="{{ route('quotation.add', [$product->id, $room]) }}" method="POST">
    @csrf
    <div class="form-group">
        <label for="quantity">Quantità</label>
        <input type="number" name="quantity" value="1" min="1" class="form-control">
    </div><br/>
    <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">Aggiungi al preventivo</button>
</form>
        </div>
    </div>

</x-app-layout>
