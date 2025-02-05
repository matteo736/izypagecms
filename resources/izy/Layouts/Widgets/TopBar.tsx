import { Link } from '@inertiajs/react';
import React, { ReactElement } from 'react';

function TopBar({ className }: { className?: string | null  }): ReactElement {
    return (
        <div className={`p-2 bg-secondary w-full flex justify-center ${className}`}>
            <Link href="/">
                Logo
            </Link>
        </div>
    )
}

export default TopBar