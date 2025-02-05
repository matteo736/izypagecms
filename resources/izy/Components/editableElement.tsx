import { ChangeEvent, useState } from 'react';
import { TooltipProvider, Tooltip, TooltipTrigger, TooltipContent } from './ui/tooltip';
import { Blocks, X } from 'lucide-react';
import { Command, CommandItem, CommandList, CommandGroup } from './ui/command';

const EditableElement = ({ type, content }: { type: keyof JSX.IntrinsicElements, content: string | undefined }) => {
    const [text, setText] = useState(content);
    const [border, setBorder] = useState('border-primary-foreground');

    // Funzione per aggiornare il contenuto
    const handleChange = (event: ChangeEvent<HTMLTextAreaElement>) => {
        setText(event.target.value);
    };

    // Restituisce un elemento dinamico basato su "type"
    const Element = type;

    // console.log(type);

    return (
        <TooltipProvider>
            <Tooltip>
                <TooltipTrigger asChild>
                    <Element 
                        className={`border ${border} p-2 m-1 rounded-sm shadow-sm`} 
                        onMouseEnter={() => setBorder('border-primary')} 
                        onMouseLeave={() => setBorder('border-primary-foreground')} >
                        {content}
                    </Element>
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
