<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CommodityLoanDefault extends Model
{

    public function commodityLoan()
    {
        return $this->belongsTo(Commodity::class);
    }
}
