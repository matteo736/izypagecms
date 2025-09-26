<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Post;

class PostContentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function authenticated_user_with_admin_role_can_create_page()
    {
        $user = User::factory()->create();

        // assegna un ruolo già esistente nel DB
        $user->assignRole('admin');

        $postData = Post::factory()->make()->toArray([
            'author_id' => $user->id
        ]);

        $response = $this->actingAs($user)->post(route('page.store'), $postData);

        $response->assertStatus(302); // redirect dopo salvataggio
        $this->assertDatabaseHas('posts', [
            'author_id' => $user->id,
            'title' => $postData['title'],
        ]);
    }

    /** @test */
    public function user_without_required_role_cannot_create_page()
    {
        $user = User::factory()->create();

        // Non assegniamo ruoli
        $postData = Post::factory()->make([
            'author_id' => $user->id
        ])->toArray();

        $response = $this->actingAs($user)->post(route('page.store'), $postData);

        $response->assertStatus(302); // redirect dopo salvataggio
        $this->assertDatabaseMissing('posts', [
            'author_id' => $user->id,
            'title' => $postData['title'],
        ]);
        $this->assertTrue(true);
    }

    /** @test */
    public function admin_cannot_create_page_without_required_fields()
    {
        $user = User::factory()->create();
        // assegna un ruolo già esistente nel DB
        $user->assignRole('admin');

        $response = $this->actingAs($user)->post(route('page.store'), [
            'title'   => '', // titolo mancante
            'content' => '', // contenuto mancante
            'type'    => 2, // usa l'id tipo Pagina
        ]);

        $response->assertSessionHasErrors(['title', 'content']);
    }

    /** @test */
    public function guest_cannot_create_page()
    {
        $user = User::factory()->create(); // utente necessario

        $postData = Post::factory()->create(['post_type_id' => 2])->toArray();

        $response = $this->post(route('page.store'), $postData);

        $response->assertRedirect('izyAdmin/login'); // redirect a login per guest
    }
}
