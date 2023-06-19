@extends('layouts.layout')
@section('one_page_js')
    <script src="{{ asset('plugins/chart.js/Chart.min.js') }}"></script>
@endsection
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">@lang('Dashboard')</a></li>
                    <li class="breadcrumb-item active">{{ __('Income Vs Expense Report') }}</li>
                </ol>
            </div>
        </div>
    </div>
</section>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ __('Income Vs Expense Summary') }}</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <div class="charts">
                            <div class="charts-chart">
                                <div>
                                    <canvas id="income_expense_chart" height="400"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="table table-responsive table-report">
                    <table class="table table-bordered table-striped table-hover" id="tbl-payments">
                        <thead>
                        <tr>
                            <th>@lang('Categories')</th>
                            @foreach($dates as $date)
                                <th class="text-right">{{ $date }}</th>
                            @endforeach
                        </tr>
                        </thead>
                        <tbody>
                        @if ($compares)
                            @foreach($compares as $type =>  $categories)
                                @foreach($categories as $category_id =>  $category)
                                    <tr>
                                        @if($type == 'income')
                                            <td>{{ $income_categories[$category_id] }}</td>
                                        @else
                                            <td>{{ $expense_categories[$category_id] }}</td>
                                        @endif
                                        @foreach($category as $item)
                                            @if($type == 'income')
                                                <td class="text-right">@money($item['amount'], $company->default_currency, true)</td>
                                            @else
                                                <td class="text-right">@money(-$item['amount'], $company->default_currency, true)</td>
                                            @endif
                                        @endforeach
                                    </tr>
                                @endforeach
                            @endforeach
                        @else
                            <tr>
                                <td colspan="13">
                                    <h5 class="text-center">@lang('No Records')</h5>
                                </td>
                            </tr>
                        @endif
                        </tbody>
                        <tfoot>
                        <tr>
                            <th>@lang('Totals')</th>
                            @foreach($totals as $total)
                                <th class="text-right">
                                    @if($total['amount'] == 0)
                                        <span>@money($total['amount'], $total['currency_code'], true)</span>
                                    @elseif($total['amount'] > 0)
                                        <span class="text-green">@money($total['amount'], $total['currency_code'], true)</span>
                                    @else
                                        <span class="text-red">@money($total['amount'], $total['currency_code'], true)</span>
                                    @endif
                                </th>
                            @endforeach
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var ctx = document.getElementById("income_expense_chart")
    var data = {
        labels: {!! $myMonth !!},
        datasets: [
            {
                fill: true,
                label: "Profit",
                lineTension: 0.3, borderColor: "#6da252",
                backgroundColor: "#6da252",
                data: {!! $myGraph !!},
            },
        ]
    };

    var myLineChart = new Chart(ctx, {
        type: 'line',
        data: data,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            legend: {
                display: true,
                position: 'top'
            },
        }
    });
</script>
@endsection
