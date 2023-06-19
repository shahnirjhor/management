@extends('layouts.layout')
@section('one_page_js')
    <script src="{{ asset('js/quill.js') }}"></script>
    <script src="{{ asset('plugins/dropify/dist/js/dropify.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
@endsection

@section('one_page_css')
    <link href="{{ asset('css/quill.snow.css') }}" rel="stylesheet">
    <link href="{{ asset('plugins/dropify/dist/css/dropify.min.css') }}" rel="stylesheet">
    <link href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

@endsection
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3>{{ __('General Setting') }}</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">@lang('Dashboard')</a></li>
                    <li class="breadcrumb-item active">{{ __('Settings') }}</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<div class="card">
	<nav>
        <div class="nav nav-tabs" id="nav-tab" role="tablist">
            <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">{{ __('Company') }}</a>
            <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">{{ __('Localisation') }}</a>
            <a class="nav-item nav-link" id="nav-contact-tab" data-toggle="tab" href="#nav-defaults" role="tab" aria-controls="nav-defaults" aria-selected="false">{{ __('Defaults') }}</a>
        </div>
    </nav>
	<div class="card-body" style="padding-top : 0">
		<section id="tabs" class="project-tab">
            <div class="row">
                <div class="col-md-12">
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                            <div class="card-body">
                                <form class="form-material form-horizontal" action="{{ route('general') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="exampleInputPassword1">{{ __('Company') }} <b class="ambitious-crimson">*</b></label>
                                                <div class="form-group input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="far fa-address-card"></i></span>
                                                    </div>
                                                    <input type="text" name="company_name" class="form-control @error('company_name') is-invalid @enderror" placeholder="{{ __('Enter Company Name') }}" value="{{ old('company_name',$company->company_name) }}" required>
                                                    @error('company_name')
                                                        <div class="invalid-feedback">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="exampleInputPassword1">@lang('Email') <b class="ambitious-crimson">*</b></label>
                                                <div class="form-group input-group mb-3">
                                                    <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                                    </div>
                                                    <input type="text" name="company_email" class="form-control @error('company_email') is-invalid @enderror" placeholder="{{ __('Enter Email Address') }}" value="{{ old('company_email',$company->company_email) }}" required>
                                                    @error('company_email')
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
                                                <label for="exampleInputPassword1">{{ __('Tax Number') }}</label>
                                                <div class="form-group input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-percentage"></i></span>
                                                    </div>
                                                    <input type="text" name="company_tax_number" class="form-control @error('company_tax_number') is-invalid @enderror" placeholder="{{ __('Enter Tax Number') }}" value="{{ old('company_tax_number',$company->company_tax_number) }}">
                                                    @error('company_tax_number')
                                                        <div class="invalid-feedback">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="exampleInputPassword1">@lang('Phone')</label>
                                                <div class="form-group input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                                    </div>
                                                    <input type="text" name="company_phone" class="form-control @error('company_phone') is-invalid @enderror" placeholder="{{ __('Enter Phone Number') }}" value="{{ old('company_phone',$company->company_phone) }}">
                                                    @error('company_phone')
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
                                                <label>@lang('Logo')</label>
                                                <input id="photo" class="dropify" name="company_logo" type="file" data-allowed-file-extensions="png jpg jpeg" data-max-file-size="1024K"/>
                                                <small id="name" class="form-text text-muted">@lang('Leave Blank For Remain Unchanged')</small>
                                                <p>@lang('Max Size: 1000kb, Allowed Format: png, jpg, jpeg')</p>
                                                @error('company_logo')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                            <div class="col-md-6">
                                                <label>@lang('Address') <b class="ambitious-crimson">*</b></label>
                                                <div id="edit_input_address" class="form-control @error('company_address') is-invalid @enderror" style="max-height: 55px;"></div>
                                                <input type="hidden" name="company_address" id="company_address" value="{{ old('company_address',$company->company_address) }}">
                                                @error('company_address')
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
                                            @can('company-update')
                                                <input type="submit" value="{{ __('Save') }}" class="btn btn-outline btn-info btn-lg"/>
                                            @endcan
                                            <a href="{{ route('dashboard') }}" class="btn btn-outline btn-warning btn-lg">@lang('Cancel')</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                            <div class="card-body">
                                <form class="form-material form-horizontal" action="{{ route('general.localisation') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="exampleInputPassword1">{{ __('Financial Year Start') }} </label>
                                                <div class="form-group input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="far fa-calendar-check"></i></span>
                                                    </div>
                                                    <input type="text" name="financial_start" id="financial_start" class="form-control @error('financial_start') is-invalid @enderror" placeholder="{{ __('Enter Financial Year Start') }}" value="{{ old('financial_start',$company->financial_start) }}">
                                                    @error('financial_start')
                                                        <div class="invalid-feedback">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="exampleInputPassword1">@lang('Time Zone')</label>
                                                <div class="form-group input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-globe"></i></span>
                                                    </div>
                                                    <select id="timezone" name="timezone" class="form-control @error('timezone') is-invalid @enderror">
                                                        @foreach($timezone as $key => $value)
                                                            <option value="{{ $key }}" {{ old('timezone', $company->timezone) == $key ? 'selected' : '' }} >{{ $value }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('timezone')
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
                                                <label for="exampleInputPassword1">{{ __('Date Format') }}</label>
                                                <div class="form-group input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                                    </div>
                                                    <select class="form-control @error('date_format') is-invalid @enderror" autocomplete="off" id="date_format" name="date_format">
                                                        <option value="d M Y" {{ old('date_format', $company->date_format) == "d M Y" ? 'selected' : '' }}>{{ date('d M Y') }}</option>
                                                        <option value="d F Y" {{ old('date_format', $company->date_format) == "d F Y" ? 'selected' : '' }}>{{ date('d F Y') }}</option>
                                                        <option value="d m Y" {{ old('date_format', $company->date_format) == "d m Y" ? 'selected' : '' }}>{{ date('d m Y') }}</option>
                                                        <option value="m d Y" {{ old('date_format', $company->date_format) == "m d Y" ? 'selected' : '' }}>{{ date('m d Y') }}</option>
                                                        <option value="Y m d" {{ old('date_format', $company->date_format) == "Y m d" ? 'selected' : '' }}>{{ date('Y m d') }}</option>
                                                    </select>
                                                    @error('date_format')
                                                        <div class="invalid-feedback">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label>{{ __('Date Separator') }}</label>
                                                <div class="form-group input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-minus"></i></span>
                                                    </div>
                                                    <select class="form-control @error('date_separator') is-invalid @enderror" id="date_separator" name="date_separator">
                                                        <option value="dash" {{ old('date_separator', $company->date_separator) == "dash" ? 'selected' : '' }}>{{ __('Dash') }} (-)</option>
                                                        <option value="slash" {{ old('date_separator', $company->date_separator) == "slash" ? 'selected' : '' }}>{{ __('Slash') }} (/)</option>
                                                        <option value="dot" {{ old('date_separator', $company->date_separator) == "dot" ? 'selected' : '' }}>{{ __('Dot') }} (.)</option>
                                                        <option value="comma" {{ old('date_separator', $company->date_separator) == "comma" ? 'selected' : '' }}>{{ __('Comma') }} (,)</option>
                                                        <option value="space" {{ old('date_separator', $company->date_separator) == "space" ? 'selected' : '' }}>{{ __('Space') }} ( )</option>
                                                    </select>
                                                    @error('date_separator')
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
                                                <label for="exampleInputPassword1">{{ __('Percent (%) Position') }}</label>
                                                <div class="form-group input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-percentage"></i></span>
                                                    </div>
                                                    <select class="form-control @error('percent_position') is-invalid @enderror" id="percent_position" name="percent_position">
                                                        <option value="before" {{ old('percent_position', $company->percent_position) == "before" ? 'selected' : '' }}>{{ __('Before Number') }}</option>
                                                        <option value="after" {{ old('percent_position', $company->percent_position) == "after" ? 'selected' : '' }}>{{ __('After Number') }}</option>
                                                    </select>
                                                    @error('percent_position')
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
                                            @can('company-update')
                                                <input type="submit" value="{{ __('Save') }}" class="btn btn-outline btn-info btn-lg"/>
                                            @endcan
                                            <a href="{{ route('dashboard') }}" class="btn btn-outline btn-warning btn-lg">@lang('Cancel')</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">
                            <div class="card-body">
                                <form class="form-material form-horizontal" action="{{ route('general.invoice') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="exampleInputPassword1">{{ __('Number Prefix') }} </label>
                                              <div class="form-group input-group mb-3">
                                                  <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-font"></i></span>
                                                  </div>
                                                  <input type="text" name="invoice_number_prefix" id="invoice_number_prefix" class="form-control" placeholder="{{ __('Enter Number Prefix') }}" value="{{ $company->invoice_number_prefix ?? null }}">
                                              </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="exampleInputPassword1">{{ __('Number Digit') }}</label>
                                              <div class="form-group input-group mb-3">
                                                  <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-text-width"></i></span>
                                                  </div>
                                                  <input type="text" name="invoice_number_digit" id="invoice_number_digit" class="form-control" placeholder="{{ __('Enter Number Digit') }}" value="{{ $company->invoice_number_digit ?? null }}">
                                              </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="exampleInputPassword1">{{ __('Next Number') }}</label>
                                              <div class="form-group input-group mb-3">
                                                  <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-chevron-right"></i></span>
                                                  </div>
                                                  <input type="text" name="invoice_number_next" id="invoice_number_next" class="form-control" placeholder="{{ __('Enter Next Number') }}" value="{{ $company->invoice_number_next ?? null }}">
                                              </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label>{{ __('Item Name') }}</label>
                                                <div class="form-group input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-font"></i></span>
                                                    </div>
                                                    <select class="form-control @error('invoice_item') is-invalid @enderror" autocomplete="off" id="invoice_item" name="invoice_item">
                                                    @foreach($itemNames as $key => $value)
                                                        <option value="{{ $key }}" {{ old('invoice_item', $company->invoice_item) == $key ? 'selected' : '' }}>{{ ucwords(str_replace('settings.invoice.', '', $value ?? null)) }}</option>
                                                    @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="exampleInputPassword1">{{ __('Price Name') }}</label>
                                                <div class="form-group input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-font"></i></span>
                                                    </div>
                                                    <select class="form-control @error('invoice_price') is-invalid @enderror" autocomplete="off" id="invoice_price" name="invoice_price">
                                                    @foreach($priceNames as $key => $value)
                                                        <option value="{{ $key }}" {{ old('invoice_price', $company->invoice_price) == $key ? 'selected' : '' }} >{{ ucwords(str_replace('settings.invoice.', '', $value ?? null)) }}</option>
                                                    @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="exampleInputPassword1">{{ __('Quantity Name') }}</label>
                                                <div class="form-group input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-font"></i></span>
                                                    </div>
                                                    <input type="text" name="invoice_quantity" id="invoice_quantity" class="form-control" placeholder="{{ __('Enter Quantity Name') }}" value="{{ str_replace('settings.invoice.', '', $company->invoice_quantity ?? null) }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="send_item_reminder">{{ __('Send Item Reminder') }}</label>
                                                <div class="form-group input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-clock"></i></span>
                                                    </div>
                                                    <select class="form-control @error('send_item_reminder') is-invalid @enderror" autocomplete="off" id="send_item_reminder" name="send_item_reminder">
                                                        <option value="1" {{ old('send_item_reminder', $company->send_item_reminder) =='1' ? 'selected' : '' }} >Yes</option>
                                                        <option value="0" {{ old('send_item_reminder', $company->send_item_reminder) == '0' ? 'selected' : '' }} >No</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="send_item_reminder">{{ __('Send When Item Stock') }}</label>
                                                <div class="form-group input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-layer-group"></i></span>
                                                    </div>
                                                    <input type="text" name="schedule_item_stocks" id="schedule_item_stocks" class="form-control" placeholder="{{ __('Enter Schedule Item Stocks') }}" value="{{ $company->schedule_item_stocks ?? null }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>@lang('Logo')</label>

                                                <input id="photo1" class="dropify" name="invoice_logo" type="file" data-allowed-file-extensions="png jpg jpeg" data-max-file-size="1024K"/>
                                                <small id="name" class="form-text text-muted">@lang('Leave Blank For Remain Unchanged')</small>
                                                <p>@lang('Max Size: 1000kb, Allowed Format: png, jpg, jpeg')</p>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 col-form-label"></label>
                                        <div class="col-md-8">
                                            @can('company-update')
                                                <input type="submit" value="{{ __('Save') }}" class="btn btn-outline btn-info btn-lg"/>
                                            @endcan
                                            <a href="{{ route('dashboard') }}" class="btn btn-outline btn-warning btn-lg">@lang('Cancel')</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="nav-bill" role="tabpanel" aria-labelledby="nav-bill-tab">
                            <div class="card-body">
                                <form class="form-material form-horizontal" action="{{ route('general.bill') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>{{ __('Number Prefix') }} </label>
                                                <div class="form-group input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-font"></i></span>
                                                    </div>
                                                    <input type="text" name="bill_number_prefix" id="bill_number_prefix" class="form-control" placeholder="{{ __('Enter Number Prefix') }}" value="{{ $company->bill_number_prefix ?? null }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label>{{ __('Number Digit') }}</label>
                                                <div class="form-group input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-text-width"></i></span>
                                                    </div>
                                                    <input type="text" name="bill_number_digit" id="bill_number_digit" class="form-control" placeholder="{{ __('Enter Number Digit') }}" value="{{ $company->bill_number_digit ?? null }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>{{ __('Next Number') }}</label>
                                                <div class="form-group input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-chevron-right"></i></span>
                                                    </div>
                                                    <input type="text" name="bill_number_next" id="bill_number_next" class="form-control" placeholder="{{ __('Enter Next Number') }}" value="{{ $company->bill_number_next ?? null }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label>{{ __('Item Name') }}</label>
                                                <div class="form-group input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-font"></i></span>
                                                    </div>
                                                    <select class="form-control @error('bill_item') is-invalid @enderror" autocomplete="off" id="bill_item" name="bill_item">
                                                        @foreach($itemNames as $key => $value)
                                                            <option value="{{ $key }}" {{ old('bill_item', $company->bill_item) == $key ? 'selected' : '' }}>{{ ucwords(str_replace('settings.invoice.', '', $value ?? null)) }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 col-form-label"></label>
                                        <div class="col-md-8">
                                            @can('company-update')
                                                <input type="submit" value="{{ __('Save') }}" class="btn btn-outline btn-info btn-lg"/>
                                            @endcan
                                            <a href="{{ route('dashboard') }}" class="btn btn-outline btn-warning btn-lg">@lang('Cancel')</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="nav-defaults" role="tabpanel" aria-labelledby="nav-defaults-tab">
                            <div class="card-body">
                                <form class="form-material form-horizontal" action="{{ route('general.defaults') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="exampleInputPassword1">{{ __('Default Account') }} </label>
                                              <div class="form-group input-group mb-3">
                                                  <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-university"></i></span>
                                                  </div>

                                                  <select id="default_account" name="default_account" class="form-control">
                                                  @foreach($company->accounts as $value)
                                                       <option value="{{ $value->id }}" @if ($company->default_account == $value->id) selected="selected" @endif >{{ $value->name }}</option>
                                                   @endforeach
                                                  </select>
                                              </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label>@lang('Default Currency')</label>
                                              <div class="form-group input-group mb-3">
                                                  <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-exchange-alt"></i></span>
                                                  </div>
                                                  <select id="default_currency" name="default_currency" class="form-control">
                                                  @foreach($company->currencies as $value)
                                                       <option value="{{ $value->id }}" @if ($company->default_currency == $value->id) selected="selected" @endif >{{ $value->name }}</option>
                                                   @endforeach
                                                  </select>
                                              </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>{{ __('Default Tax Rate') }}</label>
                                                <div class="form-group input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-chevron-right"></i></span>
                                                    </div>
                                                    <select id="default_tax" name="default_tax" class="form-control">
                                                    @foreach($company->taxes as $value)
                                                        <option value="{{ $value->id }}" @if ($company->default_tax == $key) selected="selected" @endif >{{ $value->name }}</option>
                                                    @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label>{{ __('Default Payment Method') }}</label>
                                                <div class="form-group input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-credit-card"></i></span>
                                                    </div>
                                                   <select id="default_payment_method" name="default_payment_method" class="form-control">
                                                       <option value="offlinepayment.cash" @if ($company->default_payment_method == 'offlinepayment.cash') selected="selected" @endif >{{ __('Cash') }}</option>
                                                       <option value="offlinepayment.bank_transfer" @if ($company->default_payment_method == 'offlinepayment.bank_transfer') selected="selected" @endif >{{ __('Bank Transfer') }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="exampleInputPassword1">{{ __('Default Language') }}</label>
                                              <div class="form-group input-group mb-3">
                                                  <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-flag"></i></span>
                                                  </div>
                                                  <select class="form-control ambitious-form-loading" name="default_locale" id="default_locale">
                                                    @php
                                                        $defaultLang = env('LOCALE_LANG', 'en');
                                                    @endphp
                                                    @foreach($getLang as $key => $value)
                                                        <option value="{{ $key }}" @if ($defaultLang == $key) selected="selected" @endif >{{ $value }}</option>
                                                    @endforeach
                                                </select>
                                              </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 col-form-label"></label>
                                        <div class="col-md-8">
                                            @can('company-update')
                                                <input type="submit" value="{{ __('Save') }}" class="btn btn-outline btn-info btn-lg"/>
                                            @endcan
                                            <a href="{{ route('dashboard') }}" class="btn btn-outline btn-warning btn-lg">@lang('Cancel')</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
        </section>
    </div>
</div>

@include('script.general.js')

<script>
    $(document).ready( function () {

        if($('#sku_type').val() == '1') {
            $('#sku_random').show(500);
            $('#sku_define').hide(500);
        } else {
            $('#sku_random').hide(500);
            $('#sku_define').show(500);
        }
        $('#sku_type').change(function(){
            if($('#sku_type').val() == '1') {
                $('#sku_random').show(500);
                $('#sku_define').hide(500);
            } else {
                $('#sku_random').hide(500);
                $('#sku_define').show(500);
            }
        });
    });
</script>

@endsection
