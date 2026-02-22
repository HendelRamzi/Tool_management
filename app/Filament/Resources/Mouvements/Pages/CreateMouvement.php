<?php

namespace App\Filament\Resources\Mouvements\Pages;

use App\Filament\Resources\Mouvements\MouvementResource;
use App\Models\Mouvement;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateMouvement extends CreateRecord
{
    protected static string $resource = MouvementResource::class;


    protected function handleRecordCreation(array $data): Model
    {
        return Mouvement::CreateNewMouvement([
            "tool_id" => $data["tool_id"],
            "qty" => $data["qty"],
        ], $data['mouvementable_type']);
    }
}
