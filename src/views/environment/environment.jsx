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
     messages:''
    };
  }

  emptyState = () => {
    console.log(this.state);
    this.state.start = this.state.end;
    document.querySelector('#start').value = this.state.end;
    this.state.end = '';
    this.state.notes = '';
    
    document.querySelector('#end').value = '';
    console.log(this.state);
  }
 
  handleSubmit = (event) => {
    event.preventDefault();
    this.props.environment.addItem(
      this.state.start, this.state.end, 
      this.state.seastate, this.state.swellheight, 
      this.state.winddirection,  this.state.visibility, 
      this.state.notes);
    this.emptyState();
  };

  handleChange = (event) => {
    const { value, name } = event.target;
    this.setState({ [name]: value });
  };

  
  
  render() {
    console.log(this.props.seawatch)
    const { start,  end, seastate, swellheight, winddirection, visibility, notes} = this.state;
    if(!this.props.seawatch.hasRecord){
      return <Redirect to="/sign-in" />
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
                type="time"
                value={start}
                handleChange={this.handleChange}
                required
                label="start"
                title="Start time"
                id="start"
              />
              </div>
              
              <div>
                
              <Input
                name="end"
                type="time"
                value={end}
                handleChange={this.handleChange}
                required
                label="end"
                title="End Time"
                id="end"
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
              name="visibility"
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