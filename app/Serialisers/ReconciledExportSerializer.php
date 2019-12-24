<?php

namespace App\Serialisers;

use Illuminate\Database\Eloquent\Model;
use Cyberduck\LaravelExcel\Contract\SerialiserInterface;

class ReconciledExportSerializer implements SerialiserInterface
{
    public function getData($data)
    {
        $row = [];

        $row[] = $data->field1;
        $row[] = $data->relationship->field2;

        return $row;
    }

    public function getHeaderRow()
    {
        return [
            'IPPIS',
            'NAME',
            'EXPECTED SAVINGS',
            'ACTUAL SAVINGS',
            'EXPECTED LONG TERM',
            'ACTUAL LONG TERM',
            'EXPECTED SHORT TERM',
            'ACTUAL SHORT TERM',
            'EXPECTED COMMODITY',
            'ACTUAL COMMODITY',
        ];
    }
}