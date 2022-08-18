import styled from 'styled-components';

export const HeaderContainer = styled.div`
  max-width: 30rem;
  width: 100%;
  margin: 0 auto 1rem auto;
  align-content: center;

  @media only screen and (max-width: 37.5em) {
    display: block;
    text-align: center;
    border-bottom: 1px solid $dark-orange;
    padding-bottom: 2rem;
  }
`;

export const HeaderLogo = styled.img`
  display: block;
  max-width: 10rem;
  margin: 0 auto;
  text-align: center;

  @media only screen and (max-width: 37.5em) {
    max-width: 5rem;
  }
`;

export const HeaderH2 = styled.h2`
  text-align: center;
`;
