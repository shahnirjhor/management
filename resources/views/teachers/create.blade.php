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
                    <li class="breadcrumb-item"><a href="{{ route('scholarship-teacher.index') }}">@lang('Teacher List')</a></li>
                    <li class="breadcrumb-item active">@lang('Create Teacher')</li>
                </ol>
            </div>
        </div>
    </div>
</section>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3>@lang('Create Teacher')</h3>
            </div>
            <div class="card-body">
                <form id="userQuickForm" class="form-material form-horizontal" action="{{ route('scholarship-teacher.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">
                                    @lang('Name') <b class="ambitious-crimson">*</b>
                                </label>
                                <input class="form-control ambitious-form-loading @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" id="name" type="text" placeholder="{{ __('Type Teacher Name Here') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="website">
                                    @lang('Email')
                                </label>
                                <input class="form-control ambitious-form-loading @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" id="email" type="email" placeholder="@lang('Type Email Here')">
                                @error('email')
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
                                <select class="form-control select2" name="scholarship_school_id" id="scholarship_school_id">
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
                                <select class="form-control select2" name="scholarship_college_id" id="scholarship_college_id">
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
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="phone">@lang('Phone')</label>
                                <div class="input-group mb-3">
                                    <input type="text" id="phone" name="phone" value="{{ old('phone') }}" class="form-control @error('phone') is-invalid @enderror" placeholder="@lang('Phone')">
                                    @error('phone')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="date_of_birth">@lang('Date of Birth')</label>
                                <div class="input-group mb-3">
                                    <input type="text" name="date_of_birth" id="date_of_birth" class="form-control flatpickr @error('date_of_birth') is-invalid @enderror" placeholder="@lang('Date of Birth')" value="{{ old('date_of_birth') }}">
                                    @error('date_of_birth')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="gender">@lang('Gender')</label>
                                <div class="input-group">
                                    <select name="gender" class="form-control @error('gender') is-invalid @enderror" id="gender">
                                        <option value="">--@lang('Select')--</option>
                                        <option value="male" {{ old('gender') === 'male' ? 'selected' : '' }}>@lang('Male')</option>
                                        <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>@lang('Female')</option>
                                    </select>
                                    @error('gender')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="blood_group">@lang('Blood Group')</label>
                                <div class="input-group">
                                    <select name="blood_group" class="form-control @error('gender') is-invalid @enderror" id="blood_group">
                                        <option value="">--@lang('Select')--</option>
                                        <option value="A+" {{ old('blood_group') === 'A+' ? 'selected' : '' }}>@lang('A+')</option>
                                        <option value="A-" {{ old('blood_group') === 'A-' ? 'selected' : '' }}>@lang('A-')</option>
                                        <option value="B+" {{ old('blood_group') === 'B+' ? 'selected' : '' }}>@lang('B+')</option>
                                        <option value="B-" {{ old('blood_group') === 'B-' ? 'selected' : '' }}>@lang('B-')</option>
                                        <option value="O+" {{ old('blood_group') === 'O+' ? 'selected' : '' }}>@lang('O+')</option>
                                        <option value="O-" {{ old('blood_group') === 'O-' ? 'selected' : '' }}>@lang('O-')</option>
                                        <option value="AB+" {{ old('blood_group') === 'AB+' ? 'selected' : '' }}>@lang('AB+')</option>
                                        <option value="AB-" {{ old('blood_group') === 'AB-' ? 'selected' : '' }}>@lang('AB-')</option>
                                    </select>
                                    @error('blood_group')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-6">
                            <label class="col-md-12 col-form-label"><h4>@lang('Photo')</h4></label>
                            <div class="col-md-12">
                                <input id="photo" class="dropify" name="photo" value="{{ old('photo') }}" type="file" data-allowed-file-extensions="png jpg jpeg" data-max-file-size="2024K" />
                                <p>@lang('Max Size: 2mb, Allowed Format: png, jpg, jpeg')</p>
                            </div>
                            @if ($errors->has('photo'))
                                <div class="error ambitious-red">{{ $errors->first('photo') }}</div>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <label class="col-md-12 col-form-label"><h4>@lang('Address')</h4></label>
                            <div class="col-md-12">
                                <div id="input_address" class="@error('address') is-invalid @enderror" style="min-height: 55px;">
                                </div>
                                <input type="hidden" name="address" value="{{ old('address') }}" id="address">
                                @error('address')
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
                                <label for="status">@lang('Status') <b class="ambitious-crimson">*</b></label>
                                <select class="form-control ambitious-form-loading @error('status') is-invalid @enderror" required="required" name="status" id="status">
                                    <option value="1" {{ old('status') === 1 ? 'selected' : '' }}>@lang('Active')</option>
                                    <option value="0" {{ old('status') === 0 ? 'selected' : '' }}>@lang('Inactive')</option>
                                </select>
                                @error('status')
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
                            <input id="from_submit" type="submit" value="@lang('Submit')" class="btn btn-outline btn-info btn-lg"/>
                            <a href="{{ route('scholarship-teacher.index') }}" class="btn btn-outline btn-warning btn-lg">@lang('Cancel')</a>
                        </div>
                    </div>
                </form>
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

        var quill = new Quill('#input_address', {
            theme: 'snow'
        });

        var address = $("#address").val();
        quill.clipboard.dangerouslyPasteHTML(address);
        quill.root.blur();
        $('#input_address').on('keyup', function(){
            var input_address = quill.container.firstChild.innerHTML;
            $("#address").val(input_address);
        });

        $(".flatpickr").flatpickr({
            enableTime: false
        });

        $('#from_submit').click(function(){
            var school_or_college = $('#school_or_college').val();
            if(school_or_college == '1') {
                var scholarship_school_id = $('#scholarship_school_id').val();
                if(scholarship_school_id == "" || scholarship_school_id== undefined){
                    alert("Please Select The School")
                }
            } else {
                var scholarship_college_id = $('#scholarship_college_id').val();
                if(scholarship_college_id == "" || scholarship_college_id== undefined){
                    alert("Please Select The College")
                }
            }
        });


        $(".select2").select2();
    });

    $(document).ready(function() {
        "use strict";
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
</script>
@include('script.teachers.create.js')
@endsection
