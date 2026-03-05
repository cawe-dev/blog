import type { User } from '../auth';
import type { ICategory } from './category';
import type { ITag } from './tag';
import type { IReference } from './reference';

export interface IPost {
    id: number;
    title: string;
    slug: string;
    content: IContentPost;
    content_html: string | object;
    sub_topics?: string[];
    excerpt: string;
    pinned_at?: string;
    has_spoiler?: boolean;
    type: 'personal' | 'professeonal' | 'both';
    type_label: string;
    author: User;
    category: ICategory[];
    tags: ITag[];
    references?: IReference[];
    published_at?: string;
    created_at: string;
    updated_at: string;
    [key: string]: unknown;
}

export interface IPostGroup {
    [key: string]: IPost[];
}
