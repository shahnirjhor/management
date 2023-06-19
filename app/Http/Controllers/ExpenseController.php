<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\ScholarshipVillage;
use App\Models\ScholarshipSchool;
use App\Models\ScholarshipCollege;
use App\Models\ScholarshipYear;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    /**
     * load constructor method
     *
     * @access public
     * @return void
     */
    function __construct()
    {
        $this->middleware('permission:expense-read|expense-create|expense-update|expense-delete', ['only' => ['index']]);
        $this->middleware('permission:expense-create', ['only' => ['create','store']]);
        $this->middleware('permission:expense-update', ['only' => ['edit','update']]);
        $this->middleware('permission:expense-delete', ['only' => ['destroy']]);
        $this->middleware('permission:expense-export', ['only' => ['doExport']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $expenses = $this->filter($request)->paginate(10)->withQueryString();
        $villages = ScholarshipVillage::where('company_id', session('company_id'))->where('status', 1)->orderBy('name')->pluck('name', 'id');
        $schools = ScholarshipSchool::where('company_id', session('company_id'))->where('status', 1)->orderBy('name')->pluck('name', 'id');
        $colleges = ScholarshipCollege::where('company_id', session('company_id'))->where('status', 1)->orderBy('name')->pluck('name', 'id');
        return view('expenses.index',compact('expenses','villages','schools','colleges'));
    }

    private function filter(Request $request)
    {
        $query = Expense::where('company_id', session('company_id'))->latest();

        if ($request->name)
            $query->where('name', 'like', '%'.$request->name.'%');

        if ($request->school_or_college)
            $query->where('school_or_college', 'like', $request->school_or_college);

        if ($request->scholarship_school_id)
            $query->where('scholarship_school_id', 'like', $request->scholarship_school_id);

        if ($request->scholarship_college_id)
            $query->where('scholarship_college_id', 'like', $request->scholarship_college_id);

        if ($request->scholarship_village_id)
            $query->where('scholarship_village_id', 'like', '%'.$request->scholarship_village_id.'%');

        return $query;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $schools = ScholarshipSchool::where('company_id', session('company_id'))->where('status', 1)->orderBy('name')->pluck('name', 'id');
        $colleges = ScholarshipCollege::where('company_id', session('company_id'))->where('status', 1)->orderBy('name')->pluck('name', 'id');
        $villages = ScholarshipVillage::where('company_id', session('company_id'))->where('status', 1)->orderBy('name')->pluck('name', 'id');
        $years = ScholarshipYear::where('company_id', session('company_id'))->where('status', 1)->orderBy('name')->pluck('name', 'name');
        return view('expenses.create', compact('schools','colleges','villages','years'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validation($request);
        $data = $request->only(['name','year','school_or_college','scholarship_school_id','scholarship_college_id','scholarship_village_id','amount']);

        DB::transaction(function () use ($data) {
            Expense::create($data);
        });

        return redirect()->route('expense.index')->with('success', trans('Expense Added Successfully'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function show(Expense $expense)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function edit(Expense $expense)
    {
        $schools = ScholarshipSchool::where('company_id', session('company_id'))->where('status', 1)->orderBy('name')->pluck('name', 'id');
        $colleges = ScholarshipCollege::where('company_id', session('company_id'))->where('status', 1)->orderBy('name')->pluck('name', 'id');
        $villages = ScholarshipVillage::where('company_id', session('company_id'))->where('status', 1)->orderBy('name')->pluck('name', 'id');
        $years = ScholarshipYear::where('company_id', session('company_id'))->where('status', 1)->orderBy('name')->pluck('name', 'name');
        return view('expenses.edit', compact('schools','colleges','villages','years','expense'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Expense $expense)
    {
        $this->validation($request);
        $data = $request->only(['name','year','school_or_college','scholarship_school_id','scholarship_college_id','scholarship_village_id','amount']);

        DB::transaction(function () use ($data, $expense) {
            $expense->update($data);
        });
        return redirect()->route('expense.index')->with('success', trans('Expense Update Successfully'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function destroy(Expense $expense)
    {
        $expense->delete();
        return redirect()->route('expense.index')->with('success', trans('Expense Deleted Successfully'));
    }

    private function validation(Request $request, $id = 0)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'school_or_college' => ['required', 'in:1,2'],
            'year' => ['required', 'numeric'],
            'scholarship_village_id' => ['required', 'string'],
            'amount' => ['required', 'numeric']
        ]);

        if ($request->school_or_college == '1') {
            $request->validate(['scholarship_school_id' => ['required', 'string','max:255'],]);
        }
        if ($request->school_or_college == '2') {
            $request->validate(['scholarship_college_id' => ['required', 'string','max:255'],]);
        }
    }
}
