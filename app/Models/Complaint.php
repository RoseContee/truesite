<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'domain', 'title', 'complaints', 'screenshot', 'reason', 'status',
    ];

    public function scopePending($query) {
        $query->where('status', 'pending');
    }

    public function scopeApproved($query) {
        $query->where('status', 'approved');
    }

    public function scopeStatus($query, $status) {
        if (is_array($status)) {
            $query->whereIn('status', $status);
        } else {
            $query->where('status', $status);
        }
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
