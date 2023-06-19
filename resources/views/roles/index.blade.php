@extends('layouts.layout')
@section('one_page_css')
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.4/css/buttons.dataTables.min.css">
@endsection
@section('one_page_js')
<script src="https://cdn.datatables.net/buttons/2.3.4/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.4/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.4/js/buttons.print.min.js"></script>
@endsection
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    @can('role-create')
                        <h3><a href="{{ route('roles.create') }}" class="btn btn-outline btn-info">+ {{ __('Add Role') }}</a>
                            <span class="pull-right"></span>
                        </h3>
                    @endcan
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">@lang('Dashboard')</a></li>
                        <li class="breadcrumb-item active">{{ __('Role List') }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('Role List') }}</h3>
                    <div class="card-tools">
                        @can('role-export')
                            <a class="btn btn-primary" target="_blank" href="{{ route('roles.index') }}?export=1">
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
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>@lang('Role Name')</label>
                                            <input type="text" name="name" class="form-control" value="{{ request()->name }}" placeholder="@lang('Role Name')">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>@lang('Role For')</label>
                                            <select class="form-control" name="role_for">
                                                <option value="">--@lang('Select')--</option>
                                                <option value="0" {{ old('role_for', request()->role_for) === '0' ? 'selected' : ''  }}>@lang('System User')</option>
                                                <option value="1" {{ old('role_for', request()->role_for) === '1' ? 'selected' : ''  }}>@lang('General User')</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <button type="submit" class="btn btn-info">@lang('Submit')</button>
                                        @if(request()->isFilterActive)
                                            <a href="{{ route('roles.index') }}" class="btn btn-secondary">@lang('Clear')</a>
                                        @endif
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <table class="table table-striped" id="laravel_datatable">
                        <thead>
                            <tr>
                                <th>@lang('Id')</th>
                                <th>@lang('Name')</th>
                                {{--  <th>{{ __('Price') }}</th>
                                <th>{{ __('Validity') }}</th>  --}}
                                <th>{{ __('Role For') }}</th>
                                <th>@lang('Default')</th>
                                @canany(['role-update', 'role-delete'])
                                    <th data-orderable="false">@lang('Actions')</th>
                                @endcanany
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($roles as $role)
                                <tr>
                                    <td>{{ $role->id }}</td>
                                    <td>{{ $role->name }}</td>
                                    {{--  <td>{{ $role->price }}</td>
                                    <td>{{ $role->validity }}</td>  --}}
                                    <td>
                                        @if($role->role_for == '1')
                                            <span class="badge badge-pill badge-success">@lang('General User')</span>
                                        @else
                                            <span class="badge badge-pill badge-primary">@lang('System User')</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($role->is_default == '1')
                                            <span class="badge badge-pill badge-info">@lang('Yes')</span>
                                        @else
                                            <span class="badge badge-pill badge-danger">@lang('No')</span>
                                        @endif
                                    </td>
                                    @canany(['role-update', 'role-delete'])
                                        <td>
                                            @can('role-update')
                                                <a href="{{ route('roles.edit', $role) }}" class="btn btn-info btn-outline btn-circle btn-lg" data-toggle="tooltip" title="Edit"><i class="fa fa-edit ambitious-padding-btn"></i></a>&nbsp;&nbsp;
                                            @endcan
                                            @can('role-delete')
                                                <a href="#" data-href="{{ route('roles.destroy', $role) }}" class="btn btn-info btn-outline btn-circle btn-lg" data-toggle="modal" data-target="#myModal" title="Delete"><i class="fa fa-trash ambitious-padding-btn"></i></a>
                                            @endcan
                                        </td>
                                    @endcanany
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $roles->links() }}
                </div>
            </div>
        </div>
    </div>
@include('layouts.delete_modal')
@include('script.roles.index.js')
@endsection

