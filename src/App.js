import React, { useEffect, Component } from "react";
import {
  BrowserRouter as Router,
  Switch,
  Route,
  useLocation,
  withRouter,
} from "react-router-dom";

import { Home } from "./views/home/home.page";
import Environment  from "./views/environment/environment";
import Sighting from "./views/sighting/sighting";
import { Results } from "./views/results/results";
import  SignIn  from "./views/sign-in/sign-in";
import SignUp from "./views/sign-up/sign-up";

import './models/environment';


function _ScrollToTop(props) {
  const { pathname } = useLocation();
  useEffect(() => {
    window.scrollTo(0, 0);
  }, [pathname]);

  return null;
}

const ScrollToTop = withRouter(_ScrollToTop);

class App extends Component {
  environment = new Environment();
  render() {
    return (
      <main className="container">
        <Router>
          <ScrollToTop />

          <div className="content">
            <Switch>
              <Route path="/" exact component={Home} />
              <Route path="/sign-in" exact component={SignIn} />
              <Route path="/sign-up" exact component={SignUp} />
              <Route path="/add-environment" exact component={Environment} />
              <Route path="/add-sighting" exact component={Sighting} />
              <Route path="/view-sightings" exact component={Results} />
            </Switch>
          </div>
        </Router>
      </main>
    );
  }
}

export default App;
