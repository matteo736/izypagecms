import React, { useEffect, useMemo, useCallback } from 'react';
// Inertia
import { useForm } from '@inertiajs/react';
// UI Components
import { Button } from '@/components/ui/button';
import MainIzpLayout from '@/Layouts/MainIzpLayout';
import DraggableEditor from '@/Components/Editor/DraggableEditor';
import { PageSettings } from '@/Components/Editor/Widget/PageSettings';
import { SidebarListBlocks } from '@/Components/Editor/sidebar-blocks';
// State (Redux)
import { useAppSelector, useAppDispatch } from '@/Redux/hooks/hooks';
import { setBlocks } from '@/Redux/editorSlice';
// TipTap
import { useEditor, EditorContent as TitleEditor } from '@tiptap/react';
import StarterKit from '@tiptap/starter-kit';
import Heading from '@tiptap/extension-heading';
// Types
import { Page, Section, Status } from '@types/content/pages/pagesType';
import { PageProps } from '@types/index';
// Styles
import { TITLE } from '../../../css/tw-style-map/style';

interface SPEditorProps extends PageProps {
    pageProp: Page; // Aggiungi eventuali proprietà personalizzate
}

const SPEditor: React.FC<SPEditorProps> = ({ pageProp, postTypeId }) => {
    const dispatch = useAppDispatch();
    const blocks = useAppSelector(state => state.editor.blocks);

    // --- Derivazione contenuto iniziale (robusta) ---
    const initialSections: Section[] = useMemo(() => {
        if (!pageProp?.content) return [];
        // pageProp.content può essere già oggetto (cast JSON) oppure stringa JSON
        const raw = (pageProp as any).content;
        try {
            const parsed = typeof raw === 'string' ? JSON.parse(raw) : raw;
            if (parsed && Array.isArray(parsed.sections)) return parsed.sections;
        } catch (e) {
            console.warn('[SPEditor] Impossibile parsare content', e);
        }
        return [];
    }, [pageProp]);

    // --- Form di Inertia ---
    const { data: formData, setData, put, processing } = useForm({
        title: pageProp?.title ?? '',
        content: { sections: initialSections },
        post_type_id: postTypeId ?? null,
        status: pageProp?.status ?? 'draft',
    });

    // --- TipTap per il titolo ---
    const titleEditor = useEditor({
        extensions: [
            StarterKit.configure({ heading: false }),
            Heading.configure({ levels: [1] }),
        ],
        content: `<h1>${formData.title}</h1>`,
        editorProps: {
            attributes: {
                class: TITLE.lg + ' rounded-lg p-2 focus:outline-none focus:ring-1 focus:ring-primary text-3xl font-bold',
            },
        },
        onUpdate: ({ editor }) => setData('title', editor.getText()),
    });

    // Inizializza i blocchi solo al primo mount se vuoti
    useEffect(() => {
        if (!blocks.length && initialSections.length) {
            dispatch(setBlocks(initialSections));
        }
    }, [dispatch, blocks.length, initialSections]);

    // Sincronizza i blocchi Redux nel form Inertia
    useEffect(() => {
        setData('content', { sections: blocks });
    }, [blocks, setData]);

    // Handlers memoizzati
    const handleBlocksChange = useCallback((items: Section[]) => {
        dispatch(setBlocks(items));
    }, [dispatch]);

    const handleStatusChange = useCallback((newStatus: Status) => {
        setData('status', newStatus);
    }, [setData]);

    const handleSubmit = useCallback((e: React.FormEvent) => {
        e.preventDefault();
        put(route('page.update', pageProp.id));
    }, [put, pageProp.id]);

    return (
        <MainIzpLayout title='IzyEdit'>
            <div className='flex w-full gap-4'>
                <form
                    onSubmit={handleSubmit}
                    className="m-8 mt-0 p-8 border border-border shadow-sm bg-background text-foreground rounded-md flex flex-col w-full lg:max-w-6xl"
                >
                    <div className='mb-6 w-full lg:max-w-6xl bg-accent/40 backdrop-blur rounded-md py-4 px-6 flex justify-between items-center border border-accent/30'>
                        <PageSettings status={formData.status as Status} setStatus={handleStatusChange} />
                        <Button
                            type="submit"
                            disabled={processing}
                            variant="default"
                            className='ml-4'
                        >
                            {processing ? 'Salvataggio…' : 'Salva Modifiche'}
                        </Button>
                    </div>

                    <div className='mb-6'>
                        <TitleEditor editor={titleEditor} />
                    </div>

                    <DraggableEditor items={formData.content.sections} setItems={handleBlocksChange} />
                </form>
                <aside className='pr-4 pt-8'>
                    <SidebarListBlocks />
                </aside>
            </div>
        </MainIzpLayout>
    );
};

export default SPEditor;
