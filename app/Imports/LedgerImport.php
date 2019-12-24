<?php

namespace App\Imports;
ini_set('memory_limit', '-1');

use App\Ledger;
use App\Staff;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;

class LedgerImport implements ToModel
{
    protected $count = 0;
    protected $staff = null;

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // dd($row);

        if(array_key_exists(3, $row)) {
            $ippis = explode(':', $row[3]);

            if($ippis[0] == 'IPPIS') {

                $this->count = 0;

                $this->staff = Staff::where('ippis', trim($ippis[1]))->first();

                if(!$this->staff) {
                    $this->staff = Staff::create([
                        'full_name'      => trim(explode(':', $row[0])[1]), 
                        'ippis'  => trim($ippis[1]),
                        ]);
                }
            }

            if($this->count >= 3) {    
                return new Ledger([
                    'staff_id'     => $this->staff->id,
                    'date'     => $row[0],
                    'ref'    => $row[1],
                    'savings_dr'    => $row[2],
                    'savings_cr'    => $row[3],
                    'savings_bal'    => $row[4],
                    'long_term_dr'    => $row[5],
                    'long_term_cr'    => $row[6],
                    'long_term_bal'    => $row[7],
                    'short_term_dr'    => $row[8],
                    'short_term_cr'    => $row[9],
                    'short_term_bal'    => $row[10],
                    'commodity_dr'    => $row[11],
                    'commodity_cr'    => $row[12],
                    'commodity_bal'    => $row[13],
                ]);
            }
        }

        $this->count++;
    }
}
