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
            <div class="card-body table table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th style="width: 120px;">&nbsp;</th>
                            @foreach($dates as $date)
                                <th class="text-right">{{ $date }}</th>
                            @endforeach
                        </tr>
                    </thead>
                </table>
                @if ($taxes)
                @foreach($taxes as $tax_name)
                <table class="table table-hover" style="margin-top: 40px">
                    <thead>
                        <tr>
                            <th style="width: 120px;" colspan="13">{{ $tax_name }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="width: 120px;">@lang('Incomes')</td>
                            @foreach($incomes[$tax_name] as $tax_date)
                                <td class="text-right">@money($tax_date['amount'], $company->default_currency, true)</td>
                            @endforeach
                        </tr>
                        <tr>
                            <td style="width: 120px;">@lang('Expenses')</td>
                            @foreach($expenses[$tax_name] as $tax_date)
                                <td class="text-right">@money($tax_date['amount'], $company->default_currency, true)</td>
                            @endforeach
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th style="width: 120px;">@lang('Net')</th>
                            @foreach($totals[$tax_name] as $tax_date)
                                <th class="text-right">@money($tax_date['amount'], $company->default_currency, true)</th>
                            @endforeach
                        </tr>
                    </tfoot>
                </table>
                @endforeach
                @else
                    <table class="table table-bordered table-striped table-hover" style="margin-top: 40px">
                        <tbody>
                            <tr>
                                <td colspan="13">
                                    <h5 class="text-center">@lang('No Records')</h5>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
