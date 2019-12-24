@extends('master')
@section('content')

<div class="tab-content">
    <div class="tab-pane tabs-animation fade show active" id="tab-conterole="tabpanel">
        <div class="row">
            <div class="col-md-8">
                <div class="main-card mb-3 card">
                    <div class="card-header"><i class="header-icon lnr-license icon-gradient bg-plum-plate"> </i>Edit User
                    </div>
                    <div class="card-body">
                        <div class="tab-content">
                            
  @include('flash::message')
  <div class="row">
    <div class="col-md-12">
        
    @if (count($errors) > 0)
    <div class="alert alert-danger">
      <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
    @endif

    {!! Form::model($user, ['route' => ['users.update', $user->id], 'method' => 'PUT', 'class' => 'form-horizontal ajaxForm', 'role' => 'form']) !!}

    <div class="form-group">
      {!! Form::label('staff_id', 'Member', array('class' => 'control-label', 'for' => 'staff_id')) !!}

      {!! Form::select('staff_id', $members, null, ['class' => 'form-control select', 'placeholder' => 'Select one', 'id' => 'staff_id', 'required', 'disabled', 'style' => 'width:100%']) !!}
      
      {!! Form::hidden('staff_id', $user->id) !!}

    </div>

    <div class="form-group-separator"></div>

    <div class="form-group">
      {!! Form::label('password', 'Password', array('class' => 'control-label', 'for' => 'staff_id')) !!}

      <input class="form-control" type="password" id="password" name="password" />
      
    </div>

    <div class="form-group-separator"></div>

    <div class="form-group">
      {!! Form::label('roles', 'Assign Role(s)', array('class' => 'control-label', 'for' => 'assign_permission')) !!}

      {!! Form::select('role_ids[]', $roles, null, array('class' => 'form-control', 'id' => 'role_id', 'required', 'multiple')) !!}
    </div>

    <div class="form-group-separator"></div>

    <div class="form-group">
      {!! Form::label('assign_permission', ' ', array('class' => 'control-label', 'for' => 'assign_permission')) !!}

      {!! Form::submit('Update User', array('class' => 'btn btn-primary', 'id' => 'add-user')) !!}

    </div>

    {!! Form::close() !!}
    </div>
    <div class="col-md-4">
        
    </div>
  </div>                
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

