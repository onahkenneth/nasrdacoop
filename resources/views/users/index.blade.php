@extends('master')

@section('body')
<!-- Page-Title -->
<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="page-title m-0">Users</h4>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card m-b-30">
            <div class="card-body">
            <a href="{{ route('users.create') }}" class="btn btn-primary">Add/Edit user</a>
                @if($users->count() > 0)
                    <div class="mt-3">
                        <table class="table table-responsive">
                            <thead>
                                <tr>
                                    <th class="text-center">
                                        #
                                    </th>
                                    <th class="text-center">Name</th>
                                    <th class="text-center">Role(s)</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>

                            <tbody class="middle-align">
                                @foreach($users as $user)
                                <tr id="tr_{{$user->id}}">
                                    <td class="text-center">
                                        {{$loop->iteration}}
                                    </td>
                                    <td>
                                        {{ $user->name }}
                                    </td>
                                    <td>
                                        @if($user->roles)
                                            @foreach($user->roles as $role)
                                            {{$role->name}} <br>
                                            @endforeach
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <!-- <a href="{!! route('users.edit', array($user->id)) !!}"
                                            class="btn btn-success btn-sm btn-icon icon-left">
                                            Edit
                                        </a> -->

                                        <a href="{{ route('users.delete', array($user->id)) }}"
                                            class="btn btn-danger btn-sm btn-icon icon-left">
                                            Delete
                                        </a>

                                        <a data-toggle="modal" data-keyboard="false" data-target="#myModal"
                                            data-remote="{!! route('members.show', $user->staff->ippis) !!}"
                                            href="#" class="btn btn-info btn-sm">Member's Details</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                <p class="mt-3">
                    No records found
                </p>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection


@section('js')

@endsection

