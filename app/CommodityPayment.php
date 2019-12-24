<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CommodityPayment extends Model
{
    protected $dates = ['loan_date', 'deposit_date'];
    
    public function commodity()
    {
        return $this->belongsTo(Commodity::class);
    }
}
