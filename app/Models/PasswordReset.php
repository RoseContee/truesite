<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'type', 'email', 'token', 'created_at',
    ];

    public function scopeType($query, $type) {
        $query->where('type', $type);
    }
}
