@extends('master')

@section('body')
<!-- Page-Title -->
<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="page-title m-0">Centers</h4>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-6">
        <div class="card m-b-30">
            <div class="card-body">
            @can('create centre')
            <a href="{{ route('centers.create') }}" class="btn btn-primary">Add new center</a>
            @endcan
                @if($centers->count() > 0)
                    <div class="mt-3">
                        @can('read centre')
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Code</th>
                                    <th class="text-center">Action(s)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($centers as $center)
                                <tr>
                                    <td scope="row">{{ $center->name }}</td>
                                    <td>{{ $center->code }}</td>
                                    <td class="text-center">
                                        @can('update centre')
                                        <a href="{{ route('centers.edit', $center->id) }}" class="btn btn-primary btn-sm"><i class="mdi mdi-square-edit-outline"></i></a>
                                        @endcan
                                        @can('delete centre')
                                        <a href="{{ route('centers.destroy', $center->id) }}" class="btn btn-danger btn-sm"><i class="mdi mdi-trash-can-outline"></i></a>
                                        @endcan
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @endcan
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

