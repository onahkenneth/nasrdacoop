<?php
namespace App\Exports;

use App\Center;
use App\Staff;
use App\Ledger;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LedgerExport implements FromCollection, WithHeadings
{
    public $ippis;

    function __construct($ippis) {
        $this->ippis = $ippis;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $staff = Staff::where('ippis', $this->ippis)->first();
        $ledger = Ledger::select('date', 
        'ref', 
        'savings_dr', 
        'savings_cr', 
        'savings_bal', 
        'long_term_dr', 
        'long_term_cr', 
        'long_term_bal', 
        'short_term_dr', 
        'short_term_cr', 
        'short_term_bal', 
        'commodity_dr', 
        'commodity_cr', 
        'commodity_bal')->where('staff_id', $staff->id)->get();

        return $ledger;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'DATE',
            'DESCRIPTION',
            'SAVINGS DR',
            'SAVINGS CR',
            'SAVINGS BAL',
            'LONG TERM DR',
            'LONG TERM CR',
            'LONG TERM BAL',
            'SHORT TERM DR',
            'SHORT TERM CR',
            'SHORT TERM BAL',
            'COMMODITY DR',
            'COMMODITY CR',
            'COMMODITY BAL',
        ];
    }
}