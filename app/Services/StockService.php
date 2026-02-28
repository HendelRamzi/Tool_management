<?php

namespace App\Services;

use App\Enums\UserRole;
use App\Models\InwardMouvement;
use App\Models\LoanMouvement;
use App\Models\ReturnMouvement;
use App\Models\Tool;
use DB;

class StockService
{

    public static function takeTool(int $toolId, int $qty): LoanMouvement
    {
        return DB::transaction(function () use ($toolId, $qty) {

            if ($qty <= 0) {
                throw new \Exception("Quantity must be greater than zero.");
            }

            // Lock the row to prevent race condition
            $tool = Tool::lockForUpdate()->findOrFail($toolId);

            if ($tool->available_quantity < $qty) {
                throw new \Exception("Not enough stock available.");
            }

            // Create loan
            $loan = LoanMouvement::create([
                'tool_id' => $tool->id,
                'user_id' => auth()->id(),
                'quantity' => $qty,
                'remaining_quantity' => $qty,
            ]);

            // Decrease available stock
            $tool->decrement('available_quantity', $qty);
            $tool->qtyStatusHandling();
            return $loan;
        });
    }


    public static function returnTool(int $toolId, int $qty): void
    {
        DB::transaction(function () use ($toolId, $qty) {

            if ($qty <= 0) {
                throw new \Exception("Quantity must be greater than zero.");
            }

            // Lock loans to avoid race condition
            $loans = self::getActiveLoans($toolId, auth()->user()->id);

            $totalRemaining = $loans->sum('remaining_quantity');

            if ($totalRemaining < $qty) {
                throw new \Exception("Return quantity exceeds borrowed quantity.");
            }

            $remainingToReturn = $qty;

            foreach ($loans as $loan) {

                if ($remainingToReturn <= 0) {
                    break;
                }

                $deduct = min($loan->remaining_quantity, $remainingToReturn);

                // Create return history
                self::createReturnRecord($loan, $deduct, $toolId);

                // Handle the Update
                self::updateLoan($loan, $deduct);


                $remainingToReturn -= $deduct;
            }

            // Increase available stock
            $tool = Tool::where('id', $toolId)
                ->lockForUpdate();
            $tool->increment('available_quantity', $qty);
            $tool->qtyStatusHandling(); //Probleme: Call to undefined method Illuminate\Database\Eloquent\Builder::qtyStatusHandling()
        });
    }


    public static function addStock(int $toolId, int $qty, string $note = null): void
    {
        DB::transaction(function () use ($toolId, $qty) {

            if ($qty <= 0) {
                throw new \Exception("Quantity must be greater than zero.");
            }

            $user = auth()->user();

            if (!$user || !$user->hasRole(UserRole::super_admin)) {
                throw new \Exception("Unauthorized action.");
            }

            // Lock tool to prevent race conditions
            $tool = Tool::lockForUpdate()->findOrFail($toolId);

            // Create inward history
            InwardMouvement::create([
                'tool_id' => $toolId,
                'user_id' => $user->id,
                'quantity' => $qty,
            ]);

            // Increase available stock
            $tool->increment('available_quantity', $qty);
            $tool->qtyStatusHandling();
        });
    }


    // I'll implement the quantity management here for now, but I can move it to a service class if needed
    public static function QuantityManager($tool_id, $qty, $operation)
    {
        return DB::transaction(function () use ($tool_id, $qty, $operation) {

            $tool = Tool::lockForUpdate()->findOrFail($tool_id);

            if ($qty <= 0) {
                throw new \Exception("Quantity must be greater than zero.");
            }

            switch ($operation) {

                case LoanMouvement::class:

                    if ($tool->available_quantity < $qty) {
                        throw new \Exception("Not enough quantity available.");
                    }

                    $tool->decrement('available_quantity', $qty);
                    break;


                case ReturnMouvement::class:

                    if (($tool->available_quantity + $qty) > $tool->total_quantity) {
                        throw new \Exception("Return quantity exceeds total stock.");
                    }

                    $tool->increment('available_quantity', $qty);
                    break;


                case InwardMouvement::class:

                    $tool->increment('total_quantity', $qty);
                    $tool->increment('available_quantity', $qty);
                    break;


                default:
                    throw new \Exception("Invalid operation type.");
            }

            $tool->qtyStatusHandling();
            return $tool->fresh();
        });

    }

    private static function getActiveLoans(int $toolId, int $userId)
    {
        return LoanMouvement::where('tool_id', $toolId)
            ->where('user_id', $userId)
            ->where('remaining_quantity', '>', 0)
            ->orderBy('created_at') // FIFO
            ->lockForUpdate()
            ->get();
    }
    private static function updateLoan($loan, $deduct): void
    {
        // Calculate new remaining
        $newRemaining = $loan->remaining_quantity - $deduct;
        // Update loan remaining + status if completed
        $loan->update([
            'remaining_quantity' => $newRemaining,
            'status' => $newRemaining === 0
                ? 'Completed'
                : $loan->status
        ]);
    }


    private static function createReturnRecord(
        $loan,
        $deduct,
        $toolId
    ): ReturnMouvement {
        return ReturnMouvement::create([
            'loan_mouvements_id' => $loan->id,
            'quantity' => $deduct,
            "tool_id" => $toolId
        ]);
    }




}