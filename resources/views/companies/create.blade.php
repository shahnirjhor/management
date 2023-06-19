@extends('layouts.layout')
@section('one_page_js')
    <script src="{{ asset('js/quill.js') }}"></script>
    <script src="{{ asset('plugins/dropify/dist/js/dropify.min.js') }}"></script>
@endsection

@section('one_page_css')
    <link href="{{ asset('css/quill.snow.css') }}" rel="stylesheet">
    <link href="{{ asset('plugins/dropify/dist/css/dropify.min.css') }}" rel="stylesheet">
@endsection
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('company.index') }}">@lang('Company List')</a></li>
                    <li class="breadcrumb-item active">@lang('Add Company')</li>
                </ol>
            </div>
        </div>
    </div>
</section>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3>@lang('Add Company')</h3>
            </div>
            <div class="card-body">
                <form class="form-material form-horizontal" action="{{ route('company.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label class="col-md-12">
                                <h4>@lang('Company Name') <b class="ambitious-crimson">*</b></h4>
                            </label>
                            <div class="col-md-12">
                                <input class="form-control ambitious-form-loading @error('company_name') is-invalid @enderror" name="company_name" value="{{ old('company_name') }}" id="company_name" type="text" placeholder="@lang('Enter Name')" required>
                                @error('company_name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-6">
                            <label class="col-md-12">
                                <h4>@lang('Company Email') <b class="ambitious-crimson">*</b></h4>
                            </label>
                            <div class="col-md-12">
                                <input class="form-control ambitious-form-loading @error('company_email') is-invalid @enderror" name="company_email" value="{{ old('company_email') }}" id="company_email" type="email" placeholder="@lang('Enter Company Email')" required>
                                @error('company_email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="col-md-12">
                                <h4>@lang('Domain') <b class="ambitious-crimson">*</b></h4>
                            </label>
                            <div class="col-md-12">
                                <input class="form-control ambitious-form-loading @error('domain') is-invalid @enderror" name="domain" value="{{ old('domain') }}" id="domain" type="text" placeholder="@lang('Enter Domain')" required>
                                @error('domain')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-6">
                            <label class="col-md-12"><h4>@lang('Currency') <b class="ambitious-crimson">*</b></h4></label>
                            <div class="col-md-12">
                                <select class="form-control ambitious-form-loading @error('default_currency') is-invalid @enderror" name="default_currency" value="{{ old('default_currency') }}" id="default_currency">
                                    @foreach ($currencies as $key => $value)
                                        <option value="{{ $key }}"  {{ old('default_currency') == $key ? 'selected' : '' }}>{{ $value }}</option>
                                    @endforeach
                                </select>
                                @error('default_currency')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="col-md-12"><h4>@lang('Enabled') <b class="ambitious-crimson">*</b></h4></label>
                            <div class="col-md-12">
                                <select class="form-control ambitious-form-loading @error('enabled') is-invalid @enderror" name="enabled" value="" id="enabled">
                                    <option value="1" {{ old('enabled') === 1 ? 'selected' : '' }}>@lang('Yes')</option>
                                    <option value="0" {{ old('enabled') === 0 ? 'selected' : '' }}>@lang('No')</option>
                                </select>
                                @error('enabled')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-6">
                            <label class="col-md-12"><h4>@lang('Photo') </h4></label>
                            <div class="col-md-12">
                                <input id="photo" class="dropify" name="photo" value="{{ old('photo') }}" type="file" data-allowed-file-extensions="png jpg jpeg" data-max-file-size="1024K"/>
                                <p>
                                @lang('Max Size: 1000kb, Allowed Format: png, jpg, jpeg')
                                </p>
                                @error('photo')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="col-md-12"><h4>@lang('Address')</h4></label>
                            <div class="col-md-12">
                                <div id="edit_input_address" class="form-control @error('address') is-invalid @enderror" style="min-height: 55px;">
                                </div>
                                <input type="hidden" name="address" id="address">
                                @error('address')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="form-group">
                            <label class="col-md-3 col-form-label"></label>
                            <div class="col-md-8">
                                <input type="submit" value="@lang('Submit')" class="btn btn-outline btn-info btn-lg"/>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@include('script.setting.js')
@endsection
