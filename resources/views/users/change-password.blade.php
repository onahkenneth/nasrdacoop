@extends('master')
@section('content')

<div class="tab-content">
    <div class="tab-pane tabs-animation fade show active" id="tab-conterole=" tabpanel">
        <div class="row">
            <div class="col-md-8">
                <div class="main-card mb-3 card">
                    <div class="card-header"><i class="header-icon lnr-license icon-gradient bg-plum-plate"> </i>Change
                        Password
                    </div>
                    <div class="card-body">
                        <div class="tab-content">
                            @include('flash::message')
                            @include('adminlte-templates::common.errors')
                            {!! Form::open(['route' => 'users.storeChangedPassword']) !!}

                            <input name="user_id" type="hidden" value="{{Auth::user()->id}}" />

                            <!-- Current Password -->
                            <div class="form-group row">
                                <label for="now_password" class="col-sm-4 col-form-label">Current Password</label>
                                <div class="col-sm-8">
                                    {!! Form::password('now_password', null, ['class' => 'form-control', 'id' =>
                                    'now_password']) !!}
                                </div>
                            </div>
                            <!-- New Password -->
                            <div class="form-group row">
                                <label for="password" class="col-sm-4 col-form-label">New Password</label>
                                <div class="col-sm-8">
                                    {!! Form::password('password', null, ['class' => 'form-control', 'id' =>
                                    'password']) !!}
                                </div>
                            </div>
                            <!-- Confirm New Password -->
                            <div class="form-group row">
                                <label for="password_confirmation" class="col-sm-4 col-form-label">Confirm New
                                    Password</label>
                                <div class="col-sm-8">
                                    {!! Form::password('password_confirmation', null, ['class' => 'form-control', 'id'
                                    => 'password_confirmation']) !!}
                                </div>
                            </div>

                            <!-- Submit Field -->
                            <div class="form-group col-sm-12">
                                {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                                <a href="{!! route('units.index') !!}" class="btn btn-secondary">Cancel</a>
                            </div>

                            {!! Form::close() !!}

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
