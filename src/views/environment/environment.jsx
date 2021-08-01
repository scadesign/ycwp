import React from "react";
import Select from '../../components/select/select.component';
import Input from '../../components/input/input.component';
import Button from '../../components/button/button.component';
import TextArea from '../../components/textarea/textarea.component';
import MenuItem from '../../components/menu/menu.component';
import Header from "../../components/header/header.component"
import './environment.styles.scss';

class Environment extends React.Component {
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
              value={this.state.start}
              />
              
              <Input
                name="start-time"
                type="time"
                value={this.state.start}
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
                value={this.state.end}
              />
              
              <Input
                name="end-time"
                type="time"
                value={this.state.end}
                handleChange={this.handleChange}
                required
                label="end-time"
                title="End Time"
              />
              </div>
              
            </div>

            <div className="four-up">

            <Select
              name="sea-state"
              task="sea-state"
              value={this.state.sea}
              handleChange={this.handleChange}
              required
              label="sea-state"
              title="Sea state"
            />

            <Select
              name="swell-height"
              task="swell-height"
              value={this.state.swell}
              handleChange={this.handleChange}
              required
              label="swell-height"
              title="Swell Height"
            />
            

            <Select
              name="wind-direction"
              task="wind-direction"
              value={this.state.direction}
              handleChange={this.handleChange}
              required
              label="wind-direction"
              title="Wind Direction"
            />

            <Select
              name="Visibility"
              task="visibility"
              value={this.state.visibility}
              handleChange={this.handleChange}
              required
              label="visibility"
              title="Visibility"
            />
            </div>
            <div>
              <TextArea  name="additional-notes"
                value={this.state.end}
                handleChange={this.handleChange}
                label="additional-notes"
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

export default Environment;