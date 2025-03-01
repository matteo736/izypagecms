// DbConfigForm.tsx
import React from 'react';
import { useForm } from '@inertiajs/react';
import SetupLayout from '@/Layouts/SetupLayout';
import { Button } from '@/components/ui/button';
import DbSetupDescription from './SetupDb/InitialDbDescription';
import { FormEventHandler } from 'react';
import { route } from 'ziggy-js';
import { Form, Field } from '@types/setup/FormTypes'
import LabelInput from '../../Components/labelInput';
import { usePage } from '@inertiajs/react';

const DbConfigForm: React.FC<{ dbConfig: Form; title: string; status: string | null }> = ({ dbConfig, title, status }) => {
  const { data, setData, post, processing, errors, transform, reset } = useForm<Form>({
    ...dbConfig,
  });

  const { flash } = usePage().props

  transform((data) => ({
    ...data,
    initialized: true,
  }));

  const handleSubmit: FormEventHandler = (e) => {
    e.preventDefault();
    post(route('setup.database.response'),{ // Esegue una richiesta POST verso la rotta "login".
      onFinish: () => reset('password'), // Dopo che la richiesta è completata, ripristina il campo 'password'.
    });
  };

  const fields: Field[] = [
    { label: 'Database Type', name: 'dbType', type: 'select', options: ['mysql', 'sqlite', 'pgsql', 'sqlsrv', 'mariadb'] },
    { label: 'Host', name: 'host', type: 'text' },
    { label: 'Port', name: 'port', type: 'number' },
    { label: 'Database Name', name: 'dbName', type: 'text' },
    { label: 'Username', name: 'username', type: 'text' },
    { label: 'Password', name: 'password', type: 'password' },
  ];

  const LayoutClassName = 'flex-col-reverse lg:flex-row flex justify-around items-center divide-y lg:divide-y-0 lg:divide-x mt-4'

  return (
    <>
      <SetupLayout errors={flash.error} message={flash.message} className={LayoutClassName} title={title} >
        <form onSubmit={handleSubmit} className="lg:w-fit w-full">
          {fields.map((field): React.ReactNode => {
            return <LabelInput<Form> name={field.name} label={field.label} type={field.type} setData={setData} data={data} options={field.options} errors={errors} />
          })}
          <Button type="submit" disabled={processing} variant="default" className="my-2 w-full lg:max-w-96">
            Save Configuration
          </Button>
        </form>
        {!dbConfig.initialized ? <DbSetupDescription /> : null}
      </SetupLayout>
    </>
  );
};


export default DbConfigForm;
