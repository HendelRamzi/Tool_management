<?php

namespace App\Services;


use App\Models\Mouvement;
use App\Models\LoanMouvement;
use App\Models\ReturnMouvement;
use App\Models\Tool;
use Illuminate\Support\Collection;

class MouvementService
{
    /**
     * Calculer la quantité restante d'un outil pour un utilisateur
     */
    public static function remainingQuantity(int $toolId, int $userId): int
    {
        $loaned = LoanMouvement::query()
            ->where('tool_id', $toolId)
            ->whereHas(
                'mouvement',
                fn($q) =>
                $q->where('user_id', $userId)
            )
            ->sum('quantity');

        $returned = ReturnMouvement::query()
            ->where('tool_id', $toolId)
            ->whereHas(
                'mouvement',
                fn($q) =>
                $q->where('user_id', $userId)
            )
            ->sum('quantity');

        return max($loaned - $returned, 0);
    }

    /**
     * Récupérer tous les tools empruntés et non rendus par un utilisateur
     */
    public static function borrowedToolsForUser(int $userId): Collection
    {
        $toolIds = Mouvement::query()
            ->where('user_id', $userId)
            ->where('mouvementable_type', LoanMouvement::class)
            ->pluck('tool_id')
            ->unique();

        // Filtrer seulement ceux avec quantité restante > 0
        $validToolIds = $toolIds->filter(function ($toolId) use ($userId) {
            return self::remainingQuantity($toolId, $userId) > 0;
        });

        return Tool::whereIn('id', $validToolIds)->get();
    }
}