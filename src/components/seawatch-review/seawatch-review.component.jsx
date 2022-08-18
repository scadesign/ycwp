import React from 'react';

import { Review, Bold } from '../environment-review/environment-review.styles';

const SeawatchReview = ({ station, name, date, ...otherProps }) => (
  <Review>
    <p>
      <Bold>Name:</Bold> {name} <Bold>Station:</Bold> {station} <Bold>Date:</Bold> {date}
    </p>
  </Review>
);

export default SeawatchReview;
