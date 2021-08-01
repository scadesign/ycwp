import React from 'react';

import './large-header.styles.scss';

const LargeHeader = () => {
    return(
        <div className="large-header">
            <img className ="large-header_logo" src="logo512.png" alt="Cetacean Watch" />
            <h2 className="header_title">The Yorkshire Cetacean Watch Project</h2>      
        </div>
        
       
    )
}

export default LargeHeader;