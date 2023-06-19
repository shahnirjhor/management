<?php

namespace App\Exports;

use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class UsersExport implements FromView
{
    protected $users;
    protected $companyDateFormat;

    public function __construct(Request $request, $company_id=null)
    {
        $query = User::query();
        if ($company_id) {
            $company = Company::findOrFail($company_id);
            $company->setSettings();
            $companyDateFormat = $company->date_format;
            $query->where('company_id', $company_id);
        } else {
            $companyDateFormat = "d M Y";
        }
        $this->users = $query->get();
        $this->companyDateFormat = $companyDateFormat;
    }

    public function view(): View
    {
        return view('exports.users', [
            'users' => $this->users,
            'companyDateFormat' => $this->companyDateFormat,
        ]);
    }
}
