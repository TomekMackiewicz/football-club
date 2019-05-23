export interface Post {
    id: number;
    title: string;
    slug: string;
    body: number;
    publishDate: string;
    modifyDate: string;
    categories: Array<Object>;
    images: Array<Object>;
}
