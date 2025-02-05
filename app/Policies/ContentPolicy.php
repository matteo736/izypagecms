<?php

namespace App\Policies;

use App\Models\User;

abstract class ContentPolicy
{
    /**
     * Verifica se l'utente ha accesso globale.
     *
     * @param User $user
     * @return bool
     */
    protected function hasGlobalAccess(User $user): bool
    {
        return $user->hasRole('admin'); // Controlla se l'utente è un admin.
    }

    /**
     * Verifica se l'utente è il creatore del contenuto.
     *
     * @param User $user
     * @param mixed $content
     * @return bool
     */
    protected function isContentOwner(User $user, $content): bool
    {
        return $user->id === $content->user_id; // Assumi che il contenuto abbia un campo 'created_by'.
    }

    /**
     * Regola generale per l'accesso ai contenuti.
     *
     * @param User $user
     * @param mixed $content
     * @return bool
     */
    public function canAccess(User $user, $content): bool
    {
        return $this->hasGlobalAccess($user) || $this->isContentOwner($user, $content);
    }
}
