<?php

namespace App\Http\Controllers;

use Session;
use App\Models\Company;
use App\Exports\CollegesExport;
use App\Models\ScholarshipCollege;
use App\Models\ScholarshipVillage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ScholarshipCollegeController extends Controller
{
    /**
     * load constructor method
     *
     * @access public
     * @return void
     */
    function __construct()
    {
        $this->middleware('permission:college-read|college-create|college-update|college-delete', ['only' => ['index']]);
        $this->middleware('permission:college-create', ['only' => ['create','store']]);
        $this->middleware('permission:college-update', ['only' => ['edit','update']]);
        $this->middleware('permission:college-delete', ['only' => ['destroy']]);
        $this->middleware('permission:college-export', ['only' => ['doExport']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->export)
            return $this->doExport($request);
        $colleges = $this->filter($request)->paginate(10);
        return view('colleges.index', compact('colleges'));
    }

    private function filter(Request $request)
    {
        $query = ScholarshipCollege::where('company_id', session('company_id'))->latest();

        if ($request->name)
            $query->where('name', 'like', '%'.$request->name.'%');

        if ($request->college_type)
            $query->where('college_type', 'like', '%'.$request->college_type.'%');

        if ($request->status > -1)
            $query->where('status', $request->enabled);

        return $query;
    }

    /**
     * Performs exporting
     *
     * @param Request $request
     * @return void
     */
    private function doExport(Request $request)
    {
        return Excel::download(new CollegesExport($request, session('company_id')), 'colleges.xlsx');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $villages = ScholarshipVillage::where('company_id', session('company_id'))->where('status', 1)->orderBy('name')->pluck('name', 'id');
        return view('colleges.create', compact('villages'));
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
        $data = $request->only(['name','college_type','website','email','scholarship_village_id','district','description','status']);
        // if ($request->picture) {
        //     $data['picture'] = $request->picture->store('item-images');
        // }

        $imageUrl = "";
        if ($request->picture) {

            $picture = $request->picture;
            $logoNewName = time().$picture->getClientOriginalName();
            $picture->move('lara/college',$logoNewName);
            $imageUrl = 'lara/college/'.$logoNewName;

            $data['picture'] = $imageUrl;
        }

        DB::transaction(function () use ($data) {
            ScholarshipCollege::create($data);
        });

        return redirect()->route('scholarship-college.index')->with('success', trans('College Added Successfully'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ScholarshipCollege  $scholarshipCollege
     * @return \Illuminate\Http\Response
     */
    public function show(ScholarshipCollege $scholarshipCollege)
    {
        return view('colleges.show', compact('scholarshipCollege'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ScholarshipCollege  $scholarshipCollege
     * @return \Illuminate\Http\Response
     */
    public function edit(ScholarshipCollege $scholarshipCollege)
    {
        $villages = ScholarshipVillage::where('company_id', session('company_id'))->where('status', 1)->orderBy('name')->pluck('name', 'id');
        return view('colleges.edit', compact('scholarshipCollege','villages'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ScholarshipCollege  $scholarshipCollege
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ScholarshipCollege $scholarshipCollege)
    {
        $this->validation($request, $scholarshipCollege->id);
        $data = $request->only(['name','college_type','website','email','scholarship_village_id','district','description','status']);
        if ($request->picture) {

            $picture = $request->picture;
            $logoNewName = time().$picture->getClientOriginalName();
            $picture->move('lara/college',$logoNewName);
            $imageUrl = 'lara/college/'.$logoNewName;

            $data['picture'] = $imageUrl;
        }
        DB::transaction(function () use ($data, $scholarshipCollege) {
            $scholarshipCollege->update($data);
        });
        return redirect()->route('scholarship-college.index')->with('success', trans('College Update Successfully'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ScholarshipCollege  $scholarshipCollege
     * @return \Illuminate\Http\Response
     */
    public function destroy(ScholarshipCollege $scholarshipCollege)
    {
        $scholarshipCollege->delete();
        return redirect()->route('scholarship-college.index')->with('success', trans('College Deleted Successfully'));
    }

    private function validation(Request $request, $id = 0)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'college_type' => ['nullable', 'in:Govt.,Govt. Aided,Private'],
            'website' => ['nullable', 'url'],
            'email' => ['nullable', 'email'],
            'scholarship_village_id' => ['nullable', 'string', 'max:255'],
            'district' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'in:0,1'],
            'picture' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:6048']
        ]);
    }
}
