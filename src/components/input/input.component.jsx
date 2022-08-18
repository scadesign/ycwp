import React from 'react';

import { Group, FormInput, FormInputLabel } from './input.styles';

const Input = ({ handleChange, label, title, ...otherProps }) => (
  <Group>
    {label ? <FormInputLabel htmlFor={label}>{title}</FormInputLabel> : null}
    <FormInput onChange={handleChange} {...otherProps} />
  </Group>
);

export default Input;
