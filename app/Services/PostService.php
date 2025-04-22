<?php

namespace App\Services;

use App\Models\Post;
use App\Models\Setting;

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
        return Post::published()->
            where('slug', $slug)
            ->firstOrFail();
    }

    public function getActiveTheme(): string
    {
        return Setting::where('key_name', 'active_theme')
            ->value('value') ?? 'izy-helloTheme';
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
            'content' => json_encode($data['layout'])
        ]);
    }

    /**
     * Aggiorna un post esistente.
     *
     * @param Post $post
     * @param array $data
     * @return Post
     */
    public function updatePost(Post $post, array $data): Post
    {
        if (isset($data['title'])) {
            $post->title = $data['title'];
        }

        if (isset($data['content'])) {
            $post->content = json_encode($data['content']);
        }

        $post->save();
        return $post;
    }

    /**
     * Restituisce un nuovo post vuoto.
     *
     * @return Post
     */
    public function getEmptyPost(): Post
    {
        return new Post([
            'title' => 'Modifica il Titolo',
            'content' => json_encode([
                'sections' => [
                    [
                        'id' => 1,
                        'type' => 'p',
                        'content' => 'Inizia a scrivere qui...'
                    ]
                ]
            ])
        ]);
    }
}