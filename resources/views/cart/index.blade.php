@extends('layouts.app')

@section('title', 'Keranjang Belanja')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="fw-bold mb-0">Keranjang Belanja</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Keranjang</li>
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

                    @if($cartItems->count())
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th style="min-width: 300px;">Produk</th>
                                        <th>Harga</th>
                                        <th style="min-width: 160px;">Jumlah</th>
                                        <th class="text-end">Subtotal</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cartItems as $item)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ $item->product->image ? asset('storage/' . $item->product->image) : asset('images/product-placeholder.jpg') }}" 
                                                        alt="{{ $item->product->name }}" 
                                                        class="img-thumbnail me-3" style="width: 80px; height: 80px; object-fit: cover;">
                                                    <div>
                                                        <h5 class="mb-1">{{ $item->product->name }}</h5>
                                                        @if($item->size)
                                                            <span class="badge bg-secondary">Ukuran: {{ $item->size }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="fw-semibold">Rp {{ number_format($item->product->price, 0, ',', '.') }}</td>
                                            <td>
                                                <form action="{{ route('cart.update', $item->id) }}" method="POST" class="d-flex align-items-center">
                                                    @csrf
                                                    @method('PATCH')
                                                    <div class="input-group input-group-sm" style="width: 100px;">
                                                        <input type="number" name="quantity" id="quantity-{{ $item->id }}" 
                                                            class="form-control text-center" value="{{ $item->quantity }}" min="1">
                                                    </div>
                                                    <button type="submit" class="btn btn-sm btn-outline-primary ms-2" title="Perbarui jumlah">
                                                        <i class="fas fa-sync-alt"></i>
                                                    </button>
                                                </form>
                                            </td>
                                            <td class="fw-bold text-end" id="subtotal-{{ $item->id }}">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                            <td>
                                                <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                                    @csrf 
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus item ini?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <td colspan="3" class="text-end fw-bold">Total:</td>
                                        <td class="text-end fw-bold text-primary fs-5" id="cart-total">Rp {{ number_format($total, 0, ',', '.') }}</td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i> Lanjutkan Belanja
                            </a>
                            <a href="{{ route('checkout.index') }}" class="btn btn-primary">
                                <i class="fas fa-shopping-bag me-2"></i> Lanjut ke Checkout
                            </a>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="mb-4">
                                <i class="fas fa-shopping-cart fa-4x text-muted"></i>
                            </div>
                            <h3 class="fw-bold mb-3">Keranjang Belanja Kosong</h3>
                            <p class="text-muted mb-4">Anda belum menambahkan produk apapun ke keranjang.</p>
                            <a href="{{ route('products.index') }}" class="btn btn-primary">
                                <i class="fas fa-shopping-bag me-2"></i> Mulai Belanja
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if($cartItems->count())
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h4 class="fw-bold mb-3">Rekomendasi Produk</h4>
                    <div class="row g-4">
                        @if($recommendedProducts->count())
                            @foreach($recommendedProducts as $product)
                                <div class="col-6 col-md-3">
                                    <div class="card h-100 border-0 shadow-sm">
                                        <a href="{{ route('products.show', $product->id) }}">
                                            <img 
                                                src="{{ $product->image ? asset('storage/' . $product->image) : asset('images/product-placeholder.jpg') }}" 
                                                class="card-img-top" 
                                                style="height: 180px; object-fit: cover;" 
                                                alt="{{ $product->name }}"
                                            >
                                        </a>
                                        <div class="card-body">
                                            <h5 class="card-title fw-semibold">{{ $product->name }}</h5>
                                            <p class="card-text text-primary fw-bold">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <a href="{{ route('products.show', $product->id) }}" class="btn btn-sm btn-outline-primary">Lihat Detail</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="col-12">
                                <p class="text-muted text-center mb-0">Tidak ada rekomendasi produk saat ini</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

@endsection

@section('scripts')
@endsection