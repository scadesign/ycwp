import React from 'react';
import { Link } from "react-router-dom";

import './menu.styles.scss';

const MenuItem = () => {
    return(
        <div className="navigation">
            <ul className="navigation">
            <Link to="/"><li>Home</li></Link>
            <Link to="add-environment"><li>Environment</li></Link>
            <Link to="add-sighting"><li>Sightings</li></Link>
            <Link to="review"><li>Review</li></Link>
            </ul>
        </div>
        
       
    )
}

export default MenuItem;