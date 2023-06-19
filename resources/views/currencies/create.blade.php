@extends('layouts.layout')
@section('one_page_js')
    <script src="{{ asset('js/quill.js') }}"></script>
@endsection

@section('one_page_css')
    <link href="{{ asset('css/quill.snow.css') }}" rel="stylesheet">
    <link href="{{ asset('css/switch.css') }}" rel="stylesheet">
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
                        <a href="{{ route('currency.index') }}">@lang('Currency List')</a></li>
                    <li class="breadcrumb-item active">@lang('Add New Currency')</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">@lang('Add New Currency')</h3>
            </div>
            <div class="card-body">
                <form class="form-material form-horizontal" action="{{ route('currency.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="name">@lang('Name') <b class="ambitious-crimson">*</b></label>
                                <div class="form-group input-group mb-3">
                                    <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-money-check-alt"></i></span>
                                    </div>
                                    <input type="text" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" placeholder="@lang('Enter Currency Name')" required>
                                    @error('name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="code">@lang('Code') <b class="ambitious-crimson">*</b></label>
                                <div class="form-group input-group mb-3">
                                    <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-code"></i></span>
                                    </div>
                                    <select class="form-control @error('code') is-invalid @enderror" required="required" id="code" name="code">
                                        <option value="">- @lang('Select Currency Code') -</option>
                                        @foreach($data as $key=> $value)
                                            <option value="{{ $key }}" {{ old('code') == $key ? 'selected' : '' }}>{{ $key }}</option>
                                        @endforeach
                                    </select>
                                    @error('code')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="rate">@lang('Rate') <b class="ambitious-crimson">*</b></label>
                                <div class="form-group input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-greater-than-equal"></i></span>
                                    </div>
                                    <input type="text" id="rate" name="rate" value="{{ old('rate') }}" class="form-control @error('rate') is-invalid @enderror" placeholder="@lang('Enter Currency Rate')" required>
                                    @error('rate')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="precision">@lang('Precision') <b class="ambitious-crimson">*</b></label>
                                <div class="form-group input-group mb-3">
                                    <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-bullseye"></i></span>
                                    </div>
                                    <input type="text" name="precision" value="{{ old('precision') }}" class="form-control @error('precision') is-invalid @enderror" placeholder="@lang('Enter Precision')" required>
                                    @error('precision')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="symbol">@lang('Symbol') <b class="ambitious-crimson">*</b></label>
                                <div class="form-group input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-coins"></i></span>
                                    </div>
                                    <input class="form-control @error('symbol') is-invalid @enderror" placeholder="@lang('Enter Symbol')" required="required" name="symbol" value="{{ old('symbol') }}" type="text" id="symbol" required>
                                    @error('symbol')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="symbol_first">@lang('Symbol Position') <b class="ambitious-crimson">*</b></label>
                                <div class="form-group input-group mb-3">
                                    <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-crosshairs"></i></span>
                                    </div>
                                    <select class="form-control @error('symbol_first') is-invalid @enderror" required="required" id="symbol_first" name="symbol_first">
                                        <option value="1" {{ old('symbol_first') == 1 ? 'selected' : '' }}>@lang('Before Amount')</option>
                                        <option value="0" {{ old('symbol_first') == 0 ? 'selected' : '' }}>@lang('After Amount')</option>
                                    </select>
                                    @error('symbol_first')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="decimal_mark">@lang('Decimal Mark') <b class="ambitious-crimson">*</b></label>
                                <div class="form-group input-group mb-3">
                                    <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-columns"></i></span>
                                    </div>
                                    <input class="form-control @error('decimal_mark') is-invalid @enderror" placeholder="@lang('Enter Decimal Mark')" required="required" name="decimal_mark" value="{{ old('decimal_mark') }}" type="text" id="decimal_mark" required>
                                    @error('decimal_mark')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="thousands_separator">@lang('Thousands Separator') <b class="ambitious-crimson">*</b></label>
                                <div class="form-group input-group mb-3">
                                    <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-columns"></i></span>
                                    </div>
                                    <input class="form-control @error('thousands_separator') is-invalid @enderror" placeholder="@lang('Enter Thousands Separator')" name="thousands_separator" value="{{ old('thousands_separator') }}" type="text" id="thousands_separator" required>
                                    @error('thousands_separator')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="enabled">@lang('Enabled') <b class="ambitious-crimson">*</b></label>
                                <div class="form-group input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-bell"></i></span>
                                    </div>
                                    <select class="form-control ambitious-form-loading @error('enabled') is-invalid @enderror" required="required" name="enabled" id="enabled">
                                        <option value="1" {{ old('enabled') == 1 ? 'selected' : '' }}>@lang('Yes')</option>
                                        <option value="0" {{ old('enabled') == 0 ? 'selected' : '' }}>@lang('No')</option>
                                    </select>
                                    @error('enabled')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="default_currency">@lang('Default Currency') </label>
                                <div class="form-group input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fab fa-typo3"></i></span>
                                    </div>
                                    <select class="form-control ambitious-form-loading @error('default_currency') is-invalid @enderror" required="required" name="default_currency" id="default_currency">
                                        <option value="1" {{ old('default_currency') == 1 ? 'selected' : '' }}>@lang('Yes')</option>
                                        <option value="0" {{ old('default_currency') == 0 ? 'selected' : '' }}>@lang('No')</option>
                                    </select>
                                    @error('default_currency')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 col-form-label"></label>
                        <div class="col-md-8">
                            <input type="submit" value="@lang('Submit')" class="btn btn-outline btn-info btn-lg"/>
                            <a href="{{ route('currency.index') }}" class="btn btn-outline btn-warning btn-lg">@lang('Cancel')</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@include('script.currency.create.js')

@endsection
