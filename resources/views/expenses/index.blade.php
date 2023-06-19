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
                @can('expense-create')
                    <h3><a href="{{ route('expense.create') }}" class="btn btn-outline btn-info">+ {{ __('Add New Expense') }}</a></h3>
                @endcan
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">@lang('Dashboard')</a></li>
                    <li class="breadcrumb-item active">{{ __('Expense List') }}</li>
                </ol>
            </div>
        </div>
    </div>
</section>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">@lang('Expenses') </h3>
                <div class="card-tools">
                    @can('expense-export')
                        <a class="btn btn-primary" target="_blank" href="{{ route('expense.index') }}?export=1">
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
                                        <label>@lang('Name')</label>
                                        <input type="text" name="name" class="form-control" value="{{ request()->name }}" placeholder="@lang('Expense Name')">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>@lang('Provider Organization')</label>
                                        <input type="text" name="scholarship_village_id" class="form-control" value="{{ request()->scholarship_village_id }}" placeholder="@lang('Provider Organization Name')">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>@lang('Institution')</label>
                                        <select class="form-control ambitious-form-loading" name="school_or_college" id="school_or_college">
                                            <option value="1" {{ old('school_or_college', request()->school_or_college) == '1' ? 'selected' : ''  }}>@lang('School')</option>
                                            <option value="2" {{ old('school_or_college', request()->school_or_college) == '2' ? 'selected' : ''  }}>@lang('College')</option>
                                        </select>
                                    </div>
                                </div>
                                <div id="school_block" class="col-md-3">
                                    <div class="form-group">
                                        <label>@lang('School')</label>
                                        <select class="form-control select2" name="scholarship_school_id" id="scholarship_school_id">
                                            <option value="">Select School</option>
                                            @foreach ($schools as $key => $value)
                                                <option value="{{ $key }}" {{ old('scholarship_school_id', request()->scholarship_school_id) == $key ? 'selected' : '' }}>{{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div id="college_block" class="col-md-3">
                                    <div class="form-group">
                                        <label>@lang('College')</label>
                                        <select class="form-control select2" name="scholarship_college_id" id="scholarship_college_id">
                                            <option value="">Select College</option>
                                            @foreach ($colleges as $key => $value)
                                                <option value="{{ $key }}" {{ old('scholarship_college_id', request()->scholarship_college_id) == $key ? 'selected' : '' }}>{{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <button type="submit" class="btn btn-info">Submit</button>
                                    @if(request()->isFilterActive)
                                        <a href="{{ route('expense.index') }}" class="btn btn-secondary">Clear</a>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="laravel_datatable" class="table table-striped compact table-width">
                        <thead>
                            <tr>
                                <th>@lang('Name')</th>
                                <th>@lang('Amount')</th>
                                <th>@lang('Year')</th>
                                <th>@lang('Provider Organization')</th>
                                <th>@lang('Institution Name')</th>
                                <th>@lang('Institution Type')</th>
                                @canany(['expense-update', 'expense-delete'])
                                    <th data-orderable="false">@lang('Actions')</th>
                                @endcanany
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($expenses as $expense)
                            <tr>
                                <td>{{ $expense->name }}</td>
                                <td>{{ $expense->amount }}</td>
                                <td>{{ $expense->year }}</td>
                                <td>{{ $expense->scholarship_village_id }}</td>
                                @if( $expense->school_or_college == "1")
                                    <td> {{ $expense->schoolDetail->name }}</td>
                                @else
                                    <td> {{ $expense->collegeDetail->name }}</td>
                                @endif
                                <td>
                                    @if($expense->school_or_college == '1')
                                        <span class="badge badge-pill badge-info">@lang('School')</span>
                                    @else
                                        <span class="badge badge-pill badge-warning">@lang('College')</span>
                                    @endif
                                </td>
                                @canany(['expense-update', 'expense-delete'])
                                <td>
                                    @can('expense-update')
                                        <a href="{{ route('expense.edit', $expense) }}" class="btn btn-info btn-outline btn-circle btn-lg" data-toggle="tooltip" title="Edit"><i class="fa fa-edit ambitious-padding-btn"></i></a>&nbsp;&nbsp;
                                    @endcan
                                    @can('expense-delete')
                                        <a href="#" data-href="{{ route('expense.destroy', $expense) }}" class="btn btn-info btn-outline btn-circle btn-lg" data-toggle="modal" data-target="#myModal" title="Delete"><i class="fa fa-trash ambitious-padding-btn"></i></a>
                                    @endcan
                                </td>
                            @endcanany
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $expenses->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    "use strict";
    $(document).ready(function() {
        var school_or_college = $('#school_or_college').val();
        if(school_or_college == '1') {
            $('#school_block').show();
            $('#college_block').hide();
        } else {
            $('#school_block').hide();
            $('#college_block').show();
        }

        $('#school_or_college').change(function(){
            if($('#school_or_college').val() == '1') {
                $('#school_block').show();
                $('#college_block').hide();
            } else {
                $('#school_block').hide();
                $('#college_block').show();
            }
        });

        $(".select2").select2();

    });
</script>
@include('layouts.delete_modal')
@include('script.items.index.js')
@endsection
