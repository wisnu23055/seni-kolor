<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\TransactionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();
        
        // Apply category filter if provided
        if ($request->has('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }
        
        // Apply price filter if provided
        if ($request->has('min_price') && $request->min_price !== null) {
            $query->where('price', '>=', $request->min_price);
        }
        
        if ($request->has('max_price') && $request->max_price !== null) {
            $query->where('price', '<=', $request->max_price);
        }
        
        // Apply sorting
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'price_low':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_high':
                    $query->orderBy('price', 'desc');
                    break;
                case 'latest':
                default:
                    $query->latest();
                    break;
            }
        } else {
            $query->latest(); // Default sorting
        }
        
        $products = $query->paginate(12);
        $categories = Category::all();
        
        return view('products.index', compact('products', 'categories'));
    }
    
    public function show($id)
    {
        $product = Product::findOrFail($id);
        
        // Ambil produk yang paling banyak dibeli (terlaris)
        $bestSellerProduct = Product::select('products.*')
            ->join('transaction_items', 'products.id', '=', 'transaction_items.product_id')
            ->join('transactions', 'transaction_items.transaction_id', '=', 'transactions.id')
            ->where('transactions.status', '!=', 'canceled') // Hanya hitung transaksi yang tidak dibatalkan
            ->where('products.id', '!=', $product->id) // Jangan ambil produk yang sama
            ->groupBy(
                'products.id', 
                'products.name', 
                'products.description', 
                'products.price', 
                'products.image', 
                'products.category_id',
                'products.umkm_id',
                'products.stock',
                'products.is_featured',
                'products.created_at',
                'products.updated_at'
            )
            ->selectRaw('SUM(transaction_items.quantity) as total_sold')
            ->orderBy('total_sold', 'desc')
            ->first();
            
        return view('products.show', compact('product', 'bestSellerProduct'));
    }
}