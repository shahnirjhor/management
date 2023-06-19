@extends('layouts.layout')
@section('one_page_js')
    <!-- Color Picker -->
    <script src="{{ asset('plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js') }}"></script>
@endsection

@section('one_page_css')
    <!-- Color Picker -->
    <link href="{{ asset('plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css') }}" rel="stylesheet">
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
                        <a href="{{ route('category.index') }}">@lang('Category List')</a></li>
                    <li class="breadcrumb-item active">@lang('Edit Category')</li>
                </ol>
            </div>
        </div>
    </div>
</section>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">@lang('Edit Category')</h3>
            </div>
            <div class="card-body">
                <form class="form-material form-horizontal" action="{{ route('category.update', $category) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="exampleInputPassword1">@lang('Name') <b class="ambitious-crimson">*</b></label>
                                <div class="form-group input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-money-check-alt"></i>
                                    </div>
                                    <input type="text" name="name" value="{{ old('name', $category->name) }}" class="form-control" placeholder="@lang('Enter Category Name')" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="type">@lang('Type')</label>
                                <div class="form-group input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-weight"></i></span>
                                    </div>
                                    <select class="form-control" id="type" name="type">
                                            <option value="">- @lang('Select Type') -</option>
                                            @foreach($types as $key=> $value)
                                                <option value="{{ $key }}" {{ old('type', $category->type) == $key ? 'selected' : '' }}>{{ $value }}</option>
                                            @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="exampleInputPassword1">@lang('Color') <b class="ambitious-crimson">*</b></label>
                              <div class="form-group input-group mb-3">
                                  <div class="input-group-prepend">
                                    <span class="input-group-text color-id" id="color-id"><i class="fas fa-stop"></i></span>
                                  </div>
                                  <input id="color" class="form-control my-colorpicker" required="required" name="color" value="{{ old('color', $category->color) }}" type="text" value="#00a65a">
                                  <span class="color-id"></span>
                              </div>
                            </div>
                            <div class="col-md-6">
                                <label for="enabled">@lang('Enabled') <b class="ambitious-crimson">*</b></label>
                                <div class="form-group input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-bell"></i></span>
                                    </div>
                                    <select class="form-control ambitious-form-loading @error('enabled') is-invalid @enderror" required="required" name="enabled" id="enabled">
                                        <option value="1" {{ old('enabled', $category->enabled) == 1 ? 'selected' : '' }}>@lang('Yes')</option>
                                        <option value="0" {{ old('enabled', $category->enabled) == 0 ? 'selected' : '' }}>@lang('No')</option>
                                    </select>
                                    @error('enabled')
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
                            <a href="{{ route('category.index') }}" class="btn btn-outline btn-warning btn-lg">@lang('Cancel')</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@include('script.category.create.js')
@endsection
