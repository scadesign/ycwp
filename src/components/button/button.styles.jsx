import styled from 'styled-components';

export const ButtonStyle = styled.button`
  max-width: 50rem;
  width: auto;
  height: 50px;
  letter-spacing: 0.5px;
  line-height: 50px;
  padding: 0 35px 0 35px;
  font-size: 15px;
  background-color: #e95100;
  color: white;
  text-transform: uppercase;
  font-weight: bolder;
  border: none;
  cursor: pointer;

  &:hover {
    background-color: #2998ff;
    border: 1px solid black;
  }
`;
