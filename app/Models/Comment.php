<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = ['comment', 'destination_id', 'user_id'];

    protected $searchableFields = ['*'];

    public function destination()
    {
        return $this->belongsTo(Product::class, 'destination_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
