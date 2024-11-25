<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class Setting extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'key',
        'value',
        'type',
        'is_public'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->id = Uuid::uuid4()->toString();
        });
    }

    // Optional: Accessor and mutator for value based on type
    public function getValueAttribute($value)
    {
        switch ($this->attributes['type']) {
            case 'integer':
                return (int) $value;
            case 'boolean':
                return (bool) $value;
            case 'json':
                return json_decode($value, true);
            default:
                return $value;
        }
    }

    public function setValueAttribute($value)
    {
        $this->attributes['value'] = match ($this->attributes['type'] ?? 'string') {
            'json' => json_encode($value),
            default => $value
        };
    }
}
