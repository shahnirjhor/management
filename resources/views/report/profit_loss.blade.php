@extends('layouts.layout')
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">@lang('Dashboard')</a></li>
                    <li class="breadcrumb-item active">{{ __('Tax Report') }}</li>
                </ol>
            </div>
        </div>
    </div>
</section>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ __('Tax Summary') }}</h3>
                <div class="card-tools">
                    <form action="" method="get" role="form">
                        <div class="form-row">
                            <div class="col-4">
                                <select name="status" class="form-control">
                                    @foreach ($statuses as $key => $value)
                                        <option value="{{ $key }}" @if($key == request()->status) selected @endif>{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-4">
                                <select name="year" class="form-control">
                                    @foreach ($years as $key => $value)
                                        @php
                                            (request()->year) ?  $year = request()->year : $year = $thisYear;
                                        @endphp
                                        <option value="{{ $key }}" @if($key == $year) selected @endif>{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-4">
                                <button type="submit" class="btn btn-default"><i class="fas fa-filter"></i> @lang('Filter')</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="col-sm-2">&nbsp;</th>
                                @foreach($dates as $date)
                                    <th class="col-sm-2 text-right">{{ trans('quarter.'.$date) }}</th>
                                @endforeach
                                <th class="col-sm-2 text-right">@lang('Totals')</th>
                            </tr>
                        </thead>
                    </table>
                    <table class="table table-hover" style="margin-top: 40px">
                        <thead>
                            <tr>
                                <th class="col-sm-2" colspan="6">@lang('Incomes')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($compares['income'] as $category_id => $category)
                                <tr>
                                    <td class="col-sm-2">{{ $income_categories[$category_id] }}</td>
                                    @foreach($category as $i => $item)
                                        @php $gross['income'][$i] += $item['amount']; @endphp
                                        <td class="col-sm-2 text-right">@money($item['amount'], $company->default_currency, true)</td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th class="col-sm-2">@lang('Gross Profit')</th>
                                @foreach($gross['income'] as $item)
                                    <th class="col-sm-2 text-right">@money($item, $company->default_currency, true)</th>
                                @endforeach
                            </tr>
                        </tfoot>
                    </table>
                    <table class="table table-hover" style="margin-top: 40px">
                        <thead>
                            <tr>
                                <th class="col-sm-2" colspan="6">@lang('Expenses')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($compares['expense'] as $category_id => $category)
                                <tr>
                                    <td class="col-sm-2">{{ $expense_categories[$category_id] }}</td>
                                    @foreach($category as $i => $item)
                                        @php $gross['expense'][$i] += $item['amount']; @endphp
                                        <td class="col-sm-2 text-right">@money($item['amount'], $company->default_currency, true)</td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th class="col-sm-2">@lang('Total Expenses')</th>
                                @foreach($gross['expense'] as $item)
                                    <th class="col-sm-2 text-right">@money($item, $company->default_currency, true)</th>
                                @endforeach
                            </tr>
                        </tfoot>
                    </table>
                    <table class="table" style="margin-top: 40px">
                        <tbody>
                            <tr>
                                <th class="col-sm-2" colspan="6">@lang('Net Profit')</th>
                                @foreach($totals as $total)
                                    <th class="col-sm-2 text-right"><span>@money($total['amount'], $total['currency_code'], true)</span></th>
                                @endforeach
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
