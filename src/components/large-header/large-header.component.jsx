import React from 'react';

import { HeaderContainer, HeaderLogo, HeaderH2 } from './large-header.styles';

const LargeHeader = () => {
  return (
    <HeaderContainer>
      <HeaderLogo src="logo512.png" alt="Cetacean Watch" />
      <HeaderH2>The Yorkshire Cetacean Watch Project</HeaderH2>
    </HeaderContainer>
  );
};

export default LargeHeader;
