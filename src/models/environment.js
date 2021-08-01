export default class Environment {
  constructor() {
    this.items = [];
    this.numItems = 1;
  };

  addItem(start, end, seaState, swellHeight, windDirection, visibility, notes) {
    const item = {
      id: this.numItems,
      zone: this.isBST(),
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

  getlastItem() {
    const last = this.items.length - 1;
    const newItem = this.items[last];
    newItem.start = newItem.end;
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
      this.numItems = storage.length + 1;
    }
  }

  removeStorage() {
    localStorage.removeItem('environment');
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
      if (october.getDay() == 0) {
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
}