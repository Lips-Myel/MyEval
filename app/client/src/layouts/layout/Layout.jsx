import React from 'react'
import { Link, Outlet } from 'react-router'
import logoMyEval from '../../assets/myeval-logo.png'
import './Layout.css'

function Layout() {
  return (
    <>
    <header className='header'>
      <Link to="/etudiant-espace-perso" className='container_img_header rotating'>
        <img className='rotating' src={logoMyEval} alt="" />
      </Link>
        <nav className='container_button_header'>
          <Link to="/etudiant-espace-perso">Mon profil</Link>
          <Link to="/">Déconnexion</Link>
        </nav>
    </header>
    <Outlet />
    </>
  )
}

export default Layout