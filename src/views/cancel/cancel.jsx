import React from 'react';
import { Redirect } from 'react-router-dom';
import Button from '../../components/button/button.component';
import MenuItem from '../../components/menu/menu.component';
import Header from '../../components/header/header.component';
import { PageCenter } from '../../styles/base';
import { FormButtonContainer } from '../../styles/base';
class Review extends React.Component {
  constructor(props) {
    super(props);

    this.state = {
      status: false,
    };
  }

  handleSubmit = (event) => {
    event.preventDefault();
    this.props.environment.removeStorage();
    this.props.sighting.removeStorage();
    this.props.seawatch.removeStorage();
    this.setState({ status: true });
  };

  render() {
    if (this.state.status === true) {
      return <Redirect to="/" />;
    }
    return (
      <div>
        <Header />
        <MenuItem />
        <PageCenter>
          <h2 className="title">Are you sure you wish to cancel?</h2>
          <FormButtonContainer onSubmit={this.handleSubmit}>
            <Button type="submit" value="submit">
              Cancel Seawatch
            </Button>
          </FormButtonContainer>
        </PageCenter>
      </div>
    );
  }
}

export default Review;
