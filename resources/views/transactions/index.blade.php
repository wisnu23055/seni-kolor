@extends('layouts.app')

@section('title', 'Pesanan Saya')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="fw-bold mb-0">Pesanan Saya</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Pesanan Saya</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    
                    @if($transactions->isEmpty())
                        <div class="text-center py-5">
                            <div class="mb-4">
                                <i class="fas fa-shopping-bag fa-4x text-muted"></i>
                            </div>
                            <h3 class="fw-bold mb-3">Belum Ada Pesanan</h3>
                            <p class="text-muted mb-4">Anda belum melakukan pembelian apapun.</p>
                            <a href="{{ route('products.index') }}" class="btn btn-primary">
                                <i class="fas fa-shopping-bag me-2"></i> Mulai Belanja
                            </a>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID Pesanan</th>
                                        <th>Tanggal</th>
                                        <th>UMKM</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($transactions as $transaction)
                                        <tr>
                                            <td class="fw-semibold">#{{ $transaction->id }}</td>
                                            <td>{{ $transaction->created_at->format('d M Y') }}</td>
                                            <td>{{ $transaction->umkm->name ?? 'UMKM' }}</td>
                                            <td class="fw-semibold">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
                                            <td>
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
                                            </td>
                                            <td>
                                                <a href="{{ route('transactions.show', $transaction->id) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye me-1"></i> Detail
                                                </a>
                                                
                                                @if($transaction->status == 'pending')
                                                    <a href="{{ route('payment.show', $transaction->id) }}" class="btn btn-sm btn-primary">
                                                        <i class="fas fa-credit-card me-1"></i> Bayar
                                                    </a>
                                                    
                                                    <!-- Tombol Batalkan Pesanan -->
                                                    <form action="{{ route('transactions.cancel', $transaction->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                                onclick="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')">
                                                            <i class="fas fa-times me-1"></i> Batalkan
                                                        </button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="d-flex justify-content-center mt-4">
                            {{ $transactions->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection