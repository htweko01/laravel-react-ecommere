import React from 'react'
import Navbar from './components/Navbar'
import { Outlet } from 'react-router'

function Layout() {
  return (
    <>
        <Navbar/>
        <div className="xl:w-5/6 mx-auto">
          <Outlet/>
        </div>
    </>
  )
}

export default Layout