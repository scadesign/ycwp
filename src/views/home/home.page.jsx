import React from 'react';
import { Link } from 'react-router-dom';
import LargeHeader from '../../components/large-header/large-header.component';
import { HomePage, HomeContainer } from './home.styles';

import './home.styles.scss';

export const Home = () => {
  return (
    <HomePage>
      <LargeHeader />
      <HomeContainer>
        <li>
          <Link to="sign-up">Sign Up</Link>
        </li>
        <li>
          <Link to="sign-in">Create Seawatch</Link>
        </li>
        <li>
          <Link to="/view-sightings">View Sightings</Link>
        </li>
      </HomeContainer>
    </HomePage>
  );
};
