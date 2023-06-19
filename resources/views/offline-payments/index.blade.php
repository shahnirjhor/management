@extends('layouts.layout')
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                @can('offline-payment-create')
                    <h3><a href="{{ route('offline-payment.create') }}" class="btn btn-outline btn-info">+ @lang('Add New Offline Payment')</a></h3>
                @endcan
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">@lang('Dashboard')</a></li>
                    <li class="breadcrumb-item active">@lang('Offline Payments List')</li>
                </ol>
            </div>
        </div>
    </div>
</section>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">@lang('Offline Payment Methods')</h3>
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
                                        <label>@lang('Name')</label>
                                        <input type="text" name="name" class="form-control" value="{{ request()->name }}" placeholder="@lang('Name')">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>@lang('Code')</label>
                                        <input type="text" name="code" class="form-control" value="{{ request()->code }}" placeholder="@lang('Code')">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <button type="submit" class="btn btn-info">@lang('Submit')</button>
                                    @if(request()->isFilterActive)
                                        <a href="{{ route('offline-payment.index') }}" class="btn btn-secondary">@lang('Clear')</a>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <table id="laravel_datatable" class="table table-striped compact table-width">
                    <thead>
                        <tr>
                            <th>@lang('Name')</th>
                            <th>@lang('Code')</th>
                            <th>@lang('Order')</th>
                            <th>@lang('Show to Customer')</th>
                            @canany(['offline-payment-update', 'offline-payment-delete'])
                                <th data-orderable="false">@lang('Actions')</th>
                            @endcanany
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($offlinePayments as $offlinePayment)
                            <tr>
                                <td>{{ $offlinePayment->name }}</td>
                                <td>{{ $offlinePayment->code }}</td>
                                <td>{{ $offlinePayment->order }}</td>
                                <td>
                                    @if($offlinePayment->show_to_customer == '1')
                                        <span class="badge badge-pill badge-success">@lang('Yes')</span>
                                    @else
                                        <span class="badge badge-pill badge-danger">@lang('No')</span>
                                    @endif
                                </td>
                                @canany(['offline-payment-update', 'offline-payment-delete'])
                                    <td>
                                        @can('offline-payment-update')
                                            <a href="{{ route('offline-payment.edit', $offlinePayment) }}" class="btn btn-info btn-outline btn-circle btn-lg" data-toggle="tooltip" title="@lang('Edit')"><i class="fa fa-edit ambitious-padding-btn"></i></a>&nbsp;&nbsp;
                                        @endcan
                                        @can('offline-payment-delete')
                                            <a href="#" data-href="{{ route('offline-payment.destroy', $offlinePayment) }}" class="btn btn-info btn-outline btn-circle btn-lg" data-toggle="modal" data-target="#myModal" title="@lang('Delete')"><i class="fa fa-trash ambitious-padding-btn"></i></a>
                                        @endcan
                                    </td>
                                @endcanany
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $offlinePayments->links() }}
            </div>
        </div>
    </div>
</div>
@include('layouts.delete_modal')
@include('script.offline-payments.index.js')
@endsection
