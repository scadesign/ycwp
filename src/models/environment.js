export default class Environment {
  constructor() {
    this.items = [];
    this.numItems = 0;
    this.updated = false;
  };

  addItem(start, end, seaState, swellHeight, windDirection, visibility, notes) {
    const item = {
      id: this.numItems,
      start,
      end,
      seaState,
      swellHeight,
      windDirection,
      visibility,
      notes
    };
    this.items.push(item);
    this.persistData();
    this.numItems++;
    return item;

  };

  getLastItem() {
    const last = this.items.length - 1;
    const newItem = this.items[last];
    newItem.start = newItem.end;
    newItem.end = this.incrementEnd(newItem.end);
    this.updated = true;
    return newItem;
  }

  updateItem(id, start, end, seaState, SwellHeight, windDirection, visibility, notes) {
    const index = this.items.findIndex(el => el.id === id);
    this.items[index].zone = this.isBST();
    this.items[index].start = start;
    this.items[index].end = end;
    this.items[index].seaSate = seaState;
    this.items[index].swellHeight = SwellHeight;
    this.items[index].windDirection = windDirection;
    this.items[index].vivibility = visibility;
    this.items[index].notes = notes;
    this.persistEnvData();
    return this.items[index];
  };

  deleteItem(id) {
    const index = this.items.findIndex(el => el.id === id);
    this.items.splice(index, 1);
  };

  persistData() {
    localStorage.setItem('environment', JSON.stringify(this.items));
  }

  readStorage() {
    const storage = JSON.parse(localStorage.getItem('environment'));
    if (storage) {
      this.items = storage;
      this.numItems = storage.length;
    }
  }

   clearItems(){
    this.items = [];
    this.numItems = 1;
  }

  removeStorage() {
    localStorage.removeItem('environment');
    this.clearItems()
  }

  isBST() {
    const d = new Date();
    let start = "";
    let end = "";
    let isBST = "";
    // Loop over the 31 days of March for the current year
    for (let i = 31; i > 0; i--) {
      const march = new Date(d.getFullYear(), 2, i);
      if (march.getDay() === 0) {
        // last Sunday of March
        start = march;
        break;
      }
    }
    // Loop over the 31 days of October for the current year
    for (var i = 31; i > 0; i--) {
      var october = new Date(d.getFullYear(), 9, i);
      if (october.getDay() === 0) {
        // last Sunday of October
        end = october;
        break;
      }
    }

    if (d < start || d > end) {
      isBST = "GMT";
    } else {
      isBST = "BST";
    }
    return isBST;
  }

  incrementEnd(time) {
  const splitTime = time.split(":");
  const  startHour = parseInt(splitTime[0]);
  const startMin = parseInt(splitTime[1]);
  let min = startMin;
  let hour = startHour;

  if(startMin > 44){
    min = 15-(60-startMin);
    hour = this.checkHour(startHour);
    
  } else {
    min = startMin+15;
    hour = startHour;
  }
  
  if(hour<10){
    hour = `0${hour}`;
  }
  if(min<10){
    min = `0${min}`;
  }
  return `${hour}:${min}`;
};

checkHour(hour) {
  if(hour ===23){
    return 0;
  } else {
    return hour+1;
  }
};
}