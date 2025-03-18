<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Utenti') }}
            </h2>
            <div>
                <a href="{{ route('users.create') }}" class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    + Aggiungi un utente
                </a>

            </div>
        </div>
    </x-slot>

    <main class="mt-6">
        <div class="py-12 ">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6 bg-white shadow p-4 rounded">

                <div class="container">

                    <table class="table-auto w-full">
                        <thead>
                        <tr class="bg-gray-100">
                            <th class="px-4 py-2">Nome</th>
                            <th>Email</th>
                            <th>Ruolo</th>
                            <th>Preventivi</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td class="border px-4 py-2">{{ $user->name }}</td>
                                <td class="border px-4 py-2">{{ $user->email }}</td>
                                <td class="border px-4 py-2">{{ $user->roles->pluck('name')->implode(', ') }}</td>
                                <td class="border px-4 py-2">
                                    {{ $user->quotations()->count() }}
                                </td>
                                <td class="border px-4 py-2">
                                    <a href="{{ route('users.edit', $user->id) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">Modifica</a>
                                    @if ($user->roles->pluck('name')->contains('Super Admin'))
                                        <p>L'utente è Super Admin</p>
                                    @else
                                        @if($user->quotations()->count()<1)
                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" enctype="multipart/form-data" style="display: inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150" onclick="return confirm('Sei sicuro di voler cancellare questo utente?')"> <i class="fas fa-trash-alt mr-2"></i> </button>
                                    </form>
                                        @else<p>L'utente ha preventivi</p>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</x-app-layout>>
