<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccessCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'access_code',
        'document',
        'registration_count'
    ];

    public function canRegister(): bool
    {
        return $this->registration_count < 5;
    }
}
