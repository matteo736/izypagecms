import React from 'react';
import { Page } from '@types/content/pages/pagesType';
import { PageProps } from '@types/index';
import { PageCard } from '@/Components/contentCards';
import MainIzpLayout from '@/Layouts/MainIzpLayout';
import { Button } from '@/components/ui/button';
import SortingMenuButton from '@/Layouts/Widgets/SortingMenu';

interface AllPagesProps extends PageProps {
    pages: Page[]; // Aggiungi eventuali proprietà personalizzate
}

const AllPages: React.FC<AllPagesProps> = ({ pages }) => {
    return (
        <MainIzpLayout title='Tutte le Pagine'>
            <div className='w-11/12 min-h-[88vh] p-2 m-2 flex flex-col bg-background border rounded-md'>
                <div className='flex flex-row space-x-2 mx-4 mt-2'>
                    <Button>Aggiungi una Nuova Pagina</Button>
                    <SortingMenuButton itemsToSort={'Pagine'}></SortingMenuButton>
                </div>
                {pages.length > 0 ?
                    <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 p-4">
                        {pages.map((page: Page) => (
                            <PageCard page={page} />
                        ))}
                    </div>
                    :
                    <div className='m-auto text-primary underline'>
                        No Pages Found.
                    </div>
                }

            </div>
        </MainIzpLayout>
    );
};

export default AllPages;
