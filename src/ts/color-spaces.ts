export type cssColorProperty = 'r'|'g'|'b'|'a'|'h'|'s'|'l'|'w'|'bk'|'ciel'|'ciea'|'cieb'|'ciec'|'cieh'|'okl'|'oka'|'okb'|'okc'|'okh';
export type colorProperty = cssColorProperty|'oksl'|'oklr'|'oksv'|'okv';

interface ColorSpaceBase {
  id: string,
  aliases: string[],
  properties?: colorProperty[],
  links: string[],
};

interface ColorSpaceWithGamut extends ColorSpaceBase {
  gamut: Array<number[]>,
  black?: number[],
  white?: number[]
}

interface ColorSpaceWithoutGamut extends ColorSpaceBase {
  gamutSpace: string,
}

export type ColorSpace = ColorSpaceWithGamut | ColorSpaceWithoutGamut;



const colorSpaces: ColorSpace[] = [
  {
    id: 'srgb',
    aliases: ['rgb', 'rgba'],
    properties: ['r', 'g', 'b'],
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
    properties: ['h', 's', 'l'],
    gamut: [ [-Infinity, +Infinity], [0, 1], [0, 1] ],
    gamutSpace: 'srgb',
    links: ['srgb', 'hwb']
  }, {
    id: 'hwb',
    aliases: [],
    properties: ['h', 'w', 'bk'],
    gamut: [ [-Infinity, +Infinity], [0, 1], [0, 1] ],
    gamutSpace: 'srgb',
    links: ['hsl']
  }, {
    id: 'lab',
    aliases: [],
    properties: ['ciel', 'ciea', 'cieb'],
    gamut: [ [0, +Infinity], [-Infinity, +Infinity], [-Infinity, +Infinity] ],
    links: ['xyz-d50', 'lch'],
    black: [ 0, 0, 0 ]
  }, {
    id: 'lch',
    aliases: [],
    properties: ['ciel', 'ciec', 'cieh'],
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
    properties: ['okl', 'oka', 'okb'],
    gamut: [ [0, +Infinity], [-Infinity, +Infinity], [-Infinity, +Infinity] ],
    links: ['xyz-d65', 'oklch', 'okhsl', 'okhsv', 'oklrab'],
    black: [ 0, 0, 0 ]
  }, {
    id: 'oklch',
    aliases: [],
    properties: ['okl', 'okc', 'okh'],
    gamut: [ [0, +Infinity], [0, +Infinity], [-Infinity, +Infinity] ],
    links: ['oklab', 'oklrch'],
    black: [ 0, 0, 0 ]
  },{
    id: 'oklrab',
    aliases: [],
    properties: ['oklr', 'oka', 'okb'],
    gamut: [ [0, +Infinity], [-Infinity, +Infinity], [-Infinity, +Infinity] ],
    links: ['oklab'],
    black: [ 0, 0, 0 ]
  }, {
    id: 'oklrch',
    aliases: [],
    properties: ['oklr', 'okc', 'okh'],
    gamut: [ [0, +Infinity], [0, +Infinity], [-Infinity, +Infinity] ],
    links: ['oklch'],
    black: [ 0, 0, 0 ]
  }, {
    id: 'okhsl',
    aliases: [],
    properties: ['okh', 'oksl', 'oklr'],
    gamut: [ [-Infinity, +Infinity], [0, 1], [0, 1] ],
    links: ['oklab'],
    black: [ 0, 0, 0 ],
    white: [ 0, 0, 1 ]
  }, {
    id: 'okhsv',
    aliases: [],
    properties: ['okh', 'oksv', 'okv'],
    gamut: [ [-Infinity, +Infinity], [0, 1], [0, 1] ],
    links: ['oklab'],
    black: [ 0, 0, 0 ],
    white: [ 0, 0, 1 ]
  }
];



export default colorSpaces;