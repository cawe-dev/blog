import type { User } from '../auth';
import type { ICategory } from './category';
import type { ITag } from './tag';
import type { IReference } from './reference';

export interface IPost {
    id: number;
    title: string;
    slug: string;
    content: string;
    excerpt?: string;
    featured_image?: string;
    type: 'personal' | 'professeonal' | 'both';
    type_label: string;
    author: User;
    category?: ICategory[];
    tags?: ITag[];
    references?: IReference[];
    published_at: string | null;
    created_at: string;
    updated_at: string;
    [key: string]: unknown;
}

export interface IPostGroup {
    [key: string]: IPost[];
}
