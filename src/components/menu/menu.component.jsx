import React from 'react';
import { Link } from 'react-router-dom';

import { Navigation } from './menu.styles';

const MenuItem = () => {
  return (
    <Navigation>
      <ul>
        <Link to="/">
          <li>Home</li>
        </Link>
        <Link to="add-environment">
          <li>Environment</li>
        </Link>
        <Link to="add-sighting">
          <li>Sightings</li>
        </Link>
        <Link to="review">
          <li>Review</li>
        </Link>
      </ul>
    </Navigation>
  );
};

export default MenuItem;
