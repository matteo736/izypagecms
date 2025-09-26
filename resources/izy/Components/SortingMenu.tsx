import React from "react";
import {
    Popover,
    PopoverContent,
    PopoverTrigger,
} from "@/components/ui/popover";
import { Button } from "@/components/ui/button";
import { Page } from "@types/content/pages/pagesType";
import { ListOrdered } from "lucide-react";
import { useState } from "react";

const sortingTypes = [
    { name: "Title", key: "title" },
    { name: "Creation date", key: "creationDate" },
    { name: "Last update", key: "lastUpdate" },
    { name: "Author", key: "author" },
    { name: "Status", key: "status" },
];

interface PagesSortingMenuButtonProps {
    pages: Page[];
    setPages: (pages: Page[]) => void;
}

// possibili stati della status di un post
const statusOrder = ["published", "draft", "trashed"];

export default function PagesSortingMenuButton({ pages, setPages }: PagesSortingMenuButtonProps): JSX.Element {
    // Stato per tasto attivo e direzione
    const [activeKey, setActiveKey] = useState<string | null>(null);
    const [ascending, setAscending] = useState<boolean>(true);

    // oggetto per le callback delle funzioni di ordinamento
    const sortFunctions: Record<string, (a: Page, b: Page) => number> = {
        title: (a, b) => a.title.localeCompare(b.title), // funzione per ordinare in base all'ordine alfabetico del titolo
        creationDate: (a, b) => new Date(a.created_at).getTime() - new Date(b.created_at).getTime(), // funzione per ordinare dal meno recente al piu recente
        lastUpdate: (a, b) => new Date(a.updated_at).getTime() - new Date(b.updated_at).getTime(), // funzione da quello modificato meno recentemente a quello piu recentemente
        author: (a, b) => {
            const authorA = a.author?.name || "";
            const authorB = b.author?.name || "";
            return authorA.localeCompare(authorB);
        }, // funzione per ordinare in ordine alfabetico gli autori
        status: (a, b) => {
            const idxA = statusOrder.indexOf(a.status || "draft");
            const idxB = statusOrder.indexOf(b.status || "draft");
            return idxA - idxB;
        }, // funzione per ordinare in ordine pubblicato -> bozza -> cestinato
    };

    // funzione ausiliaria che funge da switch per la selezione della funzione di sorting specifica
    const handleSort = (key: string) => {
        // Se l'utente clicca di nuovo sulla stessa opzione, inverti direzione
        const isSameKey = key === activeKey;
        const newAscending = isSameKey ? !ascending : true;

        // selezione della specifica funzione di ordinamento in base alla richiesta utente tra le possibili scelte
        const sorter = sortFunctions[key];
        if (!sorter) return;

        const sorted = [...pages].sort((a, b) => {
            const result = sorter(a, b);
            return newAscending ? result : -result;
        });

        // settiamo tutti gli stati alla fine del processo di ordinamento e creazione del nuovo array ordinato
        setActiveKey(key);
        setAscending(newAscending);
        setPages(sorted);
    };

    return (
        <Popover>
            <PopoverTrigger>
                <Button aria-label="Sort pages">
                    <ListOrdered />
                </Button>
            </PopoverTrigger>
            <PopoverContent className="bg-accent-foreground rounded-md p-2">
                <p className="text-sm text-muted mb-2">Sorting options:</p>
                <div className="flex flex-col space-y-1">
                    {sortingTypes.map((opt) => (
                        <Button
                            key={opt.key}
                            variant="outline"
                            size="sm"
                            onClick={() => handleSort(opt.key)}
                        >
                            {opt.name}
                            {opt.key === activeKey && (
                                <span className="ml-1 text-md">
                                    {ascending ? "↑" : "↓"}
                                </span>
                            )}
                        </Button>
                    ))}
                </div>
            </PopoverContent>
        </Popover>
    );
}

