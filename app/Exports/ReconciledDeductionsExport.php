<?php
namespace App\Exports;

use App\Center;
use App\Staff;
use App\Ledger;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ReconciledDeductionsExport implements FromCollection, WithHeadings
{
    public $rows;
    public $ref;
    public $deduction_for;

    function __construct($rows, $ref, $deduction_for) {
        $this->rows = $rows;
        $this->ref = $ref;
        $this->deduction_for = $deduction_for;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {

        $deductions = collect([]);

        foreach ($this->rows as $row) {

            $ippis = $row[1];
            $amountDeductedByIppis = $row[3];

            if (preg_match('/^[0-9]{6}$/',$ippis)) {              
                $ledger = new Ledger;
                $monthlyDeductions = $ledger->getMemberTotalMonthlyDeduction($ippis);

                $result = $ledger->executeDeductions($ippis, $amountDeductedByIppis, $monthlyDeductions, $this->ref, $this->deduction_for);
                $deductions->push($result); 
            }

        }

        return $deductions;
    }

    /**
     * @return array
     */
    public function headings(): array
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