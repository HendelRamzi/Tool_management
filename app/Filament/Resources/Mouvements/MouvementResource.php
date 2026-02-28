<?php

namespace App\Filament\Resources\Mouvements;

use App\Enums\UserRole;
use App\Filament\Resources\Mouvements\Pages\CreateMouvement;
use App\Filament\Resources\Mouvements\Pages\EditMouvement;
use App\Filament\Resources\Mouvements\Pages\ListMouvements;
use App\Filament\Resources\Mouvements\Schemas\MouvementForm;
use App\Filament\Resources\Mouvements\Schemas\MouvementInfolist;
use App\Filament\Resources\Mouvements\Tables\MouvementsTable;
use App\Models\LoanMouvement;
use App\Models\Mouvement;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;


class MouvementResource extends Resource
{
    protected static ?string $model = LoanMouvement::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::ArrowsUpDown;

    protected static ?string $recordTitleAttribute = 'id';

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (!auth()->user()->hasRole(UserRole::super_admin)) {
            $query->where('user_id', auth()->user()->id)
                ->with(['tool', 'mouvementable']);
        }

        return $query;
    }

    public static function form(Schema $schema): Schema
    {
        return MouvementForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MouvementsTable::configure($table);
    }

    public static function infolist(Schema $schema): Schema
    {
        return MouvementInfolist::configure($schema);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMouvements::route('/'),
            'create' => CreateMouvement::route('/create'),
            // 'edit' => EditMouvement::route('/{record}/edit'),
        ];
    }
}
