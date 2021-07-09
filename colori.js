/**
 * Colori module
 * @author Remiscan <https://remiscan.fr>
 * @module colori.js
 */
export default class Couleur {
  /**
   * Creates a new Couleur object that contains r, g, b, a properties of the color.
   * These properties will take their values from sRGB color space, even if they're out of bounds.
   * (This means values <0 or >1 can be stored — they can be clamped to a specific color space when needed.)
   * @param {colorString} couleur - Color expression in a supported format.
   * @throws {string} when the parameter isn't a valid color string.
   */
  constructor(couleur) {
    /** @property {number} r - Red value (from 0 to 1) */
    this.r = null;
    /** @property {number} g - Green value (from 0 to 1) */
    this.g = null;
    /** @property {number} b - Blue value (from 0 to 1) */
    this.b = null;
    /** @property {number} a - Opacity (from 0 to 1) */
    this.a = null;

    if (couleur instanceof Couleur) {
      this.r = couleur.r;
      this.g = couleur.g;
      this.b = couleur.b;
      this.a = couleur.a;
    }

    else if (typeof couleur != 'string') {
      throw `Couleur objects can only be created from a String ; this is not one: ${couleur}`;
    }

    else {
      const format = Couleur.matchSyntax(couleur.trim());
      const isAlpha = (val, def = 1) => !!val ? val : (val === 0) ? 0 : def;

      switch (format.id) {
        case 'HEX':
          this.hex = [format.data[1], format.data[2], format.data[3], isAlpha(format.data[4], 'ff')];
          break;
        case 'RGB':
          this.rgb = [format.data[1], format.data[2], format.data[3], isAlpha(format.data[4])];
          break;
        case 'HSL':
          this.hsl = [format.data[1], format.data[2], format.data[3], isAlpha(format.data[4])];
          break;
        case 'HWB':
          this.hwb = [format.data[1], format.data[2], format.data[3], isAlpha(format.data[4])];
          break;
        case 'LAB':
          this.lab = [format.data[1], format.data[2], format.data[3], isAlpha(format.data[4])];
          break;
        case 'LCH':
          this.lch = [format.data[1], format.data[2], format.data[3], isAlpha(format.data[4])];
          break;
        case 'COLOR':
          this.setColor(format.data[1], [format.data[2], format.data[3], format.data[4], isAlpha(format.data[5])]);
          break;
        default:
          throw `${couleur} is not a valid color format`;
      }
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
    
    // Predetermine the format, to save regex-matching time
    let format;
    if (tri.slice(0, 1) === '#') format = allFormats[0];
    else switch (tri) {
      case 'rgb': format = allFormats[1]; break;
      case 'hsl': format = allFormats[2]; break;
      case 'hwb': format = allFormats[3]; break;
      case 'lab': format = allFormats[4]; break;
      case 'lch': format = allFormats[5]; break;
      case 'col': format = allFormats[6]; break;
      default:    format = allFormats[7];
    }

    // Check if the given string matches any color syntax
    for (const [k, syntaxe] of format.syntaxes.entries()) {
      const result = couleur.match(syntaxe);
      if (result != null && result[0] === couleur) {
        if (format.id === 'NAME') {
          if (couleur === 'transparent') return Couleur.matchSyntax('#00000000');
          const allNames = Couleur.couleursNommees;
          const name = couleur.toLowerCase();
          if (name in allNames) return Couleur.matchSyntax('#' + allNames[name]);
        }
        return {
          id: format.id,
          syntaxe: k,
          data: result
        };
      }
    }

    throw `${couleur} is not a valid color format`;
  }


  /**
   * Calculates all the color properties from the already defined ones.
   * @param {string} knownFormat - Name of the already know format.
   */
  compute(knownFormat, values) {
    let rgb;
    switch (knownFormat) {
      case 'rgb': rgb = values; break;
      case 'hsl': rgb = Utils.hsl2rgb(values); break;
      case 'hwb': rgb = Utils.hsl2rgb(Utils.hwb2hsl(values)); break;
      case 'lab': rgb = Utils.lab2rgb(values); break;
      case 'lch': rgb = Utils.lab2rgb(Utils.lch2lab(values)); break;
    }
    [this.r, this.g, this.b] = rgb.map(v => Couleur.pRound(v));
  }


  /**
   * Returns a float precise to the nth decimal.
   * @param {(number|string)} _x - The number to round.
   * @param {number} n - The number of decimals.
   * @returns {number} The float precise to the nth decimal.
   */
  static pRound(_x, n = 5) {
    let x = (typeof _x === 'number') ? _x : Number(_x);
    return Number(parseFloat(x.toPrecision(n)));
  }


  /**
   * Parses a number / percentage / angle into the correct format to store it.
   * @param {(string|number)} n - The value to parse.
   * @param {?string} type - The color parameter that has n as its value.
   * @param {object} options
   * @param {boolean} options.clamp - Whether the value should de clamped to its sRGB interval.
   * @returns {number} The properly parsed number.
   */
  static parse(n, type = null, options = {}) {
    if (typeof options.clamp === 'undefined') options.clamp = true;
    const clamp = options.clamp;

    // Alpha values:
    // from any % or any number
    // clamped to [0, 100]% or [0, 1]
    // to [0, 1]
    if (type === 'a') {
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
    // to [0, 360]
    else if(['h', 'cieh'].includes(type)) {
      let _n = parseFloat(n);
      // If n is a number
      if (new RegExp('^' + Couleur.vNum + '$').test(n)) {
        while (_n < 0) _n += 360;
        while (_n > 360) _n -= 360;
        return _n;
      }
      // If n is an angle
      if ((new RegExp('^' + Couleur.vAng + '$').test(n))) {
        if (String(n).slice(-3) === 'deg') {
          while (_n < 0) _n += 360;
          while (_n > 360) _n -= 360;
          return _n;
        } else if (String(n).slice(-4) === 'grad') {
          while (_n < 0) _n += 400;
          while (_n > 400) _n -= 400;
          return _n * 360 / 400;
        } else if (String(n).slice(-3) === 'rad') {
          _n = _n * 180 / Math.PI;
          while (_n < 0) _n += 360;
          while (_n > 360) _n -= 360;
          return _n;
        } else if (String(n).slice(-4) === 'turn') {
          while (_n < 0) _n += 1;
          while (_n > 1) _n -= 1;
          return _n * 360;
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
    else if (type === 'ciec') {
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


  /**
   * Unparses a value to the format that would be used in a CSS expression.
   * @param {string} prop - Name of the property that has the value.
   * @param {number} value - Value to unparse.
   * @param {object} options
   * @param {boolean} options.round - Whether to round the unparsed value.
   * @returns {string} The unparsed value, ready to insert in a CSS expression.
   */
  static unparse(prop, value, options = {}) {
    if (typeof options.round === 'undefined') options.round = true;
    const round = options.round;

    switch (prop) {
      case 'r': case 'g': case 'b':
        return round ? `${Math.round(255 * value)}` : `${255 * value}`;
      case 's': case 'l': case 'w': case 'bk': case 'ciel':
        return round ? `${Math.round(100 * value)}%` : `${100 * value}%`;
      case 'a':
        return round ? `${Math.round(100 * value) / 100}` : `${value}`;
      default:
        return round ? `${Math.round(value)}` : `${value}`;
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
    const values = props.map((p, i) => Couleur.parse(data[i], p));
    const isAlpha = (val, def = 1) => !!val ? val : (val === 0) ? 0 : def;
    this.a = Couleur.parse(isAlpha(data[3]), 'a');
    this.compute(format, values);
  }


  /* GENERAL GETTER */

  /**
   * Gets the CSS expression of a color.
   * @param {string} format - The format of the expression.
   * @param {number[]} rgba - The values of the r, g, b, a properties.
   * @param {object?} options
   * @param {boolean} options.round - Whether to round the values in the expression.
   * @param {string} options.clamp - Which color space the values should be clamped to.
   * @returns {string} The expression of the color in the requested format.
   */
  static expr(format, rgba, options = {}) {
    if (typeof options.round === 'undefined') options.round = true;
    if (typeof options.clamp === 'undefined') options.clamp = true;

    const space = format.replace('color-', '');
    const values = [...Couleur.convert('srgb', space, rgba.slice(0, 3)), rgba[3]];
    const a = Couleur.unparse('a', values[3], { round: options.round });

    // If the requested expression is of the color(space, ...) type
    if (format.slice(0, 5) === 'color') {
      let string = `color(${space}`;
      for (const [k, v] of Object.entries(values)) {
        if (Number(k) === values.length - 1) {
          if (a >= 1) break;
          string += ` / ${a}`;
        } else {
          string += ` ${options.round ? Math.round(10000 * v) / 10000 : v}`;
        }
      }
      string += `)`;
      return string;
    }

    // If the requested expression is of the ${format}(...) type
    const props = Couleur.propertiesOf(format);
    const [x, y, z] = props.map((p, k) => Couleur.unparse(p, values[k], { round: options.round }));

    switch (format) {
      case 'rgb': case 'rgba': case 'hsl': case 'hsla': {
        if ((format.length > 3 && format.slice(-1) === 'a') || a < 1)
          return `${format}(${x}, ${y}, ${z}, ${a})`;
        else
          return `${format}(${x}, ${y}, ${z})`;
      }
      default: {
        if ((format.length > 3 && format.slice(-1) === 'a') || a < 1)
          return `${format}(${x} ${y} ${z} / ${a})`;
        else
          return `${format}(${x} ${y} ${z})`;
      }
    }
  }


  /* ALL VALUES (r, g, b) */

  /**
   * Returns the values of the color in a given color space.
   * @param {string} space - The identifier of the color space.
   * @returns {number[]} The array of r, g, b values of the color.
   */
  values(_space = 'srgb', options = {}) {
    if (typeof options.alpha === 'undefined') options.alpha = true;
    if (typeof options.clamp === 'undefined') options.clamp = true;

    let rgb = [this.r, this.g, this.b];
    const space = Couleur.whichSpaceHasFormat(_space) || Couleur.space(_space);
    const format = _space;

    if (options.clamp) rgb = Couleur.clampToColorSpace(space.id, rgb);
    const values = Couleur.convert('srgb', format, rgb);
    if (options.alpha) values.push(this.a);
    return values;
  }


  /* NAME */

  /** @returns {?string} The approximate name of the color. */
  get name() {
    if (this.a === 1) {
      const allNames = Couleur.couleursNommees;
      const [r, g, b] = [255 * this.r, 255 * this.g, 255 * this.b];
      const tolerance = 255 * Couleur.tolerance;
      for (const [name, hex] of Object.entries(allNames)) {
        const [r2, g2, b2] = [parseInt(`${hex[0]}${hex[1]}`, 16), parseInt(`${hex[2]}${hex[3]}`, 16), parseInt(`${hex[4]}${hex[5]}`, 16)];
        if (Math.abs(r2 - r) + Math.abs(g2 - g) + Math.abs(b2 - b) < tolerance) return name;
      }
    }
    else if (this.a === 0) return 'transparent';
    return null;
  }

  /** @returns {?string} The exact name of the color. */
  get exactName() {
    if (this.a === 1) {
      const allNames = Couleur.couleursNommees;
      const hex6 = this.hex.slice(1);
      const name = Object.keys(allNames).find(k => (allNames[k] === hex6));
      return name || null;
    }
    else if (this.a === 0) return 'transparent';
    else                   return null;
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
    r = (r.length === 1) ? r.repeat(2) : r;
    r = parseInt(r, 16);
    
    g = hexa[1];
    g = (g.length === 1) ? g.repeat(2) : g;
    g = parseInt(g, 16);
    
    b = hexa[2];
    b = (b.length === 1) ? b.repeat(2) : b;
    b = parseInt(b, 16);
    
    a = hexa[3] || 'ff';
    a = (a.length === 1) ? a.repeat(2) : a;
    a = parseInt(a, 16) / 255;

    this.rgb = [r, g, b, a];
  }

  /** Alias to the hex setter. */
  set hexa(hexa) { this.hex = hexa; }

  /** @returns {string} Hexadecimal expression of the color. */
  get hex() {
    if (this.a < 1) return this.hexa;
    const values = Couleur.clampToColorSpace('srgb', this.values().slice(0, 3));
    const rgb = values.map(v => Couleur.pad(Math.round(v * 255).toString(16)));
    return `#${rgb[0]}${rgb[1]}${rgb[2]}`;
  }

  /** @returns {string} Hexadecimal (+ a) expression of the color. */
  get hexa() {
    const values = Couleur.clampToColorSpace('srgb', this.values());
    const rgb = values.map(v => Couleur.pad(Math.round(v * 255).toString(16)));
    return `#${rgb[0]}${rgb[1]}${rgb[2]}${rgb[3]}`;
  }


  /* RGB (functional) */
  
  /**
   * Calculates all properties of the color from its functional RGB expression.
   * @param {Array.<string|number>} rgba - The unparsed values of the r, g, b, a properties.
   */
  set rgb(rgba) { this.set(rgba, ['r', 'g', 'b'], 'rgb'); }
  set rgba(rgba) { this.rgb = rgba; }
  get rgb() { return Couleur.expr('rgb', this.values()); }
  get rgba() { return Couleur.expr('rgba', this.values()); }


  /* HSL */

  /**
   * Calculates all properties of the color from its HSL expression.
   * @param {Array.<string|number>} hsla - The unparsed values of the h, s, l, a properties.
   */
  set hsl(hsla) { this.set(hsla, ['h', 's', 'l'], 'hsl') }
  set hsla(hsla) { this.hsl = hsla; }
  get hsl() { return Couleur.expr('hsl', this.values()); }
  get hsla() { return Couleur.expr('hsla', this.values()); }


  /* HWB */

  /**
   * Calculates all properties of the color from its HWB expression.
   * @param {Array.<string|number>} hwba - The unparsed values of the h, w, bk, a properties.
   */
  set hwb(hwba) { this.set(hwba, ['h', 'w', 'bk'], 'hwb'); }
  set hwba(hwba) { this.hwb = hwba; }
  get hwb() { return Couleur.expr('hwb', this.values()); }
  get hwba() { return Couleur.expr('hwba', this.values()); }


  /* LAB */

  /**
   * Calculates all properties of the color from its LAB expression.
   * @param {Array.<string|number>} laba - The unparsed values of the ciel, ciea, cieb, a properties.
   */
  set lab(laba) { this.set(laba, ['ciel', 'ciea', 'cieb'], 'lab'); }
  set laba(laba) { this.lab = laba; }
  get lab() { return Couleur.expr('lab', this.values()); }
  get laba() { return Couleur.expr('laba', this.values()); }


  /* LCH */

  /**
   * Calculates all properties of the color from its LCH expression.
   * @param {Array.<string|number>} - The unparsed values of the ciel, ciec, cieh, a properties.
   */
  set lch(lcha) { this.set(lcha, ['ciel', 'ciec', 'cieh'], 'lch'); }
  set lcha(lcha) { this.lch = lcha; }
  get lch() { return Couleur.expr('lch', this.values()); }
  get lcha() { return Couleur.expr('lcha', this.values()); }


  /* PROFILED COLORS */

  /**
   * Calculates all properties of the color from its functional color() expression.
   * @param {Array.<string|number>} - The numerical values of the r, g, b, a properties.
   */
  setColor(space, values) {
    let rgb = values.slice(0, 3);
    const a = values[3];

    const clamp = v => Math.max(0, Math.min(v, 1));
    switch (space) {
      case 'srgb': case 'display-p3': case 'a98-rgb': case 'prophoto-rgb': case 'rec2020':
        rgb = rgb.map(v => clamp(v));
      case 'xyz':
        rgb = Couleur.convert(space, 'srgb', rgb);
        break;
      default:
        throw `The ${space} color space is not supported`;
    }

    const rgba = [...rgb, a];
    return this.set(rgba, [null, null, null], 'rgb');
  }

  /** Returns the expression on the color in a given color space.
   * @param {string} space - The identifier of the color space.
   * @returns {string} Expression of the color.
   */
  color(space) { return Couleur.expr(`color-${space}`, this.values()); }



  /********************************************/
  /* Setters and getters for color properties */
  /********************************************/

  
  /**
   * Recalculates the r, g, b properties of the color after modifying one of its properties.
   * @param {number} val - The parsed new value of the property.
   */
  set h(val) {
    const [x, s, l] = this.values('hsl');
    this.hsl = [val, s, l, this.a].map((v, k) => Couleur.unparse([...Couleur.propertiesOf('hsl'), 'a'][k], v));
  }

  set s(val) {
    const [h, x, l] = this.values('hsl');
    this.hsl = [h, val, l, this.a].map((v, k) => Couleur.unparse([...Couleur.propertiesOf('hsl'), 'a'][k], v));
  }

  set l(val) {
    const [h, s, x] = this.values('hsl');
    this.hsl = [h, s, val, this.a].map((v, k) => Couleur.unparse([...Couleur.propertiesOf('hsl'), 'a'][k], v));
  }

  set w(val) {
    const [h, x, bk] = this.values('hwb');
    this.hwb = [h, val, bk, this.a].map((v, k) => Couleur.unparse([...Couleur.propertiesOf('hwb'), 'a'][k], v));
  }

  set bk(val) {
    const [h, w, x] = this.values('hwb');
    this.hwb = [h, w, val, this.a].map((v, k) => Couleur.unparse([...Couleur.propertiesOf('hwb'), 'a'][k], v));
  }

  set ciel(val) {
    const [x, ciea, cieb] = this.values('lab');
    this.lab = [val, ciea, cieb, this.a].map((v, k) => Couleur.unparse([...Couleur.propertiesOf('lch'), 'a'][k], v));
  }

  set ciea(val) {
    const [ciel, x, cieb] = this.values('lab');
    this.lab = [ciel, val, cieb, this.a].map((v, k) => Couleur.unparse([...Couleur.propertiesOf('lab'), 'a'][k], v));
  }

  set cieb(val) {
    const [ciel, ciea, x] = this.values('lab');
    this.lab = [ciel, ciea, val, this.a].map((v, k) => Couleur.unparse([...Couleur.propertiesOf('lab'), 'a'][k], v));
  }

  set ciec(val) {
    const [ciel, x, cieh] = this.values('lch');
    this.lch = [ciel, val, cieh, this.a].map((v, k) => Couleur.unparse([...Couleur.propertiesOf('lch'), 'a'][k], v));
  }

  set cieh(val) {
    const [ciel, ciec, x] = this.values('lch');
    this.lch = [ciel, ciec, val, this.a].map((v, k) => Couleur.unparse([...Couleur.propertiesOf('lch'), 'a'][k], v));
  }

  /** @returns {number} Gets the parsed value of one of the color properties. */
  get h() { return this.values('hsl')[0]; }
  get s() { return this.values('hsl')[1]; }
  get l() { return this.values('hsl')[2]; }
  get w() { return this.values('hwb')[1]; }
  get bk() { return this.values('hwb')[2]; }
  get ciel() { return this.values('lab')[0]; }
  get ciea() { return this.values('lab')[1]; }
  get cieb() { return this.values('lab')[2]; }
  get ciec() { return this.values('lch')[1]; }
  get cieh() { return this.values('lch')[2]; }

  get luminance() {
    if (this.a < 1) throw `The luminance of a transparent color would be meaningless`;
    return Utils.luminance(this.values('rgb', { alpha: false }));
  }



  /* Clamping to color space */


  /**
   * Checks whether parsed r, g, b values in sRGB color space correspond to a color in a given color space.
   * @param {string} space - The identifier of the color space.
   * @param {number[]} rgb - Array of parsed r, g, b values in sRGB color space.
   * @returns {boolean} Whether r, g, b in ${space} color space are all in [0, 1].
   */
  static inColorSpace(_space, rgb) {
    const ε = .000005;
    const space = Couleur.whichSpaceHasFormat(_space) || Couleur.space(_space);
    switch (space.id) {
      case 'xyz': return true;
      case 'lab': { const ciel = Couleur.convert('srgb', 'lab', rgb)[0]; return ciel >= 0 && ciel <= 1; }
      default:    return Couleur.convert('srgb', space.id, rgb).every(v => v > (0 - ε) && v < (1 + ε));
    }
  }

  /**
   * Clamps parsed r, g, b values in sRGB color space to a given color space.
   * @param {string} space - The identifier of the color space.
   * @param {number[]} rgb - Array of parsed r, g, b values in sRGB color space.
   * @returns {number[]} The array of r, g, b values in sRGB color space, after clamping the color to ${space} color space.
   */
  static clampToColorSpace(_space, rgb) {
    // Source of the math: https://css.land/lch/lch.js
    const space = Couleur.whichSpaceHasFormat(_space) || Couleur.space(_space);
    if (Couleur.inColorSpace(space.id, rgb)) return rgb;
    let lch = Utils.lab2lch(Utils.rgb2lab(rgb));

    // If lightness is too low / high, clamp it and try again
    const ε = .00001;
    if (lch[0] < (0 - ε) || lch[0] > (1 + ε)) {
      lch[0] = Math.max(0, Math.min(lch[0], 1));
      const rgb = Utils.lab2rgb(Utils.lch2lab(lch));
      return Couleur.clampToColorSpace(space.id, rgb);
    }

    // If chroma is too high, clamp it
    const τ = .0001;
    let Cmin = 0;
    let Cmax = lch[1];
    lch[1] = lch[1] / 2;

    const condition = lch => Couleur.inColorSpace(space.id, Utils.lab2rgb(Utils.lch2lab(lch)));
    while (Cmax - Cmin > τ) {
      if (condition(lch)) Cmin = lch[1];
      else                Cmax = lch[1];
      lch[1] = (Cmin + Cmax) / 2;
    }

    return Utils.lab2rgb(Utils.lch2lab(lch));
  }


  


  /**
   * Converts the color values from one color space to another.
   * @param {string} startSpace - The identifier of the starting color space.
   * @param {string} endSpace - The identifier of the color space to convert to.
   * @param {*} values - Array of color values.
   * @returns {number[]} Array of values in the new color space.
   */
  static convert(startSpace, endSpace, values) {
    if (startSpace === endSpace) return values;

    // Source of the math: https://drafts.csswg.org/css-color/#predefined-to-predefined
    const supportedSpaces = Couleur.colorSpaces;
    const [start, end] = [startSpace, endSpace].map(id => supportedSpaces.find(s => s.id === id || s.otherFormats.includes(id)));

    if (typeof start === 'undefined' || typeof end === 'undefined')
      throw `Can not convert from ${startSpace} to ${endSpace}`;

    const functions = [];
    // If we're converting from another format
    if (startSpace !== start.id) functions.push(`${startSpace}2${start.otherFormats[0]}`);
    // If we're converting to another color spaces
    if (start.id !== end.id) {
      if (startSpace !== 'xyz') functions.push(
        `gam${start.prefix}_lin${start.prefix}`,
        `lin${start.prefix}_${start.whitepoint}XYZ`
      );
      if (start.whitepoint !== end.whitepoint) functions.push(
        `${start.whitepoint}XYZ_${end.whitepoint}XYZ`
      );
      if (endSpace !== 'xyz') functions.push(
        `${end.whitepoint}XYZ_lin${end.prefix}`,
        `lin${end.prefix}_gam${end.prefix}`
      );
    }
    // If we're converting to another format
    if (endSpace !== end.id) functions.push(`${end.otherFormats[0]}2${endSpace}`);

    let result = values;
    for (const fun of functions) {
      try {
        result = Utils[fun](result);
      } catch (error) { console.error(error, fun); }
    }
    return result;
  }

  convertTo(space) { return [...Couleur.convert('srgb', space, this.values('rgb', { alpha: false })), this.a]; }



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
    const val = scale ? Couleur.parse(valeur) : Couleur.parse(valeur, propriete, { clamp: false });
    const changedColor = new Couleur(this.rgb);

    const oldVal = this[propriete];
    const newVal = replace ? val : scale ? oldVal * val : oldVal + val;
    changedColor[propriete] = newVal;
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
  greyscale() {
    const L = 255 * this.replace('a', 1).luminance;
    return new Couleur(`rgb(${L}, ${L}, ${L}, ${this.a})`);
  }
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
    const background = new Couleur(couleurs.shift());
    const overlay = new Couleur(couleurs.shift());
    let mix;

    calculation: {
      if (overlay.a === 0) {
        mix = background;
        break calculation;
      }

      const a = overlay.a + background.a * (1 - overlay.a);
      const r = (overlay.r * overlay.a + background.r * background.a * (1 - overlay.a)) / a;
      const g = (overlay.g * overlay.a + background.g * background.a * (1 - overlay.a)) / a;
      const b = (overlay.b * overlay.a + background.b * background.a * (1 - overlay.a)) / a;
      mix = new Couleur(Couleur.expr('rgb', [r, g, b, a]));
    }

    if (couleurs.length === 0) return mix;
    else                       return Couleur.blend(mix, ...couleurs);
  }


  /**
   * Solves the equation mix = blend(background, ...overlays) with background unknown.
   * @param  {...color} couleurs - Colors to unblend.
   * @returns {?Couleur} The solution to the equation, if it has one.
   * @throws if the equation has an infinite amount of solutions.
   */
  static unblend(...couleurs) {
    if (couleurs.length < 2) throw `You need at least 2 colors to unblend`;
    const mix = new Couleur(couleurs.shift());
    const overlay = new Couleur(couleurs.shift());
    let background;

    if (overlay.a === 1) {
      throw `Overlay color ${overlay.rgb} isn't transparent, so the background it was blended onto could have been any color`;
    }
    else if (overlay.a === 0)           background = mix;
    else {
      if (mix.a < overlay.a)            return null;
      else if (mix.a === overlay.a) {
        if (Couleur.same(mix, overlay)) background = new Couleur('transparent');
        else                            return null;
      }
      else {
        const a = Couleur.pRound((mix.a - overlay.a) / (1 - overlay.a), 3);
        const r = Couleur.pRound((mix.r * mix.a - overlay.r * overlay.a) / (a * (1 - overlay.a)), 3);
        const g = Couleur.pRound((mix.g * mix.a - overlay.g * overlay.a) / (a * (1 - overlay.a)), 3);
        const b = Couleur.pRound((mix.b * mix.a - overlay.b * overlay.a) / (a * (1 - overlay.a)), 3);
        for (const x of [r, g, b]) {
          if (x < 0 - Couleur.tolerance || x > 1 + Couleur.tolerance) return null;
        }
        background = new Couleur(Couleur.expr('rgb', [r, g, b, a]));
      }
    }

    if (couleurs.length === 0) return background;
    else                       return Couleur.unblend(background, ...couleurs);
  }


  /**
   * Solves the equation mix = blend(background, overlay) with overlay unknown.
   * @param {color} _background - The background color.
   * @param {color} _mix - The resulting color after the supposed blend.
   * @param {?number} alpha - The alpha value you want the solution to have.
   * @param {?number} alphaStep - The step between the alpha values of the multiple solutions.
   * @returns {(Couleur|Couleur[]|null)} The solution(s) to the equation.
   */
  static whatToBlend(_background, _mix, alpha, alphaStep = .1) {
    const background = new Couleur(_background);
    const mix = new Couleur(_mix);
    let overlay;

    const calculateSolution = a => {
      const r = Couleur.pRound((mix.r * mix.a - background.r * background.a * (1 - a)) / a, 3);
      const g = Couleur.pRound((mix.g * mix.a - background.g * background.a * (1 - a)) / a, 3);
      const b = Couleur.pRound((mix.b * mix.a - background.b * background.a * (1 - a)) / a, 3);
      for (const x of [r, g, b]) {
        if (x < 0 - Couleur.tolerance || x > 1 + Couleur.tolerance) throw `This color doesn't exist`;
      }
      return new Couleur(Couleur.expr('rgb', [r, g, b, a]));
    };

    // If alpha is known, we can find at most one solution
    if (!isNaN(alpha) && alpha >= 0 && alpha <= 1) {
      if (alpha === 0) {
        if (Couleur.same(background, mix)) return new Couleur('transparent');
        else                               return null;
      }           
      else if (alpha === 1)                return mix;
      else if (mix.a < alpha)              return null;
      else if (mix.a === alpha) {
        if (background.a > 0)              return null;
        else                               return mix;
      }
    }

    // If alpha isn't known, we can find at most one solution per possible alpha value
    if (mix.a < background.a)              return null;
    else if (mix.a > background.a) {
      if (mix.a === 1)                     overlay = mix;
      else if (background.a === 0)         overlay = mix;
      // If 0 < background.a < mix.a < 1, we can find a unique solution
      else {
        const a = Couleur.pRound((mix.a - background.a) / (1 - background.a), 3);
        if (!isNaN(alpha) && Math.abs(a - alpha) > Couleur.tolerance) return null;
        try { overlay = calculateSolution(a); }
        catch (error) { return null; }
      }
    }
    else if (mix.a === background.a) {
      if (Couleur.same(mix, background))   overlay = new Couleur('transparent');
      else if (background.a < 1)           return null;
      // If both mix and background are opaque, there are multiple solutions (one per alpha value).
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

    if (!isNaN(alpha)) return (overlay.a === alpha) ? overlay : null;
    else               return overlay;
  }


  /* Color comparison */


  /**
   * Computes the contrast between two colors as defined by WCAG2 or 3.
   * @param {color} _text
   * @param {color} _background
   * @param {string} method - Whether to use the new APCA or the old WCAG2 method.
   * @returns {number} Contrast between the two colors.
   */
  static contrast(_text, _background, method = 'WCAG2') {
    const background = new Couleur(_background);
    if (background.a < 1) throw `The contrast with a transparent background color would be meaningless`;
    let text = new Couleur(_text);

    // If the text is transparent, blend it to the background to get its actual visible color
    if (text.a < 1) text = Couleur.blend(background, text);

    switch (method.toLowerCase()) {
      case 'wcag3': case 'apca':
        return Utils.APCAcontrast(text.values('rgb', { alpha: false }), background.values('rgb', { alpha: false }));
      default:
        return Utils.WCAG2contrast(text.values('rgb', { alpha: false }), background.values('rgb', { alpha: false }));
    }
  }

  /** Non-static version of Couleur.contrast */
  contrastWith(background, method = 'WCAG2') {
    return Couleur.contrast(this, background, method);
  }


  /** 
   * Checks if black or white text would have better contrast with {this}.
   * @returns {('black'|'white')}
   */
  contrastedText() {
    const L = this.luminance;
    const Lblack = 0, Lwhite = 1;
    const contrastes = [
      (L + 0.05) / (Lblack + 0.05),
      (Lwhite + 0.05) / (L + 0.05)
    ];
    return (contrastes[0] > contrastes[1]) ? 'black' : 'white';
  }


  /**
   * Modifies the CIE lightness of a color to give it better contrast with referenceColor.
   * @param {color} referenceColor - The color whose contrast with {this} will be optimized.
   * @param {number} desiredContrast - The contrast to reach between referenceColor and {this}.
   * @param {number} step - The quantity that will be added to/substracted from {this.ciel} at each step.
   * @param {Object} options
   * @param {boolean?} options.lower - Whether contrast should be lowered if it's already bigger than desiredContrast.
   * @param {number?} options.maxIterations - The maximum number of times the color will be altered.
   * @param {('black'|'white')?} options.towards - 'black' if {this} should change towards black (lower its lightness),
   *                                                   'white' if {this} should change towards white (raise its lightness),
   *                                                   null if that choice should be made automatically (if the function
   *                                                   can't guess, 'black' will be chosen).
   * @param {string?} options.contrastMethod - The method to use to compute the contrast. 'WCAG2' by default.
   * @returns {Couleur} The modified color which verifies Couleur.contrast(color, referenceColor) === desiredContrast.
   */
  improveContrastWith(referenceColor, desiredContrast, step = 2, options = {}) {
    if (typeof options.lower === 'undefined') options.lower = false;
    if (typeof options.maxIterations === 'undefined') options.maxIterations = 100;
    if (typeof options.towards === 'undefined') options.towards = null;
    if (typeof options.contrastMethod === 'undefined') options.contrastMethod = 'WCAG2';

    let movingColor = new Couleur(`${this.rgb}`);
    let refColor = new Couleur(referenceColor);

    // Let's measure the initial contrast
    // and decide if we want it to go up or down.
    let contrast = Couleur.contrast(movingColor, refColor, options.contrastMethod);
    let direction;
    if (contrast > desiredContrast)      direction = -1;
    else if (contrast < desiredContrast) direction = 1;
    else                                 direction = 0;
    if ((direction < 0 && options.lower === false) || (direction === 0)) return this;

    // Let's measure the contrast of refColor with black and white to know if
    // desiredContrast can be reached by blackening or whitening movingColor.
    const contrastWhite = Couleur.contrast(refColor, 'white', options.contrastMethod);
    const contrastBlack = Couleur.contrast(refColor, 'black', options.contrastMethod);
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
      if ((towards === 'black' && movingColor.ciel === 0) || (towards === 'white' && movingColor.ciel === 1))
        break;

      // Let's try to raise contrast by increasing or reducing CIE lightness.
      const sign = (towards === 'white') ? 1 : -1;
      newColor = movingColor.replace('ciel', Couleur.unparse('ciel', movingColor.ciel + sign * .01 * step));
      const newContrast = Couleur.contrast(newColor, refColor, options.contrastMethod);

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
   * Calculates the distance between two colors in a given color space.
   * Colors outside of that color space will be clamped to it.
   * @param {color} _couleur1 
   * @param {color} _couleur2 
   * @param {string} space - The color space in which distance will be measured.
   * @returns {number} The distance between the two colors in sRGB space.
   */
  static distance(_couleur1, _couleur2, options = {}) {
    if (typeof options.space === 'undefined') options.space = 'lab';
    if (typeof options.method === 'undefined') options.method = 'CIEDE2000';
    
    const couleur1 = new Couleur(_couleur1);
    const couleur2 = new Couleur(_couleur2);

    switch (options.method) {
      case 'CIEDE2000':
        return Utils.CIEDE2000([couleur1, couleur2].map(c => c.values('lab', { alpha: false, clamp: false })));
      case 'euclidean':
      default: {
        const [values1, values2] = [couleur1, couleur2].map(c => c.values('lab'));
        return values1.reduce((sum, v, k) => sum + (v - values2[k]) ** 2);
      }
    }
  }

  distanceTo(color, options) { return Couleur.distance(this, color, options); }


  /**
   * Determines if two colors are the same, with a certain tolerance.
   * @param {color} couleur1 
   * @param {color} couleur2 
   * @param {number} tolerance - The minimum distance between the two colors to consider them different.
   * @returns {boolean} Whether the two colors are considered the same.
   */
  static same(couleur1, couleur2, tolerance = Couleur.tolerance) {
    if (Couleur.distance(couleur1, couleur2) > tolerance) return false;
    else return true;
  }

  sameAs(color) { return Couleur.same(this, color); }


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
    const start = new Couleur(_start);
    const end = new Couleur(_end);
    const steps = Math.min(Math.max(1, _steps), 100);
    const props = [...Couleur.propertiesOf(format), 'a'];

    // Calculate by how much each property will be changed at each steap
    const stepList = props.map(prop => {
      let step;
      switch (prop) {
        case 'h':
        case 'cieh':
          // Minimize the distance to travel through hues
          const stepUp = ((end[prop] - start[prop]) % 360 + 360) % 360;
          const stepDown = ((start[prop] - end[prop]) % 360 + 360) % 360;
          step = ((stepUp <= stepDown) ? stepUp : -stepDown) / steps;
          break;
        default:
          step = (end[prop] - start[prop]) / steps;
      }
      return step;
    });

    // Calculate all colors of the gradient
    const intermediateColors = [props.map(prop => start[prop])];
    for (let i = 1; i < steps; i++) {
      let previous = intermediateColors[i - 1];
      const next = previous.map((prop, k) => prop + stepList[k]);
      intermediateColors.push(next);
      previous = next;
    }

    return [...intermediateColors.map(c => new Couleur(Couleur.expr(format, c))), end];
  }

  gradientTo(color, steps, format) { return Couleur.gradient(this, color, steps, format); }



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
      case 'rgb': case 'rgba': return ['r', 'g', 'b'];
      case 'hsl': case 'hsla': return ['h', 's', 'l'];
      case 'hwb': case 'hwba': return ['h', 'w', 'bk'];
      case 'lab': case 'laba': return ['ciel', 'ciea', 'cieb'];
      case 'lch': case 'lcha': return ['ciel', 'ciec', 'cieh'];
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

  /** @returns {{id: string, whitepoint: string, prefix: string, otherFormat: string[]}} Supported color spaces. */
  static get colorSpaces() {
    return [
      { id: 'srgb',         whitepoint: 'd65', prefix: 'RGB',      otherFormats: ['rgb', 'hsl', 'hwb'] },
      { id: 'display-p3',   whitepoint: 'd65', prefix: 'P3',       otherFormats: []                    },
      { id: 'a98-rgb',      whitepoint: 'd65', prefix: 'A98',      otherFormats: []                    },
      { id: 'prophoto-rgb', whitepoint: 'd50', prefix: 'PROPHOTO', otherFormats: []                    },
      { id: 'rec2020',      whitepoint: 'd65', prefix: 'REC2020',  otherFormats: []                    },
      { id: 'xyz',          whitepoint: 'd50', prefix: 'XYZ',      otherFormats: []                    },
      { id: 'lab',          whitepoint: 'd50', prefix: 'LAB',      otherFormats: ['lab', 'lch']        }
    ];
  }

  static space(id) { return Couleur.colorSpaces.find(s => s.id === id); }
  static whichSpaceHasFormat(format) { return Couleur.colorSpaces.find(s => s.otherFormats.includes(format)); }

  /** @returns {{id: string, syntaxes: RegExp[]}[]} Array of supported syntaxes. */
  static get formats() {
    return [
      {
        id: 'HEX',
        syntaxes: [
          // #abc or #ABC
          /^#([a-fA-F0-9]{1})([a-fA-F0-9]{1})([a-fA-F0-9]{1})$/,
          // #abcd or #ABCD
          /^#([a-fA-F0-9]{1})([a-fA-F0-9]{1})([a-fA-F0-9]{1})([a-fA-F0-9]{1})$/,
          // #aabbcc or #AABBCC
          /^#([a-fA-F0-9]{2})([a-fA-F0-9]{2})([a-fA-F0-9]{2})$/,
          // #aabbccdd or #AABBCCDD
          /^#([a-fA-F0-9]{2})([a-fA-F0-9]{2})([a-fA-F0-9]{2})([a-fA-F0-9]{2})$/
        ]
      }, {
        id: 'RGB',
        syntaxes: [
          // rgb(255, 255, 255) (spaces not required)
          new RegExp(`^rgba?\\((${Couleur.vNum}), ?(${Couleur.vNum}), ?(${Couleur.vNum})\\)$`),
          // rgb(255, 255, 255, .5) or rgb(255, 255, 255, 50%) (spaces not required)
          new RegExp(`^rgba?\\((${Couleur.vNum}), ?(${Couleur.vNum}), ?(${Couleur.vNum}), ?(${Couleur.vNP})\\)$`),
          // rgb(100%, 100%, 100%) (spaces not required)
          new RegExp(`^rgba?\\((${Couleur.vPer}), ?(${Couleur.vPer}), ?(${Couleur.vPer})\\)$`),
          // rgb(100%, 100%, 100%, .5) or rgb(100%, 100%, 100%, 50%) (spaces not required)
          new RegExp(`^rgba?\\((${Couleur.vPer}), ?(${Couleur.vPer}), ?(${Couleur.vPer}), ?(${Couleur.vNP})\\)$`),
          // rgb(255 255 255)
          new RegExp(`^rgba?\\((${Couleur.vNum}) (${Couleur.vNum}) (${Couleur.vNum})\\)$`),
          // rgba(255 255 255 / 50%) or rgba(255 255 255 / .5)
          new RegExp(`^rgba?\\((${Couleur.vNum}) (${Couleur.vNum}) (${Couleur.vNum}) ?\\/ ?(${Couleur.vNP})\\)$`),
          // rgb(100% 100% 100%)
          new RegExp(`^rgba?\\((${Couleur.vPer}) (${Couleur.vPer}) (${Couleur.vPer})\\)$`),
          // rgba(100% 100% 100% / 50%) or rgba(100% 100% 100% / .5)
          new RegExp(`^rgba?\\((${Couleur.vPer}) (${Couleur.vPer}) (${Couleur.vPer}) ?\\/ ?(${Couleur.vNP})\\)$`)
        ]
      }, {
        id: 'HSL',
        syntaxes: [
          // hsl(<angle>, 100%, 100%)
          new RegExp(`^hsla?\\((${Couleur.vAng}), ?(${Couleur.vPer}), ?(${Couleur.vPer})\\)$`),
          // hsla(<angle>, 100%, 100%, .5) or hsla(<angle>, 100%, 100%, 50%)
          new RegExp(`^hsla?\\((${Couleur.vAng}), ?(${Couleur.vPer}), ?(${Couleur.vPer}), ?(${Couleur.vNP})\\)$`),
          // hsl(<angle> 100% 100%)
          new RegExp(`^hsla?\\((${Couleur.vAng}) (${Couleur.vPer}) (${Couleur.vPer})\\)$`),
          // hsla(<angle> 100% 100% / .5) or hsl(<angle> 100% 100% / 50%)
          new RegExp(`^hsla?\\((${Couleur.vAng}) (${Couleur.vPer}) (${Couleur.vPer}) ?\\/ ?(${Couleur.vNP})\\)$`)
        ]
      }, {
        id: 'HWB',
        syntaxes: [
          // hwb(<angle> 100% 100%)
          new RegExp(`^hwb\\((${Couleur.vAng}) (${Couleur.vPer}) (${Couleur.vPer})\\)$`),
          // hwba(<angle> 100% 100% / .5) or hsl(<angle> 100% 100% / 50%)
          new RegExp(`^hwb\\((${Couleur.vAng}) (${Couleur.vPer}) (${Couleur.vPer}) ?\\/ ?(${Couleur.vNP})\\)$`)
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
        id: 'COLOR',
        syntaxes: [
          // color(display-p3 -0.6112 1.0079 -0.2192)
          new RegExp(`^color\\(([a-zA-Z0-9_-]+?) (${Couleur.vNum}) (${Couleur.vNum}) (${Couleur.vNum})\\)$`),
          // color(display-p3 -0.6112 1.0079 -0.2192 / .5)
          new RegExp(`^color\\(([a-zA-Z0-9_-]+?) (${Couleur.vNum}) (${Couleur.vNum}) (${Couleur.vNum}) ?\\/ ?(${Couleur.vNP})\\)$`)
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
    return { aliceblue: 'f0f8ff', antiquewhite: 'faebd7', aqua: '00ffff', aquamarine: '7fffd4', azure: 'f0ffff', beige: 'f5f5dc', bisque: 'ffe4c4', black: '000000', blanchedalmond: 'ffebcd', blue: '0000ff', blueviolet: '8a2be2', brown: 'a52a2a', burlywood: 'deb887', cadetblue: '5f9ea0', chartreuse: '7fff00', chocolate: 'd2691e', coral: 'ff7f50', cornflowerblue: '6495ed', cornsilk: 'fff8dc', crimson: 'dc143c', cyan: '00ffff', darkblue: '00008b', darkcyan: '008b8b', darkgoldenrod: 'b8860b', darkgray: 'a9a9a9', darkgrey: 'a9a9a9', darkgreen: '006400', darkkhaki: 'bdb76b', darkmagenta: '8b008b', darkolivegreen: '556b2f', darkorange: 'ff8c00', darkorchid: '9932cc', darkred: '8b0000', darksalmon: 'e9967a', darkseagreen: '8fbc8f', darkslateblue: '483d8b', darkslategray: '2f4f4f', darkslategrey: '2f4f4f', darkturquoise: '00ced1', darkviolet: '9400d3', deeppink: 'ff1493', deepskyblue: '00bfff', dimgray: '696969', dimgrey: '696969', dodgerblue: '1e90ff', firebrick: 'b22222', floralwhite: 'fffaf0', forestgreen: '228b22', fuchsia: 'ff00ff', gainsboro: 'dcdcdc', ghostwhite: 'f8f8ff', gold: 'ffd700', goldenrod: 'daa520', gray: '808080', grey: '808080', green: '008000', greenyellow: 'adff2f', honeydew: 'f0fff0', hotpink: 'ff69b4', indianred: 'cd5c5c', indigo: '4b0082', ivory: 'fffff0', khaki: 'f0e68c', lavender: 'e6e6fa', lavenderblush: 'fff0f5', lawngreen: '7cfc00', lemonchiffon: 'fffacd', lightblue: 'add8e6', lightcoral: 'f08080', lightcyan: 'e0ffff', lightgoldenrodyellow: 'fafad2', lightgray: 'd3d3d3', lightgrey: 'd3d3d3', lightgreen: '90ee90', lightpink: 'ffb6c1', lightsalmon: 'ffa07a', lightseagreen: '20b2aa', lightskyblue: '87cefa', lightslategray: '778899', lightslategrey: '778899', lightsteelblue: 'b0c4de', lightyellow: 'ffffe0', lime: '00ff00', limegreen: '32cd32', linen: 'faf0e6', magenta: 'ff00ff', maroon: '800000', mediumaquamarine: '66cdaa', mediumblue: '0000cd', mediumorchid: 'ba55d3', mediumpurple: '9370d8', mediumseagreen: '3cb371', mediumslateblue: '7b68ee', mediumspringgreen: '00fa9a', mediumturquoise: '48d1cc', mediumvioletred: 'c71585', midnightblue: '191970', mintcream: 'f5fffa', mistyrose: 'ffe4e1', moccasin: 'ffe4b5', navajowhite: 'ffdead', navy: '000080', oldlace: 'fdf5e6', olive: '808000', olivedrab: '6b8e23', orange: 'ffa500', orangered: 'ff4500', orchid: 'da70d6', palegoldenrod: 'eee8aa', palegreen: '98fb98', paleturquoise: 'afeeee', palevioletred: 'd87093', papayawhip: 'ffefd5', peachpuff: 'ffdab9', peru: 'cd853f', pink: 'ffc0cb', plum: 'dda0dd', powderblue: 'b0e0e6', purple: '800080', rebeccapurple: '663399', red: 'ff0000', rosybrown: 'bc8f8f', royalblue: '4169e1', saddlebrown: '8b4513', salmon: 'fa8072', sandybrown: 'f4a460', seagreen: '2e8b57', seashell: 'fff5ee', sienna: 'a0522d', silver: 'c0c0c0', skyblue: '87ceeb', slateblue: '6a5acd', slategray: '708090', slategrey: '708090', snow: 'fffafa', springgreen: '00ff7f', steelblue: '4682b4', tan: 'd2b48c', teal: '008080', thistle: 'd8bfd8', tomato: 'ff6347', turquoise: '40e0d0', violet: 'ee82ee', wheat: 'f5deb3', white: 'ffffff', whitesmoke: 'f5f5f5', yellow: 'ffff00', yellowgreen: '9acd32' };
  }
}

const Utils = {
  /**
   * All of these functions take an array of parsed color values,
   * and return an array of parsed color values converted into the 
   * desired color space or CSS format.
   * */



  /***********************************/
  /* Conversion between color spaces */
  /***********************************/


  // Source of the math: https://www.w3.org/TR/css-color-4/#rgb-to-lab
  //                   & https://drafts.csswg.org/css-color-4/utilities.js
  //                   & https://drafts.csswg.org/css-color-4/conversions.js

  /* srgb */

  gamRGB_linRGB: function(rgb) {
    return rgb.map(x => (Math.abs(x) < 0.04045) ? x / 12.92 : (Math.sign(x) || 1) * Math.pow((Math.abs(x) + 0.055) / 1.055, 2.4));
  },

  linRGB_gamRGB: function(rgb) {
    return rgb.map(x => (Math.abs(x) > 0.0031308) ? (Math.sign(x) || 1) * (1.055 * Math.pow(Math.abs(x), 1 / 2.4) - 0.055) : 12.92 * x);
  },

  linRGB_d65XYZ: function(rgb) {
    const [r, g, b] = rgb;
    return [
      0.41239079926595934 * r + 0.357584339383878 * g + 0.1804807884018343 * b,
      0.21263900587151027 * r + 0.715168678767756 * g + 0.07219231536073371 * b,
      0.01933081871559182 * r + 0.11919477979462598 * g + 0.9505321522496607 * b
    ];
  },

  d65XYZ_linRGB: function(xyz) {
    const [x, y, z] = xyz;
    return [
      3.2409699419045226 * x + -1.537383177570094 * y + -0.4986107602930034 * z,
      -0.9692436362808796 * x + 1.8759675015077202 * y + 0.04155505740717559 * z,
      0.05563007969699366 * x + -0.20397695888897652 * y + 1.0569715142428786 * z
    ];
  },

  /* display-p3 */

  gamP3_linP3: function(rgb) { return this.gamRGB_linRGB(rgb); },
  linP3_gamP3: function(rgb) { return this.linRGB_gamRGB(rgb); },
  
  linP3_d65XYZ: function(rgb) {
    const [r, g, b] = rgb;
    return [
      0.4865709486482162 * r + 0.26566769316909306 * g + 0.1982172852343625 * b,
		  0.2289745640697488 * r + 0.6917385218365064 * g + 0.079286914093745 * b,
		  0.0000000000000000 * r + 0.04511338185890264 * g + 1.043944368900976 * b
    ];
  },

  d65XYZ_linP3: function(xyz) {
    const [x, y, z] = xyz;
    return [
      2.493496911941425 * x + -0.9313836179191239 * y + -0.40271078445071684 * z,
		  -0.8294889695615747 * x + 1.7626640603183463 * y +  0.023624685841943577 * z,
		  0.03584583024378447 * x + -0.07617238926804182 * y + 0.9568845240076872 * z
    ];
  },

  /* prophoto-rgb */

  gamPROPHOTO_linPROPHOTO: function(rgb) {
    return rgb.map(v => Math.abs(v) <= 16/512 ? v / 16 : (Math.sign(x) || 1) * Math.pow(v, 1.8));
  },

  linPROPHOTO_gamPROPHOTO: function(rgb) {
    return rgb.map(v => Math.abs(v) >= 1/512 ? (Math.sign(v) || 1) * Math.pow(Math.abs(v), 1/1.8) : 16 * v);
  },

  linPROPHOTO_d50XYZ: function(rgb) {
    const [r, g, b] = rgb;
    return [
      0.7977604896723027 * r + 0.13518583717574031 * g + 0.0313493495815248 * b,
      0.2880711282292934 * r + 0.7118432178101014 * g + 0.00008565396060525902 * b,
      0.0 * r + 0.0 * g + 0.8251046025104601 * b
    ];
  },

  d50XYZ_linPROPHOTO: function(xyz) {
    const [x, y, z] = xyz;
    return [
      1.3457989731028281 * x + -0.25558010007997534 * y + -0.05110628506753401 * z,
	  	-0.5446224939028347 * x + 1.5082327413132781 * y + 0.02053603239147973 * z,
	  	0.0 * x + 0.0 * y + 1.2119675456389454 * z
    ];
  },

  /* a98-rgb */

  gamA98_linA98: function(rgb) {
    return rgb.map(v => (Math.sign(v) || 1) * Math.pow(Math.abs(v), 563/256));
  },

  linA98_gamA98: function(rgb) {
    return rgb.map(v => (Math.sign(v) || 1) * Math.pow(Math.abs(v), 256/563));
  },

  linA98_d65XYZ: function(rgb) {
    const [r, g, b] = rgb;
    return [
      0.5766690429101305 * r + 0.1855582379065463 * g + 0.1882286462349947 * b,
      0.29734497525053605 * r + 0.6273635662554661 * g + 0.07529145849399788 * b,
      0.02703136138641234 * r + 0.07068885253582723 * g + 0.9913375368376388 * b
    ];
  },

  d65XYZ_linA98: function(xyz) {
    const [x, y, z] = xyz;
    return [
      2.0415879038107465 * x + -0.5650069742788596 * y + -0.34473135077832956 * z,
      -0.9692436362808795 * x + 1.8759675015077202 * y + 0.04155505740717557 * z,
      0.013444280632031142 * x + -0.11836239223101838 * y + 1.0151749943912054 * z
    ];
  },

  /* rec2020 */

  gamREC2020_linREC2020: function(rgb) {
    const e = 1.09929682680944;
    return rgb.map(v => Math.abs(v) < 0.018053968510807 * 4.5 ? v / 4.5 : (Math.sign(v) || 1) * Math.pow(Math.pow.abs(v) + e - 1, 1/0.45));
  },

  linREC2020_gamREC2020: function(rgb) {
    const e = 1.09929682680944;
    return rgb.map(v => Math.abs(v) > 0.018053968510807 ? (Math.sign(v) || 1) * (e * Math.pow(Math.abs(v), 0.45) - (e - 1)) : 4.5 * v);
  },

  linREC2020_d65XYZ: function(rgb) {
    const [r, g, b] = rgb;
    return [
      0.6369580483012914 * r + 0.14461690358620832 * g + 0.1688809751641721 * b,
		  0.2627002120112671 * r + 0.6779980715188708 * g + 0.05930171646986196 * b,
		  0.000000000000000 * r + 0.028072693049087428 * g + 1.060985057710791 * b
    ];
  },

  d65XYZ_linREC2020: function(xyz) {
    const [x, y, z] = xyz;
    return [
      1.7166511879712674 * x + -0.35567078377639233 * y + -0.25336628137365974 * z,
		  -0.6666843518324892 * x + 1.6164812366349395 * y + 0.01576854581391113 * z,
		  0.017639857445310783 * x + -0.042770613257808524 * y + 0.9421031212354738 * z
    ];
  },

  /* lab */

  d50XYZ_LAB: function(xyz) {
    const ε = 216/24389;
    const κ = 24389/27;
    const w = [0.96422, 1, 0.82521];

    const [x, y, z] = xyz.map((v, k) => v / w[k]);
    const f = x => (x > ε) ? Math.cbrt(x) : (κ * x + 16) / 116;
    const [f0, f1, f2] = [x, y, z].map(v => f(v));
    return [
      (116 * f1 - 16) / 100,
      500 * (f0 - f1),
      200 * (f1 - f2)
    ];
  },

  LAB_d50XYZ: function(lab) {
    const ε = 216/24389;
    const κ = 24389/27;
    const w = [0.96422, 1, 0.82521];

    let [ciel, ciea, cieb] = lab;
    ciel = 100 * ciel;
    const f1 = (ciel + 16) / 116;
    const f0 = ciea / 500 + f1;
    const f2 = f1 - cieb / 200;

    const x = (f0 ** 3 > ε) ? f0 ** 3 : (116 * f0 - 16) / κ;
    const y = (ciel > κ * ε) ? ((ciel + 16) / 116) ** 3 : ciel / κ;
    const z = (f2 ** 3 > ε) ? f2 ** 3 : (116 * f2 - 16) / κ;
    return [x, y, z].map((v, k) => v * w[k]);
  },

  gamLAB_linLAB: function(lab) { return lab; },
  linLAB_gamLAB: function(lab) { return lab; },
  d50XYZ_linLAB: function(lab) { return this.d50XYZ_LAB(lab); },
  linLAB_d50XYZ: function(lab) { return this.LAB_d50XYZ(lab); },

  /* Bradford transform */

  d65XYZ_d50XYZ: function(xyz) {
    const [x, y, z] = xyz;
    return [
      1.0479298208405488 * x + 0.022946793341019088 * y + -0.05019222954313557 * z,
      0.029627815688159344 * x + 0.990434484573249 * y + -0.01707382502938514 * z,
      -0.009243058152591178 * x + 0.015055144896577895 * y + 0.7518742899580008 * z
    ];
  },

  d50XYZ_d65XYZ: function(xyz) {
    const [x, y, z] = xyz;
    return [
      0.9554734527042182 * x + -0.023098536874261423 * y + 0.0632593086610217 * z,
      -0.028369706963208136 * x + 1.0099954580058226 * y + 0.021041398966943008 * z,
      0.012314001688319899 * x + -0.020507696433477912 * y + 1.3303659366080753 * z
    ];
  },

  d50XYZ_d50XYZ: function(xyz) { return xyz; },
  d65XYZ_d65XYZ: function(xyz) { return xyz; },



  /**********************************/
  /* Conversion between CSS formats */
  /**********************************/


   rgb2hsl: function(rgb) {
    // (source of the math: https://en.wikipedia.org/wiki/HSL_and_HSV#General_approach)
    const [r, g, b] = rgb; // all in [0, 1]

    const max = Math.max(r, g, b);
    const min = Math.min(r, g, b);
    const chroma = max - min;

    const l = (max + min) / 2;

    let h;
    if (chroma === 0) h = 0;
    else switch (max) {
      case r: h = (g - b) / chroma; break;
      case g: h = (b - r) / chroma + 2; break;
      case b: h = (r - g) / chroma + 4; break;
    }
    h = 60 * h;
    while (h < 0)   h += 360;
    while (h > 360) h -= 360;

    let s;
    if (l === 0 || l === 1) s = 0;
    else if (l <= 0.5)      s = chroma / (2 * l);
    else                    s = chroma / (2 - 2 * l);

    return [h, s, l]; // h in [0, 360], s & l in [0, 1]
  },

  hsl2rgb: function(hsl) {
    // source of the math: https://en.wikipedia.org/wiki/HSL_and_HSV#HSL_to_RGB_alternative
    const [h, s, l] = hsl; // h in [0, 360], s & l in [0, 1]

    const m = s * Math.min(l, 1 - l);
    const k = n => (n + h / 30) % 12;
    const f = n => l - m * Math.max(Math.min(k(n) - 3, 9 - k(n), 1), -1);

    const r = f(0);
    const g = f(8);
    const b = f(4);

    return [r, g, b]; // all in [0, 1]
  },

  hsl2hwb: function(hsl) {
    // Source of the math: https://en.wikipedia.org/wiki/HSL_and_HSV#HSV_to_HSL
    //                   & http://alvyray.com/Papers/CG/HWB_JGTv208.pdf
    const [h, s, l] = hsl; // h in [0, 360], s & l in [0, 1]

    let _s;
    const v = l + s * Math.min(l, 1 - l);
    if (v === 0) _s = 0;
    else         _s = 2 - 2 * l / v;

    const w = (1 - _s) * v;
    const bk = 1 - v;

    return [h, w, bk]; // h in [0, 360], w & bk in [0, 1]
  },

  hwb2hsl: function(hwb) {
    // Source of the math: https://en.wikipedia.org/wiki/HSL_and_HSV#HSV_to_HSL
    //                   & http://alvyray.com/Papers/CG/HWB_JGTv208.pdf
    const [h, w, bk] = hwb; // h in [0, 360], w & bk in [0, 1]

    let _w = w, _bk = bk;
    if (w + bk > 1) {
      _w = w / (w + bk);
      _bk = bk / (w + bk);
    }

    let _s;
    const v = 1 - _bk;
    if (_bk === 1) _s = 0;
    else           _s = 1 - _w / v;

    let s;
    const l = v - v * _s / 2;
    if (l === 0 || l === 1) s = 0;
    else                    s = (v - l) / Math.min(l, 1 - l);

    return [h, s, l]; // h in [0, 360], s & l in [0, 1]
  },

  lab2lch: function(lab) {
    const [ciel, ciea, cieb] = lab;
    const ciec = Math.sqrt(ciea ** 2 + cieb ** 2);
    let cieh = Math.atan2(cieb, ciea) * 180 / Math.PI;
    while (cieh < 0) cieh += 360;
    while (cieh > 360) cieh -= 360;

    return [ciel, ciec, cieh];
  },

  lch2lab: function(lch) {
    const [ciel, ciec, cieh] = lch;
    const ciea = ciec * Math.cos(cieh * Math.PI / 180);
    const cieb = ciec * Math.sin(cieh * Math.PI / 180);

    return [ciel, ciea, cieb];
  },

  rgb2rgb: function(rgb) { return rgb; },
  rgb2hwb: function(rgb) { return this.hsl2hwb(this.rgb2hsl(rgb)); },
  hwb2rgb: function(hwb) { return this.hsl2rgb(this.hwb2hsl(hwb)); },
  rgb2lab: function(rgb) { return this.convert('srgb', 'lab', rgb); },
  lab2rgb: function(lab) { return this.convert('lab', 'srgb', lab); },



  /**********************/
  /* Contrast functions */
  /**********************/


  /** @returns {number} Luminance of the color. */
  // (source of the math: https://www.w3.org/TR/2008/REC-WCAG20-20081211/#relativeluminancedef)
  luminance: function(_rgb) {
    const rgb = Utils.gamRGB_linRGB(_rgb);
    return 0.2126 * rgb[0] + 0.7152 * rgb[1] + 0.0722 * rgb[2];
  },


  /**
   * Computes the contrast between two colors as defined by WCAG2.
   * @param {number[]} rgbText - Array of r, g, b values of the text.
   * @param {number[]} rgbBack - Array of r, g, b values of the background.
   * @returns {number} Contrast between the two colors, in [1, 21].
   */
  // (source of the math: https://www.w3.org/TR/2008/REC-WCAG20-20081211/#contrast-ratiodef)
  WCAG2contrast: function(rgbText, rgbBack) {
    const L1 = Utils.luminance(rgbText);
    const L2 = Utils.luminance(rgbBack);
    const Lmax = Math.max(L1, L2);
    const Lmin = Math.min(L1, L2);
    return (Lmax + 0.05) / (Lmin + 0.05);
  },


  /**
   * Computes the SAPC/APCA contrast between two colors as defined by WCAG3.
   * @param {number[]} rgbText - Array of r, g, b values of the text.
   * @param {number[]} rgbBack - Array of r, g, b values of the background.
   * @returns {number} Contrast between the two colors.
   */
  // (Source of the math: https://github.com/Myndex/SAPC-APCA)
  APCAcontrast: function(rgbText, rgbBack) {
    // 1. Compute luminances
    const coeffs = [0.2126729, 0.7151522, 0.0721750];
    const gamma = 2.4;
    const luminance = rgb => rgb.reduce((sum, v, i) => sum + Math.pow(v, gamma) * coeffs[i], 0);
    let [Ltext, Lback] = [rgbText, rgbBack].map(rgb => luminance(rgb));

    // 2. Clamp luminances
    const blackClampTrigger = 0.03;
    const blackClampPow = 1.45;
    [Ltext, Lback] = [Ltext, Lback].map(L => L > blackClampTrigger ? L : L + Math.pow(blackClampTrigger - L, blackClampPow));

    const δLmin = 0.0005;
    if (Math.abs(Ltext - Lback) < δLmin) return 0;

    // 3. Compute contrast
    let result;
    const scale = 1.25;
    const compute = (Lback, Ltext, powBack, powText) => (Math.pow(Lback, powBack) - Math.pow(Ltext, powText)) * scale;
    const lowClip = 0.001, lowTrigger = 0.078, lowOffset = 0.06, invLowTrigger = 12.82051282051282;

    // for dark text on light background
    if (Lback > Ltext) {
      const powBack = 0.55, powText = 0.58;
      const SAPC = compute(Lback, Ltext, powBack, powText);
      result = (SAPC < lowClip) ? 0
             : (SAPC < lowTrigger) ? SAPC * (1 - lowOffset * invLowTrigger)
             : SAPC - lowOffset;
    }

    // for light text on dark background
    else {
      const powBack = 0.62, powText = 0.57;
      const SAPC = compute(Lback, Ltext, powBack, powText);
      result = (SAPC > -lowClip) ? 0
             : (SAPC > -lowTrigger) ? SAPC * (1 - lowOffset * invLowTrigger)
             : SAPC + lowOffset;
    }

    return result * 100;
  },



  /**********************/
  /* Distance functions */
  /**********************/


  CIEDE2000: function([[L1, a1, b1], [L2, a2, b2]]) {
    // Source of the math: http://www2.ece.rochester.edu/~gsharma/ciede2000/ciede2000noteCRNA.pdf
    const C1 = Math.sqrt(a1 ** 2 + b1 ** 2);
    const C2 = Math.sqrt(a2 ** 2 + b2 ** 2);

    const mC = (C1 + C2) / 2,
          G = 0.5 * (1 - Math.sqrt(mC ** 7 / (mC ** 7 + 25 ** 7))),
          aa1 = (1 + G) * a1,
          aa2 = (1 + G) * a2,
          CC1 = Math.sqrt(aa1 ** 2 + b1 ** 2),
          CC2 = Math.sqrt(aa2 ** 2 + b2 ** 2);
    let hh1 = CC1 === 0 ? 0 : Math.atan2(b1, aa1) * 180 / Math.PI,
        hh2 = CC2 === 0 ? 0 : Math.atan2(b2, aa2) * 180 / Math.PI;
    while (hh1 < 0) hh1 += 360; while (hh1 > 360) hh1 -= 360;
    while (hh2 < 0) hh2 += 360; while (hh2 > 360) hh2 -= 360;

    const dL = L2 - L1,
          dC = CC2 - CC1;
    const dhh = (CC1 * CC2 === 0) ? 0
              : (Math.abs(hh2 - hh1) <= 180) ? hh2 - hh1
              : (hh2 - hh1 > 180) ? hh2 - hh1 - 360
              : hh2 - hh1 + 360;
    const dH = 2 * Math.sqrt(CC1 * CC2) * Math.sin((Math.PI / 180) * (dhh / 2));

    const mL = (L1 + L2) / 2,
          mCC = (CC1 + CC2) / 2;
    const mhh = (CC1 * CC2 === 0) ? hh1 + hh2
              : (Math.abs(hh2 - hh1) <= 180) ? (hh1 + hh2) / 2
              : (hh1 + hh2 >= 360) ? (hh1 + hh2 - 360) / 2
              : (hh1 + hh2 + 360) / 2;
    const T = 1 - 0.17 * Math.cos((Math.PI / 180) * (mhh - 30))
                + 0.24 * Math.cos((Math.PI / 180) * (2 * mhh))
                + 0.32 * Math.cos((Math.PI / 180) * (3 * mhh + 6))
                - 0.20 * Math.cos((Math.PI / 180) * (4 * mhh - 63)),
          dTH = 30 * Math.exp(-1 * ((mhh - 275) / 25) ** 2),
          RC = 2 * Math.sqrt(mCC ** 7 / (mCC ** 7 + 25 ** 7)),
          SL = 1 + (0.015 * (mL - 50) ** 2) / Math.sqrt(20 + (mL - 50) ** 2),
          SC = 1 + 0.045 * mCC,
          SH = 1 + 0.015 * mCC * T,
          RT = -1 * Math.sin((Math.PI / 180) * (2 * dTH)) * RC;

    return Math.sqrt(
      (dL / SL) ** 2
      + (dC / SC) ** 2
      + (dH / SH) ** 2
      + RT * (dC / SC) * (dH / SH)
    );
  }
};