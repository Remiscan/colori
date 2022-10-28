export interface ColorSpace {
  id: string,
  aliases: string[],
  gamut: Array<number[]>,
  gamutSpace?: string,
  links: string[],
  black?: number[],
  white?: number[]
};

const colorSpaces: ColorSpace[] = [
  {
    id: 'srgb',
    aliases: ['rgb', 'rgba'],
    gamut: [ [0, 1], [0, 1], [0, 1] ],
    links: ['srgb-linear', 'hsl'],
    black: [ 0, 0, 0 ],
    white: [ 1, 1, 1 ]
  }, {
    id: 'srgb-linear',
    aliases: [],
    gamut: [ [0, 1], [0, 1], [0, 1] ],
    links: ['srgb', 'xyz-d65'],
    black: [ 0, 0, 0 ],
    white: [ 1, 1, 1 ]
  }, {
    id: 'hsl',
    aliases: ['hsla'],
    gamut: [ [-Infinity, +Infinity], [0, 1], [0, 1] ],
    gamutSpace: 'srgb',
    links: ['srgb', 'hwb']
  }, {
    id: 'hwb',
    aliases: [],
    gamut: [ [-Infinity, +Infinity], [0, 1], [0, 1] ],
    gamutSpace: 'srgb',
    links: ['hsl']
  }, {
    id: 'lab',
    aliases: [],
    gamut: [ [0, +Infinity], [-Infinity, +Infinity], [-Infinity, +Infinity] ],
    links: ['xyz-d50', 'lch'],
    black: [ 0, 0, 0 ]
  }, {
    id: 'lch',
    aliases: [],
    gamut: [ [0, +Infinity], [0, +Infinity], [-Infinity, +Infinity] ],
    links: ['lab'],
    black: [ 0, 0, 0 ]
  }, {
    id: 'xyz-d50',
    aliases: [],
    gamut: [ [-Infinity, +Infinity], [-Infinity, +Infinity], [-Infinity, +Infinity] ],
    links: ['lab', 'xyz-d65', 'prophoto-rgb-linear']
  }, {
    id: 'xyz-d65',
    aliases: ['xyz'],
    gamut: [ [-Infinity, +Infinity], [-Infinity, +Infinity], [-Infinity, +Infinity] ],
    links: ['xyz-d50', 'srgb-linear', 'display-p3-linear', 'a98-rgb-linear', 'rec2020-linear', 'oklab']
  }, {
    id: 'display-p3',
    aliases: [],
    gamut: [ [0, 1], [0, 1], [0, 1] ],
    links: ['display-p3-linear'],
    black: [ 0, 0, 0 ],
    white: [ 1, 1, 1 ]
  }, {
    id: 'display-p3-linear',
    aliases: [],
    gamut: [ [0, 1], [0, 1], [0, 1] ],
    links: ['display-p3', 'xyz-d65'],
    black: [ 0, 0, 0 ],
    white: [ 1, 1, 1 ]
  }, {
    id: 'a98-rgb',
    aliases: [],
    gamut: [ [0, 1], [0, 1], [0, 1] ],
    links: ['a98-rgb-linear'],
    black: [ 0, 0, 0 ],
    white: [ 1, 1, 1 ]
  }, {
    id: 'a98-rgb-linear',
    aliases: [],
    gamut: [ [0, 1], [0, 1], [0, 1] ],
    links: ['a98-rgb', 'xyz-d65'],
    black: [ 0, 0, 0 ],
    white: [ 1, 1, 1 ]
  }, {
    id: 'prophoto-rgb',
    aliases: [],
    gamut: [ [0, 1], [0, 1], [0, 1] ],
    links: ['prophoto-rgb-linear'],
    black: [ 0, 0, 0 ],
    white: [ 1, 1, 1 ]
  }, {
    id: 'prophoto-rgb-linear',
    aliases: [],
    gamut: [ [0, 1], [0, 1], [0, 1] ],
    links: ['prophoto-rgb', 'xyz-d50'],
    black: [ 0, 0, 0 ],
    white: [ 1, 1, 1 ]
  }, {
    id: 'rec2020',
    aliases: [],
    gamut: [ [0, 1], [0, 1], [0, 1] ],
    links: ['rec2020-linear'],
    black: [ 0, 0, 0 ],
    white: [ 1, 1, 1 ]
  }, {
    id: 'rec2020-linear',
    aliases: [],
    gamut: [ [0, 1], [0, 1], [0, 1] ],
    links: ['rec2020', 'xyz-d65'],
    black: [ 0, 0, 0 ],
    white: [ 1, 1, 1 ]
  }, {
    id: 'oklab',
    aliases: [],
    gamut: [ [0, +Infinity], [-Infinity, +Infinity], [-Infinity, +Infinity] ],
    links: ['xyz-d65', 'oklch'],
    black: [ 0, 0, 0 ]
  }, {
    id: 'oklch',
    aliases: [],
    gamut: [ [0, +Infinity], [0, +Infinity], [-Infinity, +Infinity] ],
    links: ['oklab'],
    black: [ 0, 0, 0 ]
  }
];



export default colorSpaces;