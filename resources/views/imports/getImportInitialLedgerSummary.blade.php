@extends('master')

@section('body')
<!-- Page-Title -->

<link href="https://themesdesign.in/zinzer_1/plugins/dropzone/dist/dropzone.css" rel="stylesheet" type="text/css">

<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="page-title m-0">Upload Existing Ledger Summary</h4>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-6">
        <div class="card m-b-30">
            <div class="card-body">
                <h4 class="mt-0 header-title">Upload existing ledger summary excel file</h4>
                <p class="text-muted m-b-30">Upload an Excel file in the format below.</p>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                {!! Form::open(['route' => 'postImportInitialLedgerSummary', 'class' => '','files'=>'true']) !!}
                    <div class="m-b-30">
                            <div class="fallback"><input name="file" type="file" multiple="multiple"></div>
                    </div>
                    <div class="text-center m-t-15">
                        {{Form::select('pay_point', $centers, null, ['class' => 'form-control'])}}
                    </div>
                    <div class="text-center m-t-15">
                        <button type="submit" class="btn btn-primary waves-effect waves-light">Send Files</button>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div><!-- end col -->
</div>
@endsection
