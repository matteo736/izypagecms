import React, { useLayoutEffect } from 'react';
import { Page } from '@types/content/pages/pagesType';
import { PageProps } from '@types/index';
import { PageCard } from '@/Components/contentCards';
import MainIzpLayout from '@/Layouts/MainIzpLayout';
import { Button } from '@/components/ui/button';
import PagesSortingMenuButton from '@/Components/SortingMenu';
import { Plus } from 'lucide-react';
import { useForm } from '@inertiajs/react';
import { Link } from '@inertiajs/react';
import { router } from '@inertiajs/react';

interface AllPagesProps extends PageProps {
    pages: Page[]; // Aggiungi eventuali proprietà personalizzate
}

const AllPages: React.FC<AllPagesProps> = ({ pages }) => {
    // utilizziamo il contesto per gestire il reload
    // e per eliminare l'event listener
    // quando il componente viene smontato
    useLayoutEffect(() => {
        router.on('navigate', () => {
            // Ricarica il prop 'pages' (lista) quando si visita la pagina
            router.reload({ only: ['pages'] });
        });
    }
    , []);
    // utilizziamo useForm di Inertia per gestire la cancellazione
    // delle pagine. In questo caso, non abbiamo bisogno di un form
    // ma usiamo questo hook per sfruttare
    // la funzionalità di preservare lo scroll
    // e per gestire la cancellazione della card pagina
    const { delete: destroy } = useForm();

    // funzione che viene passata al card per la cancellazione della pagina
    // passando l'id della pagina
    // e gestendo la cancellazione
    // con un messaggio di conferma
    // e un messaggio di successo o errore
    // in base al risultato della cancellazione
    const handleDelete = (pageId: number) => {
        if (confirm('Sei sicuro di voler eliminare questa pagina?')) {
            destroy(route('page.delete', { id: pageId }), {
                preserveScroll: true,
            });
        }
    };
    return (
        // Layout principale della pagina
        <MainIzpLayout title='Tutte le Pagine'>
            <div className='w-11/12 min-h-[88vh] p-2 m-2 flex flex-col bg-background border rounded-md'>
                {/* Bottoni */}
                <div className='flex flex-row space-x-2 mx-4 mt-2'>
                    <Button asChild>
                        <Link href={route('page.new')}>
                            <Plus />
                        </Link>
                    </Button>
                    <PagesSortingMenuButton pages={pages}></PagesSortingMenuButton>
                </div>
                {/* Mappiamo le pagine e creiamo un card per ognuna di esse 
                  * e se non ci sono pagine mostriamo un messaggio
                  * di errore.
                  */}
                {pages.length > 0 ? (
                    <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 p-4">
                        {pages.map((page: Page) => (
                            <PageCard
                                key={page.id}
                                page={page}
                                destroy={() => handleDelete(page.id)}
                            />
                        ))}
                    </div>
                ) : (
                    <div className='m-auto text-primary underline'>
                        No pages Found.
                    </div>
                )}
            </div>
        </MainIzpLayout>

    );
};

export default AllPages;
