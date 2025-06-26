<footer class="bg-gray-800 dark:bg-gray-900 text-white mt-auto py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div>
                <h3 class="text-lg font-semibold text-gray-100 mb-4">{{ __('À propos d\'InnovGestBooking') }}</h3>
                <p class="text-sm text-gray-400">
                    {{ __('InnovGestBooking offre des locations de vacances uniques en France. Trouvez votre séjour idéal, du chalet alpin à la villa en bord de mer.') }}
                </p>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-100 mb-4">{{ __('Navigation') }}</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('properties.index') }}" class="text-gray-400 hover:text-blue-400">{{ __('Propriétés') }}</a></li>
                    <li><a href="{{ route('dashboard') }}" class="text-gray-400 hover:text-blue-400">{{ __('Tableau de bord') }}</a></li>
                    <li><a href="{{ route('profile.edit') }}" class="text-gray-400 hover:text-blue-400">{{ __('Profil') }}</a></li>
                    @auth
                        <li>
                            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="text-gray-400 hover:text-blue-400">{{ __('Déconnexion') }}</a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                                @csrf
                            </form>
                        </li>
                    @else
                        <li><a href="{{ route('login') }}" class="text-gray-400 hover:text-blue-400">{{ __('Connexion') }}</a></li>
                        <li><a href="{{ route('register') }}" class="text-gray-400 hover:text-blue-400">{{ __('Inscription') }}</a></li>
                    @endauth
                </ul>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-100 mb-4">{{ __('Contact') }}</h3>
                <ul class="space-y-2 text-sm">
                    <li>Email: <a href="mailto:contact@innovgestbooking.com" class="text-gray-400 hover:text-blue-400">contact@innovgestbooking.com</a></li>
                    <li>Téléphone: <span class="text-gray-400">+33 1 23 45 67 89</span></li>
                    <li>Adresse: <span class="text-gray-400">25 Rue Paul Xavier, 69007 Lyon, France</span></li>
                </ul>
            </div>
        </div>
        <div class="mt-8 border-t border-gray-700 pt-4 text-center">
            <p class="text-sm text-gray-500">{{ __('© ') . date('Y') . ' InnovGestBooking. Tous droits réservés.' }}</p>
        </div>
    </div>
</footer>