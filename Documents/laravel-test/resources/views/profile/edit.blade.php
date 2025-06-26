<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Profil') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @if (session('status'))
                        <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
                        @csrf
                        @method('patch')
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Nom') }}</label>
                            <input id="name" name="name" type="text" class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md" value="{{ old('name', $user->name) }}" required autofocus>
                            @error('name') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Email') }}</label>
                            <input id="email" name="email" type="email" class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md" value="{{ old('email', $user->email) }}" required>
                            @error('email') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <button type="submit" class="bg-blue-600 dark:bg-blue-700 text-white py-2 px-4 rounded-md hover:bg-blue-700 dark:hover:bg-blue-600">{{ __('Sauvegarder') }}</button>
                    </form>
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @if (session('status') === 'Mot de passe mis à jour avec succès !')
                        <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
                            {{ session('status') }}
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="mb-4">
                            @foreach ($errors->all() as $error)
                                <span class="text-red-600 text-xs">{{ $error }}</span><br>
                            @endforeach
                        </div>
                    @endif

                    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
                        @csrf
                        @method('put')
                        <div>
                            <label for="current_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Mot de passe actuel') }}</label>
                            <input id="current_password" name="current_password" type="password" class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md" required>
                            @error('current_password') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Nouveau mot de passe') }}</label>
                            <input id="password" name="password" type="password" class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md" required>
                            @error('password') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Confirmer le nouveau mot de passe') }}</label>
                            <input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md" required>
                            @error('password_confirmation') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <button type="submit" class="bg-blue-600 dark:bg-blue-700 text-white py-2 px-4 rounded-md hover:bg-blue-700 dark:hover:bg-blue-600">{{ __('Mettre à jour le mot de passe') }}</button>
                    </form>
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Une fois votre compte supprimé, tous vos ressources et données seront définitivement supprimées. Avant de supprimer votre compte, veuillez télécharger toutes les données ou informations que vous souhaitez conserver.') }}</p>

                    <form method="post" action="{{ route('profile.destroy') }}" class="mt-6 space-y-6">
                        @csrf
                        @method('delete')
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Mot de passe') }}</label>
                            <input id="password" name="password" type="password" class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md" required>
                            @error('password') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <button type="submit" class="bg-red-600 dark:bg-red-700 text-white py-2 px-4 rounded-md hover:bg-red-700 dark:hover:bg-red-600">{{ __('Supprimer le compte') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>