import { ReactNode } from 'react';
import TopBar from './Widgets/TopBar';
import { Head } from '@inertiajs/react';

type SetupLayoutProps = {
  children: React.ReactNode; // Permette elementi singoli o multipli
  message: string | null | undefined;
  errors: string | null | undefined;
  title: string;
  className: string;
};

function SetupLayout({ children, errors, message, title, className }: SetupLayoutProps): ReactNode {
  return (
    <main className='min-h-screen min-w-full flex flex-col justify-center items-center bg-background'>
      <div className='bg-secondary p-12 border shadow-xl rounded-md mx-12'>
        <TopBar/>
        <h1 className="page-title text-center mb-2">{title}</h1>
        <div className={className}>
          <Head title={title} />
          {children}
        </div>
        {message && <div className="text-green-500 mt-4 w-full text-center">{message}</div>}
        {errors && <div className="text-destructive mt-4 w-full text-center">{errors}</div>}
      </div>
    </main>
  )
}

export default SetupLayout