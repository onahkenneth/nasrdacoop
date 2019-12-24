@extends('master')

@section('body')
<!-- Page-Title -->
<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="page-title m-0">Edit Center Information</h4>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card m-b-30">
            <div class="card-body">
            @can('update centre')

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {!! Form::model($center, ['route' => ['centers.update', $center->id], 'method' => 'PUT']) !!}
                <div class="form-group row"><label for="name" class="col-sm-2 col-form-label">Center Name</label>
                    <div class="col-sm-10">
                        {!! Form::text('name', null, ['class' => 'form-control', 'id' => 'name']) !!}
                    </div>
                </div>
                <div class="form-group row"><label for="code" class="col-sm-2 col-form-label">Unique Code</label>
                    <div class="col-sm-10">
                        {!! Form::text('code', null, ['class' => 'form-control', 'id' => 'code']) !!}
                    </div>
                </div>
                <div class="form-group row"><label for="coop_no" class="col-sm-2 col-form-label">&nbsp </label>
                    <div class="col-sm-10">
                        <button class="btn btn-primary">Edit</button>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
            @endcan
        </div>
    </div>
</div>

@endsection


@section('js')

@endsection

