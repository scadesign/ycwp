import React from 'react';

import './select.styles.scss';

const Select = ({ tasks, handleChange, label, title, ...otherProps }) => {
  return (
    <span>
      {label ? (
        <label htmlFor={label} className="select-label">
          {title}
        </label>
      ) : null}
      <select className="select" onChange={handleChange} {...otherProps}>
        <option value="">Please Select</option>
        {tasks.map((task) => (
          <option key={task.id} value={task.name}>
            {task.name}
          </option>
        ))}
      </select>
    </span>
  );
};

export default Select;
