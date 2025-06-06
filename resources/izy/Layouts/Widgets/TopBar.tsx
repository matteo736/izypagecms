import { Link } from '@inertiajs/react';
import { ReactElement, useState, useEffect } from 'react';
import { Sun, Moon } from 'lucide-react';
import { useLocalStorage } from 'usehooks-ts';

function TopBar({ className }: { className?: string | null  }): ReactElement {
    // Stato per gestire il tema scuro
    const [theme, setTheme] = useLocalStorage<'light' | 'dark'>('theme', 'light')
    // Handler per il toggle del tema
    const handleToggleTheme = () => {
        const dashboard = document.getElementById("dashboard");
        if (dashboard) {
            dashboard.classList.toggle("dark");
        }
        setTheme(prev => prev === 'dark' ? 'light' : 'dark');
    };
    return (
        <div className={`p-2 bg-secondary w-full flex justify-around ${className} w-full`}>
            <Link href="/" className='text-primary'>
                Logo
            </Link>
            {
                theme === 'dark' ? (
                    <button onClick={handleToggleTheme} className="text-primary">
                        <Sun className="h-6 w-6" />
                    </button>
                ) : (
                    <button onClick={handleToggleTheme} className="text-primary">
                        <Moon className="h-6 w-6" />
                    </button>
                )
            }
        </div>
    )
}

export default TopBar