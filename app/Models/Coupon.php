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
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'used_by');
    }
}
