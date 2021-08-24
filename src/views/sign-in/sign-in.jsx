import React from "react";
import { Link, Redirect } from "react-router-dom";

import Select from "../../components/select/select.component";
import Input from '../../components/input/input.component';
import Button from '../../components/button/button.component';
import LargeHeader from "../../components/large-header/large-header.component"
import "./sign-in.styles.scss"
import axios from "axios"


class SignIn extends React.Component {
  constructor(props) {
    super(props);

    this.state = {
      email: '',
      password: '',
      station: '',
      status: false,
      date: new Date().toLocaleDateString(),
    };
  }

  readStorage() {
    const active = JSON.parse(localStorage.getItem('seawatch'));
    if (active) {
      this.state.status = true; 
    }
  }


  handleSubmit = (event) => {
    event.preventDefault();

    const headers = {
      'Content-Type': 'application/json'
    }
    const signUp = {
      email: this.state.email,
      password: this.state.password
    }
    axios.post("http://ycwp.test/sessions", signUp, {headers: headers})
    .then((response) => {  
      this.setState({status: true}); 
      
      const seawatch = {
        session_id: response.data.data.session_id,
        access_token: response.data.data.access_token,
        station: this.state.station,
        date: this.state.date
      }
      localStorage.setItem('seawatch', JSON.stringify(seawatch));
      
    }).catch((error) => {
      this.setState({messages: error.response.data.messages});
    })
  };

  handleChange = (event) => {
    const { value, name } = event.target;
    this.setState({ [name]: value });
  };
  
  render() {
    this.readStorage();
    
    const { email, password, status, messages} = this.state;
    if(status) {
      return <Redirect to='/add-environment' />
    }
    return (
      <div>
        <LargeHeader />
        <div className="sign-in">
          <h2 className="title centre">Sign In</h2>
          <form onSubmit={this.handleSubmit}>
            <div className="sign-in-inputs">
  
              <Input
                name="email"
                type="email"
                value = {email}
                handleChange={this.handleChange}
                required
                label="email"
                title="Email"
              />
              
                
              
              <Input
                name="password"
                type="password"
                value={password}
                handleChange={this.handleChange}
                required
                label="password"
                title="Password"
              />

              <Select
                name="station"
                task="station"
                value={this.state.station}
                handleChange={this.handleChange}
                required
                label="station"
                title="station"
              />
             
              
            </div>
            <div className="button-container">
              {messages !=="" ? <div className="message">{ messages }</div>  : <div></div>}
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

export default SignIn;