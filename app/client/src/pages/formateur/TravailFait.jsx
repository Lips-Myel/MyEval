import React, { useState } from 'react';
import axios from 'axios';
import '../../utils/global-css.css'
import '../../utils/variables.css'
import './TravailFait.css'

function TravailFait() {
    return (
        <>
            <div onClick={() => navigate ("/formateuraviss")} className='travail-fait'>
                <div className='nom'>
                    <h4>Prénom</h4>
                    <h4>Nom</h4>
                    <h4>Date rendu</h4>
                </div>
                <div className='right'>
                    <div className='moyenne'></div>
                    <button className='download'></button>
                </div>
            </div>
        </>
    );
}

export default TravailFait; 
