@extends('master')

@section('body')
<!-- Page-Title -->

<link href="https://themesdesign.in/zinzer_1/plugins/dropzone/dist/dropzone.css" rel="stylesheet" type="text/css">

<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="page-title m-0">Export and Import Monthly Deductions File</h4>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">

    @can('generate IPPIS deduction file')
    <div class="col-md-6">
        <div class="card m-b-30">
            <div class="card-body text-center">
                <h4 class="mt-0 header-title">Export Excel File</h4>
                <p class="text-muted m-b-30">
                    Select a center to generate monthly deductions
                </p>
                {!! Form::open(['route' => 'exportToIppis', 'method' => 'GET']) !!}
                    {{Form::select('pay_point', $centers, null, ['class' => 'form-control'])}}
                    <button href="{{ route('exportToIppis') }}" class="btn btn-primary btn-block mt-3">Generate monthly deduction</button>
                {!! Form::close() !!}
            </div>
        </div>
    </div><!-- end col -->
    @endcan

    @can('import and reconcile IPPIS deduction file')
    <div class="col-md-6">
        <div class="card m-b-30">
            <div class="card-body">
                <h4 class="mt-0 header-title text-center">Import Excel File</h4>
                <p class="text-muted m-b-30 text-center"><a href="#">Donwload</a> this file to see sample format</p>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {!! Form::open(['route' => 'importFromIppis', 'class' => '','files'=>'true']) !!}

                    <div class="form-group row"><label for="ref" class="col-sm-3 col-form-label">Select file</label>
                        <div class="col-sm-9">
                            <div class="fallback"><input name="file" type="file"></div>
                        </div>
                    </div>

                    <div class="form-group row"><label for="deduction_for" class="col-sm-3 col-form-label">Deduction For</label>
                        <div class="col-sm-9">
                            {!! Form::date('deduction_for', null, ['class' => 'form-control', 'id' => 'deduction_for']) !!}
                        </div>
                    </div>

                    <div class="form-group row"><label for="ref" class="col-sm-3 col-form-label">Description</label>
                        <div class="col-sm-9">
                            {!! Form::text('ref', null, ['class' => 'form-control', 'id' => 'ref']) !!}
                        </div>
                    </div>

                    <div class="text-center m-t-15">
                        <button type="submit" class="btn btn-block btn-primary waves-effect waves-light">Upload monthly repayment</button>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div><!-- end col -->
    @endcan
    
</div>
@endsection
