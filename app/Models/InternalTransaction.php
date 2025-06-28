<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InternalTransaction extends Model
{
    protected $fillable = [
        'transaction_reference',
        'amount',
        'status',
    ];

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
}
