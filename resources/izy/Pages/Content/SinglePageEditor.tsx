import React from 'react';
import { useForm } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import { Page, Section } from '@types/content/pages/pagesType';
import { PageProps } from '@types/index';
import EditableElement from '@/Components/editableElement';
import MainIzpLayout from '@/Layouts/MainIzpLayout';
import DraggableEditor from '@/Components/Editor/DraggableEditor';

interface SPEditorProps extends PageProps {
    pageProp: Page; // Aggiungi eventuali proprietà personalizzate
}

const SPEditor: React.FC<SPEditorProps> = ({ pageProp }) => {
    const isNewPage = !pageProp?.id; // Controlla se la pagina è nuova
    // hookl di inertia per la gestione del form
    const { data: page, setData, post, put, processing, errors } = useForm({
        title: pageProp?.title || '',
        content: pageProp?.content ? JSON.parse(pageProp.content) : { sections: [] },
    });
    // Wrapper per setData che gestisce specificamente il content
    const handleContentChange = (newContent: { [key: string]: string }[]) => {
        setData('content', {
            sections: newContent // Aggiorna solo le sezioni
        });
    };
    // Funzione per gestire il submit del form
    // In questo caso, il form viene inviato a una route di tipo POST
    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        console.log('dati inviati', page);
        // se la pagina è nuova, invia una richiesta POST
        // altrimenti invia una richiesta PUT
        // alla route di aggiornamento della pagina
        if (isNewPage) {
            post(route('page.store')); //POST
        } else {
            put(route('page.update', pageProp.id)); //PUT
            console.log('dati inviati', page); // debug
        }
    };

    return (
        <MainIzpLayout title={page.title}>
            <form onSubmit={handleSubmit} className="m-8 mt-4 p-8 border shadow-lg bg-background text-primary rounded-sm flex flex-col w-full lg:max-w-3xl">
                {/* Componente che mostra l'editor */}
                <DraggableEditor items={page.content.sections} setItems={handleContentChange}></DraggableEditor>
                {/* Bottone per il salvataggio delle modifiche */}
                <Button type="submit" disabled={processing} variant="default" className="my-2 w-full lg:max-w-96 self-center">
                    {isNewPage ? 'Crea Pagina' : 'Salva Modifiche'}
                </Button>
            </form>
        </MainIzpLayout>
    );
};

export default SPEditor;
