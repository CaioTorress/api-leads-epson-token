<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Participant extends Model
{
    use HasFactory, HasApiTokens;

    /**
     * Os atributos que podem ser atribuídos em massa.
     */
    protected $fillable = [
        'name',
        'email',
        'address',
        'access_code',
        'password',
        'lucky_number',
    ];

    /**
     * Os atributos que devem ser ocultados ao serializar o modelo.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Relacionamento opcional com o modelo AccessCode.
     */
    public function accessCode()
    {
        return $this->hasOne(AccessCode::class, 'email', 'email');
    }
}
