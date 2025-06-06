import { FormEvent, useState } from 'react';
import { TooltipProvider, Tooltip, TooltipTrigger, TooltipContent } from './ui/tooltip';
import { Blocks, X } from 'lucide-react';
import { Command, CommandItem, CommandList, CommandGroup } from './ui/command';
import { useAppDispatch } from '@/Redux/hooks/hooks';
import { updateBlock } from '@/Redux/editorSlice';
import { EditorContent } from '@tiptap/react';
import { useEditor } from '@tiptap/react';
import StarterKit from '@tiptap/starter-kit';

const TEXT_TYPES = ['p', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'];

const EditableElement = ({ type, content, id }:
    { type: keyof JSX.IntrinsicElements, content: string | undefined, id: number }) => {
    // Componente per l'elemento modificabile
    const Element = type;
    // Stato per il bordo dell'elemento
    const [border, setBorder] = useState('border-primary-foreground');
    const dispatch = useAppDispatch();// Hook per dispatchare azioni Redux
    // Inizializza TipTap solo per elementi testuali
    const editor = TEXT_TYPES.includes(type) ? useEditor({
        extensions: [StarterKit],
        content: content,
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
                            rounded-sm shadow-sm w-full
                            bg-background cursor-move
                        `}>
                            <EditorContent editor={editor}
                                className={border}
                                onMouseEnter={() => setBorder('border-primary')}
                                onMouseLeave={() => setBorder('border-primary-foreground')} />
                        </div>
                    ) : (
                        <Element
                            className={`
                                border ${border}
                                p-2 rounded-sm shadow-sm w-full
                                bg-background cursor-move
                            `}
                            onMouseEnter={() => setBorder('border-primary')}
                            onMouseLeave={() => setBorder('border-primary-foreground')}
                        >
                            {content}
                        </Element>
                    )}
                </TooltipTrigger>
                <TooltipContent className='p-0'>
                    <Command>
                        <CommandList>
                            <CommandGroup heading={'Seleziona:'}>
                                <CommandItem>
                                    Elimina <X />
                                </CommandItem>
                                <CommandItem>
                                    Cambia blocco <Blocks />
                                </CommandItem>
                            </CommandGroup>
                        </CommandList>
                    </Command>
                </TooltipContent>
            </Tooltip>
        </TooltipProvider>
    );
};

export default EditableElement;
