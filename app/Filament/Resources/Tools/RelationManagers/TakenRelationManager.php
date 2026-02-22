<?php

namespace App\Filament\Resources\Tools\RelationManagers;

use App\Enums\ToolStatus;
use App\Enums\UserRole;
use App\Filament\Resources\Mouvements\Schemas\MouvementInfolist;
use App\Filament\Resources\Tools\Actions\CreateNewLoanAction;
use App\Filament\Resources\Tools\Actions\ToolDeleteAction;
use App\Filament\Resources\Tools\Pages\EditTool;
use App\Models\LoanMouvement;
use Filament\Actions\ActionGroup;
use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use function Laravel\Prompts\search;

class TakenRelationManager extends RelationManager
{
    protected static string $relationship = 'taken';
    protected static ?string $title = 'Tools taken ';

    public function getTableQuery(): Builder
    {
        $query = LoanMouvement::query();

        if (!auth()->user()->hasRole(UserRole::super_admin)) {
            $query->whereHas('mouvement', function (Builder $q) {
                $q->where('user_id', auth()->id())
                    ->where('tool_id', $this->ownerRecord->id);
            });
        }

        return $query;
    }


    /**
     * Don't want the table to be diplayed on the edit page 
     * of the user, 
     */
    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return $pageClass !== EditTool::class;
    }

    // If the record if soft delete activate the read only mode.
    public function isReadOnly(): bool
    {
        return false;
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public function infolist(Schema $schema): Schema
    {
        return MouvementInfolist::configure($schema);
    }


    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('tool.name')
            ->columns([
                TextColumn::make('mouvement.user.name')
                    ->label('Username')
                    ->searchable(),
                TextColumn::make('tool.reference')
                    ->label('Reference'),
                TextColumn::make('quantity')
                    ->badge()
                    ->color('info'),
                TextColumn::make('created_at')
                    ->sortable()
                    ->searchable()
                    ->dateTime('d/m/Y'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateNewLoanAction::make(true, $this->ownerRecord)
                    ->visible(fn() => $this->ownerRecord->status !== ToolStatus::Archived),
            ])
            ->recordActions([
                ActionGroup::make([
                    EditAction::make(),
                    ViewAction::make(),
                    ToolDeleteAction::make(true),
                ])->color("secondary")
                    ->visible(fn() => $this->ownerRecord->status !== ToolStatus::Archived)

            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //See later what can I do here.
                ]),
            ]);
    }
}
