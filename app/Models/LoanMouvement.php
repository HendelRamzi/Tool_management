<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanMouvement extends Model
{
    protected $fillable = [
        'tool_id',
        'quantity',
        "remaining_quantity",
        "user_id",
        "status"
    ];



    public function tool()
    {
        return $this->belongsTo(Tool::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function returnMouvements()
    {
        return $this->hasMany(ReturnMouvement::class);
    }
}
