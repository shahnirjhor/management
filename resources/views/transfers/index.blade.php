@extends('layouts.layout')
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                @can('transfer-create')
                    <h3><a href="{{ route('transfer.create') }}" class="btn btn-outline btn-info">+ @lang('Add New Transfer')</a></h3>
                @endcan
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">@lang('Dashboard')</a></li>
                    <li class="breadcrumb-item active">@lang('Transfer List')</li>
                </ol>
            </div>
        </div>
    </div>
</section>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">@lang('Transfers') </h3>
                <div class="card-tools">
                    @can('transfer-export')
                        <a class="btn btn-primary" target="_blank" href="{{ route('transfer.index') }}?export=1">
                            <i class="fas fa-cloud-download-alt"></i> @lang('Export')
                        </a>
                    @endcan
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
                                        <input type="text" name="paid_at" id="paid_at" class="form-control flatpickr" placeholder="@lang('Date')" value="{{ old('paid_at', request()->paid_at) }}">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>@lang('From Account')</label>
                                        <select name="from_account" class="form-control">
                                            <option value="">--@lang('Select')--</option>
                                            @foreach ($accounts as $key => $value)
                                                <option value="{{ $key }}" @if($key == old('from_account')) selected @endif>{{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>@lang('To Account')</label>
                                        <select name="to_account" class="form-control">
                                            <option value="">--@lang('Select')--</option>
                                            @foreach ($accounts as $key => $value)
                                                <option value="{{ $key }}" @if($key == old('to_account')) selected @endif>{{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <button type="submit" class="btn btn-info">@lang('Submit')</button>
                                    @if(request()->isFilterActive)
                                        <a href="{{ route('transfer.index') }}" class="btn btn-secondary">@lang('Clear')</a>
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
                            <th>@lang('From Account')</th>
                            <th>@lang('To Account')</th>
                            <th>@lang('Amount')</th>
                            @canany(['transfer-update', 'transfer-delete'])
                                <th data-orderable="false">@lang('Actions')</th>
                            @endcanany
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transfers as $transfer)
                        <tr>
                            <td>{{ date($company->date_format, strtotime($transfer->payment->paid_at)) }}</td>
                            <td>{{ $transfer->payment->account->name }}</td>
                            <td>{{ $transfer->revenue->account->name }}</td>
                            <td>@money($transfer->payment->amount, $transfer->payment->currency_code, true)</td>
                            @canany(['transfer-update', 'transfer-delete'])
                                <td>
                                    @can('transfer-update')
                                        <a href="{{ route('transfer.edit', $transfer) }}" class="btn btn-info btn-outline btn-circle btn-lg" data-toggle="tooltip" title="@lang('Edit')"><i class="fa fa-edit ambitious-padding-btn"></i></a>&nbsp;&nbsp;
                                    @endcan
                                    @can('transfer-delete')
                                        <a href="#" data-href="{{ route('transfer.destroy', $transfer) }}" class="btn btn-info btn-outline btn-circle btn-lg" data-toggle="modal" data-target="#myModal" title="@lang('Delete')"><i class="fa fa-trash ambitious-padding-btn"></i></a>
                                    @endcan
                                </td>
                            @endcanany
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $transfers->links() }}
            </div>
        </div>
    </div>
</div>
@include('layouts.delete_modal')
@include('script.transfer.index.js')
@endsection
