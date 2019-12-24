<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MonthlySaving extends Model
{
    public function payments()
    {
        return $this->hasMany(MonthlySavingsPayment::class);
    }
}
