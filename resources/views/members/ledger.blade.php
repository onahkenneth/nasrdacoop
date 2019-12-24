@extends('master')

@section('body')

<!-- Page-Title -->
<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <!-- <h4 class="page-title m-0">Ledger</h4> -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- <div class="row">
    <div class="col-lg-12">
        <div class="card m-b-30">
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <td><strong>NAME:</strong></td>
                        <td>{{ $staff->full_name }}</td>
                        <td><strong>IPPIS:</strong></td>
                        <td>{{ $staff->ippis }}</td>
                        <td><strong>SBU:</strong></td>
                        <td>{{ $staff->sbu }}</td>
                        <td><strong>PHONE NUMBER:</strong></td>
                        <td>{{ $staff->phone }}</td>
                        <td><strong>COOP NO:</strong></td>
                        <td>{{ $staff->coop_no }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div> -->

<div class="row">
    <div class="col-lg-12">
        <div class="card m-b-30">
            <div class="card-body">

                <!-- <a data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#myModal" data-remote="{{route('newledgerEntry', $staff->ippis)}}" href="#" class="btn btn-info waves-effect waves-light"><i class="mdi mdi-file-document-box"></i> New Entry</a> -->

                <!-- <a href="{{route('newledgerEntry', $staff->ippis)}}" class="btn btn-primary waves-effect waves-light"><i class="mdi mdi-file-document-box"></i> New Entry</a> -->

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <a href="{{route('members.dashboard', $staff->ippis)}}" class="btn btn-primary waves-effect waves-light mb-1"><i class="mdi mdi-backspace-outline"></i> Dashboard</a>
                            <a href="{{ route('members.ledgerExcel', $staff->ippis) }}" class="btn btn-success mb-1"><i
                                    class="mdi mdi-file-excel"></i> Excel</a>
                            <a href="{{ route('members.ledgerPdf', $staff->ippis) }}" class="btn btn-danger mb-1"><i class="mdi mdi-file-pdf-box"></i> PDF</a>
                            <a href="{{ route('members.ledgerPrint', $staff->ippis) }}" target="_blank" class="btn btn-primary mb-1"><i class="mdi mdi-file-pdf-box"></i> Print</a>
                        </div>
                    </div>
                    <div class="col-md-6">
                        {!! Form::open(['route' => ['members.ledger', $staff->ippis], 'method' => 'get']) !!}
                        <div class="row">
                            <div class="col-md-4">
                                {{Form::date('date_from', $date_from, ['class' => 'form-control mb-3'])}}
                            </div>
                            <div class="col-md-4">
                                {{Form::date('date_to', $date_to, ['class' => 'form-control mb-3'])}}
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary waves-effect waves-light mb-3">Filter</button>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
                

                <div class=" mt-3">
                    @if($staff->ledgers->count() > 0)

                    <!-- <div id="memberledger"></div> -->

                    <table class="table table-bordered table-hover mb-0">
                        <thead>
                            <tr>
                                <th class="text-center" colspan="2">&nbsp</th>
                                <!-- <th class="text-center">PARTICULARS</th> -->
                                <th class="text-center" colspan="3">SAVINGS</th>
                                <th class="text-center" colspan="3">LONG TERM</th>
                                <th class="text-center" colspan="3">SHORT TERM</th>
                                <th class="text-center" colspan="3">COMMODITY</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th class="text-left">DATE</th>
                                <th class="text-left">DESCRIPTION</th>
                                <th class="text-right">DR</th>
                                <th class="text-right">CR</th>
                                <th class="text-right">BALANCE</th>
                                <th class="text-right">DR</th>
                                <th class="text-right">CR</th>
                                <th class="text-right">BALANCE</th>
                                <th class="text-right">DR</th>
                                <th class="text-right">CR</th>
                                <th class="text-right">BALANCE</th>
                                <th class="text-right">DR</th>
                                <th class="text-right">CR</th>
                                <th class="text-right">BALANCE</th>
                                <!-- <th class="text-center">EDIT</th> -->
                            </tr>
                            @foreach($ledgers as $ledger)
                            <tr>
                                <th class="text-left" scope="row">{{ $ledger->date }}</th>
                                <td>{{ $ledger->ref }}</td>
                                <td class="text-right">
                                    {{ number_format($ledger->savings_dr, 2) }}</td>
                                <td class="text-right">
                                    {{ number_format($ledger->savings_cr, 2) }}</td>
                                <td class="text-right">
                                    {{ number_format($ledger->savings_bal, 2) }}
                                </td>
                                <td class="text-right">
                                    {{ number_format($ledger->long_term_dr, 2) }}
                                </td>
                                <td class="text-right">
                                    {{ number_format($ledger->long_term_cr, 2) }}
                                </td>
                                <td class="text-right">
                                    {{ number_format($ledger->long_term_bal, 2) }}
                                </td>
                                <td class="text-right">
                                    {{ number_format($ledger->short_term_dr, 2) }}
                                </td>
                                <td class="text-right">
                                    {{ number_format($ledger->short_term_cr, 2) }}
                                </td>
                                <td class="text-right">
                                    {{ number_format($ledger->short_term_bal, 2) }}
                                </td>
                                <td class="text-right">
                                    {{ number_format($ledger->commodity_dr, 2) }}
                                </td>
                                <td class="text-right">
                                    {{ number_format($ledger->commodity_cr, 2) }}
                                </td>
                                <td class="text-right">
                                    {{ number_format($ledger->commodity_bal, 2) }}
                                </td>
                                <!-- <td class="text-center">
                                    <a href="{{ route('editLedgerEntry', $ledger->id) }}"><i class="ion ion-md-create"></i></a>
                                </td> -->
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    @else
                    <p>No records found</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@section('js')
<script>
    var data = [
    ['', 'Ford', 'Tesla', 'Toyota', 'Honda'],
    ['2017', 10, 11, 12, 13],
    ['2018', 20, 11, 14, 13],
    ['2019', 30, 15, 12, 13]
    ];

    var container = document.getElementById('memberledger');
    var hot = new Handsontable(container, {
    data: data,
    rowHeaders: true,
    colHeaders: true,
    filters: true,
    dropdownMenu: true,
    licenseKey: "non-commercial-and-evaluation"
    });
</script>
@endsection
