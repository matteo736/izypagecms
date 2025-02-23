import React from 'react';
import { Page } from '@types/content/pages/pagesType';
import { PageProps } from '@types/index';
import { PageCard } from '@/Components/contentCards';
import MainIzpLayout from '@/Layouts/MainIzpLayout';
import { Button } from '@/components/ui/button';
import PagesSortingMenuButton from '@/Components/SortingMenu';
import { Plus } from 'lucide-react';
import { useState } from 'react';

interface AllPagesProps extends PageProps {
    pages: Page[]; // Aggiungi eventuali proprietà personalizzate
}

const AllPages: React.FC<AllPagesProps> = ({ pages }) => {
    const [statePages, setPages] = useState<Page[]>(pages);
    return (
        <MainIzpLayout title='Tutte le Pagine'>
            <div className='w-11/12 min-h-[88vh] p-2 m-2 flex flex-col bg-background border rounded-md'>
                <div className='flex flex-row space-x-2 mx-4 mt-2'>
                    <Button><Plus /></Button>
                    <PagesSortingMenuButton pages={statePages} setPages={setPages}></PagesSortingMenuButton>
                </div>
                {pages.length > 0 ?
                    <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 p-4">
                        {statePages.map((page: Page) => (
                            <PageCard page={page} />
                        ))}
                    </div>
                    :
                    <div className='m-auto text-primary underline'>
                        No pages Found.
                    </div>
                }

            </div>
        </MainIzpLayout>
    );
};

export default AllPages;
