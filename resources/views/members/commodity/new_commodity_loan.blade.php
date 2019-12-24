@extends('master')

@section('body')
<!-- Page-Title -->
@if($member)
<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="page-title m-0">New Commodity Loan <span class="text-danger">[ {{ $member->full_name }} | {{ $member->ippis }} ]</span></h4>
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
                <a href="{{route('members.commodity', $member->ippis)}}" class="btn btn-primary waves-effect waves-light"><i class="mdi mdi-backspace-outline"></i> Commodity Loans</a>
                
                @can('create commodity loan')
                <div class="mt-3">
                    <new-commodity-loan :staff="{{ $member }}"></new-commodity-loan>
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