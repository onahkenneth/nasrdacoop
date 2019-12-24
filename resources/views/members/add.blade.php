@extends('master')

@section('body')
<!-- Page-Title -->
<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="page-title m-0">Add new member</h4>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-6">
        <div class="card m-b-30">
            <div class="card-body">

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {!! Form::open(['route' => 'saveMember']) !!}
                <div class="form-group row"><label for="pf" class="col-sm-3 col-form-label">PF</label>
                    <div class="col-sm-9">
                        {!! Form::text('pf', null, ['class' => 'form-control', 'id' => 'pf']) !!}
                    </div>
                </div>
                <div class="form-group row"><label for="monthly_savings" class="col-sm-3 col-form-label">MONTHLY SAVING</label>
                    <div class="col-sm-9">
                        {!! Form::number('monthly_savings', null, ['class' => 'form-control', 'id' => 'monthly_savings']) !!}
                    </div>
                </div>
                <div class="form-group row"><label for="ippis" class="col-sm-3 col-form-label">IPPIS</label>
                    <div class="col-sm-9">
                        {!! Form::number('ippis', null, ['class' => 'form-control', 'id' => 'ippis']) !!}
                    </div>
                </div>
                <div class="form-group row"><label for="full_name" class="col-sm-3 col-form-label">FULL NAME</label>
                    <div class="col-sm-9">
                        {!! Form::text('full_name', null, ['class' => 'form-control', 'id' => 'full_name']) !!}
                    </div>
                </div>
                <div class="form-group row"><label for="phone" class="col-sm-3 col-form-label">PHONE</label>
                    <div class="col-sm-9">
                        {!! Form::text('phone', null, ['class' => 'form-control', 'id' => 'phone']) !!}
                    </div>
                </div>
                <div class="form-group row"><label for="email" class="col-sm-3 col-form-label">EMAIL</label>
                    <div class="col-sm-9">
                        {!! Form::email('email', null, ['class' => 'form-control', 'id' => 'email']) !!}
                    </div>
                </div>
                <div class="form-group row"><label for="pay_point" class="col-sm-3 col-form-label">PAY POINT</label>
                    <div class="col-sm-9">
                        {!! Form::select('pay_point', $centers, null, ['class' => 'form-control', 'id' => 'pay_point', 'placeholder' => 'Select pay point']) !!}
                    </div>
                </div>
                <div class="form-group row"><label for="center_id" class="col-sm-3 col-form-label">CENTER</label>
                    <div class="col-sm-9">
                        {!! Form::select('center_id', $centers, null, ['class' => 'form-control', 'id' => 'center_id', 'placeholder' => 'Select center']) !!}
                    </div>
                </div>
                <div class="form-group row"><label for="coop_no" class="col-sm-3 col-form-label">COOP NO</label>
                    <div class="col-sm-9">
                        {!! Form::text('coop_no', null, ['class' => 'form-control', 'id' => 'coop_no']) !!}
                    </div>
                </div>
                <!-- <div class="form-group row"><label for="sbu" class="col-sm-3 col-form-label">SBU</label>
                    <div class="col-sm-9">
                        {!! Form::text('sbu', null, ['class' => 'form-control', 'id' => 'sbu']) !!}
                    </div>
                </div> -->
                <div class="form-group row"><label for="nok_name" class="col-sm-3 col-form-label">NEXT OF KIN NAME</label>
                    <div class="col-sm-9">
                        {!! Form::text('nok_name', null, ['class' => 'form-control', 'id' => 'nok_name']) !!}
                    </div>
                </div>
                <div class="form-group row"><label for="nok_phone" class="col-sm-3 col-form-label">NEXT OF KIN PHONE</label>
                    <div class="col-sm-9">
                        {!! Form::text('nok_phone', null, ['class' => 'form-control', 'id' => 'nok_phone']) !!}
                    </div>
                </div>
                <div class="form-group row"><label for="nok_rship" class="col-sm-3 col-form-label">RELATIONSHIP WITH NEXT OF KIN</label>
                    <div class="col-sm-9">
                        {!! Form::text('nok_rship', null, ['class' => 'form-control', 'id' => 'nok_rship']) !!}
                    </div>
                </div>
                <div class="form-group row"><label for="nok_address" class="col-sm-3 col-form-label">NEXT OF KIN ADDRESS</label>
                    <div class="col-sm-9">
                        {!! Form::textarea('nok_address', null, ['class' => 'form-control', 'id' => 'nok_address']) !!}
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
    </div>
</div>

@endsection


@section('js')

@endsection

