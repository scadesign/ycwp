import React from 'react';

import './header.styles.scss';

const Header = () => {
    return(
        <div className="header">
            <img className ="header_logo" src="logo512.png" alt="Cetacean Watch" />
            <h2 className="header_title">The Yorkshire Cetacean Watch Project</h2>      
        </div>
        
       
    )
}

export default Header;