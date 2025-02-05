import { AppSidebar } from '@/Components/sidebar-app';
import { SidebarProvider, SidebarTrigger, useSidebar } from '@/Components/ui/sidebar';
import { PropsWithChildren, ReactElement } from 'react';
import { SIDEBAR_WIDTH } from '@/Components/sidebar-dimension'
import { Head } from '@inertiajs/react';
import TopBar from './Widgets/TopBar';

interface MainIzpLayoutProps {
    title: string;
}

export default function MainIzpLayout({ children, title }: PropsWithChildren<MainIzpLayoutProps>) {
    return (
        <SidebarProvider defaultOpen={false} >
            <AppSidebar />
            <BridgeLayout>
                <Head title={title} />
                <h1 className='page-title mt-10'>{title}</h1>
                {children}
            </BridgeLayout>
        </SidebarProvider>
    );
}
function BridgeLayout({ children }: PropsWithChildren): ReactElement {
    const { open: openDashboard, openMobile, isMobile, setOpen } = useSidebar();
    if (isMobile) {
        setOpen(false);
    }
    return (
        <main className={openDashboard || openMobile ? 'transition-all duration-300' : 'w-full'} style={openDashboard || openMobile ? { width: `calc(100% - ${SIDEBAR_WIDTH})` } : {}}>
            <SidebarTrigger className='fixed z-50'/>
            <div className="flex flex-col min-h-screen bg-background items-center pt-0">
                <TopBar className={'fixed z-40'}/>
                {children}
            </div>
        </main>
    )
}
