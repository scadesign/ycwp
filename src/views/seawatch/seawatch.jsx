import React from 'react';
import { Link, Redirect } from 'react-router-dom';

import Select from '../../components/select/select.component';
import Input from '../../components/input/input.component';
import Button from '../../components/button/button.component';
import LargeHeader from '../../components/large-header/large-header.component';
import { stations } from '../../select-data/data';
import './seawatch.styles.scss';

class SeawatchView extends React.Component {
  constructor(props) {
    super(props);

    this.state = {
      name: '',
      email: '',
      telephone: '',
      station: '',
      status: false,
      date: new Date().toLocaleDateString(),
      hours: new Date().getHours(),
      mins: new Date().getMinutes(),
    };
  }

  updateFields = () => {
    this.props.seawatch.readStorage();
    const newItem = this.props.seawatch.getLastItem();
    this.setState({ name: newItem.name });
    this.setState({ email: newItem.email });
    this.setState({ telephone: newItem.telephone });
    this.setState({ station: newItem.station });
    this.setState({ date: newItem.date });
  };

  handleSubmit = (event) => {
    event.preventDefault();
    this.props.seawatch.addRecord(this.state.station, this.state.name, this.state.email, this.state.telephone, this.state.date);
    this.updateFields();
    window.location.reload();
    this.setState({ status: true });
  };

  handleChange = (event) => {
    const { value, name } = event.target;
    this.setState({ [name]: value });
  };

  render() {
    const { name, email, telephone, messages } = this.state;
    console.log(this.props.seawatch);
    if (this.state.status) {
      return (
        <div className="centre">
          <LargeHeader />
          <h2 className="title centre">Hi {this.state.name}</h2>
          <Link to="/add-environment" className="continue">
            Continue
          </Link>
        </div>
      );
    } else if (this.props.seawatch.hasRecord) {
      return <Redirect to="/add-environment" />;
    }
    return (
      <div>
        <LargeHeader />
        <div className="seawatch">
          <h2 className="title centre">Seawatch station</h2>
          <form onSubmit={this.handleSubmit}>
            <div className="seawatch-inputs">
              <Input name="name" type="text" value={name} handleChange={this.handleChange} required label="name" title="Name" />

              <Input name="email" type="email" value={email} handleChange={this.handleChange} required label="email" title="Email" />

              <Input name="telephone" type="text" value={telephone} handleChange={this.handleChange} required label="telephone" title="Telephone" />

              <Select name="station" tasks={stations} handleChange={this.handleChange} required label="station" title="station" />
            </div>
            <div className="button-container">
              {messages !== '' ? <div className="message">{messages}</div> : <div></div>}
              <Button type="submit" value="submit">
                creat Seawatch
              </Button>
            </div>
          </form>
          <div>
            <Link to="/" className="cancel">
              Cancel
            </Link>
          </div>
        </div>
      </div>
    );
  }
}

export default SeawatchView;
