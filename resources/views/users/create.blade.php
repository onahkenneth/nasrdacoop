@extends('master')

@section('body')
<!-- Page-Title -->
<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="page-title m-0">USers</h4>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card m-b-30">
            <div class="card-body">

                <add-user></add-user>

                <!-- @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                {!! Form::open(array('route' => 'users.store', 'class' => '', 'role' => 'form')) !!}

                <div class="form-group">
                    {!! Form::label('staff_id', 'Staff', array('class' => 'control-label', 'for' => 'staff_id'))
                    !!}

                    {!! Form::select('staff_id', $staffs, null, ['class' => 'form-control select2', 'placeholder'
                    => 'Select one', 'id' => 'staff_id', 'required', 'style' => 'width:100%']) !!}

                </div>

                <div class="form-group">
                    {!! Form::label('password', 'Password', array('class' => 'control-label', 'for' =>
                    'password')) !!}

                    {!! Form::password('password', array('class' => 'form-control', 'id' => 'password')) !!}

                </div>

                <div class="form-group-separator"></div>

                <div class="form-group">
                    {!! Form::label('roles', 'Assign Role', array('class' => 'control-label', 'for' =>
                    'role_id')) !!}

                    {!! Form::select('role_ids[]', $roles, null, array('class' => 'form-control', 'id' =>
                    'role_id', 'required', 'multiple')) !!}
                </div>

                <div class="form-group-separator"></div>

                <div class="form-group">
                    {!! Form::label('assign_permission', ' ', array('class' => 'control-label', 'for' =>
                    'assign_permission')) !!}

                    {!! Form::submit('Add User', array('class' => 'btn btn-primary', 'id' => 'add-user')) !!}

                </div>

                {!! Form::close() !!} -->
            </div>
        </div>
    </div>
</div>

@endsection


@section('js')

@endsection
