import * as Conversions from './conversion.js';
export { APCAcontrast as APCA } from './ext/apca-contrast.js';



/** @returns Luminance of the color. */
// Source of the math: https://www.w3.org/TR/2008/REC-WCAG20-20081211/#relativeluminancedef
export function luminance(rgb: number[]): number {
  const linrgb = Conversions.srgb_to_srgblinear(rgb);
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