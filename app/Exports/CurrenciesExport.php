<?php

namespace App\Exports;

use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class CurrenciesExport implements FromView
{
    protected $currencies;

    public function __construct(Request $request, $company_id=null)
    {
        $query = Currency::query();

        if ($company_id)
            $query->where('company_id', $company_id);

        $this->currencies = $query->get();
    }

    public function view(): View
    {
        return view('exports.currencies', [
            'currencies' => $this->currencies
        ]);
    }
}
