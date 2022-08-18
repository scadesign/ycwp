import styled from 'styled-components';

export const SelectComponent = styled.select`
  display: block;
  font-size: $default-font-size;
  font-family: inherit;
  font-weight: 400;
  color: #777;
  line-height: 1.3;
  padding: 10px 10px 10px 5px;
  width: 100%;
  max-width: 100%;
  margin: 0 0 1rem 0;
  border: 1px solid #777;
  border-bottom: 0.3rem solid #777;
  box-shadow: 0 1px 0 1px rgba(#000, 0.04);

  -moz-appearance: none;
  -webkit-appearance: none;
  appearance: none;
  background-color: #fff;
  background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%23007CB2%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E'),
    linear-gradient(to bottom, #fff 0%, #f7f7f7 100%);
  background-repeat: no-repeat, repeat;
  background-position: right 0.7rem top 50%, 0 0;
  background-size: 0.65rem auto, 100%;

  &::-ms-expand {
    display: none;
  }

  &:hover {
    border-color: #999;
  }

  &:focus {
    outline: none;
    border: 1px solid #777;
    border-bottom: 3px solid $dark-blue;
    color: $black;
  }
`;

export const SelectLabel = styled.label`
  color: #fff;
  font-size: 16px;
  font-weight: normal;
  pointer-events: none;

  @media only screen and (max-width: 56.25em) {
    font-size: 14px;
  }
`;