<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'author',
        'year',
        'stock',
    ];

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }

    public function getBorrowedCountAttribute()
    {
        return $this->loans()->where('status', 'dipinjam')->count();
    }

    public function getAvailableStockAttribute()
    {
        return $this->stock - $this->borrowed_count;
    }
}
