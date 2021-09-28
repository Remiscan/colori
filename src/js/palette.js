import Couleur from './couleur.js';



export default class Palette {
  /**
   * Creates a palette from a hue.
   * @param {number} hue - The hue of the main color of the palette in OKLAB color space. 
   * @param {function} generator - A function that generates an array of { lightnesses, chroma, hue } objects.
   * @param {object} options
   * @param {string?} options.clampSpace - Color space to which the generated colors will be clamped. Null to disable clamping.
   */
  constructor(hue, generator = () => [], { clampSpace = 'srgb' } = {}) {
    this.colors = []; // Will be an array of arrays of color nuances.
    const colors = generator(hue);

    // Create the nuances of each color.
    for (const color of colors) {
      const nuances = [];
      for (const lightness of color.lightnesses) {
        let rgb = Couleur.convert('oklch', 'srgb', [lightness, color.chroma, color.hue]);
        if (clampSpace != null) rgb = Couleur.toGamut(clampSpace, rgb);
        const newColor = new Couleur(`color(srgb ${rgb.join(' ')})`);
        nuances.push(newColor);
      }
      this.colors.push(nuances);
    }
  }
}