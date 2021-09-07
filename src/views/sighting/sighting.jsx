import React from "react";
import { Redirect } from "react-router-dom";
import Select from '../../components/select/select.component';
import Input from '../../components/input/input.component';
import Button from '../../components/button/button.component';
import TextArea from '../../components/textarea/textarea.component';
import MenuItem from '../../components/menu/menu.component';
import Header from "../../components/header/header.component";
import StatusIndicator from '../../components/statusIndicator/statusIndicator';

class SightingView extends React.Component {
  constructor(props) {
    super(props);

    this.state = {
      start: '',
      end: '',
      species: '',
      confidence: '',
      groupsize: '',
      calves: '',
      juveniles: '',
      bearing: '',
      distance: '',
      behaviour: '',
      birds: '',
      messages: ''
    };
  }

  emptyState() {
    this.setState({start: ''}) 
    this.setState({end: ''}) 
    this.setState({species: ''})
    this.setState({confidence: ''})
    this.setState({groupSize: ''})
    this.setState({calves: ''})
    this.setState({juveniles: ''})
    this.setState({bearing: ''})
    this.setState({distance: ''})
    this.setState({behaviour: ''})
    this.setState({birds: ''})
    this.setState({messages: ''})
  }

  handleSubmit = (event) => {
    event.preventDefault();
    this.props.sighting.addItem(
      this.state.start, this.state.end, 
      this.state.species, 
      this.state.confidence, 
      this.state.groupsize,  
      this.state.calves, 
      this.state.juveniles,
      this.state.bearing,
      this.state.distance,
      this.state.behaviour,
      this.state.birds,
      );
      this.emptyState();
  };

  handleChange = (event) => {
    const { value, name } = event.target;

    this.setState({ [name]: value });
  };

  render() {
    this.props.environment.updated = false;
    console.log(this.props.sighting.numItems)
    const { start, end, species, confidence, groupsize, calves, juveniles, bearing, distance, behaviour, birds} = this.state;
    if(!this.props.seawatch.hasRecord){
      return <Redirect to="/sign-in" />
    }
    return (
      <div>
        <Header />
        <MenuItem />
        <div className="sighting page">
          <h2 className="title">Add Sighting data</h2>
          <p>Please fill in the details as completely as possible</p>
          <StatusIndicator className='sighting-indicator' numberItems={this.props.sighting.numItems} />
          <form onSubmit={this.handleSubmit}>
           
            <div className="four-up">
               <div>
                
              
              <Input
                name="start"
                type="time"
                value={start}
                handleChange={this.handleChange}
                required
                label="Start"
                title="Start time"
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
              />
              </div>

            <Select
              name="species"
              task="species"
              value={species}
              handleChange={this.handleChange}
              required
              label="species"
              title="species"
            />

            <Select
              name="confidence"
              task="confidence"
              value={confidence}
              handleChange={this.handleChange}
              required
              label="confidence"
              title="confidence"
            />
            </div>

            <div className="three-up">
               
                  
                  <Input
                    name="groupsize"
                    type="text"
                    value={groupsize}
                    handleChange={this.handleChange}
                    required
                    label="groupsize"
                    title="Group Size (min-max)"
                  />
                 
                  
                  
                  <Input
                    name="calves"
                    type="number"
                    value={calves}
                    handleChange={this.handleChange}
                    required
                    label="calves"
                    title="No. Calves < half adult size"
                  />

                   <Input
                    name="juveniles"
                    type="number"
                    value={juveniles}
                    handleChange={this.handleChange}
                    required
                    label="juveniles"
                    title="No. Juveniles > half adult size"
                  />
            </div>

              <div className="three-up">
               
                  
                <Select
                  name="bearing"
                  task="winddirection"
                  value={bearing}
                  handleChange={this.handleChange}
                  required
                  label="bearing"
                  title="Bearing"
                />
                 
                <Input
                  name="distance"
                  type="number"
                  value={distance}
                  handleChange={this.handleChange}
                  required
                  label="distannce"
                  title="Distance (km)"
                />

                <Select
                  name="behaviour"
                  task="behaviour"
                  value={behaviour}
                  handleChange={this.handleChange}
                  required
                  label="behaviour"
                  title="Behaviour"
                />
            </div>


            <div>
              <TextArea  name="birds"
                value={birds}
                handleChange={this.handleChange}
                label="birds"
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

export default SightingView;