<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InwardMouvement extends Model
{
    protected $fillable = [
        'tool_id',
        'quantity',
        "old_qty"
    ];

    public function mouvement()
    {
        return $this->morphOne(Mouvement::class, 'mouvementable');
    }

    public function tool()
    {
        return $this->belongsTo(Tool::class)->withTrashed();
    }
}
