<?php

namespace App\Http\Controllers\Content\Posts;

use App\Http\Controllers\Controller;
use App\Http\Requests\Posts\CreatePostRequest;
use Illuminate\Http\Request;
use App\Http\Requests\Posts\UpdatePostRequest;
use App\Services\PostService;
use App\Models\Post;
use App\Models\Post_Type;
use Inertia\Inertia;
use Illuminate\Http\RedirectResponse;


class PostsController extends Controller
{
    public function __construct(
        private PostService $postService
    ) {}

    /**
     * Mostra la pagina di un post pubblicato.
     *
     * @param string $slug
     * @return \Inertia\Response
     */
    public function show(string $slug)
    {
        $post = $this->postService->findPublishedBySlug($slug);
        $activeTheme = $this->postService->getActiveTheme();
        $themePath = 'Themes/' . $activeTheme . '/pageModels/';

        return $this->renderContent($post, $this->getTemplateByType($post->type, $themePath));
    }

    /**
     * Mostra tutti i post.
     *
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        return Inertia::render('Content/AllPages', [
            'pages' => Post::with('author:id,name')->get(),
        ]);
    }

    /**
     * Crea una bozza iniziale di post (contenuto editor baseline) e reindirizza la UI
     * all’editor della pagina appena generata. Richiede che l’utente corrente sia
     * autorizzato alla creazione di Post.
     *
     * @param CreatePostRequest $request Richiesta HTTP contenente l’utente autenticato.
     * @return RedirectResponse Reindirizzamento verso la route dell’editor con l’ID della bozza.
     */
    public function createAndRedirect(Request $request): RedirectResponse
    {
        $user = $request->user();

        if (!$user->can('create', Post::class)) {
            return redirect()->back()->with('error', 'Non autorizzato a creare post');
        }

        $nextId = (Post::max('id') ?? 0) + 1;

        $post = $this->postService->createDraftPost([
            'title' => 'Nuovo Post #' . $nextId,
            'content' => [
                'sections' => [
                    ['id' => 0, 'type' => 'p', 'content' => 'scrivi qui...'],
                ],
            ],
            'author_id' => $user->id,
            'post_type_id' => Post_Type::where('slug', 'page')->value('id'),
        ]);

        return redirect()->route('page.view', ['id' => $post->id]);
    }

    /**
     * Gestisce la creazione/modifica di un post.
     * Se l'ID esiste, carica il post esistente.
     * Se l'ID non esiste, crea un post temporaneo nel DB e reindirizza con il nuovo ID.
     *
     * @param Request $request
     * @param int|null $id
     * @return \Inertia\Response|\Illuminate\Http\RedirectResponse
     */
    public function single(Request $request, int $id)
    {
        $user = $request->user();
        $post = Post::findOrFail($id);

        // Controlla permessi per modificare
        if (!$user->can('edit', Post::class)) {
            return redirect()->back()->with('error', 'Non autorizzato a modificare questo post');
        }

        return Inertia::render('Content/SinglePageEditor', [
            'pageProp' => $post,
            'postTypeId' => $post->post_type_id
        ]);
    }

    /**
     * Funzione per modificare un post esistente.
     *
     * @param Post $post
     * @param UpdatePostRequest $request
     * @return \Inertia\Response
     */
    public function update(UpdatePostRequest $request, int $id)
    {
        $user = $request->user();
        $validatedData = $request->validated();

        // Usa il permesso definito in PostPolicy
        if (!$user->can('edit', Post::class)) {
            return redirect()->back()->with('message', 'Utente non autorizzato a modificare Post');
        }

        $post = Post::findOrFail($id);
        $this->postService->updatePost($post, $validatedData);
        return redirect()->back()->with('message', $validatedData['title'] . ' aggiornata con successo!');
    }
    /**
     * Elimina un post esistente.
     *
     * @param Post $post
     * @param Request $request
     * @return \Inertia\Response
     */
    public function destroy(Request $request, int $id)
    {
        $user = $request->user();
        $post = Post::findOrFail($id);
        if (!$user->can('delete', Post::class)) {
            return redirect()->back()->with('message', 'Utente non autorizzato a eliminare Post');
        }
        $post->delete();
        return redirect()->route('pages.all')->with('message', 'Pagina eliminata con successo!');
    }

    /**
     * Associa il tipo di post al template corretto.
     *
     * @param string $type
     * @param string $themePath
     * @return string
     */
    private function getTemplateByType(string $type, string $themePath): string
    {
        return $themePath . match ($type) {
            'post' => 'SinglePageModel',
            'archive' => 'ArchivePageModel',
            'page' => 'StaticPageModel',
            default => 'ErrorPageModel',
        };
    }

    /**
     * Renderizza il contenuto della pagina.
     *
     * @param Post $page
     * @param string $component
     * @return \Inertia\Response
     */
    private function renderContent($page, $component)
    {
        return Inertia::render($component, ['page' => $page]);
    }
}
