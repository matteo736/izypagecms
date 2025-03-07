declare namespace App.Models {
    export type Post = {
        title: string;
        slug: string;
        meta_description: string;
        meta_keywords: string;
        content: any[];
        status: string;
        post_type_id: string;
        author_id: string;
        id: number;
        content: any[];
    };
    export type Post_Type = {
        name: string;
        slug: string;
        has_archive: boolean;
        public: boolean;
        labels: any[];
        supports_title: boolean;
        supports_content: boolean;
        id: number;
        has_archive: boolean;
        public: boolean;
        supports_title: boolean;
        supports_content: boolean;
        labels: any[];
    };
    export type Setting = {
        key_name: string;
        value: string;
        id: number;
    };
    export type User = {
        name: string;
        email: string;
        id: number;
        email_verified_at: string;
    };
}
