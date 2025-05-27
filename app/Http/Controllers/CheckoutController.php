<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\UMKM;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $cart = auth()->user()->cart;
        
        if (!$cart || $cart->cartItems->isEmpty()) {
            return redirect()->route('products.index');
        }
        
        // Group by UMKM
        $cartItemsByUMKM = $cart->cartItems->groupBy(function ($item) {
            return $item->product->umkm_id;
        });
        
        $umkms = UMKM::whereIn('id', $cartItemsByUMKM->keys())->get();
        
        return view('checkout.index', compact('cart', 'cartItemsByUMKM', 'umkms'));
    }
    
    public function process(Request $request)
    {
        $request->validate([
            'shipping_name' => 'required|string|max:255',
            'shipping_address' => 'required|string',
            'shipping_phone' => 'required|string|max:20',
        ]);
        
        $cart = auth()->user()->cart;
        
        if (!$cart || $cart->cartItems->isEmpty()) {
            return redirect()->route('products.index');
        }
        
        // Group by UMKM
        $cartItemsByUMKM = $cart->cartItems->groupBy(function ($item) {
            return $item->product->umkm_id;
        });
        
        $transactions = [];
        
        foreach ($cartItemsByUMKM as $umkmId => $items) {
            $totalAmount = $items->sum(function ($item) {
                return $item->product->price * $item->quantity;
            });
            
            $transaction = Transaction::create([
                'user_id' => auth()->id(),
                'umkm_id' => $umkmId,
                'total_amount' => $totalAmount,
                'status' => 'pending',
                'shipping_name' => $request->shipping_name,
                'shipping_address' => $request->shipping_address,
                'shipping_phone' => $request->shipping_phone,
            ]);
            
            // Create transaction items
            foreach ($items as $item) {
                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                    'size' => $item->size,
                ]);
                
                // Reduce stock
                $item->product->decrement('stock', $item->quantity);
            }
            
            $transactions[] = $transaction;
        }
        
        // Clear the cart
        foreach ($cart->cartItems as $item) {
            $item->delete();
        }
        
        // If only one transaction, redirect to payment page
        if (count($transactions) === 1) {
            return redirect()->route('payment.show', $transactions[0]);
        }
        
        // If multiple transactions, redirect to user dashboard
        return redirect()->route('user.dashboard')->with('success', 'Orders placed successfully');
    }
}