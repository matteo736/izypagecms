<?php

namespace App\Http\Controllers\Content\Posts;

use App\Http\Controllers\Controller;
use App\Http\Requests\Posts\PostRequest;
use App\Services\PostService;
use App\Models\Post;
use App\Models\Post_Type;
use Inertia\Inertia;

use function Illuminate\Log\log;

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
     * Mostra la pagina di un post specifico.
     *
     * @param int $id
     * @return \Inertia\Response
     */
    public function single(int $id)
    {
        return Inertia::render('Content/SinglePageEditor', [
            'pageProp' => Post::findOrFail($id),
        ]);
    }

    /**
     * Mostra la pagina per creare un nuovo post.
     *
     * @return \Inertia\Response
     */
    public function newSinglePage()
    {
        return Inertia::render('Content/SinglePageEditor', [
            'pageProp' => $this->postService->getEmptyPost(),
            'postTypeId' => Post_Type::where('slug', 'page')->first()->id
        ]);
    }

    /**
     * Mostra la pagina per creare un nuovo post.
     *
     * @param int $id
     * @return \Inertia\Response
     */
    public function store(PostRequest $request)
    {
        $this->postService->createPost($request->validated());
        return redirect()->back()->with('message', 'Pagina creata con successo!');
    }

    /**
     * Mostra la pagina per modificare un post esistente.
     *
     * @param Post $post
     * @return \Inertia\Response
     */
    public function update(PostRequest $request, int $id)
    {
        $post = Post::findOrFail($id);
        $this->postService->updatePost($post, $request->validated());
        return redirect()->back()->with('message', 'Pagina aggiornata con successo!');
    }
    /**
     * Mostra la pagina per eliminare un post esistente.
     *
     * @param Post $post
     * @return \Inertia\Response
     */
    public function destroy(int $id)
    {
        $post = Post::findOrFail($id);
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
