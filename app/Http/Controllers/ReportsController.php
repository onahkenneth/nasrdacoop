<?php

namespace App\Http\Controllers;

use App\MonthlySaving;
use App\MonthlySavingsPayment;
use App\LongTerm;
use App\LongTermPayment;
use App\LongTermLoanDefault;
use App\ShortTerm;
use App\ShortTermPayment;
use App\ShortTermLoanDefault;
use App\Commodity;
use App\CommodityPayment;
use App\CommodityLoanDefault;
use App\Center;

use App\Staff;
use Carbon\Carbon;

use Illuminate\Http\Request;

class ReportsController extends Controller
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

    
    function index(Request $request) {

        // dd($request->all());

        $data['centers'] = Center::pluck('name', 'id');
        $data['date_from'] = Carbon::now()->startOfYear();
        $data['date_to'] = Carbon::now()->endOfYear();

        // select all members of a pay point
        $members = Staff::where('pay_point', $request->pay_point)->where('is_active', 1)->orderBy('full_name')->get();

        $membersReports = [];
        foreach($members as $member) {

            $savings = $member->monthly_savings_payments;

            // Long term loan reports
            $LongTermsQuery = LongTerm::query();
            $LongTermsQuery = $LongTermsQuery->where('ippis', $member->ippis);

            if ($request->date_from) {    
                $data['date_from'] = $request->date_from;
                $data['date_to'] = $request->date_to; 
                $LongTermsQuery = $LongTermsQuery->whereBetween('loan_date', [$data['date_from'], $data['date_to']]);
            }
            $LongTerms = $LongTermsQuery->get();

            $LongTermLoans = [];
            $LTLTotalBal = 0;
            foreach ($LongTerms as $LongTerm) {
                $ltl['LTLAmtLoaned'] = $LongTerm->total_amount;
                $ltl['LTLDuration'] = $LongTerm->no_of_months;
                $ltl['LTLBal'] = LongTermPayment::where(['ippis' => $member->ippis, 'long_term_id' => $LongTerm->id])->latest('id')->first()->bal;

                $LTLTotalBal += $ltl['LTLBal'];

                $LongTermLoans[] = $ltl;
            }

            // Short term loan reports
            $ShortTermsQuery = ShortTerm::query();
            $ShortTermsQuery = $ShortTermsQuery->where('ippis', $member->ippis);

            if ($request->date_from) {    
                $data['date_from'] = $request->date_from;
                $data['date_to'] = $request->date_to; 
                $ShortTermsQuery = $ShortTermsQuery->whereBetween('loan_date', [$data['date_from'], $data['date_to']]);
            }
            $ShortTerms = $ShortTermsQuery->get();

            $ShortTermLoans = [];
            $STLTotalBal = 0;
            foreach ($ShortTerms as $ShortTerm) {
                $stl['STLAmtLoaned'] = $ShortTerm->total_amount;
                $stl['STLDuration'] = $ShortTerm->no_of_months;
                $stl['STLBal'] = ShortTermPayment::where(['ippis' => $member->ippis, 'short_term_id' => $ShortTerm->id])->latest('id')->first()->bal;

                $STLTotalBal += $stl['STLBal'];

                $ShortTermLoans[] = $stl;
            }

            // Commodities loan reports
            $CommoditiesQuery = Commodity::query();
            $CommoditiesQuery = $CommoditiesQuery->where('ippis', $member->ippis);

            if ($request->date_from) {          
                $data['date_from'] = $request->date_from;
                $data['date_to'] = $request->date_to; 
                $CommoditiesQuery = $CommoditiesQuery->whereBetween('loan_date', [$data['date_from'], $data['date_to']]);
            }
            $Commodities = $CommoditiesQuery->get();

            $CommodityLoans = [];
            $COMMTotalBal = 0;
            foreach ($Commodities as $Commodity) {
                $comm['COMMAmtLoaned'] = $Commodity->total_amount;
                $comm['COMMDuration'] = $Commodity->no_of_months;

                $totalPayments = CommodityPayment::where(['ippis' => $member->ippis, 'commodity_id' => $Commodity->id])->latest('id')->sum('cr');
                $comm['COMMBal'] = $Commodity->total_amount - $totalPayments;

                $COMMTotalBal += $comm['COMMBal'];

                $CommodityLoans[] = $comm;
            }

            $membersReports[] = ['member' => $member, 'savings' => $savings, 'LongTermLoans' => $LongTermLoans, 'ShortTermLoans' => $ShortTermLoans, 'CommodityLoans' => $CommodityLoans];
        }
        // dd($membersReports);

        $data['membersReports'] = $membersReports; 

        return view('reports.index', $data);
    }

    /**
     * Generate report for those who have defaulted on their monthly loan payment
     */
    function monthlyDefaults(Request $request) {

        // dd($request->all());

        $data['centers'] = Center::pluck('name', 'id');
        $data['date_from'] = Carbon::now()->startOfYear();
        $data['date_to'] = Carbon::now()->endOfYear();

        // select all members of a pay point
        $members = Staff::where('pay_point', $request->pay_point)->where('is_active', 1)->orderBy('full_name')->get();

        $membersReports = [];
        foreach($members as $member) {

            // Long term loan reports
            $LongTermLoanDefaultQuery = LongTermLoanDefault::query();
            $LongTermLoanDefaultQuery = $LongTermLoanDefaultQuery->where('ippis', $member->ippis);

            if ($request->date_from) {    
                $data['date_from'] = $request->date_from;
                $data['date_to'] = $request->date_to; 
                $LongTermLoanDefaultQuery = $LongTermLoanDefaultQuery->whereBetween('created_at', [$data['date_from'], $data['date_to']]);
            }

            $LongTermLoanDefault = $LongTermLoanDefaultQuery->get();

            // Short term loan reports
            $ShortTermLoanDefaultQuery = ShortTermLoanDefault::query();
            $ShortTermLoanDefaultQuery = $ShortTermLoanDefaultQuery->where('ippis', $member->ippis);

            if ($request->date_from) {    
                $data['date_from'] = $request->date_from;
                $data['date_to'] = $request->date_to; 
                $ShortTermLoanDefaultQuery = $ShortTermLoanDefaultQuery->whereBetween('created_at', [$data['date_from'], $data['date_to']]);
            }

            $ShortTermLoanDefault = $ShortTermLoanDefaultQuery->get();

            // Commodities loan reports
            $CommodityLoanDefaultQuery = CommodityLoanDefault::query();
            $CommodityLoanDefaultQuery = $CommodityLoanDefaultQuery->where('ippis', $member->ippis);

            if ($request->date_from) {    
                $data['date_from'] = $request->date_from;
                $data['date_to'] = $request->date_to; 
                $CommodityLoanDefaultQuery = $CommodityLoanDefaultQuery->whereBetween('created_at', [$data['date_from'], $data['date_to']]);
            }

            $CommodityLoanDefault = $CommodityLoanDefaultQuery->get();
            // dd(($LongTermLoanDefault)->isNotEmpty());

            if(($LongTermLoanDefault->isNotEmpty()) || ($ShortTermLoanDefault->isNotEmpty()) || ($CommodityLoanDefault->isNotEmpty())) {
                $membersReports[] = ['member' => $member, 'LongTermLoanDefault' => $LongTermLoanDefault, 'ShortTermLoanDefault' => $ShortTermLoanDefault, 'CommodityLoanDefault' => $CommodityLoanDefault];
            }
            
        }
        // dd($membersReports);

        $data['membersReports'] = $membersReports; 

        return view('reports.monthlyDefaults', $data);

    }

    /**
     * Generate report for those who have did not finish paying theor loan when due
     */
    function loanDefaults(Request $request) {

        // dd($request->all());

        $data['centers'] = Center::pluck('name', 'id');
        $data['date_from'] = Carbon::now()->startOfYear();
        $data['date_to'] = Carbon::now()->endOfYear();

        // select all members of a pay point
        $members = Staff::where('pay_point', $request->pay_point)->where('is_active', 1)->orderBy('full_name')->get();

        $membersReports = [];
        foreach($members as $member) {

            // Long term loan reports
            $LongTermsQuery = LongTerm::query();
            $LongTermsQuery = $LongTermsQuery->where('ippis', $member->ippis);

            if ($request->date_from) {    
                $data['date_from'] = $request->date_from;
                $data['date_to'] = $request->date_to; 
                $LongTermsQuery = $LongTermsQuery->whereBetween('loan_date', [$data['date_from'], $data['date_to']]);
            }
            $LongTerms = $LongTermsQuery->get();

            $LongTermLoans = [];
            $LTLTotalBal = 0;
            foreach ($LongTerms as $LongTerm) {
                if($LongTerm->checkLoanDefault()) {
                    $ltl['LTLAmtLoaned'] = $LongTerm->total_amount;
                    $ltl['LTLLoanDate'] = $LongTerm->loan_date;
                    $ltl['LTLLoanEndDate'] = $LongTerm->loan_end_date;
                    $ltl['LTLBal'] = $LongTerm->payments->last()->bal;
                    $LongTermLoans[] = $ltl;
                }
            }

            // Short term loan reports
            $ShortTermsQuery = ShortTerm::query();
            $ShortTermsQuery = $ShortTermsQuery->where('ippis', $member->ippis);

            if ($request->date_from) {    
                $data['date_from'] = $request->date_from;
                $data['date_to'] = $request->date_to; 
                $ShortTermsQuery = $ShortTermsQuery->whereBetween('loan_date', [$data['date_from'], $data['date_to']]);
            }
            $ShortTerms = $ShortTermsQuery->get();

            $ShortTermLoans = [];
            $STLTotalBal = 0;
            foreach ($ShortTerms as $ShortTerm) {
                if($ShortTerm->checkLoanDefault()) {
                    $stl['STLAmtLoaned'] = $ShortTerm->total_amount;
                    $stl['STLLoanDate'] = $ShortTerm->loan_date;
                    $stl['STLLoanEndDate'] = $ShortTerm->loan_end_date;
                    $stl['STLBal'] = $ShortTerm->payments->last()->bal;
                    $ShortTermLoans[] = $stl;
                }
            }

            // Commodities loan reports
            $CommoditiesQuery = Commodity::query();
            $CommoditiesQuery = $CommoditiesQuery->where('ippis', $member->ippis);

            if ($request->date_from) {          
                $data['date_from'] = $request->date_from;
                $data['date_to'] = $request->date_to; 
                $CommoditiesQuery = $CommoditiesQuery->whereBetween('loan_date', [$data['date_from'], $data['date_to']]);
            }
            $Commodities = $CommoditiesQuery->get();

            $CommodityLoans = [];
            $COMMTotalBal = 0;
            foreach ($Commodities as $Commodity) {
                if($Commodity->checkLoanDefault()) {
                    $comm['COMMAmtLoaned'] = $Commodity->total_amount;
                    $comm['COMMLoanDate'] = $Commodity->loan_date;
                    $comm['COMMLoanEndDate'] = $Commodity->loan_end_date;
                    $comm['COMMBal'] = $Commodity->payments->last()->bal;
                    $CommodityLoans[] = $comm;
                }
            }

            if(!empty($LongTermLoans) || !empty($ShortTermLoans) || !empty($CommodityLoans)) {
                $membersReports[] = ['member' => $member, 'LongTermLoans' => $LongTermLoans, 'ShortTermLoans' => $ShortTermLoans, 'CommodityLoans' => $CommodityLoans];
            }
            
        }
        // dd($membersReports);

        $data['membersReports'] = $membersReports; 

        return view('reports.loanDefaults', $data);
    }
}
