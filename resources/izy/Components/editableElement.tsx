import { FormEvent, useState } from 'react';
import { TooltipProvider, Tooltip, TooltipTrigger, TooltipContent } from './ui/tooltip';
import { Blocks, X } from 'lucide-react';
import { Command, CommandItem, CommandList, CommandGroup } from './ui/command';
import { useAppDispatch } from '@/Redux/hooks/hooks';
import { deleteBlock, updateBlock } from '@/Redux/editorSlice';
import { EditorContent } from '@tiptap/react';
import { useEditor } from '@tiptap/react';
import StarterKit from '@tiptap/starter-kit';
import { Button } from './ui/button';
import ToolTipChangeBlock from './Editor/ToolTipChangeBlock';

const TEXT_TYPES = ['p', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'];

const EditableElement = ({ type, content, id }:
    { type: keyof JSX.IntrinsicElements, content: string | undefined, id: number }) => {
    // Componente per l'elemento modificabile
    // const Element = type; -- STATO INZIALE DELL'APPLICAZIONE PRIMA DI MODIFCHE SU AEREO
    const [Element, setElement] = useState(type);
    // Stato per il bordo dell'elemento
    const noborder = 'border-transparent';
    const [border, setBorder] = useState(noborder);
    const dispatch = useAppDispatch();// Hook per dispatchare azioni Redux
    // Modifica dello stato del componente del tipo e gestione di redux
    const changeType = () => {

    }
    // Inizializza TipTap solo per elementi testuali
    const editor = TEXT_TYPES.includes(type) ? useEditor({
        extensions: [StarterKit],
        content: content,
        editorProps: {
            attributes: {
                class: 'rounded-sm p-4 focus:outline-none focus:ring-1 focus:ring-primary rounded',
            },
        },
        onUpdate: ({ editor }) => {
            dispatch(updateBlock({
                id,
                content: editor.getText()
            }));
        },
    }) : null;
    return (
        <TooltipProvider>
            <Tooltip>
                <TooltipTrigger asChild>
                    {TEXT_TYPES.includes(type) ? (
                        <div className={`
                            border ${border}
                            rounded-sm w-full
                            bg-background cursor-move
                        `}>
                            <EditorContent editor={editor}
                                className={border}
                                onMouseEnter={() => setBorder('border-primary')}
                                onMouseLeave={() => setBorder(noborder)} />
                        </div>
                    ) : (
                        <Element
                            className={`
                                border ${border}
                                p-2 
                                rounded-sm w-full
                                bg-background cursor-move
                            `}
                            onMouseEnter={() => setBorder('border-primary')}
                            onMouseLeave={() => setBorder(noborder)}
                        >
                            {content}
                        </Element>
                    )}
                </TooltipTrigger>
                {/* finestrella di scelta del blocco da cambiare e eliminazione */}
                <TooltipContent className='p-0'>
                    <Command>
                        <CommandList>
                            <CommandGroup heading={'Seleziona:'}>
                                <CommandItem asChild>
                                    <Button className={"w-full"} variant={"destructive"} onClick={() => dispatch(deleteBlock(id))}>
                                        Elimina <X />
                                    </Button>
                                </CommandItem>
                                {/* trigger della finestrella di scelta del blocco da cambiare */}
                                <ToolTipChangeBlock>
                                    <CommandItem>
                                        Cambia blocco <Blocks />
                                    </CommandItem>
                                </ToolTipChangeBlock>
                            </CommandGroup>
                        </CommandList>
                    </Command>
                </TooltipContent>
            </Tooltip>
        </TooltipProvider>
    );
};

export default EditableElement;
