<?php

namespace App\Services;

use App\Models\InwardMouvement;
use App\Models\LoanMouvement;
use App\Models\ReturnMouvement;
use App\Models\Tool;
use DB;



class StockService
{

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


}