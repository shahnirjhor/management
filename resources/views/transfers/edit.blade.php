@extends('layouts.layout')
@section('one_page_js')
    <script src="{{ asset('js/quill.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
@endsection

@section('one_page_css')
    <link href="{{ asset('css/quill.snow.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
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
                        <a href="{{ route('transfer.index') }}">@lang('Transfers List')</a></li>
                    <li class="breadcrumb-item active">@lang('Edit Transfer')</li>
                </ol>
            </div>
        </div>
    </div>
</section>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">@lang('Edit Transfer')</h3>
            </div>
            <div class="card-body">
                <form class="form-material form-horizontal" action="{{ route('transfer.update', $transfer) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="from_account">@lang('From Account') <b class="ambitious-crimson">*</b></label>
                              <div class="form-group input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-university"></i>
                                    </div>
                                    <select class="form-control ambitious-form-loading" name="from_account" id="from_account" required>
                                        <option value="">Select Account</option>
                                        @foreach ($accounts as $key => $value)
                                            <option value="{{ $key }}" {{ old('from_account', $transfer->from_account_id) == $key ? 'selected' : '' }}>{{ $value }}</option>
                                        @endforeach
                                    </select>
                              </div>
                            </div>
                            <div class="col-md-6">
                                <label for="to_account">@lang('To Account') <b class="ambitious-crimson">*</b></label>
                              <div class="form-group input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-university"></i>
                                    </div>
                                    <select class="form-control ambitious-form-loading" name="to_account" id="to_account" required>
                                        <option value="">Select Account</option>
                                        @foreach ($accounts as $key => $value)
                                            <option value="{{ $key }}" {{ old('to_account', $transfer->to_account_id) == $key ? 'selected' : '' }}>{{ $value }}</option>
                                        @endforeach
                                    </select>
                              </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="amount">@lang('Amount') <b class="ambitious-crimson">*</b></label>
                                <div class="form-group input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-hand-holding-usd"></i>
                                    </div>
                                    <input type="text" name="amount" id="amount" class="form-control" value="{{ old('amount',$transfer->amount) }}" placeholder="@lang('Enter Amount')" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="date">@lang('Date') <b class="ambitious-crimson">*</b></label>
                                <div class="form-group input-group mb-3">
                                    <div class="input-group-prepend">
                                      <span class="input-group-text"><i class="fas fa-calendar"></i>
                                    </div>
                                    <input type="text" name="date" id="date" class="form-control dateTime-flatpickr" value="{{ old('date', $transfer->transferred_at) }}" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="payment_method">@lang('Payment Method') <b class="ambitious-crimson">*</b></label>
                                <div class="form-group input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="far fa-credit-card"></i>
                                    </div>
                                    <select class="form-control ambitious-form-loading" name="payment_method" id="payment_method" required>
                                        @foreach ($payment_methods as $key => $value)
                                            <option value="{{ $key }}" {{ old('payment_method', $transfer->payment_method) == $key ? 'selected' : '' }}>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="reference">@lang('Reference')</label>
                                <div class="form-group input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-align-left"></i>
                                    </div>
                                    <input type="text" id="reference" name="reference" class="form-control" value="{{ old('reference', $transfer->reference) }}" placeholder="@lang('Enter Reference Number')" >
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label class="col-md-12 col-form-label"><h4>@lang('Description')</h4></label>
                            <div class="col-md-12">
                                <div id="input_description" class="@error('description') is-invalid @enderror" style="min-height: 55px;">
                                </div>
                                <input type="hidden" name="description" value="{{ old('description', $transfer->description) }}" id="description">
                                @error('description')
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
                            <input type="submit" value="@lang('Update')" class="btn btn-outline btn-info btn-lg"/>
                            <a href="{{ route('transfer.index') }}" class="btn btn-outline btn-warning btn-lg">@lang('Cancel')</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@include('script.transfer.edit.js')
@endsection
