<?php

namespace App\Filament\Esther\Resources;

use App\Filament\Esther\Resources\BookingResource\Pages;
use App\Models\Booking;
use App\Models\Property;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class BookingResource extends Resource
{
    protected static ?string $model = Booking::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationLabel = 'Réservations';

    protected static ?string $pluralModelLabel = 'Réservations';

    protected static ?string $modelLabel = 'Réservation';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label("Utilisateur")
                    ->relationship('user', 'name')
                    ->searchable()
                    ->required()
                    ->default(auth()->id()),

                Forms\Components\Select::make('property_id')
                    ->label("Propriété")
                    ->relationship('property', 'name')
                    ->searchable()
                    ->required()
                    ->live()
                    ->afterStateUpdated(function (Forms\Set $set, $state, Forms\Get $get) {
                        static::updateTotalPrice($set, $get);
                        static::checkConflict($set, $get);
                    }),

                Forms\Components\DatePicker::make('start_date')
                    ->label('Date de début')
                    ->required()
                    ->minDate(now())
                    ->live()
                    ->afterStateUpdated(function (Forms\Set $set, $state, Forms\Get $get) {
                        static::updateTotalPrice($set, $get);
                        static::checkConflict($set, $get);
                    }),

                Forms\Components\DatePicker::make('end_date')
                    ->label('Date de fin')
                    ->required()
                    ->minDate(fn (Forms\Get $get) => $get('start_date') ?? now())
                    ->live()
                    ->afterStateUpdated(function (Forms\Set $set, $state, Forms\Get $get) {
                        static::updateTotalPrice($set, $get);
                        static::checkConflict($set, $get);
                    }),

                Forms\Components\TextInput::make('total_price')
                    ->label('Prix total (€)')
                    ->numeric()
                    ->prefix('€')
                    ->required()
                    ->disabled()
                    ->dehydrated()
                    ->default(0)
                    ->reactive()
                    ->afterStateHydrated(function (Forms\Set $set, $state, Forms\Get $get) {
                        static::updateTotalPrice($set, $get);
                    }),

                Forms\Components\Hidden::make('conflict_error'),
                Forms\Components\Placeholder::make('conflict_error_display')
                    ->label(false) // Supprime l'étiquette "Conflict error display"
                    ->content(function (Forms\Get $get) {
                        $error = $get('conflict_error');
                        return $error ? $error : null; // Texte brut uniquement si erreur
                    })
                    ->visible(fn (Forms\Get $get) => (bool)$get('conflict_error')) // Affiche uniquement si conflict_error est défini
                    ->extraAttributes(['class' => 'text-danger']), // Applique la classe pour le rouge
            ])
            ->statePath('data')
            ->model(Booking::class);
    }

    // Méthode pour calculer le prix total
    public static function updateTotalPrice(Forms\Set $set, Forms\Get $get)
    {
        $property = Property::find($get('property_id'));
        $startDate = $get('start_date');
        $endDate = $get('end_date');

        if ($property && $startDate && $endDate) {
            $start = new \DateTime($startDate);
            $end = new \DateTime($endDate);
            $nights = max(1, $start->diff($end)->days);
            $set('total_price', $property->price_per_night * $nights);
        } else {
            $set('total_price', 0);
        }
    }

    // Méthode pour vérifier les conflits
    public static function checkConflict(Forms\Set $set, Forms\Get $get)
    {
        $propertyId = $get('property_id');
        $startDate = $get('start_date');
        $endDate = $get('end_date');

        if ($propertyId && $startDate && $endDate) {
            $conflict = Booking::where('property_id', $propertyId)
                ->where(function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('start_date', [$startDate, $endDate])
                        ->orWhereBetween('end_date', [$startDate, $endDate])
                        ->orWhere(function ($query) use ($startDate, $endDate) {
                            $query->where('start_date', '<', $startDate)
                                ->where('end_date', '>', $endDate);
                        });
                })->exists();

            $set('conflict_error', $conflict ? 'Cette propriété est déjà réservée pour ces dates.' : null);
        }
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')->label('Utilisateur'),
                Tables\Columns\TextColumn::make('property.name')->label('Propriété'),
                Tables\Columns\TextColumn::make('start_date')->date('d M Y')->label('Début'),
                Tables\Columns\TextColumn::make('end_date')->date('d M Y')->label('Fin'),
                Tables\Columns\TextColumn::make('total_price')->money('eur')->label('Total'),
                Tables\Columns\TextColumn::make('created_at')->label('Réservé le')->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBookings::route('/'),
            'create' => Pages\CreateBooking::route('/create'),
            'edit' => Pages\EditBooking::route('/{record}/edit'),
        ];
    }
}