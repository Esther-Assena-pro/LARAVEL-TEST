<x-guest-layout>
    <div class="flex justify-center mb-6">
        <img src="{{ asset('images/logo.png') }}" alt="InnovGestBooking Logo" class="h-16 w-auto" onerror="this.src='{{ asset('images/placeholder.jpg') }}'">
    </div>
    <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-200 text-center">{{ __('Créez votre compte') }}</h2>
    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400 text-center">{{ __('Rejoignez-nous pour réserver votre séjour idéal !') }}</p>

    <form method="POST" action="{{ route('register') }}" class="max-w-md mx-auto mt-10 p-6 bg-white dark:bg-gray-800 rounded shadow">
        @csrf

        
        <div>
            <x-input-label for="name" :value="__('Nom complet')" />
            <x-text-input id="name" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="email" :value="__('Adresse email')" />
            <x-text-input id="email" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

       
        <div class="mt-4">
            <x-input-label for="password" :value="__('Mot de passe')" />
            <x-text-input id="password" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

       
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirmer le mot de passe')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

       
        <div class="flex items-center justify-between mt-6">
            <a href="{{ route('login') }}" class="underline text-sm text-navText dark:text-navText-dark hover:text-sky-400 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                {{ __('Déjà inscrit ? Se connecter') }}
            </a>

            <x-primary-button class="ml-4">
                {{ __('S’inscrire') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>