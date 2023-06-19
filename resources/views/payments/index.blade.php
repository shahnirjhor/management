@extends('layouts.layout')
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                @can('payment-create')
                    <h3>
                        <a href="{{ route('payment.create') }}" class="btn btn-outline btn-info">+ {{ __('Add Payment') }}</a>
                    </h3>
                @endcan

            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">@lang('Dashboard')</a></li>
                    <li class="breadcrumb-item active">{{ __('Payment List') }}</li>
                </ol>
            </div>
        </div>
    </div>
</section>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ __('Payment List') }}</h3>
                <div class="card-tools">
                    @can('payment-export')
                        <a class="btn btn-primary" target="_blank" href="{{ route('payment.index') }}?export=1">
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
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>@lang('Date')</label>
                                        <input type="text" name="paid_at" id="paid_at" class="form-control flatpickr" placeholder="@lang('Date')" value="{{ old('paid_at', request()->paid_at) }}">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>@lang('Vendor')</label>
                                        <select name="vendor_id" class="form-control">
                                            <option value="">--@lang('Select')--</option>
                                            @foreach ($vendors as $key => $value)
                                                <option value="{{ $key }}" @if($key == old('vendor_id')) selected @endif>{{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>@lang('Category')</label>
                                        <select name="category_id" class="form-control">
                                            <option value="">--@lang('Select')--</option>
                                            @foreach ($categories as $key => $value)
                                                <option value="{{ $key }}" @if($key == old('category_id')) selected @endif>{{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>@lang('Account')</label>
                                        <select name="account_id" class="form-control">
                                            <option value="">--@lang('Select')--</option>
                                            @foreach ($accounts as $key => $value)
                                                <option value="{{ $key }}" @if($key == old('account_id')) selected @endif>{{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <button type="submit" class="btn btn-info">@lang('Submit')</button>
                                    @if(request()->isFilterActive)
                                        <a href="{{ route('payment.index') }}" class="btn btn-secondary">@lang('Clear')</a>
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
                            <th>@lang('Amount')</th>
                            <th>@lang('Vendor')</th>
                            <th>@lang('Category ')</th>
                            <th>@lang('Account ')</th>
                            @canany(['payment-update', 'payment-delete'])
                                <th data-orderable="false" data-searchable="false">@lang('Actions')</th>
                            @endcanany
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($payments as $payment)
                        <tr>
                            <td>{{ date($company->date_format, strtotime($payment->paid_at)) }}</td>
                            <td>@money($payment->amount, $payment->currency_code, true)</td>
                            <td>{{ $payment->vendor->name }}</td>
                            <td>{{ $payment->category->name}}</td>
                            <td>{{ $payment->account->name}}</td>
                            @canany(['payment-update', 'payment-delete'])
                                <td>
                                    @can('payment-update')
                                        <a href="{{ route('payment.edit', $payment) }}" class="btn btn-info btn-outline btn-circle btn-lg" data-toggle="tooltip" title="@lang('Edit')"><i class="fa fa-edit ambitious-padding-btn"></i></a>&nbsp;&nbsp;
                                    @endcan
                                    @can('payment-delete')
                                        <a href="#" data-href="{{ route('payment.destroy', $payment) }}" class="btn btn-info btn-outline btn-circle btn-lg" data-toggle="modal" data-target="#myModal" title="@lang('Delete')"><i class="fa fa-trash ambitious-padding-btn"></i></a>
                                    @endcan
                                </td>
                            @endcanany
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $payments->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>
<div class="container">
    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">{{ $ApplicationSetting->item_name }}</h4>
                </div>
                <div class="modal-body text-center">
                    <p class="my-0 font-weight-bold">@lang('Are You Sure You Want To Delete This Data') ???</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">@lang('Close')</button>
                    <form class="btn-ok" action="" method="post">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">@lang('Delete')</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@include('script.account.index.js')
@endsection
