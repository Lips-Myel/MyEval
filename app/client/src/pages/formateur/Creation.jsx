import React, { useState } from 'react';
import axios from 'axios';
import '../../utils/global-css.css'
import '../../utils/variables.css'
import './Creation.css'

function Creation() {
    return (
        <>
            <div className='creation'>
                <label htmlFor="">JavaScript</label>
                <h4>Question</h4>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit,
                    sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                <input type="checkbox" />
            </div>
        </>
    );
}

export default Creation;
