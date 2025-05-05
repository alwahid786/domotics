<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                @if (Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Manager'))
                    {{ __('Nuovo Preventivo') }}


                @else
                    {{ __('Il tuo nuovo preventivo') }}
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
                        <form action="{{ route('quotation.updateTitle',$quotation->id) }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Titolo</label>
                                <input type="text" name="title" id="title" value="{{ $quotation->title }}"
                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            </div>



                            <div class="mb-4">
                                <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">Salva</button>
                            </div>
                        </form>





                    </div>

                    <div class="pt-3 sm:pt-5 mb-2 p-5">
                     <h2 class="text-xl font-semibold text-black dark:text-white">Perchè scegliere MyDomotics</h2>

                        <p class="mt-4 text-sm/relaxed">
                            Da oggi e disponibile in Italia questo incredibile nuovo strumento
                            per coloro che si accingono a ristrutturare casa.
                        </p>






                    </div>


                </div>
            </div>
        </div>
    </main>
</x-app-layout>
