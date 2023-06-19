@extends('layouts.layout')
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6"></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('scholarship-school.index') }}">@lang('School')</a></li>
                    <li class="breadcrumb-item active">@lang('School Info')</li>
                </ol>
            </div>
        </div>
    </div>
</section>
<div class="row">
    <div class="col-md-3">
        <div class="card card-primary card-outline">
            <div class="card-body box-profile">
                <div class="text-center">
                    @if($scholarshipSchool->picture == NULL)
                    <img class="profile-user-img img-fluid img-circle" src="{{ asset('img/no_image.png') }}" alt="" />
                    @else
                    <img class="profile-user-img img-fluid img-circle" src="{{ asset($scholarshipSchool->picture) }}" alt="" />
                    @endif
                </div>
                <h3 class="profile-username text-center">{{ $scholarshipSchool->name }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-9">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">@lang('School Info')</h3>
                @can('school-update')
                    <div class="card-tools">
                        <a href="{{ route('scholarship-school.edit', $scholarshipSchool) }}" class="btn btn-info">@lang('Edit')</a>
                    </div>
                @endcan
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="phone">@lang('School Type')</label>
                            <p>{{ $scholarshipSchool->school_type}}</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="email">@lang('Email')</label>
                            <p>{{ $scholarshipSchool->email }}</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="email">@lang('Website')</label>
                            <p>{{ $scholarshipSchool->website }}</p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="phone">@lang('Village')</label>
                            <p>{{ $scholarshipSchool->scholarshipVillage->name}}</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="email">@lang('District')</label>
                            <p>{{ $scholarshipSchool->district }}</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="status">@lang('Status') :  </label>
                            @if($scholarshipSchool->status == 1)
                                <span class="badge badge-pill badge-success">@lang('Active')</span>
                            @else
                                <span class="badge badge-pill badge-danger">@lang('Inactive')</span>
                            @endif
                        </div>
                    </div>
                </div>
                @if(isset($scholarshipSchool->description) && !empty($scholarshipSchool->description))
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="description">@lang('Description')</label>
                            <p>{!! $scholarshipSchool->description !!}</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
