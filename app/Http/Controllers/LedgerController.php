<?php
namespace App\Http\Controllers;

use App\Serialisers\CustomSerialiser;

use App\Imports\LedgerImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MonthlyDeductionsExport;
use App\Exports\ReconciledDeductionsExport;
use App\Exports\LedgerExport;
use Importer;
use Exporter;

use App\Ledger;
use App\Center;
use App\Staff;
use App\User;
use App\MonthlySaving;
use App\MonthlySavingsPayment;
use App\LongTerm;
use App\LongTermPayment;
use App\ShortTerm;
use App\ShortTermPayment;
use App\Commodity;
use App\CommodityPayment;
use Carbon\Carbon;
use Toastr;

use Illuminate\Http\Request;
define('MIN_SAVINGS', 2000);

class LedgerController extends Controller
{
    protected $count = 0;
    protected $staff = null;

    
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
     * Import Ledger.
     *
     * @param  \App\Ledger  $ledger
     * @return \Illuminate\Http\Response
     */
    public function getImportInitialLedgerSummary(Ledger $ledger)
    {
        $data['centers'] = Center::pluck('name', 'id');

        return view('imports.getImportInitialLedgerSummary', $data);
    }

    /**
     * Import Ledger.
     *
     * @param  \App\Ledger  $ledger
     * @return \Illuminate\Http\Response
     */
    public function postImportInitialLedgerSummary(Request $request, Ledger $ledger) {
        // dd($request->all());

        $rules = [
            'file' => 'required',

        ];

        $messages = [
            'file.required' => 'Please select a file',
        ];

        $this->validate($request, $rules, $messages);

        $excel = Importer::make('Excel');
        $excel->load(request()->file('file'));
        $rows = $excel->getCollection();

        // remove hearder
        unset($rows[0]);

        // dd($rows);

        foreach($rows as $row) {

            $ippis                  = $row[0];
            $monthlyContribution    = floatVal($row[1]);
            $ltlDate                = ($row[2]) != '' ? $row[2] : NULL;
            $ltlAmount              = floatVal($row[3]);
            $ltlBal                 = floatVal($row[4]);
            $ltlDuration            = intVal($row[5]);
            $stlDate                = ($row[6]) != '' ? $row[6] : NULL;
            $stlAmount              = floatVal($row[7]);
            $stlBal                 = floatVal($row[8]);
            $stlDuration            = intVal($row[9]);
            $comDate                = ($row[10]) != '' ? $row[10] : NULL;
            $comAmount              = floatVal($row[11]);
            $comBal                 = floatVal($row[12]);
            $comDuration            = intVal($row[13]);
            $lname                  = ($row[14]);
            $fname                  = ($row[15]);


            if(!empty($ippis) || !empty($monthlyContribution) || !empty($ltlDate) || !empty($ltlAmount) || !empty($ltlBal) || !empty($ltlDuration) || !empty($stlDate) || !empty($stlAmount) || !empty($stlBal) || !empty($stlDuration) || !empty($comDate) || !empty($comAmount) || !empty($comBal) || !empty($comDuration) || !empty($lname) || !empty($fname)) {

                $staff = Staff::where('ippis', $ippis)->first();

                if ($staff) {
                    // Staff edit
                    $staff->fname     = trim($fname);
                    $staff->lname     = trim($lname);
                    $staff->full_name = trim($fname). ' '.trim($lname);
                    $staff->ippis     = $ippis;
                    $staff->pay_point = $request->pay_point;
                    $staff->save();

                    $user             = User::where('ippis', $ippis)->first();
                    $user->name       = $staff->full_name; 
                    $user->username   = $ippis;
                    $user->ippis      = $ippis;
                    $user->password   = \Hash::make($ippis);
                    $user->save();
                } else {
                    // Staff create
                    $staff = new Staff;
                    $staff->fname     = trim($fname);
                    $staff->lname     = trim($lname);
                    $staff->full_name = trim($fname). ' '.trim($lname);
                    $staff->ippis     = $ippis;
                    $staff->pay_point = $request->pay_point;
                    $staff->save();

                    $user             = new User;
                    $user->name       = $staff->full_name; 
                    $user->username   = $ippis;
                    $user->ippis      = $ippis;
                    $user->password   = \Hash::make($ippis);
                    $user->save();
                }
        
                // assign role
                $user->assignRole('member');

                // monthly saving
                $monthlySaving = new MonthlySaving;
                $monthlySaving->ippis = $ippis;
                $monthlySaving->amount = $monthlyContribution;
                $monthlySaving->save();

                // make entry in long term loan table
                $ltlMonthlyAmount = $ltlDuration != 0 ? $ltlAmount / $ltlDuration : $ltlAmount;

                $ltl = new LongTerm;
                $ltl->ref = 'INITIAL IMPORT';
                if ($ltlDate) {
                    $ltlLoanEndDate = Carbon::parse($ltlDate)->addMonths($ltlDuration);
                    $ltl->loan_date = $ltlDate;
                    $ltl->loan_end_date = $ltlLoanEndDate;
                }
                $ltl->ippis = $ippis;
                $ltl->no_of_months = $ltlDuration;
                $ltl->total_amount = $ltlAmount;
                $ltl->monthly_amount = $ltlMonthlyAmount;
                $ltl->save();

                // make entry in short term loan table
                $stlMonthlyAmount = $stlDuration != 0 ? $stlAmount / $stlDuration : $stlAmount;

                $stl = new ShortTerm;
                $stl->ref = 'INITIAL IMPORT';
                if ($stlDate) {
                    $stlLoanEndDate = Carbon::parse($ltlDate)->addMonths($ltlDuration);
                    $stl->loan_date = $stlDate;
                    $stl->loan_end_date = $stlLoanEndDate;
                }
                $stl->ippis = $ippis;
                $stl->no_of_months = $stlDuration;
                $stl->total_amount = $stlAmount;
                $stl->monthly_amount = $stlMonthlyAmount;
                $stl->save();

                // make entry in commodity loan table
                $comMonthlyAmount = $comDuration != 0 ? $comAmount / $comDuration : $comAmount;

                $commodity = new Commodity;
                $commodity->ref = 'INITIAL IMPORT';
                if ($comDate) {
                    $comLoanEndDate = Carbon::parse($comDate)->addMonths($comDuration);
                    $commodity->loan_date = $comDate;
                    $commodity->loan_end_date = $comLoanEndDate;
                }
                $commodity->ippis = $ippis;
                $commodity->no_of_months = $comDuration;
                $commodity->total_amount = $comAmount;
                $commodity->monthly_amount = $comMonthlyAmount;
                $commodity->save();

            }

        }


        Toastr::success('Import successful', 'Success', ["positionClass" => "toast-bottom-right"]);
        return redirect()->back();
    }


    /**
     * Import Ledger.
     *
     * @param  \App\Ledger  $ledger
     * @return \Illuminate\Http\Response
     */
    public function getImportInitialLedger(Ledger $ledger)
    {
        $data['centers'] = Center::pluck('name', 'id');

        return view('imports.getImportInitialLedger', $data);
    }

    /**
     * Import Ledger.
     *
     * @param  \App\Ledger  $ledger
     * @return \Illuminate\Http\Response
     */
    public function postImportInitialLedger(Request $request, Ledger $ledger) {
        // dd($request->all());

        $rules = [
            'file' => 'required',

        ];

        $messages = [
            'file.required' => 'Please select a file',
        ];

        $this->validate($request, $rules, $messages);

        $excel = Importer::make('Excel');
        $excel->load(request()->file('file'));
        $rows = $excel->getCollection();

        $memberIppis = [];

        foreach($rows as $row) {

            if(count(array_filter($row)) != 0)
            {


                $ippis = explode(':', $row[3]);

                if($ippis[0] == 'IPPIS') {
                    // dd(explode(':', $row[7])[1]);
                    if (!empty($ippis[1])) {
                        $ippis = trim($ippis[1]);
                        $memberIppis[] = $ippis;

                        $this->count = 0;

                        $this->staff = Staff::where('ippis', trim($ippis))->first();

                        if(!$this->staff) {
                            $this->staff = Staff::create([
                                'full_name' => trim(explode(':', $row[0])[1]),
                                'ippis'     => isset($ippis) ? trim($ippis) : '',
                                'sbu'       => isset(explode(':', $row[5])[1]) ? trim(explode(':', $row[5])[1]) : '',
                                'phone'     => isset(explode(':', $row[7])[1]) ? trim(explode(':', $row[7])[1]) : '',
                                'coop_no'   => isset(explode(':', $row[10])[1]) ? trim(explode(':', $row[10])[1]): '',
                                'pay_point' => $request->pay_point,
                            ]);

                            $user             = new User;
                            $user->name       = $this->staff->full_name; 
                            $user->username   = $ippis;
                            $user->ippis      = $ippis;
                            $user->password   = \Hash::make($ippis);
                            $user->save();
                    
                            // assign role
                            $user->assignRole('member');

                        }
                    }
                    
                }


                if($this->count >= 3) {
                        $date           = $row[0];
                        $ref            = $row[1];
                        $savings_dr     = $row[2];
                        $savings_cr     = $row[3];
                        $savings_bal    = $row[4];
                        $long_term_dr   = $row[5];
                        $long_term_cr   = $row[6];
                        $long_term_bal  = $row[7];
                        $short_term_dr  = $row[8];
                        $short_term_cr  = $row[9];
                        $short_term_bal = $row[10];
                        $commodity_dr   = $row[11];
                        $commodity_cr   = $row[12];
                        $commodity_bal  = $row[13];

                        // make entries
                        // dd($memberIppis);
                        $this->executeInitialImport(
                            $memberIppis[count($memberIppis) - 1],
                            $date, 
                            $ref, 
                            $savings_dr, 
                            $savings_cr,
                            $savings_bal, 
                            $long_term_dr, 
                            $long_term_cr, 
                            $long_term_bal, 
                            $short_term_dr, 
                            $short_term_cr, 
                            $short_term_bal, 
                            $commodity_dr, 
                            $commodity_cr, 
                            $commodity_bal
                        );
                }
            $this->count++;
                
            }

        }


        Toastr::success('Import successful', 'Success', ["positionClass" => "toast-bottom-right"]);
        return redirect()->back();
    }


    public function executeInitialImport($ippis, $date, $ref, $savings_dr, $savings_cr, $savings_bal, $long_term_dr, $long_term_cr, $long_term_bal, $short_term_dr, $short_term_cr, $short_term_bal, $commodity_dr, $commodity_cr, $commodity_bal) {

        $savings_dr         = floatval($savings_dr);
        $savings_cr         = floatval($savings_cr);
        $savings_bal        = floatval($savings_bal);
        $long_term_dr       = floatval($long_term_dr);
        $long_term_cr       = floatval($long_term_cr);
        $long_term_bal      = floatval($long_term_bal);
        $short_term_dr      = floatval($short_term_dr);
        $short_term_cr      = floatval($short_term_cr);
        $short_term_bal     = floatval($short_term_bal);
        $commodity_dr       = floatval($commodity_dr);
        $commodity_cr       = floatval($commodity_cr);
        $commodity_bal      = floatval($commodity_bal);
        
        // dd($ippis, $date, $ref, $savings_dr, $savings_cr, $savings_bal, $long_term_dr, $long_term_cr, $long_term_bal, $short_term_dr, $short_term_cr, $short_term_bal, $commodity_dr, $commodity_cr, $commodity_bal);


            if (!empty($ippis)) {

                        $member = Staff::where('ippis', $ippis)->first();

                        // MONTHLY SAVINGS
                        if(!empty($savings_dr) || !empty($savings_cr) || !empty($savings_bal)) {
                            $monthlySavings = MonthlySaving::where('ippis', $ippis)->latest('id')->first();

                            if(!$monthlySavings) {
                                $monthlySavings = new MonthlySaving;
                                $monthlySavings->ippis = $ippis;
                                $monthlySavings->amount = !empty($savings_cr) ? $savings_cr : 0.00;
                                $monthlySavings->save();
                            } 
                            // else {
                            //     // if($monthlySavings->amount == 0.00) {
                            //         $monthlySavings->amount = !empty($savings_cr) ? $savings_cr : 0.00;
                            //         $monthlySavings->save();
                            //     // }
                            // } 
                            $monthlySavingsPayment = new MonthlySavingsPayment;
                            $monthlySavingsPayment->ippis = $ippis;
                            $monthlySavingsPayment->pay_point      = $member->pay_point;
                            $monthlySavingsPayment->ref = !empty($ref) ? $ref : 0.00;
                            $monthlySavingsPayment->monthly_saving_id = $monthlySavings->id;
                            $monthlySavingsPayment->dr = !empty($savings_dr) ? $savings_dr : 0.00;
                            $monthlySavingsPayment->cr = !empty($savings_cr) ? $savings_cr : 0.00;
                            $monthlySavingsPayment->bal = !empty($savings_bal) ? $savings_bal : 0.00;
                            $monthlySavingsPayment->month = Carbon::today()->format('m');
                            $monthlySavingsPayment->year = Carbon::today()->format('Y');
                            $monthlySavingsPayment->save();
                        }


                        // LONGTERM LOAN
                        if(!empty($long_term_dr) || !empty($long_term_cr) || !empty($long_term_bal)) {

                            $longTerm = LongTerm::where('ippis', $ippis)->latest('id')->first();

                            // if(is_null($longTerm)) {
                            //     $longTerm = new LongTerm;
                            //     $longTerm->ippis = $ippis;
                            //     $longTerm->total_amount = !empty($long_term_bal) ? $long_term_bal : 0.00;
                            //     $longTerm->save();
                            // } 
                            // else {
                            //     if($longTerm->total_amount != $long_term_bal) {
                            //         $longTerm->total_amount = !empty($long_term_bal) ? $long_term_bal : 0.00;
                            //         $longTerm->save();
                            //     }
                            // }
                            if($longTerm ) {
                                $longTermPayment = new LongTermPayment;
                                $longTermPayment->ippis = $ippis;
                                $longTermPayment->pay_point      = $member->pay_point;
                                $longTermPayment->ref = !empty($ref) ? $ref : 0.00;
                                $longTermPayment->long_term_id = $longTerm->id;
                                $longTermPayment->dr = !empty($long_term_dr) ? $long_term_dr : 0.00;
                                $longTermPayment->cr = !empty($long_term_cr) ? $long_term_cr : 0.00;
                                $longTermPayment->bal = !empty($long_term_bal) ? $long_term_bal : 0.00;
                                $longTermPayment->month = Carbon::today()->format('m');
                                $longTermPayment->year = Carbon::today()->format('Y');
                                $longTermPayment->save();
                            }
                           
                        }


                        // SHORTTERM LOAN
                        if(!empty($short_term_dr) || !empty($short_term_cr) || !empty($short_term_bal)) {

                            $shortTerm = ShortTerm::where('ippis', $ippis)->latest('id')->first();

                            // if(is_null($shortTerm)) {
                            //     $shortTerm = new ShortTerm;
                            //     $shortTerm->ippis = $ippis;
                            //     $shortTerm->total_amount = !empty($short_term_bal) ? $short_term_bal : 0.00;
                            //     $shortTerm->save();
                            // } else {
                            //     if($shortTerm->total_amount != $short_term_bal) {
                            //         $shortTerm->total_amount = !empty($short_term_bal) ? $short_term_bal : 0.00;
                            //         $shortTerm->save();
                            //     }
                            // }
                            if($shortTerm) {
                                $shortTermPayment = new ShortTermPayment;
                                $shortTermPayment->ippis = $ippis;
                                $shortTermPayment->pay_point      = $member->pay_point;
                                $shortTermPayment->ref = !empty($ref) ? $ref : 0.00;
                                $shortTermPayment->short_term_id = $shortTerm->id;
                                $shortTermPayment->dr = !empty($short_term_dr[8]) ? $short_term_dr[8] : 0.00;
                                $shortTermPayment->cr = !empty($short_term_cr) ? $short_term_cr : 0.00;
                                $shortTermPayment->bal = !empty($short_term_bal) ? $short_term_bal : 0.00;
                                $shortTermPayment->month = Carbon::today()->format('m');
                                $shortTermPayment->year = Carbon::today()->format('Y');
                                $shortTermPayment->save(); 
                            }
                        }


                        // COMMODITY LOAN
                        if(!empty($commodity_dr) || !empty($commodity_cr) || !empty($commodity_bal)) {

                            $commodity = Commodity::where('ippis', $ippis)->latest('id')->first();

                            // if(is_null($commodity)) {
                            //     $commodity = new Commodity;
                            //     $commodity->ippis = $ippis;
                            //     $commodity->total_amount = !empty($commodity_bal) ? $commodity_bal : 0.00;
                            //     $commodity->save();
                            // } else {
                            //     if($commodity->total_amount != $commodity_bal) {
                            //         $commodity->total_amount = !empty($commodity_bal) ? $commodity_bal : 0.00;
                            //         $commodity->save();
                            //     }
                            // } 
                            if($commodity) {
                                $commodityPayment = new CommodityPayment;
                                $commodityPayment->ippis = $ippis;
                                $commodityPayment->pay_point      = $member->pay_point;
                                $commodityPayment->ref = !empty($row[1]) ? $row[1] : 0.00;
                                $commodityPayment->commodity_id = $commodity->id;
                                $commodityPayment->dr = !empty($commodity_dr) ? $commodity_dr : 0.00;
                                $commodityPayment->cr = !empty($commodity_cr) ? $commodity_cr : 0.00;
                                $commodityPayment->bal = !empty($commodity_bal) ? $commodity_bal : 0.00;
                                $commodityPayment->month = Carbon::today()->format('m');
                                $commodityPayment->year = Carbon::today()->format('Y');
                                $commodityPayment->save();
                            }                   
                            
                        }

                        // LEDGER ENTRY
                        $ledger = new Ledger;
                        $ledger->staff_id       = $this->staff->id;
                        $ledger->pay_point      = $member->pay_point;
                        $ledger->date           = !empty($date) ? $date : 0.00;
                        $ledger->ref            = !empty($ref) ? $ref : 0.00;
                        $ledger->savings_dr     = !empty($savings_dr) ? $savings_dr : 0.00;
                        $ledger->savings_cr     = !empty($savings_cr) ? $savings_cr : 0.00;
                        $ledger->savings_bal    = !empty($savings_bal) ? $savings_bal : 0.00;
                        $ledger->long_term_dr   = !empty($long_term_dr) ? $long_term_dr : 0.00;
                        $ledger->long_term_cr   = !empty($long_term_cr) ? $long_term_cr : 0.00;
                        $ledger->long_term_bal  = !empty($long_term_bal) ? $long_term_bal : 0.00;
                        $ledger->short_term_dr  = !empty($short_term_dr) ? $short_term_dr : 0.00;
                        $ledger->short_term_cr  = !empty($short_term_cr) ? $short_term_cr : 0.00;
                        $ledger->short_term_bal = !empty($short_term_bal) ? $short_term_bal : 0.00;
                        $ledger->commodity_dr   = !empty($commodity_dr) ? $commodity_dr : 0.00;
                        $ledger->commodity_cr   = !empty($commodity_cr) ? $commodity_cr : 0.00;
                        $ledger->commodity_bal  = !empty($commodity_bal) ? $commodity_bal : 0.00;
                        $ledger->save();

                        return $ledger;
            }

        return null;

    }

    /**
     * Show form to make new ledger entry
     */
    public function newledgerEntry($ippis) {
        $data['staff'] = Staff::where('ippis', $ippis)->first();

        return view('ledger.create', $data);
    }

    /**
     * Insert ledger entry entry to DB
     */
    function postNewLedgerEntry(Request $request, $ippis) {
        // dd($ippis, $request->all());

        $staff = Staff::where('ippis', $ippis)->first();
        $lastLedgerEntry = Ledger::where('staff_id', $staff->id)->first();

        if($lastLedgerEntry) {
          if (!$lastLedgerEntry->savings_bal) {
            $savings_bal = 0;
          } else {
            $savings_bal = $lastLedgerEntry->savings_bal;
          }

          if (!$lastLedgerEntry->long_term_bal) {
            $long_term_bal = 0;
          } else {
            $long_term_bal = $lastLedgerEntry->long_term_bal;
          }

          if (!$lastLedgerEntry->short_term_bal) {
            $short_term_bal = 0;
          } else {
            $short_term_bal = $lastLedgerEntry->short_term_bal;
          }

          if (!$lastLedgerEntry->commodity_term_bal) {
            $commodity_term_bal = 0;
          } else {
            $commodity_term_bal = $lastLedgerEntry->commodity_term_bal;
          }

        } else {
            $savings_bal        = 0;
            $long_term_bal      = 0;
            $short_term_bal     = 0;
            $commodity_term_bal = 0;
        }


        $ledger             = new Ledger;
        $ledger->staff_id   = $staff->id;
        $ledger->date       = $request->date;
        $ledger->ref        = 'AMT  PAID';

        // get current savings balance
        if($request->savings['debit']) {
            $ledger->savings_dr     = $request->savings['debit'];
            $ledger->savings_cr     = $request->savings['credit'];
            $ledger->savings_bal    = $savings_bal - $request->savings['debit'];
        } else {
            $ledger->savings_dr     = $request->savings['debit'];
            $ledger->savings_cr     = $request->savings['credit'];
            $ledger->savings_bal    = $savings_bal + $request->savings['credit'];
        }

        // get current obligation balance based on obligation type
        if($request->obligation['obligation_type']) {
            if($request->obligation['obligation_type'] == 'long_term_loan') {

                if($request->obligation['debit']) {
                    $ledger->long_term_dr = $request->obligation['debit'];
                    $ledger->long_term_cr = $request->obligation['credit'];
                    $ledger->long_term_bal = $long_term_bal + $request->obligation['debit'];
                } else {
                    $ledger->long_term_dr = $request->obligation['debit'];
                    $ledger->long_term_cr = $request->obligation['credit'];
                    $ledger->long_term_bal = $long_term_bal - $request->obligation['credit'];
                }

            } elseif ($request->obligation['obligation_type'] == 'short_term_loan') {

                if($request->obligation['debit']) {
                    $ledger->short_term_dr = $request->obligation['debit'];
                    $ledger->short_term_cr = $request->obligation['credit'];
                    $ledger->short_term_bal = $short_term_bal + $request->obligation['debit'];
                } else {
                    $ledger->short_term_dr = $request->obligation['debit'];
                    $ledger->short_term_cr = $request->obligation['credit'];
                    $ledger->short_term_bal = $short_term_bal - $request->obligation['credit'];
                }

            } else {

                if($request->obligation['debit']) {
                    $ledger->commodity_dr = $request->obligation['debit'];
                    $ledger->commodity_cr = $request->obligation['credit'];
                    $ledger->commodity_bal = $commodity_term_bal + $request->obligation['debit'];
                } else {
                    $ledger->commodity_dr = $request->obligation['debit'];
                    $ledger->commodity_cr = $request->obligation['credit'];
                    $ledger->commodity_bal = $commodity_term_bal - $request->obligation['credit'];
                }

            }
        }



        $ledger->save();

        return $ledger;
    }

    /**
     * Show form to make new ledger entry
     */
    public function editLedgerEntry($ledger_id) {
        $data['ledger'] = Ledger::find($ledger_id);

        return view('ledger.edit', $data);
    }

    public function generateDeductions() {
        $data['centers'] = Center::pluck('name', 'id');

        return view('deductions.importFromIppis', $data);
    }


    /**
     * Generate file to be sent to IPPIS
     */
    public function exportToIppis(Request $request) {

        $rules = [
            'pay_point' => 'required',

        ];

        $messages = [
            'pay_point.required' => 'Please select a pay point',
        ];

        $this->validate($request, $rules, $messages);

        $center = Center::find($request->pay_point);

        Toastr::success('Export successful', 'Success', ["positionClass" => "toast-bottom-right"]);
        return Excel::download(new MonthlyDeductionsExport($request->pay_point), $center->name.'_'.Carbon::today()->format('m:Y').'.xlsx');
        
    }


    /**
     * Import and compare file from iPPIS
     */
    public function importFromIppis(Request $request) {
        // dd($request->all());

        $rules = [
            'file' => 'required',

        ];

        $messages = [
            'file.required' => 'Please select a file',
        ];

        $this->validate($request, $rules, $messages);

        $excel = Importer::make('Excel');
        $excel->load(request()->file('file'));
        $rows = $excel->getCollection();

        // dd($rows);

        Toastr::success('Reconciliation successful', 'Success', ["positionClass" => "toast-bottom-right"]);
        return Excel::download(new ReconciledDeductionsExport($rows, $request->ref, $request->deduction_for), 'reconciled.xlsx');

    }

    /**
     * Export individual members ledger as excel
     */
    function memberLedgerExcel($ippis) {
        $member = Staff::where('ippis', $ippis)->first();

        Toastr::success('Export successful', 'Success', ["positionClass" => "toast-bottom-right"]);
        return Excel::download(new LedgerExport($ippis), 'LEDGER_'.$member->full_name.'.xlsx');
    }

    /**
     * Export individual members ledger as PDF
     */
    function memberLedgerPdf($ippis) {
        $member = Staff::where('ippis', $ippis)->first();
        $data['member'] = $member;
        
        $pdf = \PDF::loadView('pdf.ledger', $data)->setPaper('a3', 'landscape');
        return $pdf->download('LEDGER_'.$member->full_name.'.pdf');
    }

    /**
     * Export individual members ledger as PDF
     */
    function memberLedgerPrint($ippis) {
        $member = Staff::where('ippis', $ippis)->first();
        $data['member'] = $member;
        
        $pdf = \PDF::loadView('pdf.ledger', $data)->setPaper('a3', 'landscape');
        return $pdf->stream();
    }
}
