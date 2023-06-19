@extends('layouts.layout')

@section('one_page_css')
    <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
@endsection
@section('one_page_js')
    <!-- ChartJS -->
    <script src="{{ asset('assets/plugins/chart.js/Chart.min.js') }}"></script>
@endsection
@section('content')
<style>
    #donutChart1{
        height: 300px !important;
    }
    #donutChart2{
        height: 300px !important;
    }
</style>
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h2>@lang('Dashboard')</h2>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active">@lang('Dashboard')</li>
                </ol>
            </div>
        </div>
    </div>
</section>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-4 col-sm-6 col-12">
            <div class="info-box">
                <span class="info-box-icon bg-info"><i class="fas fa-globe"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Application</span>
                    <span class="info-box-number">{{ $data_total->total_scholarships }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-6 col-12">
            <div class="info-box">
                <span class="info-box-icon bg-primary"><i class="fas fa-money-bill-wave"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Payment In Progress</span>
                    <span class="info-box-number">{{ $data_payment_in_progress->total_scholarships }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-6 col-12">
            <div class="info-box">
                <span class="info-box-icon bg-success"><i class="fas fa-hand-holding-usd"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Payment Done</span>
                    <span class="info-box-number">{{ $data_payment_done->total_scholarships }}</span>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 col-sm-6 col-12">
            <div class="info-box">
                <span class="info-box-icon bg-success"><i class="fas fa-thumbs-up"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Approved</span>
                    <span class="info-box-number">{{ $data_approved->total_scholarships }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-6 col-12">
            <div class="info-box">
                <span class="info-box-icon bg-warning"><i class="fas fa-stop"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Under Verification</span>
                    <span class="info-box-number">{{ $data_pending->total_scholarships }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-6 col-12">
            <div class="info-box">
                <span class="info-box-icon bg-danger"><i class="fas fa-times-circle"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Rejected</span>
                    <span class="info-box-number">{{ $data_rejected->total_scholarships }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title custom-color-white">Current Year @lang('Application Vs Approved Vs Rejected') </h3>
                </div>
                <div class="card-body">
                    <div class="chart">
                        <canvas id="donutChart1" class="custom-dashbord-mix"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title custom-color-white">Overall @lang('Application Vs Approved Vs Rejected') </h3>
                </div>
                <div class="card-body">
                    <div class="chart">
                        <canvas id="donutChart2" class="custom-dashbord-mix"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        "use strict";
        let siteUrl = $('meta[name="site-url"]').attr('content');
        $.get(siteUrl + "/dashboard/get-chart-data", function(data, status){

            var donutData        = {
                labels: [
                    'Application',
                    'Approved',
                    'Rejected'
                ],
                datasets: [
                    {
                        data: [data.currentYearApApRe.application, data.currentYearApApRe.approved, data.currentYearApApRe.rejected],
                        backgroundColor : ['#17a2b8', '#00a65a', '#dc3545'],
                    }
                ]
            };

            var donutChartCanvas = $('#donutChart1').get(0).getContext('2d');
            var donutData        = {
                labels: [
                    'Application',
                    'Approved',
                    'Rejected'
                ],
                datasets: [
                    {
                        data: [data.currentYearApApRe.application, data.currentYearApApRe.approved, data.currentYearApApRe.rejected],
                        backgroundColor : ['#17a2b8', '#00a65a', '#dc3545'],
                    }
                ]
            };
            var donutOptions     = {
                maintainAspectRatio : false,
                responsive : true,
            };

            new Chart(donutChartCanvas, {
                type: 'doughnut',
                data: donutData,
                options: donutOptions
            });

            var donutChartCanvas2 = $('#donutChart2').get(0).getContext('2d');
            var donutData2        = {
                labels: [
                    'Application',
                    'Approved',
                    'Rejected'
                ],
                datasets: [
                    {
                        data: [data.overallYearApApRe.application, data.overallYearApApRe.approved, data.overallYearApApRe.rejected],
                        backgroundColor : ['#17a2b8', '#00a65a', '#dc3545'],
                    }
                ]
            };
            var donutOptions     = {
                maintainAspectRatio : false,
                responsive : true,
            };

            new Chart(donutChartCanvas2, {
                type: 'doughnut',
                data: donutData2,
                options: donutOptions
            });

        });

    });
</script>


@endsection
