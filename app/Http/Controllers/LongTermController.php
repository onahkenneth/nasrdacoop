<?php

namespace App\Http\Controllers;

use App\Staff;
use App\LongTerm;
use App\LongTermPayment;
use App\Ledger;
use Carbon\Carbon;
use Toastr;
use App\MonthlySaving;
use App\MonthlySavingsPayment;

use Illuminate\Http\Request;

class LongTermController extends Controller
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
     * Long term loans
     */
    function longTermLoans($ippis) {

        $data['member'] = Staff::where('ippis', $ippis)->first();

        if(!isset($data['member'])) {
            Toastr::error('This member does not exist', 'Error', ["positionClass" => "toast-bottom-right"]);
            return redirect()->route('members.longTermLoans', $ippis);
        }

        $data['longTermLoans'] = LongTerm::with('payments')->where('ippis', $ippis)->get();
        // dd($data['longTermLoans']);

        return view('members.long_term.long_term', $data);
    }

    /**
     * Long term loans
     */
    function newLongLoan($ippis) {
        $data['member'] = Staff::where('ippis', $ippis)->first();

        if(!isset($data['member'])) {
            Toastr::error('This member does not exist', 'Error', ["positionClass" => "toast-bottom-right"]);
            return redirect()->route('members.longTermLoans', $ippis);
        }
        
        if(request()->ajax()) {
            $savings = MonthlySavingsPayment::where('ippis', $ippis)->latest('id')->first();
            $periods = [
                ['key' => '36',  'value' => "36 Months",],
                ['key' => '20',  'value' => "20 Months",],
                ['key' => '15',  'value' => "15 Months",],
                ['key' => '10',  'value' => "10 Months",],
            ];

            return ['savings' => $savings, 'periods' => $periods];
        }

        return view('members.long_term.new_long_term_loan', $data);
    }

    /**
     * Long term loans
     */
    function postNewLongLoan(Request $request, $ippis) {
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

        $longLongTermRecord = LongTerm::where('ippis', $ippis)->latest('id')->first();

        if(isset($longLongTermRecord)) {
            // $longLongTermPaymentRecord = LongTermPayment::where('long_term_id', $longLongTermRecord->id)->latest('id')->first();
            $longLongTermPaymentRecord = $longLongTermRecord->payments->last();
            
            $long_term_bal = isset($longLongTermPaymentRecord) ? $longLongTermPaymentRecord->bal + $request->total_amount : $request->total_amount;

        } else {

            $long_term_bal = $request->total_amount;

        }

        // get loan end date
        $loanEndDate = Carbon::parse($request->loan_date)->addMonths($request->no_of_months);

        // make entry in long term loan table
        $ltl = new LongTerm;
        $ltl->ref = $request->ref;
        $ltl->loan_date = $request->loan_date;
        $ltl->loan_end_date = $loanEndDate;
        $ltl->ippis = $ippis;
        $ltl->no_of_months = $request->no_of_months;
        $ltl->total_amount = $request->total_amount;
        $ltl->monthly_amount = $request->total_amount / $request->no_of_months;
        $ltl->save();

        // make entry in long term payments table
        $ltlPayment = new LongTermPayment;
        $ltlPayment->ippis = $ippis;
        $ltlPayment->pay_point      = $member->pay_point;
        $ltlPayment->ref = $request->ref;
        $ltlPayment->loan_date      = $request->loan_date;
        $ltlPayment->long_term_id = $ltl->id;
        $ltlPayment->dr = $request->total_amount;
        $ltlPayment->cr = 0.00;
        $ltlPayment->bal = $long_term_bal;
        $ltlPayment->month = Carbon::today()->format('m');
        $ltlPayment->year = Carbon::today()->format('Y');
        $ltlPayment->save();


        // make ledger entry
        $ledger                 = new Ledger;
        $ledger->staff_id       = $member->id;
        $ledger->pay_point      = $member->pay_point;
        $ledger->date           = Carbon::today()->format('Y-m-d');
        $ledger->ref            = $request->ref;
        $ledger->loan_date      = $request->loan_date;
        $ledger->long_term_dr   = $request->total_amount;
        $ledger->long_term_cr   = 0.00;
        $ledger->long_term_bal  = $long_term_bal;
        $ledger->save();

        // dd($ltl, $ledger);

        Toastr::success('Loan successful', 'Success', ["positionClass" => "toast-bottom-right"]);
        return redirect()->route('members.longTermLoans', $ippis);
    }

    /**
     * Show reayment form
     */
    function longLoanRepayment($ippis) {
        $member = Staff::where('ippis', $ippis)->first();

        if(!isset($member)) {
            Toastr::error('This member does not exist', 'Error', ["positionClass" => "toast-bottom-right"]);
            return redirect()->route('members.longTermLoans', $ippis);
        }

        $longTermLoans = LongTerm::pluck('ref', 'id');
        $lastLongTermPayment = LongTermPayment::where('ippis', $ippis)->latest('id')->first();

        $last_long_term_loan_payment = $lastLongTermPayment ? : 0;

        $last_savings = $member->monthly_savings_payments->last();
        $savings_bal = isset($last_savings) ? $last_savings->bal : 0;

        $repaymentModes = [
            ['key' => 'savings', 'value' => 'From savings'],
            ['key' => 'direct_deduction', 'value' => 'Direct deduction'],
            ['key' => 'bank_deposit', 'value' => 'Bank deposit'],
        ];

        if (request()->ajax()) {
            return [
                'long_term_loans'       => $longTermLoans, 
                'last_long_term_loan_payment'       => $last_long_term_loan_payment, 
                'member'                => $member, 
                'repayment_modes'       => $repaymentModes,
                'last_long_term_payment'   => $lastLongTermPayment,
                'savings_bal'           => $savings_bal,
            ];
        }

        $data['longTermLoans']  = $longTermLoans;
        $data['member']         = $member;
        $data['repaymentModes'] = $repaymentModes;
        $data['savings_bal']    = $savings_bal;

        return view('members.long_term.repayment', $data);
    }

    /**
     * Show reayment form
     */
    function postLongLoanRepayment(Request $request, $ippis) {
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
            'ref.required' => 'The ref is required',
            'deposit_date.required' => 'The repayment date is required',
            'ippis.required' => 'This IPPIS Number is required',
            'total_amount.required' => 'Kindly enter the amount to deduct',
            'repayment_mode.required' => 'Kindly select a repayment type',
        ];

        $this->validate($request, $rules, $messages);

        $member = Staff::where('ippis', $ippis)->first();

        $longLongTermRecord = LongTerm::where('ippis', $ippis)->latest('id')->first();

        if(isset($longLongTermRecord)) {
            // $longLongTermPaymentRecord = LongTermPayment::where('long_term_id', $longLongTermRecord->id)->latest('id')->first();
            $longLongTermPaymentRecord = $longLongTermRecord->payments->last();
            
            $long_term_bal = isset($longLongTermPaymentRecord) ? $longLongTermPaymentRecord->bal - $request->total_amount : 0 - $request->total_amount;

        } else {

            Toastr::error('No Long Term Loan exists', 'Error', ["positionClass" => "toast-bottom-right"]);
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

        // make entry in long term payments table
        $ltlPayment = new LongTermPayment;
        $ltlPayment->ippis = $ippis;
        $ltlPayment->pay_point      = $member->pay_point;
        $ltlPayment->ref = $request->ref;
        $ltlPayment->deposit_date   = $request->deposit_date;
        $ltlPayment->long_term_id = $longLongTermRecord->id;
        $ltlPayment->dr = 0.00;
        $ltlPayment->cr = $request->total_amount;
        $ltlPayment->bal = $long_term_bal;
        $ltlPayment->month = Carbon::today()->format('m');
        $ltlPayment->year = Carbon::today()->format('Y');
        $ltlPayment->save();


        // make ledger entry
        if($request->repayment_mode != 'savings') {
            $ledger                 = new Ledger;
        }
        $ledger->staff_id       = $member->id;
        $ledger->pay_point      = $member->pay_point;
        $ledger->date           = Carbon::today()->format('Y-m-d');
        $ledger->ref            = $request->ref;
        $ledger->deposit_date   = $request->deposit_date;
        $ledger->long_term_dr   = 0.00;
        $ledger->long_term_cr   = $request->total_amount;
        $ledger->long_term_bal  = $long_term_bal;
        $ledger->save();

        // dd($ltl, $ledger);
        Toastr::success('Repayent successful', 'Success', ["positionClass" => "toast-bottom-right"]);

        return redirect()->route('members.longTermLoans', $ippis);

    }



    function loanDetails($loanID) {
        $data['loan'] = LongTerm::find($loanID);

        return view('members.long_term.loan_details', $data);
    }

}
