@extends('master')

@section('body')
<!-- Page-Title -->
@if($member)
<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="page-title m-0">Short Term Loan Repayment</h4>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card m-b-30">
            <div class="card-body">

                <a href="{{route('members.dashboard', $member->ippis)}}"
                    class="btn btn-primary waves-effect waves-light mb-1"><i class="mdi mdi-backspace-outline"></i>
                    Dashboard</a>
                @can('create short term loan')
                <a href="{{route('members.newShortLoan', $member->ippis)}}"
                    class="btn btn-info waves-effect waves-light mb-1"><i class="mdi mdi-file-document-box"></i> New
                    Short Term Loan</a>
                @endcan


                @can('short term loan repayment')
                @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <div class="mt-4">
                    <h5>Short Term Loan Repayment</h5>

                    <short-term-loan-repayment :staff="{{ $member }}"></short-term-loan-repayment>

                    <!-- {!! Form::open(['route' => ['members.postShortLoanRepayment', $member->ippis]]) !!}

                {!! Form::hidden('ippis', $member->ippis) !!}

                <div class="form-group row"><label for="ref" class="col-sm-3 col-form-label">Ref</label>
                    <div class="col-sm-9">
                        {!! Form::text('ref', null, ['class' => 'form-control', 'id' => 'ref']) !!}
                    </div>
                </div>
                <div class="form-group row"><label for="total_amount" class="col-sm-3 col-form-label">Amount</label>
                    <div class="col-sm-9">
                        {!! Form::number('total_amount', null, ['class' => 'form-control', 'id' => 'total_amount']) !!}
                    </div>
                </div>
                <div class="form-group row"><label for="coop_no" class="col-sm-3 col-form-label">&nbsp </label>
                    <div class="col-sm-9">
                        <button class="btn btn-primary">Submit</button>
                    </div>
                </div>
                {!! Form::close() !!} -->
                </div>
                @endcan
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
