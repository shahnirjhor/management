@extends('layouts.layout')
@section('one_page_js')
    <script src="{{ asset('js/quill.js') }}"></script>
@endsection

@section('one_page_css')
    <link href="{{ asset('css/quill.snow.css') }}" rel="stylesheet">
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
                    <li class="breadcrumb-item active">{{ __('Edit Currency') }}</li>
                </ol>
            </div>
        </div>
    </div>
</section>

@include('partials.errors')
@include('partials.success')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('Edit Currency') }}</h3>
                </div>
                <div class="card-body">
                    <form class="form-material form-horizontal" action="{{ route('currency.update', $data) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="id" value="{{ encrypt($data->id) }}">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="exampleInputPassword1">@lang('Name') <b class="ambitious-crimson">*</b></label>
                                <div class="form-group input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-money-check-alt"></i>
                                    </div>
                                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="@lang('Enter Currency Name')" value="{{ old('name',$data->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="exampleInputPassword1">@lang('Code') <b class="ambitious-crimson">*</b></label>
                                <div class="form-group input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-code"></i></span>
                                    </div>
                                    <select class="form-control @error('code') is-invalid @enderror" required="required" id="code" name="code">
                                        <option value="">- @lang('Select Currency Code') -</option>
                                        @foreach($currencies as $key=> $value)
                                            <option value="{{ $key }}" {{ old('code', $data->code) == $key ? 'selected' : '' }}>{{ $key }}</option>
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
                                    <label for="exampleInputPassword1">@lang('Rate') <b class="ambitious-crimson">*</b></label>
                                    <div class="form-group input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-greater-than-equal"></i></span>
                                        </div>
                                        <input type="number" id="rate" name="rate" class="form-control @error('rate') is-invalid @enderror" placeholder="@lang('Enter Currency Rate')" value="{{ old('rate',$data->rate) }}" required>
                                        @error('rate')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="exampleInputPassword1">@lang('Precision') <b class="ambitious-crimson">*</b></label>
                                    <div class="form-group input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-bullseye"></i></span>
                                        </div>
                                        <input type="number" name="precision" class="form-control @error('precision') is-invalid @enderror" placeholder="@lang('Enter Precision')" value="{{ old('precision',$data->precision) }}" required>
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
                                    <label for="exampleInputPassword1">@lang('Symbol') <b class="ambitious-crimson">*</b></label>
                                    <div class="form-group input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-coins"></i></span>
                                        </div>
                                        <input class="form-control @error('symbol') is-invalid @enderror" value="{{ old('symbol',$data->symbol) }}" placeholder="@lang('Enter Symbol')" required="required" name="symbol" type="text" id="symbol">
                                        @error('symbol')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="exampleInputPassword1">@lang('Symbol Position') <b class="ambitious-crimson">*</b></label>
                                    <div class="form-group input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-crosshairs"></i></span>
                                        </div>
                                        <select class="form-control @error('symbol_first') is-invalid @enderror" required="required" id="symbol_first" name="symbol_first">
                                            <option value="1" {{ old('symbol_first', $data->symbol_first) == 1 ? 'selected' : '' }}>@lang('Before Amount')</option>
                                            <option value="0" {{ old('symbol_first', $data->symbol_first) == 0 ? 'selected' : '' }}>@lang('After Amount')</option>
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
                                    <label for="exampleInputPassword1">@lang('Decimal Mark') <b class="ambitious-crimson">*</b></label>
                                    <div class="form-group input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-columns"></i></span>
                                        </div>
                                        <input class="form-control @error('decimal_mark') is-invalid @enderror" placeholder="@lang('Enter Decimal Mark')" required="required" name="decimal_mark" type="text" id="decimal_mark" value="{{ old('decimal_mark',$data->decimal_mark) }}">
                                        @error('decimal_mark')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="exampleInputPassword1">@lang('Thousands Separator') <b class="ambitious-crimson">*</b></label>
                                    <div class="form-group input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-columns"></i></span>
                                        </div>
                                        <input class="form-control @error('thousands_separator') is-invalid @enderror" placeholder="@lang('Enter Thousands Separator')" name="thousands_separator" type="text" id="thousands_separator" value="{{ old('thousands_separator',$data->thousands_separator) }}" required>
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
                                            <option value="1" {{ old('enabled', $data->enabled) == 1 ? 'selected' : '' }}>@lang('Yes')</option>
                                            <option value="0" {{ old('enabled', $data->enabled) == 0 ? 'selected' : '' }}>@lang('No')</option>
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
                                            <option value="1" @if($company->default_currency == $data->code) selected @endif>@lang('Yes')</option>
                                            <option value="0" @if($company->default_currency != $data->code) selected @endif>@lang('No')</option>
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
