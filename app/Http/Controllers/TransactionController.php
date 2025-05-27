<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::where('user_id', Auth::id())
                                  ->with(['umkm'])
                                  ->latest()
                                  ->paginate(10);
                                  
        return view('transactions.index', compact('transactions'));
    }

    public function show(Transaction $transaction)
    {
        // Pastikan user hanya bisa melihat transaksinya sendiri
        if ($transaction->user_id !== Auth::id()) {
            abort(403);
        }
        
        // Load relationships
        $transaction->load(['transactionItems.product', 'umkm', 'paymentProof']);
        
        return view('transactions.show', compact('transaction'));
    }

    public function cancel(Transaction $transaction)
    {
        // Pastikan user hanya bisa membatalkan transaksinya sendiri
        if ($transaction->user_id !== Auth::id()) {
            abort(403);
        }
        
        // Pastikan hanya transaksi dengan status pending yang bisa dibatalkan
        if ($transaction->status !== 'pending') {
            return redirect()->route('transactions.index')
                ->with('error', 'Hanya pesanan yang belum dibayar yang dapat dibatalkan.');
        }
        
        // Update status transaksi menjadi cancelled
        $transaction->status = 'cancelled';
        $transaction->save();
        
        // Kembalikan stok produk (opsional)
        foreach ($transaction->transactionItems as $item) {
            if ($item->product) {
                $item->product->stock += $item->quantity;
                $item->product->save();
            }
        }
        
        return redirect()->route('transactions.index')
            ->with('success', 'Pesanan berhasil dibatalkan.');
    }
}