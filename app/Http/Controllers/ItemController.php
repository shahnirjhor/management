<?php

namespace App\Http\Controllers;

use Session;
use App\Models\Tax;
use App\Models\Item;
use App\Models\Company;
use App\Models\Category;
use App\Models\Currency;
use App\Exports\ItemsExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ItemController extends Controller
{
    /**
     * load constructor method
     *
     * @access public
     * @return void
     */
    function __construct()
    {
        $this->middleware('permission:item-read|item-create|item-update|item-delete', ['only' => ['index']]);
        $this->middleware('permission:item-create', ['only' => ['create','store']]);
        $this->middleware('permission:item-update', ['only' => ['edit','update']]);
        $this->middleware('permission:item-delete', ['only' => ['destroy']]);
        $this->middleware('permission:item-export', ['only' => ['doExport']]);
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
        $items = $this->filter($request)->paginate(10)->withQueryString();
        $categories = Category::where('company_id', session('company_id'))->where('enabled', 1)->where('type', 'item')->orderBy('name')->pluck('name', 'id');
        return view('items.index',compact('items','categories'));
    }

    private function filter(Request $request)
    {
        $query = Item::where('company_id', session('company_id'))->latest();

        if ($request->name)
            $query->where('name', 'like', '%'.$request->name.'%');

        if ($request->sku)
            $query->where('sku', 'like', '%'.$request->sku.'%');

        if ($request->category_id)
            $query->where('category_id', $request->category_id);

        if ($request->enabled > -1)
            $query->where('enabled', $request->enabled);

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
        return Excel::download(new ItemsExport($request, session('company_id')), 'items.xlsx');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
 		$company = Company::findOrFail(Session::get('company_id'));
        $company->setSettings();
        $categories = Category::where('company_id', session('company_id'))->where('enabled', 1)->where('type', 'item')->orderBy('name')->pluck('name', 'id');
        $taxes = Tax::where('company_id', session('company_id'))->where('enabled', 1)->orderBy('name')->get()->pluck('name', 'id');
        $currency = Currency::where('code', '=', $company->default_currency)->first();
        return view('items.create', compact('categories', 'taxes', 'currency'));
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
        $data = $request->only(['name','sku','sale_price','purchase_price','quantity','tax_id','category_id','enabled','description']);
        $data['company_id'] = session('company_id');
        if ($request->picture) {
            $data['picture'] = $request->picture->store('item-images');
        }
        DB::transaction(function () use ($data) {
            Item::create($data);
        });

        return redirect()->route('item.index')->with('success', trans('Item Added Successfully'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function show(Item $item)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function edit(Item $item)
    {
        $company = Company::findOrFail(Session::get('company_id'));
        $company->setSettings();
        $categories = Category::where('company_id', session('company_id'))->where('enabled', 1)->where('type', 'item')->orderBy('name')->pluck('name', 'id');
        $taxes = Tax::where('company_id', session('company_id'))->where('enabled', 1)->orderBy('name')->get()->pluck('name', 'id');
        $currency = Currency::where('code', '=', $company->default_currency)->first();
        return view('items.edit', compact('item', 'company', 'categories', 'taxes', 'currency'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Item $item)
    {
        $this->validation($request, $item->id);
        $data = $request->only(['name','sku','sale_price','purchase_price','quantity','tax_id','category_id','enabled','description']);
        if ($request->picture) {
            $data['picture'] = $request->picture->store('item-images');
        }
        $item->update($data);
        return redirect()->route('item.index')->with('success', trans('Item Updated Successfully'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function destroy(Item $item)
    {
        $item->delete();
        return redirect()->route('item.index')->with('success', trans('Item Deleted Successfully'));
    }

    private function validation(Request $request, $id = 0)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['required', 'string', 'unique:items,sku,'.$id, 'max:255'],
            'sale_price' => ['required', 'numeric'],
            'purchase_price' => ['required', 'numeric'],
            'quantity' => ['required', 'numeric'],
            'tax_id' => ['nullable', 'numeric'],
            'category_id' => ['required', 'numeric'],
            'description' => ['nullable', 'string', 'max:1000'],
            'enabled' => ['required', 'in:0,1'],
            'picture' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048']
        ]);
    }
}
