@extends('layouts.layout')
@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h3><a href="{{ route('smtp.create') }}" class="btn btn-outline btn-info">+ @lang('Add New Smtp')</a></h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">@lang('Dashboard')</a></li>
                    <li class="breadcrumb-item active">@lang('Smtp List')</li>
                </ol>
            </div>
        </div>
    </div>
</section>

@include('partials.errors')

<div class="row">
    <div class="col-12">

      <div class="card">
        <div class="card-header">
          <h3 class="card-title">@lang('SMTP Configrution')</h3>
        </div>
        <div class="card-body">
            <table id="laravel_datatable" class="table table-striped compact table-width">
                <thead>
                    <tr>
                        <th>@lang('Id')</th>
                        <th>@lang('Email')</th>
                        <th>@lang('Host')</th>
                        <th>@lang('Port')</th>
                        <th>@lang('User')</th>
                        <th>@lang('Password')</th>
                        <th>@lang('Type')</th>
                        <th>@lang('Status')</th>
                        <th data-orderable="false">@lang('Actions')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($lists as $list)
                        <tr>
                            <td>{{ $list->id }}</td>
                            <td>{{ $list->email_address }}</td>
                            <td>{{ $list->smtp_host }}</td>
                            <td>{{ $list->smtp_port }}</td>
                            <td>{{ $list->smtp_user }}</td>
                            <td>{{ $list->smtp_password }}</td>
                            <td>
                                @if($list->smtp_type == 'ssl')
                                    <span class="badge badge-pill badge-info">@lang('Ssl')</span>
                                @elseif($list->smtp_type == 'tls')
                                    <span class="badge badge-pill badge-success">@lang('Tls')</span>
                                @else
                                    <span class="badge badge-pill badge-secondary">@lang('Default')</span>
                                @endif
                            </td>
                            <td>
                                @if($list->status == 0)
                                    <span class="badge badge-pill badge-secondary">@lang('Inactive')</span>
                                @else
                                    <span class="badge badge-pill badge-primary">@lang('Active')</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('smtp.edit', $list) }}" class="btn btn-info btn-outline btn-circle btn-lg" data-toggle="tooltip" title="@lang('Edit')"><i class="fa fa-edit ambitious-padding-btn"></i></a>&nbsp;&nbsp;
                                <a href="#" data-href="{{ route('smtp.destroy', $list) }}" class="btn btn-info btn-outline btn-circle btn-lg" data-toggle="modal" data-target="#myModal" title="@lang('Delete')"><i class="fa fa-trash ambitious-padding-btn"></i></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $lists->links() }}
        </div>
      </div>
    </div>
</div>

@include('layouts.delete_modal')
@include('script.smtp.js')
@endsection
