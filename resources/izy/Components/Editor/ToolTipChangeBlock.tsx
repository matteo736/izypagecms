import { ReactElement } from 'react'
import { TooltipProvider, Tooltip, TooltipTrigger, TooltipContent } from '../ui/tooltip';
import { htmlTagItems } from '../Blocks/htmltags';

function ToolTipChangeBlock({ children }: { children: ReactElement }) {
    return (
        <TooltipProvider>
            <Tooltip>
                <TooltipTrigger asChild>
                    {children}
                </TooltipTrigger>
                {/* finestrella di scelta del blocco da cambiare e eliminazione */}
                <TooltipContent className='p-0'>
                    {htmlTagItems.map((item) => {
                        return (
                            <div className='p-4 bg-primary'>
                                {item.label}
                            </div>
                        )
                    })}
                </TooltipContent>
            </Tooltip>
        </TooltipProvider >
    )
}

export default ToolTipChangeBlock
