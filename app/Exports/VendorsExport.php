<?php

namespace App\Exports;

use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class VendorsExport implements FromView
{
    protected $vendors;

    public function __construct(Request $request, $company_id=null)
    {
        $query = Vendor::query();

        if ($company_id) {
            $query->where('company_id', $company_id);
        }

        $this->vendors = $query->get();
    }

    public function view(): View
    {
        return view('exports.vendors', [
            'vendors' => $this->vendors
        ]);
    }

}
