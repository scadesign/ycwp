import React from 'react';

import { ButtonStyle } from './button.styles';

const Button = ({ children, ...otherProps }) => <ButtonStyle {...otherProps}>{children}</ButtonStyle>;

export default Button;
