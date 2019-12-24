<?php
namespace App\Exports;

use App\Center;
use App\Staff;
use App\Ledger;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MonthlyDeductionsExport implements FromCollection, WithHeadings
{
    public $pay_point;

    function __construct($pay_point) {
        $this->pay_point = $pay_point;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {

        $center = Center::find($this->pay_point);
        
        if($this->pay_point) {
            $staffs = Staff::where('pay_point', $this->pay_point)->where('is_active', 1)->get();
        } else {
            $staffs = Staff::where('is_active', 1)->get();
        }
        

        $deductions = collect([]);

        foreach($staffs as $staff) {
                $ledger = new Ledger;
                $monthlyDeductions = $ledger->getMemberTotalMonthlyDeduction($staff->ippis);
                $deductions->push($monthlyDeductions);                
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
            'SAVINGS',
            'LONG TERM LOAN',
            'SHORT TERM LOAN',
            'COMMODITY LOAN',
            'TOTAL',
        ];
    }
}