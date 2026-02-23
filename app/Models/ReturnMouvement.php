<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReturnMouvement extends Model
{
    protected $fillable = [
        'tool_id',
        "quantity",
        "loan_mouvements_id"
    ];


    public function loanMouvement()
    {
        return $this->belongsTo(LoanMouvement::class);
    }
}
