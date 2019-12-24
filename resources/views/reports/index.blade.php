@extends('master')

@section('body')
<!-- Page-Title -->

<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="page-title m-0">Generate Report</h4>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card m-b-30">
            <div class="card-body">


                @can('generate reports')

                @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                {!! Form::open(['route' => 'reports', 'method' => 'get']) !!}
                <div class="row">
                    <div class="col-md-3 mb-1">
                        {{Form::date('date_from', $date_from, ['class' => 'form-control'])}}
                    </div>
                    <div class="col-md-3 mb-1">
                        {{Form::date('date_to', $date_to, ['class' => 'form-control'])}}
                    </div>
                    <div class="col-md-3 mb-1">
                        {{Form::select('pay_point', $centers, null, ['class' => 'form-control'])}}
                    </div>
                    <div class="col-md-3 mb-1">
                        <button type="submit" class="btn btn-primary waves-effect waves-light">Generate</button>
                    </div>
                </div>
                {!! Form::close() !!}

                @if(count($membersReports) > 0)
                <div class="row mt-5">
                    <div class="col-md-12">
                        <table class="table table-bordered table-responsive">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th class="text-center"></th>
                                    <th class="text-center" colspan="3">Long Term</th>
                                    <th class="text-center" colspan="3">Short Term</th>
                                    <th class="text-center" colspan="3">Commodity</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th>Name</th>
                                    <th>IPPIS</th>
                                    <th class="text-right">Savings</th>
                                    <th class="text-right">Loaned</th>
                                    <th class="text-right">Balance</th>
                                    <th class="text-right">Duration</th>
                                    <th class="text-right">Loaned</th>
                                    <th class="text-right">Balance</th>
                                    <th class="text-right">Duration</th>
                                    <th class="text-right">Loaned</th>
                                    <th class="text-right">Balance</th>
                                    <th class="text-right">Duration</th>
                                </tr>
                                @foreach($membersReports as $report)
                                @if(count($report['LongTermLoans']) > 1 || count($report['ShortTermLoans']) > 1 ||
                                count($report['CommodityLoans']) > 1 )

                                <tr>

                                    <td scope="row">{{ $report['member']->full_name }}</td>
                                    <td><a href="{{ route('members.dashboard', $report['member']->ippis) }}">{{ $report['member']->ippis }}</a></td>

                                    <td class="text-right">{{ $report['member']->monthly_savings_payments->isNotEmpty() ? number_format($report['member']->monthly_savings_payments->last()->bal, 2) : 0.00 }}</td>

                                    <td class="text-right">
                                        {{ isset($report['LongTermLoans'][0]) ? number_format($report['LongTermLoans'][0]['LTLAmtLoaned'], 2) : '' }}
                                        <br>
                                    </td>
                                    <td class="text-right">
                                        {{ isset($report['LongTermLoans'][0]) ? number_format($report['LongTermLoans'][0]['LTLBal'], 2) : '' }}
                                        <br>
                                    </td>
                                    <td class="text-right">
                                        {{ isset($report['LongTermLoans'][0]) ? $report['LongTermLoans'][0]['LTLDuration'].' months' : '' }}
                                        <br>
                                    </td>

                                    <td class="text-right">
                                        {{ isset($report['ShortTermLoans'][0]) ? number_format($report['ShortTermLoans'][0]['STLAmtLoaned'], 2) : '' }}
                                        <br>
                                    </td>
                                    <td class="text-right">
                                        {{ isset($report['ShortTermLoans'][0]) ? number_format($report['ShortTermLoans'][0]['STLBal'], 2): '' }}
                                        <br>
                                    </td>
                                    <td class="text-right">
                                        {{ isset($report['ShortTermLoans'][0]) ? $report['ShortTermLoans'][0]['STLDuration'].' months': '' }}
                                        <br>
                                    </td>

                                    <td class="text-right">
                                        {{ isset($report['CommodityLoans'][0]) ? number_format($report['CommodityLoans'][0]['COMMAmtLoaned'], 2) : '' }}
                                        <br>
                                    </td>
                                    <td class="text-right">
                                        {{ isset($report['CommodityLoans'][0]) ? number_format($report['CommodityLoans'][0]['COMMBal'], 2) : '' }}
                                        <br>
                                    </td>
                                    <td class="text-right">
                                        {{ isset($report['CommodityLoans'][0]) ? $report['CommodityLoans'][0]['COMMDuration'].' months' : '' }}
                                        <br>
                                    </td>

                                </tr>

                                @php
                                $counter = count($report['LongTermLoans']);
                                if(count($report['ShortTermLoans']) > $counter) {
                                    $counter = count($report['ShortTermLoans']);
                                }
                                if(count($report['CommodityLoans']) > $counter) {
                                    $counter = count($report['CommodityLoans']);
                                }
                                @endphp

                                @for($i=1 ; $i < $counter ; $i++)
                                <tr>
                                    <td scope="row"></td>
                                    <td></td>
                                    
                                    <td class="text-right">{{ $report['member']->monthly_savings_payments->isNotEmpty() ? number_format($report['member']->monthly_savings_payments->last()->bal, 2) : 0.00 }}</td>

                                    <td class="text-right">
                                        {{ isset($report['LongTermLoans'][$i]) ? number_format($report['LongTermLoans'][$i]['LTLAmtLoaned'], 2) : '' }}
                                        <br>
                                    </td>
                                    <td class="text-right">
                                        {{ isset($report['LongTermLoans'][$i]) ? number_format($report['LongTermLoans'][$i]['LTLBal'], 2) : '' }}
                                        <br>
                                    </td>
                                    <td class="text-right">
                                        {{ isset($report['LongTermLoans'][$i]) ? $report['LongTermLoans'][$i]['LTLDuration'].' months' : ''}}
                                        <br>
                                    </td>

                                    <td class="text-right">
                                        {{ isset($report['ShortTermLoans'][$i]) ? number_format($report['ShortTermLoans'][$i]['STLAmtLoaned'], 2) : '' }}
                                        <br>
                                    </td>
                                    <td class="text-right">
                                        {{ isset($report['ShortTermLoans'][$i]) ? number_format($report['ShortTermLoans'][$i]['STLBal'], 2) : '' }}
                                        <br>
                                    </td>
                                    <td class="text-right">
                                        {{ isset($report['ShortTermLoans'][$i]) ? $report ['ShortTermLoans'][$i]['STLDuration'].' months' : '' }}
                                        <br>
                                    </td>

                                    <td class="text-right">
                                        {{ isset($report['CommodityLoans'][$i]) ? number_format($report['CommodityLoans'][$i]['COMMAmtLoaned'], 2) : '' }}
                                        <br>
                                    </td>
                                    <td class="text-right">
                                        {{ isset($report['CommodityLoans'][$i]) ? number_format($report['CommodityLoans'][$i]['COMMBal'], 2) : '' }}
                                        <br>
                                    </td>
                                    <td class="text-right">
                                        {{ isset($report['CommodityLoans'][$i]) ? $report['CommodityLoans'][$i]['COMMDuration'].' months' : '' }}
                                        <br>
                                    </td>

                                </tr>
                                @endfor

                                @else

                                <tr>
                                    <td scope="row">{{ $report['member']->full_name }}</td>
                                    <td><a href="{{ route('members.dashboard', $report['member']->ippis) }}">{{ $report['member']->ippis }}</a></td>
                                    
                                    <td class="text-right">{{ $report['member']->monthly_savings_payments->isNotEmpty() ? number_format($report['member']->monthly_savings_payments->last()->bal, 2) : 0.00 }}</td>

                                    <td class="text-right">
                                        @foreach($report['LongTermLoans'] as $LongTermLoan)
                                        {{ number_format($LongTermLoan['LTLAmtLoaned'], 2) }} <br>
                                        @endforeach
                                    </td>
                                    <td class="text-right">
                                        @foreach($report['LongTermLoans'] as $LongTermLoan)
                                        {{ number_format($LongTermLoan['LTLBal'], 2) }} <br>
                                        @endforeach
                                    </td>
                                    <td class="text-right">
                                        @foreach($report['LongTermLoans'] as $LongTermLoan)
                                        {{ $LongTermLoan['LTLDuration'] }} months <br>
                                        @endforeach
                                    </td>

                                    <td class="text-right">
                                        @foreach($report['ShortTermLoans'] as $ShortTermLoan)
                                        {{ number_format($ShortTermLoan['STLAmtLoaned'], 2) }} <br>
                                        @endforeach
                                    </td>
                                    <td class="text-right">
                                        @foreach($report['ShortTermLoans'] as $ShortTermLoan)
                                        {{ number_format($ShortTermLoan['STLBal'], 2) }} <br>
                                        @endforeach
                                    </td>
                                    <td class="text-right">
                                        @foreach($report['ShortTermLoans'] as $ShortTermLoan)
                                        {{ $ShortTermLoan['STLDuration'] }} months <br>
                                        @endforeach
                                    </td>

                                    <td class="text-right">
                                        @foreach($report['CommodityLoans'] as $CommodityLoan)
                                        {{ number_format($CommodityLoan['COMMAmtLoaned'], 2) }} <br>
                                        @endforeach
                                    </td>
                                    <td class="text-right">
                                        @foreach($report['CommodityLoans'] as $CommodityLoan)
                                        {{ number_format($CommodityLoan['COMMBal'], 2) }} <br>
                                        @endforeach
                                    </td>
                                    <td class="text-right">
                                        @foreach($report['CommodityLoans'] as $CommodityLoan)
                                        {{ $CommodityLoan['COMMDuration'] }} months <br>
                                        @endforeach
                                    </td>

                                </tr>

                                @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @else
                <div class="row">
                    <div class="col-md-12">
                        <p class="my-5">Select dates and pay point to generate reports</p>
                    </div>
                </div>
                @endif

                @endcan

            </div>
        </div>
    </div><!-- end col -->
</div>
@endsection
