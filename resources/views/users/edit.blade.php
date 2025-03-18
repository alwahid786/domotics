<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Modifica utente') }}
            </h2>
            <div>
                <a href="{{ route('users.index') }}" class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    Torna alla lista utenti
                </a>

            </div>
        </div>
    </x-slot>

    <main class="mt-6">
        <div class="py-12 ">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6 bg-white shadow p-4 rounded">

                <div class="container mx-auto px-4">

                    <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="form-group mb-4">
                            <label class="block text-sm font-medium" for="name">Nome</label>
                            <input type="text" name="name" id="name" class="form-control border p-2 w-full" value="{{ $user->name }}" required>
                            @error('name')
                            <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group mb-4">
                            <label class="block text-sm font-medium for="email">Email</label>
                            <input type="email" name="email" id="email" class="form-control border p-2 w-full" value="{{ $user->email }}" required>
                            @error('email')
                            <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group mb-4">
                            <label class="block text-sm font-medium" for="password">Password</label>
                            <input type="password" name="password" id="password" class="form-control border p-2 w-full">
                            @error('password')
                            <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group mb-4">
                            <label class="block text-sm font-medium" for="password_confirmation">Conferma password</label>
                            <input type="password" name="confirm-password" id="confirm-password" class="form-control border p-2 w-full">
                            @error('password_confirmation')
                            <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group mb-4">
                            <label class="block text-sm font-medium" for="roles">Ruolo</label>
                            <select name="roles" id="roles" class="form-control border p-2 w-full">
                                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>User</option>
                            </select>
                            @error('roles')
                            <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Aggiorna</button>
                    </form>



                </div>
            </div>
        </div>
    </main>
</x-app-layout>>
