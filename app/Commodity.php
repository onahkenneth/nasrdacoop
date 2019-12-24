<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Commodity extends Model
{
    protected $dates = ['loan_date', 'deposit_date', 'loan_end_date'];

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'ippis', 'ippis');
    }
    
    public function payments()
    {
        return $this->hasMany(CommodityPayment::class);
    }


    public function defaults()
    {
        return $this->hasMany(CommodityLoanDefault::class);
    }


    public function checkLoanDefault()
    {
        $lastPayment = $this->payments->last();

        return ($this->loan_end_date->isPast() && $lastPayment->bal > 0) ? true : false;
    }
}
