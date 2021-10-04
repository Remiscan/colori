export interface ColorSpace {
  id: string,
  whitepoint?: string,
  CSSformat?: string,
  gamut?: Array<number[]>,
  links: string[]
};

const colorSpaces: ColorSpace[] = [
  {
    id: 'srgb',
    whitepoint: 'd65',
    CSSformat: 'rgb',
    gamut: [ [0, 1], [0, 1], [0, 1] ],
    links: ['lin_srgb', 'hsl']
  }, {
    id: 'lin_srgb',
    links: ['srgb', 'd65xyz', 'oklab']
  }, {
    id: 'hsl',
    whitepoint: 'd65',
    CSSformat: 'hsl',
    gamut: [ [0, 360], [0, 1], [0, 1] ],
    links: ['srgb', 'hwb']
  }, {
    id: 'hwb',
    whitepoint: 'd65',
    CSSformat: 'hwb',
    gamut: [ [0, 360], [0, 1], [0, 1] ],
    links: ['hsl']
  }, {
    id: 'lab',
    whitepoint: 'd50',
    CSSformat: 'lab',
    gamut: [ [0, 4], [-Infinity, Infinity], [-Infinity, Infinity] ],
    links: ['xyz', 'lch']
  }, {
    id: 'lch',
    whitepoint: 'd50',
    CSSformat: 'lch',
    gamut: [ [0, 4], [0, +Infinity], [0, 360] ],
    links: ['lab']
  }, {
    id: 'xyz',
    whitepoint: 'd50',
    CSSformat: 'color',
    gamut: [ [-Infinity, +Infinity], [-Infinity, +Infinity], [-Infinity, +Infinity] ],
    links: ['lab', 'd65xyz', 'lin_prophoto-rgb']
  }, {
    id: 'd65xyz',
    whitepoint: 'd65',
    links: ['xyz', 'lin_srgb', 'lin_display-p3', 'lin_a98-rgb', 'lin_rec2020']
  }, {
    id: 'display-p3',
    whitepoint: 'd65',
    CSSformat: 'color',
    gamut: [ [0, 1], [0, 1], [0, 1] ],
    links: ['lin_display-p3']
  }, {
    id: 'lin_display-p3',
    links: ['display-p3', 'd65xyz']
  }, {
    id: 'a98-rgb',
    whitepoint: 'd65',
    CSSformat: 'color',
    gamut: [ [0, 1], [0, 1], [0, 1] ],
    links: ['lin_a98-rgb']
  }, {
    id: 'lin_a98-rgb',
    links: ['a98-rgb', 'd65xyz']
  }, {
    id: 'prophoto-rgb',
    whitepoint: 'd50',
    CSSformat: 'color',
    gamut: [ [0, 1], [0, 1], [0, 1] ],
    links: ['lin_prophoto-rgb']
  }, {
    id: 'lin_prophoto-rgb',
    links: ['prophoto-rgb', 'xyz']
  }, {
    id: 'rec2020',
    whitepoint: 'd65',
    CSSformat: 'color',
    gamut: [ [0, 1], [0, 1], [0, 1] ],
    links: ['lin_rec2020']
  }, {
    id: 'lin_rec2020',
    links: ['rec2020', 'd65xyz']
  }, {
    id: 'oklab',
    whitepoint: 'd65',
    gamut: [ [0, 4], [-Infinity, Infinity], [-Infinity, Infinity] ],
    links: ['lin_srgb', 'oklch']
  }, {
    id: 'oklch',
    whitepoint: 'd65',
    gamut: [ [0, 4], [0, +Infinity], [0, 360] ],
    links: ['oklab']
  }
];



export default colorSpaces;