import React from 'react';

import './environment-review.styles.scss';

const EnvironmentReview = ({ start, end, seaState, swellHeight, glare, vessels, visibility, notes, ...otherProps }) => (
  <div className="review ">
    <div>
      <div>
        <span className="bold">Start time:</span> {start} <span className="bold">End time:</span> {end}
      </div>
      <div>
        <span className="bold">Sea state:</span> {seaState} <span className="bold">Swell height:</span> {swellHeight}
      </div>
      <div>
        <span className="bold">Glare:</span> {glare} <span className="bold">Vessels:</span> {vessels} <span className="bold">Visibility:</span> {visibility}
      </div>
      <div>
        <span className="bold">Notes:</span> {notes}{' '}
      </div>
    </div>
  </div>
);

export default EnvironmentReview;
