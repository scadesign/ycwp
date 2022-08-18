import styled from 'styled-components';

export const HeaderContainer = styled.div`
  max-width: 125rem;
  width: 85%;
  margin: 0 auto 1rem auto;
  display: grid;
  grid-template-columns: auto 1fr;
  gap: 2rem;
  align-content: center;

  @media only screen and (max-width: 37.5em) {
    display: block;
    text-align: center;
    border-bottom: 1px solid $dark-orange;
    padding-bottom: 2rem;
  }
`;

export const HeaderLogo = styled.img`
  width: 5rem;
`;

export const HeaderH2 = styled.h2`
  align-self: center;
`;
