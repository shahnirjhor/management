<?php

namespace App\Http\Controllers;

use App\Models\ScholarshipClass;
use Illuminate\Http\Request;

class ScholarshipClassController extends Controller
{
    /**
     * load constructor method
     *
     * @access public
     * @return void
     */
    function __construct()
    {
        $this->middleware('permission:class-read|class-create|class-update|class-delete', ['only' => ['index']]);
        $this->middleware('permission:class-create', ['only' => ['create','store']]);
        $this->middleware('permission:class-update', ['only' => ['edit','update']]);
        $this->middleware('permission:class-delete', ['only' => ['destroy']]);
        $this->middleware('permission:class-export', ['only' => ['doExport']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $classes = $this->filter($request)->paginate(10);
        return view('classes.index', compact('classes'));
    }

    /**
     * Filter function
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function filter(Request $request)
    {
        $query = ScholarshipClass::where('company_id', session('company_id'));
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
        return view('classes.create');
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
        ScholarshipClass::create($data);
        return redirect()->route('scholarship-class.index')->with('success', trans('Class Create Successfully'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ScholarshipClass  $scholarshipClass
     * @return \Illuminate\Http\Response
     */
    public function show(ScholarshipClass $scholarshipClass)
    {
        #
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ScholarshipClass  $scholarshipClass
     * @return \Illuminate\Http\Response
     */
    public function edit(ScholarshipClass $scholarshipClass)
    {
        return view('classes.edit', compact('scholarshipClass'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ScholarshipClass  $scholarshipClass
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ScholarshipClass $scholarshipClass)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'status' => 'required',
        ]);
        $data = $request->only(['name', 'status']);
        $scholarshipClass->update($data);
        return redirect()->route('scholarship-class.index')->with('success', trans('Class Updated Successfully'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ScholarshipClass  $scholarshipClass
     * @return \Illuminate\Http\Response
     */
    public function destroy(ScholarshipClass $scholarshipClass)
    {
        $scholarshipClass->delete();
        return redirect()->route('scholarship-class.index')->with('success', trans('Class Deleted Successfully'));
    }
}
