import React, { useState } from 'react';
import axios from 'axios';
import '../../utils/global-css.css'
import '../../utils/variables.css'
import './Avis.css'

function Avis() {
    return (
        <>
            <div className='avis'>
                <h4>Question</h4>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit,
                    sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                <h4>7/10</h4>
                <input type='text' />
            </div>
        </>
    );
}

export default Avis;
