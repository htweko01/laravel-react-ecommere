import { CircleChevronLeft, CircleChevronRight } from "lucide-react";
import React, { useState } from "react";
import type { Media } from "~/api/product";


export default function ProductImages({images}: {images?: Media[]}) {
  const [selectedImage, setSelectedImage] = useState(1);

  

  return (
    <div className="flex flex-col space-y-6 w-full">
      {/* Main displayed image */}
      <div
        className={`bg-gray-700 relative rounded-lg h-96 w-full flex items-center justify-center `}
      >
        {/* previous image button */}
        <button
          onClick={() => {
            setSelectedImage(selectedImage > 1 ? selectedImage - 1 : (images?.length || 1));
          }}
          className="absolute left-4 top-1/2 transform -translate-y-1/2 bg-transparent text-gray-600 rounded-full p-2 hover:bg-gray-300 transition-colors"><CircleChevronLeft size={40}/></button>
        {
            images?.map((img) => {
                if (img.order === selectedImage) {
                    return (
                        <img
                            key={img.uuid}
                            src={img.url}
                            alt={img.order.toString()}
                            className="w-full h-full object-cover rounded-lg"
                        />
                    );
                }
            } )
        }
        <button onClick={() => {
            setSelectedImage(selectedImage < (images?.length || 1) ? selectedImage+1 : 1);
          }}
          className="absolute right-4 top-1/2 transform -translate-y-1/2 bg-transparent text-gray-600 rounded-full p-2 hover:bg-gray-300 transition-colors">
          <CircleChevronRight size={40}/>
        </button>
      </div>

      {/* Thumbnails */}
      <div className="flex gap-6 w-full overflow-x-auto scrollbar-hide">
        {
            images?.map((image) => {
                return <button onClick={() => setSelectedImage(image.order)} className={`bg-gray-700 rounded min-w-20 min-h-20 flex items-center justify-center cursor-pointer hover:ring-2 ring-blue-600 ${selectedImage === image.order ? "ring-2 ring-blue-500" : ""}`} key={image.uuid}>
                    <img
                        key={image.uuid}
                        src={image.url}
                        alt={`Thumbnail for ${image.order}`}
                        className="w-20 h-20 rounded bg-gray-800 object-cover"
                    />
                </button>
            })
        }
        
      </div>
    </div>
  );
}
