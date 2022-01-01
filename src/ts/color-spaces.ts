export interface ColorSpace {
  id: string,
  gamut: Array<number[]>,
  links: string[]
};

const colorSpaces: ColorSpace[] = [
  {
    id: 'srgb',
    gamut: [ [0, 1], [0, 1], [0, 1] ],
    links: ['lin_srgb', 'hsl']
  }, {
    id: 'lin_srgb',
    gamut: [ [0, 1], [0, 1], [0, 1] ],
    links: ['srgb', 'd65xyz', 'oklab']
  }, {
    id: 'hsl',
    gamut: [ [0, 360], [0, 1], [0, 1] ],
    links: ['srgb', 'hwb']
  }, {
    id: 'hwb',
    gamut: [ [0, 360], [0, 1], [0, 1] ],
    links: ['hsl']
  }, {
    id: 'lab',
    gamut: [ [0, 4], [-Infinity, +Infinity], [-Infinity, +Infinity] ],
    links: ['xyz', 'lch']
  }, {
    id: 'lch',
    gamut: [ [0, 4], [0, +Infinity], [0, 360] ],
    links: ['lab']
  }, {
    id: 'xyz',
    gamut: [ [-Infinity, +Infinity], [-Infinity, +Infinity], [-Infinity, +Infinity] ],
    links: ['lab', 'd65xyz', 'lin_prophoto-rgb']
  }, {
    id: 'd65xyz',
    gamut: [ [-Infinity, +Infinity], [-Infinity, +Infinity], [-Infinity, +Infinity] ],
    links: ['xyz', 'lin_srgb', 'lin_display-p3', 'lin_a98-rgb', 'lin_rec2020']
  }, {
    id: 'display-p3',
    gamut: [ [0, 1], [0, 1], [0, 1] ],
    links: ['lin_display-p3']
  }, {
    id: 'lin_display-p3',
    gamut: [ [-Infinity, +Infinity], [-Infinity, +Infinity], [-Infinity, +Infinity] ],
    links: ['display-p3', 'd65xyz']
  }, {
    id: 'a98-rgb',
    gamut: [ [0, 1], [0, 1], [0, 1] ],
    links: ['lin_a98-rgb']
  }, {
    id: 'lin_a98-rgb',
    gamut: [ [-Infinity, +Infinity], [-Infinity, +Infinity], [-Infinity, +Infinity] ],
    links: ['a98-rgb', 'd65xyz']
  }, {
    id: 'prophoto-rgb',
    gamut: [ [0, 1], [0, 1], [0, 1] ],
    links: ['lin_prophoto-rgb']
  }, {
    id: 'lin_prophoto-rgb',
    gamut: [ [-Infinity, +Infinity], [-Infinity, +Infinity], [-Infinity, +Infinity] ],
    links: ['prophoto-rgb', 'xyz']
  }, {
    id: 'rec2020',
    gamut: [ [0, 1], [0, 1], [0, 1] ],
    links: ['lin_rec2020']
  }, {
    id: 'lin_rec2020',
    gamut: [ [-Infinity, +Infinity], [-Infinity, +Infinity], [-Infinity, +Infinity] ],
    links: ['rec2020', 'd65xyz']
  }, {
    id: 'oklab',
    gamut: [ [0, 4], [-Infinity, +Infinity], [-Infinity, +Infinity] ],
    links: ['lin_srgb', 'oklch']
  }, {
    id: 'oklch',
    gamut: [ [0, 4], [0, +Infinity], [0, 360] ],
    links: ['oklab']
  }
];



export default colorSpaces;