<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InwardMouvement extends Model
{
    protected $fillable = [
        'tool_id',
        "user_id",
        'quantity',
        "note",
    ];

    public function tool()
    {
        return $this->belongsTo(Tool::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
