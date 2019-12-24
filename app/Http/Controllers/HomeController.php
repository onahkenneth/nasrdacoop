<?php

namespace App\Http\Controllers;

use App\MonthlySavingsPayment;
use App\LongTermPayment;
use App\ShortTermPayment;
use App\CommodityPayment;
use App\Center;

use Illuminate\Http\Request;
use App\Charts\SavingsByCenter;
use App\Charts\LongTermLoansByCenter;
use App\Charts\ShortTermLoansByCenter;
use App\Charts\CommodityLoansByCenter;

class HomeController extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $data['totalSavings'] = MonthlySavingsPayment::sum('cr');
        $data['totalLTL'] = LongTermPayment::sum('dr');
        $data['totalSTL'] = ShortTermPayment::sum('dr');
        $data['totalCommodity'] = CommodityPayment::sum('dr');

        $centers = Center::pluck('name', 'id');
        
        // monthly savings chart
        $monthlySavingsData = MonthlySavingsPayment::get();
        $monthlySavingsData = $monthlySavingsData->groupBy('pay_point')
        ->map(function ($item) {
            return ($item->sum('cr'));
        });

        $monthlySavingsData = $monthlySavingsData->keyBy(function ($value, $key) use($centers) {
            // dd($centers->toArray());
            if (isset($centers[$key])) {
                return $centers[$key];
            } else {
                return $key;
            }
        });
        
        $monthlySavings = new SavingsByCenter;
        $monthlySavings->labels($monthlySavingsData->keys());
        $monthlySavings->dataset('Monthly Savings', 'bar', $monthlySavingsData->values());

        $data['monthlySavings'] = $monthlySavings;
        
        // long term loans chart
        $longTermData = LongTermPayment::get();
        $longTermData = $longTermData->groupBy('pay_point')
        ->map(function ($item) {
            return ($item->sum('dr'));
        });

        $longTermData = $longTermData->keyBy(function ($value, $key) use($centers) {
            // dd($centers->toArray());
            if (isset($centers[$key])) {
                return $centers[$key];
            } else {
                return $key;
            }
        });
        
        $longTermChart = new LongTermLoansByCenter;
        $longTermChart->labels($longTermData->keys());
        $longTermChart->dataset('Long Term Loans', 'bar', $longTermData->values());

        $data['longTermChart'] = $longTermChart;
        
        // short term loans chart
        $shortTermData = ShortTermPayment::get();
        $shortTermData = $shortTermData->groupBy('pay_point')
        ->map(function ($item) {
            return ($item->sum('dr'));
        });

        $shortTermData = $shortTermData->keyBy(function ($value, $key) use($centers) {
            // dd($centers->toArray());
            if (isset($centers[$key])) {
                return $centers[$key];
            } else {
                return $key;
            }
        });
        
        $shortTermChart = new ShortTermLoansByCenter;
        $shortTermChart->labels($shortTermData->keys());
        $shortTermChart->dataset('Short Term Loans', 'bar', $shortTermData->values());

        $data['shortTermChart'] = $shortTermChart;
        
        // commodity loans chart
        $commodityData = COmmodityPayment::get();
        $commodityData = $commodityData->groupBy('pay_point')
        ->map(function ($item) {
            return ($item->sum('dr'));
        });

        $commodityData = $commodityData->keyBy(function ($value, $key) use($centers) {
            // dd($centers->toArray());
            if (isset($centers[$key])) {
                return $centers[$key];
            } else {
                return $key;
            }
        });
        
        $commodityChart = new CommodityLoansByCenter;
        $commodityChart->labels($commodityData->keys());
        $commodityChart->dataset('Commodities Loans', 'bar', $commodityData->values());

        $data['commodityChart'] = $commodityChart;

        return view('home', $data);
    }
}
