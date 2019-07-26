export default class Couleur {
  constructor(couleur) {
    if (couleur instanceof Couleur)
      throw 'Already an instance of Couleur';
    else if (typeof couleur != 'string')
      throw 'Couleur objects can only be created from a String';

    const format = Couleur.matchSyntax(couleur.trim());

    if (['HEX', 'HEXA'].includes(format.id))
    {
      let r, g, b, a;
      r = format.data[1];
      r = (r.length == 1) ? r.repeat(2) : r;
      g = format.data[2];
      g = (g.length == 1) ? g.repeat(2) : g;
      b = format.data[3];
      b = (b.length == 1) ? b.repeat(2) : b;
      if (format.id === 'HEXA')
      {
        a = format.data[4];
        a = (a.length == 1) ? a.repeat(2) : a;
      }
      else
        a = 'ff';
      this.r = parseInt(r, 16) / 255;
      this.g = parseInt(g, 16) / 255;
      this.b = parseInt(b, 16) / 255;
      this.a = parseInt(a, 16) / 255;
      this.rgb2hsl();
      this.hsl2hwb();
    }
    else if (['RGB', 'RGBA'].includes(format.id))
    {
      let r, g, b, a;
      r = Couleur.parse(format.data[1]);
      g = Couleur.parse(format.data[2]);
      b = Couleur.parse(format.data[3]);
      if (format.id === 'RGBA')
        a = Couleur.parse(format.data[4], 'alpha');
      else
        a = 1;
      this.r = r;
      this.g = g;
      this.b = b;
      this.a = a;
      this.rgb2hsl();
      this.hsl2hwb();
    }
    else if (['HSL', 'HSLA'].includes(format.id))
    {
      let h, s, l, a;
      h = Couleur.parse(format.data[1], 'angle');
      s = Couleur.parse(format.data[2]);
      l = Couleur.parse(format.data[3]);
      if (format.id === 'HSLA')
        a = Couleur.parse(format.data[4], 'alpha');
      else
        a = 1;
      this.h = h;
      this.s = s;
      this.l = l;
      this.a = a;
      this.hsl2rgb();
      this.hsl2hwb();
    }
    else if (['HWB', 'HWBA'].includes(format.id))
    {
      let h, w, bk, a;
      h = Couleur.parse(format.data[1], 'angle');
      w = Couleur.parse(format.data[2]);
      bk = Couleur.parse(format.data[3]);
      if (format.id === 'HWBA')
        a = Couleur.parse(format.data[4], 'alpha');
      else
        a = 1;
      this.h = h;
      this.w = w;
      this.bk = bk;
      this.a = a;
      this.hwb2hsl();
      this.hsl2rgb();
    }

    if (this.a == 1)
    {
      let _name = Object.keys(Couleur.couleursNommees).find(k => (Couleur.couleursNommees[k] == this.hex.replace('#', '') || Couleur.couleursNommees[k] == this.hexa.replace('#', '')));
      if (typeof _name === 'undefined')
        this.name = null;
      else
        this.name = _name;
    }
    else
      this.name = null;
  }

  static matchSyntax(couleur) {
    let resultat = false;
    boucle:
    for (const format of Couleur.formats) {
      for (const [k, syntaxe] of format.syntaxes.entries()) {
        const result = couleur.match(syntaxe);
        if (result != null && result[0] === couleur)
        {
          if (format.id != 'NAME')
            resultat = {
              id: format.id,
              syntaxe: k,
              data: result
            };
          else if (format.id == 'NAME' && couleur.toLowerCase() in Couleur.couleursNommees)
            resultat = Couleur.matchSyntax('#' + Couleur.couleursNommees[couleur.toLowerCase()]);
          break boucle;
        }
      }
    }

    if (resultat)
      return resultat;
    else
      throw 'Invalid format';
  }

  // Parses a number / percentage / angle into correct format to store it
  static parse(n, type = null, log = false) {
    let _n = parseFloat(n);
    let logged;
    let error;

    // Si n est un pourcentage
    if (new RegExp('^' + Couleur.vPer + '$').test(n))
    {
      _n = _n / 100;
      if (_n < 0)
        _n = 0;
      else if (_n > 1)
        _n = 1;
      logged = '%';
    }
    // Si n est un nombre (entre 0 et 255)
    else if (new RegExp('^' + Couleur.vNum + '$').test(n) && type != 'angle' && type != 'alpha')
    {
      _n = _n / 255;
      if (_n < 0)
        _n = 0;
      else if (_n > 1)
        _n = 1;
      logged = '255';
    }
    // Si n est un nombre (entre 0 et 1)
    else if (new RegExp('^' + Couleur.vNum + '$').test(n) && type == 'alpha')
    {
      if (_n < 0)
        _n = 0;
      else if (_n > 1)
        _n = 1;
      logged = 'alpha';
    }
    // Si n est un angle (donc un nombre ou un nombre avec unité d'angle)
    else if (new RegExp('^' + Couleur.vAng + '$').test(n) && type == 'angle')
    {
      // en deg
      if (n.slice(-3) == 'deg' || new RegExp('^' + Couleur.vNum + '$').test(n))
      {
        while (_n < 0)
          _n += 360;
        while(_n > 360)
          _n -= 360;
        _n = _n / 360;
        logged = 'deg';
      }
      // en grad
      else if (n.slice(-4) == 'grad')
      {
        while (_n < 0)
          _n += 400;
        while(_n > 400)
          _n -= 400;
        _n = _n / 400;
        logged = 'grad'
      }
      // en rad
      else if (n.slice(-3) == 'rad')
      {
        _n = _n * 180 / Math.PI;
        while (_n < 0)
          _n += 360;
        while(_n > 360)
          _n -= 360;
        _n = _n / 360;
        logged = 'rad';
      }
      // en turn
      else if (n.slice(-4) == 'turn')
      {
        while (_n < 0)
          _n += 1;
        while (_n > 1)
          _n -= 1;
        logged = 'turn';
      }
      else
      {
        _n = null;
        error = 'Invalid angle format';
      }
    }
    else
    {
      _n = null;
      error = 'Invalid value format';
    }

    if (_n == null)
      throw error;
    
    if (log)
      return [_n, logged];
    else
      return _n;
  }

  // Ajoute un zéro avant une chaîne d'un seul caractère
  static pad(s) {
    return (s.length < 2) ? `0${s}` : s;
  }

  get hexa() {
    const r = Couleur.pad(Math.round(this.r * 255).toString(16));
    const g = Couleur.pad(Math.round(this.g * 255).toString(16));
    const b = Couleur.pad(Math.round(this.b * 255).toString(16));
    const a = Couleur.pad(Math.round(this.a * 255).toString(16));
    return `#${r}${g}${b}${a}`;
  }

  get hex() {
    if (this.a < 1)
      return this.hexa;
    const r = Couleur.pad(Math.round(this.r * 255).toString(16));
    const g = Couleur.pad(Math.round(this.g * 255).toString(16));
    const b = Couleur.pad(Math.round(this.b * 255).toString(16));
    return `#${r}${g}${b}`;
  }

  get rgba() {
    const r = Math.round(this.r * 255);
    const g = Math.round(this.g * 255);
    const b = Math.round(this.b * 255);
    const a = Math.round(this.a * 100) / 100;
    return `rgba(${r}, ${g}, ${b}, ${a})`;
  }

  get rgb() {
    const r = Math.round(this.r * 255);
    const g = Math.round(this.g * 255);
    const b = Math.round(this.b * 255);
    const a = Math.round(this.a * 100) / 100;
    if (this.a < 1)
      return `rgb(${r}, ${g}, ${b}, ${a})`;
    else
      return `rgb(${r}, ${g}, ${b})`;
  }

  get hsla() {
    const h = Math.round(this.h * 360);
    const s = Math.round(this.s * 100);
    const l = Math.round(this.l * 100);
    const a = Math.round(this.a * 100) / 100;
    return `hsla(${h}, ${s}%, ${l}%, ${a})`;
  }

  get hsl() {
    const h = Math.round(this.h * 360);
    const s = Math.round(this.s * 100);
    const l = Math.round(this.l * 100);
    const a = Math.round(this.a * 100) / 100;
    if (this.a < 1)
      return `hsl(${h}, ${s}%, ${l}%, ${a})`;
    else
      return `hsl(${h}, ${s}%, ${l}%)`;
  }

  get hwba() {
    const h = Math.round(this.h * 360);
    const w = Math.round(this.w * 100);
    const bk = Math.round(this.bk * 100);
    const a = Math.round(this.a * 100) / 100;
    return `hwb(${h} ${w}% ${bk}% / ${a})`;
  }

  get hwb() {
    const h = Math.round(this.h * 360);
    const w = Math.round(this.w * 100);
    const bk = Math.round(this.bk * 100);
    const a = Math.round(this.a * 100) / 100;
    if (this.a < 1)
      return `hwb(${h} ${w}% ${bk}% / ${a})`;
    else
      return `hwb(${h} ${w}% ${bk}%)`;
  }

  rgb2hsl() {
    let r = this.r;
    let g = this.g;
    let b = this.b;

    let h, s, l;

    const max = Math.max(r, g, b);
    const min = Math.min(r, g, b);
    const chroma = max - min;

    // Luminosité (l)
    l = (max + min) / 2;

    // Si chrome == 0, la couleur est grise
    if (chroma == 0)
      h = 0;
    // Sinon, on calcule la teinte h
    // (source des maths : https://en.wikipedia.org/wiki/HSL_and_HSV#General_approach)
    else
    {
      switch (max) {
        case r:
          h = (g - b) / chroma;
          break;
        case g:
          h = (b - r) / chroma + 2;
          break;
        case b:
          h = (r - g) / chroma + 4;
          break;
      }
      h = 60 * h;
      if (h < 0)
        h += 360;
    }

    if (l == 0 || l == 1)
      s = 0;
    else if (l <= 0.5)
      s = chroma / (2 * l);
    else
      s = chroma / (2 - 2 * l);

    this.h = h / 360;
    this.s = s;
    this.l = l;
  }

  hsl2rgb() {
    // source des maths : https://en.wikipedia.org/wiki/HSL_and_HSV#HSL_to_RGB_alternative
    let h = this.h * 360;
    let s = this.s;
    let l = this.l;
    let r, g, b;

    const m = s * Math.min(l, 1 - l);
    const k = n => (n + h / 30) % 12;
    const f = n => l - m * Math.max(Math.min(k(n) - 3, 9 - k(n), 1), -1);

    r = f(0);
    g = f(8);
    b = f(4);

    this.r = r;
    this.g = g;
    this.b = b;
  }

  hsl2hwb() {
    let s = this.s;
    let l = this.l;
    let _s, v, w, bk;

    v = l + s * Math.min(l, 1 - l);
    if (v == 0)
      _s = 0;
    else
      _s = 2 - 2 * l / v;

    w = (1 - _s) * v;
    bk = 1 - v;

    this.w = w;
    this.bk = bk;
  }

  hwb2hsl() {
    let w = this.w;
    let bk = this.bk;
    let _s, v, s, l;

    if (w + bk > 1)
    {
      w = w / (w + bk);
      b = b / (w + bk);
    }

    v = 1 - bk;
    _s = 1 - w / v;

    l = v - v * _s / 2;
    if (l == 0 || l == 1)
      s = 0;
    else
      s = (v - l) / Math.min(l, 1 - l);

    this.s = s;
    this.l = l;
  }

  // Fusionne une couleur transparente et une couleur opaque
  static blend(couleur1, couleur2) {
    if (!(couleur1 instanceof Couleur) || !(couleur2 instanceof Couleur))
      throw 'Arguments should be two instances of the Couleur class';
    let background, overlay;
    if (couleur1.a < 1 && couleur2.a < 1)
      throw 'At least one of the arguments needs to be an opaque Couleur';
    else if (couleur1.a < 1 && couleur2.a == 1)
    {
      background = couleur2;
      overlay = couleur1;
    }
    else
    {
      background = couleur1;
      overlay = couleur2;
    }
    const r = Math.round(255 * (overlay.a * overlay.r + (1 - overlay.a) * background.r));
    const g = Math.round(255 * (overlay.a * overlay.g + (1 - overlay.a) * background.g));
    const b = Math.round(255 * (overlay.a * overlay.b + (1 - overlay.a) * background.b));
    return new Couleur(`rgb(${r}, ${g}, ${b})`);
  }

  // Calcule la luminance d'une couleur
  // (source des maths : https://www.w3.org/TR/2008/REC-WCAG20-20081211/#relativeluminancedef)
  luminance() {
    let couleur = this;
    if (this.a < 1)
      throw 'Can\'t calculate luminance of transparent color';

    let arr = [couleur.r, couleur.g, couleur.b];
    for (let i = 0; i <= 2; i++) {
      let e = arr[i];
      if (e <= 0.03928)
        e = e / 12.92;
      else
        e = Math.pow((e + 0.055) / 1.055, 2.4);
      arr[i] = e;
    }

    return 0.2126 * arr[0] + 0.7152 * arr[1] + 0.0722 * arr[2];
  }

  // Calcule le contraste entre deux couleurs
  // (source des maths : https://www.w3.org/TR/2008/REC-WCAG20-20081211/#contrast-ratiodef)
  static contrast(couleur1, couleur2) {
    if (!(couleur1 instanceof Couleur) || !(couleur2 instanceof Couleur))
      throw 'Arguments should be two instances of the Couleur class';
    const L1 = couleur1.luminance();
    const L2 = couleur2.luminance();
    const Lmax = Math.max(L1, L2);
    const Lmin = Math.min(L1, L2);
    return (Lmax + 0.05) / (Lmin + 0.05);
  }

  // Vérifie si un texte blanc ou noir aurait meilleur contraste avec cette couleur
  contrastedText() {
    const L = this.luminance(); // luminance de la couleur entrée
    const LB = 1; // luminance du blanc
    const LN = 0; // luminance du noir
    const contrastes = [
      (L + 0.05) / (LN + 0.05), // contraste entre la couleur entrée et le noir
      (LB + 0.05) / (L + 0.05)  // contraste entre le blanc et la couleur entrée
    ];
    if (contrastes[0] > contrastes[1]) // contraste plus fort avec le noir
      return 'black'; // le texte noir ira mieux sur le fond de couleur
    else
      return 'white'; // le texte blanc ira mieux sur le fond de couleur
  }

  // Change une propriété d'une couleur
  change(propriete, valeur, remplace = false) {
    let nouvelleCouleur = new Couleur(`${this.rgb}`);
    const error = 'Incorrect value format for ' + propriete;

    if (['r', 'g', 'b'].includes(propriete))
    {
      let [_valeur, log] = Couleur.parse(valeur, null, true);
      if (log == '%')
        nouvelleCouleur[propriete] = Couleur.parse(((remplace ? 0 : nouvelleCouleur[propriete]) + _valeur) * 100 + '%');
      else if (log == '255')
        nouvelleCouleur[propriete] = Couleur.parse(((remplace ? 0 : nouvelleCouleur[propriete]) + _valeur) * 255);
      else
        throw error;
      return new Couleur(nouvelleCouleur.rgb);
    }
    else if (['h'].includes(propriete))
    {
      let [_valeur, log] = Couleur.parse(valeur, 'angle', true);
      if (log == 'deg')
        nouvelleCouleur[propriete] = Couleur.parse(((remplace ? 0 : nouvelleCouleur[propriete]) + _valeur) * 360 + 'deg', 'angle');
      else if (log == 'grad')
        nouvelleCouleur[propriete] = Couleur.parse(((remplace ? 0 : nouvelleCouleur[propriete]) + _valeur) * 400 + 'grad', 'angle');
      else if (log == 'rad')
        nouvelleCouleur[propriete] = Couleur.parse(((remplace ? 0 : nouvelleCouleur[propriete]) + _valeur) * 360 * (Math.PI / 180) + 'rad', 'angle');
      else if (log == 'turn')
        nouvelleCouleur[propriete] = Couleur.parse(((remplace ? 0 : nouvelleCouleur[propriete]) + _valeur) + 'turn', 'angle');
      else
        throw error;
      return new Couleur(nouvelleCouleur.hsl);
    }
    else if (['s', 'l'].includes(propriete))
    {
      let [_valeur, log] = Couleur.parse(valeur, null, true);
      if (log == '%')
        nouvelleCouleur[propriete] = Couleur.parse(((remplace ? 0 : nouvelleCouleur[propriete]) + _valeur) * 100 + '%');
      else
        throw error;
      return new Couleur(nouvelleCouleur.hsl);
    }
    else if (['w', 'bk'].includes(propriete))
    {
      let [_valeur, log] = Couleur.parse(valeur, null, true);
      if (log == '%')
        nouvelleCouleur[propriete] = Couleur.parse(((remplace ? 0 : nouvelleCouleur[propriete]) + _valeur) * 100 + '%');
      else
        throw error;
      return new Couleur(nouvelleCouleur.hwb);
    }
    else if (['a'].includes(propriete))
    {
      let [_valeur, log] = Couleur.parse(valeur, 'alpha', true);
      if (log == 'alpha')
        nouvelleCouleur[propriete] = Couleur.parse((remplace ? 0 : nouvelleCouleur[propriete]) + _valeur, 'alpha');
      else
        throw error;
      return new Couleur(nouvelleCouleur.hsl);
    }
    else
      return new Couleur(nouvelleCouleur.rgb);
  }

  static get formats() {
    return [
      {
        id: 'HEX',
        prefix: '#',
        separator: '',
        suffix: '',
        syntaxes: [
          // #abc ou #ABC
          /^#([a-fA-F0-9]{1})([a-fA-F0-9]{1})([a-fA-F0-9]{1})$/,
          // #aabbcc ou #AABBCC
          /^#([a-fA-F0-9]{2})([a-fA-F0-9]{2})([a-fA-F0-9]{2})$/
        ]
      }, {
        id: 'HEXA',
        prefix: '#',
        separator: '',
        suffix: '',
        syntaxes: [
          // #abcd ou #ABCD
          /^#([a-fA-F0-9]{1})([a-fA-F0-9]{1})([a-fA-F0-9]{1})([a-fA-F0-9]{1})$/,
          // #aabbccdd ou #AABBCCDD
          /^#([a-fA-F0-9]{2})([a-fA-F0-9]{2})([a-fA-F0-9]{2})([a-fA-F0-9]{2})$/
        ]
      }, {
        id: 'RGB',
        prefix: 'rgb(',
        separator: ', ',
        suffix: ')',
        syntaxes: [
          // rgb(255, 255, 255) ou rgb(255,255,255)
          new RegExp(`^rgba?\\((${Couleur.vNum}), ?(${Couleur.vNum}), ?(${Couleur.vNum})\\)?$`),
          // rgb(100%, 100%, 100%) ou rgb(100%,100%,100%)
          new RegExp(`^rgba?\\((${Couleur.vPer}), ?(${Couleur.vPer}), ?(${Couleur.vPer})\\)?$`),
          // rgb(255 255 255)
          new RegExp(`^rgba?\\((${Couleur.vNum}) (${Couleur.vNum}) (${Couleur.vNum})\\)?$`),
          // rgb(100% 100% 100%)
          new RegExp(`^rgba?\\((${Couleur.vPer}) (${Couleur.vPer}) (${Couleur.vPer})\\)?$`)
        ]
      }, {
        id: 'RGBA',
        prefix: 'rgba(',
        separator: ', ',
        suffix: ')',
        syntaxes: [
          // rgba(255, 255, 255, .5) ou rgba(255, 255, 255, 50%) (espaces optionnels)
          new RegExp(`^rgba?\\((${Couleur.vNum}), ?(${Couleur.vNum}), ?(${Couleur.vNum}), ?(${Couleur.vNP})\\)?$`),
          // rgba(100%, 100%, 100%, .5) ou rgba(100%, 100%, 100%, 50%) (espaces optionnels)
          new RegExp(`^rgba?\\((${Couleur.vPer}), ?(${Couleur.vPer}), ?(${Couleur.vPer}), ?(${Couleur.vNP})\\)?$`),
          // rgba(255 255 255 / 50%) ou rgba(255 255 255 / .5)
          new RegExp(`^rgba?\\((${Couleur.vNum}) (${Couleur.vNum}) (${Couleur.vNum}) ?\\/ ?(${Couleur.vNP})\\)?$`),
          // rgba(100% 100% 100% / 50%) ou rgba(100% 100% 100% / .5)
          new RegExp(`^rgba?\\((${Couleur.vPer}) (${Couleur.vPer}) (${Couleur.vPer}) ?\\/ ?(${Couleur.vNP})\\)?$`)
        ]
      }, {
        id: 'HSL',
        prefix: 'hsl(',
        separator: ', ',
        suffix: ')',
        syntaxes: [
          // hsl(<angle>, 100%, 100%)
          new RegExp(`^hsla?\\((${Couleur.vAng}), ?(${Couleur.vPer}), ?(${Couleur.vPer})\\)?$`),
          // hsl(<angle> 100% 100%)
          new RegExp(`^hsla?\\((${Couleur.vAng}) (${Couleur.vPer}) (${Couleur.vPer})\\)?$`)
        ]
      }, {
        id: 'HSLA',
        prefix: 'hsla(',
        separator: ', ',
        suffix: ')',
        syntaxes: [
          // hsla(<angle>, 100%, 100%, .5) ou hsla(<angle>, 100%, 100%, 50%)
          new RegExp(`^hsla?\\((${Couleur.vAng}), ?(${Couleur.vPer}), ?(${Couleur.vPer}), ?(${Couleur.vNP})\\)?$`),
          // hsla(<angle> 100% 100% / .5) ou hsl(<angle> 100% 100% / 50%)
          new RegExp(`^hsla?\\((${Couleur.vAng}) (${Couleur.vPer}) (${Couleur.vPer}) ?\\/ ?(${Couleur.vNP})\\)?$`)
        ]
      }, {
        id: 'HWB',
        prefix: 'hwb(',
        separator: ', ',
        suffix: ')',
        syntaxes: [
          // hwb(<angle>, 100%, 100%)
          new RegExp(`^hwba?\\((${Couleur.vAng}), ?(${Couleur.vPer}), ?(${Couleur.vPer})\\)?$`),
          // hwb(<angle> 100% 100%)
          new RegExp(`^hwba?\\((${Couleur.vAng}) (${Couleur.vPer}) (${Couleur.vPer})\\)?$`)
        ]
      }, {
        id: 'HWBA',
        prefix: 'hwba(',
        separator: ', ',
        suffix: ')',
        syntaxes: [
          // hwba(<angle>, 100%, 100%, .5) ou hsla(<angle>, 100%, 100%, 50%)
          new RegExp(`^hwba?\\((${Couleur.vAng}), ?(${Couleur.vPer}), ?(${Couleur.vPer}), ?(${Couleur.vNP})\\)?$`),
          // hwba(<angle> 100% 100% / .5) ou hsl(<angle> 100% 100% / 50%)
          new RegExp(`^hwba?\\((${Couleur.vAng}) (${Couleur.vPer}) (${Couleur.vPer}) ?\\/ ?(${Couleur.vNP})\\)?$`)
        ]
      }, {
        id: 'NAME',
        prefix: '',
        separator: '',
        suffix: '',
        syntaxes: [
          // white ou WHITE ou WhiTe
          /^[A-Za-z]+$/
        ]
      }
    ];
  }

  // Valid CSS values RegExp string (according to https://www.w3.org/TR/css-syntax/#typedef-number-token)
  static get vNum() { return '(?:\\-|\\+)?(?:[0-9]+(?:\\.[0-9]+)?|\\.[0-9]+)(?:(?:e|E)(?:\\-|\\+)?[0-9]+)?'; } // number (r, g, b)
  static get vPer() { return Couleur.vNum + '%'; } // percent (r, g, b, s, l, w, bk)
  static get vNP() { return Couleur.vNum + '%?'; } // number or percent (a)
  static get vAng() { return Couleur.vNum + '(?:deg|grad|rad|turn)?'; } // angle (h)

  static get couleursNommees() {
    return {
      transparent: '00000000',
      aliceblue: 'f0f8ff',
      antiquewhite: 'faebd7',
      aqua: '00ffff',
      aquamarine: '7fffd4',
      azure: 'f0ffff',
      beige: 'f5f5dc',
      bisque: 'ffe4c4',
      black: '000000',
      blanchedalmond: 'ffebcd',
      blue: '0000ff',
      blueviolet: '8a2be2',
      brown: 'a52a2a',
      burlywood: 'deb887',
      cadetblue: '5f9ea0',
      chartreuse: '7fff00',
      chocolate: 'd2691e',
      coral: 'ff7f50',
      cornflowerblue: '6495ed',
      cornsilk: 'fff8dc',
      crimson: 'dc143c',
      cyan: '00ffff',
      darkblue: '00008b',
      darkcyan: '008b8b',
      darkgoldenrod: 'b8860b',
      darkgray: 'a9a9a9',
      darkgrey: 'a9a9a9',
      darkgreen: '006400',
      darkkhaki: 'bdb76b',
      darkmagenta: '8b008b',
      darkolivegreen: '556b2f',
      darkorange: 'ff8c00',
      darkorchid: '9932cc',
      darkred: '8b0000',
      darksalmon: 'e9967a',
      darkseagreen: '8fbc8f',
      darkslateblue: '483d8b',
      darkslategray: '2f4f4f',
      darkslategrey: '2f4f4f',
      darkturquoise: '00ced1',
      darkviolet: '9400d3',
      deeppink: 'ff1493',
      deepskyblue: '00bfff',
      dimgray: '696969',
      dimgrey: '696969',
      dodgerblue: '1e90ff',
      firebrick: 'b22222',
      floralwhite: 'fffaf0',
      forestgreen: '228b22',
      fuchsia: 'ff00ff',
      gainsboro: 'dcdcdc',
      ghostwhite: 'f8f8ff',
      gold: 'ffd700',
      goldenrod: 'daa520',
      gray: '808080',
      grey: '808080',
      green: '008000',
      greenyellow: 'adff2f',
      honeydew: 'f0fff0',
      hotpink: 'ff69b4',
      indianred: 'cd5c5c',
      indigo: '4b0082',
      ivory: 'fffff0',
      khaki: 'f0e68c',
      lavender: 'e6e6fa',
      lavenderblush: 'fff0f5',
      lawngreen: '7cfc00',
      lemonchiffon: 'fffacd',
      lightblue: 'add8e6',
      lightcoral: 'f08080',
      lightcyan: 'e0ffff',
      lightgoldenrodyellow: 'fafad2',
      lightgray: 'd3d3d3',
      lightgrey: 'd3d3d3',
      lightgreen: '90ee90',
      lightpink: 'ffb6c1',
      lightsalmon: 'ffa07a',
      lightseagreen: '20b2aa',
      lightskyblue: '87cefa',
      lightslategray: '778899',
      lightslategrey: '778899',
      lightsteelblue: 'b0c4de',
      lightyellow: 'ffffe0',
      lime: '00ff00',
      limegreen: '32cd32',
      linen: 'faf0e6',
      magenta: 'ff00ff',
      maroon: '800000',
      mediumaquamarine: '66cdaa',
      mediumblue: '0000cd',
      mediumorchid: 'ba55d3',
      mediumpurple: '9370d8',
      mediumseagreen: '3cb371',
      mediumslateblue: '7b68ee',
      mediumspringgreen: '00fa9a',
      mediumturquoise: '48d1cc',
      mediumvioletred: 'c71585',
      midnightblue: '191970',
      mintcream: 'f5fffa',
      mistyrose: 'ffe4e1',
      moccasin: 'ffe4b5',
      navajowhite: 'ffdead',
      navy: '000080',
      oldlace: 'fdf5e6',
      olive: '808000',
      olivedrab: '6b8e23',
      orange: 'ffa500',
      orangered: 'ff4500',
      orchid: 'da70d6',
      palegoldenrod: 'eee8aa',
      palegreen: '98fb98',
      paleturquoise: 'afeeee',
      palevioletred: 'd87093',
      papayawhip: 'ffefd5',
      peachpuff: 'ffdab9',
      peru: 'cd853f',
      pink: 'ffc0cb',
      plum: 'dda0dd',
      powderblue: 'b0e0e6',
      purple: '800080',
      rebeccapurple: '663399',
      red: 'ff0000',
      rosybrown: 'bc8f8f',
      royalblue: '4169e1',
      saddlebrown: '8b4513',
      salmon: 'fa8072',
      sandybrown: 'f4a460',
      seagreen: '2e8b57',
      seashell: 'fff5ee',
      sienna: 'a0522d',
      silver: 'c0c0c0',
      skyblue: '87ceeb',
      slateblue: '6a5acd',
      slategray: '708090',
      slategrey: '708090',
      snow: 'fffafa',
      springgreen: '00ff7f',
      steelblue: '4682b4',
      tan: 'd2b48c',
      teal: '008080',
      thistle: 'd8bfd8',
      tomato: 'ff6347',
      turquoise: '40e0d0',
      violet: 'ee82ee',
      wheat: 'f5deb3',
      white: 'ffffff',
      whitesmoke: 'f5f5f5',
      yellow: 'ffff00',
      yellowgreen: '9acd32'
    };
  }
}