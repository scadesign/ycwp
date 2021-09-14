import React from "react";
import { Link } from "react-router-dom";
import LargeHeader from "../../components/large-header/large-header.component";
import { MapContainer, TileLayer, Marker, Tooltip } from 'react-leaflet'
import './result.styles.scss';
import Select from '../../components/select/select.component';

//import mapData from '../../data/geo';
import axios from "axios";


class Results extends React.Component {
  constructor(props) {
    super(props);

    this.state = {
      filter: '',
      url: '/seawatch',
      sightings:[],
      status: false,
      lat: 54.11577294581963,
      lng: -0.0054931640625,
      zoom: 9,
      species:''
    };
  }

 

  getSightings = () => {
    
    const headers = {
      'Content-Type': 'application/json',
    }
    if(this.state.filter !== '') {
      this.setState({url: `/seawatch/${this.state.filter}`})
    }
   
    axios.get(this.state.url, {headers: headers})
    .then((json) => {
      this.setState({sightings: json.data.data}) 
      this.setState({status: true})
    })
    .catch((error) => {
      console.log(error);
    })
  };

  handleChange = (event) => {
    const { value, name } = event.target;
    this.setState({ [name]: value });
  };
    

  render() {
  
    if(this.state.status === false){
      this.getSightings();
    }
    
    return (
      <div>
        <LargeHeader />
        <div className="sightings page">
          <h3 className="title centre">Number of Sightings by station</h3>
         
          <div id='maps'>
             <Select
              name="filter"
              task="species"
              value={this.state.filter}
              handleChange={this.handleChange}
              required
              label="filter"
              title="filter"
            />
            <MapContainer 
                 center={[this.state.lat, this.state.lng]} 
                 zoom={this.state.zoom} 
                 style={{ height: '600px'}}
              >
                {this.state.sightings.map(station => (
                  <Marker
                    key={station.id}
                    position={[
                      station.latitude,
                      station.longitude
                    ]}> 
                   <Tooltip permanent className='tooltip'>
                     <p>{station.name} <b>Sightings:</b> {station.total}</p>
                    </Tooltip>  
                  </Marker>
                ))}
              <TileLayer
                attribution='&copy <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
                url="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"
               />
             </MapContainer>
          </div>
        </div>
        <div className="button-container">
            <Link to='/'className='continue center'>Back</Link>
        </div>
         
      </div>
     
    );
  }

}

export default Results;