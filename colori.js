/**
 * Colori module
 * @author Remiscan <https://remiscan.fr>
 * @module colori.js
 */
export default class Couleur {
  /**
   * Creates a new Couleur object that contains precalculated properties of the color.
   * @param {colorString} couleur - Color expression in a supported format.
   * @throws {string} when the parameter isn't a valid color string.
   */
  constructor(couleur) {
    if (couleur instanceof Couleur)
      throw 'Already an instance of Couleur';
    else if (typeof couleur != 'string')
      throw `Couleur objects can only be created from a String ; this is not one: ${couleur}`;

    /** @property {number} r - Red value (from 0 to 1) */
    this.r = null;
    /** @property {number} g - Green value (from 0 to 1) */
    this.g = null;
    /** @property {number} b - Blue value (from 0 to 1) */
    this.b = null;
    /** @property {number} h - Hue (from 0 to 1) */
    this.h = null;
    /** @property {number} s - Saturation (from 0 to 1) */
    this.s = null;
    /** @property {number} l - Luminosity (from 0 to 1) */
    this.l = null;
    /** @property {number} w - Whiteness (from 0 to 1) */
    this.w = null;
    /** @property {number} bk - Blackness (from 0 to 1) */
    this.bk = null;
    /** @property {number} a - Opacity (from 0 to 1) */
    this.a = null;
    /** @property {number} ciel - CIE lightness (from 0 to 1) */
    this.ciel = null;
    /** @property {number} ciea - CIE A-axis value (any number, usually from -160 to 160) */
    this.ciea = null;
    /** @property {number} cieb - CIE B-axis value (any number, usually from -160 to 160) */
    this.cieb = null;
    /** @property {number} ciec - CIE chroma (any number, usually from 0 to 230) */
    this.ciec = null;
    /** @property {number} cieh - CIE hue (from 0 to 1) */
    this.cieh = null;

    const format = Couleur.matchSyntax(couleur.trim());
    const isAlpha = (val, def = 1) => !!val ? val : (val == 0) ? 0 : def;

    switch (format.id) {
      case 'HEX':
      case 'HEXA':
        this.hex = [format.data[1], format.data[2], format.data[3], isAlpha(format.data[4], 'ff')];
        break;
      case 'RGB':
      case 'RGBA':
        this.rgb = [format.data[1], format.data[2], format.data[3], isAlpha(format.data[4])];
        break;
      case 'HSL':
      case 'HSLA':
        this.hsl = [format.data[1], format.data[2], format.data[3], isAlpha(format.data[4])];
        break;
      case 'HWB':
      case 'HWBA':
        this.hwb = [format.data[1], format.data[2], format.data[3], isAlpha(format.data[4])];
        break;
      case 'LAB':
        this.lab = [format.data[1], format.data[2], format.data[3], isAlpha(format.data[4])];
        break;
      case 'LCH':
        this.lch = [format.data[1], format.data[2], format.data[3], isAlpha(format.data[4])];
        break;
      default:
        throw `${couleur} is not a valid color format`;
    }
  }


  /* TYPE DEFINITIONS */

  /** 
   * A valid color expression.
   * Supported formats are hexadecimal, RGB, HSL, HWB, LAB and LCH.
   * @typedef {string} colorString
   */

  /**
   * A Couleur, or a valid color expression. @see colorString
   * @typedef {(Couleur|colorString)} color
   */

  /**
   * A precalculated property of a Couleur object.
   * @typedef {('r'|'g'|'b'|'a'|'h'|'s'|'l'|'w'|'bk'|'ciel'|'ciea'|'cieb'|'ciec'|'cieh')} colorProperty
   */


  /**
   * Matches the user input with supported color formats.
   * @param {string} couleur - Color expression in a supported format.
   * @returns {Object} Recognized syntax.
   * @throws {string} when {couleur} is not in a valid format.
   */
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

    for (const format of formats) {
      for (const [k, syntaxe] of format.syntaxes.entries()) {
        const result = couleur.match(syntaxe);
        if (result != null && result[0] === couleur) {
          if (format.id == 'NAME') {
            const allNames = Couleur.couleursNommees;
            if (couleur.toLowerCase() in allNames)
              return Couleur.matchSyntax('#' + allNames[couleur.toLowerCase()]);
          }
          return {
            id: format.id,
            syntaxe: k,
            data: result
          };
        }
      }
    }

    throw `${couleur} is not a valid color format`;
  }


  /**
   * Calculates all the color properties from the already defined ones.
   * @param {string} knownFormat - Name of the already know format.
   */
  compute(knownFormat) {
    if (knownFormat == 'rgb') {
      this.rgb2hsl();
      this.hsl2hwb();
      this.rgb2lab();
      this.lab2lch();
    } else if (knownFormat == 'hsl') {
      this.hsl2rgb();
      this.hsl2hwb();
      this.rgb2lab();
      this.lab2lch();
    } else if (knownFormat == 'hwb') {
      this.hwb2hsl();
      this.hsl2rgb();
      this.rgb2lab();
      this.lab2lch();
    } else if (knownFormat == 'lab') {
      this.lab2lch();
      this.lch2rgb();
      this.rgb2hsl();
      this.hsl2hwb();
    } else if (knownFormat == 'lch') {
      this.lch2lab();
      this.lch2rgb();
      this.rgb2hsl();
      this.hsl2hwb();
    }
  }

  /**
   * Returns a float precise to the nth decimal.
   * @param {(number|string)} _x - The number to round.
   * @param {number} n - The number of decimals.
   * @returns {number} The float precise to the nth decimal.
   */
  static pRound(_x, n = 5) {
    let x = (typeof _x == 'number') ? _x : Number(_x);
    return Number(parseFloat(x.toPrecision(n)));
  }


  /**
   * Checks if a variable is a Couleur object, or if it can be made into one.
   * @param {color} color
   * @returns {Couleur}
   */
  static check(color) {
    if (color instanceof Couleur) return color;
    try { return new Couleur(color); }
    catch (error) {
      throw `Argument should be an instance of the Couleur class, or a valid color string ; this isn't: ${color}`;
    }
  }


  /**
   * Parses a number / percentage / angle into the correct format to store it.
   * @param {(string|number)} n - The value to parse.
   * @param {?string} type - The color parameter that has n as its value.
   * @param {boolean} clamp - Whether the value should de clamped to an interval.
   * @returns {number} The properly parsed number.
   */
  static parse(n, type = null, clamp = true) {
    // Alpha values:
    // from any % or any number
    // clamped to [0, 100]% or [0, 1]
    // to [0, 1]
    if (type == 'a') {
      // If n is a percentage
      if (new RegExp('^' + Couleur.vPer + '$').test(n)) {
        if (clamp)  return Math.max(0, Math.min(parseFloat(n) / 100, 1));
        else        return parseFloat(n) / 100;
      }
      // If n is a number
      else if (new RegExp('^' + Couleur.vNum + '$').test(n)) {
        if (clamp)  return Math.max(0, Math.min(n, 1));
        else        return parseFloat(n);
      }
      else throw `Invalid ${type} value: ${n}`;
    }

    // Red, green, blue values:
    // from any % or any number
    // clamped to [0, 100]% or [0, 255]
    // to [0, 1]
    else if (['r', 'g', 'b'].includes(type)) {
      // If n is a percentage
      if (new RegExp('^' + Couleur.vPer + '$').test(n)) {
        if (clamp)  return Math.max(0, Math.min(parseFloat(n) / 100, 1));
        else        return parseFloat(n) / 100;
      }
      // If n is a number
      else if (new RegExp('^' + Couleur.vNum + '$').test(n)) {
        if (clamp)  return Math.max(0, Math.min(n / 255, 1));
        else        return n / 255;
      }
      else throw `Invalid ${type} value: ${n}`;
    }

    // Hue and CIE hue values:
    // from any angle or any number
    // clamped to [0, 360]deg or [0, 400]grad or [0, 2π]rad or [0, 1]turn
    // to [0, 1]
    else if(['h', 'cieh'].includes(type)) {
      let _n = parseFloat(n);
      // If n is a number
      if (new RegExp('^' + Couleur.vNum + '$').test(n)) {
        while (_n < 0) _n += 360;
        while (_n > 360) _n -= 360;
        return _n / 360;
      }
      // If n is an angle
      if ((new RegExp('^' + Couleur.vAng + '$').test(n))) {
        if (String(n).slice(-3) == 'deg') {
          while (_n < 0) _n += 360;
          while (_n > 360) _n -= 360;
          return _n / 360;
        } else if (String(n).slice(-4) == 'grad') {
          while (_n < 0) _n += 400;
          while (_n > 400) _n -= 400;
          return _n / 400;
        } else if (String(n).slice(-3) == 'rad') {
          _n = _n * 180 / Math.PI;
          while (_n < 0) _n += 360;
          while (_n > 360) _n -= 360;
          return _n / 360;
        } else if (String(n).slice(-4) == 'turn') {
          while (_n < 0) _n += 1;
          while (_n > 1) _n -= 1;
          return _n;
        } else throw `Invalid angle value: ${n}`;
      }
      else throw `Invalid ${type} value: ${n}`;
    }

    // Percentage values:
    // from any %
    // clamped to [0, 100]%
    // to [0, 1]
    else if(['s', 'l', 'w', 'bk', 'ciel'].includes(type)) {
      // If n is a percentage
      if (new RegExp('^' + Couleur.vPer + '$').test(n)) {
        if (clamp)  return Math.max(0, Math.min(parseFloat(n) / 100, 1));
        else        return parseFloat(n) / 100;
      }
      else throw `Invalid ${type} value: ${n}`;
    }

    // CIE axes values:
    // any number
    else if(['ciea', 'cieb'].includes(type)) {
      // If n is a number
      if (new RegExp('^' + Couleur.vNum + '$').test(n)) {
        return parseFloat(n);
      }
      else throw `Invalid ${type} value: ${n}`;
    }

    // CIE chroma values:
    // from any number
    // clamped to [0, +Inf[
    else if (type == 'ciec') {
      // If n is a number
      if (new RegExp('^' + Couleur.vNum + '$').test(n)) {
        if (clamp)  return Math.max(0, n);
        else        return parseFloat(n);
      }
      else throw `Invalid ${type} value: ${n}`;
    }

    // Arbitrary values
    // from any % or any number
    // to any number (so that 0% becomes 0 and 100% becomes 1)
    else {
      // If n is a percentage
      if (new RegExp('^' + Couleur.vPer + '$').test(n)) {
        return parseFloat(n) / 100;
      }
      // If n is a number
      else if (new RegExp('^' + Couleur.vNum + '$').test(n)) {
        return parseFloat(n);
      }
      else throw `Invalid arbitrary value: ${n}`;
    }
  }



  /*****************************************/
  /* Setters and getters for color formats */
  /*****************************************/


  /* GENERAL SETTER */

  /**
   * Will be used by other setters to calculate all color properties.
   * @param {Array.<string|number>} data - Values to parse.
   * @param {string[]} props - Properties the values correspond to.
   * @param {string} format - Format (RGB, HSL, etc.) the properties come from.
   */
  set(data, props, format) {
    for (let i = 0; i < props.length; i++) {
      this[props[i]] = Couleur.pRound(Couleur.parse(data[i], props[i]));
    }
    const isAlpha = (val, def = 1) => !!val ? val : (val == 0) ? 0 : def;
    this.a = Couleur.pRound(Couleur.parse(isAlpha(data[3]), 'a'));
    this.compute(format);
  }


  /* NAME */

  /** @returns {?string} The approximate name of the color. */
  get name() {
    if (this.a == 1) {
      const allNames = Couleur.couleursNommees;
      const [r, g, b] = [255 * this.r, 255 * this.g, 255 * this.b];
      const tolerance = 255 * Couleur.tolerance;
      for (const [name, hex] of Object.entries(allNames)) {
        const [r2, g2, b2] = [parseInt(`${hex[0]}${hex[1]}`, 16), parseInt(`${hex[2]}${hex[3]}`, 16), parseInt(`${hex[4]}${hex[5]}`, 16)];
        if (Math.abs(r2 - r) + Math.abs(g2 - g) + Math.abs(b2 - b) < tolerance) return name;
      }
    }
    return null;
  }

  /** @returns {?string} The exact name of the color. */
  get exactName() {
    if (this.a == 1) {
      const allNames = Couleur.couleursNommees;
      const hex6 = this.hex.slice(1);
      const name = Object.keys(allNames).find(k => (allNames[k] == hex6));
      return name || null;
    }
    else if (this.a == 0)               return 'transparent';
    else                                return null;
  }


  /* RGB (hexadecimal) */

  /**
   * Adds a zero before a string of length 1.
   * @param {string} s - The string to pad.
   * @returns {string} The padded string.
   */
  static pad(s) { return (s.length < 2) ? `0${s}` : s; }
  
  /**
   * Calculates all properties of the color from its hexadecimal expression.
   * @param {string[]} - The hexadecimal values of the r, g, b, a properties.
   */
  set hex(hexa) {
    let r, g, b, a;
    
    r = hexa[0];
    r = (r.length == 1) ? r.repeat(2) : r;
    r = parseInt(r, 16);
    
    g = hexa[1];
    g = (g.length == 1) ? g.repeat(2) : g;
    g = parseInt(g, 16);
    
    b = hexa[2];
    b = (b.length == 1) ? b.repeat(2) : b;
    b = parseInt(b, 16);
    
    a = hexa[3] || 'ff';
    a = (a.length == 1) ? a.repeat(2) : a;
    a = parseInt(a, 16) / 255;

    this.rgb = [r, g, b, a];
  }

  /** Alias to the hex setter. */
  set hexa(hexa) { this.hex = hexa; }

  /** @returns {string} Hexadecimal expression of the color. */
  get hex() {
    if (this.a < 1)
      return this.hexa;
    const r = Couleur.pad(Math.round(this.r * 255).toString(16));
    const g = Couleur.pad(Math.round(this.g * 255).toString(16));
    const b = Couleur.pad(Math.round(this.b * 255).toString(16));
    return `#${r}${g}${b}`;
  }

  /** @returns {string} Hexadecimal (+ a) expression of the color. */
  get hexa() {
    const r = Couleur.pad(Math.round(this.r * 255).toString(16));
    const g = Couleur.pad(Math.round(this.g * 255).toString(16));
    const b = Couleur.pad(Math.round(this.b * 255).toString(16));
    const a = Couleur.pad(Math.round(this.a * 255).toString(16));
    return `#${r}${g}${b}${a}`;
  }


  /* RGB (functional) */
  
  /**
   * Calculates all properties of the color from its functional RGB expression.
   * @param {Array.<string|number>} - The numerical values of the r, g, b, a properties.
   */
  set rgb(rgba) {
    this.set(rgba, ['r', 'g', 'b'], 'rgb');
  }
  
  /** Alias to the rgb setter. */
  set rgba(rgba) { this.rgb = rgba; }

  /** @returns {string} RGB expression of the color. */
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

  /** @returns {string} RGBA expression of the color. */
  get rgba() {
    const r = Math.round(this.r * 255);
    const g = Math.round(this.g * 255);
    const b = Math.round(this.b * 255);
    const a = Math.round(this.a * 100) / 100;
    return `rgba(${r}, ${g}, ${b}, ${a})`;
  }


  /* HSL */

  /**
   * Calculates all properties of the color from its HSL expression.
   * @param {Array.<string|number>} - The numerical values of the h, s, l, a properties.
   */
  set hsl(hsla) {
    this.set(hsla, ['h', 's', 'l'], 'hsl')
  }

  /** Alias to the hsl setter. */
  set hsla(hsla) { this.hsl = hsla; }

  /** @returns {string} HSL expression of the color. */
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

  /** @returns {string} HSLA expression of the color. */
  get hsla() {
    const h = Math.round(this.h * 360);
    const s = Math.round(this.s * 100);
    const l = Math.round(this.l * 100);
    const a = Math.round(this.a * 100) / 100;
    return `hsla(${h}, ${s}%, ${l}%, ${a})`;
  }


  /* HWB */

  /**
   * Calculates all properties of the color from its HWB expression.
   * @param {Array.<string|number>} - The numerical values of the h, w, bk, a properties.
   */
  set hwb(hwba) {
    this.set(hwba, ['h', 'w', 'bk'], 'hwb');
  }

  /** Alias to the hwb setter. */
  set hwba(hwba) { this.hwb = hwba; }

  /** @returns {string} HWB expression of the color. */
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

  /** @returns {string} HWB expression of the color, but always with the alpha value. */
  get hwba() {
    const h = Math.round(this.h * 360);
    const w = Math.round(this.w * 100);
    const bk = Math.round(this.bk * 100);
    const a = Math.round(this.a * 100) / 100;
    return `hwb(${h} ${w}% ${bk}% / ${a})`;
  }


  /* LAB */

  /**
   * Calculates all properties of the color from its LAB expression.
   * @param {Array.<string|number>} - The numerical values of the ciel, ciea, cieb, a properties.
   */
  set lab(laba) {
    this.set(laba, ['ciel', 'ciea', 'cieb'], 'lab');
  }

  /** Alias to the lab setter. */
  set laba(laba) { this.lab = laba; }

  /** @returns {string} LAB expression of the color. */
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

  /** @returns {string} LAB expression of the color, but always with the alpha value. */
  get laba() {
    const ciel = Math.round(this.ciel * 100);
    const ciea = Math.round(this.ciea);
    const cieb = Math.round(this.cieb);
    const a = Math.round(this.a * 100) / 100;
    return `lab(${ciel}% ${ciea} ${cieb} / ${a})`;
  }


  /* LCH */

  /**
   * Calculates all properties of the color from its LCH expression.
   * @param {Array.<string|number>} - The numerical values of the ciel, ciec, cieh, a properties.
   */
  set lch(lcha) {
    this.set(lcha, ['ciel', 'ciec', 'cieh'], 'lch');
  }

  /** Alias to the lch setter. */
  set lcha(lcha) { this.lch = lcha; }

  /** @returns {string} LCH expression of the color. */
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

  /** @returns {string} LCH expression of the color, but always with the alpha value. */
  get lcha() {
    const ciel = Math.round(this.ciel * 100);
    const ciec = Math.round(this.ciec);
    const cieh = Math.round(this.cieh * 360);
    const a = Math.round(this.a * 100) / 100;
    return `lch(${ciel}% ${ciec} ${cieh} / ${a})`;
  }



  /************************************/
  /* Conversion between color formats */
  /************************************/

  /** Uses the r, g, b values to calculate the h, s, l values. */
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


  /** Uses the h, s, l values to calculate the r, g, b values. */
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


  /** Uses the h, s, l values to calculate the w, bk values. */
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


  /** Uses the h, w, bk values to calculate the s, l values. */
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


  /** Uses the r, g, b values to calulate the ciel, ciea, cieb values. */
  rgb2lab() {
    // Source of the math: https://www.w3.org/TR/css-color-4/#rgb-to-lab
    //                   & https://drafts.csswg.org/css-color-4/utilities.js
    //                   & https://drafts.csswg.org/css-color-4/conversions.js
    const linRGB = x => (Math.abs(x) < 0.04045) ? x / 12.92 : (Math.sign(x) || 1) * Math.pow((x + 0.055) / 1.055, 2.4);
    const r = linRGB(this.r);
    const g = linRGB(this.g);
    const b = linRGB(this.b);

    let x = 0.41239079926595934 * r + 0.357584339383878 * g + 0.1804807884018343 * b;
    let y = 0.21263900587151027 * r + 0.715168678767756 * g + 0.07219231536073371 * b;
    let z = 0.01933081871559182 * r + 0.11919477979462598 * g + 0.9505321522496607 * b;

    let x50 = 1.0479298208405488 * x + 0.022946793341019088 * y - 0.05019222954313557 * z;
    let y50 = 0.029627815688159344 * x + 0.990434484573249 * y - 0.01707382502938514 * z;
    let z50 = -0.009243058152591178 * x + 0.015055144896577895 * y + 0.7518742899580008 * z;

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


  /** Uses the ciel, ciec, cieh values to calculate the r, g, b values. */
  lch2rgb() {
    // Source of the math: https://css.land/lch/lch.js
    //                   & https://drafts.csswg.org/css-color-4/utilities.js
    //                   & https://drafts.csswg.org/css-color-4/conversions.js
    const conversion = (ciel, ciec, cieh) => {
      let ciea, cieb;
      ciea = ciec * Math.cos(cieh * Math.PI / 180);
      cieb = ciec * Math.sin(cieh * Math.PI / 180);

      const ε = 216/24389;
      const κ = 24389/27;
      const w = [0.96422, 1, 0.82521];

      let f1 = (ciel + 16) / 116;
      let f0 = ciea / 500 + f1;
      let f2 = f1 - cieb / 200;

      let x50 = (f0 ** 3 > ε) ? f0 ** 3 : (116 * f0 - 16) / κ;
      let y50 = (ciel > κ * ε) ? ((ciel + 16) / 116) ** 3 : ciel / κ;
      let z50 = (f2 ** 3 > ε) ? f2 ** 3 : (116 * f2 - 16) / κ;

      x50 = x50 * w[0];
      y50 = y50 * w[1];
      z50 = z50 * w[2];

      let x = 0.9554734527042182 * x50 - 0.023098536874261423 * y50 + 0.0632593086610217 * z50;
      let y = -0.028369706963208136 * x50 + 1.0099954580058226 * y50 + 0.021041398966943008 * z50;
      let z = 0.012314001688319899 * x50 - 0.020507696433477912 * y50 + 1.3303659366080753 * z50;

      let r = 3.2409699419045226 * x - 1.537383177570094 * y - 0.4986107602930034 * z;
      let g = -0.9692436362808796 * x + 1.8759675015077202 * y + 0.04155505740717559 * z;
      let b = 0.05563007969699366 * x - 0.20397695888897652 * y + 1.0569715142428786 * z;

      const gamRGB = x => (Math.abs(x) > 0.0031308) ? (Math.sign(x) || 1) * (1.055 * Math.pow(x, 1 / 2.4) - 0.055) : 12.92 * x;

      r = gamRGB(r);
      g = gamRGB(g);
      b = gamRGB(b);

      return [r, g, b];
    }

    const forceIntoGamut = (ciel, ciec, cieh) => {
      const ε1 = .000005;
      const condition = (l, c, h) => conversion(l, c, h).reduce((sum, x) => sum && x >= (-1 * ε1) && x <= (1 + ε1), true);
      if (condition(ciel, ciec, cieh)) return [ciel, ciec, cieh];

      const ε2 = .0001;
      let Cmin = 0;
      let Cmax = ciec;
      let _ciec = ciec / 2;

      while (Cmax - Cmin > ε2) {
        if (condition(ciel, _ciec, cieh)) Cmin = _ciec;
        else Cmax = _ciec;
        _ciec = (Cmin + Cmax) / 2;
      }

      return [ciel, _ciec, cieh];
    }

    const lch = forceIntoGamut(this.ciel * 100, this.ciec, this.cieh * 360);
    const rgb = conversion(...lch);

    this.r = Couleur.pRound(rgb[0]);
    this.g = Couleur.pRound(rgb[1]);
    this.b = Couleur.pRound(rgb[2]);
  }


  /** Uses the ciel, ciea, cieb values to calculate the ciec, cieh values. */
  lab2lch() {
    let ciec, cieh;
    ciec = Math.sqrt(this.ciea ** 2 + this.cieb ** 2);
    cieh = Couleur.parse(Math.atan2(this.cieb, this.ciea) * 180 / Math.PI, 'cieh');

    this.ciec = Couleur.pRound(ciec);
    this.cieh = Couleur.pRound(cieh);
  }


  /** Uses the ciel, ciec, cieh values to calculate the ciea, cieb values. */
  lch2lab() {
    let cieh = this.cieh * 360;
    let ciea, cieb;
    ciea = this.ciec * Math.cos(cieh * Math.PI / 180);
    cieb = this.ciec * Math.sin(cieh * Math.PI / 180);

    this.ciea = Couleur.pRound(ciea);
    this.cieb = Couleur.pRound(cieb);
  }



  /********************************/
  /* Color manipulation functions */
  /********************************/


  /* Color modification */


  /**
   * Modifies a color by changing a specific property.
   * @param {colorProperty} propriete - The color property that will be changed.
   * @param {(string|number)} valeur - The value that will be added to the property.
   * @param {Object} options
   * @param {boolean} options.replace - Whether the value should replace the previous value of the property, instead of being added to it.
   * @param {boolean} options.scale - Whether the value should be multiplied to the previous value of the property, instead of being added to it.
   * @returns {Couleur} The modified color.
   */
   change(propriete, valeur, options = {}) {
    const replace = (options === true) || ((typeof options.replace != 'undefined') ? options.replace : false);
    const scale = (typeof options.scale != 'undefined') ? options.scale : false;
    const val = scale ? Couleur.parse(valeur) : Couleur.parse(valeur, propriete, false);
    const changedColor = new Couleur(this.rgb);

    if (['r', 'g', 'b', 'a'].includes(propriete)) {
      const rgba = [this.r, this.g, this.b, this.a];
      switch (propriete) {
        case 'r': rgba[0] = replace ? val : scale ? this.r * val : this.r + val; break;
        case 'g': rgba[1] = replace ? val : scale ? this.g * val : this.g + val; break;
        case 'b': rgba[2] = replace ? val : scale ? this.b * val : this.b + val; break;
        case 'a': rgba[3] = replace ? val : scale ? this.a * val : this.a + val; break;
      }
      changedColor.rgb = [255 * rgba[0], 255 * rgba[1], 255 * rgba[2], rgba[3]];
    } else if (['h', 's', 'l'].includes(propriete)) {
      const hsla = [this.h, this.s, this.l, this.a];
      switch (propriete) {
        case 'h': hsla[0] = replace ? val : scale ? this.h * val : this.h + val; break;
        case 's': hsla[1] = replace ? val : scale ? this.s * val : this.s + val; break;
        case 'l': hsla[2] = replace ? val : scale ? this.l * val : this.l + val; break;
      }
      changedColor.hsl = [360 * hsla[0], `${100 * hsla[1]}%`, `${100 * hsla[2]}%`, hsla[3]];
    } else if (['w', 'bk'].includes(propriete)) {
      const hwba = [this.h, this.w, this.bk, this.a];
      switch (propriete) {
        case 'w': hwba[1] = replace ? val : scale ? this.w * val : this.w + val; break;
        case 'bk': hwba[2] = replace ? val : scale ? this.bk * val : this.bk + val; break;
      }
      changedColor.hwb = [360 * hwba[0], `${100 * hwba[1]}%`, `${100 * hwba[2]}%`, hwba[3]];
    } else if (['ciel', 'ciea', 'cieb'].includes(propriete)) {
      const laba = [this.ciel, this.ciea, this.cieb, this.a];
      switch (propriete) {
        case 'ciel': laba[0] = replace ? val : scale ? this.ciel * val : this.ciel + val; break;
        case 'ciea': laba[1] = replace ? val : scale ? this.ciea * val : this.ciea + val; break;
        case 'cieb': laba[2] = replace ? val : scale ? this.cieb * val : this.cieb + val; break;
      }
      changedColor.lab = [`${100 * laba[0]}%`, laba[1], laba[2], laba[3]];
    } else if (['ciec', 'cieh'].includes(propriete)) {
      const lcha = [this.ciel, this.ciec, this.cieh, this.a];
      switch (propriete) {
        case 'ciec': lcha[1] = replace ? val : scale ? this.ciec * val : this.ciec + val; break;
        case 'cieh': lcha[2] = replace ? val : scale ? this.cieh * val : this.cieh + val; break;
      }
      changedColor.lch = [`${100 * lcha[0]}%`, lcha[1], 360 * lcha[2], lcha[3]];
    }

    return changedColor;
  }

  /**
   * Modifies a color by replacing the value of a specific property.
   * This is an alias to change() with options.replace = true
   * @param {colorProperty} propriete - The color property that will be changed.
   * @param {(string|number)} valeur - The value that will replace the previous value of the property.
   * @returns {Couleur} The modified color.
   */
  replace(propriete, valeur) {
    return this.change(propriete, valeur, { replace: true, scale: false });
  }

  /**
   * Modifies a color by scaling the value of a specific property by a percentage.
   * This is an alias to change() with options.scale = true
   * @param {colorProperty} propriete - The color property that will be changed.
   * @param {(string|number)} valeur - The percentage that will be multiplied to the previous value of the property.
   * @returns {Couleur} The modified color.
   */
  scale(propriete, valeur) {
    return this.change(propriete, valeur, { replace: false, scale: true });
  }

  /** @returns {Couleur} The complementary color. */
  complement() { return this.change('h', 180); }

  /** @returns {Couleur} The inverse color. */
  negative() { return new Couleur(`rgb(${255 * (1 - this.r)}, ${255 * (1 - this.g)}, ${255 * (1 - this.b)}, ${this.a})`); }
  invert() { return this.negative(); }

  /** @returns {Couleur} The shade of grey of the color. */
  greyscale() { return new Couleur(`hsl(${360 * this.h}, 0%, ${100 * this.l}%, ${this.a})`); }
  grayscale() { return this.greyscale(); }

  /** @returns {Couleur} The sepia tone of the color. */
  sepia() {
    const r = Math.min(0.393 * this.r + 0.769 * this.g + 0.189 * this.b, 1);
    const g = Math.min(0.349 * this.r + 0.686 * this.g + 0.168 * this.b, 1);
    const b = Math.min(0.272 * this.r + 0.534 * this.g + 0.131 * this.b, 1);
    return new Couleur(`rgb(${255 * r}, ${255 * g}, ${255 * b}, ${this.a})`);
  }


  /* Color blending */


  /**
   * Blends colors together, in the order they were given.
   * @param  {...color} couleurs - Colors to blend.
   * @returns {Couleur} The resulting color.
   */
  static blend(...couleurs) {
    if (couleurs.length < 2) throw `You need at least 2 colors to blend`;
    const background = Couleur.check(couleurs.shift());
    const overlay = Couleur.check(couleurs.shift());
    let result;

    calculation: {
      if (overlay.a == 0) {
        result = background;
        break calculation;
      }

      const a = overlay.a + background.a * (1 - overlay.a);
      const r = (overlay.r * overlay.a + background.r * background.a * (1 - overlay.a)) / a;
      const g = (overlay.g * overlay.a + background.g * background.a * (1 - overlay.a)) / a;
      const b = (overlay.b * overlay.a + background.b * background.a * (1 - overlay.a)) / a;
      result = new Couleur(`rgb(${255 * r}, ${255 * g}, ${255 * b}, ${a})`);
    }

    if (couleurs.length == 0) return result;
    else                      return Couleur.blend(result, ...couleurs);
  }

  /** Non-static version of Couleur.blend */
  blend(...couleurs) {
    return Couleur.blend(this, ...couleurs);
  }


  /**
   * Solves the equation result = blend(background, ...overlays) with background unknown.
   * @param  {...color} couleurs - Colors to unblend.
   * @returns {?Couleur} The solution to the equation, if it has one.
   * @throws if the equation has an infinite amount of solutions.
   */
  static unblend(...couleurs) {
    if (couleurs.length < 2) throw `You need at least 2 colors to unblend`;
    const result = Couleur.check(couleurs.shift());
    const overlay = Couleur.check(couleurs.shift());
    let background;

    if (overlay.a == 1) {
      throw `Overlay color ${overlay.rgb} isn't transparent, so the background it was blended onto could have been any color`;
    }
    else if (overlay.a == 0)                background = result;
    else {
      if (result.a < overlay.a)             return null;
      else if (result.a == overlay.a) {
        if (Couleur.same(result, overlay))  background = new Couleur('transparent');
        else                                return null;
      }
      else {
        const a = Couleur.pRound((result.a - overlay.a) / (1 - overlay.a), 3);
        const r = Couleur.pRound((result.r * result.a - overlay.r * overlay.a) / (a * (1 - overlay.a)), 3);
        const g = Couleur.pRound((result.g * result.a - overlay.g * overlay.a) / (a * (1 - overlay.a)), 3);
        const b = Couleur.pRound((result.b * result.a - overlay.b * overlay.a) / (a * (1 - overlay.a)), 3);
        for (const x of [r, g, b]) {
          if (x < 0 - Couleur.tolerance || x > 1 + Couleur.tolerance) return null;
        }
        background = new Couleur(`rgb(${255 * r}, ${255 * g}, ${255 * b}, ${a})`);
      }
    }

    if (couleurs.length == 0) return background;
    else                      return Couleur.unblend(background, ...couleurs);
  }

  /** Non-static version of Couleur.unblend */
  unblend(...couleurs) {
    return Couleur.unblend(this, ...couleurs);
  }


  /**
   * Solves the equation result = blend(background, overlay) with overlay unknown.
   * @param {color} _couleur1 - The background color.
   * @param {color} _couleur2 - The resulting color after the supposed blend.
   * @param {?number} alpha - The alpha value you want the solution to have.
   * @param {?number} alphaStep - The step between the alpha values of the multiple solutions.
   * @returns {(Couleur|Couleur[]|null)} The solution(s) to the equation.
   */
  static whatToBlend(_couleur1, _couleur2, alpha, alphaStep = .1) {
    const background = Couleur.check(_couleur1);
    const result = Couleur.check(_couleur2);
    let overlay;

    const calculateSolution = a => {
      const r = Couleur.pRound((result.r * result.a - background.r * background.a * (1 - a)) / a, 3);
      const g = Couleur.pRound((result.g * result.a - background.g * background.a * (1 - a)) / a, 3);
      const b = Couleur.pRound((result.b * result.a - background.b * background.a * (1 - a)) / a, 3);
      for (const x of [r, g, b]) {
        if (x < 0 - Couleur.tolerance || x > 1 + Couleur.tolerance) throw `This color doesn't exist`;
      }
      return new Couleur(`rgb(${255 * r}, ${255 * g}, ${255 * b}, ${a})`);
    };

    // If alpha is known, we can find at most one solution
    if (!isNaN(alpha) && alpha >= 0 && alpha <= 1) {
      if (alpha == 0) {
        if (Couleur.same(background, result)) return new Couleur('transparent');
        else                                  return null;
      }           
      else if (alpha == 1)                   return result;
      else if (result.a < alpha)              return null;
      else if (result.a == alpha) {
        if (background.a > 0)                 return null;
        else                                  return result;
      }
    }

    // If alpha isn't known, we can find at most one solution per possible alpha value
    if (result.a < background.a)              return null;
    else if (result.a > background.a) {
      if (result.a == 1)                      overlay = result;
      else if (background.a == 0)             overlay = result;
      // If 0 < background.a < result.a < 1, we can find a unique solution
      else {
        const a = Couleur.pRound((result.a - background.a) / (1 - background.a), 3);
        if (!isNaN(alpha) && Math.abs(a - alpha) > Couleur.tolerance) return null;
        try { overlay = calculateSolution(a); }
        catch (error) { return null; }
      }
    }
    else if (result.a == background.a) {
      if (Couleur.same(result, background))   overlay = new Couleur('transparent');
      else if (background.a < 1)              return null;
      // If both result and background are opaque, there are multiple solutions (one per alpha value).
      // Let's calculate some of them.
      else {
        const solutions = [];
        if (!isNaN(alpha)) {
          try { overlay = calculateSolution(alpha); }
          catch { return null; }
        }
        else {
          for (let a = alphaStep; a < 1; a += alphaStep) {
            try {
              solutions.push(calculateSolution(a));
            } catch (error) { continue; }
          }
          switch (solutions.length) {
            case 0:  overlay = null; break;
            case 1:  overlay = solutions[0]; break;
            default: overlay = solutions;
          }
        }
      }
    }

    if (!isNaN(alpha)) return (overlay.a == alpha) ? overlay : null;
    else                          return overlay;
  }

  /** Non-static version of Couleur.whatToBlend */
  whatToBlend(couleur2, alpha, alphaStep = .1) {
    return Couleur.whatToBlend(this, couleur2, alpha, alphaStep);
  }


  /* Color comparison */


  /** @returns {number} Luminance of the color. */
  // (source of the math: https://www.w3.org/TR/2008/REC-WCAG20-20081211/#relativeluminancedef)
  get luminance() {
    let couleur = this;
    if (this.a < 1) throw `Can't calculate the luminance of a transparent color`;

    let arr = [couleur.r, couleur.g, couleur.b];
    for (let i = 0; i <= 2; i++) {
      let e = arr[i];
      if (e <= 0.03928) e = e / 12.92;
      else              e = Math.pow((e + 0.055) / 1.055, 2.4);
      arr[i] = e;
    }

    return Couleur.pRound(0.2126 * arr[0] + 0.7152 * arr[1] + 0.0722 * arr[2]);
  }


  /**
   * Computes the contrast between two colors.
   * @param {color} _couleur1
   * @param {color} _couleur2
   * @returns {number} Contrast between the two colors.
   */
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

  /** Non-static version of Couleur.contrast */
  contrast(couleur2) {
    return Couleur.contrast(this, couleur2);
  }


  /** 
   * Checks if black or white text would have better contrast with {this}.
   * @returns {('black'|'white')}
   */
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


  /**
   * Modifies the CIE lightness of a color to give it better contrast with referenceColor.
   * @param {color} referenceColor - The color whose contrast with {this} will be optimized.
   * @param {number} desiredContrast - The contrast to reach between referenceColor and {this}.
   * @param {number} step - The quantity that will be added to/substracted from {this.ciel} at each step.
   * @param {Object} options
   * @param {boolean} options.lower - Whether contrast should be lowered if it's already bigger than desiredContrast.
   * @param {number} options.maxIterations - The maximum number of times the color will be altered.
   * @param {('black'|'white'|null)} options.towards - 'black' if {this} should change towards black (lower its lightness),
   *                                                   'white' if {this} should change towards white (raise its lightness),
   *                                                   null if that choice should be made automatically (if the function
   *                                                   can't guess, 'black' will be chosen).
   * @returns {Couleur} The modified color which verifies Couleur.contrast(color, referenceColor) == desiredContrast.
   */
  improveContrast(referenceColor, desiredContrast, step = 2, options = {}) {
    if (typeof options.lower == 'undefined') options.lower = false;
    if (typeof options.maxIterations == 'undefined') options.maxIterations = 100;
    if (typeof options.towards == 'undefined') options.towards = null;

    let movingColor = new Couleur(`${this.rgb}`);
    let refColor = Couleur.check(referenceColor);

    // Let's measure the initial contrast
    // and decide if we want it to go up or down.
    let contrast = movingColor.contrast(refColor);
    let direction;
    if (contrast > desiredContrast)      direction = -1;
    else if (contrast < desiredContrast) direction = 1;
    else                                 direction = 0;
    if ((direction < 0 && !options.lower) || (direction == 0)) return this;

    // Let's measure the contrast of refColor with black and white to know if
    // desiredContrast can be reached by blackening or whitening movingColor.
    const contrastWhite = refColor.contrast('white');
    const contrastBlack = refColor.contrast('black');
    const towardsWhite = (direction > 0) ? contrastWhite >= desiredContrast
                                         : contrastWhite <= desiredContrast;
    const towardsBlack = (direction > 0) ? contrastBlack >= desiredContrast
                                         : contrastBlack <= desiredContrast;
    
    // Let's decide if we're going to raise blackness or whiteness
    // to reach desiredContrast.
    let towards;
    if (towardsWhite && !towardsBlack)            towards = 'white';
    else if (towardsBlack && !towardsWhite)       towards = 'black';
    else if (!towardsWhite && !towardsBlack) {
      if (options.towards !== null)               towards = options.towards;
      else if (contrastWhite > contrastBlack)     return new Couleur('white');
      else                                        return new Couleur('black');
    }
    else if (towardsWhite && towardsBlack)        towards = options.towards;
    if (towards === null) {
      if (refColor.ciel < movingColor.ciel)       towards = 'white';
      else if (refColor.ciel > movingColor.ciel)  towards = 'black';
      else                                        towards = 'black';
    }

    // We keep going as long as contrast is still below / over desiredContrast.
    const condition =  c => (direction > 0) ? (c < desiredContrast)
                                            : (c > desiredContrast);
    let i = 0;
    while (condition(contrast) && i < options.maxIterations) {
      i++;
      let newColor;

      // If movingColor is totally black (if towards black) or white (if towards white),
      // i.e. there's no way to go, stop.
      if ((towards == 'black' && movingColor.ciel == 0) || (towards == 'white' && movingColor.ciel == 1))
        break;

      // Let's try to raise contrast by increasing or reducing CIE lightness.
      const sign = (towards == 'white') ? 1 : -1;
      newColor = new Couleur(`lch(${100 * movingColor.ciel + sign * step}% ${movingColor.ciec} ${360 * movingColor.cieh})`);
      const newContrast = newColor.contrast(refColor);

      // If we overshot a little, stop.
      // (We want to overshoot when we're raising contrast, but not when we're lowering it!)
      const overshot = ((direction < 0) && (contrast > desiredContrast) && (newContrast < desiredContrast));
      if (overshot) break;

      // We're on our way, let's keep going!
      contrast = newContrast;
      movingColor = newColor;
    }

    // We're done!
    return movingColor;
  }


  /**
   * Calculates the distance between two colors in a certain format, by adding the difference between each of their properties.
   * If no format is given, calculates the average of the distances for all formats.
   * @param {color} _couleur1 
   * @param {color} _couleur2 
   * @param {?string} format - The format whose properties will be used to calculate the distance.
   * @param {number} tolerance - The tolerance value used to ignore some properties if they are close enough to value that render them useless.
   * @returns {number} The distance between the two colors.
   */
   static distance(_couleur1, _couleur2, format = null, tolerance = Couleur.tolerance) {
    const couleur1 = Couleur.check(_couleur1);
    const couleur2 = Couleur.check(_couleur2);

    const formats = ['rgb', 'hsl', 'hwb', 'lab', 'lch'];
    if (formats.includes(format)) {
      const properties = Couleur.propertiesOf(format);
      properties.push('a');

      // Let's add the difference for each property
      const distance = properties.reduce((sum, prop) => {
        // cieh has no effect when ciec is 0,
        // h and s have no effect when l is 0 or 1,
        // h has no effect when s is 0,
        // h has no effect when bk + w is 1
        if (
          (prop == 'cieh' && couleur1.ciec <= tolerance && couleur2.ciec <= tolerance)
          || (['s', 'h'].includes(prop) && couleur1.l >= 1 - tolerance && couleur2.l >= 1 - tolerance)
          || (['s', 'h'].includes(prop) && couleur1.l <= tolerance && couleur2.l <= tolerance)
          || (prop == 'h' && couleur1.s <= tolerance && couleur2.s <= tolerance)
          || (prop == 'h' && couleur1.bk + couleur1.w >= 1 - tolerance && couleur2.bk + couleur2.w >= 1 - tolerance)
        ) return sum;

        // All properties are between 0 and 1, except ciea and cieb who are roughly 100 times bigger
        const coefficient = ['ciea', 'cieb', 'ciec'].includes(prop) ? .01 : 1;
        return sum + coefficient * Math.abs(couleur1[prop] - couleur2[prop]);
      }, 0);
      return Couleur.pRound(distance, 3);
    } else {
      return formats.reduce((sum, format) => sum + Couleur.distance(couleur1, couleur2, format), 0) / formats.length;
    }
  }

  /** Non-static version of Couleur.distance */
  distance(couleur2, format = null, tolerance = Couleur.tolerance) {
    return Couleur.distance(this, couleur2, format, tolerance);
  }


  /**
   * Determines if two colors are the same, with a certain tolerance.
   * @param {color} _couleur1 
   * @param {color} _couleur2 
   * @param {number} tolerance - The minimum distance between the two colors to consider them different.
   * @returns {boolean} Whether the two colors are considered the same.
   */
  static same(_couleur1, _couleur2, tolerance = Couleur.tolerance) {
    const couleur1 = Couleur.check(_couleur1);
    const couleur2 = Couleur.check(_couleur2);

    if (Couleur.distance(couleur1, couleur2, null, tolerance) > tolerance) return false;
    else return true;
  }

  /** Non-static version of Couleur.same */
  same(couleur2, tolerance = Couleur.tolerance) {
    return Couleur.same(this, couleur2, tolerance);
  }


  /* Other functions */


  /**
   * Calculates the intermediate colors a gradient should use to go from one color to another without passing through the "desaturated zone".
   * @param {color} _start - The starting color of the gradient.
   * @param {color} _end - The ending color of the gradient.
   * @param {number} _steps - The number of steps in the gradient to go from start to end.
   * @param {string} format - The format whose properties will be used to compute the gradient.
   * @returns {Couleur[]} The array of colors in the gradient.
   */
  static gradient(_start, _end, _steps = 5, format = 'lch') {
    const start = Couleur.check(_start);
    const end = Couleur.check(_end);
    const steps = Math.min(Math.max(1, _steps), 100);
    const props = [...Couleur.propertiesOf(format), 'a'];
    const stepList = props.map(prop => {
      let step;
      switch (prop) {
        case 'h':
        case 'cieh':
          // Minimize the distance to travel through hues
          const stepUp = (360 * (end[prop] - start[prop]) % 360 + 360) % 360 / 360;
          const stepDown = (360 * (start[prop] - end[prop]) % 360 + 360) % 360 / 360;
          step = ((stepUp <= stepDown) ? stepUp : (-1 * stepDown)) / steps;
          break;
        default:
          step = (end[prop] - start[prop]) / steps;
      }
      return step;
    });

    const intermediateColors = [start];
    for (let i = 1; i < steps; i++) {
      let previous = intermediateColors[i - 1];
      const vals = props.map((prop, k) => previous[prop] + stepList[k]);
      let expr;
      switch (format) {
        case 'rgb': expr = `rgb(${vals[0] * 255}, ${vals[1] * 255}, ${vals[2] * 255}, ${vals[3]})`; break;
        case 'hsl': expr = `hsl(${vals[0] * 360}, ${vals[1] * 100}%, ${vals[2] * 100}%, ${vals[3]})`; break;
        case 'hwb': expr = `hwb(${vals[0] * 360} ${vals[1] * 100}% ${vals[2] * 100}% / ${vals[3]})`; break;
        case 'lab': expr = `lab(${vals[0] * 100}% ${vals[1]} ${vals[2]} / ${vals[3]})`; break;
        case 'lch': expr = `lch(${vals[0] * 100}% ${vals[1]} ${vals[2] * 360} / ${vals[3]})`; break;
      }
      try {
        const next = new Couleur(expr);
        intermediateColors.push(next);
        previous = next;
      } catch(error) {
        console.error(error);
      }
    }
    return [...intermediateColors, end];
  }

  /** Non-static version of Couleur.gradient */
  gradient(end, steps = 5, format = 'lch') {
    return Couleur.gradient(this, end, steps, format);
  }



  /**************/
  /* Color data */
  /**************/

  /**
   * Gets the list of color properties used in a certain format.
   * @param {string} format - Name of the color format.
   * @returns {string[]} Array of color property names.
   */
  static propertiesOf(format) {
    switch(format) {
      case 'rgb': return ['r', 'g', 'b'];
      case 'hsl': return ['h', 's', 'l'];
      case 'hwb': return ['h', 'w', 'bk'];
      case 'lab': return ['ciel', 'ciea', 'cieb'];
      case 'lch': return ['ciel', 'ciec', 'cieh'];
      default: return ['a', 'r', 'g', 'b', 'h', 's', 'l', 'w', 'bk', 'ciel', 'ciea', 'cieb', 'ciec', 'cieh'];
    }
  }

  /** @returns {string[]} Array of all color property names. */
  static get properties() {
    return Couleur.propertiesOf();
  }

  /** @returns {number} Tolerance value. Used for example as the maximum distance before two colors are considered different. */
  static get tolerance() {
    return .02;
  }

  /** @returns {{id: string, syntaxes: RegExp[]}[]} Array of supported syntaxes. */
  static get formats() {
    return [
      {
        id: 'HEX',
        syntaxes: [
          // #abc or #ABC
          /^#([a-fA-F0-9]{1})([a-fA-F0-9]{1})([a-fA-F0-9]{1})$/,
          // #aabbcc or #AABBCC
          /^#([a-fA-F0-9]{2})([a-fA-F0-9]{2})([a-fA-F0-9]{2})$/
        ]
      }, {
        id: 'HEXA',
        syntaxes: [
          // #abcd or #ABCD
          /^#([a-fA-F0-9]{1})([a-fA-F0-9]{1})([a-fA-F0-9]{1})([a-fA-F0-9]{1})$/,
          // #aabbccdd or #AABBCCDD
          /^#([a-fA-F0-9]{2})([a-fA-F0-9]{2})([a-fA-F0-9]{2})([a-fA-F0-9]{2})$/
        ]
      }, {
        id: 'RGB',
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
        syntaxes: [
          // hsl(<angle>, 100%, 100%)
          new RegExp(`^hsla?\\((${Couleur.vAng}), ?(${Couleur.vPer}), ?(${Couleur.vPer})\\)$`),
          // hsl(<angle> 100% 100%)
          new RegExp(`^hsla?\\((${Couleur.vAng}) (${Couleur.vPer}) (${Couleur.vPer})\\)$`)
        ]
      }, {
        id: 'HSLA',
        syntaxes: [
          // hsla(<angle>, 100%, 100%, .5) or hsla(<angle>, 100%, 100%, 50%)
          new RegExp(`^hsla?\\((${Couleur.vAng}), ?(${Couleur.vPer}), ?(${Couleur.vPer}), ?(${Couleur.vNP})\\)$`),
          // hsla(<angle> 100% 100% / .5) or hsl(<angle> 100% 100% / 50%)
          new RegExp(`^hsla?\\((${Couleur.vAng}) (${Couleur.vPer}) (${Couleur.vPer}) ?\\/ ?(${Couleur.vNP})\\)$`)
        ]
      }, {
        id: 'HWB',
        syntaxes: [
          // hwb(<angle>, 100%, 100%)
          new RegExp(`^hwba?\\((${Couleur.vAng}), ?(${Couleur.vPer}), ?(${Couleur.vPer})\\)$`),
          // hwb(<angle> 100% 100%)
          new RegExp(`^hwba?\\((${Couleur.vAng}) (${Couleur.vPer}) (${Couleur.vPer})\\)$`)
        ]
      }, {
        id: 'HWBA',
        syntaxes: [
          // hwba(<angle>, 100%, 100%, .5) or hsla(<angle>, 100%, 100%, 50%)
          new RegExp(`^hwba?\\((${Couleur.vAng}), ?(${Couleur.vPer}), ?(${Couleur.vPer}), ?(${Couleur.vNP})\\)$`),
          // hwba(<angle> 100% 100% / .5) or hsl(<angle> 100% 100% / 50%)
          new RegExp(`^hwba?\\((${Couleur.vAng}) (${Couleur.vPer}) (${Couleur.vPer}) ?\\/ ?(${Couleur.vNP})\\)$`)
        ]
      }, {
        id: 'LAB',
        syntaxes: [
          // lab(300% 25 40)
          new RegExp(`^lab\\((${Couleur.vPer}) (${Couleur.vNum}) (${Couleur.vNum})\\)$`),
          // lab(300% 25 40 / .5)
          new RegExp(`^lab\\((${Couleur.vPer}) (${Couleur.vNum}) (${Couleur.vNum}) ?\\/ ?(${Couleur.vNP})\\)$`)
        ]
      }, {
        id: 'LCH',
        syntaxes: [
          // lch(300% 25 <angle>)
          new RegExp(`^lch\\((${Couleur.vPer}) (${Couleur.vNum}) (${Couleur.vAng})\\)$`),
          // lch(300% 25 <angle> / .5)
          new RegExp(`^lch\\((${Couleur.vPer}) (${Couleur.vNum}) (${Couleur.vAng}) ?\\/ ?(${Couleur.vNP})\\)$`)
        ]
      }, {
        id: 'NAME',
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

  /** @returns {Object} List of named colors in CSS. */
  static get couleursNommees() {
    return { transparent: '00000000', aliceblue: 'f0f8ff', antiquewhite: 'faebd7', aqua: '00ffff', aquamarine: '7fffd4', azure: 'f0ffff', beige: 'f5f5dc', bisque: 'ffe4c4', black: '000000', blanchedalmond: 'ffebcd', blue: '0000ff', blueviolet: '8a2be2', brown: 'a52a2a', burlywood: 'deb887', cadetblue: '5f9ea0', chartreuse: '7fff00', chocolate: 'd2691e', coral: 'ff7f50', cornflowerblue: '6495ed', cornsilk: 'fff8dc', crimson: 'dc143c', cyan: '00ffff', darkblue: '00008b', darkcyan: '008b8b', darkgoldenrod: 'b8860b', darkgray: 'a9a9a9', darkgrey: 'a9a9a9', darkgreen: '006400', darkkhaki: 'bdb76b', darkmagenta: '8b008b', darkolivegreen: '556b2f', darkorange: 'ff8c00', darkorchid: '9932cc', darkred: '8b0000', darksalmon: 'e9967a', darkseagreen: '8fbc8f', darkslateblue: '483d8b', darkslategray: '2f4f4f', darkslategrey: '2f4f4f', darkturquoise: '00ced1', darkviolet: '9400d3', deeppink: 'ff1493', deepskyblue: '00bfff', dimgray: '696969', dimgrey: '696969', dodgerblue: '1e90ff', firebrick: 'b22222', floralwhite: 'fffaf0', forestgreen: '228b22', fuchsia: 'ff00ff', gainsboro: 'dcdcdc', ghostwhite: 'f8f8ff', gold: 'ffd700', goldenrod: 'daa520', gray: '808080', grey: '808080', green: '008000', greenyellow: 'adff2f', honeydew: 'f0fff0', hotpink: 'ff69b4', indianred: 'cd5c5c', indigo: '4b0082', ivory: 'fffff0', khaki: 'f0e68c', lavender: 'e6e6fa', lavenderblush: 'fff0f5', lawngreen: '7cfc00', lemonchiffon: 'fffacd', lightblue: 'add8e6', lightcoral: 'f08080', lightcyan: 'e0ffff', lightgoldenrodyellow: 'fafad2', lightgray: 'd3d3d3', lightgrey: 'd3d3d3', lightgreen: '90ee90', lightpink: 'ffb6c1', lightsalmon: 'ffa07a', lightseagreen: '20b2aa', lightskyblue: '87cefa', lightslategray: '778899', lightslategrey: '778899', lightsteelblue: 'b0c4de', lightyellow: 'ffffe0', lime: '00ff00', limegreen: '32cd32', linen: 'faf0e6', magenta: 'ff00ff', maroon: '800000', mediumaquamarine: '66cdaa', mediumblue: '0000cd', mediumorchid: 'ba55d3', mediumpurple: '9370d8', mediumseagreen: '3cb371', mediumslateblue: '7b68ee', mediumspringgreen: '00fa9a', mediumturquoise: '48d1cc', mediumvioletred: 'c71585', midnightblue: '191970', mintcream: 'f5fffa', mistyrose: 'ffe4e1', moccasin: 'ffe4b5', navajowhite: 'ffdead', navy: '000080', oldlace: 'fdf5e6', olive: '808000', olivedrab: '6b8e23', orange: 'ffa500', orangered: 'ff4500', orchid: 'da70d6', palegoldenrod: 'eee8aa', palegreen: '98fb98', paleturquoise: 'afeeee', palevioletred: 'd87093', papayawhip: 'ffefd5', peachpuff: 'ffdab9', peru: 'cd853f', pink: 'ffc0cb', plum: 'dda0dd', powderblue: 'b0e0e6', purple: '800080', rebeccapurple: '663399', red: 'ff0000', rosybrown: 'bc8f8f', royalblue: '4169e1', saddlebrown: '8b4513', salmon: 'fa8072', sandybrown: 'f4a460', seagreen: '2e8b57', seashell: 'fff5ee', sienna: 'a0522d', silver: 'c0c0c0', skyblue: '87ceeb', slateblue: '6a5acd', slategray: '708090', slategrey: '708090', snow: 'fffafa', springgreen: '00ff7f', steelblue: '4682b4', tan: 'd2b48c', teal: '008080', thistle: 'd8bfd8', tomato: 'ff6347', turquoise: '40e0d0', violet: 'ee82ee', wheat: 'f5deb3', white: 'ffffff', whitesmoke: 'f5f5f5', yellow: 'ffff00', yellowgreen: '9acd32' };
  }
}