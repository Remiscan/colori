import colorSpaces, { ColorSpace } from './color-spaces.js';
import * as Contrasts from './contrasts.js';
import * as Conversions from './conversion.js';
import { Format as CSSFormat, Formats, RegExps as ValueRegExps } from './css-formats.js';
import * as Distances from './distances.js';
import Graph from './graph.js';
import namedColors from './named-colors.js';
import * as OklabGamut from './oklab-gamut.js';
import * as Utils from './utils.js';



type colorString = string;
type colorArray = number[];
type colorObject = { r: number, g: number, b: number, a?: number };
type color = Couleur | colorObject | colorArray | colorString;
type colorProperty = 'r'|'g'|'b'|'a'|'h'|'s'|'l'|'w'|'bk'|'ciel'|'ciea'|'cieb'|'ciec'|'cieh'|'okl'|'oka'|'okb'|'okc'|'okh';
type colorSpaceOrID = ColorSpace | string;

/**
 * Colori module
 * @author Remiscan <https://remiscan.fr>
 * @module colori.js
 */

/** @class Couleur */
export default class Couleur {
  public r: number = 0;
  public g: number = 0;
  public b: number = 0;
  public a: number = 0;

  /**
   * Creates a new Couleur object that contains r, g, b, a properties of the color.
   * These properties will take their values from sRGB color space, even if they're out of bounds.
   * (This means values <0 or >1 can be stored — they can be clamped to a specific color space when needed.)
   * @param color Color expression in a supported type.
   * @throws When the parameter is not of a supported type.
   */
  constructor(color: color) {
    if (color instanceof Couleur || (typeof color === 'object' && 'r' in color && 'g' in color && 'b' in color)) {
      this.r = color.r;
      this.g = color.g;
      this.b = color.b;
      this.a = typeof color.a === 'number' ? color.a : 1;
    }

    else if (Array.isArray(color) && (color.length == 3 || color.length == 4)) {
      [this.r, this.g, this.b] = Couleur.toGamut('srgb', color.slice(0, 3), 'srgb', { method: 'naive' });
      this.a = Math.max(0, Math.min(Number(Utils.toUnparsedAlpha(color[3])), 1));
    }

    else if (typeof color === 'string') {
      const format = Couleur.matchSyntax(color.trim());

      switch (format.id) {
        case 'hex':
          this.setHex([format.data[1], format.data[2], format.data[3], Utils.toUnparsedAlpha(format.data[4], 'ff')]);
          break;
        case 'rgb':
        case 'hsl':
        case 'hwb':
        case 'lab':
        case 'lch':
        case 'oklab':
        case 'oklch': {
          const values = [format.data[1], format.data[2], format.data[3], Utils.toUnparsedAlpha(format.data[4])];
          const props: colorProperty[] = [...Couleur.propertiesOf(format.id), 'a'];
          const space = Couleur.getSpace(format.id);
          this.set(values, props, space);
        } break;
        case 'color':
          this.setColor(format.data[1], [format.data[2], format.data[3], format.data[4], Utils.toUnparsedAlpha(format.data[5])]);
          break;
        default:
          throw `${JSON.stringify(color)} is not a valid color format`;
      }
    }

    else throw `Couleur objects can only be created from a string, an array of parsed values, or another Couleur object ; this is not one: ${JSON.stringify(color)}`;
  }


  /**
   * Makes a Couleur from the argument if it's not one already.
   * @param color
   * @returns
   */
  protected static makeInstance(color: color): Couleur {
    if (color instanceof Couleur) return color;
    else                          return new Couleur(color);
  }


  /**
   * Matches the user input with supported color formats.
   * @param colorString Color expression in a supported format.
   * @returns Recognized syntax.
   * @throws When colorString is not in a valid format.
   */
  private static matchSyntax(colorString: colorString): { id: string, data: string[] } {
    const tri = colorString.slice(0, 3);
    
    // Predetermine the format, to save regex-matching time
    let format: CSSFormat | undefined;
    if (tri.slice(0, 1) === '#') format = Couleur.formats[0];
    else switch (tri) {
      case 'rgb': format = Couleur.formats[1]; break;
      case 'hsl': format = Couleur.formats[2]; break;
      case 'hwb': format = Couleur.formats[3]; break;
      case 'lab': format = Couleur.formats[4]; break;
      case 'lch': format = Couleur.formats[5]; break;
      case 'okl': {
        if (colorString.startsWith('oklab')) { format = Couleur.formats[6]; }
        else if (colorString.startsWith('oklch')) { format = Couleur.formats[7]; }
      } break;
      case 'col': format = Couleur.formats[8]; break;
      default:    format = Couleur.formats[9];
    }

    if (format == null) throw 'No matching format';

    // Check if the given string matches any color syntax
    for (const syntaxe of format.syntaxes) {
      const result = colorString.match(syntaxe);
      if (result != null && result[0] === colorString) {
        if (format.id === 'name') {
          if (colorString === 'transparent') return { id: 'rgb', data: ['', '0', '0', '0', '0'] };
          const allNames = Couleur.namedColors;
          const hex = allNames.get(colorString.toLowerCase()) || null;
          if (hex) return Couleur.matchSyntax(`#${hex}`);
        } else {
          return { id: format.id, data: result };
        }
      }
    }

    throw `${JSON.stringify(colorString)} is not a valid color format`;
  }


  /**
   * Parses a number / percentage / angle into the correct format to store it.
   * @param value The value to parse.
   * @param prop The color property that has n as its value.
   * @param options
   * @param options.clamp Whether the value should de clamped to its color space bounds.
   * @returns The properly parsed number.
   * @throws When the value isn't in a supported format for the corresponding property.
   */
  private static parse(value: number | string, prop: colorProperty | null = null, { clamp = true }: { clamp?: boolean } = {}): number {
    const val = String(value);
    const nval = parseFloat(val);

    try {
      switch (prop) {
        // Alpha values:
        // from any % or any number
        // clamped to [0, 100]% or [0, 1]
        // to [0, 1]
        case 'a': {
          // If n is a percentage
          if (new RegExp('^' + ValueRegExps.percentage + '$').test(val)) {
            if (clamp)  return Math.max(0, Math.min(nval / 100, 1));
            else        return nval / 100;
          }
          // If n is a number
          else if (new RegExp('^' + ValueRegExps.number + '$').test(val)) {
            if (clamp)  return Math.max(0, Math.min(nval, 1));
            else        return nval;
          }
          else throw 'invalid';
        }

        // Red, green, blue values:
        // from any % or any number
        // clamped to [0, 100]% or [0, 255]
        // to [0, 1]
        case 'r':
        case 'g':
        case 'b': {
          // If n is a percentage
          if (new RegExp('^' + ValueRegExps.percentage + '$').test(val)) {
            if (clamp)  return Math.max(0, Math.min(nval / 100, 1));
            else        return nval / 100;
          }
          // If n is a number
          else if (new RegExp('^' + ValueRegExps.number + '$').test(val)) {
            if (clamp)  return Math.max(0, Math.min(nval / 255, 1));
            else        return nval / 255;
          }
          else throw 'invalid';
        }


        // Hue and CIE hue values:
        // from any angle or any number
        // clamped to [0, 360]deg or [0, 400]grad or [0, 2π]rad or [0, 1]turn
        // to [0, 360]
        case 'h':
        case 'cieh':
        case 'okh': {
          let h = nval;
          // If n is a number
          if (new RegExp('^' + ValueRegExps.number + '$').test(val)) {
            return Utils.angleToRange(h);
          }
          // If n is an angle
          else if ((new RegExp('^' + ValueRegExps.angle + '$').test(val))) {
            if (val.slice(-3) === 'deg') {} // necessary to accept deg values
            else if (val.slice(-4) === 'grad')
              h = h * 360 / 400;
            else if (val.slice(-3) === 'rad')
              h = h * 180 / Math.PI;
            else if (val.slice(-4) === 'turn')
              h = h * 360;
            else throw 'angle';
            return Utils.angleToRange(h);
          }
          else throw 'invalid';
        }

        // Percentage values:
        // from any %
        // clamped to [0, 100]%
        // to [0, 1]
        case 's':
        case 'l':
        case 'w':
        case 'bk':
        case 'ciel':
        case 'okl': {
          // If n is a percentage
          if (new RegExp('^' + ValueRegExps.percentage + '$').test(val)) {
            if (clamp)  return Math.max(0, Math.min(nval / 100, 1));
            else        return nval / 100;
          }
          else throw 'invalid';
        }

        // CIE axes values:
        // any number
        case 'ciea':
        case 'cieb': {
          // If n is a number
          if (new RegExp('^' + ValueRegExps.number + '$').test(val)) {
            return nval;
          }
          else throw 'invalid';
        }

        // CIE chroma values:
        // from any number
        // clamped to [0, +Inf[
        case 'ciec': {
          // If n is a number
          if (new RegExp('^' + ValueRegExps.number + '$').test(val)) {
            if (clamp)  return Math.max(0, nval);
            else        return nval;
          }
          else throw 'invalid';
        }

        // OKLAB axes & chroma values:
        // any number
        case 'oka':
        case 'okb':
        case 'okc': {
          // If n is a number
          if (new RegExp('^' + ValueRegExps.number + '$').test(val)) {
            return nval / 100;
          }
          else throw 'invalid';
        }

        // Arbitrary values
        // from any % or any number
        // to any number (so that 0% becomes 0 and 100% becomes 1)
        default: {
          // If n is a percentage
          if (new RegExp('^' + ValueRegExps.percentage + '$').test(val)) {
            return nval / 100;
          }
          // If n is a number
          else if (new RegExp('^' + ValueRegExps.number + '$').test(val)) {
            return nval;
          }
          else throw 'invalidest'; // doesn't match any property value at all
        }
      }
    } catch (error) {
      if (error === 'invalid')    throw `Invalid ${JSON.stringify(prop)} value: ${JSON.stringify(value)}`;
      else if (error === 'angle') throw `Invalid angle value: ${JSON.stringify(value)}`;
      else                        throw `Invalid arbitrary value: ${JSON.stringify(value)}`;
    }
  }


  /**
   * Unparses a value to the format that would be used in a CSS expression.
   * @param value Value to unparse.
   * @param prop Name of the property that has the value.
   * @param options
   * @param options.precision How many decimals to display.
   * @returns The unparsed value, ready to insert in a CSS expression.
   */
  private static unparse(value: number, prop: colorProperty | null, { precision = 0 }: { precision?: number } = {}): string {
    switch (prop) {
      case 'r':
      case 'g':
      case 'b':
        return precision === null ? `${255 * value}` : `${Math.round(10**precision * 255 * value) / (10**precision)}`;
      case 's':
      case 'l':
      case 'w':
      case 'bk':
      case 'ciel':
      case 'okl':
        return precision === null ? `${100 * value}%` : `${Math.round(10**precision * 100 * value) / (10**precision)}%`;
      case 'oka':
      case 'okb':
      case 'okc':
        return precision === null ? `${100 * value}` : `${Math.round(10**precision * 100 * value) / (10**precision)}`;
      case 'a':
        return precision === null ? `${value}` : `${Math.round(10**Math.max(precision, 2) * value) / (10**Math.max(precision, 2))}`;
      default:
        return precision === null ? `${value}` : `${Math.round(10**precision * value) / (10**precision)}`;
    }
  }


  /**
   * Calculates all properties of a color from given unparsed values in a given color space.
   * @param data Array of unparsed values.
   * @param props Array of color property names the values correspond to.
   * @param spaceID Color space of the values, or its identifier.
   * @param options
   * @param options.parsed Whether the provided values are already parsed.
   */
  private set(data: Array<string|number>, props: Array<colorProperty|null>, spaceID: colorSpaceOrID, { parsed = false }: { parsed?: boolean } = {}) {
    const space = Couleur.getSpace(spaceID);
    const values = parsed ? data.map(v => Number(v)) : props.map((p, i) => Couleur.parse(data[i], p));
    [this.r, this.g, this.b] = Couleur.convert(space, 'srgb', values);

    this.a = Couleur.parse(Utils.toUnparsedAlpha(data[3]), 'a');
  }


  /**
   * Calculates all properties of the color from its hexadecimal expression.
   * @param hexa The hexadecimal values of the r, g, b, a properties.
   */
  private setHex(hexa: Array<string|number>) {
    let [r, g, b] = hexa.map(v => String(v));
    let a = String(hexa[3]) || 'ff';

    const vals = Utils.fromHex([r, g, b, a])
                      .map((v, k) => k === 3 ? v : v * 255);

    this.set(vals, ['r', 'g', 'b'], 'srgb');
  }


  /**
   * Calculates all properties of the color from its functional color() expression.
   * @param spaceID Identifier of the color space.
   * @param values The parsed values of the color's properties.
   * @throws When the color space is not supported.
   */
  private setColor(spaceID: string, values: Array<string|number>): void {
    let vals = values.slice(0, 3).map(v => Couleur.parse(v));
    const a = Couleur.parse(values[3]);

    switch (spaceID.toLowerCase()) {
      case 'srgb':
      case 'display-p3':
      case 'a98-rgb':
      case 'prophoto-rgb':
      case 'rec2020':
      case 'oklab':
      case 'oklch':
      case 'xyz':
        vals = Couleur.convert(spaceID, 'srgb', vals);
        break;
      default:
        if (spaceID.startsWith('--')) {
          const id = spaceID.substring(2);
          vals = Couleur.convert(id, 'srgb', vals);
        }
        else throw `The ${JSON.stringify(spaceID)} color space is not supported`;
    }

    const rgba = [...vals, a];
    return this.set(rgba, [null, null, null], 'srgb');
  }



  /*****************************/
  /* Getters for color formats */
  /*****************************/


  /* GENERAL EXPRESSION MAKER */

  /**
   * Creates a string containing the CSS expression of a color.
   * @param format Identifier of the color space of the requested CSS expression.
   * @param options
   * @param options.precision How many decimals to display.
   * @param options.clamp Which color space the values should be clamped to.
   * @returns The expression of the color in the requested format.
   */
  public expr(format: string, { precision = 0, clamp = true }: { precision?: number, clamp?: boolean } = {}): string {
    const _format = format.toLowerCase();
    const spaceID = _format.replace('color-', '');
    const space = Couleur.getSpace(spaceID);

    let values = this.valuesTo(space);
    if (clamp) values = Couleur.toGamut(space, values, space);
    const a = Number(Couleur.unparse(this.a, 'a', { precision }));
    values = [...values, a];

    // If the requested expression is of the color(space, ...) type
    if (_format.toLowerCase().slice(0, 5) === 'color') {
      let string = `color(${space.id}`;
      for (const [k, v] of Object.entries(values)) {
        if (Number(k) === values.length - 1) {
          if (a >= 1) break;
          string += ` / ${a}`;
        } else {
          string += ` ${precision === null ? v : Math.round(10**precision * v) / (10**precision)}`;
        }
      } 
      string += `)`;
      return string;
    }

    // If the requested expression is of the ${format}(...) type
    const props = Couleur.propertiesOf(_format);
    const [x, y, z] = props.map((p, k) => Couleur.unparse(values[k], p, { precision }));

    switch (_format.toLowerCase()) {
      case 'rgb':
      case 'rgba':
      case 'hsl':
      case 'hsla': {
        if ((_format.length > 3 && _format.slice(-1) === 'a') || a < 1)
          return `${_format}(${x}, ${y}, ${z}, ${a})`;
        else
          return `${_format}(${x}, ${y}, ${z})`;
      }
      default: {
        if (a < 1) return `${_format}(${x} ${y} ${z} / ${a})`;
        else       return `${_format}(${x} ${y} ${z})`;
      }
    }
  }

  /**
   * Creates a string containing the CSS expression of a color from a list of values.
   * @param format Identifier of the color space of the requested CSS expression.
   * @param values The values of the r, g, b, a properties.
   * @param valueSpaceID Color space of the given values, or its identifier.
   * @param options @see Couleur.expr
   * @returns The expression of the color in the requested format.
   */
  public static makeExpr(format: string, values: number[], valueSpaceID: colorSpaceOrID, options = {}): string {
    const _format = format.toLowerCase();
    const spaceID = _format.replace('color-', '');
    const rgba = [...Couleur.convert(valueSpaceID, spaceID, values.slice(0, 3)), values[3]];
    return (new Couleur(rgba)).expr(_format, options);
  }


  /* ALL VALUES (r, g, b) */

  /** @returns The array of r, g, b values of the color in sRGB color space. */
  public get values(): number[] { return [this.r, this.g, this.b]; }


  /* NAME */

  /** @returns The approximate name of the color. */
  public get name(): string | null {
    if (this.a === 1) {
      const allNames = Couleur.namedColors;
      const [r, g, b] = this.values;
      const tolerance = .02;
      for (const [name, hex] of allNames.entries()) {
        const [r2, g2, b2] = Utils.fromHex([`${hex[0]}${hex[1]}`, `${hex[2]}${hex[3]}`, `${hex[4]}${hex[5]}`]);
        if (Math.abs(r2 - r) + Math.abs(g2 - g) + Math.abs(b2 - b) < tolerance) return name;
      }
      return null;
    }
    else if (this.a === 0) return 'transparent';
    else                   return null;
  }

  /** @returns The exact name of the color. */
  public get exactName(): string | null {
    if (this.a === 1) {
      const allNames = Couleur.namedColors;
      const hex6 = this.hex.slice(1);
      for (const [name, hex] of allNames.entries()) {
        if (hex === hex6) return name;
      }
      return null;
    }
    else if (this.a === 0) return 'transparent';
    else                   return null;
  }

  /** @returns The name of the closest named color. */
  public get closestName(): string {
    if (this.a < .5) return 'transparent';
    const allNames = Couleur.namedColors;
    const [r, g, b] = this.values;
    let closest: string = '';
    let lastDistance = +Infinity;
    for (const [name, hex] of allNames.entries()) {
      const [r2, g2, b2] = Utils.fromHex([`${hex[0]}${hex[1]}`, `${hex[2]}${hex[3]}`, `${hex[4]}${hex[5]}`]);
      const distance = Math.abs(r2 - r) + Math.abs(g2 - g) + Math.abs(b2 - b);
      if (distance < lastDistance) {
        lastDistance = distance;
        closest = name;
      }
    }
    return closest;
  }


  /* CSS FORMATS */
  
  /** @returns Hexadecimal expression of the color. */
  public get hex(): string {
    const values = Couleur.toGamut('srgb', this.values);
    const rgb = Utils.toHex([...values, this.a]);
    if (this.a < 1) return `#${rgb[0]}${rgb[1]}${rgb[2]}${rgb[3]}`;
    else            return `#${rgb[0]}${rgb[1]}${rgb[2]}`;
  }

  /** @returns RGB expression of the color. */
  public get rgb(): string { return this.expr('rgb', { precision: 2 }); }
  public get rgba(): string { return this.rgb; }

  /** @returns HSL expression of the color. */
  public get hsl(): string { return this.expr('hsl', { precision: 2 }); }
  public get hsla(): string { return this.hsl; }

  /** @returns HWB expression of the color. */
  public get hwb(): string { return this.expr('hwb', { precision: 2 }); }

  /** @returns LAB expression of the color. */
  public get lab(): string { return this.expr('lab', { precision: 2 }); }

  /** @returns LCH expression of the color. */
  public get lch(): string { return this.expr('lch', { precision: 2 }); }

  /** @returns OKLAB expression of the color. */
  public get oklab(): string { return this.expr('oklab', { precision: 2 }); }

  /** @returns OKLCH expression of the color. */
  public get oklch(): string { return this.expr('oklch', { precision: 2 }); }



  /********************************************/
  /* Setters and getters for color properties */
  /********************************************/

  
  /**
   * Recalculates the r, g, b properties of the color after modifying one of its other properties.
   * @param val The parsed new value of the property.
   * @param prop The property to change.
   * @param format The id of the CSS format the property belongs to.
   * @throws When the CSS format doesn't have the requested property.
   */
  private recompute(val: number | string, prop: colorProperty, format: string) {
    const props: colorProperty[] = [...Couleur.propertiesOf(format), 'a'];
    if (!props.includes(prop))
      throw `Format ${format} does not have a property called ${prop}`;
    
    const parsedVal = (typeof val === 'string') ? Couleur.parse(val, prop) : val;
    const oldValues = [...this.valuesTo(format), this.a];
    const newValues = props.map((p, k) => {
      if (p === prop) return parsedVal;
      else            return oldValues[k];
    });
    this.set(newValues, props, format, { parsed: true });
  }

  public set red(val: number | string) { this.recompute(val, 'r', 'rgb'); }
  public set green(val: number | string) { this.recompute(val, 'g', 'rgb'); }
  public set blue(val: number | string) { this.recompute(val, 'b', 'rgb'); }
  public set alpha(val: number | string) { this.recompute(val, 'a', 'rgb'); }
  public set opacity(val: number | string) { this.recompute(val, 'a', 'rgb'); }

  public set h(val: number | string) { this.recompute(val, 'h', 'hsl'); }
  public set hue(val: number | string) { this.h = val; }

  public set s(val: number | string) { this.recompute(val, 's', 'hsl'); }
  public set saturation(val: number | string) { this.s = val; }

  public set l(val: number | string) { this.recompute(val, 'l', 'hsl'); }
  public set lightness(val: number | string) { this.l = val; }

  public set w(val: number | string) { this.recompute(val, 'w', 'hwb'); }
  public set whiteness(val: number | string) { this.w = val; }

  public set bk(val: number | string) { this.recompute(val, 'bk', 'hwb'); }
  public set blackness(val: number | string) { this.bk = val; }

  public set ciel(val: number | string) { this.recompute(val, 'ciel', 'lab'); }
  public set CIElightness(val: number | string) { this.ciel = val; }

  public set ciea(val: number | string) { this.recompute(val, 'ciea', 'lab'); }
  public set CIEa(val: number | string) { this.ciea = val; }

  public set cieb(val: number | string) { this.recompute(val, 'cieb', 'lab'); }
  public set CIEb(val: number | string) { this.cieb = val; }

  public set ciec(val: number | string) { this.recompute(val, 'ciec', 'lch'); }
  public set CIEchroma(val: number | string) { this.ciec = val; }

  public set cieh(val: number | string) { this.recompute(val, 'cieh', 'lch'); }
  public set CIEhue(val: number | string) { this.cieh = val; }

  public set okl(val: number | string) { this.recompute(val, 'okl', 'oklab'); }
  public set OKlightness(val: number | string) { this.okl = val; }

  public set oka(val: number | string) { this.recompute(val, 'oka', 'oklab'); }
  public set OKa(val: number | string) { this.oka = val; }

  public set okb(val: number | string) { this.recompute(val, 'okb', 'oklab'); }
  public set OKb(val: number | string) { this.okb = val; }

  public set okc(val: number | string) { this.recompute(val, 'okc', 'oklch'); }
  public set OKchroma(val: number | string) { this.okc = val; }

  public set okh(val: number | string) { this.recompute(val, 'okh', 'oklch'); }
  public set OKhue(val: number | string) { this.okh = val; }

  /** @returns Gets the parsed value of one of the color properties. */
  public get red(): number { return this.r; }
  public get green(): number { return this.g; }
  public get blue(): number { return this.b; }
  public get alpha(): number { return this.a; }
  public get opacity(): number { return this.a; }
  public get h(): number { return this.valuesTo('hsl')[0]; }
  public get hue(): number { return this.h; }
  public get s(): number { return this.valuesTo('hsl')[1]; }
  public get saturation(): number { return this.s; }
  public get l(): number { return this.valuesTo('hsl')[2]; }
  public get lightness(): number { return this.l; }
  public get w(): number { return this.valuesTo('hwb')[1]; }
  public get whiteness(): number { return this.w; }
  public get bk(): number { return this.valuesTo('hwb')[2]; }
  public get blackness(): number { return this.bk; }
  public get ciel(): number { return this.valuesTo('lab')[0]; }
  public get CIElightness(): number { return this.ciel; }
  public get ciea(): number { return this.valuesTo('lab')[1]; }
  public get CIEa(): number { return this.valuesTo('lab')[1]; }
  public get cieb(): number { return this.valuesTo('lab')[2]; }
  public get CIEb(): number { return this.valuesTo('lab')[2]; }
  public get ciec(): number { return this.valuesTo('lch')[1]; }
  public get CIEchroma(): number { return this.ciec; }
  public get cieh(): number { return this.valuesTo('lch')[2]; }
  public get CIEhue(): number { return this.cieh; }
  public get okl(): number { return this.valuesTo('oklab')[0]; }
  public get OKlightness(): number { return this.okl; }
  public get oka(): number { return this.valuesTo('oklab')[1]; }
  public get OKa(): number { return this.valuesTo('oklab')[1]; }
  public get okb(): number { return this.valuesTo('oklab')[2]; }
  public get OKb(): number { return this.valuesTo('oklab')[2]; }
  public get okc(): number { return this.valuesTo('oklch')[1]; }
  public get OKchroma(): number { return this.okc; }
  public get okh(): number { return this.valuesTo('oklch')[2]; }
  public get OKhue(): number { return this.okh; }

  public set luminance(val: number | string) {
    // Scale r, g, b to reach the desired luminance value
    const [r, g, b] = this.values;
    const oldLum = this.luminance;
    const newLum = Couleur.parse(val, 'a', { clamp: true });

    if (oldLum === 0) {
      this.r = newLum;
      this.g = newLum;
      this.b = newLum;
    } else {
      const ratio = newLum / oldLum;
      this.r = ratio * r;
      this.g = ratio * g;
      this.b = ratio * b;
    }
  }

  public get luminance(): number {
    if (this.a < 1) throw `The luminance of a transparent color would be meaningless`;
    return Contrasts.luminance(this.values);
  }



  /***********************************/
  /* Conversion between color spaces */
  /***********************************/


  /**
   * Converts the color values from one color space to another.
   * @param startSpaceID Starting color space, or its identifier.
   * @param endSpaceID Color space to convert to, or its identifier.
   * @param values Array of color values (without alpha) in startSpaceID color space.
   * @returns Array of values in the new color space.
   * @throws When one of the color spaces is not supported.
   */
  public static convert(startSpaceID: colorSpaceOrID, endSpaceID: colorSpaceOrID, values: number[]): number[] {
    if (
      (typeof startSpaceID === typeof endSpaceID && startSpaceID === endSpaceID)
      || (typeof startSpaceID === 'string' && typeof endSpaceID !== 'string' && startSpaceID === endSpaceID.id)
      || (typeof startSpaceID !== 'string' && typeof endSpaceID === 'string' && startSpaceID.id === endSpaceID)
    ) return values;
    const startSpace = Couleur.getSpace(startSpaceID);
    const endSpace = Couleur.getSpace(endSpaceID);

    // Find the shortest sequence of functions to convert between color spaces
    let path;
    const graph = new Graph(Couleur.colorSpaces);
    try { path = graph.shortestPath(startSpace.id, endSpace.id).map(node => node.id); }
    catch (error) {
      switch (error) {
        case `Node ${startSpace.id} does not exist`: throw `${JSON.stringify(startSpace.id)} is not a supported color space`;
        case `Node ${endSpace.id} does not exist`: throw `${JSON.stringify(endSpace.id)} is not a supported color space`;
        case `No path found from ${startSpace.id} to ${endSpace.id}`: throw `Conversion from ${JSON.stringify(startSpace.id)} space to ${JSON.stringify(endSpace.id)} space is impossible`;
        default: throw error;
      }
    }

    // Apply these functions to the color values.
    let result = values;
    while (path.length > 1) {
      const start = path.shift();
      const end = path[0];
      const functionName = `${start}_to_${end}`.replace(/-/g, '');
      const func = Conversions[functionName as keyof typeof Conversions];
      if (typeof func !== 'function') throw `Conversion function ${functionName} does not exist`;
      result = func(result);
    }

    return result;
  }


  /**
   * Converts the r, g, b values of the color to another color space.
   * @param spaceID Desired color space, or its identifier.
   * @param options
   * @param options.clamp Whether to clamp the values to their new color space.
   * @returns The array of converted values.
   */
  public valuesTo(spaceID: colorSpaceOrID, {clamp = false }: { clamp?: boolean } = {}): number[] {
    const space = Couleur.getSpace(spaceID);
    let values = Couleur.convert('srgb', space, this.values);
    if (clamp) values = Couleur.toGamut(space, values);
    return values;
  }


  /* Clamping to a color space */


  /**
   * Checks whether parsed values in valueSpaceID color space correspond to a color in the spaceID color space.
   * @param spaceID Color space whose gamut will be checked, or its identifier.
   * @param values Array of parsed values.
   * @param valueSpaceID Color space of the given values, or its identifier.
   * @returns Whether the corresponding color is in gamut.
   */
  public static inGamut(spaceID: colorSpaceOrID, values: number[], valueSpaceID: colorSpaceOrID = 'srgb', { tolerance = .0001 } = {}): boolean {
    const space = Couleur.getSpace(spaceID);
    const convertedValues = Couleur.convert(valueSpaceID, space, values);
    return convertedValues.every((v, k) => v >= (space.gamut[k][0] - tolerance) && v <= (space.gamut[k][1] + tolerance));
  }

  /** @see Couleur.inGamut - Non-static version. */
  public inGamut(spaceID: colorSpaceOrID, options = {}) { return Couleur.inGamut(spaceID, this.values, 'srgb', options); }

  /**
   * Clamps parsed values in valueSpaceID color space to the spaceID color space.
   * @param spaceID Color space whose gamut will be used, or its identifier.
   * @param values Array of parsed values.
   * @param valueSpaceID Color space of the given values, or its identifier.
   * @returns The array of values in valueSpaceID color space, after clamping the color to spaceID color space.
   */
  public static toGamut(spaceID: colorSpaceOrID, values: number[], valueSpaceID: colorSpaceOrID = 'srgb', { method = 'oklab' }: { method?: 'oklab'|'chroma'|'naive' } = {}): number[] {
    const space = Couleur.getSpace(spaceID);
    const valueSpace = Couleur.getSpace(valueSpaceID);
    const _method = method.toLowerCase();

    if (Couleur.inGamut(space, values, valueSpace, { tolerance: 0 })) return values;
    let clampedValues: number[], clampSpace: ColorSpace;

    switch (_method) {

      // OKLAB gamut clipping
      case 'oklab': {
        clampSpace = Couleur.getSpace('srgb');
        const rgb = Couleur.convert(valueSpace, clampSpace, values);
        clampedValues = OklabGamut.clip(rgb);
        break;
      }
      
      // Let's reduce the LCH chroma until the color is in the color space.
      case 'chroma': {
        // Source of the math: https://github.com/LeaVerou/color.js/blob/master/src/color.js
        clampSpace = Couleur.getSpace('lch');
        let lch = Couleur.convert(valueSpace, clampSpace, values);

        const τ = .01;
        let Cmin = 0;
        let Cmax = lch[1];
        lch[1] = lch[1] / 2;

        while (Cmax - Cmin > τ) {
          const naive = Couleur.toGamut(space, lch, clampSpace, { method: 'naive' });

          // If the color is close to the color space border
          if (Couleur.distance(naive, lch, { method: 'CIEDE2000' }) < 2 + τ)
            Cmin = lch[1];
          else
            Cmax = lch[1];
          lch[1] = (Cmin + Cmax) / 2;
        }

        clampedValues = lch;
        break;
      }

      // Naively clamp the values
      default: {
        clampSpace = space;
        const convertedValues = Couleur.convert(valueSpace, clampSpace, values);
        clampedValues = convertedValues.map((v, k) => Math.max(space.gamut[k][0], Math.min(v, space.gamut[k][1])));
      }

    }

    // Final naive clamp to get in the color space if the color is still just outside the border
    if (_method !== 'naive') clampedValues = Couleur.toGamut(space, clampedValues, clampSpace, { method: 'naive' });

    // Send the values back in the same color space we found them in
    return Couleur.convert(clampSpace, valueSpace, clampedValues);
  }

  /** @see Couleur.toGamut - Non-static version. */
  public toGamut(spaceID: colorSpaceOrID): Couleur { return new Couleur(Couleur.toGamut(spaceID, this.values, 'srgb')); }



  /********************************/
  /* Color manipulation functions */
  /********************************/


  /* Color modification */


  /**
   * Modifies a color by changing a specific property.
   * @param prop The color property that will be changed.
   * @param value The value that will be added to the property.
   * @param options
   * @param options.action 'replace' if the value should replace the previous value of the property,
   *                                   'scale' if the value should be multiplied to the previous value of the property,
   *                                   null if the value should be added to the previous value of the property.
   * @returns The modified color.
   */
  public change(prop: colorProperty, value: string | number, { action = null }: { action?: 'replace' | 'scale' | null } = {}): Couleur {
    const replace = action?.toLowerCase() === 'replace';
    const scale = action?.toLowerCase() === 'scale';
    const val = scale ? Couleur.parse(value) : Couleur.parse(value, prop, { clamp: false });
    const changedColor = new Couleur(this);

    const oldVal = this[prop as keyof typeof changedColor] as number;
    const newVal = replace ? val : scale ? oldVal * val : oldVal + val;

    type CreateWritable<Type> = { -readonly [Property in keyof Type]: Type[Property] };
    type CreateNumber<Type> = { [Property in keyof Type]: Type[Property] extends number ? Property : never }[keyof Type];
    type WritableColorProp = CreateNumber<CreateWritable<Couleur>>;

    changedColor[prop as WritableColorProp] = newVal;
    return changedColor;
  }

  /**
   * Modifies a color by replacing the value of a specific property.
   * This is an alias to change() with options.replace = true
   * @param prop The color property that will be changed.
   * @param value The value that will replace the previous value of the property.
   * @returns The modified color.
   */
  public replace(prop: colorProperty, value: string | number): Couleur {
    return this.change(prop, value, { action: 'replace' });
  }

  /**
   * Modifies a color by scaling the value of a specific property by a percentage.
   * This is an alias to change() with options.scale = true
   * @param prop The color property that will be changed.
   * @param value The percentage that will be multiplied to the previous value of the property.
   * @returns The modified color.
   */
  public scale(prop: colorProperty, value: string | number): Couleur {
    return this.change(prop, value, { action: 'scale' });
  }

  /** @returns The complementary color. */
  public complement(): Couleur { return this.change('h', 180); }

  /** @returns The inverse color. */
  public negative(): Couleur { return new Couleur(`rgb(${255 * (1 - this.r)}, ${255 * (1 - this.g)}, ${255 * (1 - this.b)}, ${this.a})`); }
  public invert(): Couleur { return this.negative(); }

  /** @returns The shade of grey of the color. */
  public greyscale(): Couleur {
    const L = 255 * this.replace('a', 1).luminance;
    return new Couleur(`rgb(${L}, ${L}, ${L}, ${this.a})`);
  }
  public grayscale(): Couleur { return this.greyscale(); }

  /** @returns The sepia tone of the color. */
  public sepia(): Couleur {
    const r = Math.min(0.393 * this.r + 0.769 * this.g + 0.189 * this.b, 1);
    const g = Math.min(0.349 * this.r + 0.686 * this.g + 0.168 * this.b, 1);
    const b = Math.min(0.272 * this.r + 0.534 * this.g + 0.131 * this.b, 1);
    return new Couleur(`rgb(${255 * r}, ${255 * g}, ${255 * b}, ${this.a})`);
  }


  /* Color blending */


  /**
   * Blends two colors together.
   * @param backgroundColor Background color.
   * @param overlayColor Overlay color.
   * @param alpha Alpha value that will replace overlay's.
   * @returns The resulting color.
   */
  public static blend(backgroundColor: color, overlayColor: color, alpha?: number | string): Couleur {
    const background = Couleur.makeInstance(backgroundColor);
    const overlay = Couleur.makeInstance(overlayColor);
    if (alpha != null) // if alpha isn't null or undefined
      overlay.a = Couleur.parse(alpha, 'a');

    if (overlay.a === 0) return background;
    else if (overlay.a === 1) return overlay;

    const a = overlay.a + background.a * (1 - overlay.a);
    const r = (overlay.r * overlay.a + background.r * background.a * (1 - overlay.a)) / a;
    const g = (overlay.g * overlay.a + background.g * background.a * (1 - overlay.a)) / a;
    const b = (overlay.b * overlay.a + background.b * background.a * (1 - overlay.a)) / a;
    return new Couleur([r, g, b, a]);
  }
  
  /**
   * Blends colors together, in the order they were given.
   * @param colors List of colors to successively blend.
   * @returns The resulting color.
   * @throws When there are less than two colors.
   */
  public static blendAll(...colors: color[]): Couleur {
    if (colors.length < 2) throw `You need at least 2 colors to blend`;
    const background = colors.shift();
    const overlay = colors.shift();
    
    if (background == null || overlay == null) throw 'Cannot blend undefined color';
    
    const mix = Couleur.blend(background, overlay);

    if (colors.length === 0) return mix;
    else                     return Couleur.blendAll(mix, ...colors);
  }

  /** @see Couleur.blend - Non-static version. */
  public blend(overlayColor: color, alpha?: number | string): Couleur { return Couleur.blend(this, overlayColor, alpha); }

  /** @see Couleur.blendAll - Non-static version. */
  public blendAll(...colors: color[]): Couleur { return Couleur.blendAll(this, ...colors); }


  /**
   * Solves the equation mix = blend(background, overlay) with background unknown.
   * @param mixColor The result of the blend.
   * @param overlayColor Color that was mixed with background to create mix.
   * @returns The background that is solution to the equation, if it has one.
   * @throws If the equation has an infinite amount of solutions.
   */
  public static unblend(mixColor: color, overlayColor: color, alpha?: number | string): Couleur | null {
    const mix = Couleur.makeInstance(mixColor);
    const overlay = Couleur.makeInstance(overlayColor);
    if (alpha != null) // if alpha isn't null or undefined
      overlay.a = Couleur.parse(alpha, 'a');

    if (overlay.a === 1) {
      throw `Overlay color ${JSON.stringify(overlay.rgb)} isn't transparent, so the background it was blended onto could have been any color`;
    }
    else if (overlay.a === 0)           return mix;
    else {
      if (mix.a < overlay.a)            return null;
      else if (mix.a === overlay.a) {
        if (Couleur.same(mix, overlay)) return new Couleur([0, 0, 0, 0]);
        else                            return null;
      }
      else {
        const a = (mix.a - overlay.a) / (1 - overlay.a);
        const r = (mix.r * mix.a - overlay.r * overlay.a) / (a * (1 - overlay.a));
        const g = (mix.g * mix.a - overlay.g * overlay.a) / (a * (1 - overlay.a));
        const b = (mix.b * mix.a - overlay.b * overlay.a) / (a * (1 - overlay.a));
        const clampedValues = Couleur.toGamut('srgb', [r, g, b], 'srgb');
        return new Couleur([...clampedValues, a]);
      }
    }
  }

  /**
   * Solves the equation mix = blendAll(background, ...overlays) with background unknown.
   * @param  {...color} colors - Colors to unblend.
   * @returns The solution to the equation, if it has one.
   * @throws If the equation has an infinite amount of solutions.
   */
  public static unblendAll(...colors: color[]): Couleur | null {
    if (colors.length < 2) throw `You need at least 2 colors to unblend`;
    const mix = colors.shift();
    const overlay = colors.shift();

    if (mix == null || overlay == null) throw 'Cannot unblend undefined color';
    
    const background = Couleur.unblend(mix, overlay);

    if (background == null)       return null;
    else if (colors.length === 0) return background;
    else                          return Couleur.unblendAll(background, ...colors);
  }

  /** @see Couleur.unblend - Non-static version. */
  public unblend(overlayColor: color, alpha?: number | string): Couleur | null { return Couleur.unblend(this, overlayColor, alpha); }
  
  /** @see Couleur.unblendAll - Non-static version. */
  public unblendAll(...colors: color[]): Couleur | null { return Couleur.unblendAll(this, ...colors); }


  /**
   * Solves the equation mix = blend(background, overlay) with overlay unknown.
   * @param backgroundColor The background color.
   * @param mixColor The result of the blend.
   * @param alpha The alpha value(s) you want the solution(s) to have.
   * @param options
   * @param options.ignoreTransparent Whether to return the color 'transparent' when it's a solution.
   * @returns The solution(s) to the equation.
   */
  public static whatToBlend(backgroundColor: color, mixColor: color, alphas: number | number[] = [], { ignoreTransparent = false }: { ignoreTransparent?: boolean } = {}): Couleur[] {
    const background = Couleur.makeInstance(backgroundColor);
    const mix = Couleur.makeInstance(mixColor);
    let overlays: Couleur[] = [];

    const calculateSolution = (a: number) => {
      const r = (mix.r * mix.a - background.r * background.a * (1 - a)) / a;
      const g = (mix.g * mix.a - background.g * background.a * (1 - a)) / a;
      const b = (mix.b * mix.a - background.b * background.a * (1 - a)) / a;
      if (!Couleur.inGamut('srgb', [r, g, b], 'srgb', { tolerance: 1/255 })) throw `This color doesn't exist`;
      const clampedValues = Couleur.toGamut('srgb', [r, g, b], 'srgb', { method: 'naive' });
      return new Couleur([...clampedValues, a]);
    };

    const requestedAlphas = [alphas].flat();
    const computedAlphas = requestedAlphas.length > 0 ? requestedAlphas.filter(a => a > 0 && a < 1) // can't divide by 0
                                                      : Array.from({ length: 9 }, (v, k) => (k + 1) / 10);

    // The mix can't have lower opacity than the background
    if (mix.a < background.a)      return [];
    // If the mix is more opaque than the background...
    else if (mix.a > background.a) {
      // If the background is partially transparent and the mix is opaque, the mix is the only solution
      // (any partially transparent overlay would have mixed with the background to make a partially transparent mix)
      if (mix.a === 1)             overlays.push(mix);
      // If the background is totally transparent and the mix is partially transparent, the mix is the only solution
      // (any other color mixed with nothing would make itself)
      else if (background.a === 0) overlays.push(mix);
      // If the background is partially transparent and the mis is too, but more opaque, then there exists a unique solution
      else {
        const a = (mix.a - background.a) / (1 - background.a);
        try { overlays.push(calculateSolution(a)); }
        catch (error) { return []; }
      }
    }
    // If the mix is as opaque as the background...
    else if (mix.a === background.a) {
      // If both the mix and the background are totally transparent, 'transparent' is the only solution
      // (any other color would have raised the opacity)
      if (mix.a === 0) overlays.push(new Couleur('transparent'));
      // If both the mix and the background are partially transparent with the same opacity, then
      // if they're the same color, 'transparent' is solution. If not, there is no solution.
      else if (mix.a < 1) {
        if (Couleur.same(mix, background)) overlays.push(new Couleur('transparent'));
        else                               return [];
      }
      // If both mix and background are totally opaque, then there is an infinity of solutions
      // (one per alpha value from 0 (included only if same color) to 1). Let's calculate the ones
      // whose alpha value was passed in the alphas argument, or those in [0, 0.1, 0.2, ..., 0.9, 1]
      // if alphas === null.
      else {
        if (Couleur.same(mix, background)) overlays.push(new Couleur('transparent'));
        for (const a of computedAlphas) {
          try { overlays.push(calculateSolution(a)); }
          catch (error) { continue; }
        }
        overlays.push(mix);
      }
    }

    let result = requestedAlphas.length > 0 ? overlays.filter(c => requestedAlphas.includes(c.a))
                                            : overlays;
    if (ignoreTransparent) result = result.filter(r => r.a > 0);

    return result;
  }

  /** @see Couleur.whatToBlend - Non-static version. */
  public whatToBlend(mixColor: color, alphas: number | number[]): Couleur[] | null { return Couleur.whatToBlend(this, mixColor, alphas); }


  /* Color comparison */


  /**
   * Computes the contrast between two colors as defined by WCAG2 or 3.
   * @param textColor First color (for text over a background, this is the text color).
   * @param backgroundColor Second color (for text over a background, this is the background color).
   * @param options
   * @param options.method Whether to use the new APCA or the old WCAG2 method.
   * @returns Contrast between the two colors.
   * @throws When the background color is transparent, as contrast can't be measured with it.
   */
  public static contrast(textColor: color, backgroundColor: color, { method = 'APCA' }: { method?: string } = {}): number {
    const background = Couleur.makeInstance(backgroundColor);
    if (background.a < 1) throw `The contrast with a transparent background color would be meaningless`;
    let text = Couleur.makeInstance(textColor);

    // If the text is transparent, blend it to the background to get its actual visible color
    if (text.a < 1) text = Couleur.blend(background, text);

    switch (method.toLowerCase()) {
      case 'wcag3':
      case 'sapc':
      case 'apca':
        return Contrasts.APCA(text.values, background.values);
      case 'wcag2':
      default:
        return Contrasts.WCAG2(text.values, background.values);
    }
  }

  /** @see Couleur.contrast - Non-static version. */
  public contrast(backgroundColor: color, options: object = {}): number {
    return Couleur.contrast(this, backgroundColor, options);
  }


  /** 
   * Determines which color scheme ('light' or 'dark') would lead to a better contrast with the color.
   * The 'light' color scheme means a light background with dark text.
   * The 'dark' color scheme means a dark background with light text.
   * @param as Whether the color is the background or the text color.
   * @returns
   */
  public bestColorScheme(as: 'background'|'text' = 'background'): 'light'|'dark' {
    const rgba = [...this.toGamut('srgb').values, this.a];
    switch (as) {
      case 'text': {
        const Cblack = Math.abs(Couleur.contrast(rgba, 'black', { method: 'apca' }));
        const Cwhite = Math.abs(Couleur.contrast(rgba, 'white', { method: 'apca' }));
        return (Cblack >= Cwhite) ? 'dark' : 'light';
      }
      case 'background': {
        const Cblack = Math.abs(Couleur.contrast('black', rgba, { method: 'apca' }));
        const Cwhite = Math.abs(Couleur.contrast('white', rgba, { method: 'apca' }));
        return (Cblack >= Cwhite) ? 'light' : 'dark';
      }
    }
  }


  /**
   * Modifies the CIE lightness of a color to give it better contrast with another color.
   * @param referenceColor The color with which contrast will be measured and improved.
   * @param desiredContrast The absolute value of the contrast to reach.
   * @param options
   * @param options.as Whether the color this function is applied to is used as 'text' or 'background' color.
   * @param options.lower Whether contrast should be lowered if it's already bigger than desiredContrast.
*                         If true, and contrast is higher from the start, it will be lowered until it reaches desiredContrast.
*                         If false, and contrast is higher from the start, nothing will be done.
   * @param options.colorScheme Whether the text color should be darker than the background color (colorScheme = 'light')
   *                            or lighter than the background color (colorScheme = 'dark').
   *                            If null, the starting color scheme will be preserved (i.e. if the text color starts darker
   *                            than the background color, it will stay darker.)
   * @param options.method The method to use to compute the contrast.
   * @returns The modified color which verifies Couleur.contrast(color, referenceColor) >= desiredContrast.
   */
  public improveContrast(referenceColor: color, desiredContrast: number, { as = 'text', lower = false, colorScheme = null, method = 'APCA' }: { as?: 'text' | 'background', lower?: boolean, colorScheme?: 'light' | 'dark' | null, method?: string } = {}): Couleur {
    const background = as === 'text' ? Couleur.makeInstance(referenceColor) : this;
    const text =       as === 'text' ? this : Couleur.makeInstance(referenceColor);
    const backgroundLab = background.valuesTo('oklab');
    const textLab = text.valuesTo('oklab');
    const movingLab = as === 'text' ? textLab : backgroundLab;

    // Let's measure the initial contrast
    // and decide if we want it to go up or down.
    const startContrast = Math.abs(Couleur.contrast(text, background, { method }));
    let directionContrast;
    if (startContrast > desiredContrast)      directionContrast = -1;
    else if (startContrast < desiredContrast) directionContrast = 1;
    else                                      directionContrast = 0;
    // If the contrast is already higher than desired, and lowering it is not allowed, return the color as is.
    if ((directionContrast < 0 && lower === false) || (directionContrast === 0)) return this;

    // Let's detect the color scheme if it isn't given.
    const _colorScheme = colorScheme || (backgroundLab[0] < textLab[0] ? 'dark' : 'light');

    // Let's measure the contrast of the background with black and white to know if
    // desiredContrast can be reached by lowering or raising the color's CIE lightness.
    const cBlack = Math.abs(
      as === 'text' ? Couleur.contrast(background, 'black', { method })
                    : Couleur.contrast('black', text, { method }))
    ;
    const cWhite = Math.abs(
      as === 'text' ? Couleur.contrast(background, 'white', { method })
                    : Couleur.contrast('white', text, { method })
    );
    const isPossible = {
      lowering: (directionContrast > 0) ? cBlack >= desiredContrast : cBlack <= desiredContrast,
      raising: (directionContrast > 0) ? cWhite >= desiredContrast : cWhite <= desiredContrast
    };

    // Let's decide which direction to move the lightness in.
    let directionOKL: number;
    if (isPossible.lowering && !isPossible.raising)      directionOKL = -1;
    else if (isPossible.raising && !isPossible.lowering) directionOKL = 1;
    // If desiredContrast can not be reached, return white or black — the one that fits the color scheme.
    else if (!isPossible.raising && !isPossible.lowering) {
      if (as === 'text') {
        if (_colorScheme === 'light') return new Couleur('black');
        else                          return new Couleur('white');
      } else {
        if (_colorScheme === 'light') return new Couleur('white');
        else                          return new Couleur('black');
      }
    }
    // If desiredContrast can be reached in both directions
    else {
      // If we're changing the text color:
      // If the background is lighter and we need to raise the contrast, lower the lightness.
      if (_colorScheme === 'light' && directionContrast > 0)      directionOKL = -1;
      // If the background is lighter and we need to lower the contrast, raise the lightness.
      else if (_colorScheme === 'light' && directionContrast < 0) directionOKL = 1;
      // If the background is darker and we need to raise the contrast, raise the lightness.
      else if (_colorScheme === 'dark' && directionContrast > 0)  directionOKL = 1;
      // If the background is darker and we need to lower the contrast, lower the lightness.
      else                                                        directionOKL = -1;

      // Else if we're changing the background color:
      if (as === 'background') directionOKL = -directionOKL;
    }

    const τ = .0001;
    let OKLmin = (directionOKL > 0) ? movingLab[0] : 0;
    let OKLmax = (directionOKL > 0) ? 1 : movingLab[0];

    while (OKLmax - OKLmin > τ) {
      // Let's try to raise contrast by increasing or reducing CIE lightness.
      const ciel = (OKLmin + OKLmax) / 2;
      const newValues = movingLab; newValues[0] = ciel;
      const newContrast = Math.abs(
        as === 'text' ? Couleur.contrast(Couleur.convert('oklab', 'srgb', newValues), background, { method })
                      : Couleur.contrast(text, Couleur.convert('oklab', 'srgb', newValues), { method })
      );

      // If the new contrast hasn't gone over its desired value
      const condition = (directionContrast > 0) ? (newContrast < desiredContrast) : (newContrast > desiredContrast);
      if (condition) {
        if (directionOKL > 0) OKLmin = ciel;
        else                  OKLmax = ciel;
      }
      // If we overshot and the contrast moved further than we want it to
      else {
        if (directionOKL > 0) OKLmax = ciel;
        else                  OKLmin = ciel;
      }

      movingLab[0] = ciel;
    }

    let result = new Couleur(Couleur.convert('oklab', 'srgb', movingLab));
    // If the color we find has its contrast slightly below the desired value, push it further.
    const lastContrast = Math.abs(
      as === 'text' ? Couleur.contrast(result, background, { method })
                    : Couleur.contrast(text, result, { method })
    );
    if (lastContrast < desiredContrast) {
      if (as === 'text') {
        if (_colorScheme === 'light') movingLab[0] = OKLmin;
        else                          movingLab[0] = OKLmax;
      } else {
        if (_colorScheme === 'light') movingLab[0] = OKLmax;
        else                          movingLab[0] = OKLmin;
      }
    }

    // We're done!
    return new Couleur(Couleur.convert('oklab', 'srgb', movingLab));
  }


  /**
   * Computes the distance between two colors.
   * @param color1 
   * @param color2 
   * @param options
   * @param options.method The method to use to compute the distance.
   * @param options.alpha Whether to add the distance between alpha values.
   * @returns The distance between the two colors in sRGB space.
   */
  public static distance(color1: color, color2: color, { method = 'deltaE2000', alpha = true }: { method?: string, alpha?: boolean } = {}): number { 
    const colore1 = Couleur.makeInstance(color1);
    const colore2 = Couleur.makeInstance(color2);
    let opaqueDist: number = +Infinity;
    let alphaCoeff: number = 1;

    switch (method.toLowerCase()) {
      case 'ciede2000':
      case 'deltae2000': {
        const [lab1, lab2] = [colore1, colore2].map(c => c.valuesTo('lab'));
        opaqueDist = Distances.CIEDE2000(lab1, lab2);
        alphaCoeff = 50;
      } break;
      case 'deltaeok': {
        const [oklab1, oklab2] = [colore1, colore2].map(c => c.valuesTo('oklab'));
        opaqueDist = Distances.euclidean(oklab1, oklab2);
      } break;
      case 'euclidean':
      default: {
        const [rgb1, rgb2] = [colore1, colore2].map(c => c.values);
        opaqueDist = Distances.euclidean(rgb1, rgb2);
      }
    }

    const alphaDist = alpha ? Distances.euclidean([colore1.a], [colore2.a]) : 0;
    return opaqueDist + alphaCoeff * alphaDist;
  }

  /** @see Couleur.distance - Non-static version. */
  public distance(color: color, options: object = {}) { return Couleur.distance(this, color, options); }


  /**
   * Determines if two colors are the same, with a certain tolerance.
   * @param color1 
   * @param color2 
   * @param tolerance The minimum distance between the two colors to consider them different.
   * @returns Whether the two colors are considered the same.
   */
  public static same(color1: color, color2: color, { tolerance = 1, method = 'deltaE2000' }: { tolerance?: number, method?: string } = {}): boolean {
    if (Couleur.distance(color1, color2, { method }) > tolerance) return false;
    else return true;
  }

  /** @see Couleur.same - Non-static version. */
  public same(color: color, options: object = {}): boolean { return Couleur.same(this, color, options); }


  /* Other functions */


  /**
   * Calculates the intermediate colors a gradient should use to go from one color to another without passing through the "desaturated zone".
   * @param startColor The starting color of the gradient.
   * @param endColor The ending color of the gradient.
   * @param steps The number of steps in the gradient to go from start to end.
   * @param spaceID Identifier of the color space in which to compute the gradient.
   * @returns The array of (steps + 1) colors in the gradient.
   */
  public static gradient(startColor: color, endColor: color, steps: number = 5, spaceID: string = 'oklch'): Couleur[] {
    const start = Couleur.makeInstance(startColor);
    const end = Couleur.makeInstance(endColor);
    const _steps = Math.max(1, steps);
    const props = [...Couleur.propertiesOf(spaceID), 'a'];
    const space = Couleur.getSpace(spaceID);
    const startValues = [...start.valuesTo(space), start.a];
    const endValues = [...end.valuesTo(space), end.a];

    // Calculate by how much each property will be changed at each steap
    const stepList = props.map((prop, k) => {
      let step;
      switch (prop) {
        case 'h':
        case 'cieh':
        case 'okh':
          // Minimize the distance to travel through hues
          const stepUp = ((endValues[k] - startValues[k]) % 360 + 360) % 360;
          const stepDown = ((startValues[k] - endValues[k]) % 360 + 360) % 360;
          step = ((stepUp <= stepDown) ? stepUp : -stepDown) / _steps;
          break;
        default:
          step = (endValues[k] - startValues[k]) / _steps;
      }
      return step;
    });

    // Calculate all colors of the gradient
    const intermediateColors = [startValues];
    for (let i = 1; i < _steps; i++) {
      let previous = intermediateColors[i - 1];
      let next = props.map((prop, k) => {
        let v = previous[k] + stepList[k];
        if (['h', 'cieh'].includes(prop)) return Utils.angleToRange(v);
        else return v;
      });
      const a = next[3];
      next = Couleur.toGamut(space, next.slice(0, 3), space);
      next = [...next, a];
      intermediateColors.push(next);
    }

    return [...intermediateColors.map(c => new Couleur(Couleur.convert(space, 'srgb', c))), end];
  }

  /** @see Couleur.gradient - Non-static version. */
  public gradient(color: color, steps: number, format: string): Couleur[] { return Couleur.gradient(this, color, steps, format); }



  /**************/
  /* Color data */
  /**************/

  /**
   * Gets the list of the short names of color properties used in a given CSS format.
   * @param format Name of the color format.
   * @returns Array of color property names.
   */
  protected static propertiesOf(format: string): colorProperty[] {
    switch(format.toLowerCase()) {
      case 'rgb':
      case 'rgba':  return ['r', 'g', 'b'];
      case 'hsl':
      case 'hsla':  return ['h', 's', 'l'];
      case 'hwb':   return ['h', 'w', 'bk'];
      case 'lab':   return ['ciel', 'ciea', 'cieb'];
      case 'lch':   return ['ciel', 'ciec', 'cieh'];
      case 'oklab': return ['okl', 'oka', 'okb'];
      case 'oklch': return ['okl', 'okc', 'okh'];
      default:      return [];
    }
  }

  /** @returns Array of all color property short names. */
  protected static get properties(): colorProperty[] {
    return ['a', 'r', 'g', 'b', 'h', 's', 'l', 'w', 'bk', 'ciel', 'ciea', 'cieb', 'ciec', 'cieh', 'okl', 'oka', 'okb', 'okc', 'okh'];
  }

  /**
   * Gets a color space from its id.
   * @param spaceID Identifier of a color space, or a color space itself.
   * @returns The corresponding color space object.
   * @throws When the color space is not supported.
   */
  protected static getSpace(spaceID: colorSpaceOrID): ColorSpace {
    let result: ColorSpace | undefined;
    if (typeof spaceID !== 'string') result = spaceID;
    else {
      let id = spaceID.toLowerCase();
      switch (id) {
        case 'rgb':
        case 'rgba': id = 'srgb'; break;
        case 'hsla': id = 'hsl'; break;
      }
      result = Couleur.colorSpaces.find(sp => sp.id == id);
    }

    if (result == null) throw `${spaceID} is not a supported color space`;
    return result;
  }

  /** @returns Array of supported color spaces. */
  private static get colorSpaces(): ColorSpace[] { return colorSpaces; }

  /** @returns Array of supported syntaxes. */
  private static get formats(): CSSFormat[] { return Formats; }

  /** @returns List of named colors in CSS. */
  private static get namedColors(): Map<string, string> { return namedColors; }
}