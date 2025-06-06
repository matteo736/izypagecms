import React, { useEffect, useLayoutEffect } from 'react';
import { useForm } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import { Page, Section } from '@types/content/pages/pagesType';
import { PageProps } from '@types/index';
import MainIzpLayout from '@/Layouts/MainIzpLayout';
import DraggableEditor from '@/Components/Editor/DraggableEditor';
import { useAppSelector, useAppDispatch } from '@/Redux/hooks/hooks';
import { setBlocks } from '@/Redux/editorSlice';
import { EditorBlock } from '@types/editor/editorTypes';
import { useEditor } from '@tiptap/react';
import StarterKit from '@tiptap/starter-kit';
import { EditorContent } from '@tiptap/react';
import { TITLE } from '../../../css/tw-style-map/style';

interface SPEditorProps extends PageProps {
    pageProp: Page; // Aggiungi eventuali proprietà personalizzate
}

const SPEditor: React.FC<SPEditorProps> = ({ pageProp, postTypeId }) => {
    const dispatch = useAppDispatch(); // Ottieni il dispatcher Redux
    const blocks = useAppSelector((state) => state.editor.blocks); // Ottieni gli elementi dallo stato Redux
    const isNewPage = !pageProp?.id; // Controlla se la pagina è nuova
    const initialContent = pageProp?.content ? JSON.parse(pageProp.content).sections : { sections: [] }; // Inizializza il contenuto della pagina
    console.log(pageProp);
    // hookl di inertia per la gestione del form
    const { data: page, setData, post, put, processing, errors } = useForm({
        title: pageProp?.title || '',
        layout: {
            sections: initialContent // Inizializza le sezioni
        },
        postTypeId : postTypeId ? postTypeId : null,
    });
    // Inizializza l'editor di testo con TipTap per il titolo
    const editor = useEditor({
        extensions: [StarterKit],
        content: page.title,
        onUpdate: ({ editor }) => {
            setData('title', editor.getText()); // Aggiorna il titolo della pagina
        },
    })
    // Effetto per impostare gli elementi iniziali nello stato Redux
    useEffect(() => {
        dispatch(setBlocks(initialContent));
    }, []);
    // Effetto per monitorare i cambiamenti negli elementi Redux
    // necessario per tracciare le modifiche fatte sul singolo blocco con il dispatcher
    // e con handleChange
    // per aggiornare il contenuto del form
    useEffect(() => {
        setData('layout', { sections: blocks }); // Aggiorna il contenuto del form con gli elementi
    }, [blocks]);
    // Funzione per gestire il cambiamento degli elementi
    const handleChange = (items: EditorBlock[]) => {
        dispatch(setBlocks(items)); // Aggiorna lo stato Redux con i nuovi elementi
    }
    // Funzione per gestire il submit del form
    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        // se la pagina è nuova, invia una richiesta POST
        // altrimenti invia una richiesta PUT
        // alla route di aggiornamento della pagina
        if (isNewPage) {
            post(route('page.store')); //POST
        } else {
            put(route('page.update', pageProp.id)); //PUT
        }
    };

    return (
        <MainIzpLayout title={'IzyEdit'}>
            <form onSubmit={handleSubmit} className="m-8 mt-4 p-8 shadow-lg bg-background text-primary rounded-sm flex flex-col w-full lg:max-w-6xl">
                {/* Titolo della pagina */}
                <EditorContent editor={editor}
                    className={TITLE.lg + ' '}
                />
                {/* Componente che mostra l'editor */}
                <DraggableEditor items={page.layout.sections} setItems={handleChange} />
                {/* Bottone per il salvataggio delle modifiche */}
                <Button type="submit" disabled={processing} variant="default" className="my-2 w-full lg:max-w-96 self-center">
                    {isNewPage ? 'Crea Pagina' : 'Salva Modifiche'}
                </Button>
            </form>
        </MainIzpLayout>
    );
};

export default SPEditor;
