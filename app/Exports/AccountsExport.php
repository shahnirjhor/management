<?php

namespace App\Exports;

use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class AccountsExport implements FromView
{
    protected $accounts;

    public function __construct(Request $request, $company_id=null)
    {
        $query = Account::query();

        if ($company_id)
            $query->where('company_id', $company_id);

        $this->accounts = $query->get();
    }

    public function view(): View
    {
        return view('exports.accounts', [
            'accounts' => $this->accounts
        ]);
    }
}
