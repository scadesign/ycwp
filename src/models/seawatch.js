
export default class Seawatch {
  constructor() {
    this.seawatch = {
    };
    this.hasRecord = false;
  };

  addRecord (session_id, access_token, station, name, date) {
    this.seawatch = {
      session_id,
      access_token, 
      station, 
      name, 
      date
    };

    this.addStorage();
    this.hasRecord = true;
  }

  getName() {
    return this.seawatch.name
  }

  addStorage() {
    localStorage.setItem('seawatch', JSON.stringify(this.seawatch));
  }

  removeStorage() {
    localStorage.removeItem('seawatch');
  }

  readStorage() {
    const storage = JSON.parse(localStorage.getItem('seawatch'));
    if(storage) {
      this.seawatch = storage;
      this.hasRecord = true;
    }
  }

}