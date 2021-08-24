import React from "react";
import { Redirect } from "react-router-dom";
import Select from '../../components/select/select.component';
import Input from '../../components/input/input.component';
import Button from '../../components/button/button.component';
import TextArea from '../../components/textarea/textarea.component';
import MenuItem from '../../components/menu/menu.component';
import Header from "../../components/header/header.component"
import './environment.styles.scss';

import '../../models/environment';
import Environment from "../../models/environment";

class EnvironmentView extends React.Component {
  constructor(props) {
    super(props);

    this.state = {
     start: '',
     end: '',
     seastate: '',
     swellheight: '',
     winddirection: '',
     visibility: '',
     notes: '',
    status: false,
    messages:''
    };
  }
  readStorage() {
    const active = JSON.parse(localStorage.getItem('seawatch'));
    if (active) {
      this.setState.status = true; 
    }
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
  environment = new Environment();
  render() {
    this.readStorage();
    const { start, end, seastate, swellheight, winddirection, visibility, notes, status} = this.state;
    if(status) {
      return <Redirect to='/sign-in' />
    }
    return (
      <div>
        <Header />
        <MenuItem />
        <div className="environment page">
          <h2 className="title">Add Environment data</h2>
          <p>Make a new record every 15mins, when environmental conditions change or when there is a break in effort</p>

          <form onSubmit={this.handleSubmit}>
            <div className="time two-up">
              <div>
                <Input
              name="start"
              type="hidden"
              value={start}
              />
              
              <Input
                name="start-time"
                type="time"
                value={start}
                handleChange={this.handleChange}
                required
                label="Start-time"
                title="Start time"
              />
              </div>
              
              <div>
                <Input
                name="end"
                type="hidden"
                value={end}
              />
              
              <Input
                name="end-time"
                type="time"
                value={end}
                handleChange={this.handleChange}
                required
                label="end-time"
                title="End Time"
              />
              </div>
              
            </div>

            <div className="four-up">

            <Select
              name="seastate"
              task="seastate"
              value={seastate}
              handleChange={this.handleChange}
              required
              label="seastate"
              title="Sea state"
            />

            <Select
              name="swellheight"
              task="swellheight"
              value={swellheight}
              handleChange={this.handleChange}
              required
              label="swellheight"
              title="Swell Height"
            />
            

            <Select
              name="winddirection"
              task="winddirection"
              value={winddirection}
              handleChange={this.handleChange}
              required
              label="winddirection"
              title="Wind Direction"
            />

            <Select
              name="Visibility"
              task="visibility"
              value={visibility}
              handleChange={this.handleChange}
              required
              label="visibility"
              title="Visibility"
            />
            </div>
            <div>
              <TextArea  name="notes"
                value={notes}
                handleChange={this.handleChange}
                label="notes"
                title="Additional Notes e.g. boat activity"
                rows="4" cols="65"
                />
              
            </div>
            <div className="button-container">
            <Button type="submit" value="submit">
                Add Environment Data
              </Button>
              
            </div>
          </form>
        </div>
      </div>
      
    );
  }

}

export default EnvironmentView;