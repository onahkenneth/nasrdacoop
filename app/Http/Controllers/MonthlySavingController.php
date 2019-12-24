<?php

namespace App\Http\Controllers;

use App\Staff;
use App\MonthlySaving;
use App\MonthlySavingsPayment;
use App\Ledger;
use App\LongTermPayment;
use Carbon\Carbon;
use Toastr;

use Illuminate\Http\Request;

class MonthlySavingController extends Controller
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
    function monthlySavings($ippis) {

        $data['member'] = Staff::where('ippis', $ippis)->first();

        if(!isset($data['member'])) {
            Toastr::error('This member does not exist', 'Error', ["positionClass" => "toast-bottom-right"]);
            return redirect()->route('members.longTermLoans', $ippis);
        }

        $data['monthlySavings'] = MonthlySaving::with('payments')->where('ippis', $ippis)->get();
        // dd($data['monthlySavings']);

        return view('members.monthly_savings.monthly_savings', $data);
    }

    /**
     * Long term loans
     */
    function newSavings($ippis) {
        $data['member'] = Staff::where('ippis', $ippis)->first();   

        if(!isset($data['member'])) {
            Toastr::error('This member does not exist', 'Error', ["positionClass" => "toast-bottom-right"]);
            return redirect()->route('members.longTermLoans', $ippis);
        }

        return view('members.monthly_savings.new_monthly_savings', $data);
    }

    /**
     * Long term loans
     */
    function postNewSavings(Request $request, $ippis) {
        // dd($request->all(), $ippis);

        $rules = [
            'ippis' => 'required',
            'total_amount' => 'required',
            // 'email' => 'email|unique:staff,email',

        ];

        $messages = [
            'total_amount.required' => 'The amount to withdraw is required',
            'ippis.required' => 'This IPPIS Number is required',
        ];

        $this->validate($request, $rules, $messages);

        $member = Staff::where('ippis', $ippis)->first();

        $lastMonthlySavingRecord = MonthlySaving::where('ippis', $ippis)->latest('id')->first();

        if(isset($lastMonthlySavingRecord)) {
            $lastMonthlySavingsPaymentRecord = MonthlySavingsPayment::where('ippis', $ippis)->latest('id')->first();
            
            $savings_bal = isset($lastMonthlySavingsPaymentRecord) ? $lastMonthlySavingsPaymentRecord->bal + $request->total_amount : $request->total_amount;

        } else {

            $savings_bal = $request->total_amount;

        }

        
        // make entry in long term payments table
        $msPayment = new MonthlySavingsPayment;
        $msPayment->monthly_saving_id = $lastMonthlySavingRecord->id;
        $msPayment->ippis = $request->ippis;
        $msPayment->pay_point      = $member->pay_point;
        $msPayment->ref = $request->ref;
        $msPayment->dr = 0.00;
        $msPayment->cr = $request->total_amount;
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
        $ledger->savings_dr     = 0.00;
        $ledger->savings_cr     = $request->total_amount;
        $ledger->savings_bal    = $savings_bal;
        $ledger->save();

        // dd($ltl, $ledger);

        Toastr::success('Deposit successful', 'Success', ["positionClass" => "toast-bottom-right"]);
        return redirect()->route('members.savings', $ippis);
    }



    /**
     * Withdrawal
     */
    function savingsWithrawal($ippis) {
        $member = Staff::where('ippis', $ippis)->first(); 

        if(!isset($member)) {
            Toastr::error('This member does not exist', 'Error', ["positionClass" => "toast-bottom-right"]);
            return redirect()->route('members.longTermLoans', $ippis);
        }

        $lastLongTermPayment = LongTermPayment::where('ippis', $ippis)->latest('id')->first();

        $lastMonthlySaving = MonthlySavingsPayment::where('ippis', $ippis)->latest('id')->first();

        if (request()->ajax()) {
            return [
                'member'                    => $member, 
                'last_long_term_payment'    => $lastLongTermPayment,
                'last_monthly_saving'       => $lastMonthlySaving,
            ];
        }

        $data['member'] = $member;
        $data['lastMonthlySaving'] = $lastMonthlySaving;

        return view('members.monthly_savings.withdrawal', $data);
    }

    /**
     * Post withdrawal
     */
    function postSavingsWithrawal(Request $request, $ippis) {
        // dd($request->all(), $ippis);

        $rules = [
            'ippis' => 'required',
            'withdrawal_date' => 'required',
            'total_amount' => 'required',
            // 'email' => 'email|unique:staff,email',

        ];

        $messages = [
            'total_amount.required' => 'The amount to withdraw is required',
            'withdrawal_date.required' => 'The withdrawal date is required',
            'ippis.required' => 'This IPPIS Number is required',
        ];

        $this->validate($request, $rules, $messages);

        $member = Staff::where('ippis', $ippis)->first();

        $lastMonthlySavingRecord = MonthlySaving::where('ippis', $ippis)->latest('id')->first();

        if(isset($lastMonthlySavingRecord)) {
            $lastMonthlySavingsPaymentRecord = MonthlySavingsPayment::where('monthly_saving_id', $lastMonthlySavingRecord->id)->latest('id')->first();
            
            $savings_bal = isset($lastMonthlySavingsPaymentRecord) ? $lastMonthlySavingsPaymentRecord->bal - $request->total_amount : 0 - $request->total_amount;

        } else {

            Toastr::error('It seems there is no money in this account', 'Error', ["positionClass" => "toast-bottom-right"]);
            return redirect()->route('members.savings', $ippis);

        }

        // make entry in long term payments table
        $msPayment = new MonthlySavingsPayment;
        $msPayment->monthly_saving_id = $lastMonthlySavingRecord->id;
        $msPayment->ippis = $request->ippis;
        $msPayment->pay_point      = $member->pay_point;
        $msPayment->ref = $request->ref;
        $msPayment->withdrawal_date = $request->withdrawal_date;
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
        $ledger->withdrawal_date            = $request->withdrawal_date;
        $ledger->savings_dr     = $request->total_amount;
        $ledger->savings_cr     = 0.00;
        $ledger->savings_bal    = $savings_bal;
        $ledger->save();

        // dd($ltl, $ledger);

        Toastr::success('Withdrawal successful', 'Success', ["positionClass" => "toast-bottom-right"]);
        return redirect()->route('members.savings', $ippis);
    }


    /**
     * Long term loans
     */
    function savingsChangeObligation($ippis) {
        $data['member'] = Staff::where('ippis', $ippis)->first();

        if(!isset($data['member'])) {
            Toastr::error('This member does not exist', 'Error', ["positionClass" => "toast-bottom-right"]);
            return redirect()->route('members.longTermLoans', $ippis);
        }

        return view('members.monthly_savings.change_obligation', $data);
    }

    /**
     * Post withdrawal
     */
    function postSavingsChangeObligation(Request $request, $ippis) {
        // dd($request->all(), $ippis);

        $rules = [
            'ippis' => 'required',
            'new_obligation' => 'required',
            // 'email' => 'email|unique:staff,email',

        ];

        $messages = [
            'new_obligation.required' => 'The new obligation amount is required',
            'ippis.required' => 'This IPPIS Number is required',
        ];

        $this->validate($request, $rules, $messages);

        $member = Staff::where('ippis', $ippis)->first();

        $monthlySaving = new MonthlySaving;
        $monthlySaving->ippis = $request->ippis;
        $monthlySaving->amount = $request->new_obligation;
        $monthlySaving->save();

        Toastr::success('Obligation successfully changed', 'Success', ["positionClass" => "toast-bottom-right"]);
        return redirect()->route('members.savings', $ippis);
    }

}
