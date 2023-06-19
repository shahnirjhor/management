@extends('layouts.layout')
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('roles.index') }}">{{ __('Role List') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('Update Role') }}</li>
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
                <h3>{{ __('Update Role') }}</h3>
            </div>
            <div class="card-body">
                <form class="form-material form-horizontal" action="{{ route('roles.update', $role) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label ambitious-center"><h4>@lang('Name') <b class="ambitious-crimson">*</b></h4></label>
                        <div class="col-md-8">
                            <input class="form-control ambitious-form-loading @error('name') is-invalid @enderror" name="name" id="name" type="text" placeholder="{{ __('Role Name') }}" value="{{ old('name', $role->name) }}" >
                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label ambitious-center"><h4>{{ __('Role For') }}</h4></label>
                        <div class="col-md-8">
                            <select class="form-control ambitious-form-loading @error('role_for') is-invalid @enderror" name="role_for" id="role_for">
                                <option value="1" {{ old('role_for', $role->role_for) == 1 ? 'selected' : ''  }}>@lang('General User')</option>
                                {{--  <option value="0" {{ old('role_for', $role->role_for) == 0 ? 'selected' : ''  }}>@lang('System User')</option>  --}}
                            </select>
                            @error('role_for')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div id="user_block">
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label ambitious-center"><h4 class="ambitious-role-margin">{{ __('Price') }} <b class="ambitious-crimson">*</b></h4></label>
                            <div class="col-md-8">
                                <input class="form-control ambitious-form-loading @error('price') is-invalid @enderror" name="price" id="price" type="text" placeholder="{{ __('Role Price') }}" value="{{ old('price', $role->price) }}">
                                @error('price')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label ambitious-center"><h4 class="ambitious-role-margin">{{ __('Validity') }} <b class="ambitious-crimson">*</b></h4></label>
                            <div class="col-md-8">
                                <input class="form-control ambitious-form-loading @error('validity') is-invalid @enderror" name="validity" id="validity" type="text" placeholder="{{ __('Validity Day') }}" value="{{ old('validity', $role->validity) }}" >
                                @error('validity')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label ambitious-center"><h4>@lang('Permissions') <b class="ambitious-crimson">*</b></h4></label>
                        <div class="col-md-10">
                            <div class="form-control-plaintext">
                                @php
                                    $lastName = '';
                                @endphp

                                @foreach($permissions as $permission)
                                    @if($lastName != $permission->display_name)
                                        @php
                                            $lastName = $permission->display_name;
                                            // dd($lastName);
                                        @endphp
                                        <div role="checkbox" style="padding-top: 5px;">
                                            <h4 class="ambitious-role-margin-extra">{{ $lastName }}</h4>
                                        </div>
                                    @endif

                                    @php
                                        $pname = explode("-", $permission->name);
                                        $display = end($pname);
                                    @endphp

                                    @if($display == 'read' && $lastName == 'Reports')
                                        <div class="role-form-ambi checkbox checkbox-info">
                                            <input name="permission[]" id="permission_{{ $permission->id }}" type="checkbox" value="{{ $permission->id }}" @if(is_array(old('permission', $rolePermissions)) && in_array($permission->id, old('permission', $rolePermissions))) checked @endif>
                                            <label class="ambitious-capital" for="permission_{{ $permission->id }}">
                                                @php
                                                    $pname = explode("-", $permission->name);
                                                    $nArray = array_diff( $pname, ['read'] );
                                                    $nStr = implode(" ", $nArray);
                                                @endphp
                                                {{ $nStr }}
                                            </label>
                                        </div>
                                    @endif

                                    @if($display == 'read' && $lastName != 'Reports')
                                        <div class="role-form-ambi checkbox checkbox-info">
                                            <input name="permission[]" id="permission_{{ $permission->id }}" type="checkbox" value="{{ $permission->id }}" @if(is_array(old('permission', $rolePermissions)) && in_array($permission->id, old('permission', $rolePermissions))) checked @endif>
                                            <label class="ambitious-capital" for="permission_{{ $permission->id }}">
                                                {{ $display }}
                                            </label>
                                        </div>
                                    @endif

                                    @if($display == 'create')
                                        <div class="role-form-ambi checkbox checkbox-primary">
                                            <input name="permission[]" id="permission_{{ $permission->id }}" type="checkbox" value="{{ $permission->id }}" @if(is_array(old('permission', $rolePermissions)) && in_array($permission->id, old('permission', $rolePermissions))) checked @endif>
                                            <label class="ambitious-capital" for="permission_{{ $permission->id }}">
                                                {{ $display }}
                                            </label>
                                        </div>
                                    @endif

                                    @if($display == 'update')
                                        <div class="role-form-ambi checkbox checkbox-warning">
                                            <input name="permission[]" id="permission_{{ $permission->id }}" type="checkbox" value="{{ $permission->id }}" @if(is_array(old('permission', $rolePermissions)) && in_array($permission->id, old('permission', $rolePermissions))) checked @endif>
                                            <label class="ambitious-capital" for="permission_{{ $permission->id }}">
                                                {{ $display }}
                                            </label>
                                        </div>
                                    @endif

                                    @if($display == 'delete')
                                        <div class="role-form-ambi checkbox checkbox-danger">
                                            <input name="permission[]" id="permission_{{ $permission->id }}" type="checkbox" value="{{ $permission->id }}" @if(is_array(old('permission', $rolePermissions)) && in_array($permission->id, old('permission', $rolePermissions))) checked @endif>
                                            <label class="ambitious-capital" for="permission_{{ $permission->id }}">
                                                {{ $display }}
                                            </label>
                                        </div>
                                    @endif
                                    @if ($display == 'export')
                                        <div class="role-form-ambi checkbox checkbox-info">
                                            <input name="permission[]" id="permission_{{ $permission->id }}" type="checkbox" value="{{ $permission->id }}" @if(is_array(old('permission',$rolePermissions)) && in_array($permission->id, old('permission', $rolePermissions))) checked @endif>
                                            <label class="ambitious-capital" for="permission_{{ $permission->id }}">
                                                {{ $display }}
                                            </label>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="form-group row mb-0">
                        <label class="col-md-2 col-form-label"></label>
                        <div class="col-md-8">
                            <input type="submit" value="@lang('Submit')" class="btn btn-outline btn-info btn-lg"/>
                        </div>
                    </div>
                    <br>
                </form>
            </div>
        </div>
    </div>
</div>
@include('script.roles.edit.js')

@endsection
