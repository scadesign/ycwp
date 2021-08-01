import React, { useEffect, useState } from 'react';

import './select.styles.scss';

const Select = ({task, handleChange, label, title, ...otherProps }) => {

  const [data, setData] = useState([]);
  
  useEffect(() => {
    fetch(`tasks/${task}`)
      .then((response) => response.json())
      .then((json) => setData(json.data.task))
      .catch((error) => console.error(error))
      
  }, [task]);

 

  return (
    <span>
    { label ? 
            ( <label htmlFor={label} className='select-label'>
                    {title}
                </label>
            ) : null 
    }
    <select className='select' onChange={handleChange} {...otherProps}>
      <option value="">Please Select</option>
      {data.map(tasks => (
            <option key={tasks.id} value={tasks.task}>{tasks.task}</option>
        ))}
    </select>
    </span>
   
  );
};

export default Select