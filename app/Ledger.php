<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Ledger;
use App\Center;
use App\Staff;
use App\MonthlySaving;
use App\MonthlySavingsPayment;
use App\LongTerm;
use App\LongTermPayment;
use App\ShortTerm;
use App\ShortTermPayment;
use App\Commodity;
use App\CommodityPayment;
use App\LongTermLoanDefault;
use App\ShortTermLoanDefault;
use App\CommodityLoanDefault;
use Carbon\Carbon;
define('LONG_TERM_PENALTY_PERCENTAGE', 0.05);
define('SHORT_TERM_PENALTY_PERCENTAGE', 0.03);
define('COMMODITY_PENALTY_PERCENTAGE', 0.05);

class Ledger extends Model
{

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'id', 'created_at', 'updated_at', 'deleted_at',
    ];

    protected $dates = ['loan_date', 'deposit_date', 'withdrawal_date'];

    public function member()
    {
        return $this->belongsTo(Staff::class);
    }



    /**
     * Generate total deductions due for a memeber
     */
    function getMemberTotalMonthlyDeduction($ippis) {
                $member = Staff::where('ippis', $ippis)->first();
                
                $monthly_savings = MonthlySaving::where('ippis', $ippis)->latest('id')->first();
                $monthly_savings_payment = MonthlySavingsPayment::where('ippis', $ippis)->latest('id')->first();

                $long_term = LongTerm::where('ippis', $ippis)->latest('id')->first();
                $long_term_payment = LongTermPayment::where('ippis', $ippis)->latest('id')->first();

                $short_term_payment = ShortTermPayment::where('ippis', $ippis)->latest('id')->first();
                $short_term = ShortTerm::where('ippis', $ippis)->latest('id')->first();
                
                $commodity = Commodity::where('ippis', $ippis)->latest('id')->first();
                $commodity_payment = CommodityPayment::where('ippis', $ippis)->latest('id')->first();
                // dd($monthly_savings, $long_term, $short_term, $commodity);


                if (!$monthly_savings) {
                    $monthly_savings_amount = MIN_SAVINGS;
                } else {
                    if(!$monthly_savings->amount) {
                        $monthly_savings_amount = MIN_SAVINGS;
                    } else {
                        $monthly_savings_amount = $monthly_savings->amount;
                    }
                }

                if (!$long_term) {
                    $long_term_monthly_amount = 0;
                } else {
                    if(!$long_term->monthly_amount) {
                        $long_term_monthly_amount = 0;
                    } else {
                        if($long_term_payment) {
                            if ($long_term_payment->bal == 0) {
                                $long_term_monthly_amount = 0;
                            } else {
                                $long_term_monthly_amount = $long_term->monthly_amount;
                            }
                        } else {
                            $long_term_monthly_amount = 0;
                        }
                        
                    }
                }

                if (!$short_term) {
                    $short_term_monthly_amount = 0;
                } else {
                    if(!$short_term->monthly_amount) {
                        $short_term_monthly_amount = 0;
                    } else {
                        if($short_term_payment) {
                            if ($short_term_payment->bal == 0) {
                                $short_term_monthly_amount = 0;
                            } else {
                                $short_term_monthly_amount = $short_term->monthly_amount;
                            }
                        } else {
                            $short_term_monthly_amount = 0;
                        }
                        
                    }
                }

                if (!$commodity) {
                    $commodity_monthly_amount = 0;
                } else {
                    if(!$commodity->monthly_amount) {
                        $commodity_monthly_amount = 0;
                    } else {
                        if($commodity_payment) {
                            if ($commodity_payment->bal == 0) {
                                $commodity_monthly_amount = 0;
                            } else {
                                $commodity_monthly_amount = $commodity->monthly_amount;
                            }
                        } else {
                            $commodity_monthly_amount = 0;
                        }                        
                    }
                }

        return [
            'ippis ' => $ippis,
            'full_name ' => isset($member) ? $member->full_name : '',
            'monthly_savings_amount'    => $monthly_savings_amount, 
            'long_term_monthly_amount'  => $long_term_monthly_amount , 
            'short_term_monthly_amount' => $short_term_monthly_amount, 
            'commodity_monthly_amount'  => $commodity_monthly_amount,
            'total'  => $monthly_savings_amount + $long_term_monthly_amount + $short_term_monthly_amount + $commodity_monthly_amount,
        ];
    }



    /**
     * Execute deductions sent back by IPPIS
     */
    public function executeDeductions($ippis, $amountDeducted, $monthlyDeductions, $ref, $deduction_for) {
        // dd($ippis, $monthlyDeductions, $amountDeducted, $ref, $deduction_for);

        $member = Staff::where('ippis', $ippis)->first();

        $savings_amount = 0;
        $long_term_amount = 0;
        $short_term_amount = 0;
        $commodity_amount = 0;

        // default amounts
        $longTermDefaultAmt = 0;
        $shortTermDefaultAmt = 0;
        $commodityDefaultAmt = 0;

        if($member) {

            if($amountDeducted != 0) {

                if ($amountDeducted >= MIN_SAVINGS) {
                    $savings_amount += MIN_SAVINGS;
                    $amountDeducted = $amountDeducted - MIN_SAVINGS;
                } else {
                    $savings_amount += $amountDeducted;
                    $amountDeducted = $amountDeducted - $amountDeducted;
                }

                if($amountDeducted >= $monthlyDeductions['long_term_monthly_amount']) {
                    $long_term_amount += $monthlyDeductions['long_term_monthly_amount'];
                    $amountDeducted = $amountDeducted - $monthlyDeductions['long_term_monthly_amount'];
                } else {

                    $longTermDefaultAmt =  ($monthlyDeductions['long_term_monthly_amount'] - $amountDeducted) * LONG_TERM_PENALTY_PERCENTAGE;

                    $long_term_amount += $amountDeducted;
                    $amountDeducted = $amountDeducted - $amountDeducted;
                }

                if($amountDeducted >= $monthlyDeductions['short_term_monthly_amount']) {
                    $short_term_amount += $monthlyDeductions['short_term_monthly_amount'];
                    $amountDeducted = $amountDeducted - $monthlyDeductions['short_term_monthly_amount'];
                } else {

                    $shortTermDefaultAmt =  ($monthlyDeductions['short_term_monthly_amount'] - $amountDeducted) * SHORT_TERM_PENALTY_PERCENTAGE;

                    $short_term_amount += $amountDeducted;
                    $amountDeducted = $amountDeducted - $amountDeducted;
                }

                if($amountDeducted >= $monthlyDeductions['commodity_monthly_amount']) {
                    $commodity_amount += $monthlyDeductions['commodity_monthly_amount'];
                    $amountDeducted = $amountDeducted - $monthlyDeductions['commodity_monthly_amount'];
                } else {

                    $commodityDefaultAmt =  ($monthlyDeductions['commodity_monthly_amount'] - $amountDeducted) * COMMODITY_PENALTY_PERCENTAGE;
                    
                    $commodity_amount += $amountDeducted;
                    $amountDeducted = $amountDeducted - $amountDeducted;
                }


                $savings_amount += $amountDeducted;
            }

            // MONTHLY SAVINGS
            $lastMonthlySavingRecord = MonthlySaving::where('ippis', $ippis)->latest('id')->first();

            if(isset($lastMonthlySavingRecord)) {
                $lastMonthlySavingsPaymentRecord = MonthlySavingsPayment::where('monthly_saving_id', $lastMonthlySavingRecord->id)->latest('id')->first();
                
                $savings_bal = isset($lastMonthlySavingsPaymentRecord) ? $lastMonthlySavingsPaymentRecord->bal + $savings_amount : $savings_amount;

            } else {

                $savings_bal = $savings_amount;

            }

            // make entry in long term payments table
            $msPayment = new MonthlySavingsPayment;
            $msPayment->monthly_saving_id = $lastMonthlySavingRecord->id;
            $msPayment->ippis = $ippis;
            $msPayment->pay_point = $member->pay_point;
            $msPayment->ref = $ref;
            $msPayment->deposit_date = $deduction_for;
            $msPayment->dr = 0.00;
            $msPayment->cr = $savings_amount;
            $msPayment->bal = $savings_bal;
            $msPayment->month = Carbon::today()->format('m');
            $msPayment->year = Carbon::today()->format('Y');
            $msPayment->save();


            // LONG TERM LOAN
            $longTermRecord = LongTerm::where('ippis', $ippis)->latest('id')->first();

            if(isset($longTermRecord)) {
                $longLongTermPaymentRecord = $longTermRecord->payments->last();
                
                $long_term_bal = isset($longLongTermPaymentRecord) ? $longLongTermPaymentRecord->bal -$long_term_amount : 0 -$long_term_amount;

            } else {

                $long_term_bal = 0 - $long_term_amount;

            }

            // make entry in long term payments table
            $ltlPayment = new LongTermPayment;
            $ltlPayment->ippis = $ippis;
            $ltlPayment->pay_point = $member->pay_point;
            $ltlPayment->ref = $ref;
            $ltlPayment->deposit_date = $deduction_for;
            $ltlPayment->long_term_id = $longTermRecord ? $longTermRecord->id : 0;
            $ltlPayment->dr = 0.00;
            $ltlPayment->cr = $long_term_amount;
            $ltlPayment->bal = $long_term_bal;
            $ltlPayment->month = Carbon::today()->format('m');
            $ltlPayment->year = Carbon::today()->format('Y');
            $ltlPayment->save();

            // treat defaults
            if($longTermDefaultAmt != 0) {
                $ltDedault = new LongTermLoanDefault;
                $ltDedault->long_term_id = $longTermRecord ? $longTermRecord->id : 0;
                $ltDedault->ippis = $ippis;
                $ltDedault->pay_point = $member->pay_point;
                $ltDedault->default_charge = $longTermDefaultAmt;
                $ltDedault->monthly_obligation = $longTermRecord ? $longTermRecord->monthly_amount : 0;
                $ltDedault->actual_paid = $long_term_amount;
                $ltDedault->default_amount = ($monthlyDeductions['long_term_monthly_amount'] - $amountDeducted);
                $ltDedault->percentage = LONG_TERM_PENALTY_PERCENTAGE;
                $ltDedault->month = Carbon::parse($deduction_for)->format('m');
                $ltDedault->year = Carbon::parse($deduction_for)->format('Y');
                $ltDedault->save();
            }




            // SHORT TERM LOAN
            $shortTermRecord = ShortTerm::where('ippis', $ippis)->latest('id')->first();

            if(isset($shortTermRecord)) {
                $shortShortTermPaymentRecord = $shortTermRecord->payments->last();
                
                $short_term_bal = isset($shortShortTermPaymentRecord) ? $shortShortTermPaymentRecord->bal - $short_term_amount : 0 -$short_term_amount;

            } else {

                $short_term_bal = 0 - $short_term_amount;

            }

            // make entry in short term payments table
            $stlPayment = new ShortTermPayment;
            $stlPayment->ippis = $ippis;
            $stlPayment->pay_point = $member->pay_point;
            $stlPayment->ref = $ref;
            $stlPayment->deposit_date = $deduction_for;
            $stlPayment->short_term_id = $shortTermRecord ? $shortTermRecord->id : 0;
            $stlPayment->dr = 0.00;
            $stlPayment->cr = $short_term_amount;
            $stlPayment->bal = $short_term_bal;
            $stlPayment->month = Carbon::today()->format('m');
            $stlPayment->year = Carbon::today()->format('Y');
            $stlPayment->save();

            // treat defaults
            if($shortTermDefaultAmt != 0) {
                $stDedault = new ShortTermLoanDefault;
                $stDedault->short_term_id = $shortTermRecord ? $shortTermRecord->id : 0;
                $stDedault->ippis = $ippis;
                $stDedault->pay_point = $member->pay_point;
                $stDedault->default_charge = $shortTermDefaultAmt;
                $stDedault->monthly_obligation = $shortTermRecord ? $shortTermRecord->monthly_amount : 0;
                $stDedault->actual_paid = $short_term_amount;
                $stDedault->default_amount = ($monthlyDeductions['short_term_monthly_amount'] - $amountDeducted);
                $stDedault->percentage = SHORT_TERM_PENALTY_PERCENTAGE;
                $stDedault->month = Carbon::parse($deduction_for)->format('m');
                $stDedault->year = Carbon::parse($deduction_for)->format('Y');
                $stDedault->save();
            }

            


            // COMMODITY LOAN
            $CommodityRecord = Commodity::where('ippis', $ippis)->latest('id')->first();

            if(isset($CommodityRecord)) {
                $CommodityPaymentRecord = $CommodityRecord->payments->last();
                
                $commodity_bal = isset($CommodityPaymentRecord) ? $CommodityPaymentRecord->bal - $commodity_amount : 0 - $commodity_amount;

            } else {

                $commodity_bal = 0 -$commodity_amount;

            }

            // make entry in short term payments table
            $commodityPayment = new CommodityPayment;
            $commodityPayment->ippis = $ippis;
            $commodityPayment->pay_point = $member->pay_point;
            $commodityPayment->ref = $ref;
            $commodityPayment->deposit_date = $deduction_for;
            $commodityPayment->commodity_id = $CommodityRecord ? $CommodityRecord->id : 0;
            $commodityPayment->dr = 0.00;
            $commodityPayment->cr = $commodity_amount;
            $commodityPayment->bal = $commodity_bal;
            $commodityPayment->month = Carbon::today()->format('m');
            $commodityPayment->year = Carbon::today()->format('Y');
            $commodityPayment->save();

            // treat defaults
            if($commodityDefaultAmt != 0) {
                $comDedault = new CommodityLoanDefault;
                $comDedault->commodity_id = $CommodityRecord ? $CommodityRecord->id : 0;
                $comDedault->ippis = $ippis;
                $comDedault->pay_point = $member->pay_point;
                $comDedault->default_charge = $commodityDefaultAmt;
                $comDedault->monthly_obligation = $CommodityRecord ? $CommodityRecord->monthly_amount : 0;
                $comDedault->actual_paid = $commodity_amount;
                $comDedault->default_amount = ($monthlyDeductions['commodity_monthly_amount'] - $amountDeducted);
                $comDedault->percentage = COMMODITY_PENALTY_PERCENTAGE;
                $comDedault->month = Carbon::parse($deduction_for)->format('m');
                $comDedault->year = Carbon::parse($deduction_for)->format('Y');
                $comDedault->save();
            }
            

            // make ledger entry
            $ledger                 = new Ledger;
            $ledger->staff_id       = $member->id;
            $ledger->pay_point      = $member->pay_point;
            $ledger->date           = Carbon::today()->format('Y-m-d');
            $ledger->ref            = $ref;
            $ledger->deposit_date   = $deduction_for;
            $ledger->withdrawal_date= $deduction_for;
            $ledger->loan_date      = $deduction_for;
            $ledger->savings_dr     = 0.00;
            $ledger->savings_cr     = $savings_amount;
            $ledger->savings_bal    = $savings_bal;
            $ledger->long_term_dr   = 0.00;
            $ledger->long_term_cr   = $long_term_amount;
            $ledger->long_term_bal  = $long_term_bal;
            $ledger->short_term_dr  = 0.00;
            $ledger->short_term_cr  = $short_term_amount;
            $ledger->short_term_bal = $short_term_bal;
            $ledger->commodity_dr   = 0.00;
            $ledger->commodity_cr   = $commodity_amount;
            $ledger->commodity_bal  = $commodity_bal;
            $ledger->save();

            if($longTermDefaultAmt != 0 || $shortTermDefaultAmt !=0  || $commodityDefaultAmt != 0) {
                $ledger                 = new Ledger;
                $ledger->staff_id       = $member->id;
                $ledger->pay_point      = $member->pay_point;
                $ledger->date           = Carbon::parse($deduction_for);
                $ledger->ref            = 'DEFAULT ON: '.$ref;
                $ledger->savings_dr     = 0.00;
                $ledger->savings_cr     = 0.00;
                $ledger->savings_bal    = $savings_bal;
                $ledger->long_term_dr   = $longTermDefaultAmt;
                $ledger->long_term_cr   = 0.00;
                $ledger->long_term_bal  = $long_term_bal + $longTermDefaultAmt;
                $ledger->short_term_dr  = $shortTermDefaultAmt;
                $ledger->short_term_cr  = 0.00;
                $ledger->short_term_bal = $short_term_bal + $shortTermDefaultAmt;
                $ledger->commodity_dr   = $commodityDefaultAmt;
                $ledger->commodity_cr   = 0.00;
                $ledger->commodity_bal  = $commodity_bal + $commodityDefaultAmt;
                $ledger->save();
            }

            return [
                $ippis,
                $member->full_name,
                number_format($monthlyDeductions['monthly_savings_amount']),
                number_format($savings_amount),
                number_format($monthlyDeductions['long_term_monthly_amount']),
                number_format($long_term_amount),
                number_format($monthlyDeductions['short_term_monthly_amount']),
                number_format($short_term_amount),
                number_format($monthlyDeductions['commodity_monthly_amount']),
                number_format($commodity_amount),
            ];
        } else {
            return [
                $ippis,
                '',
                number_format($monthlyDeductions['monthly_savings_amount']),
                number_format($savings_amount),
                number_format($monthlyDeductions['long_term_monthly_amount']),
                number_format($long_term_amount),
                number_format($monthlyDeductions['short_term_monthly_amount']),
                number_format($short_term_amount),
                number_format($monthlyDeductions['commodity_monthly_amount']),
                number_format($commodity_amount),
            ];
        }

    }



    /**
     * When total deducted by IPPIS matches what was sent to them
     */
    public function deductionsMatch($ippis, $amountDeducted, $monthlyDeductions) {
        $member = Staff::where('ippis', $ippis)->first();

        // MONTHLY SAVING
        // get latest monthly saving of member. this is important bcos monthly savings may change and we want to keep track of the particular monthly saving this entry is being made for
        $monthlySavings = MonthlySaving::where('ippis', $ippis)->latest('id')->first();

        if($monthlySavings ) {
            // dd($ippis, $amountDeducted, $monthlyDeductions);

            $existingMonthlySavingsPayment = MonthlySavingsPayment::where('monthly_saving_id', $monthlySavings->id)->latest('id')->first();

            $monthlySavingsPayment = new MonthlySavingsPayment;
            $monthlySavingsPayment->ippis = $ippis;
            $monthlySavingsPayment->monthly_saving_id = $monthlySavings->id;
            $monthlySavingsPayment->dr = 0.00;
            $monthlySavingsPayment->cr = $monthlyDeductions['monthly_savings_amount'];
            $monthlySavingsPayment->bal = $existingMonthlySavingsPayment ? $existingMonthlySavingsPayment->bal + $monthlyDeductions['monthly_savings_amount'] : 0 + $monthlyDeductions['monthly_savings_amount'];
            $monthlySavingsPayment->month = Carbon::today()->format('m');
            $monthlySavingsPayment->year = Carbon::today()->format('Y');
            $monthlySavingsPayment->save();
        }

        // LONGTERM LOAN
        $longTermLoan = LongTerm::where('ippis', $ippis)->latest('id')->first();

        if($longTermLoan ) {
            $existingLtlPayment = LongTermPayment::where('long_term_id', $longTermLoan->id)->latest('id')->first();
            $ltl = new LongTermPayment;
            $ltl->ippis = $ippis;
            $ltl->long_term_id = $longTermLoan->id;
            $ltl->dr = 0;
            $ltl->cr = $monthlyDeductions['long_term_monthly_amount'];
            $ltl->bal = $existingLtlPayment ? $existingLtlPayment->bal - $monthlyDeductions['long_term_monthly_amount'] : 0 - $monthlyDeductions['long_term_monthly_amount'];
            $ltl->month = Carbon::today()->format('m');
            $ltl->year = Carbon::today()->format('Y');
            $ltl->save();
        }

        // SHORTTERM LOAN
        $shortTermLoan = ShortTerm::where('ippis', $ippis)->latest('id')->first();

        if($shortTermLoan) {
            $existingStlPayment = ShortTermPayment::where('short_term_id', $shortTermLoan->id)->latest('id')->first();
            $stl = new ShortTermPayment;
            $stl->ippis = $ippis;
            $stl->short_term_id = $shortTermLoan->id;
            $stl->dr = $monthlyDeductions['short_term_monthly_amount'];
            $stl->cr = $monthlyDeductions['short_term_monthly_amount'];
            $stl->bal = $existingStlPayment ? $existingStlPayment->bal - $monthlyDeductions['short_term_monthly_amount'] : 0 - $monthlyDeductions['short_term_monthly_amount'];
            $stl->month = Carbon::today()->format('m');
            $stl->year = Carbon::today()->format('Y');
            $stl->save();
        }

        // COMMMODITY LOAN
        $commodityLoan = Commodity::where('ippis', $ippis)->latest('id')->first();

        if($commodityLoan) {
            $commodityPayment = CommodityPayment::where('commodity_id', $commodityLoan->id)->latest('id')->first();
            $commodity = new CommodityPayment;
            $commodity->ippis = $ippis;
            $commodity->commodity_id = $commodityLoan->id;
            $commodity->amount_paid = $monthlyDeductions['commodity_monthly_amount'];
            $commodity->dr = $monthlyDeductions['commodity_monthly_amount'];
            $commodity->cr = $monthlyDeductions['commodity_monthly_amount'];
            $commodity->bal = $commodityPayment ? $commodityPayment->bal - $monthlyDeductions['commodity_monthly_amount'] : 0 - $monthlyDeductions['commodity_monthly_amount'];
            $commodity->month = Carbon::today()->format('m');
            $commodity->year = Carbon::today()->format('Y');
            $commodity->save();
        }

        // LEDGER ENTRY
        if($member) {
            $ledger                 = new Ledger;
            $ledger->staff_id       = $member->id;
            $ledger->date           = Carbon::today()->format('Y/m');
            $ledger->ref            = 'AMT PAID('.Carbon::today()->format('Y').')';
            $ledger->savings_dr     = 0.00;
            $ledger->savings_cr     = $monthlyDeductions['monthly_savings_amount'];
            $ledger->savings_bal    = isset($existingMonthlySavingsPayment) ? $existingMonthlySavingsPayment->bal + $monthlyDeductions['monthly_savings_amount'] : 0 + $monthlyDeductions['monthly_savings_amount'];
            $ledger->long_term_dr   = 0.00;
            $ledger->long_term_cr   = $monthlyDeductions['long_term_monthly_amount'];
            $ledger->long_term_bal  = isset($existingLtlPayment) ? $existingLtlPayment->bal - $monthlyDeductions['long_term_monthly_amount'] : 0 - $monthlyDeductions['long_term_monthly_amount'];
            $ledger->short_term_dr  = 0.00;
            $ledger->short_term_cr  = $monthlyDeductions['short_term_monthly_amount'];
            $ledger->short_term_bal = isset($existingStlPayment) ? $existingStlPayment->bal - $monthlyDeductions['short_term_monthly_amount'] : 0 - $monthlyDeductions['short_term_monthly_amount'];
            $ledger->commodity_dr   = 0.00;
            $ledger->commodity_cr   = $monthlyDeductions['commodity_monthly_amount'];
            $ledger->commodity_bal  = isset($commodityPayment) ? $commodityPayment->bal - $monthlyDeductions['commodity_monthly_amount'] : 0 - $monthlyDeductions['commodity_monthly_amount'];
            $ledger->save();
        }

        return [
            'ippis' => $ippis,
            'monthly_savings'   => isset($existingMonthlySavingsPayment) ? $existingMonthlySavingsPayment->bal + $monthlyDeductions['monthly_savings_amount'] : 0 + $monthlyDeductions['monthly_savings_amount'],
            'long_term_loan'    => isset($existingLtlPayment) ? $existingLtlPayment->bal - $monthlyDeductions['long_term_monthly_amount'] : 0 - $monthlyDeductions['long_term_monthly_amount'],
            'short_term_loan'   => isset($existingStlPayment) ? $existingStlPayment->bal - $monthlyDeductions['short_term_monthly_amount'] : 0 - $monthlyDeductions['short_term_monthly_amount'],
            'commodity_loan'    => isset($commodityPayment) ? $commodityPayment->bal - $monthlyDeductions['commodity_monthly_amount'] : 0 - $monthlyDeductions['commodity_monthly_amount'],
        ];

    }
}
