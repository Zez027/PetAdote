<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // Caso use Sanctum
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password','cpf', 'pais', 'estado', 'cidade', 'facebook', 'instagram', 'contato', 'role', 'profile_photo'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function pets()
    {
        return $this->hasMany(Pet::class);
    }

    // Pets que o usuário favoritou
    public function favorites()
    {
        // Define que a relação usa a tabela 'favorites' criada no banco
        return $this->belongsToMany(Pet::class, 'favorites')->withTimestamps();
    }
}
