<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Modifica prodotto') }} {{ old('name', $product->name) }}
            </h2>
            <div>
                <a href="{{ route('products.index') }}"
                   class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    Torna ai prodotti
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
                    <!-- Form to edit the product -->
                    <form action="{{ route('products.update', $product->id) }}" method="POST"
                          enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Product Name -->
                        <div class="form-group mb-4">
                            <label for="name" class="block text-sm font-medium">Product Name:</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}"
                                   class="form-control border p-2 w-full" required>
                            @error('name')
                            <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-4">
                            <label for="name" class="block text-sm font-medium">Product Code:</label>
                            <input type="text" name="code" id="code" value="{{ old('code', $product->code) }}"
                                   class="form-control border p-2 w-full" required>
                            @error('name')
                            <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="form-group mb-4">
                            <label for="description" class="block text-sm font-medium">Description:</label>
                            <textarea name="description" id="description"
                                      class="form-control border p-2 w-full">{{ old('description', $product->description) }}</textarea>
                            @error('description')
                            <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div class="form-group mb-4">
                            <label for="status" class="block text-sm font-medium">Status:</label>
                            <select name="status" id="status" class="form-control border p-2 w-full" required>
                                <option
                                    value="active" {{ old('status', $product->status) == 'active' ? 'selected' : '' }}>
                                    Active
                                </option>
                                <option
                                    value="inactive" {{ old('status', $product->status) == 'inactive' ? 'selected' : '' }}>
                                    Inactive
                                </option>
                            </select>
                            @error('status')
                            <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Room Selection (Select2) -->
                        <div class="form-group mb-4">
                            <label for="rooms" class="block text-sm font-medium">Scegli ambienti:</label>
                            <select name="rooms[]" id="rooms" class="form-control border p-2 w-full" multiple="multiple"
                                    required>
                                @foreach($rooms as $room)
                                    <option
                                        value="{{ $room->id }}" {{ in_array($room->id, old('rooms', $product->rooms->pluck('id')->toArray())) ? 'selected' : '' }}>
                                        {{ $room->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('rooms')
                            <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Role Prices -->
                        <div class="form-group mb-4">
                            <label for="roles" class="block text-sm font-medium">Role Prices:</label>
                            @foreach($roles as $role)
                                <div class="mb-2">
                                    <label for="role_{{ $role->id }}"
                                           class="block text-sm font-medium">{{ $role->name }} Price:</label>
                                    <input type="number" name="roles[{{ $role->id }}]" id="role_{{ $role->id }}"
                                           value="{{ old('roles.' . $role->id, $product->roles->find($role->id)->pivot->price ?? '') }}"
                                           class="form-control border p-2 w-full">
                                    @error('roles.' . $role->id)
                                    <span class="text-red-500">{{ $message }}</span>
                                    @enderror
                                </div>
                            @endforeach
                        </div>

                        <!-- Image Upload -->
                        <div class="form-group mb-4">
                            <label for="image" class="block text-sm font-medium">Product Image:</label>
                            <input type="file" name="image" id="image" class="form-control border p-2 w-full">

                            <!-- Show current image if exists -->
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="Product Image"
                                     class="w-24 h-24 mt-2">
                            @endif
                            @error('image')
                            <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="form-group">
                            <button type="submit" class="nline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">Aggiorna Prodotto</button>
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
