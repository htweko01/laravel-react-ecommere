import { useQuery } from "@tanstack/react-query"
import { getProduct, type Product } from "~/api/product"
import ProductImages from "~/components/ProductImages"

function Product({params}: {params: {productId: number}}) {
    
const {data, error, isLoading} = useQuery<Product>({
    queryKey: ['product', params.productId], // Replace 1 with the actual product ID you want to fetch
    queryFn: ({queryKey}) => getProduct(params.productId),
})
console
  return (
    <div className="grid grid-cols-1 md:grid-cols-2 gap-8 p-6">
      <ProductImages images={data?.media}/>
    </div>
  )
}

export default Product