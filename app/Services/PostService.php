<?php

namespace App\Services;

use App\Models\Post;
use App\Models\Setting;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class PostService
{
    /**
     * Restituisce un post pubblicato in base allo slug.
     *
     * @param string $slug
     * @return Post
     */
    public function findPublishedBySlug(string $slug): Post
    {
        return Post::published()->where('slug', $slug)
            ->firstOrFail();
    }

    /**
     * Restituisce il tema attivo
     *
     * @return string
     */
    public function getActiveTheme(): string
    {
        return Setting::where('key_name', 'active_theme')
            ->value('value') ?? 'izy-helloTheme';
    }


    /**
     * Converte un titolo in uno slug URL-friendly.
     * 
     * @param string $title
     * @return string
     */
    private function generateSlug(string $title): string
    {
        // Converti in minuscolo
        $slug = strtolower($title);

        // Sostituisci spazi con trattini
        $slug = str_replace(' ', '-', $slug);

        // Rimuovi caratteri speciali e accenti
        $slug = preg_replace('/[^a-z0-9\-]/', '', $slug);

        // Rimuovi trattini multipli
        $slug = preg_replace('/-+/', '-', $slug);

        // Rimuovi trattini iniziali e finali
        return trim($slug, '-');
    }

    /**
     * Crea un nuovo post.
     *
     * @param array $data
     * @return Post
     */
    public function createPost(array $data): Post
    {
        return Post::create([
            'title' => $data['title'],
            'content' => json_encode($data['content']),
            'slug' => $this->generateSlug($data['title']),
            'status' => $data['status'],
            'author_id' => Auth::id(), // Aggiunge l'ID dell'utente autenticato
            'post_type_id' => $data['post_type_id'],
        ]);
    }

    /**
     * Aggiorna un post esistente preservando lo slug.
     *
     * @param Post $post
     * @param array $data
     * @return void
     */
    public function updatePost(Post $post, array $data): void
    {
        try {
            $post->update($data);
        } catch (\Exception $e) {
            Log::error('Errore aggiornamento post', [
                'error' => $e->getMessage(),
                'post_id' => $post->id,
            ]);
            throw $e;
        }
    }

    // In PostService.php
    public function createDraftPost(array $data): Post
    {
        return Post::create([
            'title' => $data['title'],
            'slug' => $this->generateSlug($data['title']),
            'content' => json_encode($data['content']),
            'status' => 'draft',
            'author_id' => $data['author_id'],
            'post_type_id' => $data['post_type_id'],
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

}
