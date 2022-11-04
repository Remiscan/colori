import { ColorProperty, ColorSpace, ColorSpaceWithGamut, ColorSpaceWithoutGamut, default as colorSpaces } from './color-spaces.js';
import * as Contrasts from './contrasts.js';
import * as Conversions from './conversion.js';
import { CSSFormat, cssFormats, cssUnitRegexps } from './css-formats.js';
import * as Distances from './distances.js';
import { default as Graph, GraphNode, PathNotFoundError, UndefinedNodeError } from './graph.js';
import namedColors from './named-colors.js';
import * as Utils from './utils.js';



/* Type definitions */

type colorString = string;
type colorArray = number[];
type colorObject = { r: number, g: number, b: number, a?: number };
type color = Couleur | colorObject | colorArray | colorString;

type cssColorFormatWithNamedProperties = 'srgb'|'rgb'|'rgba'|'hsl'|'hsla'|'hwb'|'lab'|'lch'|'oklab'|'oklch';

type colorSpaceID = string;
type colorSpaceOrID = ColorSpace | colorSpaceID;

type unparsedValue = number | string;
type unparsedAlphaValue = number | `${number}%`;
type parsedValue = number;

interface makeExprOptions {
  precision?: number
}

interface exprOptions extends makeExprOptions {
  clamp?: boolean;
}

type toGamutMethod = 'okchroma' | 'naive';

interface toGamutOptions {
  method?: toGamutMethod;
}

type contrastMethod = 'apca' | 'wcag2';

interface contrastOptions {
  method?: contrastMethod;
}

interface improveContrastOptions {
  as?: 'text' | 'background';
  lower?: boolean;
  colorScheme?: 'light' | 'dark';
  method?: contrastMethod;
}

type distanceMethod = 'ciede2000' | 'deltae2000' | 'deltaeok' | 'euclidean';

interface distanceOptions {
  method?: distanceMethod;
  alpha?: boolean;
}

interface sameOptions {
  tolerance?: number;
  method?: distanceMethod;
}

interface interpolateOptions {
  hueInterpolationMethod?: 'shorter' | 'longer' | 'increasing' | 'decreasing';
  premultiplyAlpha?: boolean;
}



/* Error definitions */

export class InvalidColorStringError extends Error {
  constructor(color: color) {
    super(`${JSON.stringify(color)} is not a valid color format`);
  }
}

export class InvalidColorPropValueError extends Error {
  constructor (prop: ColorProperty, value: unparsedValue) {
    super(`Invalid ${JSON.stringify(prop)} value: ${JSON.stringify(value)}`);
  }
}

export class InvalidColorAngleValueError extends Error {
  constructor(value: unparsedValue) {
    super(`Invalid angle value: ${JSON.stringify(value)}`);
  }
}

export class InvalidColorArbitraryValueError extends Error {
  constructor(value: unparsedValue) {
    super(`Invalid arbitrary value: ${JSON.stringify(value)}`);
  }
}

export class ColorFormatHasNoSuchPropertyError extends Error {
  constructor(format: string, prop: ColorProperty) {
    super(`Format ${format} does not have a property called ${prop}`);
  }
}

export class ImpossibleColorConversionError extends Error {
  constructor(startSpace: ColorSpace, endSpace: ColorSpace) {
    super(`Conversion from ${JSON.stringify(startSpace.id)} space to ${JSON.stringify(endSpace.id)} space is impossible`);
  }
}

export class UnsupportedColorSpaceError extends Error {
  constructor(id: string) {
    super(`${JSON.stringify(id)} is not a supported color space`);
  }
}

export class UndefinedConversionError extends Error {
  constructor(functionName: string) {
    super(`Conversion function ${functionName} does not exist`);
  }
}



/** Graph with added cache for shortestPath() results. */
class GraphWithCachedPaths extends Graph {
  #cache = new Map();

  shortestPath(startID: string | number, endID: string | number): GraphNode[] {
    const id = `${startID}_to_${endID}`;
    // Since every conversion path is reversible, only cache half of them
    let cachedPath = this.#cache.get(id);
    if (!cachedPath) {
      const reversedPath = this.#cache.get(`${endID}_to_${startID}`);
      cachedPath = reversedPath ? [...reversedPath].reverse() : null;
    }
    if (cachedPath) return cachedPath;

    const path = super.shortestPath(startID, endID);
    this.#cache.set(id, path);
    return path;
  }
}



/**
 * Colori module
 * @author Remiscan <https://remiscan.fr>
 * @module colori.js
 */

const colorSpacesGraph = new GraphWithCachedPaths(colorSpaces);

/** @class Couleur */
export default class Couleur {
  #r: number = 0;
  #g: number = 0;
  #b: number = 0;
  #a: number = 0;
  #cache: Map<colorSpaceID, number[]> = new Map();

  /**
   * Creates a new Couleur object that contains r, g, b, a properties of the color.
   * These properties will take their values from sRGB color space, even if they're out of bounds.
   * (This means values <0 or >1 can be stored — they can be clamped to a specific color space when needed.)
   * @param color Color expression in a supported type.
   * @throws When the parameter is not of a supported type.
   */
  constructor(color: color) {
    if (color instanceof Couleur || (typeof color === 'object' && 'r' in color && 'g' in color && 'b' in color)) {
      this.#r = color.r;
      this.#g = color.g;
      this.#b = color.b;
      this.#a = typeof color.a === 'number' ? color.a : 1;
    }

    else if (Array.isArray(color) && (color.length == 3 || color.length == 4)) {
      [this.#r, this.#g, this.#b] = Couleur.valuesToGamut('srgb', color.slice(0, 3), 'srgb', { method: 'naive' });
      this.#a = Math.max(0, Math.min(Number(Utils.toUnparsedAlpha(color[3])), 1));
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
          const props: ColorProperty[] = [...Couleur.propertiesOf(format.id), 'a'];
          const space = Couleur.getSpace(format.id);
          this.set(values, props, space);
        } break;
        case 'color':
          this.setColor(format.data[1], [format.data[2], format.data[3], format.data[4], Utils.toUnparsedAlpha(format.data[5])]);
          break;
        default:
          throw new InvalidColorStringError(color);
      }
    }

    else throw new Error(`Couleur objects can only be created from a string, an array of parsed values, or another Couleur object ; this is not one: ${JSON.stringify(color)}`);
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
    if (tri.slice(0, 1) === '#') format = Couleur.cssFormats[0];
    else switch (tri) {
      case 'rgb': format = Couleur.cssFormats[1]; break;
      case 'hsl': format = Couleur.cssFormats[2]; break;
      case 'hwb': format = Couleur.cssFormats[3]; break;
      case 'lab': format = Couleur.cssFormats[4]; break;
      case 'lch': format = Couleur.cssFormats[5]; break;
      case 'okl': {
        if (colorString.startsWith('oklab')) { format = Couleur.cssFormats[6]; }
        else if (colorString.startsWith('oklch')) { format = Couleur.cssFormats[7]; }
      } break;
      case 'col': format = Couleur.cssFormats[8]; break;
      default:    format = Couleur.cssFormats[9];
    }

    if (format == null) throw new Error('No matching format');

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

    throw new InvalidColorStringError(colorString);
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
  private static parse(value: unparsedValue, prop: ColorProperty | null = null, { clamp = true } = {}): number {
    const val = String(value);
    const nval = parseFloat(val);

    switch (prop) {
      // Alpha values:
      // from any % or any number
      // clamped to [0, 100]% or [0, 1]
      // to [0, 1]
      case 'a': {
        // If n is a percentage
        if (new RegExp('^' + cssUnitRegexps.percentage + '$').test(val)) {
          if (clamp)  return Math.max(0, Math.min(nval / 100, 1));
          else        return nval / 100;
        }
        // If n is a number
        else if (new RegExp('^' + cssUnitRegexps.number + '$').test(val)) {
          if (clamp)  return Math.max(0, Math.min(nval, 1));
          else        return nval;
        }
        else throw new InvalidColorPropValueError(prop, value);
      }

      // Red, green, blue values:
      // from any % or any number
      // clamped to [0, 100]% or [0, 255]
      // to [0, 1]
      case 'r':
      case 'g':
      case 'b': {
        // If n is a percentage
        if (new RegExp('^' + cssUnitRegexps.percentage + '$').test(val)) {
          if (clamp)  return Math.max(0, Math.min(nval / 100, 1));
          else        return nval / 100;
        }
        // If n is a number
        else if (new RegExp('^' + cssUnitRegexps.number + '$').test(val)) {
          if (clamp)  return Math.max(0, Math.min(nval / 255, 1));
          else        return nval / 255;
        }
        else throw new InvalidColorPropValueError(prop, value);
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
        if (new RegExp('^' + cssUnitRegexps.number + '$').test(val)) {
          return Utils.angleToRange(h);
        }
        // If n is an angle
        else if ((new RegExp('^' + cssUnitRegexps.angle + '$').test(val))) {
          if (val.slice(-3) === 'deg') {} // necessary to accept deg values
          else if (val.slice(-4) === 'grad')
            h = h * 360 / 400;
          else if (val.slice(-3) === 'rad')
            h = h * 180 / Math.PI;
          else if (val.slice(-4) === 'turn')
            h = h * 360;
          else throw new InvalidColorAngleValueError(value);
          return Utils.angleToRange(h);
        }
        else throw new InvalidColorPropValueError(prop, value);
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
        if (new RegExp('^' + cssUnitRegexps.percentage + '$').test(val)) {
          if (clamp)  return Math.max(0, Math.min(nval / 100, 1));
          else        return nval / 100;
        }
        else throw new InvalidColorPropValueError(prop, value);
      }

      // CIE axes values
      // and OKLAB axes values
      // and OKLCH chroma value:
      // any number
      case 'ciea':
      case 'cieb':
      case 'oka':
      case 'okb':
      case 'okc': {
        // If n is a number
        if (new RegExp('^' + cssUnitRegexps.number + '$').test(val)) {
          return nval;
        }
        else throw new InvalidColorPropValueError(prop, value);
      }

      // CIE chroma values:
      // from any number
      // clamped to [0, +Inf[
      case 'ciec': {
        // If n is a number
        if (new RegExp('^' + cssUnitRegexps.number + '$').test(val)) {
          if (clamp)  return Math.max(0, nval);
          else        return nval;
        }
        else throw new InvalidColorPropValueError(prop, value);
      }

      // Arbitrary values
      // from any % or any number
      // to any number (so that 0% becomes 0 and 100% becomes 1)
      default: {
        // If n is a percentage
        if (new RegExp('^' + cssUnitRegexps.percentage + '$').test(val)) {
          return nval / 100;
        }
        // If n is a number
        else if (new RegExp('^' + cssUnitRegexps.number + '$').test(val)) {
          return nval;
        }
        else throw new InvalidColorArbitraryValueError(value); // doesn't match any property value at all
      }
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
  private static unparse(value: number, prop: ColorProperty | null, { precision = 0 } = {}): string {
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
        return precision === null ? `${value}` : `${Math.round(10**Math.max(precision, 4) * value) / (10**Math.max(precision, 4))}`;
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
   * @param sourceSpaceID Color space of the values, or its identifier.
   * @param options
   * @param options.parsed Whether the provided values are already parsed.
   */
  private set(data: Array<string|number>, props: Array<ColorProperty|null>, sourceSpaceID: colorSpaceOrID, { parsed = false } = {}) {
    const sourceSpace = Couleur.getSpace(sourceSpaceID);
    const values = parsed ? data.map(v => Number(v)) : props.map((p, i) => Couleur.parse(data[i], p));
    [this.#r, this.#g, this.#b] = Couleur.convert(sourceSpace, 'srgb', values);

    this.#a = Couleur.parse(Utils.toUnparsedAlpha(data[3]), 'a');
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
   * @param sourceSpaceID Identifier of the color space.
   * @param values The parsed values of the color's properties.
   * @throws When the color space is not supported.
   */
  private setColor(sourceSpaceID: string, values: Array<string|number>): void {
    let vals = values.slice(0, 3).map(v => Couleur.parse(v));
    const a = Couleur.parse(values[3]);

    vals = Couleur.convert(sourceSpaceID, 'srgb', vals);
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
   * @param options @see Couleur.makeString
   */
  public toString(format: string = 'rgb', { precision = 0, clamp = true }: exprOptions = {}): string {
    const _format = format.toLowerCase();
    const destinationSpaceID = _format.replace('color-', '');
    const destinationSpace = Couleur.getSpace(destinationSpaceID);
    let values = this.valuesTo(destinationSpace, { clamp });
    return Couleur.makeString(format, [...values, this.a], { precision });
  }

  /**
   * Creates a string containing the CSS expression of a color from a list of values.
   * @param format Identifier of the color space of the requested CSS expression.
   * @param values The values of the properties in the given format.
   * @param options
   * @param options.precision How many decimals to display.
   * @param options.clamp Which color space the values should be clamped to.
   * @returns The expression of the color in the requested format.
   */
  public static makeString(format: string, values: number[], { precision = 0 }: makeExprOptions = {}): string {
    const _format = format.toLowerCase();
    const destinationSpaceID = _format.replace('color-', '');
    const destinationSpace = Couleur.getSpace(destinationSpaceID);

    const a = Number(Couleur.unparse(values[3] ?? 1, 'a', { precision }));
    values = [...values.slice(0, 3), a];

    // If the requested expression is of the color(space, ...) type
    if (_format.toLowerCase().slice(0, 5) === 'color') {
      const [x, y, z] = values.map(v => precision === null ? v : Math.round(10**precision * v) / (10**precision));
      if (a < 1)
        return `color(${destinationSpace.id} ${x} ${y} ${z} / ${a})`;
      else
        return `color(${destinationSpace.id} ${x} ${y} ${z})`;
    }
    
    // If the requested expression is of the ${format}(...) type
    else {
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
    const values = Couleur.valuesToGamut('srgb', this.values);
    const rgb = Utils.toHex([...values, this.a]);
    if (this.a < 1) return `#${rgb[0]}${rgb[1]}${rgb[2]}${rgb[3]}`;
    else            return `#${rgb[0]}${rgb[1]}${rgb[2]}`;
  }

  /** @returns RGB expression of the color. */
  public get rgb(): string { return this.toString('rgb', { precision: 2, clamp: true }); }
  public get rgba(): string { return this.rgb; }

  /** @returns HSL expression of the color. */
  public get hsl(): string { return this.toString('hsl', { precision: 2, clamp: true }); }
  public get hsla(): string { return this.hsl; }

  /** @returns HWB expression of the color. */
  public get hwb(): string { return this.toString('hwb', { precision: 2, clamp: true }); }

  /** @returns LAB expression of the color. */
  public get lab(): string { return this.toString('lab', { precision: 2, clamp: true }); }

  /** @returns LCH expression of the color. */
  public get lch(): string { return this.toString('lch', { precision: 2, clamp: true }); }

  /** @returns OKLAB expression of the color. */
  public get oklab(): string { return this.toString('oklab', { precision: 2, clamp: true }); }

  /** @returns OKLCH expression of the color. */
  public get oklch(): string { return this.toString('oklch', { precision: 2, clamp: true }); }



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
  private recompute(val: number | string, prop: ColorProperty, format: string) {
    const props: ColorProperty[] = [...Couleur.propertiesOf(format), 'a'];
    if (!props.includes(prop))
      throw new ColorFormatHasNoSuchPropertyError(format, prop);
    
    const parsedVal = (typeof val === 'string') ? Couleur.parse(val, prop) : val;
    const oldValues = [...this.valuesTo(format), this.a];
    const newValues = props.map((p, k) => {
      if (p === prop) return parsedVal;
      else            return oldValues[k];
    });
    this.set(newValues, props, format, { parsed: true });
    this.#cache = new Map();
  }

  public set r(val: number | string) { this.recompute(val, 'r', 'rgb'); }
  public set red(val: number | string) { this.r = val; }

  public set g(val: number | string) { this.recompute(val, 'g', 'rgb'); }
  public set green(val: number | string) { this.g = val; }

  public set b(val: number | string) { this.recompute(val, 'b', 'rgb'); }
  public set blue(val: number | string) { this.b = val; }

  public set a(val: number | string) { this.recompute(val, 'a', 'rgb'); }
  public set alpha(val: number | string) { this.a = val; }
  public set opacity(val: number | string) { this.a = val; }

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

  public set cieb(val: number | string) { this.recompute(val, 'cieb', 'lab'); }

  public set ciec(val: number | string) { this.recompute(val, 'ciec', 'lch'); }
  public set CIEchroma(val: number | string) { this.ciec = val; }

  public set cieh(val: number | string) { this.recompute(val, 'cieh', 'lch'); }
  public set CIEhue(val: number | string) { this.cieh = val; }

  public set okl(val: number | string) { this.recompute(val, 'okl', 'oklab'); }
  public set OKlightness(val: number | string) { this.okl = val; }

  public set oka(val: number | string) { this.recompute(val, 'oka', 'oklab'); }

  public set okb(val: number | string) { this.recompute(val, 'okb', 'oklab'); }

  public set okc(val: number | string) { this.recompute(val, 'okc', 'oklch'); }
  public set OKchroma(val: number | string) { this.okc = val; }

  public set okh(val: number | string) { this.recompute(val, 'okh', 'oklch'); }
  public set OKhue(val: number | string) { this.okh = val; }

  /** @returns Gets the parsed value of one of the color properties. */
  public get r(): number { return this.#r; }
  public get red(): number { return this.r; }
  public get g(): number { return this.#g; }
  public get green(): number { return this.g; }
  public get b(): number { return this.#b; }
  public get blue(): number { return this.b; }
  public get a(): number { return this.#a; }
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
  public get cieb(): number { return this.valuesTo('lab')[2]; }
  public get ciec(): number { return this.valuesTo('lch')[1]; }
  public get CIEchroma(): number { return this.ciec; }
  public get cieh(): number { return this.valuesTo('lch')[2]; }
  public get CIEhue(): number { return this.cieh; }
  public get okl(): number { return this.valuesTo('oklab')[0]; }
  public get OKlightness(): number { return this.okl; }
  public get oka(): number { return this.valuesTo('oklab')[1]; }
  public get okb(): number { return this.valuesTo('oklab')[2]; }
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
    if (this.a < 1) throw new Error(`The luminance of a transparent color would be meaningless`);
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
    const graph = colorSpacesGraph;
    try { path = graph.shortestPath(startSpace.id, endSpace.id).map(node => node.id); }
    catch (error) {
      if (error instanceof PathNotFoundError) {
        throw new ImpossibleColorConversionError(startSpace, endSpace);
      } else if (error instanceof UndefinedNodeError) {
        if (error.id === startSpace.id)    throw new UnsupportedColorSpaceError(startSpace.id);
        else if (error.id === endSpace.id) throw new UnsupportedColorSpaceError(endSpace.id);
        else throw error;
      } else throw error;
    }

    // Apply these functions to the color values.
    let result = values;
    while (path.length > 1) {
      const start = path.shift();
      const end = path[0];
      const functionName = `${start}_to_${end}`.replace(/-/g, '');
      const func = Conversions[functionName as keyof typeof Conversions];
      if (typeof func !== 'function') throw new UndefinedConversionError(functionName);
      result = func(result);
    }

    return result;
  }


  /**
   * Converts the r, g, b values of the color to another color space.
   * @param destinationSpaceID Desired color space, or its identifier.
   * @param options
   * @param options.clamp Whether to clamp the values to their new color space.
   * @returns The array of converted values.
   */
  public valuesTo(destinationSpaceID: colorSpaceOrID, { clamp = false } = {}): number[] {
    const destinationSpace = Couleur.getSpace(destinationSpaceID);
    let values = this.#cache.get(destinationSpace.id);
    if (!values) {
      values = Couleur.convert('srgb', destinationSpace, this.values);
      this.#cache.set(destinationSpace.id, values);
    }
    if (clamp) values = Couleur.valuesToGamut(destinationSpace, values, destinationSpace);
    return values;
  }


  /* Clamping to a color space */


  /**
   * Checks whether parsed values in valueSpaceID color space correspond to a color in the spaceID color space.
   * @param destinationSpaceID Color space whose gamut will be checked, or its identifier.
   * @param values Array of parsed values.
   * @param sourceSpaceID Color space of the given values, or its identifier.
   * @returns Whether the corresponding color is in gamut.
   */
  public static valuesInGamut(destinationSpaceID: colorSpaceOrID, values: number[] | Couleur, sourceSpaceID: colorSpaceOrID = 'srgb', { tolerance = .0001 } = {}): boolean {
    const destinationSpace = Couleur.getSpace(destinationSpaceID) as ColorSpaceWithoutGamut;
    const gamutSpace = (
      destinationSpace.gamutSpace ? Couleur.getSpace(destinationSpace.gamutSpace)
                                  : destinationSpace
    ) as ColorSpaceWithGamut;
    const convertedValues = values instanceof Couleur ? values.valuesTo(gamutSpace)
                                                      : Couleur.convert(sourceSpaceID, gamutSpace, values);
    return convertedValues.every((v, k) => v >= (gamutSpace.gamut[k][0] - tolerance) && v <= (gamutSpace.gamut[k][1] + tolerance));
  }

  /** @see Couleur.valuesInGamut - Non-static version. */
  public inGamut(destinationSpaceID: colorSpaceOrID, options = {}) { return Couleur.valuesInGamut(destinationSpaceID, this, undefined, options); }

  /**
   * Clamps parsed values in valueSpaceID color space to the spaceID color space.
   * @param destinationSpaceID Color space whose gamut will be used, or its identifier.
   * @param values Array of parsed values.
   * @param sourceSpaceID Color space of the given values, or its identifier.
   * @returns The array of values in valueSpaceID color space, after clamping the color to spaceID color space.
   */
  public static valuesToGamut(destinationSpaceID: colorSpaceOrID, values: number[] | Couleur, sourceSpaceID: colorSpaceOrID = 'srgb', { method = 'okchroma' }: toGamutOptions = {}): number[] {
    const destinationSpace = Couleur.getSpace(destinationSpaceID) as ColorSpaceWithoutGamut;
    const gamutSpace = (
      destinationSpace.gamutSpace ? Couleur.getSpace(destinationSpace.gamutSpace)
                                  : destinationSpace
    ) as ColorSpaceWithGamut;
    const sourceSpace = Couleur.getSpace(sourceSpaceID);
    const _method = method.toLowerCase();

    if (values instanceof Couleur) {
      if (values.inGamut(destinationSpace, { tolerance: 0 })) return values.valuesTo(sourceSpace);
      values = values.valuesTo(sourceSpace);
    } else {
      if (Couleur.valuesInGamut(destinationSpace, values, sourceSpace, { tolerance: 0 })) return values;
    }
    
    let clampedValues: number[], clampSpace: ColorSpace;

    switch (_method) {

      // OKLCH chroma gamut clipping
      // Source of the math: https://www.w3.org/TR/css-color-4/#gamut-mapping
      case 'okchroma': {
        clampSpace = Couleur.getSpace('oklch');
        let oklch = Couleur.convert(sourceSpace, clampSpace, values);

        const τ = .000001;
        const δ = .02;

        if (oklch[0] >= 1 - τ) {
          return Couleur.convert(gamutSpace, sourceSpace, gamutSpace.white || [1, 1, 1]);
        } else if (oklch[0] <= 0 + τ) {
          return Couleur.convert(gamutSpace, sourceSpace, gamutSpace.black || [0, 0, 0]);
        }
        
        let Cmin = 0;
        let Cmax = oklch[1];
        oklch[1] = oklch[1] / 2;

        while (Cmax - Cmin > τ) {
          if (Couleur.valuesInGamut(destinationSpace, oklch, clampSpace, { tolerance: 0 })) {
            Cmin = oklch[1];
          } else {
            const naiveOklch = Couleur.valuesToGamut(destinationSpace, oklch, clampSpace, { method: 'naive' });
            const naiveOklab = Couleur.convert(clampSpace, 'oklab', naiveOklch);
            const oklab = Couleur.convert(clampSpace, 'oklab', oklch);

            if (Distances.euclidean(naiveOklab, oklab) < δ) {
              oklch = naiveOklch;
              break;
            }
            Cmax = oklch[1];
          }
          oklch[1] = (Cmin + Cmax) / 2;
        }

        clampedValues = oklch;
      } break;

      // Naively clamp the values
      default: {
        clampSpace = gamutSpace;
        const convertedValues = Couleur.convert(sourceSpace, clampSpace, values);
        clampedValues = convertedValues.map((v, k) => Math.max(gamutSpace.gamut[k][0], Math.min(v, gamutSpace.gamut[k][1])));
      }

    }

    // Final naive clamp to get in the color space if the color is still just outside the border
    if (_method !== 'naive') clampedValues = Couleur.valuesToGamut(destinationSpace, clampedValues, clampSpace, { method: 'naive' });

    // Send the values back in the same color space we found them in
    return Couleur.convert(clampSpace, sourceSpace, clampedValues);
  }

  /** @see Couleur.valuesToGamut - Non-static version. */
  public toGamut(destinationSpaceID: colorSpaceOrID): Couleur {
    const destinationSpace = Couleur.getSpace(destinationSpaceID);
    const rgbClampedValues = Couleur.valuesToGamut(destinationSpace, this, undefined);
    return new Couleur([...rgbClampedValues, this.a]);
  }



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
  public change(prop: ColorProperty, value: string | number, { action }: { action?: 'replace' | 'scale' } = {}): Couleur {
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
  public replace(prop: ColorProperty, value: string | number): Couleur {
    return this.change(prop, value, { action: 'replace' });
  }

  /**
   * Modifies a color by scaling the value of a specific property by a percentage.
   * This is an alias to change() with options.scale = true
   * @param prop The color property that will be changed.
   * @param value The percentage that will be multiplied to the previous value of the property.
   * @returns The modified color.
   */
  public scale(prop: ColorProperty, value: string | number): Couleur {
    return this.change(prop, value, { action: 'scale' });
  }

  /** @returns The complementary color. */
  public complement(): Couleur { return this.change('h', 180); }

  /** @returns The inverse color. */
  public negative(): Couleur { return new Couleur([1 - this.r, 1 - this.g, 1 - this.b, this.a]); }
  public invert(): Couleur { return this.negative(); }

  /** @returns The shade of grey of the color. */
  public greyscale(): Couleur {
    const L = this.replace('a', 1).luminance;
    return new Couleur([L, L, L, this.a]);
  }
  public grayscale(): Couleur { return this.greyscale(); }

  /** @returns The sepia tone of the color. */
  public sepia(): Couleur {
    const r = Math.min(0.393 * this.r + 0.769 * this.g + 0.189 * this.b, 1);
    const g = Math.min(0.349 * this.r + 0.686 * this.g + 0.168 * this.b, 1);
    const b = Math.min(0.272 * this.r + 0.534 * this.g + 0.131 * this.b, 1);
    return new Couleur([r, g, b, this.a]);
  }


  /* Color blending */


  /**
   * Blends two colors together.
   * @param backgroundColor Background color.
   * @param overlayColor Overlay color.
   * @param alpha Alpha value that will replace overlay's.
   * @returns The resulting color.
   */
  public static blend(backgroundColor: color, overlayColor: color, alpha?: unparsedAlphaValue): Couleur {
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
    if (colors.length < 2) throw new Error(`You need at least 2 colors to blend`);
    const background = colors.shift();
    const overlay = colors.shift();
    
    if (background == null || overlay == null) throw new Error('Cannot blend undefined color');
    
    const mix = Couleur.blend(background, overlay);

    if (colors.length === 0) return mix;
    else                     return Couleur.blendAll(mix, ...colors);
  }

  /** @see Couleur.blend - Non-static version. */
  public blend(overlayColor: color, alpha?: unparsedAlphaValue): Couleur { return Couleur.blend(this, overlayColor, alpha); }

  /** @see Couleur.blendAll - Non-static version. */
  public blendAll(...colors: color[]): Couleur { return Couleur.blendAll(this, ...colors); }


  /**
   * Solves the equation mix = blend(background, overlay) with background unknown.
   * @param mixColor The result of the blend.
   * @param overlayColor Color that was mixed with background to create mix.
   * @returns The background that is solution to the equation, if it has one.
   * @throws If the equation has an infinite amount of solutions.
   */
  public static unblend(mixColor: color, overlayColor: color, alpha?: unparsedAlphaValue): Couleur | null {
    const mix = Couleur.makeInstance(mixColor);
    const overlay = Couleur.makeInstance(overlayColor);
    if (alpha != null) // if alpha isn't null or undefined
      overlay.a = Couleur.parse(alpha, 'a');

    if (overlay.a === 1) {
      throw new Error(`Overlay color ${JSON.stringify(overlay.rgb)} isn't transparent, so the background it was blended onto could have been any color`);
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
        const clampedValues = Couleur.valuesToGamut('srgb', [r, g, b], 'srgb');
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
    if (colors.length < 2) throw new Error(`You need at least 2 colors to unblend`);
    const mix = colors.shift();
    const overlay = colors.shift();

    if (mix == null || overlay == null) throw new Error('Cannot unblend undefined color');
    
    const background = Couleur.unblend(mix, overlay);

    if (background == null)       return null;
    else if (colors.length === 0) return background;
    else                          return Couleur.unblendAll(background, ...colors);
  }

  /** @see Couleur.unblend - Non-static version. */
  public unblend(overlayColor: color, alpha?: unparsedAlphaValue): Couleur | null { return Couleur.unblend(this, overlayColor, alpha); }
  
  /** @see Couleur.unblendAll - Non-static version. */
  public unblendAll(...colors: color[]): Couleur | null { return Couleur.unblendAll(this, ...colors); }


  /**
   * Solves the equation mix = blend(background, overlay) with overlay unknown.
   * @param backgroundColor The background color.
   * @param mixColor The result of the blend.
   * @param alphas The alpha value(s) you want the solution(s) to have.
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
      if (!Couleur.valuesInGamut('srgb', [r, g, b], 'srgb', { tolerance: 1/255 })) throw new Error(`This color doesn't exist`);
      const clampedValues = Couleur.valuesToGamut('srgb', [r, g, b], 'srgb', { method: 'naive' });
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
  public static contrast(textColor: color, backgroundColor: color, { method = 'apca' }: contrastOptions = {}): number {
    const background = Couleur.makeInstance(backgroundColor);
    if (background.a < 1) throw new Error(`The contrast with a transparent background color would be meaningless`);
    let text = Couleur.makeInstance(textColor);

    // If the text is transparent, blend it to the background to get its actual visible color
    if (text.a < 1) text = Couleur.blend(background, text);

    switch (method.toLowerCase()) {
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
   * Modifies the OK lightness of a color to give it better contrast with another color.
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
  public improveContrast(referenceColor: color, desiredContrast: number, { as = 'text', lower = false, colorScheme, method = 'apca' }: improveContrastOptions = {}): Couleur {
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
      const okl = (OKLmin + OKLmax) / 2;
      const newValues = movingLab; newValues[0] = okl;
      const newContrast = Math.abs(
        as === 'text' ? Couleur.contrast(Couleur.convert('oklab', 'srgb', newValues), background, { method })
                      : Couleur.contrast(text, Couleur.convert('oklab', 'srgb', newValues), { method })
      );

      // If the new contrast hasn't gone over its desired value
      const condition = (directionContrast > 0) ? (newContrast < desiredContrast) : (newContrast > desiredContrast);
      if (condition) {
        if (directionOKL > 0) OKLmin = okl;
        else                  OKLmax = okl;
      }
      // If we overshot and the contrast moved further than we want it to
      else {
        if (directionOKL > 0) OKLmax = okl;
        else                  OKLmin = okl;
      }

      movingLab[0] = okl;
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
  public static distance(color1: color, color2: color, { method = 'deltae2000', alpha = true }: distanceOptions = {}): number { 
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
  public static same(color1: color, color2: color, { tolerance = 1, method = 'deltae2000' }: sameOptions = {}): boolean {
    if (Couleur.distance(color1, color2, { method }) > tolerance) return false;
    else return true;
  }

  /** @see Couleur.same - Non-static version. */
  public same(color: color, options: sameOptions = {}): boolean { return Couleur.same(this, color, options); }


  /* Other functions */

  
  /**
   * Linearly interpolates between two colors.
   * @param color1 The first color.
   * @param color2 The second color.
   * @param steps The number of intermediate colors to calculate.
   * @param destinationSpaceID The color space in which to interpolate.
   * @param hueInterpolationMethod The method used to interpolate hues.
   */
  public static interpolate(color1: color, color2: color, steps: number, destinationSpaceID: colorSpaceOrID, { hueInterpolationMethod = 'shorter', premultiplyAlpha = true }: interpolateOptions = {}) {
    const start = Couleur.makeInstance(color1);
    const end = Couleur.makeInstance(color2);
    const _steps = Math.max(0, steps);

    const destinationSpace = Couleur.getSpace(destinationSpaceID);
    const props = Couleur.propertiesOf(destinationSpace.id);

    let startValues = start.valuesTo(destinationSpace);
    let endValues = end.valuesTo(destinationSpace);

    // Premultiply alpha values
    const premultiply = (values: number[], a: number) => values.map((v, k) => {
      switch (props[k]) {
        case 'h':
        case 'cieh':
        case 'okh':
          return v;

        default:
          return a * v;
      }
    });
    if (premultiplyAlpha) {
      startValues = premultiply(startValues, start.a);
      endValues = premultiply(endValues, end.a);
    }

    // Calculate by how much each property will be changed at each step
    const stepList = props.map((prop, k) => {
      switch (prop) {      
        case 'h':
        case 'cieh':
        case 'okh': {
          const diff = endValues[k] - startValues[k];

          switch (hueInterpolationMethod) {
            case 'shorter':
              if (diff > 180) startValues[k] += 360;
              else if (diff < -180) endValues[k] += 360;
              break;

            case 'longer':
              if (0 < diff && diff < 180) startValues[k] += 360;
              else if (-180 < diff && diff <= 0) endValues[k] += 360;
              break;

            case 'increasing':
              if (diff < 0) endValues[k] += 360;
              break;

            case 'decreasing':
              if (0 < diff) startValues[k] += 360;
              break;
          }
        } // don't break: the step value is computed in the default case

        default:
          return (endValues[k] - startValues[k]) / (_steps + 1);
      }
    });
    const stepAlpha = (end.a - start.a) / (_steps + 1);

    // Calculate the intermediate colors
    let intermediateColors = [startValues];
    for (let i = 1; i <= _steps; i++) {
      const previous = intermediateColors[i - 1];

      let next = props.map((prop, k) => {
        const v = previous[k] + stepList[k];
        switch (prop) {
          case 'h':
          case 'cieh':
          case 'okh':
            return Utils.angleToRange(v);
          
          default:
            return v;
        }
      });

      intermediateColors.push(next);
    }
    intermediateColors.push(endValues);

    // Undo alpha premultiplication
    const undoPremultiply = (values: number[], stepIndex: number) => values.map((v, k) => {
      const a = start.a + stepIndex * stepAlpha;
      switch (props[k]) {
        case 'h':
        case 'cieh':
        case 'okh':
          return v;

        default:
          return v / a;
      }
    });
    if (premultiplyAlpha) {
      intermediateColors = intermediateColors.map((values, k) => undoPremultiply(values, k));
    }

    return intermediateColors.map((values, k) => new Couleur([
      ...Couleur.convert(destinationSpace, 'srgb', values),
      start.a + k * stepAlpha
    ]));
  }

  /** @see Couleur.interpolate - Non-static version. */
  public interpolate(color: color, steps: number, destinationSpaceID: colorSpaceOrID, options: interpolateOptions = {}) { return Couleur.interpolate(this, color, steps, destinationSpaceID, options); }



  /**************/
  /* Color data */
  /**************/

  /**
   * Gets the list of the short names of color properties used in a given CSS format.
   * @param format Name of the color format.
   * @returns Array of color property names.
   */
  public static propertiesOf(format: string): ColorProperty[] {
    return Couleur.getSpace(format.toLowerCase()).properties ?? [];
  }

  /** @returns Array of all color property short names. */
  public static get properties(): ColorProperty[] {
    const props: Set<ColorProperty> = new Set();
    for (const space of Couleur.colorSpaces) {
      space.properties?.map(p => props.add(p));
    }
    return [...props, 'a'];
  }

  /**
   * Gets a color space from its id.
   * @param spaceID Identifier of a color space, or a color space itself.
   * @returns The corresponding color space object.
   * @throws When the color space is not supported.
   */
  protected static getSpace(spaceID: colorSpaceOrID): ColorSpace {
    let result: ColorSpace | undefined;
    if (typeof spaceID !== 'string') {
      if (spaceID == null) {
        throw new UnsupportedColorSpaceError('null');
      } else { 
        return spaceID;
      }
    } else {
      let id = spaceID.toLowerCase();
      result = Couleur.colorSpaces.find(sp => sp.id === id || sp.aliases.includes(id));

      if (result == null) throw new UnsupportedColorSpaceError(spaceID);
      return result;
    }
  }

  /** @returns Array of supported color spaces. */
  public static get colorSpaces(): ColorSpace[] { return colorSpaces; }

  /** @returns Graph of supported color spaces and the links (i.e. conversion functions) between them. */
  public static get colorSpacesGraph(): GraphWithCachedPaths { return colorSpacesGraph; }

  /** @returns Array of supported syntaxes. */
  public static get cssFormats(): CSSFormat[] { return cssFormats; }

  /** @returns List of named colors in CSS. */
  public static get namedColors(): Map<string, string> { return namedColors; }
}