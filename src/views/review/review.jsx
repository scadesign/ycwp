import React from "react";
import { Redirect, Link } from "react-router-dom";
import Button from '../../components/button/button.component';
import MenuItem from '../../components/menu/menu.component';
import Header from "../../components/header/header.component";
import SeawatchReview from '../../components/seawatch-review/seawatch-review.component';
import EnvironmentReview from '../../components/environment-review/environment-review.component';
import SightingReview from '../../components/sightings-review/sightings-review.component';
import axios from "axios";


class Review extends React.Component {
  constructor(props) {
    super(props);

    this.state = {
      status: false
    };
  }

 

  handleSubmit = (event) => {
    event.preventDefault();

    const headers = {
      'Content-Type': 'application/json',
      'Authorization': this.props.seawatch.seawatch.access_token
    }
    const theSeawatch = {
      session_id:  this.props.seawatch.seawatch.session_id,
      date: this.props.seawatch.seawatch.date,
      station: this.props.seawatch.seawatch.station,
      environment: this.props.environment.items,
      sightings: this.props.sighting.items
    }
    axios.post("http://ycwp.test/seawatch", theSeawatch, {headers: headers})
    .then((response) => {  
      this.setState({status: true});
      this.props.environment.removeStorage();
      this.props.sighting.removeStorage();
      this.props.seawatch.removeStorage();
      
    }).catch((error) => {
      this.setState({messages: error.response.data.messages});
    })
  };
    

  render() {
    if(!this.props.seawatch.hasRecord && this.state.status === false){
      return <Redirect to="/sign-in" />
    }
    if(this.state.status ){
      return (
        <div>
          <Header />
          <div  className="page review-page centre">
          <h2 className="title centre">Review</h2>
          <h3 className='centre'>Seawatch Submitted successfully</h3>
          <Link to='/' className='continue centre'>Continue</Link>
        </div>
        </div>
        
      )
    }
    return (
      <div>
        <Header />
        <MenuItem />
        <div className="review-page page">
          <h2 className="title">Review</h2>

          <div>
            <h3>Seawatch</h3>
            <SeawatchReview 
              station = {this.props.seawatch.seawatch.station}
              name = {this.props.seawatch.seawatch.name}
              date = {this.props.seawatch.seawatch.date}/>
          </div>
          
           <div>
             <h3>Environment recordings</h3>
             <div className="review-four">
               { this.props.environment.items.map(({ id, start, end, seaState, swellHeight, windDirection, visibility, notes}) => (
                <EnvironmentReview key={id} 
                start = {start} end = {end} 
                seaState={seaState} 
                swellHeight={swellHeight} 
                windDirection={windDirection} 
                visibility={visibility}
                notes={notes}/>
              ))
              }
             </div>
             
           </div>

            <div>
             <h3>Sighting recordings</h3>
             <div className="review-two">
              { this.props.sighting.items.map(({ id, start, end, species, confidence, groupSize, calves, juveniles, bearing, distance, behaviour, associatedBirds}) => (
                <SightingReview key={id} 
                start = {start} end = {end} 
                species={species} 
                confidence={confidence} 
                groupSize={groupSize} 
                calves={calves} 
                juveniles={juveniles}
                bearing={bearing}
                distance={distance} 
                behaviour={behaviour}
                associatedBirds={associatedBirds}/>
              ))
              }
              </div>
           </div>
            
            <form className="button-container" onSubmit={this.handleSubmit}>

            <Button type="submit" value="submit">
                Submit Seawatch
            </Button>
              
            </form>
            <div className="button-container">
            <Link  className=' cancel cancel-seawatch' to="/cancel">
                Cancel Seawatch
            </Link>
              
            </div>
          
        </div>
      </div>
      
    );
  }

}

export default Review;