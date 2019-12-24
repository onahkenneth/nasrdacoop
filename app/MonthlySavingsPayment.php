<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MonthlySavingsPayment extends Model
{
    protected $dates = ['withdrawal_date', 'deposit_date'];
    
    public function monthlySavings()
    {
        return $this->belongsTo(MonthlySavings::class);
    }
    
    public function staff()
    {
        return $this->belongsTo(Staff::class, 'ippis', 'ippis');
    }
}
