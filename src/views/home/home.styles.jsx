import styled from 'styled-components';

import { Page } from '../../styles/base';

export const HomePage = styled(Page)`
  margin-top: 1rem;
`;

export const HomeContainer = styled.ul`
  width: 30rem;
  margin: 5rem auto 0 auto;
  display: grid;
  grid-row-gap: 3rem;

  & li {
    list-style: none;
    width: 100%;
    text-align: center;

    & a {
      display: block;
      background: #e95100;
      border-radius: 1rem;
      padding: 1.5rem;
      text-transform: uppercase;
      font-weight: 800;
      color: #fff;
      text-decoration: none;
      cursor: pointer;

      &:hover {
        background: #2998ff;
      }
    }
  }
`;
