
export default class Sighting {
  constructor() {
    this.items = [];
    this.numItems = 1;
  };

  addItem (firstSeen, lastSeen, species, confidence, groupSize, calves, juveniles, bearing, distance, behaviour, associatedBirds){
    const item = {
      id: this.numItems,
      firstSeen,
      lastSeen,
      species,
      confidence,
      groupSize,
      calves,
      juveniles,
      bearing,
      distance,
      behaviour, 
      associatedBirds
    };
    this.items.push(item);
    this.persistData();
    this.numItems++;
    return item;
  };

  getlastItem (){
    const last = this.items.length-1;
    
    return last;
  }

  updateItem(id, firstSeen, lastSeen, species, confidence, groupSize, calves, juveniles, bearing, distance, behaviour, associatedBirds) {
    const index = this.items.findIndex(el => el.id === id);
    this.items[index].firstSeen = firstSeen;
    this.items[index].lastSeen = lastSeen;
    this.items[index].species = species;
    this.items[index].confidence = confidence;
    this.items[index].groupSize = groupSize;
    this.items[index].calves = calves;
    this.items[index].juveniles = juveniles;
    this.items[index].bearing = bearing;
    this.items[index].distance = distance;
    this.items[index].behaviour = behaviour;
    this.items[index].associatedBirds = associatedBirds;
    this.persistEnvData();
    return this.items[index];
  };

  deleteItem(id){
    const index = this.items.findIndex(el => el.id === id);
    this.items.splice(index, 1);
  };

  clearItems(){
    this.items = [];
    this.numItems = 1;
  }

  persistData() {
    localStorage.setItem('sightings', JSON.stringify(this.items));
  }
  
  readStorage() {
    const storage = JSON.parse(localStorage.getItem('sightings'));
    if(storage) {
      this.items = storage;
      this.numItems = storage.length +1;
    }
  }
  
  removeStorage() {
    localStorage.removeItem('sightings');
  }
}