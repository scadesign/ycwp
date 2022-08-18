import React from 'react';

import { Review, Bold } from '../environment-review/environment-review.styles';

const SightingReview = ({ firstSeen, lastSeen, species, confidence, groupSize, calves, juveniles, bearing, distance, behaviour, associatedBirds, ...otherProps }) => (
  <Review>
    <div>
      <div>
        <span className="bold">First seen:</span> {firstSeen} <span className="bold">Last Seen:</span> {lastSeen}
      </div>
      <div>
        <Bold>Species:</Bold> {species} <span className="bold">Confidence:</span> {confidence} <span className="bold">Group size:</span> {groupSize}
      </div>
      <div>
        <Bold>No. calves:</Bold> {calves} <span className="bold">No. juveniles:</span> {juveniles}
      </div>
      <div>
        <Bold>Bearing:</Bold> {bearing} <span className="bold">Distance:</span> {distance}km <span className="bold">Behaviour:</span> {behaviour}
      </div>
      <div>
        <Bold>Notes:</Bold> {associatedBirds}{' '}
      </div>
    </div>
  </Review>
);

export default SightingReview;
