import type { Product } from "~/api/product";

function ProductCard(props: Product) {
  return (
    <>
      <div className="group relative">
        {/* {props.media.map((media) => {
          if (media.order === 1) {
            return (
              
            );
          }
        })} */}
        <img
                src={props.image}
                alt={props.name}
                className="aspect-square w-full rounded-md bg-gray-200 object-cover group-hover:opacity-75 lg:aspect-auto lg:h-80"
              />
        <div className="mt-4 flex justify-between">
          <div>
            <h3 className="text-sm text-gray-700 dark:text-gray-300">
              <span aria-hidden="true" className="absolute inset-0"></span>
              {props.name}
            </h3>
            <p className="text-sm font-medium text-gray-900 dark:text-gray-200">
            ${props.price}
          </p>
            {/* <p className="mt-1 text-sm text-gray-500">Black</p> */}
          </div>
          {/* TODO: add to cart button */}
        </div>
      </div>
    </>
  );
}

export default ProductCard;
