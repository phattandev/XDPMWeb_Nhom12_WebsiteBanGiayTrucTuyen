<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shoe;

class CartController extends Controller
{

    public function index()
    {
        $cart = session()->get('cart', []);
        return view('frontend.cart.index', compact('cart'));
    }


    public function add(Request $request)
    {
        $shoe = Shoe::with('images')->findOrFail($request->shoe_id);

        $cart = session()->get('cart', []);


        $primaryImage = $shoe->images->where('is_primary', 1)->first();

        $image = $primaryImage 
            ? (str_starts_with($primaryImage->image_url, 'http') 
                ? $primaryImage->image_url 
                : asset('images/' . $primaryImage->image_url))
            : 'https://via.placeholder.com/100';


        if (isset($cart[$shoe->id])) {
            $cart[$shoe->id]['quantity'] += $request->quantity ?? 1;
        } else {

            $cart[$shoe->id] = [
                'name' => $shoe->name,
                'price' => $shoe->price,
                'quantity' => $request->quantity ?? 1,
                'image' => $image,
            ];
        }

        session()->put('cart', $cart);

        return redirect()->route('cart.index')
            ->with('success', 'Đã thêm vào giỏ hàng!');
    }


    public function update(Request $request)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$request->id])) {
            $quantity = max(1, (int)$request->quantity);
            $cart[$request->id]['quantity'] = $quantity;

            session()->put('cart', $cart);
        }

        return back();
    }


    public function remove(Request $request)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$request->id])) {
            unset($cart[$request->id]);
            session()->put('cart', $cart);
        }

        return back();
    }


    public function clear()
    {
        session()->forget('cart');
        return back()->with('success', 'Đã xóa toàn bộ giỏ hàng!');
    }
}