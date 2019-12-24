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
                </div>
            </div>
        </div>
    </div>
</div><!-- end page title end breadcrumb -->
<div class="row">
    <div class="col-xl-3 col-md-6">
        <div class="card bg-primary mini-stat text-white">
            <div class="p-3 mini-stat-desc">
                <div class="clearfix">
                    <h6 class="text-uppercase mt-0 float-left text-white-50">Savings</h6>
                    <h4 class="mb-3 mt-0 float-right">&#8358; {{ number_format($totalSavings) }}</h4>
                </div>
            </div>
            <!-- <div class="p-3">
                <div class="float-right"><a href="#" class="text-white-50"><i class="mdi mdi-cube-outline h5"></i></a>
                </div>
                <p class="font-14 m-0">Last : 1447</p>
            </div> -->
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card bg-info mini-stat text-white">
            <div class="p-3 mini-stat-desc">
                <div class="clearfix">
                    <h6 class="text-uppercase mt-0 float-left text-white-50">Long Term</h6>
                    <h4 class="mb-3 mt-0 float-right">&#8358; {{ number_format($totalLTL) }}</h4>
                </div>
            </div>
            <!-- <div class="p-3">
                <div class="float-right"><a href="#" class="text-white-50"><i class="mdi mdi-buffer h5"></i></a></div>
                <p class="font-14 m-0">Last : $47,596</p>
            </div> -->
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card bg-pink mini-stat text-white">
            <div class="p-3 mini-stat-desc">
                <div class="clearfix">
                    <h6 class="text-uppercase mt-0 float-left text-white-50">Short Term</h6>
                    <h4 class="mb-3 mt-0 float-right">&#8358; {{ number_format($totalSTL) }}</h4>
                </div>
            </div>
            <!-- <div class="p-3">
                <div class="float-right"><a href="#" class="text-white-50"><i
                            class="mdi mdi-tag-text-outline h5"></i></a></div>
                <p class="font-14 m-0">Last : 15.8</p>
            </div> -->
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card bg-success mini-stat text-white">
            <div class="p-3 mini-stat-desc">
                <div class="clearfix">
                    <h6 class="text-uppercase mt-0 float-left text-white-50">Commodities</h6>
                    <h4 class="mb-3 mt-0 float-right">&#8358; {{ number_format($totalCommodity) }}</h4>
                </div>
            </div>
            <!-- <div class="p-3">
                <div class="float-right"><a href="#" class="text-white-50"><i
                            class="mdi mdi-briefcase-check h5"></i></a></div>
                <p class="font-14 m-0">Last : 1776</p>
            </div> -->
        </div>
    </div>
</div><!-- end row -->
<div class="row">
    <div class="col-xl-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Savings</h4>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="morris-chart" style="height: 300px">
                            {!! $monthlySavings->container() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Long Term Loans</h4>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="morris-chart" style="height: 300px">
                            {!! $longTermChart->container() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Short Term Loans</h4>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="morris-chart" style="height: 300px">
                            {!! $shortTermChart->container() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Commodities Loans</h4>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="morris-chart" style="height: 300px">
                            {!! $commodityChart->container() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div><!-- end row -->
@endsection

@section('js')
<!-- dashboard js -->
<script src="{{ asset('assets/pages/dashboard.int.js') }}"></script>
<script src="{{ asset('js/Chart.min.js') }}"></script>

{!! $monthlySavings->script() !!}
{!! $longTermChart->script() !!}
{!! $shortTermChart->script() !!}
{!! $commodityChart->script() !!}
@endsection
