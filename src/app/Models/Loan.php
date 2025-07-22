<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'book_id',
        'loan_date',
        'return_date',
        'status',
        'fine',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    // Relasi: Loan milik satu Book
    public function book()
    {
        return $this->belongsTo(Book::class);
    }
    public function scopeAktif($query)
    {
        return $query->where('status', 'dipinjam');
    }

    public function calculateFine($perDay = 500)
    {
        if ($this->return_date && now()->gt($this->return_date)) {
            $daysLate = now()->diffInDays($this->return_date);
            return $daysLate * $perDay;
        }

        return 0;
    }
}
