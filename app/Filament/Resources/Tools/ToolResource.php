<?php

namespace App\Filament\Resources\Tools;

use App\Filament\Resources\Tools\Pages\CreateTool;
use App\Filament\Resources\Tools\Pages\EditTool;
use App\Filament\Resources\Tools\Pages\ListTools;
use App\Filament\Resources\Tools\Pages\ViewTool;
use App\Filament\Resources\Tools\RelationManagers\ReturnedRelationManager;
use App\Filament\Resources\Tools\RelationManagers\TakenRelationManager;
use App\Filament\Resources\Tools\Schemas\ToolForm;
use App\Filament\Resources\Tools\Schemas\ToolInfolist;
use App\Filament\Resources\Tools\Tables\ToolsTable;
use App\Models\Tool;
use BackedEnum;
use Filament\Resources\RelationManagers\RelationGroup;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ToolResource extends Resource
{
    protected static ?string $model = Tool::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::CircleStack;

    protected static ?string $recordTitleAttribute = 'name';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withTrashed();
    }

    public static function form(Schema $schema): Schema
    {
        return ToolForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ToolInfolist::configure($schema);
    }


    public static function table(Table $table): Table
    {
        return ToolsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            TakenRelationManager::class,
            ReturnedRelationManager::class,
        ];
    }



    public static function getPages(): array
    {
        return [
            'index' => ListTools::route('/'),
            'create' => CreateTool::route('/create'),
            'view' => ViewTool::route('/{record}'),
            'edit' => EditTool::route('/{record}/edit'),
        ];
    }
}
