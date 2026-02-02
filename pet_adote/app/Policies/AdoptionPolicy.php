<?php

namespace App\Policies;

use App\Models\AdoptionRequest;
use App\Models\User;

class AdoptionPolicy
{
    /**
     * Determina se o usuário pode aprovar ou rejeitar a solicitação.
     * (Apenas o dono do pet vinculado à solicitação pode fazer isso)
     */
    public function update(User $user, AdoptionRequest $adoptionRequest): bool
    {
        return $user->id === $adoptionRequest->pet->user_id;
    }

    /**
     * Determina se o usuário pode visualizar os detalhes da solicitação.
     */
    public function view(User $user, AdoptionRequest $adoptionRequest): bool
    {
        // O dono do pet ou o próprio interessado (adotante) podem ver
        return $user->id === $adoptionRequest->pet->user_id || $user->id === $adoptionRequest->user_id;
    }
}