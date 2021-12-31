import * as OKLABConversions from './ext/oklab-conversion.js';
import * as W3Conversions from './ext/w3-conversion.js';



// All of these functions take an array of parsed color values (without alpha),
// and return an array of parsed color values (without alpha) converted into the 
// desired color space or CSS format.



/* srgb */

export function srgb_to_lin_srgb(rgb: number[]): number[] {
  return W3Conversions.srgb_to_lin_srgb(rgb);
}

export function lin_srgb_to_srgb(rgb: number[]): number[] {
  return W3Conversions.lin_srgb_to_srgb(rgb);
}

export function lin_srgb_to_d65xyz(rgb: number[]): number[] {
  return W3Conversions.lin_srgb_to_d65xyz(rgb);
}

export function d65xyz_to_lin_srgb(xyz: number[]): number[] {
  return W3Conversions.d65xyz_to_lin_srgb(xyz);
}



/* hsl */

export function srgb_to_hsl(rgb: number[]): number[] {
  // Source of the math: https://en.wikipedia.org/wiki/HSL_and_HSV#General_approach
  const [r, g, b] = rgb; // all in [0, 1]

  const max = Math.max(r, g, b);
  const min = Math.min(r, g, b);
  const chroma = max - min;

  const l = (max + min) / 2;

  let h: number;
  if (chroma === 0) h = 0;
  else switch (max) {
    case r: h = (g - b) / chroma; break;
    case g: h = (b - r) / chroma + 2; break;
    default: h = (r - g) / chroma + 4;
  }
  h = 60 * h;
  while (h < 0)   h += 360;
  while (h > 360) h -= 360;

  let s: number;
  if (l === 0 || l === 1) s = 0;
  else if (l <= 0.5)      s = chroma / (2 * l);
  else                    s = chroma / (2 - 2 * l);

  return [h, s, l]; // h in [0, 360], s & l in [0, 1]
}

export function hsl_to_srgb(hsl: number[]): number[] {
  // Source of the math: https://en.wikipedia.org/wiki/HSL_and_HSV#HSL_to_RGB_alternative
  const [h, s, l] = hsl; // h in [0, 360], s & l in [0, 1]

  const m = s * Math.min(l, 1 - l);
  const k = (n: number) => (n + h / 30) % 12;
  const f = (n: number) => l - m * Math.max(Math.min(k(n) - 3, 9 - k(n), 1), -1);

  const r = f(0);
  const g = f(8);
  const b = f(4);

  return [r, g, b]; // all in [0, 1]
}



/* hwb */

export function hsl_to_hwb(hsl: number[]): number[] {
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
}

export function hwb_to_hsl(hwb: number[]): number[] {
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
}



/* display-p3 */

export function displayp3_to_lin_displayp3(rgb: number[]): number[] { return W3Conversions.displayp3_to_lin_displayp3(rgb); }
export function lin_displayp3_to_displayp3(rgb: number[]): number[] { return W3Conversions.lin_displayp3_to_displayp3(rgb); }

export function lin_displayp3_to_d65xyz(rgb: number[]): number[] {
  return W3Conversions.lin_displayp3_to_d65xyz(rgb);
}

export function d65xyz_to_lin_displayp3(xyz: number[]): number[] {
  return W3Conversions.d65xyz_to_lin_displayp3(xyz);
}



/* prophoto-rgb */

export function prophotorgb_to_lin_prophotorgb(rgb: number[]): number[] {
  return W3Conversions.prophotorgb_to_lin_prophotorgb(rgb);
}

export function lin_prophotorgb_to_prophotorgb(rgb: number[]): number[] {
  return W3Conversions.lin_prophotorgb_to_prophotorgb(rgb);
}

export function lin_prophotorgb_to_xyz(rgb: number[]): number[] {
  return W3Conversions.lin_prophotorgb_to_xyz(rgb);
}

export function xyz_to_lin_prophotorgb(xyz: number[]): number[] {
  return W3Conversions.xyz_to_lin_prophotorgb(xyz);
}



/* a98-rgb */

export function a98rgb_to_lin_a98rgb(rgb: number[]): number[] {
  return W3Conversions.a98rgb_to_lin_a98rgb(rgb);
}

export function lin_a98rgb_to_a98rgb(rgb: number[]): number[] {
  return W3Conversions.lin_a98rgb_to_a98rgb(rgb);
}

export function lin_a98rgb_to_d65xyz(rgb: number[]): number[] {
  return W3Conversions.lin_a98rgb_to_d65xyz(rgb);
}

export function d65xyz_to_lin_a98rgb(xyz: number[]): number[] {
  return W3Conversions.d65xyz_to_lin_a98rgb(xyz);
}



/* rec2020 */

export function rec2020_to_lin_rec2020(rgb: number[]): number[] {
  return W3Conversions.rec2020_to_lin_rec2020(rgb);
}

export function lin_rec2020_to_rec2020(rgb: number[]): number[] {
  return W3Conversions.lin_rec2020_to_rec2020(rgb);
}

export function lin_rec2020_to_d65xyz(rgb: number[]): number[] {
  return W3Conversions.lin_rec2020_to_d65xyz(rgb);
}

export function d65xyz_to_lin_rec2020(xyz: number[]): number[] {
  return W3Conversions.d65xyz_to_lin_rec2020(xyz);
}



/* lab */

export function xyz_to_lab(xyz: number[]): number[] {
  return W3Conversions.xyz_to_lab(xyz);
}

export function lab_to_xyz(lab: number[]): number[] {
  return W3Conversions.lab_to_xyz(lab);
}

export function lab_to_lch(lab: number[]): number[] {
  return W3Conversions.lab_to_lch(lab);
}

export function lch_to_lab(lch: number[]): number[] {
  return W3Conversions.lch_to_lab(lch);
}



/* oklab */

export function lin_srgb_to_oklab(rgb: number[]): number[] {
  return OKLABConversions.lin_srgb_to_oklab(rgb);
}

export function oklab_to_lin_srgb(lab: number[]): number[] {
  return OKLABConversions.oklab_to_lin_srgb(lab);
}

export function oklab_to_oklch(lab: number[]): number[] { return lab_to_lch(lab); }
export function oklch_to_oklab(lch: number[]): number[] { return lch_to_lab(lch); }



/* Bradford transform */

export function d65xyz_to_xyz(xyz: number[]): number[] {
  return W3Conversions.d65xyz_to_xyz(xyz);
}

export function xyz_to_d65xyz(xyz: number[]): number[] {
  return W3Conversions.xyz_to_d65xyz(xyz);
}