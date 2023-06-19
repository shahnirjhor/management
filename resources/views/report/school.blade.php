@extends('layouts.layout')

@section('one_page_css')
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.4/css/buttons.dataTables.min.css">
@endsection
@section('one_page_js')
<script src="https://cdn.datatables.net/buttons/2.3.4/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.4/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.4/js/buttons.print.min.js"></script>
@endsection

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">@lang('Dashboard')</a></li>
                    <li class="breadcrumb-item active">{{ __('School Wise Scholarship Contribution') }}</li>
                </ol>
            </div>
        </div>
    </div>
</section>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ __('School Wise Scholarship Contribution') }}</h3>
                <div class="card-tools">
                    <button class="btn btn-default" data-toggle="collapse" href="#filter"><i class="fas fa-filter"></i> @lang('Filter')</button>
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
                                        <select id="start_year" name="start_year" class="form-control">
                                            <option value="">--@lang('Select Start Year')--</option>
                                            @foreach ($selectYears as $key => $value)
                                            @if(request()->status == '1') selected @endif
                                                <option value="{{ $key }}" {{ old('start_year', request()->start_year) == $key ? 'selected' : '' }}>{{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <select name="end_year" class="form-control">
                                            <option value="">--@lang('Select End Year')--</option>
                                            @foreach ($selectYears as $key => $value)
                                                <option value="{{ $key }}" {{ old('end_year',request()->end_year) == $key ? 'selected' : '' }}>{{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <button type="submit" class="btn btn-info">Submit</button>
                                    @if(request()->isFilterActive)
                                        <a href="{{ route('report.school') }}" class="btn btn-secondary">Clear</a>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="laravel_datatable" class="table table-striped compact table-width">
                        <thead>
                            <tr style="text-align: center;">
                                <th colspan="{{ $colSForHeading }}">School wise Scholarship Contribution in @if(request()->start_year) {{ request()->start_year }} @else {{ $previousYear }} @endif to @if(request()->end_year) {{ request()->end_year }} @else {{ $thisYear }} @endif Years</th>
                            </tr>
                            <tr class="table-primary">
                                <th></th>
                                @foreach ($output as $value)
                                    @foreach ($value as $yearName => $yearData)
                                        <th></th>
                                        <th style="text-align: left;">{{ $yearName }}</th>
                                    @endforeach
                                    @break
                                @endforeach
                                <th>Total NoS.</th>
                                <th>Total Amount</th>
                            </tr>
                        </thead>
                        <tbody style="text-align: center;">
                            <tr class="table-info">
                                <th style="text-align: center;">School Name</th>
                                @foreach ($output as $value)
                                    @foreach ($value as $yearName => $yearData)
                                    <th style="text-align: center;">NoS.</th>
                                    <th style="text-align: center;">Amount</th>
                                    @endforeach
                                    @break
                                @endforeach
                                <th></th>
                                <th></th>
                            </tr>
                            @foreach ($output as $schoolName => $value)
                            <tr>
                                <td> {{ $schoolName }}</td>
                                @foreach ($value as $yearName => $yearData)
                                    @if(isset($yearData['total_student']) && !empty($yearData['total_student']))
                                        <td>{{ $yearData['total_student'] }}</td>
                                    @else
                                        <td></td>
                                    @endif
                                    @if(isset($yearData['total_amount']) && !empty($yearData['total_amount']))
                                        <td>{{ $yearData['total_amount'] }}</td>
                                    @else
                                        <td></td>
                                    @endif
                                @endforeach
                                <td>{{ $totalData[$schoolName]['school_wise_total_student'] }}</td>
                                <td>{{ $totalData[$schoolName]['school_wise_total_amount'] }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="table-info" style="text-align: center;">
                                <th>@lang('Grand Total')</th>
                                @foreach ($output as $value)
                                    @foreach ($value as $yearName => $yearData)
                                    @if(isset($studentYearData[$yearName]['g_total_student']) && !empty($studentYearData[$yearName]['g_total_student']))
                                        <th>{{ $studentYearData[$yearName]['g_total_student'] }}</th>
                                    @else
                                        <th>0</th>
                                    @endif

                                    @if(isset($studentYearData[$yearName]['g_total_amount']) && !empty($studentYearData[$yearName]['g_total_amount']))
                                        <th>{{ $studentYearData[$yearName]['g_total_amount'] }}</th>
                                    @else
                                        <th>0</th>
                                    @endif
                                    @endforeach
                                    @break
                                @endforeach
                                <th>{{ $gTotalStudent }}</th>
                                <th>{{ $gTotalAmount }}</th>
                            </tr>
                        </tfoot>
                    </table>
                    {{ $schoolWiseData->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    "use strict";
    $(document).ready( function () {
        $('#laravel_datatable').DataTable({
            "paging": false,
            "lengthChange": false,
            "searching": false,
            "ordering": false,
            "info": false,
            "autoWidth": false,
            "responsive": true,
            dom: 'Bfrtip',
            buttons: [
                { extend: 'excelHtml5', footer: true,title: 'School wise Scholarship Contribution in @if(request()->start_year) {{ request()->start_year }} @else {{ $previousYear }} @endif to @if(request()->end_year) {{ request()->end_year }} @else {{ $thisYear }} @endif Years'  },
                { extend: 'csvHtml5', footer: true,title: 'School wise Scholarship Contribution in @if(request()->start_year) {{ request()->start_year }} @else {{ $previousYear }} @endif to @if(request()->end_year) {{ request()->end_year }} @else {{ $thisYear }} @endif Years'  },
                { extend: 'pdfHtml5', footer: true,title: 'School wise Scholarship Contribution in @if(request()->start_year) {{ request()->start_year }} @else {{ $previousYear }} @endif to @if(request()->end_year) {{ request()->end_year }} @else {{ $thisYear }} @endif Years' }
            ]
        });
    });
</script>
@endsection
