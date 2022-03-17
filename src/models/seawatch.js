export default class Seawatch {
  constructor() {
    this.seawatch = {};
    this.hasRecord = false;
  }

  addRecord(station, name, email, telephone, date) {
    this.seawatch = {
      station,
      name,
      email,
      telephone,
      date,
    };

    this.addStorage();
    this.hasRecord = true;
  }

  getName() {
    return this.seawatch.name;
  }

  getLastItem() {
    return this.seawatch;
  }

  addStorage() {
    localStorage.setItem('seawatch', JSON.stringify(this.seawatch));
  }

  removeStorage() {
    localStorage.removeItem('seawatch');
    this.seawatch = {};
    this.hasRecord = false;
  }

  readStorage() {
    const storage = JSON.parse(localStorage.getItem('seawatch'));
    if (storage) {
      this.seawatch = storage;
      this.hasRecord = true;
    }
  }
}
