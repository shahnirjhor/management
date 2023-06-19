<?php

namespace App\Exports;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class CategoriesExport implements FromView
{
    protected $categories;

    public function __construct(Request $request, $company_id=null)
    {
        $query = Category::query();

        if ($company_id)
            $query->where('company_id', $company_id);

        $this->categories = $query->get();
    }

    public function view(): View
    {
        return view('exports.categories', [
            'categories' => $this->categories
        ]);
    }
}
