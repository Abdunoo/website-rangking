<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebsiteTrends extends Model
{
    protected $guarded = ['id'];

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function websites()
    {
        return $this->belongsTo(Website::class, 'website_id');
    }
}

