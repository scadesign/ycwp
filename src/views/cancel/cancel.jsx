import React from 'react';
import { Redirect } from 'react-router-dom';
import Button from '../../components/button/button.component';
import MenuItem from '../../components/menu/menu.component';
import Header from '../../components/header/header.component';

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
        <div className="page centre">
          <h2 className="title">Are you sure you wish to cancel?</h2>
          <form className="button-container" onSubmit={this.handleSubmit}>
            <Button type="submit" value="submit">
              Cancel Seawatch
            </Button>
          </form>
        </div>
      </div>
    );
  }
}

export default Review;
