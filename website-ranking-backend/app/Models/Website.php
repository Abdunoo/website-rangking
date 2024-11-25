<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Website extends Model
{
    protected $guarded = ['id'];

    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

}
