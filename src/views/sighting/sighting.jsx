import React from "react";
import Select from '../../components/select/select.component';
import Input from '../../components/input/input.component';
import Button from '../../components/button/button.component';
import TextArea from '../../components/textarea/textarea.component';
import MenuItem from '../../components/menu/menu.component';
import Header from "../../components/header/header.component"

class Sighting extends React.Component {
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
  };

  render() {
    return (
      <div>
        <Header />
        <MenuItem />
        <div className="sighting page">
          <h2 className="title">Add Sighting data</h2>
          <p>Make a new record every 15mins, when environmental conditions change or when there is a break in effort</p>

          <form onSubmit={this.handleSubmit}>
           
            <div className="four-up">
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

            <Select
              name="species"
              task="species"
              value={this.state.species}
              handleChange={this.handleChange}
              required
              label="species"
              title="species"
            />

            <Select
              name="confidence"
              task="confidence"
              value={this.state.confidence}
              handleChange={this.handleChange}
              required
              label="confidence"
              title="confidence"
            />
            </div>

            <div className="three-up">
               
                  
                  <Input
                    name="group_size"
                    type="text"
                    value={this.state.start}
                    handleChange={this.handleChange}
                    required
                    label="group_size"
                    title="Group Size (min-max)"
                  />
                 
                  
                  
                  <Input
                    name="calves"
                    type="number"
                    value={this.state.calves}
                    handleChange={this.handleChange}
                    required
                    label="calves"
                    title="No. Calves < half adult size"
                  />

                   <Input
                    name="juveniles"
                    type="number"
                    value={this.state.juveniles}
                    handleChange={this.handleChange}
                    required
                    label="juveniles"
                    title="No. Juveniles > half adult size"
                  />
            </div>

              <div className="three-up">
               
                  
                <Select
                  name="bearing"
                  task="wind-direction"
                  value={this.state.direction}
                  handleChange={this.handleChange}
                  required
                  label="bearing"
                  title="Bearing"
                />
                 
                <Input
                  name="distance"
                  type="number"
                  value={this.state.distance}
                  handleChange={this.handleChange}
                  required
                  label="distannce"
                  title="Distance (km)"
                />

                <Select
                  name="behaviour"
                  task="behaviour"
                  value={this.state.behaviour}
                  handleChange={this.handleChange}
                  required
                  label="behaviour"
                  title="Behaviour"
                />
            </div>


            <div>
              <TextArea  name="associated_birds"
                value={this.state.end}
                handleChange={this.handleChange}
                label="associated_birds"
                title="Associated Birds"
                rows="4" cols="65"
                />
              
            </div>
            <div className="button-container">
            <Button type="submit" value="submit">
                Add Sighting Data
            </Button>
              
            </div>
          </form>
        </div>
      </div>
      
    );
  }

}

export default Sighting;