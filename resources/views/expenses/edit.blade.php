@extends('layouts.layout')
@section('one_page_js')
    <script src="{{ asset('js/quill.js') }}"></script>
    <script src="{{ asset('plugins/dropify/dist/js/dropify.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
@endsection
@section('one_page_css')
    <link href="{{ asset('css/quill.snow.css') }}" rel="stylesheet">
    <link href="{{ asset('plugins/dropify/dist/css/dropify.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endsection
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('expense.index') }}">@lang('Expense List')</a></li>
                    <li class="breadcrumb-item active">@lang('Edit Expense')</li>
                </ol>
            </div>
        </div>
    </div>
</section>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3>@lang('Edit Expense')</h3>
            </div>
            <div class="card-body">
                <form id="userQuickForm" class="form-material form-horizontal" action="{{ route('expense.update', $expense) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">
                                    @lang('Name') <b class="ambitious-crimson">*</b>
                                </label>
                                <input class="form-control ambitious-form-loading @error('name') is-invalid @enderror" name="name" value="{{ old('name', $expense->name) }}" id="name" type="text" placeholder="{{ __('Type Expense Name Here') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="year">
                                    @lang('Year') <b class="ambitious-crimson">*</b>
                                </label>
                                <select class="form-control select2" name="year" id="year" required="required">
                                    <option value="">Select Year</option>
                                    @foreach ($years as $key => $value)
                                        <option value="{{ $value }}" {{ old('year', $expense->year ) == $value ? 'selected' : '' }}>{{ $value }}</option>
                                    @endforeach
                                </select>
                                @error('year')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="school_or_college">@lang('Institution') <b class="ambitious-crimson">*</b></label>
                                <select class="form-control ambitious-form-loading @error('school_or_college') is-invalid @enderror" required="required" name="school_or_college" id="school_or_college">
                                    <option value="1" {{ old('school_or_college', $expense->school_or_college) == 1 ? 'selected' : '' }}>@lang('School')</option>
                                    <option value="2" {{ old('school_or_college', $expense->school_or_college) == 2 ? 'selected' : '' }}>@lang('College')</option>
                                </select>
                                @error('school_or_college')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div id="school_block" class="col-md-6">
                            <div class="form-group">
                                <label for="scholarship_school_id">
                                    @lang('School') <b class="ambitious-crimson">*</b>
                                </label>
                                <select class="form-control select2" name="scholarship_school_id" id="scholarship_school_id">
                                    <option value="">Select School</option>
                                    @foreach ($schools as $key => $value)
                                        <option value="{{ $key }}" {{ old('scholarship_school_id',$expense->scholarship_school_id) == $key ? 'selected' : '' }}>{{ $value }}</option>
                                    @endforeach
                                </select>
                                @error('scholarship_school_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div id="college_block" class="col-md-6">
                            <div class="form-group">
                                <label for="scholarship_college_id">
                                    @lang('College') <b class="ambitious-crimson">*</b>
                                </label>
                                <select class="form-control select2" name="scholarship_college_id" id="scholarship_college_id">
                                    <option value="">Select College</option>
                                    @foreach ($colleges as $key => $value)
                                        <option value="{{ $key }}" {{ old('scholarship_college_id',$expense->scholarship_college_id) == $key ? 'selected' : '' }}>{{ $value }}</option>
                                    @endforeach
                                </select>
                                @error('scholarship_college_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="scholarship_village_id">
                                    @lang('Provider Organization') <b class="ambitious-crimson">*</b>
                                </label>
                                <input class="form-control ambitious-form-loading @error('scholarship_village_id') is-invalid @enderror" name="scholarship_village_id" value="{{ old('scholarship_village_id',$expense->scholarship_village_id) }}" id="scholarship_village_id" type="text" placeholder="{{ __('Type Provider Organization Name Here') }}" required>
                                @error('scholarship_village_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">
                                    @lang('Amount') <b class="ambitious-crimson">*</b>
                                </label>
                                <input class="form-control ambitious-form-loading @error('amount') is-invalid @enderror" name="amount" value="{{ old('amount', $expense->amount) }}" id="amount" type="number" placeholder="{{ __('Type Amount Here') }}" required>
                                @error('amount')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 col-form-label"></label>
                        <div class="col-md-8">
                            <input id="from_submit" type="submit" value="@lang('Update')" class="btn btn-outline btn-info btn-lg"/>
                            <a href="{{ route('expense.index') }}" class="btn btn-outline btn-warning btn-lg">@lang('Cancel')</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@include('script.expenses.create.js')
@endsection
