<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Aggiungi nuovo prodotto') }}
            </h2>
            <div>
                <a href="{{ route('products.create') }}"
                   class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    Stai aggiungendo un prodotto
                </a>
            </div>
        </div>
    </x-slot>


    <!-- Search and Filter -->
    <main class="mt-6">
        <div class="py-12 ">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6 bg-white shadow p-4 rounded">
                <div class="container mx-auto px-4">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <!-- Form to create the product -->
                    <form action="{{route('products.store')}}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Product Name -->
                        <div class="form-group mb-4">
                            <label for="name" class="block text-sm font-medium">Product Name:</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}"
                                   class="form-control border p-2 w-full" required>
                            @error('name')
                            <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>


                        <div class="form-group mb-4">
                            <label for="name" class="block text-sm font-medium">Product Code:</label>
                            <input type="text" name="code" id="code" value="{{ old('code') }}"
                                   class="form-control border p-2 w-full" required>
                            @error('name')
                            <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="form-group mb-4">
                            <label for="description" class="block text-sm font-medium">Description:</label>
                            <textarea name="description" id="description"
                                      class="form-control border p-2 w-full">{{ old('description') }}</textarea>
                            @error('description')
                            <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div class="form-group mb-4">
                            <label for="status" class="block text-sm font-medium">Status:</label>
                            <select name="status" id="status" class="form-control border p-2 w-full" required>
                                <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')
                            <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>


                        <!-- Image Upload -->
                        <div class="form-group mb-4">
                            <label for="image" class="block text-sm font-medium">Product Image:</label>
                            <input type="file" name="image" id="image" class="form-control border p-2 w-full">
                            @error('image')
                            <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Room Selection (Select2) -->


                        <div class="form-group">
                            <label for="rooms">Scegli ambienti:</label>
                            <select name="rooms[]" class="form-control" multiple required>
                                @foreach($rooms as $room)
                                    <option
                                        value="{{ $room->id }}" {{ in_array($room->id, old('rooms', [])) ? 'selected' : '' }}>{{ $room->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="roles">Roles and Prices</label>
                            @foreach($roles as $role)
                                <div class="row mb-3">
                                    <div class="col">
                                        <input type="checkbox" name="roles[]"
                                               value="{{ $role->id }}" {{ in_array($role->id, old('roles', [])) ? 'checked' : '' }}> {{ $role->name }}
                                    </div>
                                    <div class="col">
                                        <input type="text" name="prices[]" class="form-control"
                                               placeholder="Enter price for {{ $role->name }}"
                                               value="{{ old('prices')[$loop->index] ?? '' }}" required>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Submit Button -->
                        <div class="form-group">
                            <button type="submit" class="nline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">Crea Prodoto</button>

                        </div>
                    </form>
                </div>

                <!-- Include jQuery (required for Select2) -->
                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

                <!-- Include Select2 JS and CSS -->
                <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet"/>
                <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>

                <!-- Initialize Select2 on the Room select -->
                <script>
                    $(document).ready(function () {
                        $('#rooms').select2({
                            placeholder: "Select Room(s)",
                            allowClear: true
                        });
                    });
                </script>


            </div>
        </div>
    </main>
</x-app-layout>
