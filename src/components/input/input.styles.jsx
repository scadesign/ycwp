import styled from 'styled-components';

export const Group = styled.div`
  position: relative;
  margin: 0;
`;

export const FormInput = styled.input`
  background: none;
  background-color: white;
  color: $grey-3;
  font-size: 18px;
  padding: 10px 10px 10px 5px;
  display: block;
  width: 100%;
  border: none;
  border-radius: 0;
  border-bottom: 1px solid #999;
  margin: 0 0 1rem 0;

  &:focus {
    outline: none;
  }

  input[type='password'] {
    letter-spacing: 0.3em;
  }
`;

export const FormInputLabel = styled.label`
  color: #fff;
  font-size: 16px;
  font-weight: normal;
  pointer-events: none;

  @media only screen and (max-width: 56.25em) {
    font-size: 14px;
  }
`;
