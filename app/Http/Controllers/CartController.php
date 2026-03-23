<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shoe;

class CartController extends Controller
{
    // Hiển thị giỏ hàng
    public function index()
    {
        $cart = session()->get('cart', []);
        return view('frontend.cart.index', compact('cart'));
    }

    // Thêm sản phẩm vào giỏ
    public function add(Request $request)
    {
        $shoe = Shoe::findOrFail($request->shoe_id);

        $cart = session()->get('cart', []);

        if (isset($cart[$shoe->id])) {
            $cart[$shoe->id]['quantity']++;
        } else {
            $cart[$shoe->id] = [
                'name' => $shoe->name,
                'price' => $shoe->price,
                'quantity' => 1,
                'image' => $shoe->image ?? '',
                'size' => $request->size ?? ''
            ];
        }

        session()->put('cart', $cart);

        return redirect()->route('cart.index')
            ->with('success', 'Đã thêm vào giỏ hàng');
    }

    // Cập nhật số lượng
    public function update(Request $request)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$request->id])) {
            $cart[$request->id]['quantity'] = max(1, $request->quantity);
            session()->put('cart', $cart);
        }

        return back();
    }

    // Xóa sản phẩm
    public function remove(Request $request)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$request->id])) {
            unset($cart[$request->id]);
            session()->put('cart', $cart);
        }

        return back();
    }
}