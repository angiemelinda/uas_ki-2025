<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Member extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'student_id',
        'class',
    ];

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }

    public function getCurrentLoansCountAttribute()
    {
        return $this->loans()->where('status', 'dipinjam')->count();
    }
}
