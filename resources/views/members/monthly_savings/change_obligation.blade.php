@extends('master')

@section('body')
<!-- Page-Title -->
@if($member)
<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="page-title m-0">Change Monthly Contribution</h4>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-6">
        <div class="card m-b-30">
            <div class="card-body">

            <a href="{{route('members.dashboard', $member->ippis)}}" class="btn btn-primary waves-effect waves-light"><i class="mdi mdi-backspace-outline"></i> Dashboard</a>
            <a href="{{route('members.savings', $member->ippis)}}" class="btn btn-info waves-effect waves-light"><i class="mdi mdi-file-document-box"></i> Savings</a>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @can('change monthly contribution')
            <div class="mt-4">
            <h5>Change Monthly Contribution</h5>
            {!! Form::open(['route' => ['members.postSavingsChangeObligation', $member->ippis]]) !!}

                {!! Form::hidden('ippis', $member->ippis) !!}

                <div class="form-group row"><label for="current_obligation" class="col-sm-3 col-form-label">Current Obligation</label>
                    <div class="col-sm-9">
                        {!! Form::number('current_obligation', $member->monthly_savings->last()->amount, ['class' => 'form-control', 'id' => 'current_obligation', 'disabled']) !!}
                    </div>
                </div>
                <div class="form-group row"><label for="new_obligation" class="col-sm-3 col-form-label">New Obligation</label>
                    <div class="col-sm-9">
                        {!! Form::number('new_obligation', null, ['class' => 'form-control', 'id' => 'new_obligation']) !!}
                    </div>
                </div>
                <div class="form-group row"><label for="coop_no" class="col-sm-3 col-form-label">&nbsp </label>
                    <div class="col-sm-9">
                        <button class="btn btn-primary">Submit</button>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
            </div>
            @endcan
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

