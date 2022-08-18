import React from 'react';

import { TextAreaComponent } from './textarea.styles';

const TextArea = ({ handleChange, label, title, ...otherProps }) => {
  return (
    <span>
      {label ? <label htmlFor={label}>{title}</label> : null}
      <TextAreaComponent onChange={handleChange} {...otherProps}></TextAreaComponent>
    </span>
  );
};

export default TextArea;
