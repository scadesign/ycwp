import styled from 'styled-components';

export const Navigation = styled.div`
  background: #e95100;
  z-index: 3000;

  & ul {
    max-width: 125rem;
    display: grid;
    grid-template-columns: repeat(4, 15rem);
    margin: 0 auto;
    z-index: 3200;
    list-style: none;

    @media only screen and (max-width: 37.5em) {
      grid-template-columns: 1fr 1.2fr 1fr 1fr;
      font-size: 1.5rem;
      position: fixed;
      bottom: 0;
      left: 0;
      width: 100%;
      border-top: 1px solid #1f4564;
      background: #e95100;
    }


    & a {
      padding: 1rem;
      color: #fff;
      text-decoration: none;
      width: 100%;
      text-align: center;
      border-right: 1px solid #fff;

      &:first-child {
        border-left: 1px solid #fff;
      }

      &:hover {
        background: #990202;
      }

      @include respond(phone) {
        padding: 1rem 0.6rem;
        &:not(:last-child) {
          border-right: 1px solid #fff;
        }
        &:first-child {
          border-left: none;
        }
      }

      & li {
        list-style: none;
      }
    }
`;
