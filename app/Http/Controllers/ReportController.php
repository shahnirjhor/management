<?php

namespace App\Http\Controllers;

use Session;
use Carbon\Carbon;
use App\Models\Tax;
use App\Models\Bill;
use App\Models\Vendor;
use App\Models\Account;
use App\Models\Company;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Revenue;
use App\Models\Category;
use App\Models\Customer;
use App\Traits\DateTime;
use App\Models\BillPayment;
use Illuminate\Http\Request;
use App\Models\InvoicePayment;
use App\Models\Scholarship;
use App\Models\ScholarshipYear;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{

    use DateTime;

    /**
     * load constructor method
     *
     * @access public
     * @return void
     */
    function __construct()
    {
        $this->middleware('permission:year-wise-read', ['only' => ['year']]);
        $this->middleware('permission:school-wise-read', ['only' => ['school']]);
        $this->middleware('permission:college-wise-read', ['only' => ['college']]);
        $this->middleware('permission:village-wise-read', ['only' => ['village']]);
        $this->middleware('permission:course-wise-read', ['only' => ['course']]);
        $this->middleware('permission:student-wise-read', ['only' => ['student']]);
        $this->middleware('permission:expense-wise-read', ['only' => ['expense']]);
    }

    public function year(Request $request)
    {
        $grandTotalAmount = 0;
        $grandTotalStudent = 0;
        $yearWiseData = $this->filter($request)->paginate(10);
        if (isset($yearWiseData) && !empty($yearWiseData)) {
            foreach ($yearWiseData as $data){
                $grandTotalAmount += $data->total_amount;
                $grandTotalStudent += $data->total_student;
            }
        }

        $company = Company::findOrFail(Session::get('company_id'));
        $company->setSettings();

        $years = ScholarshipYear::where('company_id', session('company_id'))->where('status', 1)->orderBy('name')->pluck('name', 'name');
        $thisYear = Carbon::now()->year;
        $previousYear = $thisYear-2;

        return view('report.income', compact('grandTotalStudent','grandTotalAmount','years','thisYear','previousYear','company','yearWiseData'));
    }

    private function filter(Request $request)
    {
        $thisYear = Carbon::now()->year;
        $previousYear = $thisYear-2;

        $sub = Scholarship::orderBy('created_at','DESC');
        $chats = DB::table(DB::raw("({$sub->toSql()}) as sub"))
        ->where('status','payment_done')
        ->selectRaw("SUM(fee_amount) as total_amount")
        ->selectRaw("count(id) as total_student")
        ->selectRaw("year")
        ->groupBy('year');
        if ($request->start_year && $request->end_year) {
            $chats->whereBetween('year', [$request->start_year, $request->end_year]);
        } else {
            $chats->whereBetween('year', [$previousYear, $thisYear]);
        }

        return $chats;
    }

    public function college(Request $request)
    {
        $thisYear = Carbon::now()->year;
        $previousYear = $thisYear-2;

        $company = Company::findOrFail(Session::get('company_id'));
        $company->setSettings();

        $collegeWiseData = $this->filterCollege($request)->paginate(15);

        $datas = $collegeWiseData->mapToGroups(function ($item, $key) {
            return [$item->name => $item];
        });

        $datasYear = $collegeWiseData->mapToGroups(function ($item, $key) {
            return [$item->year => $item];
        });

        $studentYearData = array();

        foreach($datasYear as $key => $value) {
            $gTotalYearStudent = 0;
            $gTotalYearAmount = 0;
            foreach ($value as $v) {
                $gTotalYearStudent = $gTotalYearStudent + $v->total_student;
                $gTotalYearAmount = $gTotalYearAmount + $v->total_amount;

            }
            $studentYearData[$key]['g_total_student'] = $gTotalYearStudent;
            $studentYearData[$key]['g_total_amount'] = $gTotalYearAmount;
        }

        if ($request->start_year && $request->end_year) {
            for ($x = $request->start_year; $x <= $request->end_year; $x++) {
                $years[]['year'] = $x;
            }
        } else {
            for ($x = $previousYear; $x <= $thisYear; $x++) {
                $years[]['year'] = $x;
            }
        }
        $years = json_decode(json_encode($years), FALSE);
        $yearCount = count($years);
        $colSForHeading = $yearCount*2+3;

        $gTotalStudent = 0;
        $gTotalAmount = 0;
        $output = array();
        $totalData = array();

        foreach($datas as $key => $value) {
            $collegeWiseTotalStudent = 0;
            $collegeWiseTotalAmount = 0;
            foreach ($value as $v) {
                $collegeWiseTotalStudent = $collegeWiseTotalStudent + $v->total_student;
                $collegeWiseTotalAmount = $collegeWiseTotalAmount + $v->total_amount;
                foreach ($years as $y) {
                    $output[$v->name][$y->year][] = array();
                    if($y->year == $v->year) {
                        $output[$v->name][$y->year]['name'] = $v->name;
                        $output[$v->name][$y->year]['total_amount'] = $v->total_amount;
                        $output[$v->name][$y->year]['total_student'] = $v->total_student;
                    }
                }
                $totalData[$v->name]['college_wise_total_student'] = $collegeWiseTotalStudent;
                $totalData[$v->name]['college_wise_total_amount'] = $collegeWiseTotalAmount;
            }
            $gTotalStudent = $gTotalStudent + $collegeWiseTotalStudent;
            $gTotalAmount = $gTotalAmount + $collegeWiseTotalAmount;
        }

        $selectYears = ScholarshipYear::where('company_id', session('company_id'))->where('status', 1)->orderBy('name')->pluck('name', 'name');
        return view('report.college',compact('studentYearData','datas','company','years','totalData','selectYears','colSForHeading','previousYear','thisYear','output','gTotalStudent','gTotalAmount','collegeWiseData'));
    }

    public function expense(Request $request)
    {
        $thisYear = Carbon::now()->year;
        $previousYear = $thisYear-2;

        $company = Company::findOrFail(Session::get('company_id'));
        $company->setSettings();

        $expenseWiseData = $this->filterExpense($request)->paginate(15);

        $expenseWiseCollegeData = $this->filterCollegeExpense($request)->paginate(15);

        $datas = $expenseWiseData->mapToGroups(function ($item, $key) {
            return [$item->name => $item];
        });

        $collegeDatas = $expenseWiseCollegeData->mapToGroups(function ($item, $key) {
            return [$item->name => $item];
        });

        $datasYear = $expenseWiseData->mapToGroups(function ($item, $key) {
            return [$item->year => $item];
        });

        $collegeDatasYear = $expenseWiseCollegeData->mapToGroups(function ($item, $key) {
            return [$item->year => $item];
        });

        $expenseCollegeData = array();
        foreach($collegeDatasYear as $key => $value) {
            $gTotalYearEexpenseCollege = 0;
            $gTotalYearAmountCollege = 0;
            foreach ($value as $v) {
                $gTotalYearEexpenseCollege = $gTotalYearEexpenseCollege + $v->total_expenses;
                $gTotalYearAmountCollege = $gTotalYearAmountCollege + $v->total_amount;
            }
            $expenseCollegeData[$key]['g_total_expense'] = $gTotalYearEexpenseCollege;
            $expenseCollegeData[$key]['g_total_amount'] = $gTotalYearAmountCollege;
        }

        $expenseSchoolData = array();
        foreach($datasYear as $key => $value) {
            $gTotalYearEexpenseSchool = 0;
            $gTotalYearAmountSchool = 0;
            foreach ($value as $v) {
                $gTotalYearEexpenseSchool = $gTotalYearEexpenseSchool + $v->total_expenses;
                $gTotalYearAmountSchool = $gTotalYearAmountSchool + $v->total_amount;

            }
            $expenseSchoolData[$key]['g_total_expense'] = $gTotalYearEexpenseSchool;
            $expenseSchoolData[$key]['g_total_amount'] = $gTotalYearAmountSchool;
        }
        if ($request->start_year && $request->end_year) {
            for ($x = $request->start_year; $x <= $request->end_year; $x++) {
                $years[]['year'] = $x;
            }
        } else {
            for ($x = $previousYear; $x <= $thisYear; $x++) {
                $years[]['year'] = $x;
            }
        }

        $years = json_decode(json_encode($years), FALSE);
        $yearCount = count($years);
        $colSForHeading = $yearCount*2+3;

        $gTotalExpenseSchool = 0;
        $gTotalAmountSchool = 0;
        $outputSchool = array();
        $totalDataSchool = array();

        $gTotalExpenseCollege = 0;
        $gTotalAmountCollege = 0;
        $outputCollege = array();
        $totalDataCollege = array();

        foreach($collegeDatas as $key => $value) {
            $collegeWiseTotalExpense = 0;
            $collegeWiseTotalAmount = 0;
            foreach ($value as $v) {
                $collegeWiseTotalExpense = $collegeWiseTotalExpense + $v->total_expenses;
                $collegeWiseTotalAmount = $collegeWiseTotalAmount + $v->total_amount;
                foreach ($years as $y) {
                    $outputCollege[$v->name][$y->year][] = array();
                    if($y->year == $v->year) {
                        $outputCollege[$v->name][$y->year]['name'] = $v->name;
                        $outputCollege[$v->name][$y->year]['total_amount'] = $v->total_amount;
                        $outputCollege[$v->name][$y->year]['total_expenses'] = $v->total_expenses;
                    }
                }
                $totalDataCollege[$v->name]['college_wise_total_expense'] = $collegeWiseTotalExpense;
                $totalDataCollege[$v->name]['college_wise_total_amount'] = $collegeWiseTotalAmount;
            }
            $gTotalAmountCollege = $gTotalAmountCollege + $collegeWiseTotalAmount;
        }

        foreach($datas as $key => $value) {
            $schoolWiseTotalExpense = 0;
            $schoolWiseTotalAmount = 0;
            foreach ($value as $v) {
                $schoolWiseTotalExpense = $schoolWiseTotalExpense + $v->total_expenses;
                $schoolWiseTotalAmount = $schoolWiseTotalAmount + $v->total_amount;
                foreach ($years as $y) {
                    $outputSchool[$v->name][$y->year][] = array();
                    if($y->year == $v->year) {
                        $outputSchool[$v->name][$y->year]['name'] = $v->name;
                        $outputSchool[$v->name][$y->year]['total_amount'] = $v->total_amount;
                        $outputSchool[$v->name][$y->year]['total_expenses'] = $v->total_expenses;
                    }
                }
                $totalDataSchool[$v->name]['school_wise_total_expense'] = $schoolWiseTotalExpense;
                $totalDataSchool[$v->name]['school_wise_total_amount'] = $schoolWiseTotalAmount;
            }
            $gTotalExpenseSchool = $gTotalExpenseSchool + $schoolWiseTotalExpense;
            $gTotalAmountSchool = $gTotalAmountSchool + $schoolWiseTotalAmount;
        }
        $selectYears = ScholarshipYear::where('company_id', session('company_id'))->where('status', 1)->orderBy('name')->pluck('name', 'name');

        return view('report.my_expense', compact('gTotalAmountCollege','expenseCollegeData','totalDataCollege','outputCollege','expenseCollegeData','expenseSchoolData','gTotalAmountSchool','gTotalExpenseSchool','selectYears','totalDataSchool','years','thisYear','previousYear','company','datas','outputSchool','colSForHeading','expenseWiseData','expenseWiseCollegeData'));
    }


    public function school(Request $request)
    {
        $thisYear = Carbon::now()->year;
        $previousYear = $thisYear-2;

        $company = Company::findOrFail(Session::get('company_id'));
        $company->setSettings();

        $schoolWiseData = $this->filterSchool($request)->paginate(15);

        $datas = $schoolWiseData->mapToGroups(function ($item, $key) {
            return [$item->name => $item];
        });

        $datasYear = $schoolWiseData->mapToGroups(function ($item, $key) {
            return [$item->year => $item];
        });

        $studentYearData = array();

        foreach($datasYear as $key => $value) {
            $gTotalYearStudent = 0;
            $gTotalYearAmount = 0;
            foreach ($value as $v) {
                $gTotalYearStudent = $gTotalYearStudent + $v->total_student;
                $gTotalYearAmount = $gTotalYearAmount + $v->total_amount;

            }
            $studentYearData[$key]['g_total_student'] = $gTotalYearStudent;
            $studentYearData[$key]['g_total_amount'] = $gTotalYearAmount;
        }

        if ($request->start_year && $request->end_year) {
            for ($x = $request->start_year; $x <= $request->end_year; $x++) {
                $years[]['year'] = $x;
            }
        } else {
            for ($x = $previousYear; $x <= $thisYear; $x++) {
                $years[]['year'] = $x;
            }
        }
        $years = json_decode(json_encode($years), FALSE);
        $yearCount = count($years);
        $colSForHeading = $yearCount*2+3;

        $gTotalStudent = 0;
        $gTotalAmount = 0;
        $output = array();
        $totalData = array();

        foreach($datas as $key => $value) {
            $schoolWiseTotalStudent = 0;
            $schoolWiseTotalAmount = 0;

            foreach ($value as $v) {
                $schoolWiseTotalStudent = $schoolWiseTotalStudent + $v->total_student;
                $schoolWiseTotalAmount = $schoolWiseTotalAmount + $v->total_amount;

                foreach ($years as $y) {

                    $output[$v->name][$y->year][] = array();
                    if($y->year == $v->year) {

                        $output[$v->name][$y->year]['name'] = $v->name;
                        $output[$v->name][$y->year]['total_amount'] = $v->total_amount;
                        $output[$v->name][$y->year]['total_student'] = $v->total_student;
                    }
                }
                $totalData[$v->name]['school_wise_total_student'] = $schoolWiseTotalStudent;
                $totalData[$v->name]['school_wise_total_amount'] = $schoolWiseTotalAmount;


            }
            $gTotalStudent = $gTotalStudent + $schoolWiseTotalStudent;
            $gTotalAmount = $gTotalAmount + $schoolWiseTotalAmount;
        }
        $selectYears = ScholarshipYear::where('company_id', session('company_id'))->where('status', 1)->orderBy('name')->pluck('name', 'name');
        return view('report.school', compact('studentYearData','gTotalAmount','gTotalStudent','selectYears','totalData','years','thisYear','previousYear','company','datas','output','colSForHeading','schoolWiseData'));
    }

    public function course(Request $request)
    {
        $thisYear = Carbon::now()->year;
        $previousYear = $thisYear-2;

        $company = Company::findOrFail(Session::get('company_id'));
        $company->setSettings();

        $courseWiseData = $this->filterCourse($request)->paginate(15);

        $datas = $courseWiseData->mapToGroups(function ($item, $key) {
            return [$item->name => $item];
        });

        $datasYear = $courseWiseData->mapToGroups(function ($item, $key) {
            return [$item->year => $item];
        });

        $courseYearData = array();
        foreach($datasYear as $key => $value) {
            $gTotalYearStudent = 0;
            $gTotalYearAmount = 0;
            foreach ($value as $v) {
                $gTotalYearStudent = $gTotalYearStudent + $v->total_student;
                $gTotalYearAmount = $gTotalYearAmount + $v->total_amount;

            }
            $courseYearData[$key]['g_total_student'] = $gTotalYearStudent;
            $courseYearData[$key]['g_total_amount'] = $gTotalYearAmount;
        }
        if ($request->start_year && $request->end_year) {
            for ($x = $request->start_year; $x <= $request->end_year; $x++) {
                $years[]['year'] = $x;
            }
        } else {
            for ($x = $previousYear; $x <= $thisYear; $x++) {
                $years[]['year'] = $x;
            }
        }
        $years = json_decode(json_encode($years), FALSE);
        $yearCount = count($years);
        $colSForHeading = $yearCount*2+3;

        $gTotalStudent = 0;
        $gTotalAmount = 0;
        $output = array();
        $totalData = array();
        foreach($datas as $key => $value) {
            $courseWiseTotalStudent = 0;
            $courseWiseTotalAmount = 0;
            foreach ($value as $v) {
                $courseWiseTotalStudent = $courseWiseTotalStudent + $v->total_student;
                $courseWiseTotalAmount = $courseWiseTotalAmount + $v->total_amount;
                foreach ($years as $y) {
                    $output[$v->name][$y->year][] = array();
                    if($y->year == $v->year) {

                        $output[$v->name][$y->year]['name'] = $v->name;
                        $output[$v->name][$y->year]['total_amount'] = $v->total_amount;
                        $output[$v->name][$y->year]['total_student'] = $v->total_student;
                    }
                }
                $totalData[$v->name]['course_wise_total_student'] = $courseWiseTotalStudent;
                $totalData[$v->name]['course_wise_total_amount'] = $courseWiseTotalAmount;


            }
            $gTotalStudent = $gTotalStudent + $courseWiseTotalStudent;
            $gTotalAmount = $gTotalAmount + $courseWiseTotalAmount;
        }
        $selectYears = ScholarshipYear::where('company_id', session('company_id'))->where('status', 1)->orderBy('name')->pluck('name', 'name');
        return view('report.course',compact('courseYearData','datas','company','years','totalData','selectYears','colSForHeading','previousYear','thisYear','output','gTotalStudent','gTotalAmount','courseWiseData'));
    }

    public function village(Request $request)
    {
        $thisYear = Carbon::now()->year;
        $previousYear = $thisYear-2;

        $company = Company::findOrFail(Session::get('company_id'));
        $company->setSettings();

        $villageWiseData = $this->filterVillage($request)->paginate(15);

        $datas = $villageWiseData->mapToGroups(function ($item, $key) {
            return [$item->name => $item];
        });

        $datasYear = $villageWiseData->mapToGroups(function ($item, $key) {
            return [$item->year => $item];
        });

        $villageYearData = array();
        foreach($datasYear as $key => $value) {
            $gTotalYearStudent = 0;
            $gTotalYearAmount = 0;
            foreach ($value as $v) {
                $gTotalYearStudent = $gTotalYearStudent + $v->total_student;
                $gTotalYearAmount = $gTotalYearAmount + $v->total_amount;

            }
            $villageYearData[$key]['g_total_student'] = $gTotalYearStudent;
            $villageYearData[$key]['g_total_amount'] = $gTotalYearAmount;
        }

        if ($request->start_year && $request->end_year) {
            for ($x = $request->start_year; $x <= $request->end_year; $x++) {
                $years[]['year'] = $x;
            }
        } else {
            for ($x = $previousYear; $x <= $thisYear; $x++) {
                $years[]['year'] = $x;
            }
        }
        $years = json_decode(json_encode($years), FALSE);
        $yearCount = count($years);
        $colSForHeading = $yearCount*2+3;

        $gTotalStudent = 0;
        $gTotalAmount = 0;
        $output = array();
        $totalData = array();
        foreach($datas as $key => $value) {
            $villageWiseTotalStudent = 0;
            $villageWiseTotalAmount = 0;
            foreach ($value as $v) {
                $villageWiseTotalStudent = $villageWiseTotalStudent + $v->total_student;
                $villageWiseTotalAmount = $villageWiseTotalAmount + $v->total_amount;
                foreach ($years as $y) {
                    $output[$v->name][$y->year][] = array();
                    if($y->year == $v->year) {

                        $output[$v->name][$y->year]['name'] = $v->name;
                        $output[$v->name][$y->year]['total_amount'] = $v->total_amount;
                        $output[$v->name][$y->year]['total_student'] = $v->total_student;
                    }
                }
                $totalData[$v->name]['village_wise_total_student'] = $villageWiseTotalStudent;
                $totalData[$v->name]['village_wise_total_amount'] = $villageWiseTotalAmount;


            }
            $gTotalStudent = $gTotalStudent + $villageWiseTotalStudent;
            $gTotalAmount = $gTotalAmount + $villageWiseTotalAmount;
        }

        $selectYears = ScholarshipYear::where('company_id', session('company_id'))->where('status', 1)->orderBy('name')->pluck('name', 'name');
        return view('report.village',compact('villageYearData','datas','company','years','totalData','selectYears','colSForHeading','previousYear','thisYear','output','gTotalStudent','gTotalAmount','villageWiseData'));
    }

    public function student(Request $request)
    {
        $thisYear = Carbon::now()->year;
        $previousYear = $thisYear-2;

        $company = Company::findOrFail(Session::get('company_id'));
        $company->setSettings();

        $studentWiseData = $this->filterStudent($request)->paginate(15);

        $datas = $studentWiseData->mapToGroups(function ($item, $key) {
            return [$item->name => $item];
        });

        $datasYear = $studentWiseData->mapToGroups(function ($item, $key) {
            return [$item->year => $item];
        });

        // dd($studentWiseData);

        $studentYearData = array();
        foreach($datasYear as $key => $value) {
            // $gTotalYearStudent = 0;
            $gTotalYearAmount = 0;
            foreach ($value as $v) {
                // $gTotalYearStudent = $gTotalYearStudent + $v->total_student;
                $gTotalYearAmount = $gTotalYearAmount + $v->total_amount;

            }
            // $villageYearData[$key]['g_total_student'] = $gTotalYearStudent;
            $studentYearData[$key]['g_total_amount'] = $gTotalYearAmount;
        }

        if ($request->start_year && $request->end_year) {
            for ($x = $request->start_year; $x <= $request->end_year; $x++) {
                $years[]['year'] = $x;
            }
        } else {
            for ($x = $previousYear; $x <= $thisYear; $x++) {
                $years[]['year'] = $x;
            }
        }

        $years = json_decode(json_encode($years), FALSE);
        $yearCount = count($years);
        $colSForHeading = $yearCount*2+3;

        $gTotalStudent = 0;
        $gTotalAmount = 0;
        $output = array();
        $totalData = array();
        foreach($datas as $key => $value) {
            $studentWiseTotalStudent = 0;
            $studentWiseTotalAmount = 0;
            foreach ($value as $v) {
                $studentWiseTotalAmount = $studentWiseTotalAmount + $v->total_amount;
                if($v->total_amount > 0) {
                    $studentWiseTotalStudent = $studentWiseTotalStudent + 1;
                }
                foreach ($years as $y) {
                    $output[$v->name][$y->year][] = array();
                    if($y->year == $v->year) {

                        $output[$v->name][$y->year]['name'] = $v->name;
                        $output[$v->name][$y->year]['total_amount'] = $v->total_amount;
                    }
                }
                $totalData[$v->name]['student_wise_total_student'] = $studentWiseTotalStudent;
                $totalData[$v->name]['student_wise_total_amount'] = $studentWiseTotalAmount;


            }
            // $gTotalStudent = $gTotalStudent + $villageWiseTotalStudent;
            $gTotalAmount = $gTotalAmount + $studentWiseTotalAmount;
        }

        // dd($studentYearData);

        $selectYears = ScholarshipYear::where('company_id', session('company_id'))->where('status', 1)->orderBy('name')->pluck('name', 'name');
        return view('report.student',compact('datas','company','years','totalData','selectYears','colSForHeading','previousYear','thisYear','output','gTotalStudent','gTotalAmount','studentWiseData','studentYearData'));

    }
    private function filterSchool(Request $request)
    {
        $thisYear = Carbon::now()->year;
        $previousYear = $thisYear-2;
        $projects = DB::table('scholarships')
            ->orderBy('scholarships.year','ASC')
            ->where('scholarships.school_or_college','1')
            ->where('scholarships.status','payment_done')
            ->join('scholarship_schools', 'scholarships.scholarship_school_id', '=', 'scholarship_schools.id')
            ->select('scholarships.year as year','scholarship_schools.name as name', DB::raw('sum(fee_amount) as total_amount'),DB::raw('count(scholarships.id) as total_student'))
            ->groupBy('scholarship_school_id','year');

        if ($request->start_year && $request->end_year) {
            $projects->whereBetween('year', [$request->start_year, $request->end_year]);
        } else {
            $projects->whereBetween('year', [$previousYear, $thisYear]);
        }
        return $projects;
    }

    private function filterExpense(Request $request)
    {
        $thisYear = Carbon::now()->year;
        $previousYear = $thisYear-2;

        $projects = DB::table('expenses')
            ->orderBy('expenses.year','ASC')
            ->where('expenses.school_or_college','1')
            ->where('expenses.status','1')
            ->join('scholarship_schools', 'expenses.scholarship_school_id', '=', 'scholarship_schools.id')
            ->select('expenses.year as year','scholarship_schools.name as name', DB::raw('sum(amount) as total_amount'),DB::raw('count(expenses.id) as total_expenses'))
            ->groupBy('scholarship_school_id','year');
            if ($request->start_year && $request->end_year) {
                $projects->whereBetween('year', [$request->start_year, $request->end_year]);
            } else {
                $projects->whereBetween('year', [$previousYear, $thisYear]);
            }
        return $projects;
    }

    private function filterCollegeExpense(Request $request)
    {
        $thisYear = Carbon::now()->year;
        $previousYear = $thisYear-2;

        $projects = DB::table('expenses')
            ->orderBy('expenses.year','ASC')
            ->where('expenses.school_or_college','2')
            ->where('expenses.status','1')
            ->join('scholarship_colleges', 'expenses.scholarship_college_id', '=', 'scholarship_colleges.id')
            ->select('expenses.year as year','scholarship_colleges.name as name', DB::raw('sum(amount) as total_amount'),DB::raw('count(expenses.id) as total_expenses'))
            ->groupBy('scholarship_college_id','year');
            if ($request->start_year && $request->end_year) {
                $projects->whereBetween('year', [$request->start_year, $request->end_year]);
            } else {
                $projects->whereBetween('year', [$previousYear, $thisYear]);
            }
        return $projects;
    }

    private function filterCollege(Request $request)
    {
        $thisYear = Carbon::now()->year;
        $previousYear = $thisYear-2;

        $projects = DB::table('scholarships')
            ->orderBy('scholarships.year','ASC')
            ->where('scholarships.school_or_college','2')
            ->where('scholarships.status','payment_done')
            ->join('scholarship_colleges', 'scholarships.scholarship_college_id', '=', 'scholarship_colleges.id')
            ->select('scholarships.year as year','scholarship_colleges.name as name', DB::raw('sum(fee_amount) as total_amount'),DB::raw('count(scholarships.id) as total_student'))
            ->groupBy('scholarship_college_id','year');

        if ($request->start_year && $request->end_year) {
            $projects->whereBetween('year', [$request->start_year, $request->end_year]);
        } else {
            $projects->whereBetween('year', [$previousYear, $thisYear]);
        }
        return $projects;
    }

    private function filterVillage(Request $request)
    {
        $thisYear = Carbon::now()->year;
        $previousYear = $thisYear-2;

        $projects = DB::table('scholarships')
            ->orderBy('scholarships.year','ASC')
            ->where('scholarships.status','payment_done')
            ->join('student_details', 'student_details.id', '=', 'scholarships.student_detail_id')
            ->join('scholarship_villages', 'scholarship_villages.id', '=', 'student_details.scholarship_village_id')
            ->select('scholarships.year as year','scholarship_villages.name as name', DB::raw('sum(fee_amount) as total_amount'),DB::raw('count(scholarships.id) as total_student'))
            ->groupBy('scholarship_village_id','year');
            if ($request->start_year && $request->end_year) {
                $projects->whereBetween('year', [$request->start_year, $request->end_year]);
            } else {
                $projects->whereBetween('year', [$previousYear, $thisYear]);
            }
        return $projects;
    }

    private function filterCourse(Request $request)
    {
        $thisYear = Carbon::now()->year;
        $previousYear = $thisYear-2;

        $projects = DB::table('scholarships')
            ->orderBy('scholarships.year','ASC')
            ->where('scholarships.status','payment_done')
            ->join('scholarship_classes', 'scholarships.school_grade', '=', 'scholarship_classes.id')
            ->select('scholarships.year as year','scholarship_classes.name as name', DB::raw('sum(fee_amount) as total_amount'),DB::raw('count(scholarships.id) as total_student'))
            ->groupBy('school_grade','year');
            if ($request->start_year && $request->end_year) {
                $projects->whereBetween('year', [$request->start_year, $request->end_year]);
            } else {
                $projects->whereBetween('year', [$previousYear, $thisYear]);
            }
        return $projects;
    }

    private function filterStudent(Request $request)
    {
        $thisYear = Carbon::now()->year;
        $previousYear = $thisYear-2;

        $projects = DB::table('scholarships')
            ->orderBy('scholarships.year','ASC')
            ->where('scholarships.status','payment_done')
            ->join('users', 'scholarships.user_id', '=', 'users.id')
            ->select('scholarships.year as year','users.name as name', DB::raw('sum(fee_amount) as total_amount'),DB::raw('count(users.id) as total_student'))
            ->groupBy('users.id','year');
            if ($request->start_year && $request->end_year) {
                $projects->whereBetween('year', [$request->start_year, $request->end_year]);
            } else {
                $projects->whereBetween('year', [$previousYear, $thisYear]);
            }
        return $projects;
    }
}
