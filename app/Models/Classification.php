<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Classification extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'description',
    ];

    public function scopeSearch($query, $search)
    {
        return $query->when($search, function($query, $find) {
            return $query
                ->Where('code', $find)
                ->orWhere('description', $find);
        });
    }

    public function scopeRender($query, $search)
    {
        return $query
            ->search($search);
    }
}
