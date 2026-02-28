<?php

namespace App\Models;

use App\Enums\ToolStatus;
use App\Observers\ToolObserver;
use Database\Factories\ToolFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;


#[UseFactory(ToolFactory::class)]
#[ObservedBy([ToolObserver::class])]
class Tool extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'reference',
        'qty',
        'status'
    ];


    // Check if the tool is available by checking if 
    // the quantity is greater than 0 or if the status is "Disponible"
    public static function isDispo(Tool $tool): bool
    {
        return $tool->qty > 0 || $tool->status === ToolStatus::Disponible;
    }


    // Change the tool status to "NoFunctionnal" and save the 
    // changes to the database
    public function isNotWorking()
    {
        return $this->status === ToolStatus::NoFunctionnal;
        $this->save();
    }

    // Return a color based on the quantity of the tool
    public static function ColorQtyMapping($qty): string
    {
        if ($qty <= 5) {
            return 'danger';
        } elseif ($qty < 10) {
            return 'warning';
        }
        return 'info';
    }

    public function qtyStatusHandling(){
        $this->status = $this->qty > 0 ? ToolStatus::Disponible : ToolStatus::NoDisponible;
    }

    // I'll implement the quantity management here for now, but I can move it to a service class if needed
    public static function QuantityManager($tool_id, $qty, $operation)
    {
        $tool = self::find($tool_id);
        try {

            // Check if the tool is available
            if ($operation === LoanMouvement::class && !self::isDispo($tool)) {
                throw new \Exception("Tool is not available");
            }

            // manage Loan operation
            if ($operation === LoanMouvement::class) {
                if ($tool->qty < $qty) {
                    throw new \Exception("Not enough quantity available");
                }
                $tool->qty -= $qty;
            } else {
                $tool->qty += $qty;
            }

            $tool->qtyStatusHandling(); 
            $tool->save();

        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withPivot('quantity')->withTimestamps();
    }


    // For UI perposes, I'll add to direct connection with the 
    // loan mouvement, and return mouvement to make relation management
    // more simple

    // public function taken()
    // {
    //     return $this->hasMany(LoanMouvement::class);
    // }

    // public function returned()
    // {
    //     return $this->hasMany(ReturnMouvement::class);
    // }

    public function mouvements(){
        return $this->hasMany(Tool::class); 
    }
}
