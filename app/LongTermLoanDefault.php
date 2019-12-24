<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LongTermLoanDefault extends Model
{

    public function longTermLoan()
    {
        return $this->belongsTo(LongTerm::class);
    }
}
