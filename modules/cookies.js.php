export default class Cookie {
  constructor(name, value, path = '/colori', maxAge = null) {
    this.name = name;
    this.value = value;
    this.path = path;
    this.maxAge = maxAge;
    this.set();
  }

  set() {
    const expiration = this.maxAge ? `max-age=${this.maxAge}` 
                                   : `expires=${new Date(2147483647000).toUTCString()}`;
    document.cookie = `${this.name}=${this.value}; path=${this.path}; ${expiration};`
  }

  delete() {
    this.value = '';
    this.maxAge = -1;
    this.set();
  }

  static get(name) {
    const match = document.cookie.match(new RegExp(`(?:^|; )${name}=([^;]*)`));
    return (match !== null) ? match[1] : null;
  }
}