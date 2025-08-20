const BASE_URL:string = import.meta.env.VITE_API_BASE_URL;
const API_KEY:string = import.meta.env.VITE_API_KEY;

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
    name: string;
    price: number;
    description?: string;
    image?: string;
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
    media?: Media[];
}

export function products() {
    return fetch(`${BASE_URL}/products`).then((response) => response.json())
}

export function getProduct(id: number) {
    return fetch(`${BASE_URL}/products/${id}`).then((response) => response.json())
}