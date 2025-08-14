import { products, type Product } from "~/api/product";
import type { Route } from "./+types/home";
import { useQuery } from "@tanstack/react-query";
import ProductCard from "~/components/ProductCard";
import SkeletonProductCard from "~/components/SkeletonProductCard";
export function meta({}: Route.MetaArgs) {
  return [
    { title: "Ecommmerce" },
    { name: "description", content: "Welcome to React Router!" },
  ];
}


export default function Home() {
  const {data, isLoading, error} = useQuery({
    queryKey: ['products'],
    queryFn: products,
  })
  if (isLoading) return (
    <div className="mt-6 grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-4 xl:gap-x-8">
      {
        Array.from({ length: 8 }).map((_, index) => (
          <SkeletonProductCard key={index} />
        ))     
      }
      
    </div>
  );
  return <>
    <div className="mt-6 grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-4 xl:gap-x-8">
      {
        data.map((product: Product) => (

          <ProductCard key={product.id} {...product}/>
        ))
        
      }
      
      {
        error && <h1>Error loading products</h1>
      }
    </div>
  </>;
}
