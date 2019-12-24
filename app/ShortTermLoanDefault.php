<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShortTermLoanDefault extends Model
{

    public function shortTermLoan()
    {
        return $this->belongsTo(ShortTerm::class);
    }
}
