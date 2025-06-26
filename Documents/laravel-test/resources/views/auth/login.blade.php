<x-guest-layout>
  
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <!-- logo  -->
    <div class="text-center mb-6">
        <img src="{{ asset('images/logo.png') }}" alt="Logo InnovGest" class="mx-auto h-20">
        <h2 class="text-2xl font-bold text-gray-900 mt-2">InnovGest</h2>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

  
        <div>
            <x-input-label for="email" :value="__('forms.email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

      
        <div class="mt-4">
            <x-input-label for="password" :value="__('forms.password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- se souvenir de moi -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('forms.remember_me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                    {{ __('forms.forgot_password') }}
                </a>
            @endif

            @if (Route::has('register'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 ms-3" href="{{ route('register') }}">
                    {{ __('forms.register') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('forms.login') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
