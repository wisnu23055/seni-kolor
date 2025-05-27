<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;
use App\Models\Cart;
use App\Models\CartItem;

class UserDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Ambil semua transaksi milik user
        $transactions = Transaction::where('user_id', $user->id)->latest()->get();

        // Ambil cart user
        $cart = Cart::where('user_id', $user->id)->first();

        // Hitung total belanja dan ambil item cart
        $totalBelanja = 0;
        $cartItems = [];

        if ($cart) {
            $cartItems = CartItem::with('product')
                ->where('cart_id', $cart->id)
                ->get();

            foreach ($cartItems as $item) {
                $totalBelanja += $item->product->price * $item->quantity;
            }
        }

        // Kirim data ke view (belum dibuat)
        return view('user.dashboard', [
            'transactions' => $transactions,
            'cartItems' => $cartItems,
            'totalBelanja' => $totalBelanja,
        ]);
    }
}
