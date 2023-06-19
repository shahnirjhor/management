@extends('layouts.layout')
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                @can('vendor-create')
                    <h3>
                        <a href="{{ route('vendor.create') }}" class="btn btn-outline btn-info">+ @lang('Add Vendor')</a>
                    </h3>
                @endcan
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">@lang('Dashboard')</a></li>
                    <li class="breadcrumb-item active">@lang('Vendor List')</li>
                </ol>
            </div>
        </div>
    </div>
</section>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">@lang('Vendor List')</h3>
                <div class="card-tools">
                    @can('vendor-export')
                        <a class="btn btn-primary" target="_blank" href="{{ route('vendor.index') }}?export=1">
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
                                        <label>@lang('Name')</label>
                                        <input type="text" name="name" class="form-control" value="{{ request()->name }}" placeholder="@lang('Name')">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>@lang('Email')</label>
                                        <input type="text" name="email" class="form-control" value="{{ request()->email }}" placeholder="@lang('Email')">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>@lang('Phone')</label>
                                        <input type="text" name="phone" class="form-control" value="{{ request()->phone }}" placeholder="@lang('Phone')">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <button type="submit" class="btn btn-info">@lang('Submit')</button>
                                    @if(request()->isFilterActive)
                                        <a href="{{ route('vendor.index') }}" class="btn btn-secondary">@lang('Clear')</a>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <table class="table table-striped" id="laravel_datatable">
                    <thead>
                        <tr>
                            <th>@lang('Name')</th>
                            <th>@lang('Email')</th>
                            <th>@lang('Phone')</th>
                            <th>@lang('Unpaid')</th>
                            <th>@lang('Status')</th>
                            @canany(['vendor-update', 'vendor-delete'])
                                <th data-orderable="false">@lang('Actions')</th>
                            @endcanany
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($vendors as $vendor)
                            <tr>
                                <td>{{ $vendor->name }}</td>
                                <td>{{ $vendor->email }}</td>
                                <td>{{ $vendor->phone }}</td>
                                <td>@money($vendor->unpaid, $company->default_currency, true)</td>
                                <td>
                                    @if($vendor->enabled)
                                        <span class="badge badge-success">@lang('Active')</span>
                                    @else
                                        <span class="badge badge-danger">@lang('Inactive')</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('vendor.show', $vendor->id) }}" class="btn btn-info btn-outline btn-circle btn-lg" data-toggle="tooltip" title="@lang('Show')"><i class="fa fa-eye ambitious-padding-btn"></i></a>&nbsp;&nbsp;
                                    @can('vendor-update')
                                        <a href="{{ route('vendor.edit', $vendor->id) }}" class="btn btn-info btn-outline btn-circle btn-lg" data-toggle="tooltip" title="@lang('Edit')"><i class="fa fa-edit ambitious-padding-btn"></i></a>&nbsp;&nbsp;
                                    @endcan
                                    @can('vendor-update')
                                        <a href="#" data-href="{{ route('vendor.destroy', $vendor->id) }}" class="btn btn-info btn-outline btn-circle btn-lg" data-toggle="modal" data-target="#myModal" title="@lang('Delete')"><i class="fa fa-trash ambitious-padding-btn"></i></a>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $vendors->links() }}
            </div>
        </div>
    </div>
</div>
@include('layouts.delete_modal')
@include('script.vendors.index.js')
@endsection
