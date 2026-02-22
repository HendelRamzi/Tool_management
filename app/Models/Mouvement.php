<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Mouvement extends Model
{

    protected $fillable = [
        'mouvementable_id',
        'mouvementable_type',
        'user_id',
        'tool_id',
    ];


    public static function CreateNewMouvement(array $data, $type): Mouvement
    {
        return DB::transaction(function () use ($type, $data) {

            $tool = Tool::select('id', 'qty')
                ->findOrFail($data['tool_id']);

            $payload = [
                'tool_id' => $tool->id,
                'quantity' => $data['qty'],
            ];

            // if inward mouvement, need to store the old quantity of the tool before updating it.
            if ($type === InwardMouvement::class) {
                $payload['old_qty'] = $tool->qty ?? 0;
            }

            /** @var \Illuminate\Database\Eloquent\Model $movementable */
            $movementable = $type::create($payload);

            // Manage the tool quantity
            Tool::QuantityManager($data['tool_id'], $data['qty'], $type);

            $movementable->mouvement()->create([
                'user_id' => auth()->id(),
                'tool_id' => $data['tool_id'],
            ]);

            return $movementable->mouvement; 
        });

    }

    public function getTypeLabel(): string
    {
        return match ($this->mouvementable_type) {
            LoanMouvement::class => 'Taken',
            ReturnMouvement::class => 'Returned',
            InwardMouvement::class => 'Stock In',
            default => 'Unknown',
        };
    }

    public function typeColor(): string
    {
        return match ($this->mouvementable_type) {
            LoanMouvement::class => 'danger',
            ReturnMouvement::class => 'success',
            InwardMouvement::class => 'info',
            default => 'gray',
        };
    }


    public function mouvementable()
    {
        return $this->morphTo();
    }

    public function tool()
    {
        return $this->belongsTo(Tool::class)->withTrashed();
    }

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }
}
