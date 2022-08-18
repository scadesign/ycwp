import React from 'react';
import { Link, Redirect } from 'react-router-dom';

import Select from '../../components/select/select.component';
import Input from '../../components/input/input.component';
import Button from '../../components/button/button.component';
import LargeHeader from '../../components/large-header/large-header.component';
import axios from 'axios';
import { SignInComponent } from './sign-in.styles';

class SignIn extends React.Component {
  constructor(props) {
    super(props);

    this.state = {
      email: '',
      password: '',
      station: '',
      status: false,
      date: new Date().toLocaleDateString(),
      hours: new Date().getHours(),
      mins: new Date().getMinutes(),
    };
  }

  handleSubmit = (event) => {
    event.preventDefault();

    const headers = {
      'Content-Type': 'application/json',
    };
    const signUp = {
      email: this.state.email,
      password: this.state.password,
      first_name: '',
    };
    axios
      .post('http://ycwp.test/sessions', signUp, { headers: headers })
      .then((response) => {
        this.setState({ status: true });
        this.setState({ first_name: response.data.data.first_name });

        const date = `${this.state.date} ${this.state.hours}:${this.state.mins}`;
        console.log(date);

        this.props.seawatch.addRecord(response.data.data.session_id, response.data.data.access_token, this.state.station, response.data.data.first_name, date);
        this.state.status = true;
        this.props.seawatch.readStorage();
      })
      .catch((error) => {
        this.setState({ messages: error.response.data.messages });
      });
  };

  handleChange = (event) => {
    const { value, name } = event.target;
    this.setState({ [name]: value });
  };

  render() {
    this.props.environment.updated = false;
    const { email, password, messages } = this.state;
    if (this.state.status) {
      return (
        <div className="centre">
          <LargeHeader />
          <h2 className="title centre">Welcome Back {this.state.first_name}</h2>
          <Link to="/add-environment" className="continue">
            Continue
          </Link>
        </div>
      );
    } else if (this.props.seawatch.hasRecord) {
      return <Redirect to="/add-environment" />;
    }
    return (
      <div>
        <LargeHeader />
        <SignInComponent>
          <h2 className="title centre">Sign In</h2>
          <form onSubmit={this.handleSubmit}>
            <div className="sign-in-inputs">
              <Input name="email" type="email" value={email} handleChange={this.handleChange} required label="email" title="Email" />

              <Input name="password" type="password" value={password} handleChange={this.handleChange} required label="password" title="Password" />

              <Select name="station" task="station" value={this.state.station} handleChange={this.handleChange} required label="station" title="station" />
            </div>
            <div className="button-container">
              {messages !== '' ? <div className="message">{messages}</div> : <div></div>}
              <Button type="submit" value="submit">
                Sign In
              </Button>
            </div>
          </form>
          <div>
            <Link to="/" className="cancel">
              Cancel
            </Link>
          </div>
        </SignInComponent>
      </div>
    );
  }
}

export default SignIn;
