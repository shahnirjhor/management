<?php

namespace App\Http\Controllers;

use App\Models\ScholarshipYear;
use Illuminate\Http\Request;

class ScholarshipYearController extends Controller
{
    /**
     * load constructor method
     *
     * @access public
     * @return void
     */
    function __construct()
    {
        $this->middleware('permission:year-read|year-create|year-update|year-delete', ['only' => ['index']]);
        $this->middleware('permission:year-create', ['only' => ['create','store']]);
        $this->middleware('permission:year-update', ['only' => ['edit','update']]);
        $this->middleware('permission:year-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $years = $this->filter($request)->paginate(10);
        return view('years.index', compact('years'));
    }

    /**
     * Filter function
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function filter(Request $request)
    {
        $query = ScholarshipYear::where('company_id', session('company_id'));
        if ($request->name)
            $query->where('name', 'like', $request->name.'%');
        if (isset($request->status))
            $query->where('status', $request->status);
        return $query;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('years.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'status' => 'required'
        ]);
        $data = $request->only(['name','status']);
        ScholarshipYear::create($data);
        return redirect()->route('scholarship-year.index')->with('success', trans('Year Create Successfully'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ScholarshipYear  $scholarshipYear
     * @return \Illuminate\Http\Response
     */
    public function show(ScholarshipYear $scholarshipYear)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ScholarshipYear  $scholarshipYear
     * @return \Illuminate\Http\Response
     */
    public function edit(ScholarshipYear $scholarshipYear)
    {
        return view('years.edit', compact('scholarshipYear'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ScholarshipYear  $scholarshipYear
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ScholarshipYear $scholarshipYear)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'status' => 'required',
        ]);
        $data = $request->only(['name', 'status']);
        $scholarshipYear->update($data);
        return redirect()->route('scholarship-year.index')->with('success', trans('Year Updated Successfully'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ScholarshipYear  $scholarshipYear
     * @return \Illuminate\Http\Response
     */
    public function destroy(ScholarshipYear $scholarshipYear)
    {
        $scholarshipYear->delete();
        return redirect()->route('scholarship-year.index')->with('success', trans('Year Deleted Successfully'));
    }
}
