<?php

namespace App\Filament\Resources\Users\RelationManagers;

use App\Filament\Resources\Users\Pages\EditUser;
use App\Models\Mouvement;
use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class MouvementsRelationManager extends RelationManager
{
    protected static string $relationship = 'mouvements';
    protected static ?string $title = "User Mouvements";

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
            ]);
    }

    /**
     * Don't want the table to be diplayed on the edit page 
     * of the user, 
     */
    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return $pageClass !== EditUser::class;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Mouvement::query()->where('user_id', $this->ownerRecord->id)
                    ->with(['tool', 'mouvementable'])
            )
            ->defaultGroup('tool.reference')
            ->groups([
                Group::make('tool.reference')
                    ->label('Tool reference')
                    ->collapsible(),
                Group::make('created_at')
                    ->date()
                    ->label('Date'),
            ])
            ->columns(components: [
                TextColumn::make('tool.reference')
                    ->searchable()
                    ->label('Tool reference'),

                TextColumn::make('tool.name')
                    ->searchable()
                    ->label('Tool name'),

                TextColumn::make('mouvementable_type')
                    ->badge()
                    ->sortable()
                    ->getStateUsing(fn($record) => $record->getTypeLabel())
                    ->color(fn($record) => $record->typeColor())
                    ->label('Type'),

                TextColumn::make('mouvementable.quantity')
                    ->badge()
                    ->color("info")
                    ->label('Quantity'),

                TextColumn::make('created_at')
                    ->label('Created at')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([

            ])
            ->recordActions([

            ])
            ->toolbarActions([
                BulkActionGroup::make([

                ]),
            ]);
    }
}
