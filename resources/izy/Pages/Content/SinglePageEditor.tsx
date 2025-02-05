import React from 'react';
import { useForm } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import { Page, Section } from '@types/content/pages/pagesType';
import { PageProps } from '@types/index';
import EditableElement from '@/Components/editableElement';
import MainIzpLayout from '@/Layouts/MainIzpLayout';

interface SPEditorProps extends PageProps {
    page: Page; // Aggiungi eventuali proprietà personalizzate
}

const SPEditor: React.FC<SPEditorProps> = ({ page }) => {
    const { data, setData, post, processing, errors } = useForm({
        title: page.title,
        content: JSON.parse(page.content),
    });
    const handleChange = (e: React.ChangeEvent<HTMLTextAreaElement>) => {
        setData('content', e.target.value); // Aggiorna lo stato con il valore della textarea
    };
    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        post(route('pages.store'));
    };
    return (
        <MainIzpLayout title={data.title}>
            <form onSubmit={handleSubmit} className="m-8 mt-4 p-8 border shadow-lg bg-background text-primary rounded-sm flex flex-col">
                {data.content.sections.map((section: Section) => {
                    return <EditableElement type={section.type} content={section.content} />
                })}
                <Button type="submit" disabled={processing} variant="default" className="my-2 w-full lg:max-w-96 self-center">
                    Save Page
                </Button>
            </form>
        </MainIzpLayout>
    );
};

export default SPEditor;
