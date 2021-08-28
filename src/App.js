import React, { useEffect, Component } from "react";
import {
  BrowserRouter as Router,
  Switch,
  Route,
  useLocation,
  withRouter,
} from "react-router-dom";

import { Home } from "./views/home/home.page";
import EnvironmentView  from "./views/environment/environment";
import SightingView from "./views/sighting/sighting";
import { Results } from "./views/results/results";
import  SignIn  from "./views/sign-in/sign-in";
import SignUp from "./views/sign-up/sign-up";
import Sighting from './models/sighting';
import Environment from './models/environment';
import Seawatch from './models/seawatch';


function _ScrollToTop(props) {
  const { pathname } = useLocation();
  useEffect(() => {
    window.scrollTo(0, 0);
  }, [pathname]);

  return null;
}

const ScrollToTop = withRouter(_ScrollToTop);


class App extends Component {
  state ={
    sighting: new Sighting(),
    environment: new Environment(),
    seawatch: new Seawatch()
  }

  readStorage() {
    this.state.sighting.readStorage();
    this.state.environment.readStorage();
    this.state.seawatch.readStorage();
  }

  render() {
    this.readStorage();
    return (
      <main className="container">
        <Router>
          <ScrollToTop />

          <div className="content">
            <Switch>
              <Route path="/" exact component={Home} />
              <Route path="/sign-up" exact component={SignUp} />
              <Route path="/sign-in" render={props => 
                (<SignIn {...props} seawatch={this.state.seawatch}/>)
              }/>
              <Route path="/add-environment" render={props => 
                (<EnvironmentView {...props} 
                  environment={this.state.environment}
                  seawatch={this.state.seawatch}/>)
              }/>
              <Route path="/add-sighting" render={props => 
                (<SightingView {...props} 
                  sighting={this.state.sighting}
                  seawatch={this.state.seawatch}/>)
              }/>
              <Route path="/view-sightings" exact component={Results} />
            </Switch>
          </div>
        </Router>
      </main>
    );
  }
}

export default App;
