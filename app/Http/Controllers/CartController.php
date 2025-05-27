<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $cart = auth()->user()->cart ?? Cart::create(['user_id' => auth()->id()]);
        $cartItems = $cart->cartItems; // memastikan relasi ini ada di model Cart
    
        $total = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });
        
        // Mengambil produk untuk rekomendasi secara acak
        // Kecualikan produk yang sudah ada di keranjang
        $cartProductIds = $cartItems->pluck('product_id')->toArray();
        $recommendedProducts = Product::whereNotIn('id', $cartProductIds)
                                ->inRandomOrder()
                                ->take(4)
                                ->get();
    
        return view('cart.index', compact('cart', 'cartItems', 'total', 'recommendedProducts'));
    }
    
    
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'size' => 'nullable|string',
        ]);
        
        $product = Product::findOrFail($request->product_id);
        
        // Create cart if user doesn't have one
        $cart = auth()->user()->cart ?? Cart::create(['user_id' => auth()->id()]);
        
        // Check if item already exists in cart
        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $product->id)
            ->where('size', $request->size)
            ->first();
        
        if ($cartItem) {
            $cartItem->update([
                'quantity' => $cartItem->quantity + $request->quantity,
            ]);
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'quantity' => $request->quantity,
                'size' => $request->size,
            ]);
        }
        
        return redirect()->route('cart.index')->with('success', 'Product added to cart');
    }
    
    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);
        
        $cartItem = CartItem::findOrFail($id);
        
        // Make sure the cart item belongs to the user
        if ($cartItem->cart->user_id !== auth()->id()) {
            abort(403);
        }
        
        $cartItem->update([
            'quantity' => $request->quantity,
        ]);
        
        return redirect()->route('cart.index')->with('success', 'Cart updated');
    }
    
    public function remove($id)
    {
        $cartItem = CartItem::findOrFail($id);
        
        // Make sure the cart item belongs to the user
        if ($cartItem->cart->user_id !== auth()->id()) {
            abort(403);
        }
        
        $cartItem->delete();
        
        return redirect()->route('cart.index')->with('success', 'Item removed from cart');
    }
}