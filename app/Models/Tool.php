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
        'total_quantity',
        'available_quantity',
        'status'
    ];


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

    public function qtyStatusHandling()
    {
        $this->status = $this->available_quantity > 0 ? ToolStatus::Disponible : ToolStatus::NoDisponible;
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withPivot('quantity')->withTimestamps();
    }

    public function loans()
    {
        return $this->hasMany(LoanMouvement::class);
    }

    public function inwards()
    {
        return $this->hasMany(InwardMouvement::class);
    }
}
