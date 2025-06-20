<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PetPhoto extends Model
{
    use HasFactory;

    protected $fillable = ['foto', 'pet_id'];

    public function pet()
    {
        return $this->belongsTo(Pet::class);
    }
}

