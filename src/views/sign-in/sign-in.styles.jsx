import styled from 'styled-components';

export const SignInComponent = styled.div`
  width: 30rem;
  margin: 1rem auto 0 auto;
  display: grid;
  grid-row-gap: 3rem;

  @media only screen and (max-width: 37.5em) {
    width: 85%;
    display: block;
    padding-bottom: 2rem;
  }
`;
