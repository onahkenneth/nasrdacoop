@extends('master')

@section('body')
<!-- Page-Title -->

<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="page-title m-0">Generate Monthly Defaulters</h4>
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

                {!! Form::open(['route' => 'reports.monthlyDefaults', 'method' => 'get']) !!}
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
                                    <th class="text-center" colspan="3">Long Term</th>
                                    <th class="text-center" colspan="3">Short Term</th>
                                    <th class="text-center" colspan="3">Commodity</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th>Name</th>
                                    <th>IPPIS</th>
                                    <th class="text-right">Default Charge</th>
                                    <th class="text-right">Amount Due</th>
                                    <th class="text-right">Amount Paid</th>
                                    <th class="text-right">Default Charge</th>
                                    <th class="text-right">Amount Due</th>
                                    <th class="text-right">Amount Paid</th>
                                    <th class="text-right">Default Charge</th>
                                    <th class="text-right">Amount Due</th>
                                    <th class="text-right">Amount Paid</th>
                                </tr>

                                @foreach($membersReports as $report)
                                <tr>
                                    <td scope="row">{{ $report['member']->full_name }}</td>
                                    <td><a href="{{ route('members.dashboard', $report['member']->ippis) }}">{{ $report['member']->ippis }}</a></td>

                                    <td class="text-right">
                                        @foreach($report['LongTermLoanDefault'] as $default)
                                        {{ number_format($default->default_charge, 2) }} <br>
                                        @endforeach
                                    </td>
                                    <td class="text-right">
                                        @foreach($report['LongTermLoanDefault'] as $default)
                                        {{ number_format($default->monthly_obligation, 2) }} <br>
                                        @endforeach
                                    </td>
                                    <td class="text-right">
                                        @foreach($report['LongTermLoanDefault'] as $default)
                                        {{ number_format($default->actual_paid, 2) }} <br>
                                        @endforeach
                                    </td>

                                    <td class="text-right">
                                        @foreach($report['ShortTermLoanDefault'] as $default)
                                        {{ number_format($default->default_charge, 2) }} <br>
                                        @endforeach
                                    </td>
                                    <td class="text-right">
                                        @foreach($report['ShortTermLoanDefault'] as $default)
                                        {{ number_format($default->monthly_obligation, 2) }} <br>
                                        @endforeach
                                    </td>
                                    <td class="text-right">
                                        @foreach($report['ShortTermLoanDefault'] as $default)
                                        {{ number_format($default->actual_paid, 2) }} <br>
                                        @endforeach
                                    </td>

                                    <td class="text-right">
                                        @foreach($report['CommodityLoanDefault'] as $default)
                                        {{ number_format($default->default_charge, 2) }} <br>
                                        @endforeach
                                    </td>
                                    <td class="text-right">
                                        @foreach($report['CommodityLoanDefault'] as $default)
                                        {{ number_format($default->monthly_obligation, 2) }} <br>
                                        @endforeach
                                    </td>
                                    <td class="text-right">
                                        @foreach($report['CommodityLoanDefault'] as $default)
                                        {{ number_format($default->actual_paid, 2) }} <br>
                                        @endforeach
                                    </td>

                                </tr>
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
