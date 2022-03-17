import React from 'react';
import { Redirect } from 'react-router-dom';
import Select from '../../components/select/select.component';
import Input from '../../components/input/input.component';
import Button from '../../components/button/button.component';
import TextArea from '../../components/textarea/textarea.component';
import MenuItem from '../../components/menu/menu.component';
import Header from '../../components/header/header.component';
import StatusIndicator from '../../components/statusIndicator/statusIndicator';
import './environment.styles.scss';
import { seaState, distance, swellHeight, lighting } from '../../select-data/data';

class EnvironmentView extends React.Component {
  constructor(props) {
    super(props);

    this.state = {
      start: '',
      end: '',
      seastate: '',
      swellheight: '',
      glare: '',
      vessels: '',
      visibility: '',
      notes: '',
      messages: '',
    };
  }

  updateFields = () => {
    this.props.environment.readStorage();
    const newItem = this.props.environment.getLastItem();
    this.setState({ start: newItem.start });
    this.setState({ end: newItem.end });
    this.setState({ seastate: newItem.seaState });
    this.setState({ swellheight: newItem.swelHeight });
    this.setState({ glare: newItem.glare });
    this.setState({ vessels: newItem.vessels });
    this.setState({ visibility: newItem.visibility });
    this.setState({ notes: '' });
  };

  handleSubmit = (event) => {
    event.preventDefault();
    this.props.environment.addItem(this.state.start, this.state.end, this.state.seastate, this.state.swellheight, this.state.glare, this.state.vessels, this.state.visibility, this.state.notes);
    this.updateFields();
    window.location.reload();
  };

  handleChange = (event) => {
    const { value, name } = event.target;
    this.setState({ [name]: value });
  };

  render() {
    if (this.props.environment.numItems > 0 && this.props.environment.updated !== true) {
      this.updateFields();
    }
    const { start, end, seastate, swellheight, glare, vessels, visibility, notes } = this.state;
    if (!this.props.seawatch.hasRecord) {
      return <Redirect to="/home" />;
    }
    return (
      <div>
        <Header />

        <MenuItem />
        <div className="environment page">
          <h2 className="title">Add Environment data</h2>

          <p>Make a new record every 15mins, when environmental conditions change or when there is a break in effort</p>
          <StatusIndicator className="status-indicator" numberItems={this.props.environment.numItems} />
          {this.props.environment.updated === true ? <p className="highlight">Details updated to estimated next environment data submission</p> : <p>please fill in the form to continue</p>}
          <form onSubmit={this.handleSubmit}>
            <div className="time two-up">
              <div>
                <Input name="start" type="time" value={start} handleChange={this.handleChange} required label="start" title="Start time" id="start" />
              </div>

              <div>
                <Input name="end" type="time" value={end} handleChange={this.handleChange} required label="end" title="End Time" id="end" />
              </div>
            </div>

            <div className="four-up">
              <Select name="seastate" tasks={seaState} value={seastate} handleChange={this.handleChange} required label="seastate" title="Sea state" id="seastate" />

              <Select name="swellheight" tasks={swellHeight} value={swellheight} handleChange={this.handleChange} required label="swellheight" title="Swell Height" id="swellheight" />

              <Select name="glare" tasks={lighting} value={glare} handleChange={this.handleChange} required label="glare" title="glare" id="glare" />

              <TextArea name="vessels" value={vessels} handleChange={this.handleChange} label="vessels" title="Active Vessels within 5km" rows="4" cols="65" id="vessels" />

              <Select name="visibility" tasks={distance} value={visibility} handleChange={this.handleChange} required label="visibility" title="Visibility" id="visibility" />
            </div>
            <div>
              <TextArea name="notes" value={notes} handleChange={this.handleChange} label="notes" title="Additional Notes e.g. boat activity" rows="4" cols="65" id="notes" />
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
