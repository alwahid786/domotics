<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Preventivi') }}
            </h2>
            <div>
                <a href="{{ route('quotation.create') }}" class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    + Crea nuovo preventivo
                </a>

            </div>
        </div>
    </x-slot>



    <!-- Search and Filter -->
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6 bg-white shadow p-4 rounded">

            @if ($quotations->count() > 0)
                <div class="overflow-x-auto">
                    <table class="table-auto w-full">
                        <thead>
                        <tr class="bg-gray-100">
                            <th class="px-4 py-2">Preventivo</th>
                            <th class="px-4 py-2">Utente</th>
                            <th class="px-4 py-2">Stato</th>
                            <th class="px-4 py-2">n. prodotti</th>
                            <th class="px-4 py-1">Azioni</th>
                            <th class="px-4 py-1">Quote</th>
                        </tr>
                        </thead>
                        <tbody>
        @foreach ($quotations as $quotation)
            <tr>
                <td class="border px-4 py-2">
                    <a href="{{ route('quotation.titlechange', $quotation->id) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"> <i class="fas fa-edit mr-2"></i>  #{{ $quotation->id }}</a>
                    <a href="{{ route('quotation.titlechange',$quotation->id) }}">{{ $quotation->title }}</a>


                </td>
                <td class="border px-4 py-2">
                    {{ $quotation->user->name ?? $quotation->user->email }}
                </td>
                <td class="border px-4 py-2">
                    {{ $quotation->status }}
                </td>
                <td class="border px-4 py-2">
                    {{ $quotation->products->count() }}
                </td>
                <td class="border px-4 py-2">
                    @if(($quotation->status==='confirmed'||$quotation->status==='pending')&& Auth::user()->hasRole('Admin'))
                        <a href="{{ route('quotations.edit', $quotation->id) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"> <i class="fas fa-edit mr-2"></i> </a>
                        <form action="{{ route('quotations.destroy', $quotation->id) }}" method="POST" style="display: inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150" onclick="return confirm('Are you sure you want to delete this quotation?')"> <i class="fas fa-trash-alt mr-2"></i> </button>
                        </form>

                    @elseif($quotation->status==='pending')
                        <a href="{{ route('quotations.edit', $quotation->id) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"><i class="fas fa-edit mr-2"></i></a>

                    @else
                        <a href="{{ route('quotations.view', $quotation->id) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"><i class="fas fa-file-alt mr-2"></i></a>
                    @endif

                </td>
                <td class="border px-4 py-2">
                        @if($quotation->status!='completed')
                        @if ($quotation->pdf_path)
                            <form action="{{ route('quotations.removePdf', $quotation->id) }}" method="POST" style="display: inline-block;">
                                @csrf
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 dark:bg-red-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-red-800 uppercase tracking-widest hover:bg-red-700 dark:hover:bg-red-300 focus:bg-red-700 dark:focus:bg-red-300 active:bg-red-900 dark:active:bg-red-400 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-red-800 transition ease-in-out duration-150"><i class="fas fa-trash-alt mr-2"></i> </button>
                            </form>
                        @else
                            @if($quotation->user_id==\Illuminate\Support\Facades\Auth::user()->id)
                            <style>
                                .custom-file-input {
                                    display: none;
                                }

                                .custom-file-label {
                                    display: inline-block;
                                    padding: 0.5rem 1rem;
                                    font-size: 0.875rem;
                                    font-weight: 600;
                                    color: #fff;
                                    background-color: #4a5568;
                                    border: 1px solid transparent;
                                    border-radius: 0.375rem;
                                    cursor: pointer;
                                    transition: background-color 0.15s ease-in-out;
                                }

                                .custom-file-label:hover {
                                    background-color: #2d3748;
                                }

                                .custom-file-label:focus {
                                    outline: none;
                                    box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.5);
                                }
                            </style>

                            <form action="{{ route('quotations.uploadPdf', $quotation->id) }}" method="POST" enctype="multipart/form-data" style="display: inline-block;">
                                @csrf
                                <label for="pdf" class="custom-file-label">Scegli PDF</label>
                                <input type="file" name="pdf" id="pdf" accept="application/pdf" class="custom-file-input" required>
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">Carica PDF</button>
                            </form>
                            @endif
                        @endif
                    @endif

                    @if ($quotation->pdf_path)
                        <a href="{{ Storage::url($quotation->pdf_path) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"><i class="fas fa-file-alt mr-2"></i></a>

                    @endif
                </td>
            </tr>

        @endforeach
                        </tbody>
                    </table>
                </div>

@else
    <p>No quotations available.</p>
@endif

        </div>
    </div>
</x-app-layout>
