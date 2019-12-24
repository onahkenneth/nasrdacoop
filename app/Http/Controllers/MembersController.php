<?php

namespace App\Http\Controllers;

use App\Staff;
use App\User;
use App\MonthlySaving;
use App\LongTerm;
use App\ShortTerm;
use App\Commodity;
use App\Ledger;
use App\Center;
use Carbon\Carbon;

use Illuminate\Http\Request;
use LaravelDaily\LaravelCharts\Classes\LaravelChart;

class MembersController extends Controller
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // dd($request->all());

        $membersQuery = Staff::query();

        if ($request->pay_point) {
            $membersQuery = $membersQuery->where('pay_point', $request->pay_point);
        } 

        if ($request->status) {
            $membersQuery = $membersQuery->where('is_active', $request->status);
        }        

        $data['members'] = $membersQuery->paginate(20);
        $data['centers'] = Center::pluck('name', 'id');

        return view('members.index', $data);
    }

    public function show($ippis) {


        $data['member'] = Staff::where('ippis', $ippis)->first();

        return view('members.show', $data);
    }

    /**
     * Display a member's ledger.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard($ippis)
    {
        $data['member'] = Staff::where('ippis', $ippis)->first();
        $chart_options = [
            'chart_title' => 'Transactions by dates',
            'report_type' => 'group_by_date',
            'model' => 'App\Ledger',
            'group_by_field' => 'created_at',
            'group_by_period' => 'day',
            'aggregate_function' => 'sum',
            'aggregate_field' => 'savings_bal',
            'chart_type' => 'line',
        ];
        $data['chart1'] = new LaravelChart($chart_options);

        return view('members.dashboard', $data);
    }

    /**
     * Display a member's ledger.
     *
     * @return \Illuminate\Http\Response
     */
    public function ledger(Request $request, $ippis)
    {
        // dd($request, $ippis);

        $data['date_from'] = Carbon::now()->startOfYear();
        $data['date_to'] = Carbon::now()->endOfYear();
        $data['staff'] = Staff::where('ippis', $ippis)->first();
        $ledgerQuery = Ledger::query();
        $ledgerQuery = $ledgerQuery->where('staff_id', $data['staff']->id);


        if ($request->date_from) {    
            $data['date_from'] = $request->date_from;
            $data['date_to'] = $request->date_to; 
            $ledgerQuery = $ledgerQuery->whereBetween('loan_date', [$data['date_from'], $data['date_to']]);
        }

        $ledgers = $ledgerQuery->get();
        $data['ledgers'] = $ledgers;


        return view('members.ledger', $data);
    }

    /**
     * Add a member
     */
    function addMember() {
        $data['centers'] = Center::pluck('name', 'id');

        return view('members.add', $data);
    }

    /**
     * Save a member
     */
    function saveMember(Request $request) {
        // dd($request->all());

        $rules = [
            'full_name' => 'required',
            'ippis' => 'required',
            'monthly_savings' => 'required',
            'pay_point' => 'required',
            // 'email' => 'email|unique:staff,email',

        ];

        $messages = [
            'full_name.required' => 'The fullname is required',
            'ippis.required' => 'This IPPIS Number is required',
            'pay_point.required' => 'Member\'s pay point is required',
            'monthly_savings.size' => 'The monthly savings must be at least 2000 naira.'
        ];

        $this->validate($request, $rules, $messages);

        $staff = new Staff;
        $staff->pf = $request->pf;
        $staff->ippis = $request->ippis;
        $staff->full_name = $request->full_name;
        $staff->sbu = $request->sbu;
        $staff->email = $request->email;
        $staff->phone = $request->phone;
        $staff->coop_no = $request->coop_no;
        $staff->pay_point = $request->pay_point;
        $staff->nok_name = $request->nok_name;
        $staff->nok_phone = $request->nok_phone;
        $staff->nok_address = $request->nok_address;
        $staff->nok_rship = $request->nok_rship;
        $staff->save();

        // add monthly savings
        $ms = new MonthlySaving;
        $ms->ippis = $staff->ippis;
        $ms->amount = $request->monthly_savings;
        $ms->save();

        $user = User::create([
            'name'      => $staff->full_name, 
            'username'  => $staff->ippis, 
            'ippis'     => $staff->ippis, 
            'password'  => \Hash::make('12345678'), 
        ]);

        return redirect('members');
    }

    /**
     * Savings
     */
    function savings(Staff $member) {
        $data['savings'] = $member->monthly_savings;
        dd($data['savings']);        

        return view('members.savings', $data);
    }

    /**
     * search for a member
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function awesomplete(Request $request)
    {
        $user = \Auth::user();
        $searchTerm = ' '.$request->get('q');

        return Staff::search($searchTerm)->get();
        
    }

    public function status($ippis) {
        $staff = Staff::where('ippis', $ippis)->first();

        $staff->is_active   = !$staff->is_active;

        if ($staff->is_active) {
            $staff->deactivation_date = Carbon::now();
        } else {
            $staff->activation_date = Carbon::now();
        }
        $staff->save();


        $user = USer::where('ippis', $ippis)->first();
        $user->is_active    = $staff->is_active;
        $user->save();

        return redirect()->back();
    }
}
