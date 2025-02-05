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
    page: Page; // Aggiungi eventuali proprietà personalizzate
}

export const PageCard: React.FC<CardPageProps> = ({ page }) => {
    return (
        <Card key={page.id} className="flex flex-col shadow-md">
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
                    <Link href={route('page', { id: page.id })}>Modifica</Link>
                </Button>
                <Button asChild variant={'destructive'}>
                    <Link href={route('page', { id: page.id })}>Elimina</Link>
                </Button>
            </CardContent>
            <CardFooter>
                <p className="text-muted-foreground">Data di Modifica</p>
            </CardFooter>
        </Card>
    );
}