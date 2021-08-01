import React from 'react';

import './textarea.styles.scss';

const TextArea = ({handleChange, label, title, ...otherProps }) => {

  
  return (
    <span>
    { label ? 
            ( <label htmlFor={label}>
                    {title}
                </label>
            ) : null 
    }
    <textarea className='textarea' onChange={handleChange} {...otherProps}>
      
    </textarea>
    </span>
   
  );
};

export default TextArea