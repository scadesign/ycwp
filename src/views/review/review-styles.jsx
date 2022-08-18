import styled from 'styled-components';

export const ReviewFour = styled.div`
  display: grid;
  grid-template-columns: repeat(4, 23.5%);
  gap: 2%;

  @media only screen and (max-width: 75em) {
    grid-template-columns: repeat(2, 48%);
    gap: 4%;
  }

  @media only screen and (max-width: 37.5em) {
    display: block;
    margin-bottom: 0.5rem;
  }
`;

export const ReviewTwo = styled.div`
  display: grid;
  grid-template-columns: repeat(2, 48%);
  gap: 4%;

  @media only screen and (max-width: 75em) {
    display: block;
    margin-bottom: 0.5rem;
  }
`;
