<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    /**
     * Status konstanta untuk konsistensi (sesuai database)
     */
    const STATUS_PENDING = 'pending';
    const STATUS_PROCESSING = 'processing';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';
    
    /**
     * Array semua kemungkinan status (sesuai database)
     */
    const STATUSES = [
        self::STATUS_PENDING,
        self::STATUS_PROCESSING,
        self::STATUS_COMPLETED,
        self::STATUS_CANCELLED,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'umkm_id',
        'total_amount',
        'status',
        'shipping_address',
        'shipping_phone',
        'shipping_name',
        'notes',
        'payment_method',
        'tracking_number',
    ];

    /**
     * Get the user that owns the transaction.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the UMKM associated with the transaction.
     */
    public function umkm()
    {
        return $this->belongsTo(UMKM::class);
    }

    /**
     * Get the transaction items for the transaction.
     */
    public function transactionItems()
    {
        return $this->hasMany(TransactionItem::class);
    }

    /**
     * Get the payment proof associated with the transaction.
     */
    public function paymentProof()
    {
        return $this->hasOne(PaymentProof::class);
    }
    
    /**
     * Get the formatted total amount.
     */
    public function getTotalAmountFormattedAttribute()
    {
        return 'Rp ' . number_format($this->total_amount, 0, ',', '.');
    }
    
    /**
     * Get the status badge HTML.
     */
    public function getStatusBadgeAttribute()
    {
        $badgeColors = [
            self::STATUS_PENDING => 'warning',
            self::STATUS_PROCESSING => 'info',
            self::STATUS_COMPLETED => 'success',
            self::STATUS_CANCELLED => 'danger',
        ];
        
        $statusLabels = [
            self::STATUS_PENDING => 'Menunggu',
            self::STATUS_PROCESSING => 'Diproses',
            self::STATUS_COMPLETED => 'Selesai',
            self::STATUS_CANCELLED => 'Dibatalkan',
        ];
        
        $color = $badgeColors[$this->status] ?? 'secondary';
        $label = $statusLabels[$this->status] ?? ucfirst($this->status);
        
        return '<span class="badge bg-' . $color . '">' . $label . '</span>';
    }
    
    /**
     * Scope a query to only include pending transactions.
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }
    
    /**
     * Scope a query to only include processing transactions.
     */
    public function scopeProcessing($query)
    {
        return $query->where('status', self::STATUS_PROCESSING);
    }
    
    /**
     * Scope a query to only include completed transactions.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }
    
    /**
     * Scope a query to only include cancelled transactions.
     */
    public function scopeCancelled($query)
    {
        return $query->where('status', self::STATUS_CANCELLED);
    }
    
    /**
     * Check if the transaction is in pending status.
     */
    public function isPending()
    {
        return $this->status === self::STATUS_PENDING;
    }
    
    /**
     * Check if payment proof has been uploaded.
     */
    public function hasPaymentProof()
    {
        return $this->paymentProof()->exists();
    }
    
    /**
     * Get the created date in formatted string.
     */
    public function getCreatedDateAttribute()
    {
        return $this->created_at->format('d M Y');
    }
}