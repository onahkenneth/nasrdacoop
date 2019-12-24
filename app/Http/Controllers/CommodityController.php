<?php

namespace App\Http\Controllers;

use App\Staff;
use App\Commodity;
use App\CommodityPayment;
use App\LongTermPayment;
use App\MonthlySavingsPayment;
use App\Ledger;
use Carbon\Carbon;
use Toastr;

use Illuminate\Http\Request;

class CommodityController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Commodity loans
     */
    function commodity($ippis) {

        $data['member'] = Staff::where('ippis', $ippis)->first();

        if(!isset($data['member'])) {
            Toastr::error('This member does not exist', 'Error', ["positionClass" => "toast-bottom-right"]);
            return redirect()->route('members.longTermLoans', $ippis);
        }

        $data['commodityLoans'] = Commodity::with('payments')->where('ippis', $ippis)->get();
        // dd($data['longTermLoans']);

        return view('members.commodity.commodity', $data);
    }

    /**
     * commodity loans
     */
    function newCommodityLoan($ippis) {
        $member = Staff::where('ippis', $ippis)->first();

        if(!isset($member)) {
            Toastr::error('This member does not exist', 'Error', ["positionClass" => "toast-bottom-right"]);
            return redirect()->route('members.longTermLoans', $ippis);
        }

        $commodityLoans = Commodity::pluck('ref', 'id');
        $member = Staff::where('ippis', $ippis)->first();
        $lastLongTermPayment = LongTermPayment::where('ippis', $ippis)->latest('id')->first();
        $last_savings = $member->monthly_savings_payments->last();
        $savings_bal = isset($last_savings) ? $last_savings->bal : 0;
        $lastCommodityLoan = CommodityPayment::where('ippis', $ippis)->latest('id')->first();

        $savings = MonthlySavingsPayment::where('ippis', $ippis)->latest('id')->first();
        $periods = [
            ['key' => '1',  'value' => "1 Months",],
            ['key' => '2',  'value' => "2 Months",],
            ['key' => '3',  'value' => "3 Months",],
            ['key' => '4',  'value' => "4 Months",],
            ['key' => '5',  'value' => "5 Months",],
            ['key' => '6',  'value' => "6 Months",],
        ];
        
        if(request()->ajax()) {

            return [
                'savings'                   => $savings, 
                'periods'                   => $periods,
                'last_long_term_payment'    => $lastLongTermPayment,
                'savings_bal'               => $savings_bal,
                'last_commodity_loan'        => $lastCommodityLoan,
            ];
        }

        $data['commodityLoans'] = $commodityLoans;
        $data['member'] = $member;   

        return view('members.commodity.new_commodity_loan', $data);
    }

    /**
     * Post commodity loans
     */
    function postNewCommodityLoan(Request $request, $ippis) {
        // dd($request->all(), $ippis);

        $rules = [
            'ref' => 'required',
            'loan_date' => 'required',
            'no_of_months' => 'required',
            'ippis' => 'required',
            'total_amount' => 'required',
            // 'email' => 'email|unique:staff,email',

        ];

        $messages = [
            'ref.required' => 'The description is required',
            'loan_date.required' => 'The loan date is required',
            'no_of_months.required' => 'The number of months is required',
            'ippis.required' => 'This IPPIS Number is required',
            'total_amount.required' => 'Kindly enter the loan amount',
        ];

        $this->validate($request, $rules, $messages);

        $member = Staff::where('ippis', $ippis)->first();

        $lastCommodityRecord = Commodity::where('ippis', $ippis)->latest('id')->first();

        if(isset($lastCommodityRecord)) {
            // $lastCommodityPaymentRecord = CommodityPayment::where('long_term_id', $lastCommodityRecord->id)->latest('id')->first();
            $lastCommodityPaymentRecord = $lastCommodityRecord->payments->last();
            
            $commodity_bal = isset($lastCommodityPaymentRecord) ? $lastCommodityPaymentRecord->bal + $request->total_amount : $request->total_amount;

        } else {

            $commodity_bal = $request->total_amount;

        }

        // get loan end date
        $loanEndDate = Carbon::parse($request->loan_date)->addMonths($request->no_of_months);

        // last loan
        $lastCommodityLoanBal = (CommodityPayment::where('ippis', $ippis)->latest('id'))->first() ? CommodityPayment::where('ippis', $ippis)->latest('id')->first()->bal : 0;

        // make entry in commodity loan table
        $commodity = new Commodity;
        $commodity->ref = $request->ref;
        $commodity->loan_date = $request->loan_date;
        $commodity->loan_end_date = $loanEndDate;
        $commodity->ippis = $ippis;
        $commodity->no_of_months = $request->no_of_months;
        $commodity->total_amount = $request->total_amount;
        $commodity->monthly_amount = ($request->total_amount + $lastCommodityLoanBal) / $request->no_of_months;
        $commodity->save();

        // make entry in commodity payments table
        $commodityPayment = new CommodityPayment;
        $commodityPayment->ippis = $ippis;
        $commodityPayment->pay_point      = $member->pay_point;
        $commodityPayment->ref = $request->ref;
        $commodityPayment->loan_date = $request->loan_date;
        $commodityPayment->commodity_id = $commodity->id;
        $commodityPayment->dr = $request->total_amount;
        $commodityPayment->cr = 0.00;
        $commodityPayment->bal = $commodity_bal;
        $commodityPayment->month = Carbon::today()->format('m');
        $commodityPayment->year = Carbon::today()->format('Y');
        $commodityPayment->save();


        // make ledger entry
        $ledger                 = new Ledger;
        $ledger->staff_id       = $member->id;
        $ledger->pay_point      = $member->pay_point;
        $ledger->date           = Carbon::today()->format('Y, m, d');
        $ledger->ref            = $request->ref;
        $ledger->loan_date = $request->loan_date;
        $ledger->commodity_dr   = $request->total_amount;
        $ledger->commodity_cr   = 0.00;
        $ledger->commodity_bal  = $commodity_bal;
        $ledger->save();

        // dd($commodity, $ledger);

        Toastr::success('Loan successful', 'Success', ["positionClass" => "toast-bottom-right"]);
        return redirect()->route('members.longTermLoans', $ippis);
    }

    /**
     * Show reayment form
     */
    function commodityLoanRepayment($ippis) {
        $member = Staff::where('ippis', $ippis)->first();

        if(!isset($member)) {
            Toastr::error('This member does not exist', 'Error', ["positionClass" => "toast-bottom-right"]);
            return redirect()->route('members.longTermLoans', $ippis);
        }
        $commodityLoans = Commodity::pluck('ref', 'id');
        $lastLongTermPayment = LongTermPayment::where('ippis', $ippis)->latest('id')->first();

        $last_commodity_loan_payment = CommodityPayment::where('ippis', $ippis)->latest('id')->first() ? : 0;

        $last_savings = $member->monthly_savings_payments->last();
        $savings_bal = isset($last_savings) ? $last_savings->bal : 0;

        $repaymentModes = [
            ['key' => 'savings', 'value' => 'From savings'],
            ['key' => 'direct_deduction', 'value' => 'Direct deduction'],
            ['key' => 'bank_deposit', 'value' => 'Bank deposit'],
        ];

        if (request()->ajax()) {
            return [
                'commodity_loans'       => $commodityLoans,
                'last_commodity_loan_payment' => $last_commodity_loan_payment,
                'member'                => $member, 
                'repayment_modes'       => $repaymentModes,
                'last_long_term_payment'   => $lastLongTermPayment,
                'savings_bal'           => $savings_bal,
            ];
        }

        $data['commodityLoans']  = $commodityLoans;
        $data['member']         = $member;
        $data['repaymentModes'] = $repaymentModes;
        $data['savings_bal']    = $savings_bal;

        return view('members.commodity.repayment', $data);
    }

    /**
     * Save reayment form
     */
    function postCommodityLoanRepayment(Request $request, $ippis) {
        // dd($request->all(), $ippis);

        $rules = [
            'ref' => 'required',
            'deposit_date' => 'required',
            'ippis' => 'required',
            'total_amount' => 'required',
            'repayment_mode' => 'required',
            // 'email' => 'email|unique:staff,email',

        ];

        $messages = [
            'ref.required' => 'The description is required',
            'deposit_date.required' => 'The repayment date is required',
            'ippis.required' => 'This IPPIS Number is required',
            'total_amount.required' => 'Kindly enter the loan amount',
            'repayment_mode.required' => 'Kindly select a repayment type',
        ];

        $this->validate($request, $rules, $messages);

        $member = Staff::where('ippis', $ippis)->first();

        $CommodityRecord = Commodity::where('ippis', $ippis)->latest('id')->first();

        if(isset($CommodityRecord)) {
            // $CommodityPaymentRecord = CommodityPayment::where('commodity_id', $CommodityRecord->id)->latest('id')->first();
            $CommodityPaymentRecord = $CommodityRecord->payments->last();
            
            $commodity_bal = isset($CommodityPaymentRecord) ? $CommodityPaymentRecord->bal - $request->total_amount : 0 - $request->total_amount;

        } else {

            Toastr::error('No Commodity Loan exists', 'Error', ["positionClass" => "toast-bottom-right"]);
            return redirect()->route('members.commodity', $ippis);

        }

        // make entry in short term payments table
        $commodityPayment = new CommodityPayment;
        $commodityPayment->ippis = $ippis;
        $commodityPayment->pay_point      = $member->pay_point;
        $commodityPayment->ref = $request->ref;
        $commodityPayment->deposit_date   = $request->deposit_date;
        $commodityPayment->commodity_id = $CommodityRecord->id;
        $commodityPayment->dr = 0.00;
        $commodityPayment->cr = $request->total_amount;
        $commodityPayment->bal = $commodity_bal;
        $commodityPayment->month = Carbon::today()->format('m');
        $commodityPayment->year = Carbon::today()->format('Y');
        $commodityPayment->save();


        // make ledger entry
        $ledger                 = new Ledger;
        $ledger->staff_id       = $member->id;
        $ledger->pay_point      = $member->pay_point;
        $ledger->date           = Carbon::today()->format('Y-m-d');
        $ledger->ref            = $request->ref;
        $ledger->deposit_date   = $request->deposit_date;
        $ledger->commodity_dr   = 0.00;
        $ledger->commodity_cr   = $request->total_amount;
        $ledger->commodity_bal  = $commodity_bal;
        $ledger->save();

        // dd($stl, $ledger);

        Toastr::success('Repayent successful', 'Success', ["positionClass" => "toast-bottom-right"]);
        return redirect()->route('members.commodity', $ippis);

    }


    function loanDetails($loanID) {
        $data['loan'] = Commodity::find($loanID);

        return view('members.commodity.loan_details', $data);
    }
}
