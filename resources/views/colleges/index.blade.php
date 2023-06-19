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
                @can('college-create')
                    <h3><a href="{{ route('scholarship-college.create') }}" class="btn btn-outline btn-info">+ {{ __('Add New College') }}</a></h3>
                @endcan
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">@lang('Dashboard')</a></li>
                    <li class="breadcrumb-item active">{{ __('College List') }}</li>
                </ol>
            </div>
        </div>
    </div>
</section>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">@lang('College') </h3>
                <div class="card-tools">
                    @can('college-export')
                        <a class="btn btn-primary" target="_blank" href="{{ route('scholarship-college.index') }}?export=1">
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
                                        <select name="college_type" class="form-control">
                                            <option value="">--@lang('Select')--</option>
                                            <option value="Govt." {{ old('college_type', request()->college_type) === 'Govt.' ? 'selected' : ''  }}>@lang('Govt.')</option>
                                            <option value="Govt. Aided" {{ old('college_type', request()->college_type) === 'Govt. Aided' ? 'selected' : ''  }}>@lang('Govt. Aided')</option>
                                            <option value="Private" {{ old('college_type', request()->college_type) === 'Private' ? 'selected' : ''  }}>@lang('Private')</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <button type="submit" class="btn btn-info">Submit</button>
                                    @if(request()->isFilterActive)
                                        <a href="{{ route('scholarship-college.index') }}" class="btn btn-secondary">Clear</a>
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
                            @canany(['college-read','college-update', 'college-delete'])
                                <th data-orderable="false">@lang('Actions')</th>
                            @endcanany
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($colleges as $college)
                        <tr>
                            @if($college->picture == NULL)
                            <td><img class="profile-user-img img-fluid img-circle" src="{{ asset('img/no_image.png') }}" alt="" /></td>
                            @else
                            <td><img class="profile-user-img img-fluid img-circle" src="{{ asset($college->picture) }}" alt="" /></td>
                            @endif


                            <td>{{ $college->name }}</td>
                            <td>{{ $college->college_type }}</td>
                            <td>{{ $college->email }}</td>
                            <td>
                                @if($college->status == '1')
                                    <span class="badge badge-pill badge-success">@lang('Active')</span>
                                @else
                                    <span class="badge badge-pill badge-danger">@lang('Inactive')</span>
                                @endif
                            </td>
                            @canany(['college-read','college-update', 'college-delete'])
                                <td>
                                    @can('college-read')
                                        <a href="{{ route('scholarship-college.show', $college) }}" class="btn btn-info btn-outline btn-circle btn-lg" data-toggle="tooltip" title="show"><i class="fa fa-eye ambitious-padding-btn"></i></a>&nbsp;&nbsp;
                                    @endcan
                                    @can('college-update')
                                        <a href="{{ route('scholarship-college.edit', $college) }}" class="btn btn-info btn-outline btn-circle btn-lg" data-toggle="tooltip" title="Edit"><i class="fa fa-edit ambitious-padding-btn"></i></a>&nbsp;&nbsp;
                                    @endcan
                                    @can('college-delete')
                                        <a href="#" data-href="{{ route('scholarship-college.destroy', $college) }}" class="btn btn-info btn-outline btn-circle btn-lg" data-toggle="modal" data-target="#myModal" title="Delete"><i class="fa fa-trash ambitious-padding-btn"></i></a>
                                    @endcan
                                </td>
                            @endcanany
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $colleges->links() }}
            </div>
        </div>
    </div>
</div>
@include('layouts.delete_modal')
@include('script.school.index.js')
@endsection
