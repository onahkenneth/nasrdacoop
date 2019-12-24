<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShortTermPayment extends Model
{
    protected $dates = ['loan_date', 'deposit_date'];
    
    public function shortTermLoan()
    {
        return $this->belongsTo(ShortTerm::class);
    }
}
