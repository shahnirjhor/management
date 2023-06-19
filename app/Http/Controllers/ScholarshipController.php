<?php

namespace App\Http\Controllers;

use Auth;
use Session;
use App\Models\Company;
use App\Models\Scholarship;
use App\Models\ScholarshipClass;
use App\Models\ScholarshipYear;
use App\Models\ScholarshipVillage;
use App\Models\ScholarshipSchool;
use App\Models\ScholarshipCollege;
use App\Models\ScholarshipBankDetail;
use App\Models\ScholarshipTeacher;
use App\Models\StudentDetail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
//use Pdf;

class ScholarshipController extends Controller
{
    /**
     * load constructor method
     *
     * @access public
     * @return void
     */
    function __construct()
    {
        $this->middleware('permission:scholarship-read|scholarship-create|scholarship-update|scholarship-delete', ['only' => ['index']]);
        $this->middleware('permission:scholarship-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:scholarship-update', ['only' => ['edit', 'update']]);
        $this->middleware('permission:scholarship-delete', ['only' => ['destroy']]);
        $this->middleware('permission:scholarship-export', ['only' => ['doExport']]);

        $this->middleware('permission:scholarship-pending-read', ['only' => ['pending']]);
        $this->middleware('permission:scholarship-approved-read', ['only' => ['approved']]);
        $this->middleware('permission:scholarship-payment_in_progress-read', ['only' => ['payment_in_progress']]);
        $this->middleware('permission:scholarship-payment_done-read', ['only' => ['payment_done']]);
        $this->middleware('permission:scholarship-rejected-read', ['only' => ['rejected']]);
        $this->middleware('permission:scholarship-all-read', ['only' => ['index']]);
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
        $scholarships = $this->filter($request)->paginate(10);
        $villages = ScholarshipVillage::where('company_id', session('company_id'))->where('status', 1)->orderBy('name')->pluck('name', 'id');
        $schools = ScholarshipSchool::where('company_id', session('company_id'))->where('status', 1)->orderBy('name')->pluck('name', 'id');
        $colleges = ScholarshipCollege::where('company_id', session('company_id'))->where('status', 1)->orderBy('name')->pluck('name', 'id');
        $years = ScholarshipYear::where('company_id', session('company_id'))->where('status', 1)->orderBy('name')->pluck('name', 'name');
        $title = "All Applications";
        return view('scholarships.index', compact('title', 'scholarships', 'villages', 'schools', 'colleges', 'years'));
    }

    public function pending(Request $request)
    {
        if ($request->export)
            return $this->doExport($request);
        $scholarships = $this->filterPending($request)->paginate(10);
        $villages = ScholarshipVillage::where('company_id', session('company_id'))->where('status', 1)->orderBy('name')->pluck('name', 'id');
        $schools = ScholarshipSchool::where('company_id', session('company_id'))->where('status', 1)->orderBy('name')->pluck('name', 'id');
        $colleges = ScholarshipCollege::where('company_id', session('company_id'))->where('status', 1)->orderBy('name')->pluck('name', 'id');
        $years = ScholarshipYear::where('company_id', session('company_id'))->where('status', 1)->orderBy('name')->pluck('name', 'name');
        $title = "Under Verification Applications";
        return view('scholarships.index', compact('title', 'scholarships', 'villages', 'schools', 'colleges', 'years'));
    }

    public function approved(Request $request)
    {
        if ($request->export)
            return $this->doExport($request);
        $scholarships = $this->filterApproved($request)->paginate(10);
        $villages = ScholarshipVillage::where('company_id', session('company_id'))->where('status', 1)->orderBy('name')->pluck('name', 'id');
        $schools = ScholarshipSchool::where('company_id', session('company_id'))->where('status', 1)->orderBy('name')->pluck('name', 'id');
        $colleges = ScholarshipCollege::where('company_id', session('company_id'))->where('status', 1)->orderBy('name')->pluck('name', 'id');
        $years = ScholarshipYear::where('company_id', session('company_id'))->where('status', 1)->orderBy('name')->pluck('name', 'name');
        $title = "Approved Applications";
        return view('scholarships.index', compact('title', 'scholarships', 'villages', 'schools', 'colleges', 'years'));
    }

    public function payment_in_progress(Request $request)
    {
        if ($request->export)
            return $this->doExport($request);
        $scholarships = $this->filterPaymentInProgress($request)->paginate(10);
        $villages = ScholarshipVillage::where('company_id', session('company_id'))->where('status', 1)->orderBy('name')->pluck('name', 'id');
        $schools = ScholarshipSchool::where('company_id', session('company_id'))->where('status', 1)->orderBy('name')->pluck('name', 'id');
        $colleges = ScholarshipCollege::where('company_id', session('company_id'))->where('status', 1)->orderBy('name')->pluck('name', 'id');
        $years = ScholarshipYear::where('company_id', session('company_id'))->where('status', 1)->orderBy('name')->pluck('name', 'name');
        $title = "Payment Processing Applications";
        return view('scholarships.index', compact('title', 'scholarships', 'villages', 'schools', 'colleges', 'years'));
    }

    public function payment_done(Request $request)
    {
        if ($request->export)
            return $this->doExport($request);
        $scholarships = $this->filterPaymentDone($request)->paginate(10);
        $villages = ScholarshipVillage::where('company_id', session('company_id'))->where('status', 1)->orderBy('name')->pluck('name', 'id');
        $schools = ScholarshipSchool::where('company_id', session('company_id'))->where('status', 1)->orderBy('name')->pluck('name', 'id');
        $colleges = ScholarshipCollege::where('company_id', session('company_id'))->where('status', 1)->orderBy('name')->pluck('name', 'id');
        $years = ScholarshipYear::where('company_id', session('company_id'))->where('status', 1)->orderBy('name')->pluck('name', 'name');
        $title = "Payment Completed Applications";
        return view('scholarships.index', compact('title', 'scholarships', 'villages', 'schools', 'colleges', 'years'));
    }

    public function rejected(Request $request)
    {
        if ($request->export)
            return $this->doExport($request);
        $scholarships = $this->filterRejected($request)->paginate(10);
        $villages = ScholarshipVillage::where('company_id', session('company_id'))->where('status', 1)->orderBy('name')->pluck('name', 'id');
        $schools = ScholarshipSchool::where('company_id', session('company_id'))->where('status', 1)->orderBy('name')->pluck('name', 'id');
        $colleges = ScholarshipCollege::where('company_id', session('company_id'))->where('status', 1)->orderBy('name')->pluck('name', 'id');
        $years = ScholarshipYear::where('company_id', session('company_id'))->where('status', 1)->orderBy('name')->pluck('name', 'name');
        $title = "Rejected Applications";
        return view('scholarships.index', compact('title', 'scholarships', 'villages', 'schools', 'colleges', 'years'));
    }

    private function filterRejected(Request $request)
    {
        $query = Scholarship::with(['studentDetail'])
            ->whereHas('studentDetail', function ($q) use ($request) {
                $q->where('company_id', session('company_id'));
                if ($request->scholarship_village_id)
                    $q->where('scholarship_village_id', 'like', $request->scholarship_village_id . '%');
            })
            ->where('company_id', session('company_id'))->latest();
        $roleName = Auth::user()->getRoleNames();
        if ($roleName[0] == "Student") {
            $query->where('scholarships.user_id', Auth::user()->id);
        }
        if ($request->application_no)
            $query->where('application_no', 'like', '%' . $request->application_no . '%');
        if ($request->year)
            $query->where('year', 'like', '%' . $request->year . '%');
        if ($request->school_or_college)
            $query->where('school_or_college', 'like', $request->school_or_college);
        if ($request->scholarship_school_id)
            $query->where('scholarship_school_id', 'like', $request->scholarship_school_id);
        if ($request->scholarship_college_id)
            $query->where('scholarship_college_id', 'like', $request->scholarship_college_id);

        $query->where('status', 'like', 'rejected');
        return $query;
    }

    private function filterPaymentDone(Request $request)
    {
        $query = Scholarship::with(['studentDetail'])
            ->whereHas('studentDetail', function ($q) use ($request) {
                $q->where('company_id', session('company_id'));
                if ($request->scholarship_village_id)
                    $q->where('scholarship_village_id', 'like', $request->scholarship_village_id . '%');
            })
            ->where('company_id', session('company_id'))->latest();
        $roleName = Auth::user()->getRoleNames();
        if ($roleName[0] == "Student") {
            $query->where('scholarships.user_id', Auth::user()->id);
        }
        if ($request->application_no)
            $query->where('application_no', 'like', '%' . $request->application_no . '%');
        if ($request->year)
            $query->where('year', 'like', '%' . $request->year . '%');
        if ($request->school_or_college)
            $query->where('school_or_college', 'like', $request->school_or_college);
        if ($request->scholarship_school_id)
            $query->where('scholarship_school_id', 'like', $request->scholarship_school_id);
        if ($request->scholarship_college_id)
            $query->where('scholarship_college_id', 'like', $request->scholarship_college_id);

        $query->where('status', 'like', 'payment_done');
        return $query;
    }

    private function filterPaymentInProgress(Request $request)
    {
        $query = Scholarship::with(['studentDetail'])
            ->whereHas('studentDetail', function ($q) use ($request) {
                $q->where('company_id', session('company_id'));
                if ($request->scholarship_village_id)
                    $q->where('scholarship_village_id', 'like', $request->scholarship_village_id . '%');
            })
            ->where('company_id', session('company_id'))->latest();
        $roleName = Auth::user()->getRoleNames();
        if ($roleName[0] == "Student") {
            $query->where('scholarships.user_id', Auth::user()->id);
        }
        if ($request->application_no)
            $query->where('application_no', 'like', '%' . $request->application_no . '%');
        if ($request->year)
            $query->where('year', 'like', '%' . $request->year . '%');
        if ($request->school_or_college)
            $query->where('school_or_college', 'like', $request->school_or_college);
        if ($request->scholarship_school_id)
            $query->where('scholarship_school_id', 'like', $request->scholarship_school_id);
        if ($request->scholarship_college_id)
            $query->where('scholarship_college_id', 'like', $request->scholarship_college_id);

        $query->where('status', 'like', 'payment_in_progress');
        return $query;
    }

    private function filterApproved(Request $request)
    {
        $query = Scholarship::with(['studentDetail'])
            ->whereHas('studentDetail', function ($q) use ($request) {
                $q->where('company_id', session('company_id'));
                if ($request->scholarship_village_id)
                    $q->where('scholarship_village_id', 'like', $request->scholarship_village_id . '%');
            })
            ->where('company_id', session('company_id'))->latest();
        $roleName = Auth::user()->getRoleNames();
        if ($roleName[0] == "Student") {
            $query->where('scholarships.user_id', Auth::user()->id);
        }
        if ($request->application_no)
            $query->where('application_no', 'like', '%' . $request->application_no . '%');
        if ($request->year)
            $query->where('year', 'like', '%' . $request->year . '%');
        if ($request->school_or_college)
            $query->where('school_or_college', 'like', $request->school_or_college);
        if ($request->scholarship_school_id)
            $query->where('scholarship_school_id', 'like', $request->scholarship_school_id);
        if ($request->scholarship_college_id)
            $query->where('scholarship_college_id', 'like', $request->scholarship_college_id);

        $query->where('status', 'like', 'approved');
        return $query;
    }

    private function filterPending(Request $request)
    {
        $query = Scholarship::with(['studentDetail'])
            ->whereHas('studentDetail', function ($q) use ($request) {
                $q->where('company_id', session('company_id'));
                if ($request->scholarship_village_id)
                    $q->where('scholarship_village_id', 'like', $request->scholarship_village_id . '%');
            })
            ->where('company_id', session('company_id'))->latest();
        $roleName = Auth::user()->getRoleNames();
        if ($roleName[0] == "Student") {
            $query->where('scholarships.user_id', Auth::user()->id);
        }
        if ($request->application_no)
            $query->where('application_no', 'like', '%' . $request->application_no . '%');
        if ($request->year)
            $query->where('year', 'like', '%' . $request->year . '%');
        if ($request->school_or_college)
            $query->where('school_or_college', 'like', $request->school_or_college);
        if ($request->scholarship_school_id)
            $query->where('scholarship_school_id', 'like', $request->scholarship_school_id);
        if ($request->scholarship_college_id)
            $query->where('scholarship_college_id', 'like', $request->scholarship_college_id);

        $query->where('status', 'like', 'pending');
        return $query;
    }

    private function filter(Request $request)
    {
        $query = Scholarship::with(['studentDetail'])
            ->whereHas('studentDetail', function ($q) use ($request) {
                $q->where('company_id', session('company_id'));
                if ($request->scholarship_village_id)
                    $q->where('scholarship_village_id', 'like', $request->scholarship_village_id . '%');
            })
            ->where('company_id', session('company_id'))->latest();
        $roleName = Auth::user()->getRoleNames();
        if ($roleName[0] == "Student") {
            $query->where('scholarships.user_id', Auth::user()->id);
        }
        if ($request->application_no)
            $query->where('application_no', 'like', '%' . $request->application_no . '%');
        if ($request->year)
            $query->where('year', 'like', '%' . $request->year . '%');
        if ($request->school_or_college)
            $query->where('school_or_college', 'like', $request->school_or_college);
        if ($request->scholarship_school_id)
            $query->where('scholarship_school_id', 'like', $request->scholarship_school_id);
        if ($request->scholarship_college_id)
            $query->where('scholarship_college_id', 'like', $request->scholarship_college_id);
        return $query;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roleName = Auth::user()->getRoleNames();
        $myRole = $roleName[0];
        $students = User::role('Student')->where('status', "1")->orderBy('name')->pluck('name', 'id');
        $company = Company::findOrFail(Session::get('company_id'));
        $company->setSettings();
        $number = $this->getNextInvoiceNumber($company);
        $years = ScholarshipYear::where('company_id', session('company_id'))->where('status', 1)->orderBy('name')->pluck('name', 'id');
        $classes = ScholarshipClass::where('company_id', session('company_id'))->where('status', 1)->orderBy('name')->pluck('name', 'id');
        $villages = ScholarshipVillage::where('company_id', session('company_id'))->where('status', 1)->orderBy('name')->pluck('name', 'id');
        $schools = ScholarshipSchool::where('company_id', session('company_id'))->where('status', 1)->orderBy('name')->pluck('name', 'id');
        $colleges = ScholarshipCollege::where('company_id', session('company_id'))->where('status', 1)->orderBy('name')->pluck('name', 'id');
        return view('scholarships.create', compact('number', 'years', 'classes', 'villages', 'schools', 'colleges','myRole','students'));
    }

    /**
     * Generate next invoice number
     *
     * @return string
     */
    public function getNextInvoiceNumber($company)
    {
        $prefix = $company->invoice_number_prefix;
        $next = $company->invoice_number_next;
        $digit = $company->invoice_number_digit;
        $number = $prefix . str_pad($next, $digit, '0', STR_PAD_LEFT);
        return $number;
    }

    /**
     * Increase the next invoice number
     */
    public function increaseNextInvoiceNumber($company)
    {
        $currentInvoice = $company->invoice_number_next;
        $next = $currentInvoice + 1;

        DB::table('settings')->where('company_id', $company->id)->where('key', 'general.invoice_number_next')->update(['value' => $next]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'application_no' => ['required', 'unique:scholarships,application_no', 'string', 'max:255'],
            'year' => ['required', 'string', 'max:255'],
            'annual_income' => ['required', 'numeric'],
            'percentage_marks_obtained' => ['required', 'numeric'],
            'full_name' => ['required', 'string', 'max:255'],
            'father_name' => ['required', 'string', 'max:255'],
            'father_occupation' => ['required', 'string', 'max:255'],
            'mother_name' => ['required', 'string', 'max:255'],
            'mother_occupation' => ['required', 'string', 'max:255'],
            'house_no' => ['required', 'string', 'max:255'],
            'scholarship_village_id' => ['required', 'numeric'],
            'street' => ['required', 'string', 'max:255'],
            'post_office' => ['required', 'string', 'max:255'],
            'taluk' => ['required', 'string', 'max:255'],
            'district' => ['required', 'string', 'max:255'],
            'pincode' => ['required', 'string', 'max:255'],
            'state' => ['required', 'string', 'max:255'],
            'date_of_birth' => ['required', 'date'],
            'contact_no_1' => ['required', 'string', 'max:255'],
            'contact_no_2' => ['required', 'string', 'max:255'],
            'gender' => ['required', 'in:male,female'],
            'age' => ['required', 'numeric'],
            'aadhar_no' => ['required', 'string', 'max:255'],
            'school_or_college' => ['required', 'in:1,2'],
            'school_or_college' => ['required', 'in:1,2'],
            'school_year' => ['required', 'numeric'],
            'school_grade' => ['required', 'numeric'],
            'school_contact_person' => ['required', 'string', 'max:255'],
            'school_designation' => ['required', 'in:Principal,Head,Teacher'],
            'school_contact_number' => ['required', 'string', 'max:255'],
            'marks_obtained_type' => ['required', 'in:SSLC,PUC,Degree'],
            'marks_obtained' => ['required', 'string', 'max:255'],
            'further_education_details_school_or_college' => ['required', 'in:1,2'],
            'further_education_details_course_joined' => ['required', 'string', 'max:255'],
            'bank_name' => ['required', 'string', 'max:255'],
            'branch' => ['required', 'string', 'max:255'],
            'account_holder_name' => ['required', 'string', 'max:255'],
            'account_no' => ['required', 'numeric'],
            'ifsc_code' => ['required', 'string', 'max:255'],
            'status' => ['required', 'in:Self,Father,Mother,Teacher'],
            'fee_amount' => ['required', 'string', 'max:255'],
            'date' => ['required', 'date'],
            'given_information' => ['required', 'in:1,0'],
            'any_other_scholarship' => ['required', 'in:1,0'],
            'scholarship_refunded' => ['required', 'in:1,0'],
            'photo' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:6048'],
            'income_certificate' => ['required', 'mimes:pdf', 'max:6048'],
            'id_proof' => ['required', 'mimes:pdf', 'max:6048'],
            'previous_educational_marks_card' => ['required', 'mimes:pdf', 'max:6048'],
            'bank_passbook' => ['required', 'mimes:pdf', 'max:6048'],
            'original_fee_receipt' => ['required', 'mimes:pdf', 'max:6048'],
        ]);
        if ($request->school_or_college == '1') {
            $request->validate(['scholarship_school_id' => ['required', 'string', 'max:255'],]);
        }
        if ($request->school_or_college == '2') {
            $request->validate(['scholarship_college_id' => ['required', 'string', 'max:255'],]);
        }
        if ($request->marks_obtained_type == 'PUC' || $request->marks_obtained_type == 'Degree') {
            $request->validate(['marks_subject' => ['required', 'string', 'max:255'],]);
        }
        if ($request->further_education_details_school_or_college == '1') {
            $request->validate(['further_education_details_scholarship_school_id' => ['required', 'string', 'max:255'],]);
        }
        if ($request->further_education_details_school_or_college == '2') {
            $request->validate(['further_education_details_scholarship_college_id' => ['required', 'string', 'max:255'],]);
        }

        $roleName = Auth::user()->getRoleNames();
        $myRole = $roleName[0];

        if($myRole == "Student") {
            DB::beginTransaction();
            try {
                $company = Company::findOrFail(Session::get('company_id'));
                $company->setSettings();
                $studentDetail = StudentDetail::create([
                    'user_id' => Auth::user()->id,
                    'full_name' => $request->full_name,
                    'father_name' => $request->father_name,
                    'father_occupation' => $request->father_occupation,
                    'mother_name' => $request->mother_name,
                    'mother_occupation' => $request->mother_occupation,
                    'house_no' => $request->house_no,
                    'scholarship_village_id' => $request->scholarship_village_id,
                    'street' => $request->street,
                    'post_office' => $request->post_office,
                    'taluk' => $request->taluk,
                    'district' => $request->district,
                    'pincode' => $request->pincode,
                    'state' => $request->state,
                    'contact_no_1' => $request->contact_no_1,
                    'contact_no_2' => $request->contact_no_2,
                    'date_of_birth' => $request->date_of_birth,
                    'age' => $request->age,
                    'gender' => $request->gender,
                    'aadhar_no' => $request->aadhar_no
                ]);

                $scholarshipBankDetail = ScholarshipBankDetail::create([
                    'user_id' => Auth::user()->id,
                    'bank_name' => $request->bank_name,
                    'branch' => $request->branch,
                    'account_holder_name' => $request->account_holder_name,
                    'account_no' => $request->account_no,
                    'ifsc_code' => $request->ifsc_code,
                    'status' => $request->status,
                ]);

                $imageUrl = "";
                if ($request->photo) {
                    $picture = $request->photo;
                    $logoNewName = time().$picture->getClientOriginalName();
                    $picture->move('lara/scholarship',$logoNewName);
                    $imageUrl = 'lara/scholarship/'.$logoNewName;
                }

                $incomeCertificateUrl = "";
                if ($request->income_certificate) {
                    $incomeFile = $request->income_certificate;
                    $incomeNewName = time().$incomeFile->getClientOriginalName();
                    $incomeFile->move('lara/scholarship',$incomeNewName);
                    $incomeCertificateUrl = 'lara/scholarship/'.$incomeNewName;
                }

                $idProofUrl = "";
                if ($request->id_proof) {
                    $idProof = $request->id_proof;
                    $idNewName = time().$idProof->getClientOriginalName();
                    $idProof->move('lara/scholarship',$idNewName);
                    $idProofUrl = 'lara/scholarship/'.$idNewName;
                }

                $previousEducationalMarksCardUrl = "";
                if ($request->previous_educational_marks_card) {
                    $pEduMarkCard = $request->previous_educational_marks_card;
                    $pEduMarkCardNewName = time().$pEduMarkCard->getClientOriginalName();
                    $pEduMarkCard->move('lara/scholarship',$pEduMarkCardNewName);
                    $previousEducationalMarksCardUrl = 'lara/scholarship/'.$pEduMarkCardNewName;
                }

                $bankPassbookUrl = "";
                if ($request->bank_passbook) {
                    $bankPassbookFile = $request->bank_passbook;
                    $bankPassNewName = time().$bankPassbookFile->getClientOriginalName();
                    $bankPassbookFile->move('lara/scholarship',$bankPassNewName);
                    $bankPassbookUrl = 'lara/scholarship/'.$bankPassNewName;
                }

                $originalFeeReceiptUrl = "";
                if ($request->original_fee_receipt) {
                    $originalFeeReceipt = $request->original_fee_receipt;
                    $oFeeNewName = time().$originalFeeReceipt->getClientOriginalName();
                    $originalFeeReceipt->move('lara/scholarship',$oFeeNewName);
                    $originalFeeReceiptUrl = 'lara/scholarship/'.$oFeeNewName;
                }

                $scholarship = Scholarship::create([
                    'user_id' => Auth::user()->id,
                    'application_no' => $request->application_no,
                    'year' => $request->year,
                    'annual_income' => $request->annual_income,
                    'percentage_marks_obtained' => $request->percentage_marks_obtained,
                    'student_detail_id' => $studentDetail->id,
                    'school_or_college' => $request->school_or_college,
                    'scholarship_school_id' => $request->scholarship_school_id,
                    'scholarship_college_id' => $request->scholarship_college_id,
                    'school_year' => $request->school_year,
                    'school_grade' => $request->school_grade,
                    'school_contact_person' => $request->school_contact_person,
                    'school_designation' => $request->school_designation,
                    'school_contact_number' => $request->school_contact_number,
                    'marks_obtained_type' => $request->marks_obtained_type,
                    'marks_subject' => $request->marks_subject,
                    'marks_obtained' => $request->marks_obtained,
                    'further_education_details_school_or_college' => $request->further_education_details_school_or_college,
                    'further_education_details_scholarship_school_id' => $request->further_education_details_scholarship_school_id,
                    'further_education_details_scholarship_college_id' => $request->further_education_details_scholarship_college_id,
                    'further_education_details_course_joined' => $request->further_education_details_course_joined,
                    'fee_amount' => $request->fee_amount,
                    'apply_amount' => $request->fee_amount,
                    'date' => $request->date,
                    'given_information' => $request->given_information,
                    'any_other_scholarship' => $request->any_other_scholarship,
                    'scholarship_bank_detail_id' => $scholarshipBankDetail->id,
                    'scholarship_refunded' => $request->scholarship_refunded,
                    'photo' => $imageUrl,
                    'income_certificate' => $incomeCertificateUrl,
                    'id_proof' => $idProofUrl,
                    'previous_educational_marks_card' => $previousEducationalMarksCardUrl,
                    'bank_passbook' => $bankPassbookUrl,
                    'original_fee_receipt' => $originalFeeReceiptUrl,
                ]);
                // Update next invoice number
                $this->increaseNextInvoiceNumber($company);
                DB::commit();
                Session::flash('successMessage', 1);
                echo json_encode(array("status" => 1));
            } catch (Exception $e) {
                DB::rollback();
                Session::flash('errorMessage', 1);
                echo json_encode(array("status" => 0));
            }
        } else {
            $myStudentId = $request->full_name;
            $myStudentInfo = User::findOrFail($myStudentId);

            DB::beginTransaction();
            try {
                $company = Company::findOrFail(Session::get('company_id'));
                $company->setSettings();
                $studentDetail = StudentDetail::create([
                    'user_id' => $myStudentInfo->id,
                    'full_name' => $myStudentInfo->name,
                    'father_name' => $request->father_name,
                    'father_occupation' => $request->father_occupation,
                    'mother_name' => $request->mother_name,
                    'mother_occupation' => $request->mother_occupation,
                    'house_no' => $request->house_no,
                    'scholarship_village_id' => $request->scholarship_village_id,
                    'street' => $request->street,
                    'post_office' => $request->post_office,
                    'taluk' => $request->taluk,
                    'district' => $request->district,
                    'pincode' => $request->pincode,
                    'state' => $request->state,
                    'contact_no_1' => $request->contact_no_1,
                    'contact_no_2' => $request->contact_no_2,
                    'date_of_birth' => $request->date_of_birth,
                    'age' => $request->age,
                    'gender' => $request->gender,
                    'aadhar_no' => $request->aadhar_no
                ]);

                $scholarshipBankDetail = ScholarshipBankDetail::create([
                    'user_id' => $myStudentInfo->id,
                    'bank_name' => $request->bank_name,
                    'branch' => $request->branch,
                    'account_holder_name' => $request->account_holder_name,
                    'account_no' => $request->account_no,
                    'ifsc_code' => $request->ifsc_code,
                    'status' => $request->status,
                ]);

                $imageUrl = "";
                if ($request->photo) {
                    $picture = $request->photo;
                    $logoNewName = time().$picture->getClientOriginalName();
                    $picture->move('lara/scholarship',$logoNewName);
                    $imageUrl = 'lara/scholarship/'.$logoNewName;
                }

                $incomeCertificateUrl = "";
                if ($request->income_certificate) {
                    $incomeFile = $request->income_certificate;
                    $incomeNewName = time().$incomeFile->getClientOriginalName();
                    $incomeFile->move('lara/scholarship',$incomeNewName);
                    $incomeCertificateUrl = 'lara/scholarship/'.$incomeNewName;
                }

                $idProofUrl = "";
                if ($request->id_proof) {
                    $idProof = $request->id_proof;
                    $idNewName = time().$idProof->getClientOriginalName();
                    $idProof->move('lara/scholarship',$idNewName);
                    $idProofUrl = 'lara/scholarship/'.$idNewName;
                }

                $previousEducationalMarksCardUrl = "";
                if ($request->previous_educational_marks_card) {
                    $pEduMarkCard = $request->previous_educational_marks_card;
                    $pEduMarkCardNewName = time().$pEduMarkCard->getClientOriginalName();
                    $pEduMarkCard->move('lara/scholarship',$pEduMarkCardNewName);
                    $previousEducationalMarksCardUrl = 'lara/scholarship/'.$pEduMarkCardNewName;
                }

                $bankPassbookUrl = "";
                if ($request->bank_passbook) {
                    $bankPassbookFile = $request->bank_passbook;
                    $bankPassNewName = time().$bankPassbookFile->getClientOriginalName();
                    $bankPassbookFile->move('lara/scholarship',$bankPassNewName);
                    $bankPassbookUrl = 'lara/scholarship/'.$bankPassNewName;
                }

                $originalFeeReceiptUrl = "";
                if ($request->original_fee_receipt) {
                    $originalFeeReceipt = $request->original_fee_receipt;
                    $oFeeNewName = time().$originalFeeReceipt->getClientOriginalName();
                    $originalFeeReceipt->move('lara/scholarship',$oFeeNewName);
                    $originalFeeReceiptUrl = 'lara/scholarship/'.$oFeeNewName;
                }
                $scholarship = Scholarship::create([
                    'user_id' => $myStudentInfo->id,
                    'application_no' => $request->application_no,
                    'year' => $request->year,
                    'annual_income' => $request->annual_income,
                    'percentage_marks_obtained' => $request->percentage_marks_obtained,
                    'student_detail_id' => $studentDetail->id,
                    'school_or_college' => $request->school_or_college,
                    'scholarship_school_id' => $request->scholarship_school_id,
                    'scholarship_college_id' => $request->scholarship_college_id,
                    'school_year' => $request->school_year,
                    'school_grade' => $request->school_grade,
                    'school_contact_person' => $request->school_contact_person,
                    'school_designation' => $request->school_designation,
                    'school_contact_number' => $request->school_contact_number,
                    'marks_obtained_type' => $request->marks_obtained_type,
                    'marks_subject' => $request->marks_subject,
                    'marks_obtained' => $request->marks_obtained,
                    'further_education_details_school_or_college' => $request->further_education_details_school_or_college,
                    'further_education_details_scholarship_school_id' => $request->further_education_details_scholarship_school_id,
                    'further_education_details_scholarship_college_id' => $request->further_education_details_scholarship_college_id,
                    'further_education_details_course_joined' => $request->further_education_details_course_joined,
                    'fee_amount' => $request->fee_amount,
                    'apply_amount' => $request->fee_amount,
                    'date' => $request->date,
                    'given_information' => $request->given_information,
                    'any_other_scholarship' => $request->any_other_scholarship,
                    'scholarship_bank_detail_id' => $scholarshipBankDetail->id,
                    'scholarship_refunded' => $request->scholarship_refunded,
                    'photo' => $imageUrl,
                    'income_certificate' => $incomeCertificateUrl,
                    'id_proof' => $idProofUrl,
                    'previous_educational_marks_card' => $previousEducationalMarksCardUrl,
                    'bank_passbook' => $bankPassbookUrl,
                    'original_fee_receipt' => $originalFeeReceiptUrl,
                ]);

                // Update next invoice number
                $this->increaseNextInvoiceNumber($company);
                DB::commit();
                Session::flash('successMessage', 1);
                echo json_encode(array("status" => 1));
            } catch (Exception $e) {
                DB::rollback();
                Session::flash('errorMessage', 1);
                echo json_encode(array("status" => 0));
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Scholarship  $scholarship
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $scholarship = Scholarship::find($id);
        $cMark = intval($scholarship->percentage_marks_obtained);
        $aAmount = intval($scholarship->apply_amount);

        $eAmount = 0;

        if ($cMark > 85 && $cMark <= 101) {
            if ($aAmount > 20000) {
                $eAmount = 20000;
            } else {
                $eAmount = $aAmount;
            }
        } elseif ($cMark > 75 && $cMark <= 85) {
            if ($aAmount > 15000) {
                $eAmount = 15000;
            } else {
                $eAmount = $aAmount;
            }
        } else {
            if ($aAmount > 10000) {
                $eAmount = 10000;
            } else {
                $eAmount = $aAmount;
            }
        }
        //dd($scholarship);
        return view('scholarships.show', compact('scholarship', 'eAmount'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Scholarship  $scholarship
     * @return \Illuminate\Http\Response
     */
    public function edit($id = 0)
    {
        if ($id != 0) {
            $scholarship = Scholarship::with(['studentDetail', 'scholarshipBankDetail', 'scholarshipVillage'])->findOrFail($id);
            $cMark = intval($scholarship->percentage_marks_obtained);
            $aAmount = intval($scholarship->apply_amount);

            $eAmount = 0;

            if ($cMark > 85 && $cMark <= 101) {
                if ($aAmount > 20000) {
                    $eAmount = 20000;
                } else {
                    $eAmount = $aAmount;
                }
            } elseif ($cMark > 75 && $cMark <= 85) {
                if ($aAmount > 15000) {
                    $eAmount = 15000;
                } else {
                    $eAmount = $aAmount;
                }
            } else {
                if ($aAmount > 10000) {
                    $eAmount = 10000;
                } else {
                    $eAmount = $aAmount;
                }
            }


            // dd($eAmount);
            // die();
            $company = Company::findOrFail(Session::get('company_id'));
            $company->setSettings();
            $number = $this->getNextInvoiceNumber($company);
            $years = ScholarshipYear::where('company_id', session('company_id'))->where('status', 1)->orderBy('name')->pluck('name', 'id');
            $classes = ScholarshipClass::where('company_id', session('company_id'))->where('status', 1)->orderBy('name')->pluck('name', 'id');
            $villages = ScholarshipVillage::where('company_id', session('company_id'))->where('status', 1)->orderBy('name')->pluck('name', 'id');
            $schools = ScholarshipSchool::where('company_id', session('company_id'))->where('status', 1)->orderBy('name')->pluck('name', 'id');
            $colleges = ScholarshipCollege::where('company_id', session('company_id'))->where('status', 1)->orderBy('name')->pluck('name', 'id');
            return view('scholarships.edit', compact('eAmount', 'scholarship', 'number', 'years', 'classes', 'villages', 'schools', 'colleges'));
        } else {
            return false;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Scholarship  $scholarship
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $request->validate([
            'scholarship_id' => ['required'],
            'year' => ['required', 'string', 'max:255'],
            'annual_income' => ['required', 'numeric'],
            'father_name' => ['required', 'string', 'max:255'],
            'father_occupation' => ['required', 'string', 'max:255'],
            'mother_name' => ['required', 'string', 'max:255'],
            'mother_occupation' => ['required', 'string', 'max:255'],
            'date_of_birth' => ['required', 'date'],
            'age' => ['required', 'numeric'],
            'fee_amount' => ['required', 'string', 'max:255'],
            'aadhar_no' => ['required', 'string', 'max:255'],
            'school_year' => ['required', 'numeric'],
            'school_contact_person' => ['required', 'string', 'max:255'],
            'school_designation' => ['required', 'in:Principal,Head,Teacher'],
            'school_contact_number' => ['required', 'string', 'max:255'],
            'marks_obtained_type' => ['required', 'in:SSLC,PUC,Degree'],
            'marks_obtained' => ['required', 'string', 'max:255'],
            'further_education_details_school_or_college' => ['required', 'in:1,2'],
            'further_education_details_course_joined' => ['required', 'string', 'max:255'],
            'date' => ['required', 'date'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:6048'],
            'income_certificate' => ['nullable', 'mimes:pdf', 'max:6048'],
            'id_proof' => ['nullable', 'mimes:pdf', 'max:6048'],
            'previous_educational_marks_card' => ['nullable', 'mimes:pdf', 'max:6048'],
            'bank_passbook' => ['nullable', 'mimes:pdf', 'max:6048'],
            'original_fee_receipt' => ['nullable', 'mimes:pdf', 'max:6048'],
            's_status' => ['nullable', 'in:pending,approved,payment_in_progress,payment_done,rejected'],
            'payment_date' => ['nullable', 'date'],
        ]);
        if ($request->marks_obtained_type == 'PUC' || $request->marks_obtained_type == 'Degree') {
            $request->validate(['marks_subject' => ['required', 'string', 'max:255'],]);
        }
        if ($request->further_education_details_school_or_college == '1') {
            $request->validate(['further_education_details_scholarship_school_id' => ['required', 'string', 'max:255'],]);
        }
        if ($request->further_education_details_school_or_college == '2') {
            $request->validate(['further_education_details_scholarship_college_id' => ['required', 'string', 'max:255'],]);
        }

        $id = $request->scholarship_id;

        DB::beginTransaction();
        try {
            $company = Company::findOrFail(Session::get('company_id'));
            $company->setSettings();
            $scholarshipInfo = Scholarship::find($id);
            $scholarshipInfo->year = $request->year;
            $scholarshipInfo->school_year = $request->school_year;
            $scholarshipInfo->school_contact_person = $request->school_contact_person;
            $scholarshipInfo->school_designation = $request->school_designation;
            $scholarshipInfo->school_contact_number = $request->school_contact_number;
            $scholarshipInfo->marks_obtained_type = $request->marks_obtained_type;
            $scholarshipInfo->marks_subject = $request->marks_subject;
            $scholarshipInfo->marks_obtained = $request->marks_obtained;
            $scholarshipInfo->fee_amount = $request->fee_amount;
            $scholarshipInfo->further_education_details_school_or_college = $request->further_education_details_school_or_college;
            $scholarshipInfo->further_education_details_scholarship_college_id = $request->further_education_details_scholarship_college_id;
            $scholarshipInfo->further_education_details_course_joined = $request->further_education_details_course_joined;
            if ($request->s_status) {
                $scholarshipInfo->status = $request->s_status;
            }
            $scholarshipInfo->payment_date = $request->payment_date;
            if ($request->photo) {
                $picture = $request->photo;
                $logoNewName = time().$picture->getClientOriginalName();
                $picture->move('lara/scholarship',$logoNewName);
                $imageUrl = 'lara/scholarship/'.$logoNewName;
                $scholarshipInfo->photo = $imageUrl;
            }
            if ($request->income_certificate) {
                $incomeFile = $request->income_certificate;
                $incomeNewName = time().$incomeFile->getClientOriginalName();
                $incomeFile->move('lara/scholarship',$incomeNewName);
                $incomeCertificateUrl = 'lara/scholarship/'.$incomeNewName;
                $scholarshipInfo->income_certificate = $incomeCertificateUrl;
            }
            if ($request->id_proof) {
                $idProof = $request->id_proof;
                $idNewName = time().$idProof->getClientOriginalName();
                $idProof->move('lara/scholarship',$idNewName);
                $idProofUrl = 'lara/scholarship/'.$idNewName;
                $scholarshipInfo->id_proof = $idProofUrl;
            }
            if ($request->previous_educational_marks_card) {
                $pEduMarkCard = $request->previous_educational_marks_card;
                $pEduMarkCardNewName = time().$pEduMarkCard->getClientOriginalName();
                $pEduMarkCard->move('lara/scholarship',$pEduMarkCardNewName);
                $previousEducationalMarksCardUrl = 'lara/scholarship/'.$pEduMarkCardNewName;
                $scholarshipInfo->previous_educational_marks_card = $previousEducationalMarksCardUrl;
            }
            if ($request->bank_passbook) {
                $bankPassbookFile = $request->bank_passbook;
                $bankPassNewName = time().$bankPassbookFile->getClientOriginalName();
                $bankPassbookFile->move('lara/scholarship',$bankPassNewName);
                $bankPassbookUrl = 'lara/scholarship/'.$bankPassNewName;
                $scholarshipInfo->bank_passbook = $bankPassbookUrl;
            }
            if ($request->original_fee_receipt) {
                $originalFeeReceipt = $request->original_fee_receipt;
                $oFeeNewName = time().$originalFeeReceipt->getClientOriginalName();
                $originalFeeReceipt->move('lara/scholarship',$oFeeNewName);
                $originalFeeReceiptUrl = 'lara/scholarship/'.$oFeeNewName;
                $scholarshipInfo->original_fee_receipt = $originalFeeReceiptUrl;
            }
            $scholarshipInfo->save();
            $studentDetailInfo = StudentDetail::findOrFail($scholarshipInfo->student_detail_id);
            $studentDetailInfo->father_name = $request->father_name;
            $studentDetailInfo->father_occupation = $request->father_occupation;
            $studentDetailInfo->mother_name = $request->mother_name;
            $studentDetailInfo->mother_occupation = $request->mother_occupation;
            $studentDetailInfo->gender = $request->gender;
            $studentDetailInfo->aadhar_no = $request->aadhar_no;
            $studentDetailInfo->age = $request->age;
            $studentDetailInfo->save();
            DB::commit();
            Session::flash('successMessage', 1);
            echo json_encode(array("status" => 1));
        } catch (Exception $e) {
            DB::rollback();
            Session::flash('errorMessage', 1);
            echo json_encode(array("status" => 0));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Scholarship  $scholarship
     * @return \Illuminate\Http\Response
     */
    public function destroy($id = 0)
    {
        $scholarship = Scholarship::find($id);
        $scholarship->delete();
        return redirect()->route('scholarship.index')->with('success', trans('Scholarship Deleted Successfully'));
    }

    /**
     * Genrate pdf the specified resource from storage.
     *
     * @param  \App\Models\Scholarship  $scholarship
     * @return \Illuminate\Http\Response
     */
    public function download($id)
    {
        $scholarship = Scholarship::where('id', $id)->first();
        $teacherName = "";
        $fSchoolCollege = $scholarship->further_education_details_school_or_college;
        if ($fSchoolCollege == "1") {
            $schoolId = $scholarship->further_education_details_scholarship_school_id;
            $schoolTeacher = ScholarshipTeacher::where('scholarship_school_id', $schoolId)->first();
            if (isset($schoolTeacher->name) && !empty($schoolTeacher->name))
                $teacherName = $schoolTeacher->name;
        } else {
            $CollegeId = $scholarship->further_education_details_scholarship_college_id;
            $collegeTeacher = ScholarshipTeacher::where('scholarship_college_id', $CollegeId)->first();
            if (isset($collegeTeacher->name) && !empty($collegeTeacher->name))
                $teacherName = $collegeTeacher->name;
        }
        //return view('scholarships.pdf', compact('scholarship'));
        $pdf = Pdf::loadView('scholarships.pdf', compact('scholarship', 'teacherName'));
        return $pdf->stream();
        //dd($pdf->loadHTML(''));
        /* $pdf->getDomPDF()->setHttpContext(
            stream_context_create([
                'ssl' => [
                    'allow_self_signed'=> TRUE,
                    'verify_peer' => FALSE,
                    'verify_peer_name' => FALSE,
                ]
            ])
                ); */
        return $pdf->download('scholarships.pdf');
    }
}
