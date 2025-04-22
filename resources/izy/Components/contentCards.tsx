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
import { Link, router } from "@inertiajs/react";
import { Skeleton } from "./ui/skeleton";
import { useForm } from "@inertiajs/react";
import { useCallback } from "react";
import { toast } from "sonner";
import { useMemo } from "react";


interface CardPageProps {
    page: Page; // Tipizzazione della pagina
    destroy: () => void; // Funzione per eliminare la pagina
}

//Componente per la visualizzazione di una card di una pagina
export const PageCard: React.FC<CardPageProps> = ({ page, destroy }) => {
    return (
        <Card className="flex flex-col shadow-md">
            <div className="relative w-full h-48">
                {!page.image && (
                    <Skeleton className="w-full h-full absolute top-0 left-0" />
                )}
                <img
                    src={page.image}
                    style={{ objectFit: 'cover' }}
                />
            </div>
            <CardHeader className="flex flex-col divide-y-2">
                <CardTitle>{page.title}</CardTitle>
                <CardDescription>
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
                <p className="text-muted-foreground">Data di Modifica</p>
            </CardFooter>
        </Card>
    );
}