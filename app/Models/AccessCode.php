<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccessCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'access_code'
    ];

    public function participant()
    {
        return $this->hasOne(Participant::class, 'email', 'email');
    }
}
