<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Brand;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::orderBy('id', 'desc')->paginate(10);
        return view('admin.brands.index', compact('brands'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:brands,name'
        ], [
            'name.unique' => 'Tên thương hiệu này đã tồn tại!'
        ]);

        Brand::create(['name' => $request->name]);
        return back()->with('success', 'Thêm Thương hiệu mới thành công!');
    }

    public function destroy($id)
    {
        Brand::findOrFail($id)->delete();
        return back()->with('success', 'Đã xóa Thương hiệu!');
    }
}
