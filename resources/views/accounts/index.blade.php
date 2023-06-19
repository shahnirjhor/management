@extends('layouts.layout')
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                @can('account-create')
                    <h3><a href="{{ route('account.create') }}" class="btn btn-outline btn-info">+ @lang('Add New Account')</a></h3>
                @endcan
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">@lang('Dashboard')</a></li>
                    <li class="breadcrumb-item active">@lang('Account List')</li>
                </ol>
            </div>
        </div>
    </div>
</section>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">@lang('Account List')</h3>
                <div class="card-tools">
                    @can('account-export')
                        <a class="btn btn-primary" target="_blank" href="{{ route('account.index') }}?export=1">
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
                                        <label>@lang('Number')</label>
                                        <input type="text" name="number" class="form-control" value="{{ request()->number }}" placeholder="@lang('Number')">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>@lang('Currency')</label>
                                        <select name="currency_code" class="form-control">
                                            <option value="">--@lang('Select')--</option>
                                            @foreach ($currencies as $key => $value)
                                                <option value="{{ $key }}" @if($key == old('currency_code')) selected @endif>{{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <button type="submit" class="btn btn-info">@lang('Submit')</button>
                                    @if(request()->isFilterActive)
                                        <a href="{{ route('account.index') }}" class="btn btn-secondary">@lang('Clear')</a>
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
                            <th>@lang('Number')</th>
                            <th>@lang('Current Balance')</th>
                            <th>@lang('Status')</th>
                            @canany(['account-update', 'account-delete'])
                                <th data-orderable="false" data-searchable="false">@lang('Actions')</th>
                            @endcanany
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($accounts as $account)
                        <tr>
                            <td>{{ $account->name }}</td>
                            <td>{{ $account->number }}</td>
                            <td>@money($account->balance, $account->currency_code, true)</td>
                            <td>
                                @if($account->enabled == '1')
                                    <span class="badge badge-pill badge-success">@lang('Enabled')</span>
                                @else
                                    <span class="badge badge-pill badge-danger">@lang('Disabled')</span>
                                @endif
                            </td>
                            @canany(['account-update', 'account-delete'])
                                <td>
                                    @can('account-update')
                                        <a href="{{ route('account.edit', $account) }}" class="btn btn-info btn-outline btn-circle btn-lg" data-toggle="tooltip" title="@lang('Edit')"><i class="fa fa-edit ambitious-padding-btn"></i></a>&nbsp;&nbsp;
                                    @endcan
                                    @can('account-delete')
                                        <a href="#" data-href="{{ route('account.destroy', $account) }}" class="btn btn-info btn-outline btn-circle btn-lg" data-toggle="modal" data-target="#myModal" title="@lang('Delete')"><i class="fa fa-trash ambitious-padding-btn"></i></a>
                                    @endcan
                                </td>
                            @endcanany
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $accounts->links() }}
            </div>
        </div>
    </div>
</div>
@include('layouts.delete_modal')
@include('script.account.index.js')
@endsection
