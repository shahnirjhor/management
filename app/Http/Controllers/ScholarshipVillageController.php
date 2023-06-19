<?php

namespace App\Http\Controllers;

use App\Models\ScholarshipVillage;
use Illuminate\Http\Request;

class ScholarshipVillageController extends Controller
{
    /**
     * load constructor method
     *
     * @access public
     * @return void
     */
    function __construct()
    {
        $this->middleware('permission:village-read|village-create|village-update|village-delete', ['only' => ['index']]);
        $this->middleware('permission:village-create', ['only' => ['create','store']]);
        $this->middleware('permission:village-update', ['only' => ['edit','update']]);
        $this->middleware('permission:village-delete', ['only' => ['destroy']]);
        $this->middleware('permission:village-export', ['only' => ['doExport']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $villages = $this->filter($request)->paginate(10);
        return view('villages.index', compact('villages'));
    }

    /**
     * Filter function
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function filter(Request $request)
    {
        $query = ScholarshipVillage::where('company_id', session('company_id'));
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
        return view('villages.create');
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
        ScholarshipVillage::create($data);
        return redirect()->route('scholarship-village.index')->with('success', trans('Village Create Successfully'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ScholarshipVillage  $scholarshipVillage
     * @return \Illuminate\Http\Response
     */
    public function show(ScholarshipVillage $scholarshipVillage)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ScholarshipVillage  $scholarshipVillage
     * @return \Illuminate\Http\Response
     */
    public function edit(ScholarshipVillage $scholarshipVillage)
    {
        return view('villages.edit', compact('scholarshipVillage'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ScholarshipVillage  $scholarshipVillage
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ScholarshipVillage $scholarshipVillage)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'status' => 'required',
        ]);
        $data = $request->only(['name', 'status']);
        $scholarshipVillage->update($data);
        return redirect()->route('scholarship-village.index')->with('success', trans('Village Updated Successfully'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ScholarshipVillage  $scholarshipVillage
     * @return \Illuminate\Http\Response
     */
    public function destroy(ScholarshipVillage $scholarshipVillage)
    {
        $scholarshipVillage->delete();
        return redirect()->route('scholarship-village.index')->with('success', trans('Village Deleted Successfully'));
    }
}
