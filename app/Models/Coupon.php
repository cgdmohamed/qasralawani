<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'is_used',
        'used_by',
        'used_at', // if you want to mass-assign used_at as well
    ];

    /**
     * Relationship to the subscriber who used the coupon.
     */
    public function subscriber()
    {
        return $this->belongsTo(\App\Models\Subscriber::class, 'used_by');
    }
}
