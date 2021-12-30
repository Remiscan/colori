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
// Source: https://github.com/Myndex/SAPC-APCA
export function APCA(rgbText: number[], rgbBack: number[]): number {
  // 1. Compute luminances (slightly different from luminances used in WCAG2 contrast)
  const coeffs = [0.2126729, 0.7151522, 0.0721750];
  const gamma = 2.4;
  const luminance = (rgb: number[]) => rgb.reduce((sum, v, i) => sum + Math.pow(v, gamma) * coeffs[i], 0);
  let [Ytext, Yback] = [rgbText, rgbBack].map(rgb => luminance(rgb));

  // APCA 0.0.98 G - 4g Constants
  const normBG = 0.56,
        normTXT = 0.57,
        revTXT = 0.62,
        revBG = 0.65;
  const blkThrs = 0.022,
        blkClmp = 1.414,
        scaleBoW = 1.14,
        scaleWoB = 1.14,
        loBoWthresh = 0.035991,
        loWoBthresh = 0.035991,
        loBoWfactor = 27.7847239587675,
        loWoBfactor = 27.7847239587675,
        loBoWoffset = 0.027,
        loWoBoffset = 0.027,
        loClip = 0.001,
        deltaYmin = 0.0005;

  // 2. Clamp luminances
  [Ytext, Yback] = [Ytext, Yback].map(Y => Y > blkThrs ? Y : Y + Math.pow(blkThrs - Y, blkClmp));
  if (Math.abs(Ytext - Yback) < deltaYmin) return 0;

  // 3. Compute contrast
  let SAPC = 0;
  let output = 0;

  // for dark text on light background
  if (Yback > Ytext) {
    SAPC = (Math.pow(Yback, normBG) - Math.pow(Ytext, normTXT)) * scaleBoW;
    output = (SAPC < loClip) ? 0
           : (SAPC < loBoWthresh) ? SAPC - SAPC * loBoWfactor * loBoWoffset
           : SAPC - loBoWoffset;
  }

  // for light text on dark background
  else {
    SAPC = (Math.pow(Yback, revBG) - Math.pow(Ytext, revTXT)) * scaleWoB;
    output = (SAPC > -loClip) ? 0
           : (SAPC > -loWoBthresh) ? SAPC - SAPC * loWoBfactor * loWoBoffset
           : SAPC + loWoBoffset;
  }

  return output * 100;
}