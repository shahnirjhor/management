@extends('layouts.layout')
@section('one_page_js')
    <script src="{{ asset('plugins/dropify/dist/js/dropify.min.js') }}"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('plugins/steps/js/jquery.steps.min.js') }}"></script>
    <script src="{{ asset('plugins/steps/js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('plugins/validation/additional-methods.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.1/js/standalone/selectize.min.js"></script>
@endsection

@section('one_page_css')
    <link href="{{ asset('plugins/dropify/dist/css/dropify.min.css') }}" rel="stylesheet">
    <link href="{{ asset('plugins/steps/css/jquery.steps.css') }}" rel="stylesheet">
    <link href="{{ asset('plugins/steps/css/steps.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" integrity="sha512-nMNlpuaDPrqlEls3IX/Q56H36qvBASwb3ipuo3MxeWbsQB1881ox0cRv7UPTgBlriqoynt35KjEwgGUeUXIPnw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endsection
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('scholarship.index') }}">@lang('Scholarship List')</a></li>
                    <li class="breadcrumb-item active">@lang('Apply Scholarship')</li>
                </ol>
            </div>
        </div>
    </div>
</section>
<link href="{{ asset('css/scholarship.css') }}" rel="stylesheet">
<div class="row">
    <div class="col-12">
        <div class="material-card card">
            <div class="card-header">
                <h3>@lang('Apply Scholarship')</h3>
            </div>
            <div class="card-body wizard-content">
                <form enctype="multipart/form-data" action="#" id="scholarship_create_form" class="validation-wizard wizard-circle mt-5" method="post">
                    <h6>{{ __('Basic') }}</h6>
                    <section>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="application_no">
                                        {{ __('Application No') }} <b class="ambitious-crimson">*</b>
                                    </label>
                                    <input id="application_no" class="form-control @if($errors->has('application_no')) is-invalid @endif" name="application_no" type="text" value="{{ old('application_no', $number) }}" required readonly>
                                    @error('application_no')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="year">
                                        {{ __('Year') }} <b class="ambitious-crimson">*</b>
                                    </label>
                                    <select class="form-control" name="year" id="year" required>
                                        <option value="">Select Year</option>
                                        @foreach ($years as $key => $value)
                                            <option value="{{ $value }}" {{ old('year') == $value ? 'selected' : '' }}>{{ $value }}</option>
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
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="annual_income">
                                        {{ __('Annual Income') }} <b class="ambitious-crimson">*</b>
                                    </label>
                                    <input id="annual_income" class="form-control @if($errors->has('annual_income')) is-invalid @endif" name="annual_income" type="number" value="{{ old('annual_income') }}" placeholder="Annual Income of Your Guardian" required>
                                    @if ($errors->has('annual_income'))
                                        {{ Session::flash('error',$errors->first('annual_income')) }}
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="percentage_marks_obtained">
                                        {{ __('Percentage of Last Examination Marks') }} <b class="ambitious-crimson">*</b>
                                    </label>
                                    <input id="percentage_marks_obtained" class="form-control @if($errors->has('percentage_marks_obtained')) is-invalid @endif" name="percentage_marks_obtained" type="number" value="{{ old('percentage_marks_obtained') }}" placeholder="Percentage Marks In Your Last Examination" required>
                                    @if ($errors->has('percentage_marks_obtained'))
                                        {{ Session::flash('error',$errors->first('percentage_marks_obtained')) }}
                                    @endif
                                </div>
                            </div>
                        </div>
                    </section>
                    <h6>{{ __('Personal Details') }}</h6>
                    <section>
                        <div class="row mb-2">
                            @if($myRole == "Student")
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="full_name">
                                            {{ __('Full Name') }} <b class="ambitious-crimson">*</b>
                                        </label>
                                        <input id="full_name" class="form-control @if($errors->has('full_name')) is-invalid @endif" name="full_name" type="text" value="{{ old('full_name') }}" placeholder="Type Your Full Name" required>
                                        @if ($errors->has('full_name'))
                                            {{ Session::flash('error',$errors->first('full_name')) }}
                                        @endif
                                    </div>
                                </div>
                            @else
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="full_name">
                                            {{ __('Student') }} <b class="ambitious-crimson">*</b>
                                        </label>

                                        <select style="width: 100%" class="form-control select2" name="full_name" id="full_name" required>
                                            <option value="">Select Student</option>
                                            @foreach ($students as $key => $value)
                                                <option value="{{ $key }}" {{ old('full_name') == $key ? 'selected' : '' }}>{{ $value }}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('full_name'))
                                            {{ Session::flash('error',$errors->first('full_name')) }}
                                        @endif
                                    </div>
                                </div>
                            @endif

                        </div>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="father_name">
                                        {{ __('Father Name') }} <b class="ambitious-crimson">*</b>
                                    </label>
                                    <input id="father_name" class="form-control @if($errors->has('father_name')) is-invalid @endif" name="father_name" type="text" value="{{ old('father_name') }}" placeholder="Type Your Father Name" required>
                                    @if ($errors->has('father_name'))
                                        {{ Session::flash('error',$errors->first('father_name')) }}
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="father_occupation">
                                        {{ __('Father Occupation') }} <b class="ambitious-crimson">*</b>
                                    </label>
                                    <input id="father_occupation" class="form-control @if($errors->has('father_occupation')) is-invalid @endif" name="father_occupation" type="text" value="{{ old('father_occupation') }}" placeholder="Type Your Father Occupation" required>
                                    @if ($errors->has('father_occupation'))
                                        {{ Session::flash('error',$errors->first('father_occupation')) }}
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="mother_name">
                                        {{ __('Mother Name') }} <b class="ambitious-crimson">*</b>
                                    </label>
                                    <input id="mother_name" class="form-control @if($errors->has('mother_name')) is-invalid @endif" name="mother_name" type="text" value="{{ old('mother_name') }}" placeholder="Type Your Mother Name" required>
                                    @if ($errors->has('mother_name'))
                                        {{ Session::flash('error',$errors->first('mother_name')) }}
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="mother_occupation">
                                        {{ __('Mother Occupation') }} <b class="ambitious-crimson">*</b>
                                    </label>
                                    <input id="mother_occupation" class="form-control @if($errors->has('mother_occupation')) is-invalid @endif" name="mother_occupation" type="text" value="{{ old('mother_occupation') }}" placeholder="Type Your Mother Occupation" required>
                                    @if ($errors->has('mother_occupation'))
                                        {{ Session::flash('error',$errors->first('mother_occupation')) }}
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="house_no">
                                        {{ __('House No.') }} <b class="ambitious-crimson">*</b>
                                    </label>
                                    <input id="house_no" class="form-control @if($errors->has('house_no')) is-invalid @endif" name="house_no" type="text" value="{{ old('house_no') }}" placeholder="Type Your House No" required>
                                    @if ($errors->has('house_no'))
                                        {{ Session::flash('error',$errors->first('house_no')) }}
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="scholarship_village_id">
                                        {{ __('Village') }} <b class="ambitious-crimson">*</b>
                                    </label>
                                    <select class="form-control" name="scholarship_village_id" id="scholarship_village_id" required>
                                        <option value="">Select Village</option>
                                        @foreach ($villages as $key => $value)
                                            <option value="{{ $key }}" {{ old('scholarship_village_id') == $key ? 'selected' : '' }}>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                    @error('scholarship_village_id')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="street">
                                        {{ __('Street') }} <b class="ambitious-crimson">*</b>
                                    </label>
                                    <input id="street" class="form-control @if($errors->has('street')) is-invalid @endif" name="street" type="text" value="{{ old('street') }}" placeholder="Type Your Street" required>
                                    @if ($errors->has('street'))
                                        {{ Session::flash('error',$errors->first('street')) }}
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="post_office">
                                        {{ __('Post Office') }} <b class="ambitious-crimson">*</b>
                                    </label>
                                    <input id="post_office" class="form-control @if($errors->has('post_office')) is-invalid @endif" name="post_office" type="text" value="{{ old('post_office') }}" placeholder="Type Your Post Office" required>
                                    @if ($errors->has('post_office'))
                                        {{ Session::flash('error',$errors->first('post_office')) }}
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="taluk">
                                        {{ __('Taluk') }} <b class="ambitious-crimson">*</b>
                                    </label>
                                    <input id="taluk" class="form-control @if($errors->has('taluk')) is-invalid @endif" name="taluk" type="text" value="{{ old('taluk') }}" placeholder="Type Your Taluk" required>
                                    @if ($errors->has('taluk'))
                                        {{ Session::flash('error',$errors->first('taluk')) }}
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="district">
                                        {{ __('District') }} <b class="ambitious-crimson">*</b>
                                    </label>
                                    <input id="district" class="form-control @if($errors->has('district')) is-invalid @endif" name="district" type="text" value="{{ old('district') }}" placeholder="Type Your District" required>
                                    @if ($errors->has('district'))
                                        {{ Session::flash('error',$errors->first('district')) }}
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="pincode">
                                        {{ __('Pincode') }} <b class="ambitious-crimson">*</b>
                                    </label>
                                    <input id="pincode" class="form-control @if($errors->has('pincode')) is-invalid @endif" name="pincode" type="number" value="{{ old('pincode') }}" placeholder="Type Your Pincode" required>
                                    @if ($errors->has('pincode'))
                                        {{ Session::flash('error',$errors->first('pincode')) }}
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="state">
                                        {{ __('State') }} <b class="ambitious-crimson">*</b>
                                    </label>
                                    <input id="state" class="form-control @if($errors->has('state')) is-invalid @endif" name="state" type="text" value="{{ old('state') }}" placeholder="Type Your State" required>
                                    @if ($errors->has('state'))
                                        {{ Session::flash('error',$errors->first('state')) }}
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="date_of_birth">@lang('Date of Birth') <b class="ambitious-crimson">*</b></label>
                                    <input type="text" name="date_of_birth" id="date_of_birth" class="form-control flatpickr @error('date_of_birth') is-invalid @enderror" placeholder="@lang('Date of Birth')" required>
                                    @if ($errors->has('date_of_birth'))
                                        {{ Session::flash('error',$errors->first('date_of_birth')) }}
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="contact_no_1">
                                        {{ __('Contact No 1') }} <b class="ambitious-crimson">*</b>
                                    </label>
                                    <input id="contact_no_1" class="form-control @if($errors->has('contact_no_1')) is-invalid @endif" name="contact_no_1" type="number" value="{{ old('contact_no_1') }}" placeholder="Type Your Contact No 1" required>
                                    @if ($errors->has('contact_no_1'))
                                        {{ Session::flash('error',$errors->first('contact_no_1')) }}
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="contact_no_2">
                                        {{ __('Contact No 2') }} <b class="ambitious-crimson">*</b>
                                    </label>
                                    <input id="contact_no_2" class="form-control @if($errors->has('contact_no_2')) is-invalid @endif" name="contact_no_2" type="number" value="{{ old('contact_no_2') }}" placeholder="Type Your Contact No 2" required>
                                    @if ($errors->has('contact_no_2'))
                                        {{ Session::flash('error',$errors->first('contact_no_2')) }}
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="gender">@lang('Gender') <b class="ambitious-crimson">*</b></label>
                                    <select name="gender" class="form-control @error('gender') is-invalid @enderror" id="gender" required>
                                        <option value="">--@lang('Select')--</option>
                                        <option value="male" {{ old('gender') === 'male' ? 'selected' : '' }}>@lang('Male')</option>
                                        <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>@lang('Female')</option>
                                    </select>
                                    @if ($errors->has('gender'))
                                        {{ Session::flash('error',$errors->first('gender')) }}
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="age">
                                        {{ __('Age') }} <b class="ambitious-crimson">*</b>
                                    </label>
                                    <input id="age" class="form-control @if($errors->has('age')) is-invalid @endif" name="age" type="number" value="{{ old('age') }}" placeholder="Type Your Age" required>
                                    @if ($errors->has('age'))
                                        {{ Session::flash('error',$errors->first('age')) }}
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="aadhar_no">
                                        {{ __('Aadhar No') }} <b class="ambitious-crimson">*</b>
                                    </label>
                                    <input id="aadhar_no" class="form-control @if($errors->has('aadhar_no')) is-invalid @endif" name="aadhar_no" type="number" value="{{ old('aadhar_no') }}" placeholder="Type Aadhar No" required>
                                    @if ($errors->has('aadhar_no'))
                                        {{ Session::flash('error',$errors->first('aadhar_no')) }}
                                    @endif
                                </div>
                            </div>
                        </div>
                    </section>
                    <h6>{{ __('Studied Details') }}</h6>
                    <section>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="school_or_college">@lang('Institution') <b class="ambitious-crimson">*</b></label>
                                    <select class="form-control ambitious-form-loading @error('school_or_college') is-invalid @enderror" required="required" name="school_or_college" id="school_or_college">
                                        <option value="1" {{ old('school_or_college') === 1 ? 'selected' : '' }}>@lang('School')</option>
                                        <option value="2" {{ old('school_or_college') === 2 ? 'selected' : '' }}>@lang('College')</option>
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
                                    <select class="form-control" name="scholarship_school_id" id="scholarship_school_id">
                                        <option value="">Select School</option>
                                        @foreach ($schools as $key => $value)
                                            <option value="{{ $key }}" {{ old('scholarship_school_id') == $key ? 'selected' : '' }}>{{ $value }}</option>
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
                                    <select class="form-control" name="scholarship_college_id" id="scholarship_college_id">
                                        <option value="">Select College</option>
                                        @foreach ($colleges as $key => $value)
                                            <option value="{{ $key }}" {{ old('scholarship_college_id') == $key ? 'selected' : '' }}>{{ $value }}</option>
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
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="school_year">
                                        @lang('Year') <b class="ambitious-crimson">*</b>
                                    </label>
                                    <select class="form-control" name="school_year" id="school_year">
                                        <option value="">Select Year</option>
                                        @foreach ($years as $key => $value)
                                            <option value="{{ $key }}" {{ old('school_year') == $key ? 'selected' : '' }}>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                    @error('school_year')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="school_grade">
                                        @lang('Class') <b class="ambitious-crimson">*</b>
                                    </label>
                                    <select class="form-control" name="school_grade" id="school_grade">
                                        <option value="">Select Class</option>
                                        @foreach ($classes as $key => $value)
                                            <option value="{{ $key }}" {{ old('school_grade') == $key ? 'selected' : '' }}>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                    @error('school_grade')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="school_contact_person">
                                        {{ __('Contact Person') }} <b class="ambitious-crimson">*</b>
                                    </label>
                                    <input id="school_contact_person" class="form-control @if($errors->has('school_contact_person')) is-invalid @endif" name="school_contact_person" type="text" value="{{ old('school_contact_person') }}" placeholder="Type Your Contact Person" required>
                                    @if ($errors->has('school_contact_person'))
                                        {{ Session::flash('error',$errors->first('school_contact_person')) }}
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="school_designation">
                                        {{ __('Contact Person Designation') }} <b class="ambitious-crimson">*</b>
                                    </label>
                                    <select name="school_designation" class="form-control @error('school_designation') is-invalid @enderror" id="school_designation" required>
                                        <option value="">--@lang('Select')--</option>
                                        <option value="Principal" {{ old('school_designation') === 'Principal' ? 'selected' : '' }}>@lang('Principal')</option>
                                        <option value="Head" {{ old('school_designation') === 'Head' ? 'selected' : '' }}>@lang('Head')</option>
                                        <option value="Teacher" {{ old('school_designation') === 'Teacher' ? 'selected' : '' }}>@lang('Teacher')</option>
                                    </select>
                                    @error('school_designation')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="school_contact_number">
                                        {{ __('Contact Number') }} <b class="ambitious-crimson">*</b>
                                    </label>
                                    <input id="school_contact_number" class="form-control @if($errors->has('school_contact_number')) is-invalid @endif" name="school_contact_number" type="number" value="{{ old('school_contact_number') }}" placeholder="Type Your Contact Number" required>
                                    @if ($errors->has('school_contact_number'))
                                        {{ Session::flash('error',$errors->first('school_contact_number')) }}
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="marks_obtained_type">@lang('Examination Type') <b class="ambitious-crimson">*</b></label>
                                    <select name="marks_obtained_type" class="form-control @error('marks_obtained_type') is-invalid @enderror" id="marks_obtained_type" required>
                                        <option value="">--@lang('Select')--</option>
                                        <option value="SSLC" {{ old('marks_obtained_type') === 'SSLC' ? 'selected' : '' }}>@lang('SSLC')</option>
                                        <option value="PUC" {{ old('marks_obtained_type') === 'PUC' ? 'selected' : '' }}>@lang('PUC')</option>
                                        <option value="Degree" {{ old('marks_obtained_type') === 'Degree' ? 'selected' : '' }}>@lang('Degree')</option>
                                    </select>
                                    @if ($errors->has('marks_obtained_type'))
                                        {{ Session::flash('error',$errors->first('marks_obtained_type')) }}
                                    @endif
                                </div>
                            </div>
                            <div id="examination_subject_block" class="col-md-4">
                                <div class="form-group">
                                    <label for="marks_subject">
                                        {{ __('Subject') }} <b class="ambitious-crimson">*</b>
                                    </label>
                                    <input id="marks_subject" class="form-control @if($errors->has('marks_subject')) is-invalid @endif" name="marks_subject" type="text" value="{{ old('marks_subject') }}" placeholder="Type Your subject">
                                    @if ($errors->has('marks_obtained'))
                                        {{ Session::flash('error',$errors->first('marks_obtained')) }}
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="marks_obtained">
                                        {{ __('Last Examination Marks') }} <b class="ambitious-crimson">*</b>
                                    </label>
                                    <input id="marks_obtained" class="form-control @if($errors->has('marks_obtained')) is-invalid @endif" name="marks_obtained" type="text" value="{{ old('marks_obtained') }}" placeholder="Type Your Marks" required>
                                    @if ($errors->has('marks_obtained'))
                                        {{ Session::flash('error',$errors->first('marks_obtained')) }}
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="further_education_details_school_or_college">@lang('Further Education Institution') <b class="ambitious-crimson">*</b></label>
                                    <select class="form-control ambitious-form-loading @error('further_education_details_school_or_college') is-invalid @enderror" required="required" name="further_education_details_school_or_college" id="further_education_details_school_or_college">
                                        <option value="1" {{ old('further_education_details_school_or_college') === 1 ? 'selected' : '' }}>@lang('School')</option>
                                        <option value="2" {{ old('further_education_details_school_or_college') === 2 ? 'selected' : '' }}>@lang('College')</option>
                                    </select>
                                    @error('further_education_details_school_or_college')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div id="further_education_details_school_block" class="col-md-4">
                                <div class="form-group">
                                    <label for="further_education_details_scholarship_school_id">
                                        @lang('Further Education School') <b class="ambitious-crimson">*</b>
                                    </label>
                                    <select class="form-control" name="further_education_details_scholarship_school_id" id="further_education_details_scholarship_school_id">
                                        <option value="">Select School</option>
                                        @foreach ($schools as $key => $value)
                                            <option value="{{ $key }}" {{ old('further_education_details_scholarship_school_id') == $key ? 'selected' : '' }}>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                    @error('further_education_details_scholarship_school_id')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div id="further_education_details_college_block" class="col-md-4">
                                <div class="form-group">
                                    <label for="further_education_details_scholarship_college_id">
                                        @lang('Further Education College') <b class="ambitious-crimson">*</b>
                                    </label>
                                    <select class="form-control" name="further_education_details_scholarship_college_id" id="further_education_details_scholarship_college_id">
                                        <option value="">Select College</option>
                                        @foreach ($colleges as $key => $value)
                                            <option value="{{ $key }}" {{ old('further_education_details_scholarship_college_id') == $key ? 'selected' : '' }}>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                    @error('further_education_details_scholarship_college_id')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="further_education_details_course_joined">
                                        {{ __('Further Education Current Course/Class') }} <b class="ambitious-crimson">*</b>
                                    </label>
                                    <input id="further_education_details_course_joined" class="form-control @if($errors->has('further_education_details_course_joined')) is-invalid @endif" name="further_education_details_course_joined" type="text" value="{{ old('further_education_details_course_joined') }}" placeholder="Type Your Course Joined" required>
                                    @if ($errors->has('further_education_details_course_joined'))
                                        {{ Session::flash('error',$errors->first('further_education_details_course_joined')) }}
                                    @endif
                                </div>
                            </div>
                        </div>
                    </section>
                    <h6>{{ __('Bank Details') }}</h6>
                    <section>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="bank_name">
                                        {{ __('Bank Name') }} <b class="ambitious-crimson">*</b>
                                    </label>
                                    <input id="bank_name" class="form-control @if($errors->has('bank_name')) is-invalid @endif" name="bank_name" type="text" value="{{ old('bank_name') }}" placeholder="Type Your Bank Name" required>
                                    @if ($errors->has('bank_name'))
                                        {{ Session::flash('error',$errors->first('bank_name')) }}
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="branch">
                                        {{ __('Branch') }} <b class="ambitious-crimson">*</b>
                                    </label>
                                    <input id="branch" class="form-control @if($errors->has('branch')) is-invalid @endif" name="branch" type="text" value="{{ old('branch') }}" placeholder="Type Your Branch" required>
                                    @if ($errors->has('branch'))
                                        {{ Session::flash('error',$errors->first('branch')) }}
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="account_holder_name">
                                        {{ __('Account Holder Name') }} <b class="ambitious-crimson">*</b>
                                    </label>
                                    <input id="account_holder_name" class="form-control @if($errors->has('account_holder_name')) is-invalid @endif" name="account_holder_name" type="text" value="{{ old('account_holder_name') }}" placeholder="Type Your Account Holder Name" required>
                                    @if ($errors->has('account_holder_name'))
                                        {{ Session::flash('error',$errors->first('account_holder_name')) }}
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="account_no">
                                        {{ __('Account No') }} <b class="ambitious-crimson">*</b>
                                    </label>
                                    <input id="account_no" class="form-control @if($errors->has('account_no')) is-invalid @endif" name="account_no" type="number" value="{{ old('account_no') }}" placeholder="Type Your Account No" required>
                                    @if ($errors->has('account_no'))
                                        {{ Session::flash('error',$errors->first('account_no')) }}
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="ifsc_code">
                                        {{ __('IFSC Code') }} <b class="ambitious-crimson">*</b>
                                    </label>
                                    <input id="ifsc_code" class="form-control @if($errors->has('ifsc_code')) is-invalid @endif" name="ifsc_code" type="text" value="{{ old('ifsc_code') }}" placeholder="Type Your IFSC Code" required>
                                    @if ($errors->has('ifsc_code'))
                                        {{ Session::flash('error',$errors->first('ifsc_code')) }}
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status">@lang('Status') <b class="ambitious-crimson">*</b></label>
                                    <select name="status" class="form-control @error('status') is-invalid @enderror" id="status" required>
                                        <option value="">--@lang('Select')--</option>
                                        <option value="Self" {{ old('status') === 'Self' ? 'selected' : '' }}>@lang('Self')</option>
                                        <option value="Father" {{ old('status') === 'Father' ? 'selected' : '' }}>@lang('Father')</option>
                                        <option value="Mother" {{ old('status') === 'Mother' ? 'selected' : '' }}>@lang('Mother')</option>
                                        <option value="Teacher" {{ old('status') === 'Teacher' ? 'selected' : '' }}>@lang('Teacher')</option>
                                    </select>
                                    @if ($errors->has('status'))
                                        {{ Session::flash('error',$errors->first('status')) }}
                                    @endif
                                </div>
                            </div>
                        </div>
                    </section>
                    <!-- Step 5 -->
                    <h6>{{ __('Documents & Declaration') }}</h6>
                    <section>
                        <div class="row mb-2">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="photo">
                                        {{ __('Student Photo') }} <b class="ambitious-crimson">*</b>
                                    </label>
                                    <input id="photo" class="dropify" name="photo" value="{{ old('photo') }}" type="file" data-allowed-file-extensions="png jpg jpeg" data-max-file-size="5500K" required />
                                    <p>@lang('Max Size: 5mb, Allowed Format: png, jpg, jpeg')</p>
                                </div>
                                @if ($errors->has('photo'))
                                    {{ Session::flash('error',$errors->first('photo')) }}
                                @endif
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="income_certificate">
                                        {{ __('Income certificate of Parents/ Guardian') }} <b class="ambitious-crimson">*</b>
                                    </label>
                                    <input id="income_certificate" class="dropify" name="income_certificate" value="{{ old('income_certificate') }}" type="file" data-allowed-file-extensions="pdf" data-max-file-size="5500K" required />
                                    <p>@lang('Max Size: 5mb, Allowed Format: pdf')</p>
                                </div>
                                @if ($errors->has('income_certificate'))
                                    {{ Session::flash('error',$errors->first('income_certificate')) }}
                                @endif
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="id_proof">
                                        {{ __('Govt. ID proof (Aadhar, Ration card etc.)') }} <b class="ambitious-crimson">*</b>
                                    </label>
                                    <input id="id_proof" class="dropify" name="id_proof" value="{{ old('id_proof') }}" type="file" data-allowed-file-extensions="pdf" data-max-file-size="5500K" required />
                                    <p>@lang('Max Size: 5mb, Allowed Format: pdf')</p>
                                </div>
                                @if ($errors->has('id_proof'))
                                    {{ Session::flash('error',$errors->first('id_proof')) }}
                                @endif
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="previous_educational_marks_card">
                                        {{ __('Previous Educational Marks Card') }} <b class="ambitious-crimson">*</b>
                                    </label>
                                    <input id="previous_educational_marks_card" class="dropify" name="previous_educational_marks_card" value="{{ old('previous_educational_marks_card') }}" type="file" data-allowed-file-extensions="pdf" data-max-file-size="5500K" required />
                                    <p>@lang('Max Size: 5mb, Allowed Format: pdf')</p>
                                </div>
                                @if ($errors->has('previous_educational_marks_card'))
                                    {{ Session::flash('error',$errors->first('previous_educational_marks_card')) }}
                                @endif
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="bank_passbook">
                                        {{ __('Bank passbook copy') }} <b class="ambitious-crimson">*</b>
                                    </label>
                                    <input id="bank_passbook" class="dropify" name="bank_passbook" value="{{ old('bank_passbook') }}" type="file" data-allowed-file-extensions="pdf" data-max-file-size="5500K" required />
                                    <p>@lang('Max Size: 5mb, Allowed Format: pdf')</p>
                                </div>
                                @if ($errors->has('bank_passbook'))
                                    {{ Session::flash('error',$errors->first('bank_passbook')) }}
                                @endif
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="original_fee_receipt">
                                        {{ __('Original fee receipt') }} <b class="ambitious-crimson">*</b>
                                    </label>
                                    <input id="original_fee_receipt" class="dropify" name="original_fee_receipt" value="{{ old('original_fee_receipt') }}" type="file" data-allowed-file-extensions="pdf" data-max-file-size="5500K" required />
                                    <p>@lang('Max Size: 5mb, Allowed Format: pdf')</p>
                                </div>
                                @if ($errors->has('original_fee_receipt'))
                                    {{ Session::flash('error',$errors->first('id_proof')) }}
                                @endif
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="fee_amount">
                                        {{ __('Fee amount') }} <b class="ambitious-crimson">*</b>
                                    </label>
                                    <input id="fee_amount" class="form-control @if($errors->has('fee_amount')) is-invalid @endif" name="fee_amount" type="number" value="{{ old('fee_amount') }}" placeholder="Type Fee Amount" required>
                                    @if ($errors->has('fee_amount'))
                                        {{ Session::flash('error',$errors->first('fee_amount')) }}
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="date">@lang('Date') <b class="ambitious-crimson">*</b></label>
                                    <input type="text" name="date" id="date" class="form-control flatpickr @error('date') is-invalid @enderror" placeholder="@lang('Date')" required>
                                    @if ($errors->has('date'))
                                        {{ Session::flash('error',$errors->first('date')) }}
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="role-form-ambi checkbox checkbox-primary">
                                        <input name="given_information" id="given_information" type="checkbox" value="1">
                                        <label for="given_information">
                                            I certified that the information given in above is true and correct. <b class="ambitious-crimson">*</b>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="role-form-ambi checkbox checkbox-primary">
                                        <input name="any_other_scholarship" id="any_other_scholarship" type="checkbox" value="1">
                                        <label for="any_other_scholarship">
                                            I am not availing any other scholarship for this purpose from any NGO/State/Central Govt. <b class="ambitious-crimson">*</b>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="role-form-ambi checkbox checkbox-primary">
                                        <input name="scholarship_refunded" id="scholarship_refunded" type="checkbox" value="1">
                                        <label for="scholarship_refunded">
                                            If the information given by me is found to be false/incorrect, the scholarship sanction to me may be cancelled <br>
                                            and the amount of scholarship refunded by me. <b class="ambitious-crimson">*</b>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('plugins/sweetalert/sweetalert.min.js') }}"></script>
<script>

    $(document).ready(function() {
        "use strict";
        $(".select2").select2();



        $('.dropify').dropify();
        $('.dropify-fr').dropify({
            messages: {
                default: 'Glissez-déposez un fichier ici ou cliquez',
                replace: 'Glissez-déposez un fichier ou cliquez pour remplacer',
                remove: 'Supprimer',
                error: {
                    'fileSize': 'The file size is too big  max.',
                    'fileFormat': 'The image format is not allowed only.'
                }
            }
        });
        var drEvent = $('#input-file-events').dropify();
        drEvent.on('dropify.beforeClear', function(event, element) {
            return confirm("Do you really want to delete \"" + element.file.name + "\" ?");
        });
        drEvent.on('dropify.afterClear', function(event, element) {
            alert('File deleted');
        });
        drEvent.on('dropify.errors', function(event, element) {
            console.log('Has Errors');
        });
        var drDestroy = $('#input-file-to-destroy').dropify();
        drDestroy = drDestroy.data('dropify')
        $('#toggleDropify').on('click', function(e) {
            e.preventDefault();
            if (drDestroy.isDropified()) {
                drDestroy.destroy();
            } else {
                drDestroy.init();
            }
        })
    });

    $(document).ready(function() {
        $('.select3').selectize();

        $(".flatpickr").flatpickr({
            enableTime: false
        });

        var marks_obtained_type = $('#marks_obtained_type').val();
        if(marks_obtained_type == 'PUC' || marks_obtained_type == 'Degree') {
            $('#examination_subject_block').show();
        } else {
            $('#examination_subject_block').hide();
        }

        $('#marks_obtained_type').change(function(){
            if($('#marks_obtained_type').val() == 'PUC' || $('#marks_obtained_type').val() == 'Degree') {
                $('#examination_subject_block').show();
            } else {
                $('#examination_subject_block').hide();
            }
        });

        var further_education_details_school_or_college = $('#further_education_details_school_or_college').val();
        if(further_education_details_school_or_college == '1') {
            $('#further_education_details_school_block').show();
            $('#further_education_details_college_block').hide();
        } else {
            $('#further_education_details_school_block').hide();
            $('#further_education_details_college_block').show();
        }

        $('#further_education_details_school_or_college').change(function(){
            if($('#further_education_details_school_or_college').val() == '1') {
                $('#further_education_details_school_block').show();
                $('#further_education_details_college_block').hide();
            } else {
                $('#further_education_details_school_block').hide();
                $('#further_education_details_college_block').show();
            }
        });

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
    });


    var form = $(".validation-wizard").show();

    $(".validation-wizard").steps({
        headerTag: "h6",
        bodyTag: "section",
        transitionEffect: "fade",
        titleTemplate: '<span class="step">#index#</span> #title#',
        labels: {
            finish: "Submit"
        },
        onStepChanging: function(event, currentIndex, newIndex) {
            var itemName = "{{ $ApplicationSetting->item_name  }}";
            if(currentIndex == 0) {
                var percentage_marks_obtained = $("#percentage_marks_obtained").val();
                var marks = Number(percentage_marks_obtained);
                if(marks == 0) {
                    Swal.fire(
                        itemName,
                        '{{ __('Please Give Your Last Examination Marks') }}',
                        'warning'
                    );
                    return false;
                }
                if(marks > 100) {
                    Swal.fire(
                        itemName,
                        '{{ __('Please Give Percentage Marks Of Your Last Examination ') }}',
                        'warning'
                    );
                    return false;
                }
                if(marks > 0 && marks < 65) {
                    Swal.fire(
                            itemName,
                            '{{ __('You Are Not Eligible For The Scholarship') }}',
                            'warning'
                        ).then(function() {
                            document.location.href="{{ route('dashboard') }}";
                        });
                    return false;
                }
            }

            if(currentIndex == 2) {
                var marks_obtained_type = $('#marks_obtained_type').val();
                if(marks_obtained_type == 'PUC' || marks_obtained_type == 'Degree') {
                    var marks_subject = $('#marks_subject').val();
                    if(marks_subject == "" || marks_subject== undefined){
                        Swal.fire(
                            itemName,
                            '{{ __('Please Type Subject') }}',
                            'warning'
                        );
                        return false;
                    }
                }

                var further_education_details_school_or_college = $('#further_education_details_school_or_college').val();
                if(further_education_details_school_or_college == '1') {
                    var further_education_details_scholarship_school_id = $('#further_education_details_scholarship_school_id').val();
                    if(further_education_details_scholarship_school_id == "" || further_education_details_scholarship_school_id== undefined){
                        Swal.fire(
                            itemName,
                            '{{ __('Please Select Further Education School First') }}',
                            'warning'
                        );
                        return false;
                    }
                } else {
                    var further_education_details_scholarship_college_id = $('#further_education_details_scholarship_college_id').val();
                    if(further_education_details_scholarship_college_id == "" || further_education_details_scholarship_college_id== undefined){
                        Swal.fire(
                            itemName,
                            '{{ __('Please Select Further Education College First') }}',
                            'warning'
                        );
                        return false;
                    }
                }

                var school_or_college = $('#school_or_college').val();
                if(school_or_college == '1') {
                    var scholarship_school_id = $('#scholarship_school_id').val();
                    if(scholarship_school_id == "" || scholarship_school_id== undefined){
                        Swal.fire(
                            itemName,
                            '{{ __('Please Select A School First') }}',
                            'warning'
                        );
                        return false;
                    }
                } else {
                    var scholarship_college_id = $('#scholarship_college_id').val();
                    if(scholarship_college_id == "" || scholarship_college_id== undefined){
                        Swal.fire(
                            itemName,
                            '{{ __('Please Select A College First') }}',
                            'warning'
                        );
                        return false;
                    }
                }
            }

            return currentIndex > newIndex || !(3 === newIndex && Number($("#age-2").val()) < 18) && (currentIndex < newIndex && (form.find(".body:eq(" + newIndex + ") label.error").remove(), form.find(".body:eq(" + newIndex + ") .error").removeClass("error")), form.validate().settings.ignore = ":disabled,:hidden", form.valid())
        },
        onFinishing: function(event, currentIndex) {
            return form.validate().settings.ignore = ":disabled", form.valid()
        },
        onFinished: function(event, currentIndex) {
            var itemName = "{{ $ApplicationSetting->item_name  }}";

            var veryyes = document.getElementById("given_information");
            var veryno = document.getElementById("any_other_scholarship");
            var verygood = document.getElementById("scholarship_refunded");

            if (veryyes.checked == false || veryno.checked == false || verygood.checked == false){
                Swal.fire(
                    itemName,
                    '{{ __('All Declaration checkbox not checked!') }}',
                    'warning'
                );
                return false;
            }

            var percentage_marks_obtained = $("#percentage_marks_obtained").val();
            var marks = Number(percentage_marks_obtained);

            var queryString = new FormData($("#scholarship_create_form")[0]);
            $.ajax({
                url: '{{ url('scholarship/store') }}',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                type:'POST',
                data:queryString,
                dataType : 'JSON',
                cache: false,
                contentType: false,
                processData: false,
                success:function(response){
                    if(response.status==0){
                        Swal.fire(
                            itemName,
                            '{{ __('Oops Something Wrong') }}',
                            'warning'
                        ).then(function() {
                            document.location.href="#";
                        });
                    }
                    else {
                        Swal.fire(
                          itemName,
                          '{{ __('Apply Scholarship Successfully') }}',
                          'success'
                        ).then(function() {
                            document.location.href="{{ route('scholarship.index') }}";
                        });
                    }
                }
            });
        }
    }), $(".validation-wizard").validate({
        ignore: "input[type=hidden]",
        errorClass: "text-danger",
        successClass: "text-success",
        highlight: function(element, errorClass) {
            $(element).removeClass(errorClass)
        },
        unhighlight: function(element, errorClass) {
            $(element).removeClass(errorClass)
        },
        errorPlacement: function(error, element) {
            error.insertAfter(element)
        },
        rules: {
            email: {
                email: !0
            },

            password:{
                required: true,
                minlength: 6
            },
            confirm_password:{
                required: true,
                minlength: 6,
                equalTo: "#password"
            }
        }
    })
</script>
@endsection
