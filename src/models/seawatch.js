
export default class Recording {
  constructor() {
    this.record = {
    };
    this.hasRecord = false;
  };

  addRecord (date, siteName, observer, address, email, telephone) {
    this.record = {
      date,
      siteName,
      latLong: this.getLatLong(siteName),
      observer,
      address,
      email,
      telephone
    };

    this.addStorage();
    this.hasRecord = true;
  }

  getLatLong(siteName){
    let latLong ="";
    switch(siteName){
      case 'Aldborough' :
      latLong = '53.83695, -0.09156';
      break;
      case 'Bay Ness' :
      latLong = '54.441389, -0.5225';
      break;
      case 'Bempton Cliffs' :
        latLong = '54.1516, -0.17499';
      break;
      case 'Bridlington south beach' :
      latLong = '54.074372, -0.201190';
      break; 
      case 'Cayton Bay South' :
      latLong = '54.2417, -0.33932';
      break;
      case 'Cloughton Newlands' :
      latLong = '54.35006, -0.43617';
      break;
      case 'Cloughton Wyke' :
      latLong = '54.34000015, -0.4318125';
      break;
      case 'Danes Dyke' :
      latLong = '54.10457, -0.14148';
      break;
      case 'Filey Brigg' :
      latLong = '54.21751, -0.26984';
      break; 
      case 'Filey North' :
      latLong = '54.21919, -0.27687';
      break;
      case 'Flamborough Head' :
      latLong = '54.1164, -0.07731';
      break;
      case 'Flamborough Head North Landing' :
      latLong = '54.13072, -0.10315';
      break;
      case 'Flamborough South Landing' :
      latLong = '54.104459, -0.118406';
      break;
      case 'Hornsea north' :
      latLong = '53.9152, -0.1611';
      break;
      case 'Hornsea south' :
      latLong = '53.90645, -0.15705';
      break;
      case 'Humber Bridge' :
      latLong = '53.71505, -0.45059';
      break;
      case 'Huntcliff' :
      latLong = '54.58615, -0.94998';
      break;
      case 'Kettleness' :
      latLong = '54.52943, -0.71766';
      break;
      case 'Long Nab' :
      latLong = '54.33132, -0.41848';
      break;
      case 'Mappleton' :
      latLong = '53.87608, -0.1338';
      break;
      case 'Marine Drive, Scarborough' :
      latLong = '54.28760, -0.38456';
      break;
      case 'Old Nab' :
      latLong = '54.55642, -0.77588';
      break;
      case 'Ravenscar radar station' :
      latLong = '54.39364, -0.47369';
      break;
      case 'Scarborough North Bay' :
      latLong = '54.3501, -0.40874';
      break;
      case 'Spurn Warren' :
      latLong = '53.612570, 0.144649';
      break;
      case 'The Deep' :
      latLong = '53.7386, -0.32964';
      break;
      case 'Whitby Abbey' :
      latLong = '54.48991, -0.60537';
      break;
      case 'Whitby high light' :
      latLong = '54.47818, -0.56849';
      break;
    }
    return latLong;
  };

  addStorage() {
    localStorage.setItem('record', JSON.stringify(this.record));
  }

  removeStorage() {
    localStorage.removeItem('record');
  }

  readStorage() {
    const storage = JSON.parse(localStorage.getItem('record'));
    if(storage) {
      this.record = storage;
      this.hasRecord = true;
    }
  }

}