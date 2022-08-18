import styled from 'styled-components';
export const TextAreaComponent = styled.textarea`
  color: #000;
  font-size: 1.6rem;
  font-family: inherit;
  padding: 1.5rem 2rem;
  background-color: #fff;
  border: 1px solid #777;
  border-bottom: 0.3rem solid #777;
  width: 100%;

  &:focus {
    outline: none;
    box-shadow: 0 1rem 2rem rgba(#000, 0.1);
    border-bottom: 0.3rem solid #1f4564;
  }

  &:focus:invalid {
    border-bottom: 0.3rem solid #e95100;
  }

  &::-webkit-input-placeholder {
    color: #777;
  }
`;
