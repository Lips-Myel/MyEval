import { StrictMode } from 'react';
import { createRoot } from 'react-dom/client';
import Router from './router/Router.jsx'; 
import './utils/global-css.css'
import './utils/variables.css'

createRoot(document.getElementById('root')).render(
  <StrictMode>
    <Router />
  </StrictMode>
);
