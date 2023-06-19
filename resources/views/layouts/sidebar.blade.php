<!-- Main Sidebar Container -->
@php

$c = Request::segment(1);
$m = Request::segment(2);
$roleName = Auth::user()->getRoleNames();

@endphp

<aside class="main-sidebar elevation-4 sidebar-light-info">
    <!-- Brand Logo -->
    <a href="{{ route('dashboard')  }}" class="brand-link navbar-info">
        <img src="{{ asset('img/logo-text.png') }}" alt="{{ $ApplicationSetting->item_name }}" class="brand-image" style="opacity: .8; width :32px; height : 32px">
        <span class="brand-text font-weight-light">{{ $ApplicationSetting->item_short_name }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <?php
            if(Auth::user()->photo == NULL)
            {
                $photo = "img/profile/male.png";
            } else {
                $photo = Auth::user()->photo;
            }
        ?>
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ asset($photo) }}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info my-auto">
                {{ Auth::user()->name }}
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link @if($c == 'dashboard') active @endif">
                        <i class="nav-icon fas fa-chart-line"></i>
                        <p>@lang('Dashboard')</p>
                    </a>
                </li>


                @canany(['student-read', 'student-create', 'student-update', 'student-delete'])
                    <li class="nav-item">
                        <a href="{{ route('student.studentIndex') }}" class="nav-link @if($c == 'student' && $m='studentIndex') active @endif ">
                            <i class="fa fa-users nav-icon"></i>
                            <p>@lang('Student')</p>
                        </a>
                    </li>
                @endcan

                @canany(['customer-read', 'customer-create', 'customer-update', 'customer-delete'])
                <li class="nav-item has-treeview @if($c == 'customer' || $c == 'invoice' || $c == 'revenue') menu-open @endif">
                    <a href="javascript:void(0)" class="nav-link @if($c == 'customer' || $c == 'invoice' || $c == 'revenue') active @endif">
                        <i class="nav-icon fas fa-plus"></i>
                        <p>
                            @lang('Incomes')
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('invoice.index') }}" class="nav-link @if($c == 'invoice') active @endif ">
                                <i class="fas fa-file-invoice-dollar nav-icon"></i>
                                <p>@lang('Invoice')</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('revenue.index') }}" class="nav-link @if($c == 'revenue') active @endif ">
                                <i class="fas fa-hand-holding-usd nav-icon"></i>
                                <p>@lang('Revenue')</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('customer.index') }}" class="nav-link @if($c == 'customer') active @endif ">
                                <i class="fas fa-user-tag nav-icon"></i>
                                <p>@lang('Customer')</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endcan

                @canany(['scholarship-read', 'scholarship-create', 'scholarship-update', 'scholarship-delete'])
                    <li class="nav-item has-treeview @if($c == 'scholarship') menu-open @endif">
                        <a href="javascript:void(0)" class="nav-link @if($c == 'scholarship') active @endif">
                            <i class="nav-icon fas fa-graduation-cap"></i>
                            <p>
                                @lang('Applications')
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @canany(['scholarship-create'])
                                <li class="nav-item">
                                    <a href="{{ route('scholarship.create') }}" class="nav-link @if($c == 'scholarship' && $m == 'create') active @endif ">
                                        <i class="far fa-dot-circle nav-icon"></i>
                                        <p>@lang('Apply Application')</p>
                                    </a>
                                </li>
                            @endcanany
                            @can('scholarship-pending-read')
                                <li class="nav-item">
                                    <a href="{{ route('scholarship.pending') }}" class="nav-link @if($c == 'scholarship' && $m == 'pending') active @endif ">
                                        <i class="far fa-dot-circle nav-icon"></i>
                                        <p>@lang('Under Verification')</p>
                                    </a>
                                </li>
                            @endcan
                            @can('scholarship-approved-read')
                                <li class="nav-item">
                                    <a href="{{ route('scholarship.approved') }}" class="nav-link @if($c == 'scholarship' && $m == 'approved') active @endif ">
                                        <i class="far fa-dot-circle nav-icon"></i>
                                        <p>@lang('Approved')</p>
                                    </a>
                                </li>
                            @endcan
                            @can('scholarship-payment_in_progress-read')
                                <li class="nav-item">
                                    <a href="{{ route('scholarship.payment_in_progress') }}" class="nav-link @if($c == 'scholarship' && $m == 'payment_in_progress') active @endif ">
                                        <i class="far fa-dot-circle nav-icon"></i>
                                        <p>@lang('Payment In Progress')</p>
                                    </a>
                                </li>
                            @endcan
                            @can('scholarship-payment_done-read')
                                <li class="nav-item">
                                    <a href="{{ route('scholarship.payment_done') }}" class="nav-link @if($c == 'scholarship' && $m == 'payment_done') active @endif ">
                                        <i class="far fa-dot-circle nav-icon"></i>
                                        <p>@lang('Payment Done')</p>
                                    </a>
                                </li>
                            @endcan
                            @can('scholarship-rejected-read')
                                <li class="nav-item">
                                    <a href="{{ route('scholarship.rejected') }}" class="nav-link @if($c == 'scholarship' && $m == 'rejected') active @endif ">
                                        <i class="far fa-dot-circle nav-icon"></i>
                                        <p>@lang('Rejected')</p>
                                    </a>
                                </li>
                            @endcan
                            @can('scholarship-all-read')
                                <li class="nav-item">
                                    <a href="{{ route('scholarship.index') }}" class="nav-link @if($c == 'scholarship'  && $m == 'index') active @endif ">
                                        <i class="far fa-dot-circle nav-icon"></i>
                                        <p>@lang('All Applications')</p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan

                @canany(['village-read', 'village-create', 'village-update', 'village-delete','class-read', 'class-create', 'class-update', 'class-delete','year-read', 'year-create', 'year-update', 'year-delete','school-read', 'school-create', 'school-update', 'school-delete','college-read', 'college-create', 'college-update', 'college-delete','teacher-read', 'teacher-create', 'teacher-update', 'teacher-delete'])
                <li class="nav-item has-treeview @if($c == 'scholarship-class' || $c == 'scholarship-college' || $c == 'scholarship-year' || $c =='scholarship-teacher' || $c == 'scholarship-village' || $c == 'scholarship-school' || $c == 'teacher') menu-open @endif">
                    <a href="javascript:void(0)" class="nav-link @if($c == 'scholarship-class' || $c == 'scholarship-college' || $c == 'scholarship-year' || $c == 'scholarship-teacher' || $c == 'scholarship-village' || $c == 'scholarship-school' || $c == 'teacher') active @endif">
                        <i class="nav-icon fas fa-tools"></i>
                        <p>
                            @lang('Basic Configuration')
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        @canany(['village-read', 'village-create', 'village-update', 'village-delete'])
                            <li class="nav-item">
                                <a href="{{ route('scholarship-village.index') }}" class="nav-link @if($c == 'scholarship-village') active @endif ">
                                    <i class="far fa-dot-circle nav-icon"></i>
                                    <p>@lang('Village')</p>
                                </a>
                            </li>
                        @endcanany
                        @canany(['class-read', 'class-create', 'class-update', 'class-delete'])
                            <li class="nav-item">
                                <a href="{{ route('scholarship-class.index') }}" class="nav-link @if($c == 'scholarship-class') active @endif ">
                                    <i class="far fa-dot-circle nav-icon"></i>
                                    <p>@lang('Class')</p>
                                </a>
                            </li>
                        @endcanany
                        @canany(['year-read', 'year-create', 'year-update', 'year-delete'])
                            <li class="nav-item">
                                <a href="{{ route('scholarship-year.index') }}" class="nav-link @if($c == 'scholarship-year') active @endif ">
                                    <i class="far fa-dot-circle nav-icon"></i>
                                    <p>@lang('Year')</p>
                                </a>
                            </li>
                        @endcanany
                        @canany(['school-read', 'school-create', 'school-update', 'school-delete'])
                            <li class="nav-item">
                                <a href="{{ route('scholarship-school.index') }}" class="nav-link @if($c == 'scholarship-school') active @endif ">
                                    <i class="far fa-dot-circle nav-icon"></i>
                                    <p>@lang('School')</p>
                                </a>
                            </li>
                        @endcanany
                        @canany(['college-read', 'college-create', 'college-update', 'college-delete'])
                            <li class="nav-item">
                                <a href="{{ route('scholarship-college.index') }}" class="nav-link @if($c == 'scholarship-college') active @endif ">
                                    <i class="far fa-dot-circle nav-icon"></i>
                                    <p>@lang('College')</p>
                                </a>
                            </li>
                        @endcanany
                        @canany(['teacher-read', 'teacher-create', 'teacher-update', 'teacher-delete'])
                            <li class="nav-item">
                                <a href="{{ route('scholarship-teacher.index') }}" class="nav-link @if($c == 'scholarship-teacher') active @endif ">
                                    <i class="far fa-dot-circle nav-icon"></i>
                                    <p>@lang('Teacher')</p>
                                </a>
                            </li>
                        @endcanany
                    </ul>
                </li>
                @endcanany

                @canany(['year-wise-read','school-wise-read','college-wise-read','village-wise-read','course-wise-read','student-wise-read','expense-wise-read'])
                <li class="nav-item has-treeview @if($c == 'report') menu-open @endif">
                    <a href="javascript:void(0)" class="nav-link @if($c == 'report') active @endif">
                        <i class="nav-icon fas fa-chart-bar"></i>
                        <p>
                            @lang('Reports')
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        @canany(['year-wise-read'])
                            <li class="nav-item">
                                <a href="{{ route('report.year') }}" class="nav-link @if($c == 'report' && $m == 'year') active @endif ">
                                    <i class="far fa-dot-circle nav-icon"></i>
                                    <p>@lang('Year Wise Scholarship')</p>
                                </a>
                            </li>
                        @endcanany
                        @canany(['school-wise-read'])
                        <li class="nav-item">
                            <a href="{{ route('report.school') }}" class="nav-link @if($c == 'report' && $m == 'school') active @endif ">
                                <i class="far fa-dot-circle nav-icon"></i>
                                <p>@lang('School Wise Scholarship')</p>
                            </a>
                        </li>
                        @endcanany
                        @canany(['college-wise-read'])
                        <li class="nav-item">
                            <a href="{{ route('report.college') }}" class="nav-link @if($c == 'report' && $m == 'college') active @endif ">
                                <i class="far fa-dot-circle nav-icon"></i>
                                <p>@lang('College Wise Scholarship')</p>
                            </a>
                        </li>
                        @endcanany
                        @canany(['village-wise-read'])
                        <li class="nav-item">
                            <a href="{{ route('report.village') }}" class="nav-link @if($c == 'report' && $m == 'village') active @endif ">
                                <i class="far fa-dot-circle nav-icon"></i>
                                <p>@lang('Village Wise Scholarship')</p>
                            </a>
                        </li>
                        @endcanany
                        @canany(['course-wise-read'])
                        <li class="nav-item">
                            <a href="{{ route('report.course') }}" class="nav-link @if($c == 'report' && $m == 'course') active @endif ">
                                <i class="far fa-dot-circle nav-icon"></i>
                                <p>@lang('Course Wise Scholarship')</p>
                            </a>
                        </li>
                        @endcanany
                        @canany(['student-wise-read'])
                        <li class="nav-item">
                            <a href="{{ route('report.student') }}" class="nav-link @if($c == 'report' && $m == 'student') active @endif ">
                                <i class="far fa-dot-circle nav-icon"></i>
                                <p>@lang('Student Wise Scholarship')</p>
                            </a>
                        </li>
                        @endcanany
                        @canany(['expense-wise-read'])
                        <li class="nav-item">
                            <a href="{{ route('report.expense') }}" class="nav-link @if($c == 'report' && $m == 'expense') active @endif ">
                                <i class="far fa-dot-circle nav-icon"></i>
                                <p>@lang('Expense Wise Report')</p>
                            </a>
                        </li>
                        @endcanany
                    </ul>
                </li>
                @endcanany

                @canany(['expense-read', 'expense-create', 'expense-update', 'expense-delete'])
                <li class="nav-item">
                    <a href="{{ route('expense.index') }}" class="nav-link @if($c == 'expense') active @endif ">
                        <i class="fas fa-minus nav-icon"></i>
                        <p>@lang('Expense')</p>
                    </a>
                </li>
                @endcanany

                @canany(['category-read', 'category-create', 'category-update', 'category-delete', 'category-export', 'category-import', 'currencies-read', 'currencies-create', 'currencies-update', 'currencies-delete', 'currencies-export', 'currencies-import','tax-rate-read', 'tax-rate-create', 'tax-rate-update', 'tax-rate-delete', 'tax-rate-export', 'tax-rate-import'])
                    <li class="nav-item has-treeview @if($c == 'category' || $c == 'currency' || $c == 'tax') menu-open @endif">
                        <a href="javascript:void(0)" class="nav-link @if($c == 'category' || $c == 'currency' || $c == 'tax') active @endif">
                            <i class="nav-icon fas fa-quote-right"></i>
                            <p>
                                @lang('Types')
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @canany(['category-read', 'category-create', 'category-update', 'category-delete', 'category-export', 'category-import'])
                                <li class="nav-item">
                                    <a href="{{ route('category.index') }}" class="nav-link @if($c == 'category') active @endif ">
                                        <i class="fas fa-code-branch nav-icon"></i>
                                        <p>@lang('Category')</p>
                                    </a>
                                </li>
                            @endcanany
                            @canany(['currencies-read', 'currencies-create', 'currencies-update', 'currencies-delete', 'currencies-export', 'currencies-import'])
                                <li class="nav-item">
                                    <a href="{{ route('currency.index') }}" class="nav-link @if($c == 'currency') active @endif ">
                                        <i class="fas fa-coins nav-icon"></i>
                                        <p>@lang('Currencies')</p>
                                    </a>
                                </li>
                            @endcanany
                            @canany(['tax-rate-read', 'tax-rate-create', 'tax-rate-update', 'tax-rate-delete', 'tax-rate-export', 'tax-rate-import'])
                                <li class="nav-item">
                                    <a href="{{ route('tax.index') }}" class="nav-link @if($c == 'tax') active @endif ">
                                        <i class="fas fa-percentage nav-icon"></i>
                                        <p>@lang('Tax rates')</p>
                                    </a>
                                </li>
                            @endcanany
                        </ul>
                    </li>
                @endcanany
                @canany(['company-read', 'company-update'])
                    <li class="nav-item">
                        <a href="{{ route('general') }}" class="nav-link @if($c == 'general') active @endif ">
                            <i class="fas fa-align-left nav-icon"></i>
                            <p>@lang('My Company')</p>
                        </a>
                    </li>
                @endcanany
                @canany(['role-read', 'role-create', 'role-update', 'role-delete', 'role-export', 'user-read', 'user-create', 'user-update', 'user-delete', 'user-export', 'offline-payment-read', 'offline-payment-create', 'offline-payment-update', 'offline-payment-delete'])
                    <li class="nav-item has-treeview @if($c == 'roles' || $c == 'users' || $c == 'apsetting' || $c == 'smtp' || $c == 'offline-payment' ) menu-open @endif">
                        <a href="javascript:void(0)" class="nav-link @if($c == 'roles' || $c == 'users' || $c == 'apsetting' || $c == 'smtp' || $c == 'offline-payment' ) active @endif">
                            <i class="nav-icon fa fa-cogs"></i>
                            <p>
                                @lang('Settings')
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @canany(['role-read', 'role-create', 'role-update', 'role-delete', 'role-export'])
                                <li class="nav-item">
                                    <a href="{{ route('roles.index') }}" class="nav-link @if($c == 'roles') active @endif ">
                                        <i class="fas fa-cube nav-icon"></i>
                                        <p>@lang('Role Management')</p>
                                    </a>
                                </li>
                            @endcanany
                            @canany(['user-read', 'user-create', 'user-update', 'user-delete', 'user-export'])
                                <li class="nav-item">
                                    <a href="{{ route('users.index') }}" class="nav-link @if($c == 'users') active @endif ">
                                        <i class="fa fa-users nav-icon"></i>
                                        <p>@lang('User Management')</p>
                                    </a>
                                </li>
                            @endcanany
                            @if ($roleName['0'] == "Super Admin")
                                <li class="nav-item">
                                    <a href="{{ route('apsetting') }}" class="nav-link @if($c == 'apsetting' && $m == null) active @endif ">
                                        <i class="fa fa-globe nav-icon"></i>
                                        <p>@lang('Application Settings')</p>
                                    </a>
                                </li>
                                {{--  <li class="nav-item">
                                    <a href="{{ route('smtp.index') }}" class="nav-link @if($c == 'smtp') active @endif ">
                                        <i class="fas fa-mail-bulk nav-icon"></i>
                                        <p>@lang('Smtp Settings')</p>
                                    </a>
                                </li>  --}}
                            @endif
                            @canany(['offline-payment-read', 'offline-payment-create', 'offline-payment-update', 'offline-payment-delete'])
                                {{--  <li class="nav-item">
                                    <a href="{{ route('offline-payment.index') }}" class="nav-link @if($c == 'offline-payment') active @endif ">
                                        <i class="fas fa-money-check nav-icon"></i>
                                        <p>@lang('Offline Payments')</p>
                                    </a>
                                </li>  --}}
                            @endcanany
                        </ul>
                    </li>
                @endcanany
            </ul>
        </nav>
    </div>
</aside>
