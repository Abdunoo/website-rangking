<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'website_id',
        'type',
        'value',
        'user_id',
    ];

    // Relasi ke website
    public function website()
    {
        return $this->belongsTo(Website::class);
    }

    // Relasi ke pengguna yang menambahkan
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
