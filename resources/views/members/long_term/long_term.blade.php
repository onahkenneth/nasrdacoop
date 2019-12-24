@extends('master')

@section('body')
<!-- Page-Title -->
@if($member)
<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="page-title m-0">Long Term Loans <span class="text-danger">[ {{ $member->full_name }} |
                            {{ $member->ippis }} ]</span><span
                            class="text-{{ $member->is_active? 'success' : 'danger' }}">[
                            {{ $member->is_active? 'active' : 'inactive' }} ]</span></h4>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card m-b-30">
            <div class="card-body">

                <a href="{{route('members.dashboard', $member->ippis)}}"
                    class="btn btn-primary waves-effect waves-light mb-1"><i class="mdi mdi-backspace-outline"></i>
                    Dashboard</a>
                @can('long term loan repayment')
                <a href="{{route('members.longLoanRepayment', $member->ippis)}}"
                    class="btn btn-secondary waves-effect waves-light mb-1 {{ $member->is_active ? '' : 'disabled' }} {{ $longTermLoans->count() > 0 ? '' : 'disabled' }}"><i
                        class="mdi mdi-file-document-box"></i> Repayment</a>
                @endcan
                @can('create long term loan')
                <a href="{{route('members.newLongLoan', $member->ippis)}}"
                    class="btn btn-info waves-effect waves-light mb-1 {{ $member->is_active ? '' : 'disabled' }}"><i
                        class="mdi mdi-file-document-box"></i> New Long
                    Term Loan</a>
                @endcan

                @if($member->long_term_loans->count() > 0)
                <div class="row mt-3">
                    <div class="col-md-3">
                        <h6>Long Term Loans Taken</h6>
                        <table class="table table-bordered table-hover">
                            <tbody>
                                <thead>
                                    <tr>
                                        <td class="text-left">Date</td>
                                        <td class="text-right">Amount</td>
                                        <td class="text-center">Action(s)</td>
                                    </tr>
                                </thead>
                                @foreach($longTermLoans as $longTermLoan)
                                <tr>
                                    <td class="text-left">{{ $longTermLoan->created_at->toFormattedDateString() }}</td>
                                    <td class="text-right">{{ number_format($longTermLoan->total_amount, 2) }}</td>
                                    <td class="text-center">
                                        <a data-toggle="modal" data-keyboard="false" data-target="#myModal"
                                            data-remote="{{route('members.longLoanDetails', $longTermLoan->id)}}"
                                            href="#" class="">Details</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-9">
                        <h6>Long Term Loan Payment History</h6>
                        <table class="table table-bordered table-hover table-responsive">
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
                                @foreach($longTermLoans as $longTermLoan)
                                @foreach($longTermLoan->payments as $payments)
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
