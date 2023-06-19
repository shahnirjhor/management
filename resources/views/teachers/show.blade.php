@extends('layouts.layout')
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6"></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('scholarship-teacher.index') }}">@lang('Teacher')</a></li>
                    <li class="breadcrumb-item active">@lang('Teacher Info')</li>
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
                    @if($scholarshipTeacher->photo == NULL)
                    <img class="profile-user-img img-fluid img-circle" src="{{ asset('img/no_image.png') }}" alt="" />
                    @else
                    <img class="profile-user-img img-fluid img-circle" src="{{ asset($scholarshipTeacher->photo) }}" alt="" />
                    @endif
                </div>
                <h3 class="profile-username text-center">{{ $scholarshipTeacher->name }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-9">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">@lang('Teacher Info')</h3>
                @can('school-teacher')
                    <div class="card-tools">
                        <a href="{{ route('scholarship-teacher.edit', $scholarshipTeacher) }}" class="btn btn-info">@lang('Edit')</a>
                    </div>
                @endcan
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="phone">@lang('Email')</label>
                            <p>{{ $scholarshipTeacher->email}}</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="type">@lang('Institution')</label>
                            @if($scholarshipTeacher->school_or_college == '1')
                                <p>School</p>
                            @else
                                <p>College</p>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            @if($scholarshipTeacher->school_or_college == '1')
                            <label for="type">@lang('School Name')</label>
                            @else
                            <label for="type">@lang('College Name')</label>
                            @endif
                            <p>{{ $info->name }}</p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="phone">@lang('Phone')</label>
                            <p>{{ $scholarshipTeacher->phone}}</p>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="phone">@lang('Address')</label>
                            <p>{!! $scholarshipTeacher->address!!}</p>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="phone">@lang('Date Of Birth')</label>
                            <p>{{ $scholarshipTeacher->date_of_birth}}</p>
                        </div>
                    </div>


                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="phone">@lang('Gender')</label>
                            <p>{{ ucfirst($scholarshipTeacher->gender) }}</p>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="phone">@lang('Blood Group')</label>
                            <p>{{ $scholarshipTeacher->blood_group }}</p>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="status">@lang('Status') :  </label>
                            <p>
                            @if($scholarshipTeacher->status == 1)
                                <span class="badge badge-pill badge-success">@lang('Active')</span>
                            @else
                                <span class="badge badge-pill badge-danger">@lang('Inactive')</span>
                            @endif
                        </p>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>
</div>
@endsection
