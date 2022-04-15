export interface ColorSpace {
  id: string,
  gamut: Array<number[]>,
  links: string[]
};

const colorSpaces: ColorSpace[] = [
  {
    id: 'srgb',
    gamut: [ [0, 1], [0, 1], [0, 1] ],
    links: ['srgb-linear', 'hsl']
  }, {
    id: 'srgb-linear',
    gamut: [ [0, 1], [0, 1], [0, 1] ],
    links: ['srgb', 'xyz-d65']
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
    links: ['xyz-d50', 'lch']
  }, {
    id: 'lch',
    gamut: [ [0, 4], [0, +Infinity], [0, 360] ],
    links: ['lab']
  }, {
    id: 'xyz-d50',
    gamut: [ [-Infinity, +Infinity], [-Infinity, +Infinity], [-Infinity, +Infinity] ],
    links: ['lab', 'xyz-d65', 'prophoto-rgb-linear']
  }, {
    id: 'xyz-d65',
    gamut: [ [-Infinity, +Infinity], [-Infinity, +Infinity], [-Infinity, +Infinity] ],
    links: ['xyz-d50', 'srgb-linear', 'display-p3-linear', 'a98-rgb-linear', 'rec2020-linear', 'oklab']
  }, {
    id: 'display-p3',
    gamut: [ [0, 1], [0, 1], [0, 1] ],
    links: ['display-p3-linear']
  }, {
    id: 'display-p3-linear',
    gamut: [ [0, 1], [0, 1], [0, 1] ],
    links: ['display-p3', 'xyz-d65']
  }, {
    id: 'a98-rgb',
    gamut: [ [0, 1], [0, 1], [0, 1] ],
    links: ['a98-rgb-linear']
  }, {
    id: 'a98-rgb-linear',
    gamut: [ [0, 1], [0, 1], [0, 1] ],
    links: ['a98-rgb', 'xyz-d65']
  }, {
    id: 'prophoto-rgb',
    gamut: [ [0, 1], [0, 1], [0, 1] ],
    links: ['prophoto-rgb-linear']
  }, {
    id: 'prophoto-rgb-linear',
    gamut: [ [0, 1], [0, 1], [0, 1] ],
    links: ['prophoto-rgb', 'xyz-d50']
  }, {
    id: 'rec2020',
    gamut: [ [0, 1], [0, 1], [0, 1] ],
    links: ['rec2020-linear']
  }, {
    id: 'rec2020-linear',
    gamut: [ [0, 1], [0, 1], [0, 1] ],
    links: ['rec2020', 'xyz-d65']
  }, {
    id: 'oklab',
    gamut: [ [0, 4], [-Infinity, +Infinity], [-Infinity, +Infinity] ],
    links: ['xyz-d65', 'oklch']
  }, {
    id: 'oklch',
    gamut: [ [0, 4], [0, +Infinity], [0, 360] ],
    links: ['oklab']
  }
];



export default colorSpaces;