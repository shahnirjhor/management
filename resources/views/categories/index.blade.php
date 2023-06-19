@extends('layouts.layout')
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                @can('category-create')
                    <h3><a href="{{ route('category.create') }}" class="btn btn-outline btn-info">+ @lang('Add New')</a></h3>
                @endcan
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">@lang('Dashboard')</a></li>
                    <li class="breadcrumb-item active">@lang('Category List')</li>
                </ol>
            </div>
        </div>
    </div>
</section>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">@lang('Category List')</h3>
                <div class="card-tools">
                    @can('category-export')
                        <a class="btn btn-primary" target="_blank" href="{{ route('category.index') }}?export=1">
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
                                        <label>@lang('Type')</label>
                                        <select name="type" class="form-control">
                                            <option value="">--@lang('Select')--</option>
                                            <option value="expense" @if(request()->type == 'expense') selected @endif>@lang('Expense')</option>
                                            <option value="income" @if(request()->type == 'income') selected @endif>@lang('Income')</option>
                                            <option value="item" @if(request()->type == 'item') selected @endif>@lang('Item')</option>
                                            <option value="other" @if(request()->type == 'other') selected @endif>@lang('Other')</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>@lang('Status')</label>
                                        <select name="enabled" class="form-control">
                                            <option value="">--@lang('Select')--</option>
                                            <option value="1" @if(request()->enabled == '1') selected @endif>@lang('Enabled')</option>
                                            <option value="0" @if(request()->enabled == '0') selected @endif>@lang('Disabled')</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <button type="submit" class="btn btn-info">@lang('Submit')</button>
                                    @if(request()->isFilterActive)
                                        <a href="{{ route('category.index') }}" class="btn btn-secondary">@lang('Clear')</a>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <table class="table table-striped" id="laravel_datatable">
                    <thead>
                        <tr>
                            <th>@lang('ID')</th>
                            <th>@lang('Name')</th>
                            <th>@lang('Type')</th>
                            <th>@lang('Color')</th>
                            <th>@lang('Status')</th>
                            @canany(['category-update', 'category-delete'])
                                <th>@lang('Actions')</th>
                            @endcanany
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $category)
                            <tr>
                                <td>{{ $category->id }}</td>
                                <td>{{ $category->name }}</td>
                                <td>{{ $category->type }}</td>
                                <td>
                                    <span class="dot" style="background-color :{{ $category->color }}"></span>
                                </td>
                                <td>
                                    @if($category->enabled == '1')
                                        <span class="badge badge-pill badge-success">@lang('Enabled')</span>
                                    @else
                                        <span class="badge badge-pill badge-danger">@lang('Disabled')</span>
                                    @endif
                                </td>
                                @canany(['category-update', 'category-delete'])
                                    <td>
                                        @can('category-update')
                                            <a href="{{ route('category.edit', $category) }}" class="btn btn-info btn-outline btn-circle btn-lg" data-toggle="tooltip" title="Edit"><i class="fa fa-edit ambitious-padding-btn"></i></a>&nbsp;&nbsp;
                                        @endcan
                                        @can('category-delete')
                                            <a href="#" data-href="{{ route('category.destroy', $category) }}" class="btn btn-info btn-outline btn-circle btn-lg" data-toggle="modal" data-target="#myModal" title="Delete"><i class="fa fa-trash ambitious-padding-btn"></i></a>
                                        @endcan
                                    </td>
                                @endcanany
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $categories->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>
@include('layouts.delete_modal')
@include('script.currency.index.js')
@endsection
