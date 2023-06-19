@extends('layouts.layout')
@section('one_page_js')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
@endsection

@section('one_page_css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endsection
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h3><a href="{{ route('bill.create') }}" class="btn btn-outline btn-info">+ @lang('Add New Bill')</a></h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">@lang('Dashboard')</a></li>
                    <li class="breadcrumb-item active">@lang('Bill List')</li>
                </ol>
            </div>
        </div>
    </div>
</section>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">@lang('Bills') </h3>
                <div class="card-tools">
                    <a class="btn btn-primary" target="_blank" href="{{ route('bill.index') }}?export=1">
                        <i class="fas fa-cloud-download-alt"></i> @lang('Export')
                    </a>
                    <button class="btn btn-default" data-toggle="collapse" href="#filter">
                        <i class="fas fa-filter"></i> @lang('Filter')
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div id="filter" class="collapse @if(request()->isFilterActive) show @endif">
                    <div class="card-body border">
                        <form action="" method="get" role="form" autocomplete="off">
                            <input type="hidden" name="isFilterActive" value="true">
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>@lang('Bill Number')</label>
                                        <input type="text" name="bill_number" class="form-control" value="{{ request()->bill_number }}" placeholder="@lang('Bill Number')">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>@lang('Bill Date')</label>
                                        <input type="text" name="billed_at" class="form-control flatpickr" value="{{ request()->billed_at }}" placeholder="@lang('Bill Date')">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>@lang('Amount')</label>
                                        <input type="text" name="amount" class="form-control" value="{{ request()->amount }}" placeholder="@lang('Amount')">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <button type="submit" class="btn btn-info">@lang('Submit')</button>
                                    @if(request()->isFilterActive)
                                        <a href="{{ route('bill.index') }}" class="btn btn-secondary">@lang('Clear')</a>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <table id="laravel_datatable" class="table table-striped compact table-width">
                    <thead>
                        <tr>
                            <th>@lang('Bill No')</th>
                            <th>@lang('Vendor')</th>
                            <th>@lang('Amount')</th>
                            <th>@lang('Bill Date')</th>
                            <th>@lang('Due Date')</th>
                            <th>@lang('Status')</th>
                            <th data-orderable="false">@lang('Actions')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($bills as $bill)
                        <tr>
                            <td>{{ $bill->bill_number }}</td>
                            <td>{{ $bill->vendor->name }}</td>
                            <td>@money($bill->amount, $bill->currency_code, true)</td>
                            <td>{{ date($company->date_format, strtotime($bill->billed_at)) }}</td>
                            <td>{{ date($company->date_format, strtotime($bill->due_at)) }}</td>
                            <td>
                                @php
                                    switch ($bill->bill_status_code) {
                                        case 'paid':
                                            $badge = 'badge badge-success';
                                            break;
                                        case 'delete':
                                            $badge = 'badge badge-danger';
                                            break;
                                        case 'partial':
                                        case 'sent':
                                            $badge = 'badge badge-warning';
                                            break;
                                        default:
                                            $badge = 'badge badge-primary';
                                            break;
                                    }
                                @endphp
                                    <span class="{{$badge}}">{{Str::ucfirst($bill->bill_status_code) }}</span>

                            </td>
                            <td>
                                <a href="{{ route('bill.show', $bill) }}" class="btn btn-info btn-outline btn-circle btn-lg" data-toggle="tooltip" title="View"><i class="fa fa-eye ambitious-padding-btn"></i></a>&nbsp;&nbsp;
                                <a href="{{ route('bill.edit', $bill) }}" class="btn btn-info btn-outline btn-circle btn-lg" data-toggle="tooltip" title="Edit"><i class="fa fa-edit ambitious-padding-btn"></i></a>&nbsp;&nbsp;
                                <a href="#" data-href="{{ route('bill.destroy', $bill) }}" class="btn btn-info btn-outline btn-circle btn-lg" data-toggle="modal" data-target="#myModal" title="Delete"><i class="fa fa-trash ambitious-padding-btn"></i></a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $bills->links() }}
            </div>
        </div>
    </div>
</div>
@include('layouts.delete_modal')
@include('script.bill.index.js')
@endsection
