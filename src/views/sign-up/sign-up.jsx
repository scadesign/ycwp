import React from 'react';
import { Link } from 'react-router-dom';
import Input from '../../components/input/input.component';
import Button from '../../components/button/button.component';
import LargeHeader from '../../components/large-header/large-header.component';
import { SignUpContainer } from './sign-up.styles';
import axios from 'axios';

class SignUp extends React.Component {
  constructor(props) {
    super(props);

    this.state = {
      first_name: '',
      last_name: '',
      email: '',
      phone: '',
      password: '',
      status: false,
      messages: [],
    };
  }

  handleSubmit = (event) => {
    event.preventDefault();
    const headers = {
      'Content-Type': 'application/json',
    };
    // send post
    axios
      .post('http://ycwp.test/users', this.state, { headers: headers })
      .then((response) => {
        this.setState({ status: true });
        console.log(this.state.status);
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
    const { first_name, last_name, phone, email, password, status, messages } = this.state;
    return (
      <div>
        <LargeHeader />
        {!status ? (
          <SignUpContainer>
            <h2 className="title centre">Sign Up</h2>
            <form onSubmit={this.handleSubmit}>
              <div className="sign-in-inputs">
                <Input name="first_name" type="text" value={first_name} handleChange={this.handleChange} required label="first_name" title="Firstname" />

                <Input name="last_name" type="text" value={last_name} handleChange={this.handleChange} required label="last_name" title="Lastname" />

                <Input name="phone" type="text" value={phone} handleChange={this.handleChange} required label="phone" title="Phone" />

                <Input name="email" type="email" value={email} handleChange={this.handleChange} required label="email" title="Email" />

                <Input name="password" type="password" value={password} handleChange={this.handleChange} required label="password" title="Password" />
              </div>
              <div className="button-container">
                {messages !== '' ? <div className="message">{messages}</div> : <div></div>}
                <Button type="submit" value="submit">
                  Sign Up
                </Button>
              </div>
            </form>
            <div>
              <Link to="/" className="cancel">
                Cancel
              </Link>
            </div>
          </SignUpContainer>
        ) : (
          <div>
            <h4 className="title centre">Welcome {first_name}</h4>
            <Link to="/" className="cancel">
              Continue
            </Link>
          </div>
        )}
      </div>
    );
  }
}

export default SignUp;
