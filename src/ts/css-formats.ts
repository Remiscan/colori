// Valid CSS values RegExp string (according to https://www.w3.org/TR/css-syntax/#typedef-number-token)
const numberExp = '(?:\\-|\\+)?(?:[0-9]+(?:\\.[0-9]+)?|\\.[0-9]+)(?:(?:e|E)(?:\\-|\\+)?[0-9]+)?';
export const cssUnitRegexps = {
  number: numberExp,
  percentage: numberExp + '%',
  numberOrPercentage: numberExp + '%?',
  angle: numberExp + '(?:deg|grad|rad|turn)?'
};



export interface CSSFormat {
  id: string,
  syntaxes: RegExp[]
};

export const cssFormats: CSSFormat[] = [
  {
    id: 'hex',
    syntaxes: [
      // #abc or #ABC
      /^#([a-fA-F0-9]{1})([a-fA-F0-9]{1})([a-fA-F0-9]{1})$/,
      // #abcd or #ABCD
      /^#([a-fA-F0-9]{1})([a-fA-F0-9]{1})([a-fA-F0-9]{1})([a-fA-F0-9]{1})$/,
      // #aabbcc or #AABBCC
      /^#([a-fA-F0-9]{2})([a-fA-F0-9]{2})([a-fA-F0-9]{2})$/,
      // #aabbccdd or #AABBCCDD
      /^#([a-fA-F0-9]{2})([a-fA-F0-9]{2})([a-fA-F0-9]{2})([a-fA-F0-9]{2})$/
    ]
  }, {
    id: 'rgb',
    syntaxes: [
      // rgb(255, 255, 255) (spaces not required)
      new RegExp(`^rgba?\\((${cssUnitRegexps.number}), ?(${cssUnitRegexps.number}), ?(${cssUnitRegexps.number})\\)$`),
      // rgb(255, 255, 255, .5) or rgb(255, 255, 255, 50%) (spaces not required)
      new RegExp(`^rgba?\\((${cssUnitRegexps.number}), ?(${cssUnitRegexps.number}), ?(${cssUnitRegexps.number}), ?(${cssUnitRegexps.numberOrPercentage})\\)$`),
      // rgb(100%, 100%, 100%) (spaces not required)
      new RegExp(`^rgba?\\((${cssUnitRegexps.percentage}), ?(${cssUnitRegexps.percentage}), ?(${cssUnitRegexps.percentage})\\)$`),
      // rgb(100%, 100%, 100%, .5) or rgb(100%, 100%, 100%, 50%) (spaces not required)
      new RegExp(`^rgba?\\((${cssUnitRegexps.percentage}), ?(${cssUnitRegexps.percentage}), ?(${cssUnitRegexps.percentage}), ?(${cssUnitRegexps.numberOrPercentage})\\)$`),
      // rgb(255 255 255)
      new RegExp(`^rgba?\\((${cssUnitRegexps.number}) (${cssUnitRegexps.number}) (${cssUnitRegexps.number})\\)$`),
      // rgba(255 255 255 / 50%) or rgba(255 255 255 / .5)
      new RegExp(`^rgba?\\((${cssUnitRegexps.number}) (${cssUnitRegexps.number}) (${cssUnitRegexps.number}) ?\\/ ?(${cssUnitRegexps.numberOrPercentage})\\)$`),
      // rgb(100% 100% 100%)
      new RegExp(`^rgba?\\((${cssUnitRegexps.percentage}) (${cssUnitRegexps.percentage}) (${cssUnitRegexps.percentage})\\)$`),
      // rgba(100% 100% 100% / 50%) or rgba(100% 100% 100% / .5)
      new RegExp(`^rgba?\\((${cssUnitRegexps.percentage}) (${cssUnitRegexps.percentage}) (${cssUnitRegexps.percentage}) ?\\/ ?(${cssUnitRegexps.numberOrPercentage})\\)$`)
    ]
  }, {
    id: 'hsl',
    syntaxes: [
      // hsl(<angle>, 100%, 100%)
      new RegExp(`^hsla?\\((${cssUnitRegexps.angle}), ?(${cssUnitRegexps.percentage}), ?(${cssUnitRegexps.percentage})\\)$`),
      // hsla(<angle>, 100%, 100%, .5) or hsla(<angle>, 100%, 100%, 50%)
      new RegExp(`^hsla?\\((${cssUnitRegexps.angle}), ?(${cssUnitRegexps.percentage}), ?(${cssUnitRegexps.percentage}), ?(${cssUnitRegexps.numberOrPercentage})\\)$`),
      // hsl(<angle> 100% 100%)
      new RegExp(`^hsla?\\((${cssUnitRegexps.angle}) (${cssUnitRegexps.percentage}) (${cssUnitRegexps.percentage})\\)$`),
      // hsla(<angle> 100% 100% / .5) or hsl(<angle> 100% 100% / 50%)
      new RegExp(`^hsla?\\((${cssUnitRegexps.angle}) (${cssUnitRegexps.percentage}) (${cssUnitRegexps.percentage}) ?\\/ ?(${cssUnitRegexps.numberOrPercentage})\\)$`)
    ]
  }, {
    id: 'hwb',
    syntaxes: [
      // hwb(<angle> 100% 100%)
      new RegExp(`^hwb\\((${cssUnitRegexps.angle}) (${cssUnitRegexps.percentage}) (${cssUnitRegexps.percentage})\\)$`),
      // hwba(<angle> 100% 100% / .5) or hsl(<angle> 100% 100% / 50%)
      new RegExp(`^hwb\\((${cssUnitRegexps.angle}) (${cssUnitRegexps.percentage}) (${cssUnitRegexps.percentage}) ?\\/ ?(${cssUnitRegexps.numberOrPercentage})\\)$`)
    ]
  }, {
    id: 'lab',
    syntaxes: [
      // lab(300% 25 40)
      new RegExp(`^lab\\((${cssUnitRegexps.percentage}) (${cssUnitRegexps.number}) (${cssUnitRegexps.number})\\)$`),
      // lab(300% 25 40 / .5)
      new RegExp(`^lab\\((${cssUnitRegexps.percentage}) (${cssUnitRegexps.number}) (${cssUnitRegexps.number}) ?\\/ ?(${cssUnitRegexps.numberOrPercentage})\\)$`)
    ]
  }, {
    id: 'lch',
    syntaxes: [
      // lch(300% 25 <angle>)
      new RegExp(`^lch\\((${cssUnitRegexps.percentage}) (${cssUnitRegexps.number}) (${cssUnitRegexps.angle})\\)$`),
      // lch(300% 25 <angle> / .5)
      new RegExp(`^lch\\((${cssUnitRegexps.percentage}) (${cssUnitRegexps.number}) (${cssUnitRegexps.angle}) ?\\/ ?(${cssUnitRegexps.numberOrPercentage})\\)$`)
    ]
  }, {
    id: 'oklab',
    syntaxes: [
      // oklab(50% -25 40)
      new RegExp(`^oklab\\((${cssUnitRegexps.percentage}) (${cssUnitRegexps.number}) (${cssUnitRegexps.number})\\)$`),
      // oklab(50% -25 40 / .5)
      new RegExp(`^oklab\\((${cssUnitRegexps.percentage}) (${cssUnitRegexps.number}) (${cssUnitRegexps.number}) ?\\/ ?(${cssUnitRegexps.numberOrPercentage})\\)$`)
    ]
  }, {
    id: 'oklch',
    syntaxes: [
      // oklch(50% 25 <angle>)
      new RegExp(`^oklch\\((${cssUnitRegexps.percentage}) (${cssUnitRegexps.number}) (${cssUnitRegexps.angle})\\)$`),
      // oklch(50% 25 <angle> / .5)
      new RegExp(`^oklch\\((${cssUnitRegexps.percentage}) (${cssUnitRegexps.number}) (${cssUnitRegexps.angle}) ?\\/ ?(${cssUnitRegexps.numberOrPercentage})\\)$`)
    ]
  }, {
    id: 'color',
    syntaxes: [
      // color(display-p3 -0.6112 1.0079 -0.2192)
      new RegExp(`^color\\(([a-zA-Z0-9_-]+?) (${cssUnitRegexps.number}) (${cssUnitRegexps.number}) (${cssUnitRegexps.number})\\)$`),
      // color(display-p3 -0.6112 1.0079 -0.2192 / .5)
      new RegExp(`^color\\(([a-zA-Z0-9_-]+?) (${cssUnitRegexps.number}) (${cssUnitRegexps.number}) (${cssUnitRegexps.number}) ?\\/ ?(${cssUnitRegexps.numberOrPercentage})\\)$`)
    ]
  }, {
    id: 'name',
    syntaxes: [
      // white or WHITE or WhiTe
      /^[A-Za-z]+$/
    ]
  }
];