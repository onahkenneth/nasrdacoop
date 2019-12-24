@extends('master')

@section('body')
<!-- Page-Title -->
<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="page-title m-0">Dashboard <span class="text-danger">[ {{ $member->full_name }} | {{ $member->ippis }} ]</span><span class="text-{{ $member->is_active? 'success' : 'danger' }}">[ {{ $member->is_active? 'active' : 'inactive' }} ]</span></h4>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card m-b-30">
            <div class="card-body">

                <div class="row mb-5">
                    <div class="col-md-12">
                        <!-- <a data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#myModal" data-remote="{{route('newledgerEntry', $member->ippis)}}" href="#" class="btn btn-info waves-effect waves-light"><i class="mdi mdi-file-document-box"></i> New Entry</a> -->

                        @can('view member ledger')
                        <a href="{{route('members.ledger', $member->ippis)}}" class="btn btn-primary waves-effect waves-light mb-1"><i class="mdi mdi-file-document-box"></i> Ledger</a>

                        <a href="{{ route('members.savings', $member->ippis) }}" class="btn btn-secondary mb-1"><i class="mdi mdi-file-excel"></i> Savings</a>
                        <a href="{{ route('members.longTermLoans', $member->ippis) }}" class="btn btn-secondary mb-1"><i class="mdi mdi-file-pdf-box"></i> Long Term Loans</a>
                        <a href="{{ route('members.shortTermLoans', $member->ippis) }}" class="btn btn-secondary mb-1"><i class="mdi mdi-file-pdf-box"></i> Short Term Loans</a>
                        <a href="{{ route('members.commodity', $member->ippis) }}" class="btn btn-secondary mb-1"><i class="mdi mdi-file-pdf-box"></i> Commodities</a>
                        @endcan

                        @can('disable member')
                        @if($member->is_active)
                        <a href="{{ route('members.status', $member->ippis) }}" class="btn btn-danger mb-1 float-right"><i class="mdi mdi-file-pdf-box"></i> Deactivate</a>
                        @else
                        <a href="{{ route('members.status', $member->ippis) }}" class="btn btn-success mb-1 float-right"><i class="mdi mdi-file-pdf-box"></i> Activate</a>
                        @endif
                        @endcan
                    </div>
                </div>
                


                <div class="row">
                    <div class="col-md-6">        
                        <h5>Member's details</h5>                
                        <table class="table table-condensed table-hover table-bordered">
                            <tbody>
                                <tr>
                                    <td>Name: </td>
                                    <td>{{ $member->full_name }}</td>
                                </tr>
                                <tr>
                                    <td>IPPIS: </td>
                                    <td>{{ $member->ippis }}</td>
                                </tr>
                                <tr>
                                    <td>Current Monthly Contribution: </td>
                                    <td>&#8358; {{ count($member->monthly_savings) > 0 ? number_format($member->monthly_savings->last()->amount, 2) : 0.00 }}</td>
                                </tr>
                                <tr>
                                    <td>Pay point: </td>
                                    <td>{{ $member->member_pay_point ? $member->member_pay_point->name : '' }}</td>
                                </tr>
                                <tr>
                                    <td>Coop No: </td>
                                    <td>{{ $member->coop_no }}</td>
                                </tr>
                                <tr>
                                    <td>Phone: </td>
                                    <td>{{ $member->phone }}</td>
                                </tr>
                                <tr>
                                    <td class="text-center" colspan="2"><b>Next of Kin Information</b></td>
                                </tr>
                                <tr>
                                    <td>Next of Kin Name: </td>
                                    <td>{{ $member->nok_name }}</td>
                                </tr>
                                <tr>
                                    <td>Next of Kin Phone: </td>
                                    <td>{{ $member->nok_phone }}</td>
                                </tr>
                                <tr>
                                    <td>Next of Kin Address: </td>
                                    <td>{{ $member->nok_address }}</td>
                                </tr>
                                <tr>
                                    <td>Relationship with Next of Kin : </td>
                                    <td>{{ $member->nok_rship }}</td>
                                </tr>
                            </tbody>
                        </table>                        
                    </div>
                    <div class="col-md-6"> 
                        <h5>Savings & Loans Balance</h5>                 
                        <table class="table table-condensed table-hover table-bordered">
                            <tbody>
                                <tr>
                                    <td>Total Savings: </td>
                                    <td>&#8358; {{ $member->monthly_savings_payments->isNotEmpty() ? number_format($member->monthly_savings_payments->last()->bal, 2) : 0.00 }}</td>
                                </tr>
                                <tr>
                                    <td>Long Term Loan Balance: </td>
                                    <td>&#8358; {{ $member->long_term_payments->isNotEmpty() ? number_format($member->long_term_payments->last()->bal, 2) : number_format(0, 2) }}</td>
                                </tr>
                                <tr>
                                    <td>Short Term Loan Balance: </td>
                                    <td>&#8358; {{ $member->short_term_payments->isNotEmpty() ? number_format($member->short_term_payments->last()->bal, 2) : number_format(0, 2) }}</td>
                                </tr>
                                <tr>
                                    <td>Commodity Loan Balance: </td>
                                    <td>&#8358; {{ $member->commodities_loans_payments->isNotEmpty() ? number_format($member->commodities_loans_payments->last()->bal, 2) : number_format(0, 2) }}</td>
                                </tr>
                            </tbody>
                        </table> 
                    </div>
                    <!-- <div class="col-md-6">
                        <h5>{{ $chart1->options['chart_title'] }}</h5>
                        {!! $chart1->renderHtml() !!}
                    </div> -->
                </div>
                

            </div>
        </div>
    </div>
</div>
@endsection


@section('js')
{!! $chart1->renderChartJsLibrary() !!}
{!! $chart1->renderJs() !!}
@endsection
