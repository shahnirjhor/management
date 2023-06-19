<?php

namespace App\Http\Controllers;

use Session;
use App\Models\Company;
use App\Exports\SchoolsExport;
use App\Models\ScholarshipSchool;
use App\Models\ScholarshipVillage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ScholarshipSchoolController extends Controller
{
    /**
     * load constructor method
     *
     * @access public
     * @return void
     */
    function __construct()
    {
        $this->middleware('permission:school-read|school-create|school-update|school-delete', ['only' => ['index']]);
        $this->middleware('permission:school-create', ['only' => ['create','store']]);
        $this->middleware('permission:school-update', ['only' => ['edit','update']]);
        $this->middleware('permission:school-delete', ['only' => ['destroy']]);
        $this->middleware('permission:school-export', ['only' => ['doExport']]);
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
        $schools = $this->filter($request)->paginate(10);
        return view('schools.index', compact('schools'));
    }

    private function filter(Request $request)
    {
        $query = ScholarshipSchool::where('company_id', session('company_id'))->latest();

        if ($request->name)
            $query->where('name', 'like', '%'.$request->name.'%');

        if ($request->school_type)
            $query->where('school_type', 'like', '%'.$request->school_type.'%');

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
        return Excel::download(new SchoolsExport($request, session('company_id')), 'schools.xlsx');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $villages = ScholarshipVillage::where('company_id', session('company_id'))->where('status', 1)->orderBy('name')->pluck('name', 'id');
        return view('schools.create', compact('villages'));
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
        $data = $request->only(['name','school_type','website','email','scholarship_village_id','district','description','status']);
        $imageUrl = "";
        if ($request->picture) {

            $picture = $request->picture;
            $logoNewName = time().$picture->getClientOriginalName();
            $picture->move('lara/school',$logoNewName);
            $imageUrl = 'lara/school/'.$logoNewName;

            $data['picture'] = $imageUrl;
        }
        DB::transaction(function () use ($data) {
            ScholarshipSchool::create($data);
        });

        return redirect()->route('scholarship-school.index')->with('success', trans('School Added Successfully'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ScholarshipSchool  $scholarshipSchool
     * @return \Illuminate\Http\Response
     */
    public function show(ScholarshipSchool $scholarshipSchool)
    {
        return view('schools.show', compact('scholarshipSchool'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ScholarshipSchool  $scholarshipSchool
     * @return \Illuminate\Http\Response
     */
    public function edit(ScholarshipSchool $scholarshipSchool)
    {
        $villages = ScholarshipVillage::where('company_id', session('company_id'))->where('status', 1)->orderBy('name')->pluck('name', 'id');
        return view('schools.edit', compact('scholarshipSchool','villages'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ScholarshipSchool  $scholarshipSchool
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ScholarshipSchool $scholarshipSchool)
    {
        $this->validation($request, $scholarshipSchool->id);
        $data = $request->only(['name','school_type','website','email','scholarship_village_id','district','description','status']);
        // if ($request->picture) {
        //     $data['picture'] = $request->picture->store('item-images');
        // }

        if ($request->picture) {

            $picture = $request->picture;
            $logoNewName = time().$picture->getClientOriginalName();
            $picture->move('lara/school',$logoNewName);
            $imageUrl = 'lara/school/'.$logoNewName;

            $data['picture'] = $imageUrl;
        }
        DB::transaction(function () use ($data, $scholarshipSchool) {
            $scholarshipSchool->update($data);
        });
        return redirect()->route('scholarship-school.index')->with('success', trans('School Update Successfully'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ScholarshipSchool  $scholarshipSchool
     * @return \Illuminate\Http\Response
     */
    public function destroy(ScholarshipSchool $scholarshipSchool)
    {
        $scholarshipSchool->delete();
        return redirect()->route('scholarship-school.index')->with('success', trans('School Deleted Successfully'));
    }

    private function validation(Request $request, $id = 0)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'school_type' => ['nullable', 'in:Govt.,Govt. Aided,Private'],
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
