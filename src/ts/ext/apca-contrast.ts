/******************************************************************
 * Derived from https://github.com/Myndex/apca-w3                 *
 * under W3C license (Copyright © 2019-2021 by Andrew Somers)     *
 ******************************************************************/



/**
 * Computes the luminance of a color (slightly different from luminances used in WCAG2 contrast).
 * @param rgb - Array of r, g, b values of the color in [0, 1].
 * @returns The luminance value.
 */
function luminance(rgb: number[]): number {
  const coeffs = [0.2126729, 0.7151522, 0.0721750];
  const gamma = 2.4;
  return rgb.reduce((sum, v, i) => sum + Math.pow(v, gamma) * coeffs[i], 0);
}


/**
 * Computes the SAPC/APCA contrast between two colors as defined by WCAG3.
 * @param rgbText Array of r, g, b values of the text.
 * @param rgbBack Array of r, g, b values of the background.
 * @returns Contrast between the two colors.
 */
export function APCAcontrast(rgbText: number[], rgbBack: number[]): number {
  let [Ytext, Yback] = [rgbText, rgbBack].map(rgb => luminance(rgb));
  if (isNaN(Ytext) || isNaN(Yback) || Math.min(Ytext, Yback) < 0 || Math.max(Ytext, Yback) > 1.1) return 0;

  // APCA 0.1.1   G - 4g - W3 Constants
  const normBG = 0.56,
        normTXT = 0.57,
        revTXT = 0.62,
        revBG = 0.65;
  const blkThrs = 0.022,
        blkClmp = 1.414,
        scaleBoW = 1.14,
        scaleWoB = 1.14,
        loBoWoffset = 0.027,
        loWoBoffset = 0.027,
        loClip = 0.1,
        deltaYmin = 0.0005;

  // Clamp luminances
  [Ytext, Yback] = [Ytext, Yback].map(Y => Y > blkThrs ? Y : Y + Math.pow(blkThrs - Y, blkClmp));

  // Compute contrast
  if (Math.abs(Ytext - Yback) < deltaYmin) return 0;
  let SAPC = 0;
  let output = 0;

  // - for dark text on light background
  if (Yback > Ytext) {
    SAPC = (Math.pow(Yback, normBG) - Math.pow(Ytext, normTXT)) * scaleBoW;
    output = (SAPC < loClip) ? 0 : SAPC - loBoWoffset;
  }

  // - for light text on dark background
  else {
    SAPC = (Math.pow(Yback, revBG) - Math.pow(Ytext, revTXT)) * scaleWoB;
    output = (SAPC > -loClip) ? 0 : SAPC + loWoBoffset;
  }

  return output * 100;
}