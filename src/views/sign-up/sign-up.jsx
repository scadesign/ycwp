import React from "react";
import { Link } from "react-router-dom";
import Input from '../../components/input/input.component';
import Button from '../../components/button/button.component';
import LargeHeader from "../../components/large-header/large-header.component"
import "./sign-up.styles.scss"


class SignUp extends React.Component {
  constructor(props) {
    super(props);

    this.state = {
     
    };
  }

  handleSubmit = (event) => {
    event.preventDefault();
    //this.setState({ email: "", password: "" });
  };

  handleChange = (event) => {
    const { value, name } = event.target;

    this.setState({ [name]: value });
    console.log(this.state);
  };

  render() {
    return (
      <div>
        <LargeHeader />
        <div className="sign-in">
          <h2 className="title centre">Sign In</h2>
          <form onSubmit={this.handleSubmit}>
            <div className="sign-in-inputs">
             
               
              
              <Input
                name="firstname"
                type="text"
                value={this.state.firstname}
                handleChange={this.handleChange}
                required
                label="firstname"
                title="Firstname"
              />
              
              <Input
                name="lastname"
                type="text"
                value={this.state.lastname}
                handleChange={this.handleChange}
                required
                label="lastname"
                title="Lastname"
              />

               <Input
                name="Phone"
                type="text"
                value={this.state.phone}
                handleChange={this.handleChange}
                required
                label="phone"
                title="phone"
              />

               <Input
                name="email"
                type="email"
                value={this.state.email}
                handleChange={this.handleChange}
                required
                label="email"
                title="Email"
              />
              
              <Input
                name="password"
                type="password"
                value={this.state.password}
                handleChange={this.handleChange}
                required
                label="password"
                title="Password"
              />

             
              
            </div>
            <div className="button-container">
            <Button type="submit" value="submit">
                Sign In
              </Button>             
            </div>
          </form>
           <div>
            <Link to ="/" className="cancel">
                Cancel
            </Link>             
            </div>
        </div>
      </div>
      
    );
  }

}

export default SignUp;