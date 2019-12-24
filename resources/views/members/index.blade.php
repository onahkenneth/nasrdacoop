@extends('master')

@section('body')
<!-- Page-Title -->
<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="page-title m-0">Members</h4>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card m-b-30">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            @can('create member')
                            <a href="{{ route('addMember') }}" class="btn btn-primary">Add Member</a>
                            @endcan
                        </div>
                    </div>
                    <div class="col-md-6 text-right">
                        {!! Form::open(['route' => ['members.index'], 'method' => 'get']) !!}
                        <div class="row">
                            <div class="col-md-4">
                                {{Form::select('pay_point', $centers, null, ['class' => 'form-control mb-3', 'placeholder' => 'Pay point'])}}
                            </div>
                            <div class="col-md-4">
                                {{Form::select('status', [1 => 'active', 0 => 'deactivated'], null, ['class' => 'form-control mb-3', 'placeholder' => 'Status'])}}
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary waves-effect waves-light mb-3">Filter</button>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive mt-4">
                            @if($members->count() > 0)
                            @can('read member')
                            <table class="table table-hover table-bordered mb-0 datatable">
                                <thead>
                                    <tr>
                                        <!-- <th class="text-center">#</th> -->
                                        <th class="text-left">Name</th>
                                        <th class="text-center">IPPIS</th>
                                        <th class="text-center">Pay Point</th>
                                        <th class="text-right">Savings Balance</th>
                                        <th class="text-right">Long Term Loan Balance</th>
                                        <th class="text-right">Short Term Loan Balance</th>
                                        <th class="text-right">Commodity Loan Balance</th>
                                        @can('view member dashboard')
                                        <th class="text-center">Action(s)</th>
                                        @endcan
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($members as $member)
                                    <tr>
                                        <!-- <th class="text-center" scope="row">{{ $loop->iteration }}</th> -->
                                        <td>{{ $member->full_name }}</td>
                                        <td class="text-center">{{ $member->ippis }}</td>
                                        <td class="text-center">{{ $member->member_pay_point ? $member->member_pay_point->name : '' }}</td>
                                        <td class="text-right">{{ $member->monthly_savings_payments->isNotEmpty() ? number_format($member->monthly_savings_payments->last()->bal, 2) : '0.00' }}</td>
                                        <td class="text-right">{{$member->long_term_payments->isNotEmpty() ?  number_format($member->long_term_payments->last()->bal, 2) : '0.00' }}</td>
                                        <td class="text-right">{{ $member->short_term_payments->isNotEmpty() ? number_format($member->short_term_payments->last()->bal, 2) : '0.00' }}</td>
                                        <td class="text-right">{{ $member->commodities_loans_payments->isNotEmpty() ? number_format($member->commodities_loans_payments->last()->bal, 2) : '0.00' }}</td>
                                        @can('view member dashboard')
                                        <td class="text-center">
                                            <a href="{{ route('members.dashboard', $member->ippis) }}" class="btn btn-primary">Dashboard</a>
                                        </td>
                                        @endcan
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @endcan
                            @else
                            <p>
                                No records found
                            </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12 text-center">
    {{ $members->links() }}
    </div>
</div>
@endsection
