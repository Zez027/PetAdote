<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdoptionRequestStatusLog extends Model
{
    use HasFactory;

    protected $fillable = ['adoption_request_id', 'user_id', 'status', 'observacao'];

    public function adoptionRequest()
    {
        return $this->belongsTo(AdoptionRequest::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}