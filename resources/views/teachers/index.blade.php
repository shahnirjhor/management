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
                @can('teacher-create')
                    <h3><a href="{{ route('scholarship-teacher.create') }}" class="btn btn-outline btn-info">+ {{ __('Add New Teacher') }}</a></h3>
                @endcan
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">@lang('Dashboard')</a></li>
                    <li class="breadcrumb-item active">{{ __('Teacher List') }}</li>
                </ol>
            </div>
        </div>
    </div>
</section>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">@lang('Teachers') </h3>
                <div class="card-tools">
                    @can('teacher-export')
                        <a class="btn btn-primary" target="_blank" href="{{ route('scholarship-teacher.index') }}?export=1">
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
                                        <select name="school_or_college" class="form-control">
                                            <option value="">--@lang('Select')--</option>
                                            <option value="1" {{ old('school_or_college', request()->school_or_college) == '1' ? 'selected' : ''  }}>@lang('School')</option>
                                            <option value="2" {{ old('school_or_college', request()->school_or_college) == '2' ? 'selected' : ''  }}>@lang('College')</option>
                                        </select>
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
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <button type="submit" class="btn btn-info">Submit</button>
                                    @if(request()->isFilterActive)
                                        <a href="{{ route('scholarship-teacher.index') }}" class="btn btn-secondary">Clear</a>
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
                            <th>@lang('Email')</th>
                            <th>@lang('Institution')</th>
                            <th>@lang('Status')</th>
                            @canany(['teacher-update', 'teacher-delete'])
                                <th data-orderable="false">@lang('Actions')</th>
                            @endcanany
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($teachers as $teacher)
                        <tr>
                            @if($teacher->photo == NULL)
                            <td><img class="profile-user-img img-fluid img-circle" src="{{ asset('img/no_image.png') }}" alt="" /></td>
                            @else
                            <td><img class="profile-user-img img-fluid img-circle" src="{{ asset($teacher->photo) }}" alt="" /></td>
                            @endif
                            <td>{{ $teacher->name }}</td>
                            <td>{{ $teacher->email }}</td>
                            <td>
                                @if($teacher->school_or_college == '1')
                                    <span class="badge badge-pill badge-info">@lang('School')</span>
                                @else
                                    <span class="badge badge-pill badge-warning">@lang('College')</span>
                                @endif
                            </td>
                            <td>
                                @if($teacher->status == '1')
                                    <span class="badge badge-pill badge-success">@lang('Active')</span>
                                @else
                                    <span class="badge badge-pill badge-danger">@lang('Inactive')</span>
                                @endif
                            </td>
                            @canany(['teacher-read','teacher-update', 'teacher-delete'])
                                <td>
                                    @can('teacher-read')
                                        <a href="{{ route('scholarship-teacher.show', $teacher) }}" class="btn btn-info btn-outline btn-circle btn-lg" data-toggle="tooltip" title="show"><i class="fa fa-eye ambitious-padding-btn"></i></a>&nbsp;&nbsp;
                                    @endcan
                                    @can('teacher-update')
                                        <a href="{{ route('scholarship-teacher.edit', $teacher) }}" class="btn btn-info btn-outline btn-circle btn-lg" data-toggle="tooltip" title="Edit"><i class="fa fa-edit ambitious-padding-btn"></i></a>&nbsp;&nbsp;
                                    @endcan
                                    @can('teacher-delete')
                                        <a href="#" data-href="{{ route('scholarship-teacher.destroy', $teacher) }}" class="btn btn-info btn-outline btn-circle btn-lg" data-toggle="modal" data-target="#myModal" title="Delete"><i class="fa fa-trash ambitious-padding-btn"></i></a>
                                    @endcan
                                </td>
                            @endcanany
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $teachers->links() }}
            </div>
        </div>
    </div>
</div>
@include('layouts.delete_modal')
@include('script.school.index.js')
@endsection
