<?php

namespace App\Http\Controllers;

use App\Staff;
use App\ShortTerm;
use App\ShortTermPayment;
use App\LongTermPayment;
use App\Ledger;
use Carbon\Carbon;
use Toastr;
use App\MonthlySaving;
use App\MonthlySavingsPayment;

use Illuminate\Http\Request;

class ShortTermController extends Controller
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
     * Short term loans
     */
    function shortTermLoans($ippis) {

        $data['member'] = Staff::where('ippis', $ippis)->first();

        if(!isset($data['member'])) {
            Toastr::error('This member does not exist', 'Error', ["positionClass" => "toast-bottom-right"]);
            return redirect()->route('members.longTermLoans', $ippis);
        }

        $data['shortTermLoans'] = ShortTerm::with('payments')->where('ippis', $ippis)->get();
        // dd($data['shortTermLoans']);

        return view('members.short_term.short_term', $data);
    }

    /**
     * Short term loans
     */
    function newShortLoan($ippis) {
        $data['member'] = Staff::where('ippis', $ippis)->first();

        if(!isset($data['member'])) {
            Toastr::error('This member does not exist', 'Error', ["positionClass" => "toast-bottom-right"]);
            return redirect()->route('members.longTermLoans', $ippis);
        }
        
        if(request()->ajax()) {
            $savings = MonthlySavingsPayment::where('ippis', $ippis)->latest('id')->first();
            $periods = [
                ['key' => '3',  'value' => "3 Months",],
                ['key' => '4',  'value' => "4 Months",],
                ['key' => '5',  'value' => "5 Months",],
            ];

            return ['savings' => $savings, 'periods' => $periods];
        } 

        return view('members.short_term.new_short_term_loan', $data);
    }

    /**
     * Short term loans
     */
    function postNewShortLoan(Request $request, $ippis) {
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

        $shortShortTermRecord = ShortTerm::where('ippis', $ippis)->latest('id')->first();

        if(isset($shortShortTermRecord)) {
            // $shortShortTermPaymentRecord = ShortTermPayment::where('short_term_id', $shortShortTermRecord->id)->latest('id')->first();
            $shortShortTermPaymentRecord = $shortShortTermRecord->payments->last();
            
            $short_term_bal = isset($shortShortTermPaymentRecord) ? $shortShortTermPaymentRecord->bal + $request->total_amount : $request->total_amount;

        } else {

            $short_term_bal = $request->total_amount;

        }

        // get loan end date
        $loanEndDate = Carbon::parse($request->loan_date)->addMonths($request->no_of_months);

        // make entry in short term loan table
        $stl = new ShortTerm;
        $stl->ref = $request->ref;
        $stl->loan_date = $request->loan_date;
        $stl->loan_end_date = $loanEndDate;
        $stl->ippis = $ippis;
        $stl->no_of_months = $request->no_of_months;
        $stl->total_amount = $request->total_amount;
        $stl->monthly_amount = $request->total_amount / $request->no_of_months;
        $stl->save();

        // make entry in short term payments table
        $stlPayment = new ShortTermPayment;
        $stlPayment->ippis = $ippis;
        $stlPayment->pay_point      = $member->pay_point;
        $stlPayment->ref = $request->ref;
        $stlPayment->loan_date = $request->loan_date;
        $stlPayment->short_term_id = $stl->id;
        $stlPayment->dr = $request->total_amount;
        $stlPayment->cr = 0.00;
        $stlPayment->bal = $short_term_bal;
        $stlPayment->month = Carbon::today()->format('m');
        $stlPayment->year = Carbon::today()->format('Y');
        $stlPayment->save();


        // make ledger entry
        $ledger                 = new Ledger;
        $ledger->staff_id       = $member->id;
        $ledger->pay_point      = $member->pay_point;
        $ledger->date           = Carbon::today()->format('Y, m, d');
        $ledger->ref            = $request->ref;
        $ledger->loan_date      = $request->loan_date;
        $ledger->short_term_dr   = $request->total_amount;
        $ledger->short_term_cr   = 0.00;
        $ledger->short_term_bal  = $short_term_bal;
        $ledger->save();

        Toastr::success('Loan successful', 'Success', ["positionClass" => "toast-bottom-right"]);
        return redirect()->route('members.shortTermLoans', $ippis);
    }

    /**
     * Show reayment form
     */
    function shortLoanRepayment($ippis) {
        $member = Staff::where('ippis', $ippis)->first();

        if(!isset($member)) {
            Toastr::error('This member does not exist', 'Error', ["positionClass" => "toast-bottom-right"]);
            return redirect()->route('members.longTermLoans', $ippis);
        }

        $shortTermLoans = ShortTerm::pluck('ref', 'id');
        $lastLongTermPayment = LongTermPayment::where('ippis', $ippis)->latest('id')->first();
        $lastShortTermPayment = ShortTermPayment::where('ippis', $ippis)->latest('id')->first();

        $last_short_term_loan_payment = $lastShortTermPayment ? : 0;

        $last_savings = $member->monthly_savings_payments->last();
        $savings_bal = isset($last_savings) ? $last_savings->bal : 0;

        $repaymentModes = [
            ['key' => 'savings', 'value' => 'From savings'],
            ['key' => 'direct_deduction', 'value' => 'Direct deduction'],
            ['key' => 'bank_deposit', 'value' => 'Bank deposit'],
        ];

        if (request()->ajax()) {
            return [
                'short_term_loans'       => $shortTermLoans, 
                'last_short_term_loan_payment' => $last_short_term_loan_payment,
                'member'                => $member, 
                'repayment_modes'       => $repaymentModes,
                'last_long_term_payment'   => $lastLongTermPayment,
                'savings_bal'           => $savings_bal,
            ];
        }

        $data['shortTermLoans']  = $shortTermLoans;
        $data['member']         = $member;
        $data['repaymentModes'] = $repaymentModes;

        return view('members.short_term.repayment', $data);
    }

    /**
     * Show reayment form
     */
    function postShortLoanRepayment(Request $request, $ippis) {
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
            'total_amount.required' => 'Kindly enter the amount to deduct',
            'repayment_mode.required' => 'Kindly select a repayment type',
        ];

        $this->validate($request, $rules, $messages);

        $member = Staff::where('ippis', $ippis)->first();

        $shortShortTermRecord = ShortTerm::where('ippis', $ippis)->latest('id')->first();

        if(isset($shortShortTermRecord)) {
            // $shortShortTermPaymentRecord = ShortTermPayment::where('short_term_id', $shortShortTermRecord->id)->latest('id')->first();
            $shortShortTermPaymentRecord = $shortShortTermRecord->payments->last();
            
            $short_term_bal = isset($shortShortTermPaymentRecord) ? $shortShortTermPaymentRecord->bal - $request->total_amount : 0 - $request->total_amount;

        } else {

            Toastr::error('No Short Term Loan exists', 'Error', ["positionClass" => "toast-bottom-right"]);
            return redirect()->route('members.shortTermLoans', $ippis);

        }

        if($request->repayment_mode == 'savings') {

            $lastMonthlySavingRecord = MonthlySaving::where('ippis', $ippis)->latest('id')->first();

            $lastMonthlySavingsPaymentRecord = MonthlySavingsPayment::where('ippis', $ippis)->latest('id')->first();
                
            $savings_bal = isset($lastMonthlySavingsPaymentRecord) ? $lastMonthlySavingsPaymentRecord->bal - $request->total_amount : 0 - $request->total_amount;


            // make entry in long term payments table
            $msPayment = new MonthlySavingsPayment;
            $msPayment->monthly_saving_id = $lastMonthlySavingRecord ? $lastMonthlySavingRecord->id : 0;
            $msPayment->ippis = $request->ippis;
            $msPayment->pay_point      = $member->pay_point;
            $msPayment->ref = $request->ref;
            $msPayment->withdrawal_date = $request->deposit_date;
            $msPayment->dr = $request->total_amount;
            $msPayment->cr = 0.00;
            $msPayment->bal = $savings_bal;
            $msPayment->month = Carbon::today()->format('m');
            $msPayment->year = Carbon::today()->format('Y');
            $msPayment->save();


            // make ledger entry
            $ledger                 = new Ledger;
            $ledger->staff_id       = $member->id;
            $ledger->pay_point      = $member->pay_point;
            $ledger->date           = Carbon::today()->format('Y-m-d');
            $ledger->ref            = $request->ref;
            $ledger->deposit_date   = $request->deposit_date;
            $ledger->savings_dr     = $request->total_amount;
            $ledger->savings_cr     = 0.00;
            $ledger->savings_bal    = $savings_bal;
            $ledger->save();
        }

        // make entry in short term payments table
        $stlPayment = new ShortTermPayment;
        $stlPayment->ippis = $ippis;
        $stlPayment->pay_point      = $member->pay_point;
        $stlPayment->ref = $request->ref;
        $stlPayment->deposit_date   = $request->deposit_date;
        $stlPayment->short_term_id = $shortShortTermRecord->id;
        $stlPayment->dr = 0.00;
        $stlPayment->cr = $request->total_amount;
        $stlPayment->bal = $short_term_bal;
        $stlPayment->month = Carbon::today()->format('m');
        $stlPayment->year = Carbon::today()->format('Y');
        $stlPayment->save();


        // make ledger entry
        if($request->repayment_mode != 'savings') {
            $ledger                 = new Ledger;
        }
        $ledger->staff_id       = $member->id;
        $ledger->pay_point      = $member->pay_point;
        $ledger->date           = Carbon::today()->format('Y-m-d');
        $ledger->ref            = $request->ref;
        $ledger->deposit_date   = $request->deposit_date;
        $ledger->short_term_dr   = 0.00;
        $ledger->short_term_cr   = $request->total_amount;
        $ledger->short_term_bal  = $short_term_bal;
        $ledger->save();

        // dd($stl, $ledger);

        Toastr::success('Repayent successful', 'Success', ["positionClass" => "toast-bottom-right"]);
        return redirect()->route('members.shortTermLoans', $ippis);

    }


    function loanDetails($loanID) {
        $data['loan'] = ShortTerm::find($loanID);

        return view('members.short_term.loan_details', $data);
    }

}
