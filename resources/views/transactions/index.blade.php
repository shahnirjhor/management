@extends('layouts.layout')
@section('one_page_js')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
@endsection

@section('one_page_css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
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
                    <li class="breadcrumb-item active">@lang('Transaction List')</li>
                </ol>
            </div>
        </div>
    </div>
</section>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">@lang('Transactions') </h3>
                <div class="card-tools">
                    <button class="btn btn-default" data-toggle="collapse" href="#filter"><i class="fas fa-filter"></i> @lang('Filter')</button>
                </div>
            </div>
            <div class="card-body">
                <div id="filter" class="collapse @if(request()->isFilterActive) show @endif">
                    <div class="card-body border">
                        <form action="" method="get" role="form" autocomplete="off">
                            <input type="hidden" name="isFilterActive" value="true">
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>@lang('Date')</label>
                                        <input type="text" name="paid_at" class="form-control flatpickr" value="{{ request()->paid_at }}" placeholder="@lang('Date')">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>@lang('Account')</label>
                                        <select name="account_id" class="form-control">
                                            <option value="">--@lang('Select')--</option>
                                            @foreach ($accounts as $key => $value)
                                                <option value="{{ $key }}" @if($key == request()->account_id) selected @endif>{{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>@lang('Amount')</label>
                                        <input type="text" name="amount" class="form-control" value="{{ request()->amount }}" placeholder="@lang('Amount')">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <button type="submit" class="btn btn-info">@lang('Submit')</button>
                                    @if(request()->isFilterActive)
                                        <a href="{{ route('transaction.index') }}" class="btn btn-secondary">@lang('Clear')</a>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <table id="laravel_datatable" class="table table-striped compact table-width">
                    <thead>
                        <tr>
                            <th>@lang('Date')</th>
                            <th>@lang('Account Name ')</th>
                            <th>@lang('Type')</th>
                            <th>@lang('Category')</th>
                            <th>@lang('Amount')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transactions as $transaction)
                            <tr>
                                <td>{{ date($company->date_format, strtotime($transaction['paid_at'])) }}</td>
                                <td>{{ $transaction['account_name'] }}</td>
                                <td>{{ $transaction['type'] }}</td>
                                <td>{{ $transaction['category_name'] }}</td>
                                <td>@money($transaction['amount'], $transaction['currency_code'], true)</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{-- {{ $transactions->links() }} --}}
            </div>
        </div>
    </div>
</div>
<script>
    "use strict";
    $(document).ready( function () {

        $(".flatpickr").flatpickr({
            enableTime: false
        });

        $('#laravel_datatable').DataTable({
            "paging": false,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": false,
            "autoWidth": false,
            "responsive": true,
        });
    });
</script>
@endsection
