<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Support\Facades\Log;

class PostPolicy
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

    /**
     * Determina se l'utente può creare un post.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->can('create-content');
    }

    /**
     * Determina se l'utente può modificare un post.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function edit(User $user): bool
    {
        return $user->can('edit-content');
    }

    /**
     * Determina se l'utente può modificare un post.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function delete(User $user): bool
    {
        return $user->can('delete-content');
    }
}
