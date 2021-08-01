import React from 'react';

import './input.styles.scss';

const Input  = ({ handleChange, label, title, ...otherProps }) => (
    <div className='group'>
         {
            label ? 
            ( <label htmlFor={label} className='form-input-label'>
                    {title}
                </label>
            ) : null 
        }
        <input className='form-input' onChange={handleChange} {...otherProps} />
       
    </div>
)

export default Input;