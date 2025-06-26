<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark:bg-gray-900">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'InnovGestBooking') }} - Voyage avec sourire !</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
</head>
<body class="font-sans antialiased bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 transition-colors duration-300">
    <div class="min-h-screen flex flex-col">
        <nav class="bg-white dark:bg-gray-800 shadow-md fixed w-full z-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <div class="flex items-center">
                        <a href="/" class="flex-shrink-0">
                            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-8 w-auto">
                        </a>
                        @auth
                            <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                                <a href="{{ route('dashboard') }}" class="text-gray-700 dark:text-gray-300 hover:text-joy font-medium px-3 py-2 rounded-md">{{ __('Tableau de bord') }}</a>
                                <a href="{{ route('properties.index') }}" class="text-gray-700 dark:text-gray-300 hover:text-joy font-medium px-3 py-2 rounded-md">{{ __('Propriétés') }}</a>
                            </div>
                        @endauth
                    </div>
                    <div class="flex items-center space-x-4">
                        <button id="darkModeToggle" class="text-gray-500 dark:text-gray-400 hover:text-joy focus:outline-none" onclick="toggleDarkMode()">
                            <span class="sr-only">{{ __('Basculer le thème') }}</span>
                            <svg id="moon-icon" class="w-6 h-6 hidden dark:block" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                            </svg>
                            <svg id="sun-icon" class="w-6 h-6 dark:hidden" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 2a8 8 0 100 16 8 8 0 000-16zm0 14a6 6 0 110-12 6 6 0 010 12z"></path>
                            </svg>
                        </button>
                        @auth
                            <div class="relative" x-data="{ open: false }" @click.away="open = false">
                                <button 
                                    @click="open = !open"
                                    class="flex items-center space-x-2 text-gray-700 dark:text-gray-300 hover:text-joy focus:outline-none rounded-full px-3 py-2"
                                    aria-expanded="true"
                                    aria-haspopup="true"
                                    id="user-menu-button"
                                >
                                    <svg class="w-6 h-6 text-joy dark:text-gray-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A9 9 0 1112 21a9 9 0 01-6.879-3.196z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span class="font-semibold">{{ Auth::user()->name }}</span>
                                    <svg class="w-4 h-4 text-joy dark:text-gray-300 transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" :class="{ 'rotate-180': open }">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>

                                <div 
                                    x-show="open" 
                                    class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg py-1 z-50"
                                    role="menu"
                                    aria-orientation="vertical"
                                    aria-labelledby="user-menu-button"
                                    x-transition:enter="transition ease-out duration-100"
                                    x-transition:enter-start="transform opacity-0 scale-95"
                                    x-transition:enter-end="transform opacity-100 scale-100"
                                    x-transition:leave="transition ease-in duration-75"
                                    x-transition:leave-start="transform opacity-100 scale-100"
                                    x-transition:leave-end="transform opacity-0 scale-95"
                                >
                                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 text-joy hover:text-blue-500" role="menuitem">{{ __('Profil') }}</a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 text-joy hover:text-blue-500" role="menuitem">{{ __('Déconnexion') }}</button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="text-gray-700 dark:text-gray-300 hover:text-joy font-medium px-3 py-2 rounded-md">{{ __('Connexion') }}</a>
                            <a href="{{ route('register') }}" class="bg-joy text-white font-semibold py-2 px-4 rounded-md hover:bg-blue-600 transition-colors duration-200">{{ __('Inscription') }}</a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        @if (isset($header))
            <header class="bg-white dark:bg-gray-800 shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <main class="pt-16 flex-grow">
            {{ $slot ?? '' }}
        </main>

        @if(!isset($footerRendered))
            <x-footer />
            <?php $footerRendered = true; ?>
        @endif

        @livewireScripts
        <script>
            function toggleDarkMode() {
                document.documentElement.classList.toggle('dark');
                localStorage.theme = document.documentElement.classList.contains('dark') ? 'dark' : 'light';
                document.getElementById('moon-icon').classList.toggle('hidden');
                document.getElementById('sun-icon').classList.toggle('hidden');
            }

// Initialisation de Flatpickr uniquement pour la gestion de minDate
            document.addEventListener('DOMContentLoaded', () => {
                flatpickr(".datepicker", {
                    minDate: "today",
                    dateFormat: "Y-m-d",
                    locale: 'fr',
                    onChange: function(selectedDates, dateStr, instance) {
                        const startDateInput = document.getElementById('start-date');
                        const endDateInput = document.getElementById('end-date');
                        if (instance.element.id === 'start-date' && selectedDates.length > 0) {
                            endDateInput._flatpickr.set('minDate', dateStr);
                        }
                    }
                });

// Vérification initiale du thème
                if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                    document.documentElement.classList.add('dark');
                    document.getElementById('moon-icon').classList.remove('hidden');
                    document.getElementById('sun-icon').classList.add('hidden');
                } else {
                    document.documentElement.classList.remove('dark');
                    document.getElementById('moon-icon').classList.add('hidden');
                    document.getElementById('sun-icon').classList.remove('hidden');
                }
            });
        </script>
        @stack('scripts')
    </div>
</body>
</html>