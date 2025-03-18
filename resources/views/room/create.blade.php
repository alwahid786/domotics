<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Crea ambiente') }}
            </h2>
            <div>
                <a href="{{ route('room.index') }}" class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    Torna agli ambienti
                </a>

            </div>
        </div>
    </x-slot>

    <main class="mt-6">
        <div class="py-12 ">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6 bg-white shadow p-4 rounded">

                <div class="container mx-auto px-4">
    <form action="{{ route('room.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group mb-4">
            <label class="block text-sm font-medium for="name">Name</label>
            <input type="text" name="name" id="name" class="form-control border p-2 w-full" required>
        </div>
        <div class="form-group mb-4">
            <label class="block text-sm font-medium for="description">Description</label>
            <textarea name="description" id="description" class="form-control border p-2 w-full"></textarea>
        </div>
        <div class="form-group mb-4">
            <label class="block text-sm font-medium for="image">Image</label>
            <input type="file" name="image" id="image" class="form-control border p-2 w-full">
        </div>
        <button type="submit" class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Crea</button>
    </form>
</div>
            </div>
            </div>
        </main>
    </x-app-layout>
