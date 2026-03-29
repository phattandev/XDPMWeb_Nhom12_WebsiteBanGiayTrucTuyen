<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shoe;
use App\Models\ShoeVariant;

class CartController extends Controller
{

    public function index()
    {
        $cart = session()->get('cart', []);
        return view('frontend.cart.index', compact('cart'));
    }


    public function add(Request $request)
    {
        $request->validate([
            'shoe_id' => 'required|exists:shoes,id',
            'shoe_variant_id' => 'required|exists:shoe_variants,id',
            'quantity' => 'required|integer|min:1'
        ]);
        $shoe = Shoe::with('images')->findOrFail($request->shoe_id);
        $variant = ShoeVariant::findOrFail($request->shoe_variant_id);
        $cart = session()->get('cart', []);


        $primaryImage = $shoe->images->where('is_primary', true)->first() ?? $shoe->first();

        $image = $primaryImage 
            ? (str_starts_with($primaryImage->image_url, 'http') 
                ? $primaryImage->image_url 
                : asset('images/' . $primaryImage->image_url))
            : 'https://via.placeholder.com/100';


        if (isset($cart[$variant->id])) {
            $cart[$variant->id]['quantity'] += $request->quantity ?? 1;
        } else {

            $cart[$variant->id] = [
                'shoe_id' => $shoe->id,
                'name' => $shoe->name,
                'price' => $shoe->price,
                'quantity' => $request->quantity ?? 1,
                'image' => $image,
                'color' => $variant->color,
                'size' => $variant->size,
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