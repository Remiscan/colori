export default class Couleur {
  constructor(couleur) {
    if (couleur instanceof Couleur)
      throw 'Already an instance of Couleur';
    else if (typeof couleur != 'string')
      throw 'Couleur objects can only be created from a String';

    this.r = null;
    this.g = null;
    this.b = null;
    this.h = null;
    this.s = null;
    this.l = null;
    this.w = null;
    this.bk = null;
    this.a = null;
    this.ciel = null;
    this.ciea = null;
    this.cieb = null;
    this.ciec = null;
    this.cieh = null;
    this.name = null;

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
      this.r = Couleur.pRound(parseInt(r, 16) / 255);
      this.g = Couleur.pRound(parseInt(g, 16) / 255);
      this.b = Couleur.pRound(parseInt(b, 16) / 255);
      this.a = Couleur.pRound(parseInt(a, 16) / 255);
      this.rgb2hsl();
      this.hsl2hwb();
      this.rgb2lab();
      this.lab2lch();
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
      this.r = Couleur.pRound(r);
      this.g = Couleur.pRound(g);
      this.b = Couleur.pRound(b);
      this.a = Couleur.pRound(a);
      this.rgb2hsl();
      this.hsl2hwb();
      this.rgb2lab();
      this.lab2lch();
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
      this.h = Couleur.pRound(h);
      this.s = Couleur.pRound(s);
      this.l = Couleur.pRound(l);
      this.a = Couleur.pRound(a);
      this.hsl2rgb();
      this.hsl2hwb();
      this.rgb2lab();
      this.lab2lch();
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
      this.h = Couleur.pRound(h);
      this.w = Couleur.pRound(w);
      this.bk = Couleur.pRound(bk);
      this.a = Couleur.pRound(a);
      this.hwb2hsl();
      this.hsl2rgb();
      this.rgb2lab();
      this.lab2lch();
    }
    else if (['LAB'].includes(format.id))
    {
      let ciel, ciea, cieb, a;
      ciel = Couleur.parse(format.data[1], 'cie');
      ciea = format.data[2];
      cieb = format.data[3];
      if (typeof format.data[4] !== 'undefined')
        a = Couleur.parse(format.data[4], 'alpha');
      else
        a = 1;
      this.ciel = Couleur.pRound(ciel);
      this.ciea = Couleur.pRound(ciea);
      this.cieb = Couleur.pRound(cieb);
      this.a = Couleur.pRound(a);
      this.lab2lch();
      this.lab2rgb();
      this.rgb2hsl();
      this.hsl2hwb();
    }
    else if (['LCH'].includes(format.id))
    {
      let ciel, ciec, cieh, a;
      ciel = Couleur.parse(format.data[1], 'cie');
      ciec = Couleur.parse(format.data[2], 'cie');
      cieh = Couleur.parse(format.data[3], 'angle');
      if (typeof format.data[4] !== 'undefined')
        a = Couleur.parse(format.data[4], 'alpha');
      else
        a = 1;
      this.ciel = Couleur.pRound(ciel);
      this.ciec = Couleur.pRound(ciec);
      this.cieh = Couleur.pRound(cieh);
      this.a = Couleur.pRound(a);
      this.lch2lab();
      this.lab2rgb();
      this.rgb2hsl();
      this.hsl2hwb();
    }

    if (this.a == 1)
    {
      const allNames = Couleur.couleursNommees;
      const hex6 = this.hex.slice(1);
      const _name = Object.keys(allNames).find(k => (allNames[k] == hex6));
      if (typeof _name === 'undefined')
        this.name = null;
      else
        this.name = _name;
    }
    else
    {
      if (this.a == 0) this.name = 'transparent';
      else this.name = null;
    }
  }

  static matchSyntax(couleur) {
    const tri = couleur.slice(0, 3);
    const allFormats = Couleur.formats;
    let formats;
    if (tri.slice(0, 1) == '#')
      formats = [allFormats[0], allFormats[1]];
    else if (tri == 'rgb')
      formats = [allFormats[2], allFormats[3]];
    else if (tri == 'hsl')
      formats = [allFormats[4], allFormats[5]];
    else if (tri == 'hwb')
      formats = [allFormats[6], allFormats[7]];
    else if (tri == 'lab')
      formats = [allFormats[8]];
    else if (tri == 'lch')
      formats = [allFormats[9]];
    else
      formats = [allFormats[10]];
    
    let resultat = false;
    boucle:
    for (const format of formats) {
      for (const [k, syntaxe] of format.syntaxes.entries()) {
        const result = couleur.match(syntaxe);
        if (result != null && result[0] === couleur) {
          const allNames = Couleur.couleursNommees;
          if (format.id != 'NAME')
            resultat = {
              id: format.id,
              syntaxe: k,
              data: result
            };
          else if (format.id == 'NAME' && couleur.toLowerCase() in allNames)
            resultat = Couleur.matchSyntax('#' + allNames[couleur.toLowerCase()]);
          break boucle;
        }
      }
    }

    if (resultat) return resultat;
    else          throw 'Invalid format';
  }

  // Checks if a variable is a Couleur object, or if it can be made into one
  static check(color) {
    if (color instanceof Couleur) return color;
    try {
      return new Couleur(color);
    }
    catch (error) {
      throw 'Argument should be an instance of the Couleur class, or a valid color string';
    }
  }

  // Parses a number / percentage / angle into correct format to store it
  static parse(n, type = null, log = false) {
    let _n = parseFloat(n);
    let logged;
    let error;

    if (type == 'arbitrary')
    {
      // If n is any percentage
      if (new RegExp('^' + Couleur.vPer + '$').test(n)) {
        _n = _n / 100;
        logged = 'arbitrary%';
      }
      // If n is any number
      else if (new RegExp('^' + Couleur.vNum + '$').test(n)) {
        logged = 'arbitraryN';
      }
    }
    // If n is a percentage between 0 and 100%
    else if (new RegExp('^' + Couleur.vPer + '$').test(n))
    {
      _n = _n / 100;
      if (_n < 0)
        _n = 0;
      else if (_n > 1 && type != 'cie')
        _n = 1;
      logged = '%';
    }
    // If n is a number between 0 and 255
    else if (new RegExp('^' + Couleur.vNum + '$').test(n) && type != 'angle' && type != 'alpha' && type != 'cie')
    {
      _n = _n / 255;
      if (_n < 0)
        _n = 0;
      else if (_n > 1)
        _n = 1;
      logged = '255';
    }
    // If n is a number between 0 and 1
    else if (new RegExp('^' + Couleur.vNum + '$').test(n) && type == 'alpha')
    {
      if (_n < 0)
        _n = 0;
      else if (_n > 1)
        _n = 1;
      logged = 'alpha';
    }
    // If n is a number between 0 and +Infinity
    else if (new RegExp('^' + Couleur.vNum + '$').test(n) && type == 'cie')
    {
      if (_n < 0)
        _n = 0;
      logged = 'cieC';
    }
    // If n is an angle (i.e. a number with or without an angle unit)
    else if (new RegExp('^' + Couleur.vAng + '$').test(n) && type == 'angle')
    {
      // deg
      if (String(n).slice(-3) == 'deg' || new RegExp('^' + Couleur.vNum + '$').test(n))
      {
        while (_n < 0)
          _n += 360;
        while(_n > 360)
          _n -= 360;
        _n = _n / 360;
        logged = 'deg';
      }
      // grad
      else if (String(n).slice(-4) == 'grad')
      {
        while (_n < 0)
          _n += 400;
        while(_n > 400)
          _n -= 400;
        _n = _n / 400;
        logged = 'grad'
      }
      // rad
      else if (String(n).slice(-3) == 'rad')
      {
        _n = _n * 180 / Math.PI;
        while (_n < 0)
          _n += 360;
        while(_n > 360)
          _n -= 360;
        _n = _n / 360;
        logged = 'rad';
      }
      // turn
      else if (String(n).slice(-4) == 'turn')
      {
        while (_n < 0)
          _n += 1;
        while (_n > 1)
          _n -= 1;
        logged = 'turn';
      }
      else
      {
        error = 'Invalid angle format';
      }
    }
    else
    {
      error = 'Invalid value format';
    }

    if (error) {
      console.error(error, n, _n, type, log);
      throw error;
    }
    
    if (log)
      return [_n, logged];
    else
      return _n;
  }

  // Adds a zero before a string of length 1
  static pad(s) {
    return (s.length < 2) ? `0${s}` : s;
  }

  // Returns a float precise to the nth decimal
  static pRound(_x, n = 5) {
    let x = (typeof _x == 'number') ? _x : Number(_x);
    return Number(parseFloat(x.toPrecision(n)));
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

  get lab() {
    const ciel = Math.round(this.ciel * 100);
    const ciea = Math.round(this.ciea);
    const cieb = Math.round(this.cieb);
    const a = Math.round(this.a * 100) / 100;
    if (this.a < 1)
      return `lab(${ciel}% ${ciea} ${cieb} / ${a})`;
    else
      return `lab(${ciel}% ${ciea} ${cieb})`;
  }

  get laba() {
    const ciel = Math.round(this.ciel * 100);
    const ciea = Math.round(this.ciea);
    const cieb = Math.round(this.cieb);
    const a = Math.round(this.a * 100) / 100;
    return `lab(${ciel}% ${ciea} ${cieb} / ${a})`;
  }

  get lch() {
    const ciel = Math.round(this.ciel * 100);
    const ciec = Math.round(this.ciec);
    const cieh = Math.round(this.cieh * 360);
    const a = Math.round(this.a * 100) / 100;
    if (this.a < 1)
      return `lch(${ciel}% ${ciec} ${cieh} / ${a})`;
    else
      return `lch(${ciel}% ${ciec} ${cieh})`;
  }

  get lcha() {
    const ciel = Math.round(this.ciel * 100);
    const ciec = Math.round(this.ciec);
    const cieh = Math.round(this.cieh * 360);
    const a = Math.round(this.a * 100) / 100;
    return `lch(${ciel}% ${ciec} ${cieh} / ${a})`;
  }

  rgb2hsl() {
    let r = this.r;
    let g = this.g;
    let b = this.b;

    let h, s, l;

    const max = Math.max(r, g, b);
    const min = Math.min(r, g, b);
    const chroma = max - min;

    // Luminosity (l)
    l = (max + min) / 2;

    // If chroma == 0, the color is grey
    if (chroma == 0)
      h = 0;
    // If not, we calculate the hue h
    // (source of the math: https://en.wikipedia.org/wiki/HSL_and_HSV#General_approach)
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

    this.h = Couleur.pRound(h / 360);
    this.s = Couleur.pRound(s);
    this.l = Couleur.pRound(l);
  }

  hsl2rgb() {
    // source of the math: https://en.wikipedia.org/wiki/HSL_and_HSV#HSL_to_RGB_alternative
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

    this.r = Couleur.pRound(r);
    this.g = Couleur.pRound(g);
    this.b = Couleur.pRound(b);
  }

  hsl2hwb() {
    // Source of the math: https://en.wikipedia.org/wiki/HSL_and_HSV#HSV_to_HSL
    //                   & http://alvyray.com/Papers/CG/HWB_JGTv208.pdf
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

    this.w = Couleur.pRound(w);
    this.bk = Couleur.pRound(bk);
  }

  hwb2hsl() {
    // Source of the math: https://en.wikipedia.org/wiki/HSL_and_HSV#HSV_to_HSL
    //                   & http://alvyray.com/Papers/CG/HWB_JGTv208.pdf
    let w = this.w;
    let bk = this.bk;
    let _s, v, s, l;
    let _w = w, _bk = bk;

    if (w + bk > 1) {
      _w = w / (w + bk);
      _bk = bk / (w + bk);
    }

    v = 1 - _bk;
    if (_bk == 1)
      _s = 0;
    else
      _s = 1 - _w / v;

    l = v - v * _s / 2;
    if (l == 0 || l == 1)
      s = 0;
    else
      s = (v - l) / Math.min(l, 1 - l);

    this.s = Couleur.pRound(s);
    this.l = Couleur.pRound(l);
  }

  rgb2lab() {
    // Source of the math: https://www.w3.org/TR/css-color-4/#rgb-to-lab
    const linRGB = x => (x < 0.04045) ? x / 12.92 : Math.pow((x + 0.055) / 1.055, 2.4);
    const r = linRGB(this.r);
    const g = linRGB(this.g);
    const b = linRGB(this.b);

    let x = 0.4124564 * r + 0.3575761 * g + 0.1804375 * b;
    let y = 0.2126729 * r + 0.7151522 * g + 0.0721750 * b;
    let z = 0.0193339 * r + 0.1191920 * g + 0.9503041 * b;

    let x50 = 1.0478112 * x + 0.0228866 * y - 0.0501270 * z;
    let y50 = 0.0295424 * x + 0.9904844 * y - 0.0170491 * z;
    let z50 = -0.0092345 * x + 0.0150436 * y + 0.7521316 * z;

    const ε = 216/24389;
    const κ = 24389/27;
    const w = [0.96422, 1, 0.82521];

    x = x50 / w[0];
    y = y50 / w[1];
    z = z50 / w[2];

    const f = x => (x > ε) ? Math.cbrt(x) : (κ * x + 16) / 116;

    let ciel, ciea, cieb;
    ciel = (116 * f(y) - 16) / 100;
    ciea = 500 * (f(x) - f(y));
    cieb = 200 * (f(y) - f(z));

    this.ciel = Couleur.pRound(ciel);
    this.ciea = Couleur.pRound(ciea);
    this.cieb = Couleur.pRound(cieb);
  }

  lab2rgb() {
    // Source of the math: https://www.w3.org/TR/css-color-4/#lab-to-rgb
    const ε = 216/24389;
    const κ = 24389/27;
    const w = [0.96422, 1, 0.82521];

    const l = this.ciel * 100;

    let f1 = (l + 16) / 116;
    let f0 = this.ciea / 500 + f1;
    let f2 = f1 - this.cieb / 200;

    let x50 = (f0 ** 3 > ε) ? f0 ** 3 : (116 * f0 - 16) / κ;
    let y50 = (l > κ * ε) ? ((l + 16) / 116) ** 3 : l / κ;
    let z50 = (f2 ** 3 > ε) ? f2 ** 3 : (116 * f2 - 16) / κ;

    x50 = x50 * w[0];
    y50 = y50 * w[1];
    z50 = z50 * w[2];

    let x = 0.9555766 * x50 - 0.0230393 * y50 + 0.0631636 * z50;
    let y = -0.0282895 * x50 + 1.0099416 * y50 + 0.0210077 * z50;
    let z = 0.0122982 * x50 - 0.0204830 * y50 + 1.3299098 * z50;

    let r = 3.2404542 * x - 1.5371385 * y - 0.4985341 * z;
    let g = -0.9692660 * x + 1.8760108 * y + 0.0415560 * z;
    let b = 0.0556434 * x - 0.2040259 * y + 1.0572252 * z;

    const gamRGB = x => (x > 0.0031308) ? 1.055 * Math.pow(x, 1 / 2.4) - 0.055 : 12.92 * x;

    r = gamRGB(r);
    g = gamRGB(g);
    b = gamRGB(b);

    this.r = Math.max(0, Math.min(Couleur.pRound(r), 1));
    this.g = Math.max(0, Math.min(Couleur.pRound(g), 1));
    this.b = Math.max(0, Math.min(Couleur.pRound(b), 1));
  }

  lab2lch() {
    let ciec, cieh;
    ciec = Math.sqrt(this.ciea ** 2 + this.cieb ** 2);
    cieh = Couleur.parse(Math.atan2(this.cieb, this.ciea) * 180 / Math.PI, 'angle');

    this.ciec = Couleur.pRound(ciec);
    this.cieh = Couleur.pRound(cieh);
  }

  lch2lab() {
    let cieh = this.cieh * 360;
    let ciea, cieb;
    ciea = this.ciec * Math.cos(cieh * Math.PI / 180);
    cieb = this.ciec * Math.sin(cieh * Math.PI / 180);

    this.ciea = Couleur.pRound(ciea);
    this.cieb = Couleur.pRound(cieb);
  }

  // Blends a transparent color and an opaque color
  static blend(_couleur1, _couleur2) {
    let couleur1 = Couleur.check(_couleur1);
    let couleur2 = Couleur.check(_couleur2);

    let background, overlay;
    if (couleur1.a < 1 && couleur2.a < 1)
      throw 'At least one of the arguments needs to be an opaque Couleur';
    else if (couleur1.a < 1 && couleur2.a == 1) {
      background = couleur2;
      overlay = couleur1;
    } else {
      background = couleur1;
      overlay = couleur2;
    }
    const r = 255 * (overlay.a * overlay.r + (1 - overlay.a) * background.r);
    const g = 255 * (overlay.a * overlay.g + (1 - overlay.a) * background.g);
    const b = 255 * (overlay.a * overlay.b + (1 - overlay.a) * background.b);
    return new Couleur(`rgb(${r}, ${g}, ${b})`);
  }

  // Shorthand for Couleur.blend
  blend(_couleur2, alpha = null) {
    let couleur2 = Couleur.check(_couleur2);
    let background, overlay;
    if (this.a < 1 && couleur2.a == 1) {
      overlay = this;
      background = couleur2;
    } else {
      background = this;
      overlay = couleur2;
    }
    if (alpha !== null && alpha != overlay.a) overlay = overlay.replace('a', alpha);
    return Couleur.blend(background, overlay);
  }

  // Computes the luminance of a color
  // (source of the math: https://www.w3.org/TR/2008/REC-WCAG20-20081211/#relativeluminancedef)
  get luminance() {
    let couleur = this;
    if (this.a < 1) throw 'Can\'t calculate luminance of transparent color';

    let arr = [couleur.r, couleur.g, couleur.b];
    for (let i = 0; i <= 2; i++) {
      let e = arr[i];
      if (e <= 0.03928) e = e / 12.92;
      else              e = Math.pow((e + 0.055) / 1.055, 2.4);
      arr[i] = e;
    }

    return Couleur.pRound(0.2126 * arr[0] + 0.7152 * arr[1] + 0.0722 * arr[2]);
  }

  // Computes the contrast between two colors
  // (source of the math: https://www.w3.org/TR/2008/REC-WCAG20-20081211/#contrast-ratiodef)
  static contrast(_couleur1, _couleur2) {
    let couleur1 = Couleur.check(_couleur1);
    let couleur2 = Couleur.check(_couleur2);

    const L1 = couleur1.luminance;
    const L2 = couleur2.luminance;
    const Lmax = Math.max(L1, L2);
    const Lmin = Math.min(L1, L2);
    return Couleur.pRound((Lmax + 0.05) / (Lmin + 0.05));
  }

  // Shorthand for Couleur.contrast
  contrast(_couleur2) {
    let couleur2 = Couleur.check(_couleur2);
    return Couleur.contrast(this, couleur2);
  }

  // Checks if black or white text would have better contrast with this color
  contrastedText() {
    const L = this.luminance;
    const LB = 1; // luminance of white
    const LN = 0; // luminance of black
    const contrastes = [
      (L + 0.05) / (LN + 0.05), // contrast between this and black
      (LB + 0.05) / (L + 0.05)  // contrast between white and this
    ];
    if (contrastes[0] > contrastes[1])  return 'black'; // if contrast is higher with black
    else                                return 'white';
  }

  // Modifies the color (without changing its hue) to give it
  // better contrast (= closer to desiredContrast) with referenceColor
  // The options argument supports these properties:
  // - lower: if true and the contrast is higher than desired,
  //   the color will be modified to lower the contrast
  // - changeSecondColor: if true, the reference color will be
  //   modified too
  betterContrast(referenceColor, desiredContrast, step = 5, options = {}) {
    if (typeof options.lower == 'undefined') options.lower = false;
    if (typeof options.changeSecondColor == 'undefined') options.changeSecondColor = false;
    if (typeof options.maxIterations == 'undefined') options.maxIterations = 1000;

    let movingColor = new Couleur(`${this.rgb}`);
    let refColor = Couleur.check(referenceColor);

    // Lets measure the initial contrast
    // and decide if we want it to go up or down.
    let contrast = movingColor.contrast(refColor);
    let direction;
    if (contrast > desiredContrast)      direction = -1;
    else if (contrast < desiredContrast) direction = 1;
    else                                 direction = 0;
    if ((direction < 0 && !options.lower) || (direction == 0)) {
      if (options.changeSecondColor)  return [this, refColor];
      else                            return this;
    }

    // We keep going as long as contrast is still below / over desiredContrast.
    const condition =  c => (direction > 0) ? (c < desiredContrast)
                                            : (c > desiredContrast);
    let up = 'bk';
    let i = 0;
    const initialL = refColor.l;
    while (condition(contrast) && i < options.maxIterations) {
      i++;
      let newColor;
      let newRefColor = refColor;
      // Let's try to raise contrast by increasing blackness and reducing whiteness.
      if (up == 'bk') newColor = movingColor.change('bk', `+${step}%`).change('w', `-${step}%`);
      else            newColor = movingColor.change('bk', `-${step}%`).change('w', `+${step}%`);
      if (options.changeSecondColor) newRefColor = newColor.replace('l', `${initialL * 100}%`);
      // If next step is gonna make the color black or white, stop. Continuing would loop.
      if (
        (up == 'bk' && newColor.bk > (1 - .01 * step) && newColor.w < (0 + .1 * step))
        || (up == 'w' && newColor.w > (1 - .01 * step) && newColor.bk < (0 + .1 * step))
      ) break;
      const newContrast = newColor.contrast(newRefColor);

      // We're going the wrong way! Let's reverse blackness's and whiteness's roles.
      const wrongWay =  (direction > 0) ? (newContrast <= contrast)
                                        : (newContrast >= contrast);
      if (wrongWay) {
        up = 'w';
        continue;
      }

      // We went the right way! But we overshot a little. Let's stop.
      const overshot = Math.abs(contrast - desiredContrast) <= Math.abs(newContrast - desiredContrast);
      if (overshot) {
        break;
      }

      // We went the right way, let's keep going!
      contrast = newContrast;
      movingColor = newColor;
      if (options.changeSecondColor) refColor = newRefColor;
    }

    // We're done!
    if (options.changeSecondColor)  return [movingColor, refColor];
    else                            return movingColor;
  }

  // Changes a property of the color
  change(propriete, valeur, options = {}) {
    let nouvelleCouleur = new Couleur(`${this.rgb}`);
    const error = 'Incorrect value format for ' + propriete;
    const remplace = (options === true) || ((typeof options.replace != 'undefined') ? options.replace : false);

    if (['r', 'g', 'b'].includes(propriete))
    {
      let [_valeur, log] = Couleur.parse(valeur, null, true);
      if (log == '%')
        nouvelleCouleur[propriete] = Couleur.parse(((remplace ? 0 : nouvelleCouleur[propriete]) + parseFloat(valeur) / 100) * 100 + '%');
      else if (log == '255')
        nouvelleCouleur[propriete] = Couleur.parse(((remplace ? 0 : nouvelleCouleur[propriete]) + parseFloat(valeur) / 255) * 255);
      else
        throw error;
      return new Couleur(nouvelleCouleur.rgb);
    }
    else if (['h'].includes(propriete))
    {
      let [_valeur, log] = Couleur.parse(valeur, 'angle', true);
      if (log == 'deg')
        nouvelleCouleur[propriete] = Couleur.parse(((remplace ? 0 : nouvelleCouleur[propriete]) + parseFloat(valeur) / 360) * 360 + 'deg', 'angle');
      else if (log == 'grad')
        nouvelleCouleur[propriete] = Couleur.parse(((remplace ? 0 : nouvelleCouleur[propriete]) + parseFloat(valeur) / 400) * 400 + 'grad', 'angle');
      else if (log == 'rad')
        nouvelleCouleur[propriete] = Couleur.parse(((remplace ? 0 : nouvelleCouleur[propriete]) + parseFloat(valeur) / (360 * (Math.PI / 180))) * 360 * (Math.PI / 180) + 'rad', 'angle');
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
        nouvelleCouleur[propriete] = Couleur.parse(((remplace ? 0 : nouvelleCouleur[propriete]) + parseFloat(valeur) / 100) * 100 + '%');
      else
        throw error;
      return new Couleur(nouvelleCouleur.hsl);
    }
    else if (['w', 'bk'].includes(propriete))
    {
      let [_valeur, log] = Couleur.parse(valeur, null, true);
      if (log == '%')
        nouvelleCouleur[propriete] = Couleur.parse(((remplace ? 0 : nouvelleCouleur[propriete]) + parseFloat(valeur) / 100) * 100 + '%');
      else
        throw error;
      return new Couleur(nouvelleCouleur.hwb);
    }
    else if (['a'].includes(propriete))
    {
      let [_valeur, log] = Couleur.parse(valeur, 'alpha', true);
      if (log == 'alpha')
        nouvelleCouleur[propriete] = Couleur.parse((remplace ? 0 : nouvelleCouleur[propriete]) + parseFloat(valeur), 'alpha');
      else if (log == '%')
        nouvelleCouleur[propriete] = Couleur.parse(((remplace ? 0 : nouvelleCouleur[propriete]) + parseFloat(valeur) / 100) * 100 + '%', 'alpha');
      else
        throw error;
      return new Couleur(nouvelleCouleur.hsl);
    }
    else if (['ciel'].includes(propriete))
    {
      let [_valeur, log] = Couleur.parse(valeur, 'cie', true);
      if (log == '%')
        nouvelleCouleur[propriete] = Couleur.parse(((remplace ? 0 : nouvelleCouleur[propriete]) + parseFloat(valeur) / 100) * 100 + '%', 'cie');
      else
        throw error;
      return new Couleur(nouvelleCouleur.lab);
    }
    else if (['ciea', 'cieb'].includes(propriete))
    {
      let [_valeur, log] = Couleur.parse(valeur, 'arbitrary', true);
      if (log == 'arbitraryN')
        nouvelleCouleur[propriete] = Couleur.parse(((remplace ? 0 : nouvelleCouleur[propriete]) + parseFloat(valeur)), 'arbitrary');
      else
        throw error;
      return new Couleur(nouvelleCouleur.lab);
    }
    else if (['ciec'].includes(propriete))
    {
      let [_valeur, log] = Couleur.parse(valeur, 'cie', true);
      if (log == 'cieC')
        nouvelleCouleur[propriete] = Couleur.parse(((remplace ? 0 : nouvelleCouleur[propriete]) + parseFloat(valeur)), 'cie');
      else
        throw error;
      return new Couleur(nouvelleCouleur.lch);
    }
    else if (['cieh'].includes(propriete))
    {
      let [_valeur, log] = Couleur.parse(valeur, 'angle', true);
      if (log == 'deg')
        nouvelleCouleur[propriete] = Couleur.parse(((remplace ? 0 : nouvelleCouleur[propriete]) + parseFloat(valeur) / 360) * 360 + 'deg', 'angle');
      else if (log == 'grad')
        nouvelleCouleur[propriete] = Couleur.parse(((remplace ? 0 : nouvelleCouleur[propriete]) + parseFloat(valeur) / 400) * 400 + 'grad', 'angle');
      else if (log == 'rad')
        nouvelleCouleur[propriete] = Couleur.parse(((remplace ? 0 : nouvelleCouleur[propriete]) + parseFloat(valeur) / (360 * (Math.PI / 180))) * 360 * (Math.PI / 180) + 'rad', 'angle');
      else if (log == 'turn')
        nouvelleCouleur[propriete] = Couleur.parse(((remplace ? 0 : nouvelleCouleur[propriete]) + _valeur) + 'turn', 'angle');
      else
        throw error;
      return new Couleur(nouvelleCouleur.lch);
    }
    else
      return new Couleur(nouvelleCouleur.rgb);
  }

  // Replaces a property of the color
  replace(propriete, valeur, options = {}) {
    const _options = options;
    options.replace = true;
    return this.change(propriete, valeur, options);
  }

  // Replaces a property of the color by a percentage of its initial value
  scale(propriete, valeur, options = {}) {
    const _options = options;
    options.replace = true;

    const [_valeur, log] = Couleur.parse(valeur, 'alpha', true);
    if (!['%', 'alpha'].includes(log))
      throw 'Second parameter should be a percentage or a number between 0 and 1';

    let nouvelleCouleur = new Couleur(`${this.rgb}`);
    nouvelleCouleur[propriete] = _valeur * nouvelleCouleur[propriete];

    if (['r', 'g', 'b'].includes(propriete))
      return new Couleur(nouvelleCouleur.rgb);
    else if (['h'].includes(propriete))
      return new Couleur(nouvelleCouleur.hsl);
    else if (['s', 'l'].includes(propriete))
      return new Couleur(nouvelleCouleur.hsl);
    else if (['w', 'bk'].includes(propriete))
      return new Couleur(nouvelleCouleur.hwb);
    else if (['a'].includes(propriete))
      return new Couleur(nouvelleCouleur.hsl);
    else if (['ciel'].includes(propriete))
      return new Couleur(nouvelleCouleur.lab);
    else if (['ciea', 'cieb'].includes(propriete))
      return new Couleur(nouvelleCouleur.lab);
    else if (['ciec', 'cieh'].includes(propriete))
      return new Couleur(nouvelleCouleur.lab);
    else
      return new Couleur(nouvelleCouleur.rgb);
  }

  // change() aliases
  complement() {
    return this.change('h', 180);
  }

  negative() {
    return new Couleur(`rgba(${255 * (1 - this.r)}, ${255 * (1 - this.g)}, ${255 * (1 - this.b)}, ${this.a})`);
  }

  invert() {
    return this.negative();
  }

  // options: {scale: true/false}
  darken(_value, options = {}) {
    let value = Couleur.parse(_value, 'arbitrary');
    value = value * 100;
    const scale = (options === true || options === false) ? options : ((typeof options.scale != 'undefined') ? options.scale : true);
    const newValue = (scale == true) ? (this.l * (100 - parseFloat(value))) + '%'
                                     : -1 * parseFloat(value) + '%';
    return this.change('l', newValue, scale);
  }

  lighten(_value, options = {}) {
    let value = Couleur.parse(_value, 'arbitrary');
    value = value * 100;
    const scale = (options === true || options === false) ? options : ((typeof options.scale != 'undefined') ? options.scale : true);
    const newValue = (scale == true) ? (this.l * (100 + parseFloat(value))) + '%'
                                     : parseFloat(value) + '%';
    return this.change('l', newValue, scale);
  }

  desaturate(_value, options = {}) {
    let value = Couleur.parse(_value, 'arbitrary');
    value = value * 100;
    const scale = (options === true || options === false) ? options : ((typeof options.scale != 'undefined') ? options.scale : true);
    const newValue = (scale == true) ? (this.s * (100 - parseFloat(value))) + '%'
                                     : -1 * parseFloat(value) + '%';
    return this.change('s', newValue, scale);
  }

  saturate(_value, options = {}) {
    let value = Couleur.parse(_value, 'arbitrary');
    value = value * 100;
    const scale = (options === true || options === false) ? options : ((typeof options.scale != 'undefined') ? options.scale : true);
    const newValue = (scale == true) ? (this.s * (100 + parseFloat(value))) + '%'
                                     : parseFloat(value) + '%';
    return this.change('s', newValue, scale);
  }

  greyscale() {
    return this.desaturate('100%');
  }

  grayscale() {
    return this.greyscale();
  }

  // Computes the values of intermediate colors to make a gradient that avoids "grey zone"
  static gradient(_from, _to, _steps = 5) {
    const from = Couleur.check(_from);
    const to = Couleur.check(_to);
    const steps = Math.min(Math.max(1, _steps), 100);

    const intermediateColors = [from];
    const stepL = (to.ciel - from.ciel) / steps;
    const stepC = (to.ciec - from.ciec) / steps;
    // Minimize the distance to travel through hues
    const stepHup = (360 * (to.cieh - from.cieh) % 360 + 360) % 360 / 360;
    const stepHdown = (360 * (from.cieh - to.cieh) % 360 + 360) % 360 / 360;
    const stepH = ((stepHup <= stepHdown) ? stepHup : (-1 * stepHdown)) / steps;

    for (let i = 1; i < steps; i++) {
      let previous = intermediateColors[i - 1];
      const L = previous.ciel + stepL;
      const C = previous.ciec + stepC;
      const H = previous.cieh + stepH;
      try {
        const next = new Couleur(`lch(${L * 100}% ${C} ${H * 360})`);
        intermediateColors.push(next);
        previous = next;
      } catch(error) {
        console.error(error);
      }
    }
    return [...intermediateColors, to];
  }

  // Shorthand for Couleur.gradient()
  gradient(to, steps = 5) {
    return Couleur.gradient(this, to, steps);
  }

  static get properties() {
    return ['a', 'r', 'g', 'b', 'h', 's', 'l', 'w', 'bk', 'ciel', 'ciea', 'cieb', 'ciec', 'cieh'];
  }

  static get formats() {
    return [
      {
        id: 'HEX',
        prefix: '#',
        separator: '',
        suffix: '',
        syntaxes: [
          // #abc or #ABC
          /^#([a-fA-F0-9]{1})([a-fA-F0-9]{1})([a-fA-F0-9]{1})$/,
          // #aabbcc or #AABBCC
          /^#([a-fA-F0-9]{2})([a-fA-F0-9]{2})([a-fA-F0-9]{2})$/
        ]
      }, {
        id: 'HEXA',
        prefix: '#',
        separator: '',
        suffix: '',
        syntaxes: [
          // #abcd or #ABCD
          /^#([a-fA-F0-9]{1})([a-fA-F0-9]{1})([a-fA-F0-9]{1})([a-fA-F0-9]{1})$/,
          // #aabbccdd or #AABBCCDD
          /^#([a-fA-F0-9]{2})([a-fA-F0-9]{2})([a-fA-F0-9]{2})([a-fA-F0-9]{2})$/
        ]
      }, {
        id: 'RGB',
        prefix: 'rgb(',
        separator: ', ',
        suffix: ')',
        syntaxes: [
          // rgb(255, 255, 255) or rgb(255,255,255)
          new RegExp(`^rgba?\\((${Couleur.vNum}), ?(${Couleur.vNum}), ?(${Couleur.vNum})\\)$`),
          // rgb(100%, 100%, 100%) or rgb(100%,100%,100%)
          new RegExp(`^rgba?\\((${Couleur.vPer}), ?(${Couleur.vPer}), ?(${Couleur.vPer})\\)$`),
          // rgb(255 255 255)
          new RegExp(`^rgba?\\((${Couleur.vNum}) (${Couleur.vNum}) (${Couleur.vNum})\\)$`),
          // rgb(100% 100% 100%)
          new RegExp(`^rgba?\\((${Couleur.vPer}) (${Couleur.vPer}) (${Couleur.vPer})\\)$`)
        ]
      }, {
        id: 'RGBA',
        prefix: 'rgba(',
        separator: ', ',
        suffix: ')',
        syntaxes: [
          // rgba(255, 255, 255, .5) or rgba(255, 255, 255, 50%) (espaces optionnels)
          new RegExp(`^rgba?\\((${Couleur.vNum}), ?(${Couleur.vNum}), ?(${Couleur.vNum}), ?(${Couleur.vNP})\\)$`),
          // rgba(100%, 100%, 100%, .5) or rgba(100%, 100%, 100%, 50%) (espaces optionnels)
          new RegExp(`^rgba?\\((${Couleur.vPer}), ?(${Couleur.vPer}), ?(${Couleur.vPer}), ?(${Couleur.vNP})\\)$`),
          // rgba(255 255 255 / 50%) or rgba(255 255 255 / .5)
          new RegExp(`^rgba?\\((${Couleur.vNum}) (${Couleur.vNum}) (${Couleur.vNum}) ?\\/ ?(${Couleur.vNP})\\)$`),
          // rgba(100% 100% 100% / 50%) or rgba(100% 100% 100% / .5)
          new RegExp(`^rgba?\\((${Couleur.vPer}) (${Couleur.vPer}) (${Couleur.vPer}) ?\\/ ?(${Couleur.vNP})\\)$`)
        ]
      }, {
        id: 'HSL',
        prefix: 'hsl(',
        separator: ', ',
        suffix: ')',
        syntaxes: [
          // hsl(<angle>, 100%, 100%)
          new RegExp(`^hsla?\\((${Couleur.vAng}), ?(${Couleur.vPer}), ?(${Couleur.vPer})\\)$`),
          // hsl(<angle> 100% 100%)
          new RegExp(`^hsla?\\((${Couleur.vAng}) (${Couleur.vPer}) (${Couleur.vPer})\\)$`)
        ]
      }, {
        id: 'HSLA',
        prefix: 'hsla(',
        separator: ', ',
        suffix: ')',
        syntaxes: [
          // hsla(<angle>, 100%, 100%, .5) or hsla(<angle>, 100%, 100%, 50%)
          new RegExp(`^hsla?\\((${Couleur.vAng}), ?(${Couleur.vPer}), ?(${Couleur.vPer}), ?(${Couleur.vNP})\\)$`),
          // hsla(<angle> 100% 100% / .5) or hsl(<angle> 100% 100% / 50%)
          new RegExp(`^hsla?\\((${Couleur.vAng}) (${Couleur.vPer}) (${Couleur.vPer}) ?\\/ ?(${Couleur.vNP})\\)$`)
        ]
      }, {
        id: 'HWB',
        prefix: 'hwb(',
        separator: ', ',
        suffix: ')',
        syntaxes: [
          // hwb(<angle>, 100%, 100%)
          new RegExp(`^hwba?\\((${Couleur.vAng}), ?(${Couleur.vPer}), ?(${Couleur.vPer})\\)$`),
          // hwb(<angle> 100% 100%)
          new RegExp(`^hwba?\\((${Couleur.vAng}) (${Couleur.vPer}) (${Couleur.vPer})\\)$`)
        ]
      }, {
        id: 'HWBA',
        prefix: 'hwba(',
        separator: ', ',
        suffix: ')',
        syntaxes: [
          // hwba(<angle>, 100%, 100%, .5) or hsla(<angle>, 100%, 100%, 50%)
          new RegExp(`^hwba?\\((${Couleur.vAng}), ?(${Couleur.vPer}), ?(${Couleur.vPer}), ?(${Couleur.vNP})\\)$`),
          // hwba(<angle> 100% 100% / .5) or hsl(<angle> 100% 100% / 50%)
          new RegExp(`^hwba?\\((${Couleur.vAng}) (${Couleur.vPer}) (${Couleur.vPer}) ?\\/ ?(${Couleur.vNP})\\)$`)
        ]
      }, {
        id: 'LAB',
        prefix: 'lab(',
        separator: ' ',
        suffix: ')',
        syntaxes: [
          // lab(300% 25 40)
          new RegExp(`^lab\\((${Couleur.vPer}) (${Couleur.vNum}) (${Couleur.vNum})\\)$`),
          // lab(300% 25 40 / .5)
          new RegExp(`^lab\\((${Couleur.vPer}) (${Couleur.vNum}) (${Couleur.vNum}) ?\\/ ?(${Couleur.vNP})\\)$`)
        ]
      }, {
        id: 'LCH',
        prefix: 'lch(',
        separator: ' ',
        suffix: ')',
        syntaxes: [
          // lch(300% 25 <angle>)
          new RegExp(`^lch\\((${Couleur.vPer}) (${Couleur.vNum}) (${Couleur.vAng})\\)$`),
          // lch(300% 25 <angle> / .5)
          new RegExp(`^lch\\((${Couleur.vPer}) (${Couleur.vNum}) (${Couleur.vAng}) ?\\/ ?(${Couleur.vNP})\\)$`)
        ]
      }, {
        id: 'NAME',
        prefix: '',
        separator: '',
        suffix: '',
        syntaxes: [
          // white or WHITE or WhiTe
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
  static get vProp() { return Couleur.properties.join('|'); } // noms des propriétés (r, g, b, etc)

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