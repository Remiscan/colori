import * as OKHSLV from './ext/okhsl-okhsv-conversion.js';
export * from './ext/w3-conversion.js';



// All of these functions take an array of parsed color values (without alpha),
// and return an array of parsed color values (without alpha) converted into the 
// desired color space or CSS format.



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



/* okhsl */

export function oklab_to_okhsl(lab: number[]): number[] {
  const [h, s, l] = OKHSLV.oklab_to_okhsl(lab);
  return [360 * h, s, l];
}

export function okhsl_to_oklab(hsl: number[]): number[] {
  const [h, s, l] = hsl;
  return OKHSLV.okhsl_to_oklab([h / 360, s, l]);
}



/* okhsv */

export function oklab_to_okhsv(lab: number[]): number[] {
  const [h, s, v] = OKHSLV.oklab_to_okhsv(lab);
  return [360 * h, s, v];
}

export function okhsv_to_oklab(hsv: number[]): number[] {
  const [h, s, v] = hsv;
  return OKHSLV.okhsv_to_oklab([h / 360, s, v]);
}