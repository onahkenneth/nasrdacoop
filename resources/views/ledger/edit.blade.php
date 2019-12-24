@extends('master')

@section('body')
<!-- Page-Title -->
<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="page-title m-0">Edit Ledger Entry</h4>
                </div>
            </div>
        </div>
    </div>
</div>

<edit-ledger-entry :ledger_entry="{{$ledger}}"><edit-ledger-entry/>

@endsection


@section('js')

@endsection

