import React, { useEffect, useState } from 'react';

import { SelectComponent, SelectLabel } from './select.styles';

const Select = ({ task, handleChange, label, title, ...otherProps }) => {
  const [data, setData] = useState([]);

  useEffect(() => {
    fetch(`tasks/${task}`)
      .then((response) => response.json())
      .then((json) => setData(json.data.task))
      .catch((error) => console.error(error));
  }, [task]);

  return (
    <span>
      {label ? <SelectLabel htmlFor={label}>{title}</SelectLabel> : null}
      <SelectComponent onChange={handleChange} {...otherProps}>
        <option value="">Please Select</option>
        {data.map((tasks) => (
          <option key={tasks.id} value={tasks.task}>
            {tasks.task}
          </option>
        ))}
      </SelectComponent>
    </span>
  );
};

export default Select;
