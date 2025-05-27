@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="fw-bold mb-0">Checkout</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('cart.index') }}">Keranjang</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Checkout</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-7 mb-4 mb-lg-0">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h3 class="fw-bold mb-3">Informasi Pengiriman</h3>
                    
                    <form action="{{ route('checkout.process') }}" method="POST" id="checkout-form">
                        @csrf
                        <div class="mb-3">
                            <label for="shipping_name" class="form-label fw-semibold">Nama Penerima*</label>
                            <input type="text" class="form-control @error('shipping_name') is-invalid @enderror" id="shipping_name" name="shipping_name" value="{{ old('shipping_name', auth()->user()->name) }}" required>
                            @error('shipping_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="shipping_phone" class="form-label fw-semibold">Nomor Telepon*</label>
                            <input type="text" class="form-control @error('shipping_phone') is-invalid @enderror" id="shipping_phone" name="shipping_phone" value="{{ old('shipping_phone') }}" required>
                            @error('shipping_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="shipping_address" class="form-label fw-semibold">Alamat Pengiriman*</label>
                            <textarea class="form-control @error('shipping_address') is-invalid @enderror" id="shipping_address" name="shipping_address" rows="3" required>{{ old('shipping_address') }}</textarea>
                            @error('shipping_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="notes" class="form-label fw-semibold">Catatan (Opsional)</label>
                            <textarea class="form-control" id="notes" name="notes" rows="2">{{ old('notes') }}</textarea>
                        </div>
                    </form>
                </div>
            </div>

            @foreach($cartItemsByUMKM as $umkmId => $items)
                @php
                    $umkm = $umkms->where('id', $umkmId)->first();
                    $subtotal = $items->sum(function($item) {
                        return $item->product->price * $item->quantity;
                    });
                @endphp
                
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <img src="{{ $umkm?->image ? asset('storage/' . $umkm->image) : asset('images/umkm-placeholder.png') }}" 
                                     alt="{{ $umkm?->name }}" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                            </div>
                            <div>
                                <h5 class="fw-bold mb-0">{{ $umkm?->name ?? 'UMKM' }}</h5>
                                <p class="text-muted small mb-0">{{ $umkm?->location ?? 'Indonesia' }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-borderless align-middle">
                                <tbody>
                                    @foreach($items as $item)
                                        <tr>
                                            <td style="width: 70px;">
                                                <img src="{{ $item->product->image ? asset('storage/' . $item->product->image) : asset('images/product-placeholder.jpg') }}" 
                                                    alt="{{ $item->product->name }}" class="img-thumbnail" style="width: 60px; height: 60px; object-fit: cover;">
                                            </td>
                                            <td>
                                                <h6 class="mb-1">{{ $item->product->name }}</h6>
                                                <p class="text-muted small mb-0">
                                                    {{ $item->quantity }} x Rp {{ number_format($item->product->price, 0, ',', '.') }}
                                                    @if($item->size)
                                                        <span class="ms-2">Ukuran: {{ $item->size }}</span>
                                                    @endif
                                                </p>
                                            </td>
                                            <td class="text-end fw-bold">Rp {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="2" class="text-end fw-bold">Subtotal</td>
                                        <td class="text-end fw-bold">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm mb-4 sticky-top" style="top: 20px; z-index: 1;">
                <div class="card-body">
                    <h3 class="fw-bold mb-4">Ringkasan Pesanan</h3>
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal</span>
                        <span class="fw-semibold">Rp {{ number_format($cart->total, 0, ',', '.') }}</span>
                    </div>
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span>Biaya Pengiriman</span>
                        <span class="fw-semibold">Rp 0</span>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between mb-4">
                        <span class="fw-bold">Total</span>
                        <span class="fw-bold text-primary fs-5">Rp {{ number_format($cart->total, 0, ',', '.') }}</span>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" form="checkout-form" class="btn btn-primary btn-lg">
                            <i class="fas fa-credit-card me-2"></i> Lanjut ke Pembayaran
                        </button>
                    </div>
                    
                    <div class="mt-4">
                        <h5 class="fw-bold mb-3">Metode Pembayaran</h5>
                        <div class="alert alert-info mb-0">
                            <i class="fas fa-info-circle me-2"></i>
                            Anda akan diarahkan ke halaman pembayaran setelah menyelesaikan checkout.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection