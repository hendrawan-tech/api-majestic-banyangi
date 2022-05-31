<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = ['name', 'number', 'bank', 'image'];

    protected $searchableFields = ['*'];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
