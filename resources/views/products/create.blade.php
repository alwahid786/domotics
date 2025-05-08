<x-app-layout>
    <style>
        .custom-select-wrapper {
            position: relative;
            width: 100%;
        }

        .options-list {
            display: none;
            border: 1px solid #ccc;
            background: white;
            position: absolute;
            z-index: 100;
            max-height: 200px;
            overflow-y: auto;
            width: 100%;
        }

        .options-list.show {
            display: block;
        }

        .custom-select {
            border: 1px solid #ccc;
            border-radius: 4px;
            padding: 8px;
            cursor: pointer;
            min-height: 46px;
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
            background-color: white;
        }

        .custom-select:after {
            content: '▼';
            position: absolute;
            right: 10px;
            top: 14px;
            pointer-events: none;
            font-size: 12px;
        }

        .custom-select.open:after {
            content: '▲';
        }

        .badge-role {
            background-color: #007bff;
            color: white;
            padding: 5px 10px;
            border-radius: 16px;
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .badge-role .remove {
            cursor: pointer;
            font-weight: bold;
            font-size: 16px;
        }

        .options-list {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            border: 1px solid #ccc;
            border-top: none;
            background-color: white;
            z-index: 1000;
            display: none;
            max-height: 200px;
            overflow-y: auto;
        }

        .options-list div {
            padding: 10px;
            cursor: pointer;
        }

        .options-list div:hover {
            background-color: #f0f0f0;
        }

        .custom-select.open+.options-list {
            display: block;
        }

        .price-input {
            width: 100px;
            margin-left: 5px;
            padding: 2px 6px;
            border-radius: 4px;
            border: 1px solid #ccc;
            font-size: 12px;
        }
    </style>
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
                    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
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
                            <textarea name="description" id="description" class="form-control border p-2 w-full">{{ old('description') }}</textarea>
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
                            <div class="custom-select-wrapper">
                                <div id="roomSelect" class="custom-select"></div>
                                <div id="roomOptions" class="options-list">
                                    @foreach ($rooms as $room)
                                        <div data-id="{{ $room->id }}" data-name="{{ $room->name }}">
                                            {{ $room->name }}</div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="roles">Roles and Prices</label>
                            @foreach ($roles as $role)
                                <div class="row mb-3">
                                    <div class="col">
                                        <input type="checkbox" name="roles[]" value="{{ $role->id }}"
                                            {{ in_array($role->id, old('roles', [])) ? 'checked' : '' }}>
                                        {{ $role->name }}
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
                            <button type="submit"
                                class="nline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">Crea
                                Prodoto</button>

                        </div>
                    </form>
                </div>

                <!-- Include jQuery (required for Select2) -->
                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

                <!-- Include Select2 JS and CSS -->
                <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet" />
                <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>

                <!-- Initialize Select2 on the Room select -->
                <script>
                    $(document).ready(function() {
                        $('#rooms').select2({
                            placeholder: "Select Room(s)",
                            allowClear: true
                        });
                    });
                </script>


            </div>
        </div>
    </main>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const roomSelect = document.getElementById("roomSelect");
            const roomOptions = document.getElementById("roomOptions");
            const selectedRooms = new Set();

            // Add "Select All" control at the top of options
            const selectAllOption = document.createElement("div");
            selectAllOption.textContent = "Select All";
            selectAllOption.classList.add("select-all");
            selectAllOption.style.cursor = "pointer";
            selectAllOption.style.fontWeight = "bold";
            selectAllOption.style.borderBottom = "1px solid #ccc";
            selectAllOption.style.paddingBottom = "4px";
            roomOptions.insertBefore(selectAllOption, roomOptions.firstChild);

            selectAllOption.addEventListener("click", () => {
                const allOptions = roomOptions.querySelectorAll("div[data-id]");
                const allSelected = [...allOptions].every(opt =>
                    selectedRooms.has(opt.getAttribute("data-id"))
                );

                allOptions.forEach(opt => {
                    const id = opt.getAttribute("data-id");
                    const name = opt.getAttribute("data-name");

                    if (allSelected) {
                        // Deselect all
                        selectedRooms.delete(id);
                        removeRoomBadge(id);
                        unhighlightOption(id);
                    } else {
                        // Select all
                        if (!selectedRooms.has(id)) {
                            selectedRooms.add(id);
                            addRoomBadge(id, name);
                            highlightOption(id);
                        }
                    }
                });

                selectAllOption.textContent = allSelected ? "Select All" : "Deselect All";
            });

            // Toggle dropdown
            roomSelect.addEventListener("click", () => {
                roomSelect.classList.toggle("open");
                roomOptions.classList.toggle("show");
            });

            // Handle click on individual options
            roomOptions.addEventListener("click", (e) => {
                const option = e.target;
                const id = option.getAttribute("data-id");
                const name = option.getAttribute("data-name");

                if (!id || !name) return;

                if (selectedRooms.has(id)) {
                    selectedRooms.delete(id);
                    removeRoomBadge(id);
                    unhighlightOption(id);
                } else {
                    selectedRooms.add(id);
                    addRoomBadge(id, name);
                    highlightOption(id);
                }

                updateSelectAllLabel();
            });

            function addRoomBadge(id, name) {
                if (roomSelect.querySelector(`.badge-room[data-id="${id}"]`)) return;

                const badge = document.createElement("div");
                badge.className = "badge-room";
                badge.setAttribute("data-id", id);

                badge.innerHTML = `
                ${name}
                <input type="hidden" name="rooms[]" value="${id}">
                <span class="remove">&nbsp ,</span>
            `;

                badge.querySelector(".remove").addEventListener("click", () => {
                    badge.remove();
                    selectedRooms.delete(id);
                    unhighlightOption(id);
                    updateSelectAllLabel();
                });

                roomSelect.insertBefore(badge, roomSelect.firstChild);
            }

            function removeRoomBadge(id) {
                const badge = roomSelect.querySelector(`.badge-room[data-id="${id}"]`);
                if (badge) badge.remove();
            }

            function highlightOption(id) {
                const option = roomOptions.querySelector(`div[data-id="${id}"]`);
                if (option) {
                    option.style.backgroundColor = "#BC3136";
                    option.style.color = "#fff";
                }
            }

            function unhighlightOption(id) {
                const option = roomOptions.querySelector(`div[data-id="${id}"]`);
                if (option) {
                    option.style.backgroundColor = "";
                    option.style.color = "";
                }
            }

            function updateSelectAllLabel() {
                const allOptions = roomOptions.querySelectorAll("div[data-id]");
                const allSelected = [...allOptions].every(opt =>
                    selectedRooms.has(opt.getAttribute("data-id"))
                );
                selectAllOption.textContent = allSelected ? "Deselect All" : "Select All";
            }

            // Close dropdown on outside click
            document.addEventListener("click", function(e) {
                if (!roomSelect.contains(e.target) && !roomOptions.contains(e.target)) {
                    roomSelect.classList.remove("open");
                    roomOptions.classList.remove("show");
                }
            });
        });
    </script>



</x-app-layout>
