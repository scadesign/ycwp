import React from 'react';
import { Link } from 'react-router-dom';
import LargeHeader from '../../components/large-header/large-header.component';

import './home.styles.scss';

export const Home = () => {
  return (
    <div className="page homepage">
      <LargeHeader />
      <ul className="home">
        <li>
          <Link to="seawatch">Create Seawatch</Link>
        </li>
      </ul>
    </div>
  );
};
