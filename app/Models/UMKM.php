<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class UMKM extends Model
{
    use HasFactory;
    protected $table = 'umkms';

    protected $fillable = [
        'name',
        'address',
        'phone',
        'description',
        'image',
        'bank_name',
        'bank_account_number',
        'bank_account_name',
        'whatsapp',
        'instagram',
        'facebook',
        'operating_hours',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }
}