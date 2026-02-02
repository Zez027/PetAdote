<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pet extends Model
{
    use HasFactory;
    use SoftDeletes;
    

    protected $fillable = [
        'nome', 'idade', 'genero' ,'porte', 'raca', 'tipo', 'pais', 'estado', 'cidade', 'descricao', 'status', 'user_id',
        'vacinado', 'castrado', 'vermifugado'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function photos()
    {
        return $this->hasMany(PetPhoto::class);
    }

    public function adoptionRequests()
    {
        return $this->hasMany(AdoptionRequest::class);
    }

    // Relação: Um pet pode ser favoritado por vários usuários
    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites');
    }
}
