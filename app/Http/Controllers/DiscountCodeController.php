<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\Models\DiscountCode;

class DiscountCodeController extends Controller
{
    public function list()
    {
        $data['getRecord'] = DiscountCode::getRecord();
        $data['header_title'] = 'Discount Code';
        return view('dashboard\manage-discount-code\list',$data);
    }

    public function add()
    {
        $data['header_title'] = 'Add New Discount Code';
        return view('dashboard/manage-discount-code/add',$data);
    }

    public function insert(Request $request)
    {
        $DiscountCode = new DiscountCode;
        $DiscountCode->name = trim($request->name);
        $DiscountCode->code = trim($request->code);
        $DiscountCode->status = trim($request->status);
        $DiscountCode->created_by = Auth::user()->id;
        $DiscountCode->save();

        return redirect('admin/discount_code/list')->with('success', "DiscountCode Successfully Created");
    }

    public function edit($id)
    {
        $data['getRecord'] = DiscountCode::getSingle($id);
        $data['header_title'] = 'Edit Discount Code';
        return view('dashboard.manage-discount-code.edit',$data);
    }

    public function update($id, Request $request)
    {
        $DiscountCode = DiscountCode::getSingle($id);
        $DiscountCode->name = trim($request->name);
        $DiscountCode->code = trim($request->code);
        $DiscountCode->status = trim($request->status);

        return redirect('admin/discount_code/list')->back()->with('success', "Discount Code Successfully Updated");

    }

    public function delete($id)
    {
        $DiscountCode = DiscountCode::getSingle($id);
        $DiscountCode->is_delete = 1;
        $DiscountCode->save();

        return redirect()->back()->with('success', "Discount Code Successfully Deleted");
    }


}
