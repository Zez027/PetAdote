<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdoptionRequest extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'pet_id', 'status', 'motivo_rejeicao'];

    public function pet() { return $this->belongsTo(Pet::class); }
    public function user() { return $this->belongsTo(User::class); }

    public function statusLogs()
    {
        return $this->hasMany(AdoptionRequestStatusLog::class)->orderBy('created_at', 'desc');
    }

    protected static function booted()
    {
        // Ao criar um novo pedido, registra o status 'pendente' no log
        static::created(function ($adoptionRequest) {
            AdoptionRequestStatusLog::create([
                'adoption_request_id' => $adoptionRequest->id,
                'user_id' => auth()->id() ?? $adoptionRequest->user_id,
                'status' => $adoptionRequest->status ?? 'pendente',
                'observacao' => 'Pedido de adoção iniciado.'
            ]);
        });

        // Ao atualizar um pedido, verifica se o status mudou para gerar o log
        static::updated(function ($adoptionRequest) {
            if ($adoptionRequest->wasChanged('status')) {
                AdoptionRequestStatusLog::create([
                    'adoption_request_id' => $adoptionRequest->id,
                    'user_id' => auth()->id(),
                    'status' => $adoptionRequest->status,
                    'observacao' => $adoptionRequest->motivo_rejeicao ?? 'Status atualizado.'
                ]);
            }
        });
    }
}
