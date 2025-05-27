@extends('layouts.app')

@section('title', 'Instruksi Pembayaran')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="fw-bold mb-0">Instruksi Pembayaran</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('transactions.show', $transaction->id) }}">Pesanan #{{ $transaction->id }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Pembayaran</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mb-4 mb-lg-0">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h3 class="fw-bold mb-4">Detail Pesanan</h3>
                    
                    <div class="mb-4 p-3 bg-light rounded">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <div class="text-primary me-3">
                                        <i class="fas fa-shopping-bag"></i>
                                    </div>
                                    <div>
                                        <p class="text-muted mb-0">ID Pesanan</p>
                                        <p class="fw-bold mb-0">#{{ $transaction->id }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <div class="text-primary me-3">
                                        <i class="fas fa-calendar-alt"></i>
                                    </div>
                                    <div>
                                        <p class="text-muted mb-0">Tanggal Pesanan</p>
                                        <p class="fw-bold mb-0">{{ $transaction->created_at->format('d M Y, H:i') }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <div class="text-primary me-3">
                                        <i class="fas fa-store"></i>
                                    </div>
                                    <div>
                                        <p class="text-muted mb-0">UMKM</p>
                                        <p class="fw-bold mb-0">{{ $transaction->umkm->name ?? 'UMKM' }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <div class="text-primary me-3">
                                        <i class="fas fa-money-bill-wave"></i>
                                    </div>
                                    <div>
                                        <p class="text-muted mb-0">Total Pembayaran</p>
                                        <p class="fw-bold text-primary mb-0">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <h4 class="fw-bold mb-3">Metode Pembayaran</h4>
                    
                    @if($transaction->umkm && ($transaction->umkm->bank_name || $transaction->umkm->bank_account_number))
                        <div class="card mb-4">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    @if($transaction->umkm->image)
                                        <img src="{{ asset('storage/' . $transaction->umkm->image) }}" 
                                            alt="{{ $transaction->umkm->name }}" 
                                            class="rounded-circle me-3" 
                                            style="width: 40px; height: 40px; object-fit: cover;">
                                    @else
                                        <div class="bg-primary text-white rounded-circle me-3 d-flex align-items-center justify-content-center" 
                                            style="width: 40px; height: 40px;">
                                            <i class="fas fa-store"></i>
                                        </div>
                                    @endif
                                    <h5 class="fw-bold mb-0">{{ $transaction->umkm->name }}</h5>
                                </div>
                                
                                <ul class="list-unstyled mb-0">
                                    <li class="mb-2">
                                        <span class="fw-semibold">Bank:</span> 
                                        <span class="ms-2">{{ $transaction->umkm->bank_name ?: 'Bank tidak ditentukan' }}</span>
                                    </li>
                                    <li class="mb-2">
                                        <span class="fw-semibold">No. Rekening:</span> 
                                        <span class="ms-2" id="account-number">{{ $transaction->umkm->bank_account_number ?: 'Nomor rekening tidak ditentukan' }}</span>
                                        @if($transaction->umkm->bank_account_number)
                                            <button class="btn btn-sm btn-outline-primary ms-2" 
                                                    onclick="copyToClipboard('{{ $transaction->umkm->bank_account_number }}')">
                                                <i class="fas fa-copy"></i>
                                            </button>
                                        @endif
                                    </li>
                                    <li>
                                        <span class="fw-semibold">Atas Nama:</span> 
                                        <span class="ms-2">{{ $transaction->umkm->bank_account_name ?: $transaction->umkm->name }}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-warning mb-4">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Informasi bank untuk UMKM ini belum tersedia. Silakan hubungi penjual untuk informasi pembayaran.
                        </div>
                    @endif
                    
                    <div class="alert alert-info d-flex" role="alert">
                        <div class="me-3">
                            <i class="fas fa-info-circle fa-2x"></i>
                        </div>
                        <div>
                            <h5 class="alert-heading fw-bold">Petunjuk Pembayaran</h5>
                            <p class="mb-0">
                                Mohon transfer tepat sampai 3 digit terakhir untuk memudahkan verifikasi. Pembayaran akan diproses dalam 1x24 jam setelah bukti pembayaran diunggah.
                            </p>
                        </div>
                    </div>
                    
                    @if ($transaction->paymentProof)
                        <div class="alert alert-success d-flex mt-4" role="alert">
                            <div class="me-3">
                                <i class="fas fa-check-circle fa-2x"></i>
                            </div>
                            <div>
                                <h5 class="alert-heading fw-bold">Bukti Pembayaran Telah Diunggah</h5>
                                <p class="mb-0">
                                    Anda telah mengunggah bukti pembayaran pada {{ $transaction->paymentProof->created_at->format('d M Y, H:i') }}. 
                                    Kami sedang memverifikasi pembayaran Anda.
                                </p>
                                @if($transaction->umkm && $transaction->umkm->whatsapp)
                                <div class="mt-2">
                                    <a href="{{ route('payment.contact_seller', $transaction->id) }}" class="btn btn-sm btn-success">
                                        <i class="fab fa-whatsapp me-2"></i> Hubungi Penjual
                                    </a>
                                </div>
                                @endif
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <h5 class="fw-bold mb-3">Bukti Pembayaran</h5>
                            <div class="card">
                                <div class="card-body">
                                    <div class="text-center mb-3">
                                        <img src="{{ asset('storage/' . $transaction->paymentProof->image) }}" 
                                            alt="Bukti Pembayaran" 
                                            class="img-fluid rounded" 
                                            style="max-height: 300px;">
                                    </div>
                                    
                                    @if($transaction->paymentProof->notes)
                                    <div class="mt-3">
                                        <h6 class="fw-bold">Catatan:</h6>
                                        <p class="mb-0">{{ $transaction->paymentProof->notes }}</p>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @else
                        <h4 class="fw-bold mt-4 mb-3">Unggah Bukti Pembayaran</h4>
                        
                        <form action="{{ route('payment.store', $transaction->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            
                            @if ($errors->any())
                                <div class="alert alert-danger mb-3">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            
                            <div class="mb-3">
                                <label for="payment_proof" class="form-label fw-semibold">Bukti Pembayaran*</label>
                                <input type="file" name="payment_proof" id="payment_proof" class="form-control @error('payment_proof') is-invalid @enderror" required>
                                <div class="form-text">Format: JPG, PNG, JPEG. Maksimal 2MB.</div>
                                @error('payment_proof')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-4">
                                <label for="notes" class="form-label fw-semibold">Catatan (Opsional)</label>
                                <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror" rows="3">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="d-grid gap-2 d-md-flex">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-upload me-2"></i> Unggah Bukti Pembayaran
                                </button>
                                <a href="{{ route('transactions.show', $transaction->id) }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-2"></i> Kembali
                                </a>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h3 class="fw-bold mb-3">Ringkasan Pesanan</h3>
                    
                    <div class="mb-4">
                        <h6 class="fw-bold mb-3">Produk</h6>
                        @foreach($transaction->transactionItems as $item)
                            <div class="d-flex mb-3">
                                <img src="{{ $item->product->image ? asset('storage/' . $item->product->image) : asset('images/product-placeholder.jpg') }}" 
                                     alt="{{ $item->product->name }}" 
                                     class="img-thumbnail me-3" style="width: 60px; height: 60px; object-fit: cover;">
                                <div>
                                    <p class="fw-semibold mb-0">{{ $item->product->name }}</p>
                                    <p class="text-muted small mb-0">
                                        {{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}
                                        @if($item->size)
                                            <span class="ms-1">({{ $item->size }})</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="border-top pt-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Subtotal</span>
                            <span>Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Pengiriman</span>
                            <span>Gratis</span>
                        </div>
                        <div class="d-flex justify-content-between fw-bold">
                            <span>Total</span>
                            <span class="text-primary">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h3 class="fw-bold mb-3">Alamat Pengiriman</h3>
                    
                    <div class="d-flex mb-3">
                        <div class="text-primary me-3">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div>
                            <p class="fw-semibold mb-1">{{ $transaction->shipping_name }}</p>
                            <p class="text-muted mb-1">{{ $transaction->shipping_phone }}</p>
                            <p class="text-muted mb-0">{{ $transaction->shipping_address }}</p>
                        </div>
                    </div>
                    
                    @if($transaction->notes)
                    <div class="mt-3">
                        <h6 class="fw-bold">Catatan:</h6>
                        <p class="text-muted mb-0">{{ $transaction->notes }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(function() {
            alert('Nomor rekening disalin: ' + text);
        }, function(err) {
            console.error('Gagal menyalin: ', err);
        });
    }
</script>
@endsection