import React from 'react';

import { Review, Bold } from './environment-review.styles';

const EnvironmentReview = ({ start, end, seaState, swellHeight, windDirection, visibility, notes, ...otherProps }) => (
  <Review>
    <div>
      <div>
        <Bold>Start time:</Bold> {start} <span className="bold">End time:</span> {end}
      </div>
      <div>
        <Bold>Sea state:</Bold> {seaState} <span className="bold">Swell height:</span> {swellHeight}
      </div>
      <div>
        <Bold>Wind direction:</Bold> {windDirection} <span className="bold">Visibility:</span> {visibility}
      </div>
      <div>
        <Bold>Notes:</Bold> {notes}{' '}
      </div>
    </div>
  </Review>
);

export default EnvironmentReview;
