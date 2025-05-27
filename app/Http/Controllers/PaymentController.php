<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\PaymentProof;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function show(Transaction $transaction)
    {
        // Make sure the transaction belongs to the user
        if ($transaction->user_id !== auth()->id()) {
            abort(403);
        }
        
        // Check if transaction status is pending
        if ($transaction->status !== Transaction::STATUS_PENDING) {
            return redirect()->route('transactions.show', $transaction->id)
                ->with('info', 'Pembayaran untuk pesanan ini sudah diproses.');
        }
        
        return view('payment.show', compact('transaction'));
    }
    
    public function store(Request $request, Transaction $transaction)
    {
        // Make sure the transaction belongs to the user
        if ($transaction->user_id !== auth()->id()) {
            abort(403);
        }
        
        // Check if transaction status is pending
        if ($transaction->status !== Transaction::STATUS_PENDING) {
            return redirect()->route('transactions.show', $transaction->id)
                ->with('info', 'Pembayaran untuk pesanan ini sudah diproses.');
        }
        
        // Check if payment proof already exists
        if ($transaction->paymentProof) {
            return redirect()->route('payment.show', $transaction->id)
                ->with('info', 'Anda sudah mengunggah bukti pembayaran untuk pesanan ini.');
        }
        
        $request->validate([
            'payment_proof' => 'required|image|max:2048',
            'notes' => 'nullable|string|max:255',
        ]);
        
        // Process the image upload
        $path = $request->file('payment_proof')->store('payment_proofs', 'public');
        
        // Create payment proof record
        PaymentProof::create([
            'transaction_id' => $transaction->id,
            'image' => $path,
            'notes' => $request->notes,
        ]);
        
        // Update transaction status
        $transaction->update([
            'status' => Transaction::STATUS_PROCESSING,
        ]);
        
        return redirect()->route('transactions.show', $transaction->id)
            ->with('success', 'Bukti pembayaran berhasil diunggah. Pesanan Anda sedang diproses.');
    }
    
    public function contactSeller(Transaction $transaction)
    {
        // Make sure the transaction belongs to the user
        if ($transaction->user_id !== auth()->id()) {
            abort(403);
        }
        
        $umkm = $transaction->umkm;
        $whatsappNumber = $umkm->whatsapp ?? null;
        
        if (!$whatsappNumber) {
            return back()->with('error', 'Nomor WhatsApp penjual tidak tersedia.');
        }
        
        // Format the message
        $message = "Halo, saya telah melakukan pembelian (Pesanan #{$transaction->id}). Mohon konfirmasi pesanan saya. Terima kasih!";
        $encodedMessage = urlencode($message);
        
        // Format the WhatsApp number (remove any spaces, +, etc.)
        $whatsappNumber = preg_replace('/[^0-9]/', '', $whatsappNumber);
        
        // If the number doesn't start with the country code, add it 
        if (substr($whatsappNumber, 0, 2) !== '62') {
            // If it starts with 0, replace it with 62
            if (substr($whatsappNumber, 0, 1) === '0') {
                $whatsappNumber = '62' . substr($whatsappNumber, 1);
            } else {
                $whatsappNumber = '62' . $whatsappNumber;
            }
        }
        
        $whatsappUrl = "https://wa.me/{$whatsappNumber}?text={$encodedMessage}";
        
        return redirect($whatsappUrl);
    }
}