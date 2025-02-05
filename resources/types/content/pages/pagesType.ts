// interfaccia Autore
export interface Author {
    id: number;
    name: string;
}

export interface Section {
    type: keyof JSX.IntrinsicElements;
    url?: string;
    content?: string;
}

export interface Content {
    sections: Section[];
}

// Definisci l'interfaccia per una singola pagina
export interface Page {
    id: number;        // Identificatore unico della pagina
    title: string;     // Titolo della pagina
    content: string;
    status: 'draft' | 'published';
    author: Author; // Contenuto della pagina (può essere una stringa o JSON)
    author_id: number;
    meta_description: null | string;
    meta_keywords: null | string[];
    parent_id: null | number;
    slug: string;
}
// Definisci il tipo per l'array di pagine
export type PagesArray = Page[];