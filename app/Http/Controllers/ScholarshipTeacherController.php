<?php

namespace App\Http\Controllers;

use App\Models\ScholarshipCollege;
use App\Models\ScholarshipSchool;
use App\Models\ScholarshipTeacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TeachersExport;

class ScholarshipTeacherController extends Controller
{
    /**
     * load constructor method
     *
     * @access public
     * @return void
     */
    function __construct()
    {
        $this->middleware('permission:teacher-read|teacher-create|teacher-update|teacher-delete', ['only' => ['index']]);
        $this->middleware('permission:teacher-create', ['only' => ['create','store']]);
        $this->middleware('permission:teacher-update', ['only' => ['edit','update']]);
        $this->middleware('permission:teacher-delete', ['only' => ['destroy']]);
        $this->middleware('permission:teacher-export', ['only' => ['doExport']]);
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

        $teachers = $this->filter($request)->paginate(10);
        return view('teachers.index', compact('teachers'));
    }

    private function filter(Request $request)
    {
        $query = ScholarshipTeacher::where('company_id', session('company_id'))->latest();

        if ($request->name)
            $query->where('name', 'like', '%'.$request->name.'%');

        if ($request->school_or_college)
            $query->where('school_or_college', 'like', $request->school_or_college);

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
        return Excel::download(new TeachersExport($request, session('company_id')), 'teachers.xlsx');
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
        return view('teachers.create', compact('schools','colleges'));
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
        $data = $request->only(['name','email','school_or_college','scholarship_school_id','scholarship_college_id','phone','date_of_birth','gender','blood_group','address','status']);
        // if ($request->picture) {
        //     $data['photo'] = $request->picture->store('item-images');
        // }

        $imageUrl = "";
        if ($request->photo) {

            $picture = $request->photo;
            $logoNewName = time().$picture->getClientOriginalName();
            $picture->move('lara/teacher',$logoNewName);
            $imageUrl = 'lara/teacher/'.$logoNewName;

            $data['photo'] = $imageUrl;
        }

        DB::transaction(function () use ($data) {
            ScholarshipTeacher::create($data);
        });

        return redirect()->route('scholarship-teacher.index')->with('success', trans('Teacher Added Successfully'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ScholarshipTeacher  $scholarshipTeacher
     * @return \Illuminate\Http\Response
     */
    public function show(ScholarshipTeacher $scholarshipTeacher)
    {
        if($scholarshipTeacher->school_or_college == '1'){
            $info = ScholarshipSchool::find($scholarshipTeacher->scholarship_school_id);
        } else {
            $info = ScholarshipCollege::find($scholarshipTeacher->scholarship_college_id);
        }
        return view('teachers.show', compact('scholarshipTeacher','info'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ScholarshipTeacher  $scholarshipTeacher
     * @return \Illuminate\Http\Response
     */
    public function edit(ScholarshipTeacher $scholarshipTeacher)
    {
        $schools = ScholarshipSchool::where('company_id', session('company_id'))->where('status', 1)->orderBy('name')->pluck('name', 'id');
        $colleges = ScholarshipCollege::where('company_id', session('company_id'))->where('status', 1)->orderBy('name')->pluck('name', 'id');
        return view('teachers.edit', compact('schools','colleges','scholarshipTeacher'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ScholarshipTeacher  $scholarshipTeacher
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ScholarshipTeacher $scholarshipTeacher)
    {
        $this->validation($request);
        $data = $request->only(['name','email','school_or_college','scholarship_school_id','scholarship_college_id','phone','date_of_birth','gender','blood_group','address','status']);
        // if ($request->picture) {
        //     $data['photo'] = $request->picture->store('item-images');
        // }

        if ($request->photo) {

            $picture = $request->photo;
            $logoNewName = time().$picture->getClientOriginalName();
            $picture->move('lara/teacher',$logoNewName);
            $imageUrl = 'lara/teacher/'.$logoNewName;

            $data['photo'] = $imageUrl;
        }

        DB::transaction(function () use ($data, $scholarshipTeacher) {
            $scholarshipTeacher->update($data);
        });
        return redirect()->route('scholarship-teacher.index')->with('success', trans('Teacher Update Successfully'));
    }

    private function validation(Request $request, $id = 0)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email'],
            'school_or_college' => ['required', 'in:1,2'],
            'phone' => ['nullable', 'string', 'max:255'],
            'date_of_birth' => ['nullable', 'string', 'max:255'],
            'gender' => ['nullable', 'in:male,female'],
            'blood_group' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'in:0,1'],
            'picture' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:6048']
        ]);

        if($request->school_or_college == "1") //school
        {
            $this->validate($request, [
                'scholarship_school_id' => ['required', 'integer'],
            ]);
        }

        if($request->school_or_college == "2") //college
        {
            $this->validate($request, [
                'scholarship_college_id' => ['required', 'integer'],
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ScholarshipTeacher  $scholarshipTeacher
     * @return \Illuminate\Http\Response
     */
    public function destroy(ScholarshipTeacher $scholarshipTeacher)
    {
        $scholarshipTeacher->delete();
        return redirect()->route('scholarship-teacher.index')->with('success', trans('Teacher Deleted Successfully'));
    }
}
