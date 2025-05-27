@extends('layouts.app')

@section('title', 'Detail Pesanan')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="fw-bold mb-0">Detail Pesanan #{{ $transaction->id }}</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('transactions.index') }}">Pesanan Saya</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Detail #{{ $transaction->id }}</li>
                </ol>
            </nav>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            {{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8 mb-4 mb-lg-0">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="fw-bold mb-0">Informasi Pesanan</h4>
                        <span class="badge bg-{{ $transaction->status == 'pending' ? 'warning' : 
                                            ($transaction->status == 'processing' ? 'info' : 
                                            ($transaction->status == 'shipped' ? 'primary' : 
                                            ($transaction->status == 'completed' ? 'success' : 
                                            ($transaction->status == 'cancelled' ? 'danger' : 'secondary')))) }}">
                            {{ $transaction->status == 'pending' ? 'Menunggu Pembayaran' : 
                            ($transaction->status == 'processing' ? 'Diproses' : 
                            ($transaction->status == 'shipped' ? 'Dikirim' : 
                            ($transaction->status == 'completed' ? 'Selesai' : 
                            ($transaction->status == 'cancelled' ? 'Dibatalkan' : ucfirst($transaction->status))))) }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <h6 class="fw-bold">Tanggal Pesanan</h6>
                            <p class="mb-0">{{ $transaction->created_at->format('d M Y, H:i') }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold">ID Pesanan</h6>
                            <p class="mb-0">#{{ $transaction->id }}</p>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Produk</th>
                                    <th>Harga</th>
                                    <th>Jumlah</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($transaction->transactionItems as $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $item->product->image ? asset('storage/' . $item->product->image) : asset('images/product-placeholder.jpg') }}" 
                                                    alt="{{ $item->product->name }}" class="img-thumbnail me-3" style="width: 60px; height: 60px; object-fit: cover;">
                                                <div>
                                                    <h6 class="mb-0">{{ $item->product->name }}</h6>
                                                    @if($item->size)
                                                        <small class="text-muted">Ukuran: {{ $item->size }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td class="text-end">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <td colspan="3" class="text-end fw-bold">Total:</td>
                                    <td class="text-end fw-bold text-primary">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    @if($transaction->notes)
                        <div class="mt-4">
                            <h6 class="fw-bold">Catatan:</h6>
                            <p class="mb-0">{{ $transaction->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h4 class="fw-bold mb-0">Informasi UMKM</h4>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        @if($transaction->umkm->image)
                            <img src="{{ asset('storage/' . $transaction->umkm->image) }}" 
                                 alt="{{ $transaction->umkm->name }}" 
                                 class="rounded-circle me-3" 
                                 style="width: 60px; height: 60px; object-fit: cover;">
                        @else
                            <div class="bg-primary text-white rounded-circle me-3 d-flex align-items-center justify-content-center" 
                                 style="width: 60px; height: 60px;">
                                <i class="fas fa-store fa-2x"></i>
                            </div>
                        @endif
                        <div>
                            <h5 class="fw-bold mb-1">{{ $transaction->umkm->name }}</h5>
                            <p class="text-muted mb-0">{{ $transaction->umkm->location ?? 'Indonesia' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h4 class="fw-bold mb-0">Alamat Pengiriman</h4>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="fw-bold">Nama Penerima</h6>
                        <p class="mb-0">{{ $transaction->shipping_name }}</p>
                    </div>
                    <div class="mb-3">
                        <h6 class="fw-bold">Nomor Telepon</h6>
                        <p class="mb-0">{{ $transaction->shipping_phone }}</p>
                    </div>
                    <div>
                        <h6 class="fw-bold">Alamat</h6>
                        <p class="mb-0">{{ $transaction->shipping_address }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h4 class="fw-bold mb-0">Status Pesanan</h4>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item px-0 d-flex justify-content-between">
                            <span>Status Pesanan</span>
                            <span class="badge bg-{{ $transaction->status == 'pending' ? 'warning' : 
                                            ($transaction->status == 'processing' ? 'info' : 
                                            ($transaction->status == 'shipped' ? 'primary' : 
                                            ($transaction->status == 'completed' ? 'success' : 
                                            ($transaction->status == 'cancelled' ? 'danger' : 'secondary')))) }}">
                                {{ $transaction->status == 'pending' ? 'Menunggu Pembayaran' : 
                                ($transaction->status == 'processing' ? 'Diproses' : 
                                ($transaction->status == 'shipped' ? 'Dikirim' : 
                                ($transaction->status == 'completed' ? 'Selesai' : 
                                ($transaction->status == 'cancelled' ? 'Dibatalkan' : ucfirst($transaction->status))))) }}
                            </span>
                        </li>
                        <li class="list-group-item px-0 d-flex justify-content-between">
                            <span>Metode Pembayaran</span>
                            <span class="fw-semibold">Transfer Bank</span>
                        </li>
                        @if($transaction->tracking_number)
                            <li class="list-group-item px-0 d-flex justify-content-between">
                                <span>Nomor Resi</span>
                                <span class="fw-semibold">{{ $transaction->tracking_number }}</span>
                            </li>
                        @endif
                    </ul>

                    <!-- Keterangan status pesanan -->
                    <div class="alert alert-info mt-3 mb-0">
                        <small><i class="fas fa-info-circle me-2"></i> Status pesanan akan berubah "selesai" ketika barang anda sedang diantar.</small>
                    </div>

                    <hr>

                    <div class="d-grid gap-2">
                        @if($transaction->status == 'pending')
                            <a href="{{ route('payment.show', $transaction->id) }}" class="btn btn-primary">
                                <i class="fas fa-credit-card me-2"></i> Bayar Pesanan
                            </a>
                        @elseif($transaction->status == 'processing' || $transaction->status == 'shipped')
                            @if($transaction->umkm && $transaction->umkm->whatsapp)
                                <a href="{{ route('payment.contact_seller', $transaction->id) }}" class="btn btn-success">
                                    <i class="fab fa-whatsapp me-2"></i> Hubungi Penjual
                                </a>
                            @endif
                        @endif

                        <a href="{{ route('transactions.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Kembali ke Daftar Pesanan
                        </a>
                    </div>
                </div>
            </div>

            @if($transaction->paymentProof)
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h4 class="fw-bold mb-0">Bukti Pembayaran</h4>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <img src="{{ asset('storage/' . $transaction->paymentProof->image) }}" 
                                alt="Bukti Pembayaran" 
                                class="img-fluid rounded" 
                                style="max-height: 200px;">
                        </div>
                        <p class="text-muted small text-center">
                            Diunggah pada {{ $transaction->paymentProof->created_at->format('d M Y, H:i') }}
                        </p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection