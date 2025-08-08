import { Search, ShoppingCart, User } from "lucide-react";
import { useState } from "react"

function Navbar() {
    const [toggleSearch, setToggleSearch] = useState<boolean>(false);
  return (
    <>
            <div className="bg-white shadow-md p-4 dark:bg-gray-800 text-black dark:text-white">
                <div className="container mx-auto md:px-8 flex justify-between items-center ">
                    <h1 className="text-2xl md:text-3xl font-bold text-red-600 dark:text-red-400">
                        Ecommerce
                    </h1>
                    <div className="flex items-center">
                        <div className="relative hidden md:block">
                            <input
                                type="text"
                                className="w-full rounded-full border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 px-5 py-3 pr-20 text-base shadow-md transition-shadow duration-200 hover:shadow-lg focus:border-gray-300 focus:outline-none"
                                placeholder="Search products..."
                            />
                            <div className="absolute right-0 top-0 mr-4 mt-3 flex items-center">
                                <button
                                    type="submit"
                                    className="text-dark dark:text-white hover:text-gray-700 dark:hover:text-gray-200"
                                >
                                    <Search size={20} />
                                </button>
                            </div>
                        </div>
                        
                        <button className="block md:hidden ml-4 p-2 bg-transparent text-dark dark:text-white rounded-full dark:hover:bg-gray-600 hover:bg-gray-200 transition-colors" onClick={() => setToggleSearch(!toggleSearch)}>
                            <Search size={25} />
                        </button>
                        <button  className="ml-4 p-2 bg-transparent text-dark dark:text-white rounded-full dark:hover:bg-gray-600 hover:bg-gray-200 transition-colors">
                            <ShoppingCart size={25} />
                        </button>
                <button className="ml-4 p-2 bg-transparent text-dark dark:text-white rounded-full dark:hover:bg-gray-600 hover:bg-gray-200 transition-colors">
                            <User size={25} />
                        </button>
                    </div>
                </div>
                {/* mobile search bar */}
                <div className={`relative block md:hidden mt-4 ${toggleSearch ? "block" : "hidden"} `}>
                            <input
                                type="text"
                                className="w-full rounded-full border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 px-5 py-3 pr-20 text-base shadow-md transition-shadow duration-200 hover:shadow-lg focus:border-gray-300 focus:outline-none"
                                placeholder="Search products..."
                            />
                            <div className="absolute right-0 top-0 mr-4 mt-3 flex items-center">
                                <button
                                    type="submit"
                                    className="text-dark dark:text-white hover:text-gray-700 dark:hover:text-gray-200"
                                >
                                    <Search size={20} />
                                </button>
                            </div>
                        </div>
            </div>
        </>
  )
}

export default Navbar