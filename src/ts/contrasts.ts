import * as Conversions from './conversion.js';



/** @returns Luminance of the color. */
// Source of the math: https://www.w3.org/TR/2008/REC-WCAG20-20081211/#relativeluminancedef
export function luminance(rgb: number[]): number {
  const linrgb = Conversions.srgb_to_lin_srgb(rgb);
  return 0.2126729 * linrgb[0] + 0.7151522 * linrgb[1] + 0.0721750 * linrgb[2];
}


/**
 * Computes the contrast between two colors as defined by WCAG2.
 * @param rgbText Array of r, g, b values of the text.
 * @param rgbBack Array of r, g, b values of the background.
 * @returns Contrast between the two colors, in [1, 21].
 */
// Source of the math: https://www.w3.org/TR/2008/REC-WCAG20-20081211/#contrast-ratiodef
export function WCAG2(rgbText: number[], rgbBack: number[]): number {
  const L1 = luminance(rgbText);
  const L2 = luminance(rgbBack);
  return (Math.max(L1, L2) + 0.05) / (Math.min(L1, L2) + 0.05);
}


/**
 * Computes the SAPC/APCA contrast between two colors as defined by WCAG3.
 * @param rgbText Array of r, g, b values of the text.
 * @param rgbBack Array of r, g, b values of the background.
 * @returns Contrast between the two colors.
 */
// Source of the math: https://github.com/Myndex/SAPC-APCA
export function APCA(rgbText: number[], rgbBack: number[]): number {
  // 1. Compute luminances
  const coeffs = [0.2126729, 0.7151522, 0.0721750];
  const gamma = 2.4;
  const luminance = (rgb: number[]) => rgb.reduce((sum, v, i) => sum + Math.pow(v, gamma) * coeffs[i], 0);
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
  const compute = (Lback: number, Ltext: number, powBack: number, powText: number) => (Math.pow(Lback, powBack) - Math.pow(Ltext, powText)) * scale;
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
}