import React from 'react';

import { StatusTitle, StatusNumber } from './statusIndicator-styles';

const StatusIndicator = ({ numberItems, ...otherProps }) => {
  return (
    <div>
      <StatusTitle {...otherProps}>
        {' '}
        Number Submitted<StatusNumber>{numberItems}</StatusNumber>
      </StatusTitle>
    </div>
  );
};

export default StatusIndicator;
