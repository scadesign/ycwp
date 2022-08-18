import styled from 'styled-components';

export const Page = styled.div`
  display: block;
  max-width: 125rem;
  width: 100%;
  margin: 2rem auto;
  padding: 0 0 0.1rem 0;
  position: relative;

  @media only screen and (max-width: 75em) {
    width: 85%;
  }
`;

export const PageCenter = styled(Page)`
  text-align: center;
`;

export const TwoUp = styled.div`
  display: grid;
  grid-template-columns: repeat(2, 23.5%);
  gap: 2%;

  @media only screen and (max-width: 37.5em) {
    grid-template-columns: repeat(2, 48%);
    gap: 4%;
  }
`;

export const FourUp = styled.div`
  display: grid;
  grid-template-columns: repeat(4, 23.5%);
  gap: 2%;

  @media only screen and (max-width: 37.5em) {
    display: block;
    margin-bottom: 0.5rem;
  }
`;

export const ThreeUp = styled.div`
  display: grid;
  grid-template-columns: repeat(3, 32%);
  gap: 2%;

  @media only screen and (max-width: 37.5em) {
    display: block;
    margin-bottom: 0.5rem;
  }
`;

export const ButtonContainer = styled.div`
  text-align: center;
  margin-top: 3rem;
  margin-bottom: 1rem;
  z-index: 5000;
`;

export const FormButtonContainer = styled.form`
  text-align: center;
  margin-top: 3rem;
  margin-bottom: 1rem;
  z-index: 5000;
  padding-bottom: 2rem;
`;
