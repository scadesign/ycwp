import React from 'react';

import './sightings.review.styles.scss';

const SightingReview  = ({ firstSeen, lastSeen, species, confidence, groupSize, calves, juveniles, bearing, distance, behaviour, associatedBirds, ...otherProps }) => (
    <div className='review'>
        <div><span className="bold">First seen:</span> {firstSeen} <span className="bold">Last Seen:</span> {lastSeen}</div> 
        <div><span className="bold">Species:</span> {species} <span className="bold">Confidence:</span> {confidence} <span className="bold">Group size:</span> {groupSize}</div>
        <div><span className="bold">No. calves:</span> {calves} <span className="bold">No. juveniles:</span> {juveniles}</div>
        <div><span className="bold">Bearing:</span> {bearing} <span className="bold">Distance:</span> {distance}km <span className="bold">Behaviour:</span> {behaviour}</div>
        <div><span className="bold">Notes:</span> {associatedBirds} </div>
    </div>
)

export default SightingReview;