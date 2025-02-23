import {
    Popover,
    PopoverContent,
    PopoverTrigger,
} from "@/components/ui/popover";
import { Button } from "@/components/ui/button";
import { Page } from "@types/content/pages/pagesType";
import { Dispatch, FC } from "react";
import { ListOrdered } from "lucide-react";

const sortingTypes = [
    {name: "Title"},
    {name: "Creation date"},
    {name: "Last update"},
    {name: "Author"},
]

function PagesSortingMenuButton({pages, setPages}: 
    {pages: Page[], setPages: Dispatch<Page[]>}): JSX.Element {   
    return (
        <Popover>
            <PopoverTrigger>
                <Button><ListOrdered/></Button>
            </PopoverTrigger>
            <PopoverContent className="bg-accent-foreground border rounded-md p-2">
                <p className="text-sm text-muted">Sorting options:</p>
                <hr></hr>
            </PopoverContent>
        </Popover>

    )
}

export default PagesSortingMenuButton
