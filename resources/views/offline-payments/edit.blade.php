@extends('layouts.layout')
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('offline-payment.index') }}">@lang('Offline Payments List')</a></li>
                    <li class="breadcrumb-item active">@lang('Edit Offline Payment')</li>
                </ol>
            </div>
        </div>
    </div>
</section>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">@lang('Edit Offline Payment')</h3>
            </div>
            <div class="card-body">
                <form class="form-material form-horizontal" action="{{ route('offline-payment.update', $offlinePayment) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="name">@lang('Name') <b class="ambitious-crimson">*</b></label>
                                <div class="form-group input-group mb-3">
                                    <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-money-check-alt"></i></span>
                                    </div>
                                    <input type="text" name="name" value="{{ old('name', $offlinePayment->name) }}" class="form-control @error('name') is-invalid @enderror" placeholder="@lang('Enter Name')" required>
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
                                    <span class="input-group-text"><i class="fas fa-key"></i></span>
                                    </div>
                                    <input type="text" name="code" value="{{ old('code', $offlinePayment->code) }}" class="form-control @error('code') is-invalid @enderror" placeholder="@lang('Enter Code')" required>
                                    @error('code')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="order">@lang('Order')</label>
                                <div class="form-group input-group mb-3">
                                    <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fa fa-sort"></i></span>
                                    </div>
                                    <input type="text" name="order" value="{{ old('order', $offlinePayment->order) }}" class="form-control @error('order') is-invalid @enderror" placeholder="@lang('Enter Order')">
                                    @error('order')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="show_to_customer">@lang('Show to Customer') <b class="ambitious-crimson">*</b></label>
                                <div class="form-group input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-bell"></i></span>
                                    </div>
                                    <select class="form-control ambitious-form-loading @error('show_to_customer') is-invalid @enderror" required="required" name="show_to_customer" id="show_to_customer">
                                        <option value="1" {{ old('show_to_customer', $offlinePayment->show_to_customer) == 1 ? 'selected' : '' }}>@lang('Yes')</option>
                                        <option value="0" {{ old('show_to_customer', $offlinePayment->show_to_customer) == 0 ? 'selected' : '' }}>@lang('No')</option>
                                    </select>
                                    @error('show_to_customer')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label class="col-md-12 col-form-label"><h4>@lang('Description')</h4></label>
                                <div class="col-md-12">
                                    <div id="input_description" class="@error('description') is-invalid @enderror" style="min-height: 55px;">
                                    </div>
                                    <input type="hidden" name="description" value="{{ old('description', $offlinePayment->description) }}" id="description">
                                    @error('description')
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
                            <input type="submit" value="@lang('Update')" class="btn btn-outline btn-info btn-lg"/>
                            <a href="{{ route('offline-payment.index') }}" class="btn btn-outline btn-warning btn-lg">@lang('Cancel')</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function() {
    "use strict";

    var quill = new Quill('#input_description', {
        theme: 'snow'
    });

    var description = $("#description").val();
    quill.clipboard.dangerouslyPasteHTML(description);
    quill.root.blur();
    $('#input_description').on('keyup', function(){
        var input_description = quill.container.firstChild.innerHTML;
        $("#description").val(input_description);
    });
});
</script>
@endsection