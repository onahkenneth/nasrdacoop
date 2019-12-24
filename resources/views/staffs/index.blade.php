@extends('master')

@section('body')
<!-- Page-Title -->
<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="page-title m-0">Dashboard</h4>
                </div>
                <div class="col-md-4">
                    <div class="float-right d-none d-md-block">
                        <div class="dropdown"><button class="btn btn-primary dropdown-toggle" type="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i
                                    class="ti-settings mr-1"></i> Settings</button>
                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-animated"><a
                                    class="dropdown-item" href="#">Action</a> <a class="dropdown-item" href="#">Another
                                    action</a> <a class="dropdown-item" href="#">Something
                                    else here</a>
                                <div class="dropdown-divider"></div><a class="dropdown-item" href="#">Separated link</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card bg-primary mini-stat text-white">
            <div class="p-3 mini-stat-desc">
                <div class="clearfix">
                    <h6 class="text-uppercase mt-0 float-left text-white-50">Orders</h6>
                    <h4 class="mb-3 mt-0 float-right">1,587</h4>
                </div>
                <div>
                    <span class="badge badge-light text-info">+11% </span><span class="ml-2">From previous period</span>
                </div>
            </div>
            <div class="p-3">
                <div class="float-right"><a href="#" class="text-white-50"><i class="mdi mdi-cube-outline h5"></i></a>
                </div>
                <p class="font-14 m-0">Last : 1447</p>
            </div>
        </div>
    </div>
</div>
@endsection
