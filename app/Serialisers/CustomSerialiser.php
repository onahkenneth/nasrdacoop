<?php

namespace App\Serialisers; 
use Illuminate\Database\Eloquent\Model; 
use Cyberduck\LaravelExcel\Contract\SerialiserInterface; 
use Cyberduck\LaravelExcel\Serialiser\BasicSerialiser; 

class CustomSerialiser extends BasicSerialiser { 
    public function getHeaderRow() 

    { 
        return [ 
            'IPPIS', 
            'NAME', 
            'EXPECTED SAVING', 
            'ACTUAL SAVING',
            'EXPECTED LONG TERM LOAN', 
            'ACTUAL LONG TERM LOAN',
            'EXPECTED SHORT TERM LOAN', 
            'ACTUAL SHORT TERM LOAN',
            'EXPECTED COMMODITY LOAN', 
            'ACTUAL COMMODITY LOAN',
        ]; 
    } 
}