<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LongTermPayment extends Model
{
    protected $dates = ['loan_date', 'deposit_date'];
    
    public function longTermLoan()
    {
        return $this->belongsTo(LongTerm::class);
    }
}
