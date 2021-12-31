/******************************************************************
 * Derived from https://github.com/Myndex/SAPC-APCA               *
 * under AGPL v3 license (Copyright © 2019-2021 by Andrew Somers) *
 ******************************************************************/



/**
 * Computes the SAPC/APCA contrast between two colors as defined by WCAG3.
 * @param rgbText Array of r, g, b values of the text.
 * @param rgbBack Array of r, g, b values of the background.
 * @returns Contrast between the two colors.
 */
export function APCAcontrast(rgbText: number[], rgbBack: number[]): number {
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

  // 3. Compute contrast
  if (Math.abs(Ytext - Yback) < deltaYmin) return 0;
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