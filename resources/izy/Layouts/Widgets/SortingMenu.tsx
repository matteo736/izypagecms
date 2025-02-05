import {
    Popover,
    PopoverContent,
    PopoverTrigger,
} from "@/components/ui/popover";
import { Button } from "@/components/ui/button";


function SortingMenuButton({itemsToSort}: {itemsToSort: string}) {
    return (
        <Popover>
            <PopoverTrigger>
                <Button>Ordina {itemsToSort}</Button>
            </PopoverTrigger>
            <PopoverContent>Place content for the popover here.</PopoverContent>
        </Popover>

    )
}

export default SortingMenuButton
