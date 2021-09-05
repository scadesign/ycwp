import React from 'react';

import './seawatch-review.styles.scss';

const SeawatchReview  = ({ station, name, date, ...otherProps }) => (
    <div className='review'>
        <p><span className="bold">Name:</span> {name} <span className="bold">Station:</span> {station} <span className="bold">Date:</span> {date}</p> 
    </div>
)

export default SeawatchReview;