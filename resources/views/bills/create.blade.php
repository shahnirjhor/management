@extends('layouts.layout')
@section('one_page_js')
    <!-- Include the Quill library -->
    <script src="{{ asset('js/quill.js') }}"></script>
    <script src="{{ asset('plugins/dropify/dist/js/dropify.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
    <script src="{{ asset('plugins/sweetalert2/swal.js') }}"></script>

@endsection

@section('one_page_css')
    <!-- Include quill -->
    <link href="{{ asset('css/quill.snow.css') }}" rel="stylesheet">
    <link href="{{ asset('plugins/dropify/dist/css/dropify.min.css') }}" rel="stylesheet">
    <link href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('plugins/select2/select2.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endsection
@section('content')
<style>
    #t1 th {
        color: #ffffff;
        background-color: #17a2b8;
        border-color: #17a2b8;
    }
</style>
<section class="content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('bill.index') }}">@lang('Bills List')</a></li>
                    <li class="breadcrumb-item active">@lang('Add Bill')</li>
                </ol>
            </div>
        </div>
    </div>
</section>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">@lang('Add Bill')</h3>
            </div>
            <div class="card-body">
                <form class="form-material form-horizontal" action="{{ route('bill.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="vendor_id">@lang('Vendor') <b class="ambitious-crimson">*</b></label>
                                <div class="form-group input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-user"></i>
                                    </div>
                                    <select class="form-control ambitious-form-loading" name="vendor_id" id="vendor_id" required>
                                        <option value="">Select Vendor</option>
                                        @foreach ($vendors as $key => $value)
                                            <option value="{{ $key }}" {{ old('vendor_id') == $key ? 'selected' : '' }}>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="currency_code">@lang('Currency') <b class="ambitious-crimson">*</b></label>
                                <div class="form-group input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-user"></i>
                                    </div>
                                    <select class="form-control ambitious-form-loading" name="currency_code" id="currency_code" required>
                                        <option value="">Select Currency</option>
                                        @foreach ($currencies as $key => $value)
                                            <option value="{{ $key }}" {{ old('currency_code') == $key ? 'selected' : '' }}>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="billed_at">@lang('Bill Date') <b class="ambitious-crimson">*</b></label>
                                <div class="form-group input-group mb-3">
                                    <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-calendar"></i>
                                    </div>
                                    <input type="text" name="billed_at" id="billed_at" class="form-control today-flatpickr" value="{{ old('billed_at') }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="due_at">@lang('Due Date') <b class="ambitious-crimson">*</b></label>
                                <div class="form-group input-group mb-3">
                                    <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-calendar"></i>
                                    </div>
                                    <input type="text" name="due_at" id="due_at" class="form-control flatpickr" value="{{ old('due_at') }}" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">@lang('Bill Number') <b class="ambitious-crimson">*</b></label>
                                <div class="form-group input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-file-signature"></i>
                                    </div>
                                    <input type="text" name="bill_number" value="{{ old('bill_number', $number) }}" id="bill_number" class="form-control @error('bill_number') is-invalid @enderror" required>
                                    @error('bill_number')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">@lang('Order Number') </label>
                                <div class="form-group input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-shopping-cart"></i>
                                    </div>
                                    <input type="text" name="order_number" value="{{ old('order_number') }}" id="order_number" class="form-control @error('order_number') is-invalid @enderror">
                                    @error('order_number')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="category_id">@lang('Category')</label>
                                <div class="form-group input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-code-branch"></i>
                                    </div>
                                    <select class="form-control ambitious-form-loading" name="category_id" id="category_id">
                                        <option value="">Select Category</option>
                                        @foreach ($categories as $key => $value)
                                            <option value="{{ $key }}" {{ old('category_id') == $key ? 'selected' : '' }}>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <label for="js-example-data-ajax">@lang('Add Item') </label>
                            <div class="form-group input-group" style="margin-bottom: unset;">
                                <div class="barcode">
                                    <div class="row">
                                        <div class="col-bar-icon d-none d-xl-block">
                                            <i class="fa fa-barcode fa-4x" aria-hidden="true"></i>
                                        </div>
                                        <div class="col-sm-11 my-auto col-bar-box">
                                            <select class="js-example-data-ajax select2-container" id="js-example-data-ajax" name="combo_id[]"  multiple="multiple">
                                                <option value="AL">...</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <table class="table" id="table-combo">
                              <thead>
                                <tr class="bg-info">
                                    <th scope="col" style="white-space: nowrap;">@lang('Item Name')</th>
                                    <th scope="col" style="width: 12%;">@lang('Quantity')</th>
                                    <th scope="col" style="width: 15%;">@lang('Price')</th>
                                    <th scope="col" style="width: 20%;">@lang('Tax')</th>
                                    <th scope="col" style="width: 15%;">@lang('Total')</th>
                                    <th scope="col" style="width: 10%;">@lang('Remove')</th>
                                </tr>
                              </thead>
                              <tbody>
                                    @if (old('product.order_row_id'))
                                        @foreach (old('product.order_row_id') as $key => $value)
                                        @php
                                            $showTaxTypeRate = "";
                                            if(old('product.order_tax_type_rate')[$key] == '0') {
                                                $showTaxTypeRate = "No Tax";
                                            } else {
                                                $taxTypeRate = old('product.order_tax_type_rate')[$key];
                                                $taxTypeRate = explode("_",$taxTypeRate);
                                                $showTaxTypeRate = $taxTypeRate[1]."% ".ucfirst($taxTypeRate[0]);
                                            }
                                        @endphp
                                        <tr id="{{ old('product.order_row_id')[$key] }}" class="table-info">
                                            <th scope="row">
                                                <input type="hidden" class="order_row_id" value="{{ old('product.order_row_id')[$key] }}" name="product[order_row_id][]">
                                                <input type="hidden" class="order_name" value="{{ old('product.order_name')[$key] }}" name="product[order_name][]">{{ old('product.order_name')[$key] }}
                                            </th>
                                            <td>
                                                <input type="number" step="any" class="form-control order_quantity" min="1" value="{{ old('product.order_quantity')[$key] }}" name="product[order_quantity][]">
                                            </td>
                                            <td>
                                                <input type="hidden" class="order_price" value="{{ old('product.order_price')[$key] }}" name="product[order_price][]"><span>{{ old('product.order_price')[$key] }}</span>
                                            </td>
                                            <td>
                                                <input type="hidden" class="order_tax_type_rate" value="{{ old('product.order_tax_type_rate')[$key] }}" name="product[order_tax_type_rate][]">
                                                <input type="hidden" class="order_product_tax" value="{{ old('product.order_product_tax')[$key] }}" name="product[order_product_tax][]"><span class="order_product_tax_text">{{ $showTaxTypeRate }}</span>
                                            </td>
                                            <td>
                                                <input type="hidden" class="order_subtotal" value="{{ old('product.order_subtotal')[$key] }}" name="product[order_subtotal][]"><span class="order_subtotal_text">{{ old('product.order_subtotal')[$key] }}</span>
                                            </td>
                                            <td>
                                                <a href="javascript:void(0)" class="btn btn-info btn-outline table-remove" data-toggle="modal" data-target="#myModal" title="Delete">
                                                  <i class="fa fa-trash ambitious-padding-btn"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    @endif
                              </tbody>
                              <tbody>
                                <tr>
                                    <td colspan="3"></td>
                                    <th style="text-align: right;vertical-align: inherit;">@lang('Sub Total')</th>
                                    <td>
                                        <input type="number" step=".01" name="sub_total" class="form-control sub_total" value="{{ old('sub_total', '0.00') }}" placeholder="@lang('Sub Total')" readonly>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3"></td>
                                    <td class="text-right">@lang('Tax')</td>
                                    <td>
                                        <input type="number" step=".01" name="total_tax" class="form-control total_tax" value="{{ old('total_tax', '0.00') }}" placeholder="@lang('Total Tax')" readonly>
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td colspan="3"></td>
                                    <td class="text-right">@lang('Discount')</td>
                                    <td>
                                        <input type="number" step=".01" name="total_discount" class="form-control total_discount" value="{{ old('total_discount', '0.00') }}" placeholder="@lang('Total Discount')">
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td colspan="3"></td>
                                    <td style="text-align: right;">@lang('Grand Total')</td>
                                    <td>
                                        <input type="number" step=".01" name="grand_total" class="form-control grand_total" value="{{ old('grand_total', '0.00') }}" placeholder="@lang('Grand Total')" readonly>
                                    </td>
                                    <td></td>
                                </tr>
                            </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-12 col-form-label"><h4>@lang('Description')</h4></label>
                                <div class="col-md-12">
                                    <div id="input_description" class="@error('description') is-invalid @enderror" style="min-height: 55px;">
                                    </div>
                                    <input type="hidden" name="description" value="{{ old('description') }}" id="description">
                                    @error('description')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-12 col-form-label"><h4>@lang('Picture')</h4></label>
                                <div class="col-md-12">
                                    <input id="picture" class="dropify" name="picture" value="{{ old('picture') }}" type="file" data-allowed-file-extensions="png jpg jpeg" data-max-file-size="2024K" />
                                    <p>@lang('Max Size: 2mb, Allowed Format: png, jpg, jpeg')</p>
                                </div>
                                @if ($errors->has('picture'))
                                    <div class="error ambitious-red">{{ $errors->first('picture') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <br>
                    <hr>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <input type="submit" value="@lang('Submit')" class="btn btn-outline btn-info btn-lg btn-block"/>
                            </div>
                        </div>
                        <div class="col-md-4"></div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <a href="{{ route('bill.index') }}" class="btn btn-outline btn-warning btn-lg btn-block" style="float: right;">@lang('Cancel')</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    "use strict";
    var old_row_qty;
    let grand_total = 0;
    var item_array = [];
    var d = null;

    $('.dropify').dropify();

    var quill = new Quill('#input_description', {
        theme: 'snow'
    });
    var address = $("#description").val();
    quill.clipboard.dangerouslyPasteHTML(address);
    quill.root.blur();
    $('#input_description').on('keyup', function(){
        var input_description = quill.container.firstChild.innerHTML;
        $("#description").val(input_description);
    });
    $(".select2").select2();

    $('.js-example-data-ajax').on('select2:select', function (e) {
        var data = e.params.data;
        var discount = 0;
        var taxType = 0;
        var taxRate = 0;
        var taxTypeRate = 0
        var pr_tax_val = 0;
        var pr_tax_rate = 0;
        var showTaxTypeRate = 'No Tax';
        var order_subtotal = data.purchase_price;
        var order_net_purchase = data.purchase_price;
        if(data.tax_id != null) {
            taxType = data.tax.type;
            taxRate = data.tax.rate;
            if (taxRate !== null && taxRate != 0) {
                if (taxType == "inclusive") {
                    pr_tax_val = Number((((order_net_purchase) * parseFloat(taxRate)) / (100 + parseFloat(taxRate))), 4).toFixed(2);
                    pr_tax_rate = Number(taxRate).toFixed(2) + '%';
                    order_net_purchase -= pr_tax_val;
                } else if (taxType == "exclusive") {
                    pr_tax_val = Number((((order_net_purchase) * parseFloat(taxRate)) / 100), 4).toFixed(2);
                    pr_tax_rate = Number(taxRate).toFixed(2) + '%';
                } else {
                    pr_tax_val = parseFloat(taxRate);
                    pr_tax_rate = taxRate;
                }
            }
            // order_subtotal = Number(order_net_purchase) + Number(pr_tax_val);
            order_subtotal = Number(order_net_purchase).toFixed(2);

            taxTypeRate = taxType+"_"+taxRate;
            showTaxTypeRate = taxRate+"%"+" "+capitalizeFirstLetter(taxType);
        }


        $("#table-combo").append('<tr id="'+ data.id +'" class="table-info"><th scope="row"><input type="hidden" class="order_row_id" value="'+data.id+'" name="product[order_row_id][]"><input type="hidden" class="order_name" value="'+data.name+'" name="product[order_name][]">' + data.name + '</th><td><input type="number" step="any" class="form-control order_quantity" min="1" value="1" name="product[order_quantity][]"></td><td><input type="hidden" class="order_price" value="'+data.purchase_price+'" name="product[order_price][]"><span>'+data.purchase_price+'</span></td><td><input type="hidden" class="order_tax_type_rate" value="'+taxTypeRate+'" name="product[order_tax_type_rate][]"><input type="hidden" class="order_product_tax" value="'+pr_tax_val+'" name="product[order_product_tax][]"><span class="order_product_tax_text">'+showTaxTypeRate+'</span></td><td><input type="hidden" class="order_subtotal" value="'+order_subtotal+'" name="product[order_subtotal][]"><span class="order_subtotal_text">'+order_subtotal+'</span></td><td><a href="javascript:void(0)" class="btn btn-info btn-outline table-remove" data-toggle="modal" data-target="#myModal" title="Delete"><i class="fa fa-trash ambitious-padding-btn"></i></a></td></tr>')

        /*** Start Total Product ***/
        var tbProductTax = $("input[name='product[order_product_tax][]']").map(function(){return $(this).val();}).get();
        var tbTotalProductTax=0;
        for(var i in tbProductTax) {
            tbTotalProductTax += Number(tbProductTax[i]);
        }
        $('.total_tax').val(tbTotalProductTax.toFixed(2));
        /*** End Total Product ***/

        /*** Start Total ***/
        var tbSubTotal = $("input[name='product[order_subtotal][]']").map(function(){return $(this).val();}).get();
        var tbTotalSubTotal=0;
        for(var i in tbSubTotal) {
            tbTotalSubTotal += Number(tbSubTotal[i]);
        }
        $('.sub_total').val(tbTotalSubTotal.toFixed(2));
        /*** End Total ***/

        /*** Start Grand Total ***/
        let mydiscount = $('.total_discount').val();
        mydiscount = (!mydiscount.length || isNaN(mydiscount)) ? 0 : parseFloat(mydiscount);

        let mytax = $('.total_tax').val();
        mytax = (!mytax.length || isNaN(mytax)) ? 0 : parseFloat(mytax);

        grand_total = tbTotalSubTotal - mydiscount;
        grand_total += mytax;
        $('.grand_total').val(grand_total.toFixed(2));
        /*** End Grand Total ***/

        // push
        item_array.push(data.id);
        // blank
        $('.js-example-data-ajax').val(null).trigger('change');
        // array to string
        var b = item_array.toString();
        var c = b;
        // comma replace to underscore
        window.d = c.replace(/,/g, '_');
    });

    $(document).on("focus", '.order_quantity', function () {
        old_row_qty = $(this).val();
    }).on('change keyup', '.order_quantity', function () {
        var row = $(this).closest('tr');
        var pr_tax_val = 0;
        var prs_tax_val = 0;
        if (!Number($(this).val()) || parseFloat($(this).val()) < 0) {
            $(this).val(old_row_qty);
            Swal.fire({
                icon: 'warning',
                title: 'Warning !',
                text: 'Unexpected value provided!',
            });
            row.children().children('.order_quantity').val(old_row_qty);
            return;
        }
        var new_order_subtotal = 0;
        var new_qty = parseFloat($(this).val());
        var item_id = row.attr('id');
        var order_price = row.children().children('.order_price').val();
        var order_tax_type_rate = row.children().children('.order_tax_type_rate').val();
        var order_price_number = Number(order_price);
        var new_qty_number = Number(new_qty);
        if(order_tax_type_rate != '0'){
            var strData = order_tax_type_rate.split("_");
            var taxType = strData[0];
            var taxRate = strData[1];
            if (taxRate !== null && taxRate != 0) {
                if (taxType == "inclusive") {
                    pr_tax_val = Number((((order_price) * parseFloat(taxRate)) / (100 + parseFloat(taxRate))), 4).toFixed(2);
                    prs_tax_val = Number(pr_tax_val*new_qty_number).toFixed(2);
                    new_order_subtotal = new_qty_number * order_price_number-prs_tax_val;
                } else {
                    pr_tax_val = Number((((order_price) * parseFloat(taxRate)) / 100), 4).toFixed(2);
                    prs_tax_val = Number(pr_tax_val*new_qty_number).toFixed(2);
                    new_order_subtotal = new_qty_number * order_price_number;
                }
            }
        } else {
            new_order_subtotal = new_qty_number * order_price_number;
        }
        row.children().children('.order_subtotal').val(new_order_subtotal);
        row.children().children('.order_subtotal_text').text(new_order_subtotal);
        row.children().children('.order_product_tax').val(prs_tax_val);

        /*** Start Total Sub Total ***/
        var tbSubTotal = $("input[name='product[order_subtotal][]']").map(function(){return $(this).val();}).get();
        var tbTotalSubTotal=0;
        for(var i in tbSubTotal) {
            tbTotalSubTotal += Number(tbSubTotal[i]);
        }
        $('.sub_total').val(tbTotalSubTotal.toFixed(2));
        /*** End Total Sub Total***/

        /*** Start Total Product Tax***/
        var tbProductTax = $("input[name='product[order_product_tax][]']").map(function(){return $(this).val();}).get();
        var tbTotalProductTax=0;
        for(var i in tbProductTax) {
            tbTotalProductTax += Number(tbProductTax[i]);
        }
        $('.total_tax').val(tbTotalProductTax.toFixed(2));
        /*** End Total Product Tax***/

        /*** Start Grand Total ***/
        let mydiscount = $('.total_discount').val();
        mydiscount = (!mydiscount.length || isNaN(mydiscount)) ? 0 : parseFloat(mydiscount);

        let mytax = tbTotalProductTax.toFixed(2);
        mytax = (!mytax.length || isNaN(mytax)) ? 0 : parseFloat(mytax);

        grand_total = tbTotalSubTotal - mydiscount;
        grand_total += mytax;
        $('.grand_total').val(grand_total.toFixed(2));
        /*** End Grand Total ***/
    });

    // tr remove item
    $("#table-combo").on('click', '.table-remove', function () {
        var row = $(this).closest('tr').remove();
        window.item_array = [];
        var tbRowId = $("input[name='product[order_row_id][]']").map(function(){return $(this).val();}).get();
        window.item_array.push(tbRowId);
        var b = tbRowId.toString();
        var c = b;
        window.d = c.replace(/,/g, '_');
        /*** Start Total ***/
        var tbSubTotal = $("input[name='product[order_subtotal][]']").map(function(){return $(this).val();}).get();
        var tbTotalSubTotal=0;
        for(var i in tbSubTotal) {
            tbTotalSubTotal += Number(tbSubTotal[i]);
        }
        $('.sub_total').val(tbTotalSubTotal.toFixed(2));
        /*** End Total ***/
        /*** Start Total Product ***/
        var tbProductTax = $("input[name='product[order_product_tax][]']").map(function(){return $(this).val();}).get();
        var tbTotalProductTax=0;
        for(var i in tbProductTax) {
            tbTotalProductTax += Number(tbProductTax[i]);
        }
        $('.total_tax').val(tbTotalProductTax.toFixed(2));
        /*** End Total Product ***/
        /*** Start Grand Total ***/
        let mydiscount = $('.total_discount').val();
        mydiscount = (!mydiscount.length || isNaN(mydiscount)) ? 0 : parseFloat(mydiscount);

        let mytax = tbTotalProductTax.toFixed(2);
        mytax = (!mytax.length || isNaN(mytax)) ? 0 : parseFloat(mytax);

        grand_total = tbTotalSubTotal - mydiscount;
        grand_total += mytax;
        $('.grand_total').val(grand_total.toFixed(2));
        /*** End Grand Total ***/
    });

    function capitalizeFirstLetter(string){
        return string.charAt(0).toUpperCase() + string.slice(1);
    }

    $(document).on('change keyup', '.total_discount', function () {
        calculateDiscount();
    });

    function calculateDiscount() {
        let total_discount = $('.total_discount').val();
        calculateTax();
    }

    function calculateTax() {
        let discount = $('.total_discount').val();
        discount = (!discount.length || isNaN(discount)) ? 0 : parseFloat(discount);

        let tax = $('.total_tax').val();
        tax = (!tax.length || isNaN(tax)) ? 0 : parseFloat(tax);

        var tbSubTotal = $("input[name='product[order_subtotal][]']").map(function(){return $(this).val();}).get();
        var total=0;
        for(var i in tbSubTotal) {
            total += Number(tbSubTotal[i]);
        }

        total = parseFloat(total.toFixed(2));
        grand_total = total - discount;
        grand_total += tax;
        $('.grand_total').val(grand_total.toFixed(2));
    }
</script>

<script type="text/javascript" class="js-code-placeholder">

    $(".js-example-data-ajax").select2({
        ajax: {
            url: "/getBillItems",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term,
                    item_array: d,
                    page: params.page
                };
            },
            processResults: function (data, params) {
                params.page = params.page || 1;
                return {
                    results: data,
                    pagination: {
                        more: (params.page * 30) < data.total_count
                    }
                };
            },
            cache: true
        },
        placeholder: '@lang('Search Your Item')',
        minimumInputLength: 1,
        templateResult: formatRepo,
        templateSelection: formatRepoSelection
    });

    function formatRepo (repo) {
        if (repo.loading) {
            return repo.text;
        }
        var $container = $(
            "<div class='select2-result-repository clearfix'>" +
            "<div class='select2-result-repository__meta'>" +
            "<div class='select2-result-repository__title'></div>" +
            "</div>" +
            "</div>" +
            "</div>"
        );
        $container.find(".select2-result-repository__title").text(repo.name);
        return $container;
    }

    function formatRepoSelection (repo) {
        return repo.name || repo.sku;
    }

    $(".today-flatpickr").flatpickr({
        enableTime: false,
        defaultDate: "today"
    });

    $(".flatpickr").flatpickr({
        enableTime: false
    });

    $(document).ready(function(){
        $(window).scrollTop(0);
    });

    </script>
@endsection
