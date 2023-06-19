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
                @can('school-create')
                    <h3><a href="{{ route('scholarship-school.create') }}" class="btn btn-outline btn-info">+ {{ __('Add New School') }}</a></h3>
                @endcan
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">@lang('Dashboard')</a></li>
                    <li class="breadcrumb-item active">{{ __('School List') }}</li>
                </ol>
            </div>
        </div>
    </div>
</section>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">@lang('Schools') </h3>
                <div class="card-tools">
                    @can('school-export')
                        <a class="btn btn-primary" target="_blank" href="{{ route('scholarship-school.index') }}?export=1">
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
                                        <label>@lang('Status')</label>
                                        <select name="status" class="form-control">
                                            <option value="">--@lang('Select')--</option>
                                            <option value="1" {{ old('status', request()->status) === '1' ? 'selected' : ''  }}>@lang('Active')</option>
                                            <option value="0" {{ old('status', request()->status) === '0' ? 'selected' : ''  }}>@lang('Inactive')</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>@lang('Type')</label>
                                        <select name="school_type" class="form-control">
                                            <option value="">--@lang('Select')--</option>
                                            <option value="Govt." {{ old('school_type', request()->school_type) === 'Govt.' ? 'selected' : ''  }}>@lang('Govt.')</option>
                                            <option value="Govt. Aided" {{ old('school_type', request()->school_type) === 'Govt. Aided' ? 'selected' : ''  }}>@lang('Govt. Aided')</option>
                                            <option value="Private" {{ old('school_type', request()->school_type) === 'Private' ? 'selected' : ''  }}>@lang('Private')</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <button type="submit" class="btn btn-info">Submit</button>
                                    @if(request()->isFilterActive)
                                        <a href="{{ route('scholarship-school.index') }}" class="btn btn-secondary">Clear</a>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <table id="laravel_datatable" class="table table-striped compact table-width">
                    <thead>
                        <tr>
                            <th>@lang('Picture')</th>
                            <th>@lang('Name')</th>
                            <th>@lang('Type')</th>
                            <th>@lang('Email')</th>
                            <th>@lang('Status')</th>
                            @canany(['school-read','school-update', 'school-delete'])
                                <th data-orderable="false">@lang('Actions')</th>
                            @endcanany
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($schools as $school)
                        <tr>
                            @if($school->picture == NULL)
                            <td><img class="profile-user-img img-fluid img-circle" src="{{ asset('img/no_image.png') }}" alt="" /></td>
                            @else
                            <td><img class="profile-user-img img-fluid img-circle" src="{{ asset($school->picture) }}" alt="" /></td>
                            @endif
                            <td>{{ $school->name }}</td>
                            <td>{{ $school->school_type }}</td>
                            <td>{{ $school->email }}</td>
                            <td>
                                @if($school->status == '1')
                                    <span class="badge badge-pill badge-success">@lang('Active')</span>
                                @else
                                    <span class="badge badge-pill badge-danger">@lang('Inactive')</span>
                                @endif
                            </td>
                            @canany(['school-read','school-update', 'school-delete'])
                                <td>
                                    @can('school-read')
                                        <a href="{{ route('scholarship-school.show', $school) }}" class="btn btn-info btn-outline btn-circle btn-lg" data-toggle="tooltip" title="show"><i class="fa fa-eye ambitious-padding-btn"></i></a>&nbsp;&nbsp;
                                    @endcan
                                    @can('school-update')
                                        <a href="{{ route('scholarship-school.edit', $school) }}" class="btn btn-info btn-outline btn-circle btn-lg" data-toggle="tooltip" title="Edit"><i class="fa fa-edit ambitious-padding-btn"></i></a>&nbsp;&nbsp;
                                    @endcan
                                    @can('school-delete')
                                        <a href="#" data-href="{{ route('scholarship-school.destroy', $school) }}" class="btn btn-info btn-outline btn-circle btn-lg" data-toggle="modal" data-target="#myModal" title="Delete"><i class="fa fa-trash ambitious-padding-btn"></i></a>
                                    @endcan
                                </td>
                            @endcanany
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $schools->links() }}
            </div>
        </div>
    </div>
</div>
@include('layouts.delete_modal')
@include('script.school.index.js')
@endsection
