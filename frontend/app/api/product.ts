const BASE_URL = import.meta.env.VITE_API_BASE_URL;

export interface ProductVariant {
    id: number;
    product_id: number;
    price: number;
    stock: number;
    sku: string;
}

export interface Media {
    uuid: number;
    url: string;
    order: number;
}

export interface Product {
    id: number;
    title: string;
    price: number;
    description: string;
    category?: {
        id: number;
        name: string;
        slug: string;
    }
    department?: {
        id: number;
        name: string;
        slug: string;
    }
    variants?: ProductVariant[];
    media: Media[];
}

export function products() {
    return fetch(`${BASE_URL}/products`).then((response) => response.json())
}