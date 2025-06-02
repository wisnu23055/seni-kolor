@extends('layouts.app')

@section('title', 'Dashboard Pengguna')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="fw-bold mb-0">Dashboard Pengguna</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                </ol>
            </nav>
        </div>
    </div>
    
    <div class="row g-4">
        <!-- User Welcome Card -->
        <div class="col-12">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h4 class="card-title fw-bold">Selamat Datang, {{ Auth::user()->name }}!</h4>
                    <p class="card-text">Kelola akun dan pesanan Anda melalui dashboard ini.</p>
                </div>
            </div>
        </div>
        
        <!-- Stats Cards - PERBAIKAN -->
        <div class="col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h5 class="card-title fw-bold mb-0">Pesanan</h5>
                        <div class="text-primary">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                    </div>
                    {{-- PERBAIKAN: Cek apakah $transactions adalah collection atau array --}}
                    <h2 class="display-6 fw-bold mb-0">
                        {{ is_array($transactions) ? count($transactions) : $transactions->count() }}
                    </h2>
                    <p class="card-text text-muted">Total pesanan</p>
                </div>
            </div>
        </div>
        
        <div class="col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h5 class="card-title fw-bold mb-0">Menunggu</h5>
                        <div class="text-warning">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                    {{-- PERBAIKAN: Handle kedua kemungkinan --}}
                    <h2 class="display-6 fw-bold mb-0">
                    @if(is_array($transactions))
                        {{ count(array_filter($transactions, function($tx) { return $tx->status == 'pending'; })) }}
                    @else
                        {{ $transactions->where('status', 'pending')->count() }}
                    @endif
                    </h2>
                    <p class="card-text text-muted">Pesanan dalam proses</p>
                </div>
            </div>
        </div>
        
        <div class="col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h5 class="card-title fw-bold mb-0">Selesai</h5>
                        <div class="text-success">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                    {{-- PERBAIKAN: Handle kedua kemungkinan --}}
                    <h2 class="display-6 fw-bold mb-0">
                    @if(is_array($transactions))
                        {{ count(array_filter($transactions, function($tx) { return $tx->status == 'completed'; })) }}
                    @else
                        {{ $transactions->where('status', 'completed')->count() }}
                    @endif
                    </h2>
                    <p class="card-text text-muted">Pesanan selesai</p>
                </div>
            </div>
        </div>
        
        <div class="col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h5 class="card-title fw-bold mb-0">Keranjang</h5>
                        <div class="text-info">
                            <i class="fas fa-shopping-basket"></i>
                        </div>
                    </div>
                    {{-- PERBAIKAN: Gunakan count() function untuk array --}}
                    <h2 class="display-6 fw-bold mb-0">
                    {{ is_array($cartItems) ? count($cartItems) : $cartItems->count() }}
                    </h2>
                    <p class="card-text text-muted">Item dalam keranjang</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Transaksi Terbaru - PERBAIKAN -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title fw-bold mb-0">Transaksi Terbaru</h5>
                        {{-- PERBAIKAN: Cek apakah ada transaksi --}}
                        @if((is_array($transactions) && count($transactions) > 0) || (!is_array($transactions) && $transactions->isNotEmpty()))
                            <a href="{{ route('transactions.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    {{-- PERBAIKAN: Cek empty dengan lebih aman --}}
                    @if((is_array($transactions) && count($transactions) == 0) || (!is_array($transactions) && $transactions->isEmpty()))
                        <div class="text-center py-5">
                            <div class="text-muted mb-3">
                                <i class="fas fa-receipt fa-3x"></i>
                            </div>
                            <h4 class="fw-bold">Belum Ada Transaksi</h4>
                            <p class="text-muted">Mulai belanja untuk melihat riwayat transaksi di sini</p>
                            <a href="{{ route('products.index') }}" class="btn btn-primary">Belanja Sekarang</a>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Tanggal</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- PERBAIKAN: Handle array dan collection --}}
                                    @php
                                        $transactionsList = is_array($transactions) ? array_slice($transactions, 0, 5) : $transactions->take(5);
                                    @endphp
                                    
                                    @forelse($transactionsList as $tx)
                                        <tr>
                                            <td class="fw-semibold">#{{ $tx->id }}</td>
                                            <td>{{ $tx->created_at->format('d M Y') }}</td>
                                            <td class="fw-semibold">Rp {{ number_format($tx->total_amount, 0, ',', '.') }}</td>
                                            <td>
                                                @if($tx->status == 'completed')
                                                    <span class="badge bg-success">Selesai</span>
                                                @elseif($tx->status == 'pending')
                                                    <span class="badge bg-warning">Menunggu</span>
                                                @elseif($tx->status == 'processing')
                                                    <span class="badge bg-info">Diproses</span>
                                                @elseif($tx->status == 'cancelled')
                                                    <span class="badge bg-danger">Dibatalkan</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ $tx->status }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('transactions.show', $tx->id) }}" class="btn btn-sm btn-outline-primary">Detail</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4">Tidak ada transaksi</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <!-- Keranjang Belanja - PERBAIKAN -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title fw-bold mb-0">Keranjang Belanja</h5>
                        {{-- PERBAIKAN: Cek apakah ada item di keranjang --}}
                        @if((is_array($cartItems) && count($cartItems) > 0) || (!is_array($cartItems) && $cartItems->isNotEmpty()))
                            <a href="{{ route('cart.index') }}" class="btn btn-sm btn-outline-primary">Lihat Keranjang</a>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    {{-- PERBAIKAN: Cek empty dengan lebih aman --}}
                    @if((is_array($cartItems) && count($cartItems) == 0) || (!is_array($cartItems) && $cartItems->isEmpty()))
                        <div class="text-center py-5">
                            <div class="text-muted mb-3">
                                <i class="fas fa-shopping-cart fa-3x"></i>
                            </div>
                            <h4 class="fw-bold">Keranjang Kosong</h4>
                            <p class="text-muted">Tambahkan produk ke keranjang untuk melakukan pembelian</p>
                            <a href="{{ route('products.index') }}" class="btn btn-primary">Belanja Sekarang</a>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>Produk</th>
                                        <th>Harga</th>
                                        <th>Jumlah</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- PERBAIKAN: Handle array dan collection --}}
                                    @php
                                        $cartItemsList = is_array($cartItems) ? array_slice($cartItems, 0, 3) : $cartItems->take(3);
                                        $totalItems = is_array($cartItems) ? count($cartItems) : $cartItems->count();
                                    @endphp
                                    
                                    @forelse($cartItemsList as $item)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ $item->product->image ? asset('storage/' . $item->product->image) : asset('images/product-placeholder.jpg') }}" 
                                                         alt="{{ $item->product->name }}" class="img-thumbnail me-3" style="width: 50px; height: 50px; object-fit: cover;">
                                                    <div>
                                                        <p class="fw-semibold mb-0">{{ $item->product->name }}</p>
                                                        @if(isset($item->size))
                                                            <small class="text-muted">Ukuran: {{ $item->size }}</small>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td>Rp {{ number_format($item->product->price, 0, ',', '.') }}</td>
                                            <td>{{ $item->quantity }}</td>
                                            <td class="fw-semibold">Rp {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-4">Tidak ada item dalam keranjang</td>
                                        </tr>
                                    @endforelse
                                    
                                    @if($totalItems > 3)
                                        <tr>
                                            <td colspan="4" class="text-center">
                                                <a href="{{ route('cart.index') }}" class="text-decoration-none">Lihat {{ $totalItems - 3 }} item lainnya...</a>
                                            </td>
                                        </tr>
                                    @endif
                                    
                                    {{-- PERBAIKAN: Cek apakah variabel ada --}}
                                    @if(isset($totalBelanja))
                                        <tr class="table-light">
                                            <td colspan="3" class="text-end fw-bold">Total:</td>
                                            <td class="fw-bold text-primary">Rp {{ number_format($totalBelanja, 0, ',', '.') }}</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                            
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-3">
                                <a href="{{ route('checkout.index') }}" class="btn btn-primary">
                                    <i class="fas fa-shopping-bag me-2"></i> Checkout
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <!-- Additional Actions -->
    <div class="row mt-4 g-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title fw-bold mb-3">Akun Saya</h5>
                    <p class="card-text text-muted mb-4">Kelola informasi profil dan password Anda</p>
                    <a href="{{ route('profile.index') }}" class="btn btn-outline-primary">Edit Profil</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title fw-bold mb-3">Butuh Bantuan?</h5>
                    <p class="card-text text-muted mb-4">Hubungi kami untuk pertanyaan tentang pesanan atau produk</p>
                    <a href="{{ route('contact') }}" class="btn btn-outline-primary">Kontak Kami</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection