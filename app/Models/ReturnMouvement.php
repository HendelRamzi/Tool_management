<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReturnMouvement extends Model
{
    protected $fillable = [
        'tool_id',
        "quantity"
    ];


    public function getTypeLabel(): string
    {
        return 'Taken';
    }

    public function typeColor(): string
    {
        return 'success';
    }


    public function mouvement()
    {
        return $this->morphOne(Mouvement::class, 'mouvementable');
    }

    public function tool()
    {
        return $this->belongsTo(Tool::class)->withTrashed();
    }
}
