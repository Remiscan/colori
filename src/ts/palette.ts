import Couleur from './couleur.js';



export default class Palette {
  colors: Couleur[][] = []; // Will be an array of arrays of color nuances.

  /**
   * Creates a color palet from a hue.
   * @param color The color from which the palet will be derived.
   * @param generator A function that generates an array of { lightnesses, chroma, hue } objects (values in OKLAB color space).
   * @param options
   * @param options.clampSpace Color space to which the generated colors will be clamped. Null to disable clamping.
   */
  constructor(color: Couleur, generator: (color: Couleur) => Array<{ lightnesses: number[], chroma: number, hue: number}> = () => [], { clampSpace = 'srgb' }: { clampSpace?: string } = {}) {
    const colors = generator(color);

    // Create the nuances of each color.
    for (const color of colors) {
      const nuances: Couleur[] = [];
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