<?php

namespace App\Services;

use App\Models\LoanMouvement;
use Illuminate\Database\Eloquent\Model;



class UserService {

    public static function getLoanNumber(Model $user){
        return $user->whereHas("mouvements", function ($query) {
            $query->where('mouvementable_type', LoanMouvement::class); 
        })->count();
    }

}