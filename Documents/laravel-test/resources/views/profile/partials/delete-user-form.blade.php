<section class="max-w-xl">
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Delete Account') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm.') }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.destroy') }}" class="mt-6 space-y-6">
        @csrf
        @method('delete')

        <div>
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" placeholder="{{ __('Password') }}" autocomplete="current-password" />
            <x-input-error for="password" class="mt-2" />
        </div>

        <div class="flex items-center gap-4">
            <x-secondary-button type="button" onclick="if(confirm('{{ __('Are you sure you want to delete your account?') }}')) document.querySelector('form').submit();">
                {{ __('Delete Account') }}
            </x-secondary-button>
        </div>
    </form>
</section>