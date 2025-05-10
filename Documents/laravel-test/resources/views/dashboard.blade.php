<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tableau de Bord
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <x-property-card
                    title="Villa Prestige "
                    description="Une villa exclusive avec vue sur mer, proposée par Movlix."
                    price="150"
                    image="{{ asset('images/villa-prestige.jpg') }}"
                >
                    <x-button>Réserver</x-button>
                </x-property-card>

                <x-property-card
                    title="Appartement Urbain"
                    description="Un appartement moderne au cœur de la ville, signé Movlix."
                    price="80"
                    image="{{ asset('images/appart-urbain.jpg') }}"
                >
                    <x-button>Réserver</x-button>
                </x-property-card>

                <x-property-card
                    title="Chalet Alpin"
                    description="Un chalet chaleureux pour vos vacances d'hiver, par Movlix."
                    price="120"
                    image="{{ asset('images/chalet-alpin.jpg') }}"
                >
                    <x-button>Réserver</x-button>
                </x-property-card>
            </div>
        </div>
    </div>
</x-app-layout>