<?php

namespace App\Exports;

use App\Models\Tax;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class TaxesExport implements FromView
{
    protected $taxes;

    public function __construct(Request $request, $company_id=null)
    {
        $query = Tax::query();

        if ($company_id)
            $query->where('company_id', $company_id);

        $this->taxes = $query->get();
    }

    public function view(): View
    {
        return view('exports.taxes', [
            'taxes' => $this->taxes
        ]);
    }
}
