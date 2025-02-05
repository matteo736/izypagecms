<?php

namespace App\Http\Controllers\Content\Pages;

use Illuminate\Http\Request;
use App\Models\Page;
use App\Http\Controllers\Controller;
use Inertia\Inertia;

class PagesController extends Controller
{
    public function show($slug)
    {
        $page = Page::where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();
    
        $activeTheme = Setting::where('key_name', 'active_theme')->value('value') ?? 'izy-helloTheme';
    
        $themePath = 'Themes/' . $activeTheme . '/pageModels/';
    
        return match ($page->type) {
            'post' => $this->renderContent($page, $themePath . 'SinglePageModel'),
            'archive' => $this->renderContent($page, $themePath . 'ArchivePageModel'),
            'page' => $this->renderContent($page, $themePath . 'StaticPageModel'),
            default => $this->renderContent($page, $themePath . 'ErrorPageModel'),
        };
    }
    
    private function renderContent($page, $component)
    {
        return Inertia::render($component, ['page' => $page]);
    }
    
    /**
     * Elenca tutte le pagine.
     */
    public function index()
    {
        // Recupera tutte le pagine e carica il nome dell'autore
        $pages = Page::with('author:id,name')->get();

        return Inertia::render('Content/AllPages', [
            'pages' => $pages,
        ]);
    }

    /**
     * Mostra una singola pagina.
     */
    public function single($id)
    {
        $page = Page::findOrFail($id); // Recupera una pagina per ID
        return Inertia::render('Content/SinglePageEditor', [
            'page' => $page,
        ]);
    }

    /**
     * Salva una nuova pagina.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'layout' => 'required|array', // Deve essere un array (il JSON della pagina)
        ]);

        $page = Page::create([
            'title' => $data['title'],
            'content' => json_encode($data['layout']), // Salva il layout come JSON
        ]);

        return response()->json(['message' => 'Pagina creata con successo!', 'page' => $page]);
    }

    /**
     * Aggiorna una pagina esistente.
     */
    public function update(Request $request, $id)
    {
        $page = Page::findOrFail($id);

        $data = $request->validate([
            'title' => 'sometimes|string|max:255',
            'layout' => 'sometimes|array', // Deve essere un array se fornito
        ]);

        if (isset($data['title'])) {
            $page->title = $data['title'];
        }

        if (isset($data['layout'])) {
            $page->content = json_encode($data['layout']);
        }

        $page->save();

        return response()->json(['message' => 'Pagina aggiornata con successo!', 'page' => $page]);
    }

    /**
     * Elimina una pagina.
     */
    public function destroy($id)
    {
        $page = Page::findOrFail($id);
        $page->delete();

        return response()->json(['message' => 'Pagina eliminata con successo!']);
    }
}
