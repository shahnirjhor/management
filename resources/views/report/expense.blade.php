
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
                    <li class="breadcrumb-item active">{{ __('Expense Report') }}</li>
                </ol>
            </div>
        </div>
    </div>
</section>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ __('Expense Summary') }}</h3>
                <div class="card-tools">
                    <form action="" method="get" role="form">
                        <div class="form-row">
                            <div class="col-2">
                                <select name="status" class="form-control">
                                    @foreach ($statuses as $key => $value)
                                        <option value="{{ $key }}" @if($key == request()->status) selected @endif>{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-2">
                                <select name="year" class="form-control">
                                    @foreach ($years as $key => $value)
                                        @php
                                            ($thisYear == request()->year) ?  $cYear = $thisYear : $cYear = request()->year;
                                        @endphp
                                        <option value="{{ $key }}" @if($key == $cYear) selected @endif>{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-3">
                                <select name="category_id" class="form-control">
                                    <option value="">@lang('Select Category')</option>
                                    @foreach ($categories as $key => $value)
                                        <option value="{{ $key }}" @if($key == request()->category_id) selected @endif>{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-3">
                                <select name="account_id" class="form-control">
                                    <option value="">@lang('Select Account')</option>
                                    @foreach ($accounts as $key => $value)
                                        <option value="{{ $key }}" @if($key == request()->account_id) selected @endif>{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-2">
                                <button type="submit" class="btn btn-default"><i class="fas fa-filter"></i> @lang('Filter')</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card-body table table-responsive">
                <div class="row">
                    <div class="col-12">
                        <div class="charts">
                            <div class="charts-chart">
                                <div>
                                    <canvas id="myExpenseChart" height="400"></canvas>
                                </div>
                            </div>
                        </div>
                        <br>
                        <br>
                        <table class="table table-striped compact table-width table-bordered">
                            <thead>
                                <tr class="table-info">
                                    <th>@lang('Category')</th>
                                    @foreach($dates as $date)
                                        <th class="text-right">{{ $date }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @if ($expenses)
                                    @foreach($expenses as $category_id =>  $category)
                                        <tr>
                                            <td>{{ $categories[$category_id] }}</td>
                                            @foreach($category as $item)
                                                <td class="text-right">@money($item['amount'], $company->default_currency, true)</td>
                                            @endforeach
                                        </tr>
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
                                        <th class="text-right">@money($total['amount'], $total['currency_code'], true)</th>
                                    @endforeach
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var ctx = document.getElementById("myExpenseChart")
    var data = {
        labels: {!! $myMonth !!},
        datasets: [
            {
                fill: true,
                label: "Expense",
                lineTension: 0.3, borderColor: "#F56954",
                backgroundColor: "#F56954",
                data: {!! $myExpensesGraph !!},
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
