import styled from 'styled-components';

export const EnvironmentIndicator = styled.div`
  display: block;
  position: absolute;
  top: 2rem;
  right: 5rem;

  @media only screen and (max-width: 75em) {
    top: 13rem;
  }

  @media only screen and (max-width: 56.25em) {
    top: 15rem;
    right: 2rem;
  }

  @media only screen and (max-width: 37.5em) {
    position: relative;
    margin: -1rem 0 1.5rem 0;
    top: 0;
    right: 0;
  }
`;
