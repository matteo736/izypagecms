import { Model } from "@types/global";

export interface Section {
    id: number;
    type: keyof JSX.IntrinsicElements;
    content: string;
    settings?: Record<string, any>;
}

export interface Author {
    id: number;
    name: string;
    // Altri campi relativi all'autore
}

export interface Content {
    sections: Section[];
}

export enum Status {
    Draft = "draft",
    Published = "published",
    Trashed = "trashed"
}

// Definisci un tipo più generico per i post
export interface Post extends Model {
    id: number;
    image: string;
    title: string;
    content?: { sections?: Section[] };
    status: Status;
    author: Author;
    author_id: number;
    meta_description: null | string;
    meta_keywords: null | string[];
    slug: string;
}

// Definisci il tipo per l'array di post
export type PostArray = Post[];

// Definisci l'interfaccia per una singola pagina
export interface Page extends Post {
    parent_id: null | number;
}

// Definisci il tipo per l'array di pagine
export type PageArray = Page[];

