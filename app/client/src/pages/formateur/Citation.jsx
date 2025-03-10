import React, { useState } from 'react';
import axios from 'axios';
import '../../utils/global-css.css'
import '../../utils/variables.css'
import './Citation.css'

function Citation() {
    return (
        <>
            <div className='citation'>
                <h1><strong>M</strong>y<strong>E</strong>val</h1>
                <h2>S'évaluer, visualiser, progresser.</h2>
            </div>
        </>
    );
}

export default Citation;
