<?php

namespace App\Filament\Resources\Tools\RelationManagers;


use App\Enums\ToolStatus;
use App\Enums\UserRole;
use App\Filament\Resources\Mouvements\Schemas\MouvementInfolist;
use App\Filament\Resources\Mouvements\Tables\MouvementsTable;
use App\Filament\Resources\Tools\Actions\CreateNewLoanAction;
use App\Filament\Resources\Tools\Actions\CreateNewReturnAction;
use App\Filament\Resources\Tools\Pages\EditTool;
use App\Models\Mouvement;
use App\Services\MouvementService;
use Filament\Actions\ActionGroup;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class MouvementRelationManager extends RelationManager
{
    protected static string $relationship = 'mouvements';
    protected static ?string $title = 'Tool mouvements ';

    public function getTableQuery(): Builder
    {
        $query = Mouvement::query()->where('tool_id', $this->ownerRecord->id);

        if (!auth()->user()->hasRole(UserRole::super_admin)) {
            $query->where('user_id', auth()->id())
                ->where('tool_id', $this->ownerRecord->id);
        }

        return $query;
    }


    /**
     * Don't want the table to be diplayed on the edit page 
     */
    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return $pageClass !== EditTool::class;
    }

    // If the record if soft delete activate the read only mode.
    public function isReadOnly(): bool
    {
        return is_null($this->ownerRecord->deleted_at) ? false : true ;
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
        return MouvementsTable::configure($table)
            ->headerActions([
                ActionGroup::make([
                    CreateNewLoanAction::make(true, $this->ownerRecord)
                        ->hidden(fn() => $this->ownerRecord->status == ToolStatus::Archived || $this->ownerRecord->status == ToolStatus::NoDisponible || $this->ownerRecord->qty < 0),
                    CreateNewReturnAction::make(true, $this->ownerRecord)

                        ->hidden(fn() => MouvementService::remainingQuantity($this->ownerRecord->id, auth()->id()) <= 0)
                        ->visible(fn() => $this->ownerRecord->status !== ToolStatus::Archived),
                ])->label('Mouvement actions')
                    ->button()
                    ->outlined()
                    ->icon(Heroicon::ChevronDown)
                    ->color(""),

            ]);
    }

}
