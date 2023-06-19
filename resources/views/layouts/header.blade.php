<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-dark navbar-info">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
        </li>
    </ul>
    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        {{--  <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="false">
                <i class="far fa-bell"></i>
                @if ($notifications)
                    <span class="badge badge-warning navbar-badge">{{ $notifications }}</span>
                @endif
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" style="left: inherit; right: 0px;">
                <span class="dropdown-item dropdown-header">{{ $notifications }} Notifications</span>
                <div class="dropdown-divider"></div>
                @if (count($notify_items))
                    <a href="{{ url('users/' . $user->id . '/read-items') }}" class="dropdown-item">
                        <i class="fas fa-envelope mr-2"></i> {{ trans_choice('header.notifications.items_stock', count($notify_items), ['count' => count($notify_items)]) }}
                    </a>
                @endif
                @if (count($notify_items_reminder))
                    <a href="{{ url('users/' . $user->id . '/read-items') }}" class="dropdown-item">
                        <i class="fas fa-envelope mr-2"></i> {{ trans_choice('header.notifications.items_reminder', count($notify_items_reminder), ['count' => count($notify_items_reminder)]) }}
                    </a>
                @endif
            </div>
        </li>  --}}

        <!-- Company Name -->
        <li class="nav-item dropdown nav-margin">

            <a class="dropdown-toggle profile-pic login_profile mr-2" data-toggle="dropdown" href="#">
                <img src="{{ asset($companyLogo) }}" alt="user-img" width="36" class="img-circle">
                <b id="ambitious-user-name-id" class="hidden-xs">{{ \Illuminate\Support\Str::limit($company_full_name, 20, '...') }}</b>
                <span class="caret"></span>
            </a>


            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <div class="dropdown-divider"></div>
                @foreach ($companySwitchingInfo as $key => $value)
                    <a href="{{ route('company.companyAccountSwitch', ['company_switch' => $key]  ) }}" class="dropdown-item" @if ($key == Session::get('companyInfo')) style="background-color : #ddd" @endif>
                        <i class="fas fa-building mr-2"></i> {{ \Illuminate\Support\Str::limit($value, 20, '...') }}
                    </a>
                    <div class="dropdown-divider"></div>
                @endforeach

                <div class="dropdown-divider"></div>
                @can('company-read')
                <a href="{{ route('company.index') }}" class="dropdown-item"><i class="fa fa-sliders-h mr-2"></i> @lang('Manage Organization')</a>
                @endcan
            </div>
        </li>


        <li class="nav-item dropdown">
            <?php
                if(Auth::user()->photo == NULL)
                {
                    $photo = "img/profile/male.png";
                } else {
                    $photo = Auth::user()->photo;
                }
            ?>

            <a class="dropdown-toggle profile-pic login_profile" data-toggle="dropdown" href="#">
                <img src="{{ asset($photo) }}" alt="user-img" width="36" class="img-circle">
                <b id="ambitious-user-name-id" class="hidden-xs">{{  strtok(Auth::user()->name, " ") }}</b>
                <span class="caret"></span>
            </a>


            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">

                <div class="dw-user-box">
                    <div class="u-img"><img src="{{ asset($photo) }}" alt="user" /></div>
                    <div class="u-text">
                        <h4>{{ Auth::user()->name }}</h4>
                        <p class="text-muted" style="padding-bottom: 5px;">{{ Auth::user()->email }}</p>
                        <a href="{{ route('profile.view') }}" class="btn btn-rounded btn-danger btn-sm">@lang('View Profile')</a>
                    </div>
                </div>
                <div class="dropdown-divider"></div>
                @can('profile-read')
                <a href="{{ route('profile.view') }}" class="dropdown-item">
                    <i class="fas fa-user mr-2"></i> @lang('My Profile')
                </a>
                @endcan
                @can('profile-update')
                <a href="{{ route('profile.setting') }}" class="dropdown-item">
                    <i class="fas fa-cogs mr-2"></i> @lang('Account Setting')
                </a>
                @endcan
                <a href="{{ route('profile.password') }}" class="dropdown-item">
                    <i class="fa fa-key mr-2"></i></i> @lang('Change Password')
                </a>
                <div class="dropdown-divider"></div>

                <a href="{{ route('logout') }}" class="dropdown-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fa fa-power-off mr-2"></i> @lang('Logout')</a>

                <form id="logout-form" class="ambitious-display-none" action="{{ route('logout') }}" method="POST">@csrf</form>
            </div>
        </li>
    </ul>
</nav>
<!-- /.navbar -->
