import React from "react";
import { Link } from "react-router-dom";
import LargeHeader from "../../components/large-header/large-header.component";

import './home.styles.scss';

export const Home = () => {
  return (
    <div className="page homepage">
      <LargeHeader />
      <ul className ="home">
        <li><Link to='sign-up'>
          Sign Up
        </Link></li>
         <li><Link to='sign-in'>
         Create Seawatch
        </Link></li>
         <li><Link to='/view-sightings'>
          View Sightings
        </Link></li>
      </ul>
    </div>
  );
};
