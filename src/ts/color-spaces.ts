export interface ColorSpace {
  id: string,
  aliases: string[],
  gamut: Array<number[]>,
  links: string[]
};

const colorSpaces: ColorSpace[] = [
  {
    id: 'srgb',
    aliases: ['rgb', 'rgba'],
    gamut: [ [0, 1], [0, 1], [0, 1] ],
    links: ['srgb-linear', 'hsl']
  }, {
    id: 'srgb-linear',
    aliases: [],
    gamut: [ [0, 1], [0, 1], [0, 1] ],
    links: ['srgb', 'xyz-d65']
  }, {
    id: 'hsl',
    aliases: ['hsla'],
    gamut: [ [0, 360], [0, 1], [0, 1] ],
    links: ['srgb', 'hwb']
  }, {
    id: 'hwb',
    aliases: [],
    gamut: [ [0, 360], [0, 1], [0, 1] ],
    links: ['hsl']
  }, {
    id: 'lab',
    aliases: [],
    gamut: [ [0, 4], [-Infinity, +Infinity], [-Infinity, +Infinity] ],
    links: ['xyz-d50', 'lch']
  }, {
    id: 'lch',
    aliases: [],
    gamut: [ [0, 4], [0, +Infinity], [0, 360] ],
    links: ['lab']
  }, {
    id: 'xyz-d50',
    aliases: [],
    gamut: [ [0, 1], [0, 1], [0, 1] ],
    links: ['lab', 'xyz-d65', 'prophoto-rgb-linear']
  }, {
    id: 'xyz-d65',
    aliases: ['xyz'],
    gamut: [ [0, 1], [0, 1], [0, 1] ],
    links: ['xyz-d50', 'srgb-linear', 'display-p3-linear', 'a98-rgb-linear', 'rec2020-linear', 'oklab']
  }, {
    id: 'display-p3',
    aliases: [],
    gamut: [ [0, 1], [0, 1], [0, 1] ],
    links: ['display-p3-linear']
  }, {
    id: 'display-p3-linear',
    aliases: [],
    gamut: [ [0, 1], [0, 1], [0, 1] ],
    links: ['display-p3', 'xyz-d65']
  }, {
    id: 'a98-rgb',
    aliases: [],
    gamut: [ [0, 1], [0, 1], [0, 1] ],
    links: ['a98-rgb-linear']
  }, {
    id: 'a98-rgb-linear',
    aliases: [],
    gamut: [ [0, 1], [0, 1], [0, 1] ],
    links: ['a98-rgb', 'xyz-d65']
  }, {
    id: 'prophoto-rgb',
    aliases: [],
    gamut: [ [0, 1], [0, 1], [0, 1] ],
    links: ['prophoto-rgb-linear']
  }, {
    id: 'prophoto-rgb-linear',
    aliases: [],
    gamut: [ [0, 1], [0, 1], [0, 1] ],
    links: ['prophoto-rgb', 'xyz-d50']
  }, {
    id: 'rec2020',
    aliases: [],
    gamut: [ [0, 1], [0, 1], [0, 1] ],
    links: ['rec2020-linear']
  }, {
    id: 'rec2020-linear',
    aliases: [],
    gamut: [ [0, 1], [0, 1], [0, 1] ],
    links: ['rec2020', 'xyz-d65']
  }, {
    id: 'oklab',
    aliases: [],
    gamut: [ [0, 4], [-Infinity, +Infinity], [-Infinity, +Infinity] ],
    links: ['xyz-d65', 'oklch']
  }, {
    id: 'oklch',
    aliases: [],
    gamut: [ [0, 4], [0, +Infinity], [0, 360] ],
    links: ['oklab']
  }
];



export default colorSpaces;