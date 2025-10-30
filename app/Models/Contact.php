<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email', 
        'phone',
        'subject',
        'message',
        'ip_address',
        'user_agent',
        'read_at'
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    // Scope for unread messages
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    // Scope for read messages
    public function scopeRead($query)
    {
        return $query->whereNotNull('read_at');
    }
}