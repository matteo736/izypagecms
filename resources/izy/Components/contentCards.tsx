import {
    Card,
    CardContent,
    CardDescription,
    CardFooter,
    CardHeader,
    CardTitle,
} from "@/components/ui/card"
import { Page } from "@types/content/pages/pagesType";
import { Button } from "./ui/button";
import { Link } from "@inertiajs/react";
import { Skeleton } from "./ui/skeleton";

interface CardPageProps {
    page: Page; // Tipizzazione della pagina
    destroy: () => void; // Funzione per eliminare la pagina
}

//Componente per la visualizzazione di una card di una pagina
export const PageCard: React.FC<CardPageProps> = ({ page, destroy }) => {
    return (
        <Card className="flex flex-col shadow-md h-[23rem]">
            <div className="relative w-full min-h-40">
                {!page.image && (
                    <Skeleton className="w-full h-full absolute top-0 left-0" />
                )}
                <img
                    src={page.image}
                    style={{ objectFit: 'cover' }}
                />
            </div>
            <CardHeader className="flex flex-col divide-y-2">
                <CardTitle className="truncate h-[1.7rem]">{page.title}</CardTitle>
                <CardDescription className="truncate">
                    <strong> Status:</strong> {page.status} |
                    <strong> Author:</strong> {page.author.name}
                </CardDescription>
            </CardHeader>
            <CardContent className="flex-1 space-x-2 flex items-center">
                <Button asChild>
                    <Link href={route('page.view', { id: page.id })}>Modifica</Link>
                </Button>
                <Button asChild variant={'destructive'} onClick={destroy} className="cursor-pointer">
                    <p>Elimina</p>
                </Button>
            </CardContent>
            <CardFooter>
                <p className="text-muted-foreground">Ultima Modifica: {page.updated_at.slice(0,10)}</p>
            </CardFooter>
        </Card>
    );
}