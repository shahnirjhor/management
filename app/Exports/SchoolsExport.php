<?php

namespace App\Exports;

use App\Models\Company;
use App\Models\ScholarshipSchool;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class SchoolsExport implements FromView
{
    protected $schools;
    protected $companyDateFormat;

    public function __construct(Request $request, $company_id=null)
    {
        $query = ScholarshipSchool::query();

        if ($company_id) {
            $company = Company::findOrFail($company_id);
            $company->setSettings();
            $companyDateFormat = $company->date_format;
            $query->where('company_id', $company_id);
        } else {
            $companyDateFormat = "d M Y";
        }

        $this->schools = $query->get();
        $this->companyDateFormat = $companyDateFormat;
    }

    public function view(): View
    {
        return view('exports.schools', [
            'schools' => $this->schools,
            'companyDateFormat' => $this->companyDateFormat,
        ]);
    }
}
