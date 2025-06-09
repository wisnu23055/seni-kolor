<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * @OA\Info(
 *     title="Seni Kolor API",
 *     version="1.0.0",
 *     description="API untuk aplikasi Seni Kolor dengan berbagai metode autentikasi: JWT Bearer Token, Basic Auth, dan API Key.
 *                  
 *                  ## Cara Testing di Swagger UI:
 *                  
 *                  ### 1. JWT Authentication (Bearer Token)
 *                  - Klik endpoint `/api/login` untuk mendapatkan token
 *                  - Input credentials: admin@example.com/password atau customer@example.com/password
 *                  - Copy `access_token` dari response
 *                  - Klik tombol 'Authorize' (🔒) di kanan atas
 *                  - Di bagian 'bearerAuth', masukkan: Bearer {your_token}
 *                  - Klik 'Authorize' → Sekarang bisa test semua endpoint JWT
 *                  
 *                  ### 2. Basic Authentication
 *                  - Klik tombol 'Authorize' (🔒) di kanan atas
 *                  - Di bagian 'basicAuth', masukkan:
 *                    * Username: admin@example.com atau customer@example.com
 *                    * Password: password
 *                  - Klik 'Authorize' → Sekarang bisa test endpoint Basic Auth (Read Only)
 *                  
 *                  ### 3. API Key Authentication
 *                  - Klik tombol 'Authorize' (🔒) di kanan atas
 *                  - Di bagian 'apiKeyAuth', masukkan: HQldYrXmCAojpQfYzRxJXqbx9vg9EG4d
 *                  - Klik 'Authorize' → Sekarang bisa test endpoint `/api/secure-data`
 *                  
 *                  ## Role Permission:
 *                  - **Admin**: Bisa CRUD semua endpoint JWT + Read endpoint Basic Auth
 *                  - **Customer**: Hanya bisa Read endpoint JWT & Basic Auth
 *                  - **API Key**: Hanya bisa akses endpoint `/api/secure-data`"
 * )
 * 
 * @OA\Server(
 *     url="http://localhost:8000",
 *     description="Laravel Development Server"
 * )
 * 
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="JWT Bearer token authentication"
 * )
 * 
 * @OA\SecurityScheme(
 *     securityScheme="basicAuth",
 *     type="http",
 *     scheme="basic",
 *     description="Basic HTTP authentication"
 * )
 * 
 * @OA\SecurityScheme(
 *     securityScheme="apiKeyAuth",
 *     type="apiKey",
 *     in="header",
 *     name="senikolor-api-key",
 *     description="API Key authentication"
 * )
 * 
 * @OA\Schema(
 *     schema="Product",
 *     type="object",
 *     required={"id", "name", "price", "description", "stock"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Celana Premium"),
 *     @OA\Property(property="price", type="number", example=250000),
 *     @OA\Property(property="description", type="string", example="Celana dengan motif tradisional"),
 *     @OA\Property(property="category_id", type="integer", example=1),
 *     @OA\Property(property="umkm_id", type="integer", example=1),
 *     @OA\Property(property="image", type="string", example="products/1234567890_celana.jpg"),
 *     @OA\Property(property="stock", type="integer", example=15),
 *     @OA\Property(property="is_featured", type="boolean", example=true),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class ProductApiController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/products",
     *     summary="List semua produk (JWT Auth)",
     *     tags={"JWT Auth"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="List Produk",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean"),
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="count", type="integer"),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Product"))
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function index()
    {
        $products = Product::all();
        return response()->json([
            'success' => true,
            'message' => 'List Produk (JWT Auth)',
            'count' => $products->count(),
            'data' => $products
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/products/{id}",
     *     summary="Detail produk (JWT Auth)",
     *     tags={"JWT Auth"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detail Produk",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean"),
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="data", ref="#/components/schemas/Product")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Produk tidak ditemukan"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function show($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json([
                'success' => false, 
                'message' => 'Produk tidak ditemukan'
            ], 404);
        }
        
        return response()->json([
            'success' => true, 
            'message' => 'Detail Produk (JWT Auth)',
            'data' => $product
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/products",
     *     summary="Buat produk baru (Admin Only)",
     *     tags={"JWT Auth"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"name","price","description","category_id","umkm_id","stock"},
     *                 @OA\Property(property="name", type="string", example="Celana Premium"),
     *                 @OA\Property(property="price", type="number", example=150000),
     *                 @OA\Property(property="description", type="string", example="Celana dengan motif tradisional"),
     *                 @OA\Property(property="category_id", type="integer", example=1),
     *                 @OA\Property(property="umkm_id", type="integer", example=1),
     *                 @OA\Property(property="image", type="string", format="binary", description="Upload file gambar"),
     *                 @OA\Property(property="stock", type="integer", example=10),
     *                 @OA\Property(property="is_featured", type="boolean", example=false)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Produk berhasil dibuat",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean"),
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="data", ref="#/components/schemas/Product"),
     *             @OA\Property(property="image_url", type="string")
     *         )
     *     ),
     *     @OA\Response(response=403, description="Hanya admin yang dapat membuat produk"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function store(Request $request)
    {
        $user = auth('api')->user();
        if (!$user || !$user->hasRole('admin')) {
            return response()->json([
                'error' => 'Forbidden. Only admin can create products',
                'user_roles' => $user ? $user->getRoleNames() : []
            ], 403);
        }

        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'price' => 'required|numeric|min:0',
                'description' => 'required|string',
                'category_id' => 'required|integer',
                'umkm_id' => 'required|integer',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'stock' => 'required|integer|min:0',
                'is_featured' => 'nullable|boolean',
            ]);
            
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '_' . $image->getClientOriginalName();
                $imagePath = $image->storeAs('products', $imageName, 'public');
                $validated['image'] = $imagePath;
            }
            
            if (!isset($validated['is_featured'])) {
                $validated['is_featured'] = false;
            }
            
            $product = Product::create($validated);
            
            return response()->json([
                'success' => true, 
                'message' => 'Produk berhasil dibuat', 
                'data' => $product,
                'image_url' => $product->image ? asset('storage/' . $product->image) : null
            ], 201);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating product',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/products/{id}",
     *     summary="Update produk (Admin Only - JSON)",
     *     tags={"JWT Auth"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Celana Premium Updated"),
     *             @OA\Property(property="price", type="number", example=175000),
     *             @OA\Property(property="description", type="string", example="Celana premium"),
     *             @OA\Property(property="category_id", type="integer", example=1),
     *             @OA\Property(property="umkm_id", type="integer", example=1),
     *             @OA\Property(property="stock", type="integer", example=15),
     *             @OA\Property(property="is_featured", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(response=200, description="Produk berhasil diupdate"),
     *     @OA\Response(response=404, description="Produk tidak ditemukan"),
     *     @OA\Response(response=403, description="Hanya admin yang dapat mengupdate produk")
     * )
     */

    /**
     * @OA\Post(
     *     path="/api/products/{id}/update",
     *     summary="Update produk dengan file upload (Admin Only)",
     *     tags={"JWT Auth"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(property="name", type="string", example="Celana Premium Updated"),
     *                 @OA\Property(property="price", type="number", example=175000),
     *                 @OA\Property(property="description", type="string", example="Celana premium updated"),
     *                 @OA\Property(property="category_id", type="integer", example=1),
     *                 @OA\Property(property="umkm_id", type="integer", example=1),
     *                 @OA\Property(property="image", type="string", format="binary", description="Upload file gambar baru"),
     *                 @OA\Property(property="stock", type="integer", example=15),
     *                 @OA\Property(property="is_featured", type="boolean", example=true)
     *             )
     *         )
     *     ),
     *     @OA\Response(response=200, description="Produk berhasil diupdate"),
     *     @OA\Response(response=404, description="Produk tidak ditemukan"),
     *     @OA\Response(response=403, description="Hanya admin yang dapat mengupdate produk")
     * )
     */
    public function update(Request $request, $id)
    {
        $user = auth('api')->user();
        if (!$user || !$user->hasRole('admin')) {
            return response()->json([
                'error' => 'Forbidden. Only admin can update products',
                'user_roles' => $user ? $user->getRoleNames() : []
            ], 403);
        }

        $product = Product::find($id);
        if (!$product) {
            return response()->json([
                'success' => false, 
                'message' => 'Produk tidak ditemukan'
            ], 404);
        }
        
        try {
            $validated = $request->validate([
                'name' => 'sometimes|string|max:255',
                'price' => 'sometimes|numeric|min:0',
                'description' => 'sometimes|string',
                'category_id' => 'sometimes|integer',
                'umkm_id' => 'sometimes|integer',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'stock' => 'sometimes|integer|min:0',
                'is_featured' => 'sometimes|boolean',
            ]);
            
            if ($request->hasFile('image')) {
                if ($product->image) {
                    Storage::disk('public')->delete($product->image);
                }
                
                $image = $request->file('image');
                $imageName = time() . '_' . $image->getClientOriginalName();
                $imagePath = $image->storeAs('products', $imageName, 'public');
                $validated['image'] = $imagePath;
            }
            
            $product->update($validated);
            
            return response()->json([
                'success' => true, 
                'message' => 'Produk berhasil diupdate', 
                'data' => $product->fresh(),
                'image_url' => $product->image ? asset('storage/' . $product->image) : null
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating product',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/products/{id}",
     *     summary="Delete produk (Admin Only)",
     *     tags={"JWT Auth"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Produk berhasil dihapus"),
     *     @OA\Response(response=404, description="Produk tidak ditemukan"),
     *     @OA\Response(response=403, description="Hanya admin yang dapat menghapus produk")
     * )
     */
    public function destroy($id)
    {
        $user = auth('api')->user();
        if (!$user || !$user->hasRole('admin')) {
            return response()->json([
                'error' => 'Forbidden. Only admin can delete products',
                'user_roles' => $user ? $user->getRoleNames() : []
            ], 403);
        }

        $product = Product::find($id);
        if (!$product) {
            return response()->json([
                'success' => false, 
                'message' => 'Produk tidak ditemukan'
            ], 404);
        }
        
        try {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            
            $product->delete();
            
            return response()->json([
                'success' => true, 
                'message' => 'Produk berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting product',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/products-basic",
     *     summary="List semua produk (Basic Auth - Read Only)",
     *     description="Endpoint untuk mendapatkan semua produk menggunakan Basic Authentication. Admin dan Customer bisa akses endpoint ini untuk read-only.",
     *     tags={"Basic Auth"},
     *     security={{"basicAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="List Produk Berhasil",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="List Produk (Basic Auth - Read Only)"),
     *             @OA\Property(property="count", type="integer", example=5),
     *             @OA\Property(property="auth_method", type="string", example="Basic Authentication"),
     *             @OA\Property(
     *                 property="data", 
     *                 type="array", 
     *                 @OA\Items(ref="#/components/schemas/Product")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized - Username/Password salah",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Unauthenticated.")
     *         )
     *     )
     * )
     */
    public function indexBasic()
    {
        $products = Product::all();
        return response()->json([
            'success' => true,
            'message' => 'List Produk (Basic Auth - Read Only)',
            'count' => $products->count(),
            'auth_method' => 'Basic Authentication',
            'data' => $products
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/products-basic/{id}",
     *     summary="Detail produk (Basic Auth - Read Only)",
     *     description="Endpoint untuk mendapatkan detail produk menggunakan Basic Authentication. Admin dan Customer bisa akses endpoint ini untuk read-only.",
     *     tags={"Basic Auth"},
     *     security={{"basicAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID produk yang ingin dilihat detailnya",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detail Produk Berhasil",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Detail Produk (Basic Auth - Read Only)"),
     *             @OA\Property(property="auth_method", type="string", example="Basic Authentication"),
     *             @OA\Property(property="data", ref="#/components/schemas/Product")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Produk tidak ditemukan",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Produk tidak ditemukan")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized - Username/Password salah",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Unauthenticated.")
     *         )
     *     )
     * )
     */
    public function showBasic($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json([
                'success' => false, 
                'message' => 'Produk tidak ditemukan'
            ], 404);
        }
        
        return response()->json([
            'success' => true, 
            'message' => 'Detail Produk (Basic Auth - Read Only)',
            'auth_method' => 'Basic Authentication',
            'data' => $product
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/secure-data",
     *     summary="Get products using API Key",
     *     description="Endpoint khusus untuk testing API Key authentication. Gunakan header 'senikolor-api-key' dengan value yang valid dari environment variable.",
     *     tags={"API Key"},
     *     security={{"apiKeyAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Success - Data berhasil diambil dengan API Key",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="List Produk (API Key)"),
     *             @OA\Property(property="authenticated_via", type="string", example="API Key"),
     *             @OA\Property(
     *                 property="data", 
     *                 type="array", 
     *                 @OA\Items(ref="#/components/schemas/Product")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Invalid API Key - API Key tidak valid atau tidak ada",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Invalid API Key")
     *         )
     *     )
     * )
     */
    public function secureData()
    {
        // Method ini hanya untuk dokumentasi, implementasi tetap di routes
        return response()->json(['message' => 'Method for documentation only']);
    }
}
