import { AppSidebar } from '@/Components/sidebar-app';
import { SidebarProvider, SidebarTrigger, useSidebar } from '@/Components/ui/sidebar';
import { PropsWithChildren, ReactElement } from 'react';
import { SIDEBAR_WIDTH } from '@/Components/sidebar-dimension'
import { Head } from '@inertiajs/react';
import TopBar from './Widgets/TopBar';
import { Toaster } from 'sonner';
import { useEffect } from 'react';
import { usePage } from '@inertiajs/react';
import { PageProps } from '@types/index';
import { toast } from 'sonner';
import Cookies from 'js-cookie';
import { useLocalStorage } from 'usehooks-ts';

interface MainIzpLayoutProps {
    title: string;
}

//Layout principale della pagina
//permette di gestire il sidebar,
//il layout principale della pagina,
//il bridge layout,
//e il toaster
export default function MainIzpLayout({ children, title }: PropsWithChildren<MainIzpLayoutProps>) {
    const { flash } = usePage<PageProps>().props;
    // otteniamo il valore di apertura della sidebar da un cookie di shadcn
    // e in base a questo valore, decidiamo se aprire o meno la sidebar
    // tra i rernder
    const defaultOpen = Cookies.get("sidebar:state") === "true";
    // Effetto per mostrare i toast quando cambiano i flash messages
    useEffect(() => {
        if (flash.message) {
            toast.success(flash.message); // Mostra il messaggio di successo
        }
        if (flash.error) {
            toast.error(flash.error); // Mostra il messaggio di errore
        }
    }, [flash]);
    // stato del tema
    const [theme] = useLocalStorage<'light' | 'dark'>('theme', 'light');
    return (
        <SidebarProvider defaultOpen={defaultOpen} id='dashboard' className={theme === 'dark' ? 'dark' : ''} >
            <AppSidebar />
            <BridgeLayout>
                <Head title={title} />
                <h1 className='page-title mt-12'>{title}</h1>
                {children}
                <Toaster position="top-right" />
            </BridgeLayout>
        </SidebarProvider>
    );
}
//Layout di supporto per il sidebar
//permette di gestire la larghezza del layout
function BridgeLayout({ children }: PropsWithChildren): ReactElement {
    // Hook di shadcn per la gestione del sidebar
    const { open: openDashboard, openMobile, isMobile, setOpen } = useSidebar();
    // Gestisce la chiusura automatica della sidebar su mobile
    useEffect(() => {
        if (isMobile) {
            setOpen(false);
        }
    }, [isMobile]);
    return (
        <main className={openDashboard || openMobile ? 'transition-all duration-300' : 'w-full'} style={openDashboard || openMobile ? { width: `calc(100% - ${SIDEBAR_WIDTH})` } : {}}>
            <SidebarTrigger className='fixed z-50 text-primary mt-[6px]' />
            <div className="flex flex-col min-h-screen bg-background items-center pt-0">
                <TopBar className={'fixed z-40'} />
                {children}
            </div>
        </main>
    )
}
