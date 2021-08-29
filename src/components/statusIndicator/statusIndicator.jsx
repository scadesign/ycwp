import React from "react";

import './statusIndicator.scss';



const StatusIndicator = ({numberItems, ...otherProps }) => {

  
  return (
    <div>
        <h3 className="status-title" {...otherProps}> Number Submitted<span className='status-number'>{numberItems}</span></h3>
    </div>
   
  );
};

export default StatusIndicator