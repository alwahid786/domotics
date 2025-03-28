<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Stime') }}
            </h2>
            <div>
                {{-- <a href="{{ route('estimations.create') }}" class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            + Aggiungi una nuova stima
        </a> --}}
            </div>
        </div>
    </x-slot>
    <input type="hidden" id="estimate" value="{{ $estimate }}">

    <!-- Search and Filter -->
    <main class="mt-6">
        <div class="py-12 ">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6 bg-white shadow p-4 rounded">

            </div>
        </div>
    </main>
</x-app-layout>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        fetchEstimations();
    });

    function fetchEstimations() {
        var estimateInput = document.getElementById("estimate");
        var estimate = estimateInput ? estimateInput.value : "";

        fetch("/estimations/fetch?estimate=" + encodeURIComponent(estimate))
            .then(response => response.json())
            .then(data => {
                console.log(data); // Debugging response in the console
                updateTable(data);
            })
            .catch(error => console.error("Error fetching estimations:", error));
    }
</script>
