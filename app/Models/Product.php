<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = [
        'title',
        'category',
        'address',
        'description',
        'image',
        'price',
    ];

    protected $searchableFields = ['*'];

    public function comments()
    {
        return $this->hasMany(Comment::class, 'destination_id');
    }

    public function likes()
    {
        return $this->hasMany(Like::class, 'post_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
