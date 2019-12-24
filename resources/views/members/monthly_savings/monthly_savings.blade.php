@extends('master')

@section('body')
<!-- Page-Title -->
@if($member)
<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="page-title m-0">Monthly Savings <span class="text-primary">[ {{ $member->full_name }} | {{ $member->ippis }} ]</span></h4>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card m-b-30">
            <div class="card-body">

                <a href="{{route('members.dashboard', $member->ippis)}}" class="btn btn-primary waves-effect waves-light"><i class="mdi mdi-backspace-outline"></i> Dashboard</a>
                @can('add to savings')
                <a href="{{route('members.newSavings', $member->ippis)}}" class="btn btn-info waves-effect waves-light"><i class="mdi mdi-file-document-box"></i> Add Saving</a>
                @endcan
                @can('withdraw from savings')
                <a href="{{route('members.savingsWithrawal', $member->ippis)}}" class="btn btn-danger waves-effect waves-light"><i class="mdi mdi-file-document-box"></i> Withdrawal</a>
                @endcan
                @can('change monthly contribution')
                <a href="{{route('members.savingsChangeObligation', $member->ippis)}}" class="btn btn-secondary waves-effect waves-light"><i class="mdi mdi-file-document-box"></i> Change Monthly Contribution</a>
                @endcan

                @if($monthlySavings->count() > 0)
                <div class="row mt-3">
                    <div class="col-md-3">
                    <h6>Monthly Contributions</h6>
                        <table class="table table-bordered table-hover">
                            <tbody>
                                <thead>
                                    <tr>
                                        <td class="text-left">Date</td>
                                        <td class="text-right">Monthly Contribution</td>
                                    </tr>
                                </thead>
                                @foreach($monthlySavings as $monthlySaving)
                                    <tr>
                                        <td class="text-left">{{ $monthlySaving->created_at->toFormattedDateString() }}</td>
                                        <td class="text-right">{{ number_format($monthlySaving->amount, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-9">
                    <h6>Monthly Contributions Payment History</h6>
                        <table class="table table-bordered table-hover">
                            <tbody>
                                <thead>
                                    <tr>
                                        <td>Description</td>
                                        <td class="text-right">Debit</td>
                                        <td class="text-right">Credit</td>
                                        <td class="text-right">Balance</td>
                                        <td class="text-right">Entry Date</td>
                                    </tr>
                                </thead>
                                @foreach($monthlySavings as $monthlySaving)
                                    @foreach($monthlySaving->payments as $payments)
                                    <tr>
                                        <td>{{$payments->ref}}</td>
                                        <td class="text-right">{{ number_format($payments->dr, 2) }}</td>
                                        <td class="text-right">{{ number_format($payments->cr, 2) }}</td>
                                        <td class="text-right">{{ number_format($payments->bal, 2) }}</td>
                                        <td class="text-right">{{ $payments->created_at->toDayDateTimeString() }}</td>
                                    </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @else
                <p class="mt-3">No records found.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@else
<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <div class="row align-items-center">
                <div class="col-md-8">
                
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card m-b-30">
            <div class="card-body">
            
                <p class="my-5">
                    This member does not exist
                </p>
            
            </div>
        </div>
    </div>
</div>
@endif

@endsection


@section('js')

@endsection

